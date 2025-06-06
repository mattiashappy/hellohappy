<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\TagEmailSequence;
use App\Models\SubscriberSequence;

class EnrollTaggedSubscribers extends Command
{
    protected $signature = 'sequences:enroll';
    protected $description = 'Enroll tagged subscribers into email sequences';

    public function handle()
    {
        $this->info('Processing tag-based sequence enrollments');

        TagEmailSequence::with('emailSequence')->chunk(100, function ($mappings) {
            foreach ($mappings as $mapping) {
                if (!$mapping->emailSequence) {
                    continue;
                }

                $subscriberIds = DB::table('sendportal_tag_subscriber')
                    ->where('tag_id', $mapping->tag_id)
                    ->pluck('subscriber_id');

                foreach ($subscriberIds as $subscriberId) {
                    $exists = SubscriberSequence::where('subscriber_id', $subscriberId)
                        ->where('email_sequence_id', $mapping->email_sequence_id)
                        ->exists();

                    if (!$exists) {
                        $mapping->emailSequence->addSubscriber($subscriberId);
                        $this->info("Enrolled subscriber {$subscriberId} in sequence {$mapping->email_sequence_id}");
                    }
                }
            }
        });

        $this->info('Enrollment processing complete');
    }
}
