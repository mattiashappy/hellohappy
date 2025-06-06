<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriberSequence;
use Sendportal\Base\Models\Campaign;
use Carbon\Carbon;

class ProcessEmailSequences extends Command
{
    protected $signature = 'sequences:process';
    protected $description = 'Process email sequences and send next emails';

    public function handle()
    {
        // Get all subscriber sequences ready to send
        $readyToSend = SubscriberSequence::where('status', 'active')
            ->where('next_send_at', '<=', Carbon::now())
            ->with(['emailSequence.emails', 'subscriber'])
            ->get();

        $this->info("Found {$readyToSend->count()} sequences ready to process");

        foreach ($readyToSend as $subscriberSequence) {
            try {
                $this->sendNextEmail($subscriberSequence);
                $this->info("Processed sequence for subscriber {$subscriberSequence->subscriber_id}");
            } catch (\Exception $e) {
                $this->error("Failed to process sequence {$subscriberSequence->id}: " . $e->getMessage());
                \Log::error("Sequence processing failed", [
                    'subscriber_sequence_id' => $subscriberSequence->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Completed processing sequences");
    }

    private function sendNextEmail($subscriberSequence)
    {
        $sequence = $subscriberSequence->emailSequence;
        $emails = $sequence->emails;
        $currentStep = $subscriberSequence->current_step;

        // Get the email to send (current step)
        $emailToSend = $emails->where('send_order', $currentStep)->first();
        
        if (!$emailToSend) {
            // Sequence complete
            $subscriberSequence->update([
                'status' => 'completed',
                'completed_at' => Carbon::now()
            ]);
            $this->info("Sequence completed for subscriber {$subscriberSequence->subscriber_id}");
            return;
        }

        // Create a campaign using existing SendPortal functionality
        $campaign = Campaign::create([
            'workspace_id' => $sequence->workspace_id,
            'name' => $sequence->name . " - Email {$currentStep} - " . $subscriberSequence->subscriber->email,
            'subject' => $emailToSend->subject,
            'template_id' => $emailToSend->template_id,
            'status_id' => 1, // Draft status initially
            'send_to_all' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Add just this subscriber to the campaign
        $campaign->subscribers()->attach($subscriberSequence->subscriber_id);

        // Mark campaign as sent (this triggers SendPortal's sending mechanism)
        $campaign->update(['status_id' => 2]); // Sent status

        // Update subscriber sequence for next email
        $nextEmail = $emails->where('send_order', $currentStep + 1)->first();
        
        if ($nextEmail) {
            $subscriberSequence->update([
                'current_step' => $currentStep + 1,
                'next_send_at' => Carbon::now()->addDays($nextEmail->delay_days)
            ]);
        } else {
            $subscriberSequence->update([
                'status' => 'completed',
                'completed_at' => Carbon::now()
            ]);
        }
    }
}