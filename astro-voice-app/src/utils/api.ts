import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const API_BASE_URL = 'https://astroauraa.com';

export const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Automatically inject sanctum token
api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('astro_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export interface UserProfile {
  id: number;
  name: string;
  email: string;
  phone_number?: string;
  wallet_balance: number;
}

export interface Astrologer {
  id: number;
  display_name: string;
  call_price: number;
  is_call_online: number;
  profile_image?: string;
  specializations?: string[];
  languages?: string[];
}

export const apiService = {
  login: async (email: string, password: string): Promise<string> => {
    const res = await api.post('/api/login', { email, password });
    const token = res.data.access_token;
    await AsyncStorage.setItem('astro_token', token);
    return token;
  },

  logout: async (): Promise<void> => {
    await api.post('/api/logout');
    await AsyncStorage.removeItem('astro_token');
  },

  getProfile: async (): Promise<UserProfile> => {
    const res = await api.get('/api/profile');
    // Map response structure
    const data = res.data.data || res.data;
    return {
      id: data.id,
      name: data.name,
      email: data.email,
      phone_number: data.phone_number,
      wallet_balance: parseFloat(data.wallet?.balance || '0'),
    };
  },

  getAstrologers: async (): Promise<Astrologer[]> => {
    const res = await api.get('/api/astrologers');
    const items = res.data.data || [];
    return items.map((item: any) => ({
      id: item.id,
      display_name: item.display_name,
      call_price: parseFloat(item.chat_price || item.call_price || '0'),
      is_call_online: item.is_call_online === true || item.is_call_online === 1 ? 1 : 0,
      profile_image: item.profile_image,
      specializations: (item.specializations || []).map((s: any) => s.name || s),
      languages: (item.languages || []).map((l: any) => l.name || l),
    }));
  },

  requestCall: async (astrologer_id: number): Promise<{ call_request_id: number }> => {
    const res = await api.post('/api/consultations/request-call', { astrologer_id });
    return {
      call_request_id: res.data.data?.call_request_id || res.data.call_request_id,
    };
  },

  getVoiceToken: async (): Promise<{ token: string; identity: string }> => {
    const res = await api.get('/api/twilio/voice-token');
    return res.data;
  },

  getImageUrl: (path?: string): string => {
    if (!path) {
      return 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=60';
    }
    if (path.startsWith('http://') || path.startsWith('https://')) {
      return path;
    }
    return `${API_BASE_URL}/storage/${path}`;
  },
};
