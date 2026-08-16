import { Voice } from '@twilio/voice-react-native-sdk';
import { Platform } from 'react-native';

export type CallState = 'disconnected' | 'connecting' | 'ringing' | 'connected' | 'reconnecting';

export interface CallListener {
  onStateChange: (state: CallState, error?: string) => void;
  onTimerTick?: (seconds: number) => void;
}

class TwilioVoiceManager {
  private activeCall: any = null;
  private currentListener: CallListener | null = null;
  private callTimer: any = null;
  private durationSeconds = 0;

  constructor() {
    this.setupListeners();
  }

  private setupListeners() {
    // Listen for call invites or incoming calls
    Voice.on(Voice.Event.CallInvite, (invite) => {
      console.log('Incoming call invite received:', invite);
      // Auto accept or trigger notification if registered.
      // For this user app, we mainly initiate outgoing calls, but we can accept if needed.
    });

    Voice.on(Voice.Event.Error, (error) => {
      console.error('Twilio Voice global error:', error);
      if (this.currentListener) {
        this.currentListener.onStateChange('disconnected', error.message);
      }
    });
  }

  public register(token: string): Promise<void> {
    console.log('Registering Voice token with Twilio...');
    // We register the device to receive calls. If not registered, we can still make outgoing calls.
    return Voice.register(token);
  }

  public async makeCall(token: string, callRequestId: number, listener: CallListener): Promise<void> {
    this.currentListener = listener;
    this.durationSeconds = 0;

    try {
      console.log('Initiating Twilio connect with call_request_id:', callRequestId);
      listener.onStateChange('connecting');

      const call = await Voice.connect(token, {
        params: {
          call_request_id: String(callRequestId),
          Platform: Platform.OS,
        },
      });

      this.activeCall = call;

      call.on(Voice.CallEvent.Ringing, () => {
        console.log('Twilio Call Ringing...');
        listener.onStateChange('ringing');
      });

      call.on(Voice.CallEvent.Connected, () => {
        console.log('Twilio Call Connected!');
        listener.onStateChange('connected');
        this.startTimer();
      });

      call.on(Voice.CallEvent.Disconnected, (error) => {
        console.log('Twilio Call Disconnected:', error);
        this.stopTimer();
        listener.onStateChange('disconnected', error?.message);
        this.activeCall = null;
        this.currentListener = null;
      });

      call.on(Voice.CallEvent.Reconnecting, () => {
        console.log('Twilio Call Reconnecting...');
        listener.onStateChange('reconnecting');
      });

    } catch (err: any) {
      console.error('Error placing call:', err);
      listener.onStateChange('disconnected', err.message || 'Failed to place call');
      this.activeCall = null;
      this.currentListener = null;
    }
  }

  public disconnect(): void {
    if (this.activeCall) {
      console.log('Disconnecting call...');
      this.activeCall.disconnect();
      this.activeCall = null;
    }
    this.stopTimer();
  }

  private startTimer() {
    this.stopTimer();
    this.callTimer = setInterval(() => {
      this.durationSeconds += 1;
      if (this.currentListener && this.currentListener.onTimerTick) {
        this.currentListener.onTimerTick(this.durationSeconds);
      }
    }, 1000);
  }

  private stopTimer() {
    if (this.callTimer) {
      clearInterval(this.callTimer);
      this.callTimer = null;
    }
  }

  public getDurationMinutes(): number {
    return Math.max(1, Math.ceil(this.durationSeconds / 60));
  }
}

export const voiceManager = new TwilioVoiceManager();
