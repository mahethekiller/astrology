<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChatRequest;
use App\Models\CallRequest;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EndStaleConsultations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consultations:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up and bill stale or abandoned chat/call requests.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of stale consultations...');

        $this->cleanupChats();
        $this->cleanupCalls();

        $this->info('Cleanup completed.');
    }

    private function cleanupChats()
    {
        // 30 minutes of inactivity
        $staleTime = now()->subMinutes(30);

        // Find chats that are accepted or pending and haven't been updated in 30 minutes
        $staleChats = ChatRequest::with(['user.wallet', 'astrologer.user.wallet'])
            ->whereIn('status', ['accepted', 'pending', 'active']) // Adjust statuses as per your DB
            ->where('updated_at', '<', $staleTime)
            ->get();

        foreach ($staleChats as $chat) {
            if ($chat->status === 'pending') {
                // If it never started, just cancel it
                $chat->update(['status' => 'expired']);
                $this->info("Chat #{$chat->id} expired (was pending).");
                continue;
            }

            // It was active/accepted. Let's bill them for 30 minutes.
            $durationMinutes = 30;
            $pricePerMinute = $chat->astrologer->chat_price ?? 0;
            $commissionPercent = $chat->astrologer->chat_commission_percentage ?? \App\Models\Setting::getValue('global_chat_commission', 0);

            $totalCost = $durationMinutes * $pricePerMinute;
            $commission = $totalCost * ($commissionPercent / 100);
            $earnings = $totalCost - $commission;

            $userWallet = $chat->user->wallet;
            $astroWallet = $chat->astrologer->user->wallet;

            DB::beginTransaction();
            try {
                if ($userWallet && $totalCost > 0) {
                    $userWallet->balance -= $totalCost;
                    $userWallet->save();

                    WalletTransaction::create([
                        'wallet_id' => $userWallet->id,
                        'amount' => $totalCost,
                        'type' => 'debit',
                        'description' => "Abandoned Chat consultation with {$chat->astrologer->display_name} ({$durationMinutes} mins)"
                    ]);
                }

                if ($astroWallet && $earnings > 0) {
                    $astroWallet->balance += $earnings;
                    $astroWallet->save();

                    WalletTransaction::create([
                        'wallet_id' => $astroWallet->id,
                        'amount' => $earnings,
                        'type' => 'credit',
                        'description' => "Earnings from abandoned chat session with {$chat->user->name} ({$durationMinutes} mins)"
                    ]);
                }

                $chat->update([
                    'status' => 'completed', // or 'expired'
                    'chat_duration' => $durationMinutes,
                    'chat_cost' => $totalCost,
                    'commission_amount' => $commission,
                    'astrologer_earnings' => $earnings,
                ]);

                DB::commit();
                $this->info("Chat #{$chat->id} processed and billed for 30 mins.");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to clean up chat #{$chat->id}: " . $e->getMessage());
                $this->error("Failed to clean up chat #{$chat->id}");
            }
        }
    }

    private function cleanupCalls()
    {
        // 60 minutes fallback for calls that Twilio webhook somehow missed
        $staleTime = now()->subMinutes(60);

        $staleCalls = CallRequest::whereIn('call_status', ['initiated', 'ringing', 'in-progress'])
            ->where('updated_at', '<', $staleTime)
            ->get();

        foreach ($staleCalls as $call) {
            // Since we don't know the true duration because the webhook failed, 
            // the safest bet is to just mark it as failed/canceled without billing,
            // or rely on a default fallback. Let's just cancel it to prevent it hanging.
            $call->update([
                'call_status' => 'failed',
                'end_time' => now()
            ]);
            $this->info("Call #{$call->id} marked as failed due to missing webhook.");
        }
    }
}
