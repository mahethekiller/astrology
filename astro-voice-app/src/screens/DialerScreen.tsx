import React, { useEffect, useState } from 'react';
import {
  StyleSheet,
  Text,
  View,
  FlatList,
  Image,
  TouchableOpacity,
  ActivityIndicator,
  Alert,
  SafeAreaView,
  Modal,
} from 'react-native';
import { Phone, PhoneOff, Wallet, RefreshCw, LogOut } from 'lucide-react-native';
import { apiService, Astrologer, UserProfile } from '../utils/api';
import { voiceManager, CallState } from '../utils/voice';

interface DialerScreenProps {
  onLogout: () => void;
}

export default function DialerScreen({ onLogout }: DialerScreenProps) {
  const [profile, setProfile] = useState<UserProfile | null>(null);
  const [astrologers, setAstrologers] = useState<Astrologer[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  // Call state
  const [activeCallId, setActiveCallId] = useState<number | null>(null);
  const [callingAstrologer, setCallingAstrologer] = useState<Astrologer | null>(null);
  const [callState, setCallState] = useState<CallState>('disconnected');
  const [callDuration, setCallDuration] = useState('00:00');
  const [isCallingModalVisible, setIsCallingModalVisible] = useState(false);

  const loadData = async () => {
    try {
      setLoading(true);
      const [p, list] = await Promise.all([
        apiService.getProfile(),
        apiService.getAstrologers(),
      ]);
      setProfile(p);
      setAstrologers(list);
    } catch (err: any) {
      console.error(err);
      Alert.alert('Error loading data', 'Could not sync astrologers list.');
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const handleStartCall = async (astrologer: Astrologer) => {
    if (!profile) return;

    if (profile.wallet_balance < astrologer.call_price) {
      Alert.alert('Insufficient Balance', 'Please recharge your wallet balance first.');
      return;
    }

    try {
      setCallingAstrologer(astrologer);
      setIsCallingModalVisible(true);
      setCallState('connecting');
      setCallDuration('00:00');

      // 1. Log request to backend
      console.log('Initiating backend call request for astrologer id:', astrologer.id);
      const { call_request_id } = await apiService.requestCall(astrologer.id);
      setActiveCallId(call_request_id);

      // 2. Fetch Twilio token
      console.log('Fetching Twilio voice token...');
      const { token } = await apiService.getVoiceToken();

      // 3. Trigger Twilio Connect
      await voiceManager.makeCall(token, call_request_id, {
        onStateChange: (state, error) => {
          setCallState(state);
          if (error) {
            Alert.alert('Call Error', error);
            handleDisconnect();
          }
          if (state === 'disconnected') {
            handleDisconnect();
          }
        },
        onTimerTick: (seconds) => {
          const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
          const secs = String(seconds % 60).padStart(2, '0');
          setCallDuration(`${mins}:${secs}`);
        },
      });

    } catch (err: any) {
      console.error(err);
      const msg = err.response?.data?.message || err.message || 'Failed to place call';
      Alert.alert('Call Failed', msg);
      handleDisconnect();
    }
  };

  const handleDisconnect = async () => {
    voiceManager.disconnect();

    // Trigger backend endCall to compute duration and process wallet deduction
    if (activeCallId) {
      const minutes = voiceManager.getDurationMinutes();
      console.log(`Ending call ID ${activeCallId} with backend, billed duration: ${minutes} mins`);
      try {
        await apiService.api.post('/api/consultations/end-call', {
          call_request_id: activeCallId,
          duration_minutes: minutes,
        });
      } catch (err) {
        console.error('Error sending endCall callback:', err);
      }
    }

    setActiveCallId(null);
    setCallingAstrologer(null);
    setCallState('disconnected');
    setIsCallingModalVisible(false);
    loadData(); // Reload balance/data
  };

  const handleLogout = async () => {
    try {
      await apiService.logout();
      onLogout();
    } catch (err) {
      console.error(err);
      onLogout();
    }
  };

  const renderAstrologer = ({ item }: { item: Astrologer }) => {
    const isOnline = item.is_call_online === 1;

    return (
      <View style={styles.card}>
        <Image
          source={{ uri: apiService.getImageUrl(item.profile_image) }}
          style={styles.avatar}
        />
        <View style={styles.info}>
          <Text style={styles.name}>{item.display_name}</Text>
          <Text style={styles.specializations}>
            {item.specializations?.join(', ') || 'Astrology'}
          </Text>
          <Text style={styles.languages}>{item.languages?.join(', ') || 'English, Hindi'}</Text>
          <Text style={styles.rate}>₹{item.call_price}/min</Text>
        </View>

        <TouchableOpacity
          style={[styles.callButton, !isOnline && styles.offlineButton]}
          disabled={!isOnline}
          onPress={() => handleStartCall(item)}
        >
          <Phone size={20} color={isOnline ? '#0a0b10' : '#888'} />
          <Text style={[styles.callButtonText, !isOnline && styles.offlineButtonText]}>
            {isOnline ? 'Call' : 'Offline'}
          </Text>
        </TouchableOpacity>
      </View>
    );
  };

  return (
    <SafeAreaView style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <View>
          <Text style={styles.welcome}>Welcome,</Text>
          <Text style={styles.userName}>{profile?.name || 'User'}</Text>
        </View>
        <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
          <LogOut size={20} color="#FFD700" />
        </TouchableOpacity>
      </View>

      {/* Wallet Balance Info */}
      <View style={styles.walletBar}>
        <View style={styles.walletLeft}>
          <Wallet size={20} color="#FFD700" />
          <Text style={styles.walletText}>Balance: ₹{profile?.wallet_balance?.toFixed(2) || '0.00'}</Text>
        </View>
        <TouchableOpacity onPress={loadData} disabled={refreshing}>
          <RefreshCw size={18} color="#cdd1e4" />
        </TouchableOpacity>
      </View>

      {/* Astrologers List */}
      {loading && !refreshing ? (
        <View style={styles.loading}>
          <ActivityIndicator size="large" color="#FFD700" />
        </View>
      ) : (
        <FlatList
          data={astrologers}
          keyExtractor={(item) => String(item.id)}
          renderItem={renderAstrologer}
          contentContainerStyle={styles.list}
          refreshing={refreshing}
          onRefresh={() => {
            setRefreshing(true);
            loadData();
          }}
          ListEmptyComponent={
            <Text style={styles.emptyText}>No astrologers currently available.</Text>
          }
        />
      )}

      {/* Call Management Modal */}
      <Modal
        visible={isCallingModalVisible}
        transparent
        animationType="fade"
        onRequestClose={handleDisconnect}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Image
              source={{ uri: apiService.getImageUrl(callingAstrologer?.profile_image) }}
              style={styles.modalAvatar}
            />
            <Text style={styles.modalName}>{callingAstrologer?.display_name || 'Astrologer'}</Text>

            <Text style={styles.modalState}>
              {callState.toUpperCase()}...
            </Text>

            {callState === 'connected' && (
              <Text style={styles.modalTimer}>{callDuration}</Text>
            )}

            <TouchableOpacity style={styles.hangupButton} onPress={handleDisconnect}>
              <PhoneOff size={28} color="#fff" />
            </TouchableOpacity>
          </View>
        </View>
      </Modal>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#0a0b10',
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingHorizontal: 20,
    paddingVertical: 16,
  },
  welcome: {
    fontSize: 14,
    color: '#8b8f9e',
  },
  userName: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#fff',
  },
  logoutButton: {
    padding: 10,
    borderRadius: 12,
    backgroundColor: '#131520',
    borderWidth: 1,
    borderColor: '#242736',
  },
  walletBar: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    backgroundColor: '#131520',
    marginHorizontal: 20,
    marginBottom: 16,
    padding: 16,
    borderRadius: 16,
    borderWidth: 1,
    borderColor: '#242736',
  },
  walletLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  walletText: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#fff',
    marginLeft: 10,
  },
  loading: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  list: {
    paddingHorizontal: 20,
    paddingBottom: 24,
  },
  card: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#131520',
    borderRadius: 20,
    padding: 16,
    marginBottom: 14,
    borderWidth: 1,
    borderColor: '#1e2132',
  },
  avatar: {
    width: 65,
    height: 65,
    borderRadius: 32.5,
    backgroundColor: '#1b1d2a',
  },
  info: {
    flex: 1,
    marginLeft: 16,
    marginRight: 10,
  },
  name: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 2,
  },
  specializations: {
    fontSize: 12,
    color: '#8b8f9e',
    marginBottom: 2,
  },
  languages: {
    fontSize: 12,
    color: '#656877',
    marginBottom: 4,
  },
  rate: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#FFD700',
  },
  callButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#FFD700',
    paddingVertical: 10,
    paddingHorizontal: 16,
    borderRadius: 20,
  },
  callButtonText: {
    color: '#0a0b10',
    fontSize: 14,
    fontWeight: 'bold',
    marginLeft: 6,
  },
  offlineButton: {
    backgroundColor: '#1b1d2a',
  },
  offlineButtonText: {
    color: '#888',
    marginLeft: 0,
  },
  emptyText: {
    textAlign: 'center',
    color: '#8b8f9e',
    marginTop: 40,
    fontSize: 16,
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(5, 6, 10, 0.9)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  modalContent: {
    backgroundColor: '#131520',
    borderRadius: 24,
    padding: 32,
    width: '80%',
    maxWidth: 340,
    alignItems: 'center',
    borderWidth: 1,
    borderColor: '#242736',
  },
  modalAvatar: {
    width: 100,
    height: 100,
    borderRadius: 50,
    marginBottom: 16,
    backgroundColor: '#1b1d2a',
  },
  modalName: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 8,
  },
  modalState: {
    fontSize: 14,
    color: '#FFD700',
    fontWeight: 'bold',
    letterSpacing: 2,
    marginBottom: 24,
  },
  modalTimer: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#fff',
    marginBottom: 32,
  },
  hangupButton: {
    width: 60,
    height: 60,
    borderRadius: 30,
    backgroundColor: '#ff3b30',
    justifyContent: 'center',
    alignItems: 'center',
    shadowColor: '#ff3b30',
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.4,
    shadowRadius: 10,
    elevation: 4,
  },
});
