<?php

namespace App\Jobs;

use App\Contracts\TrustmedisServiceInterface;
use App\Models\Visit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SyncVisitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Visit $visit)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(TrustmedisServiceInterface $trustmedisService): void
    {
        $success = $trustmedisService->syncVisit($this->visit);

        if ($success) {
            Log::info("SyncVisitJob: Visit #{$this->visit->id} successfully synced to Trustmedis.");

            /*
             * =========================================================================
             * AUTO-DELETE LOCAL PHOTO CLEANUP (SIMULASI / PRODUCTION HOOK)
             * =========================================================================
             * Once Trustmedis cloud storage confirms ingestion of patient clinical
             * before/after photos, local disk storage can be freed to maintain HIPAA/
             * GDPR compliance and conserve VPS storage capacity.
             *
             * Uncomment the following block when Trustmedis production bucket is active:
             *
             * if ($this->visit->photo_before_path && Storage::disk('public')->exists($this->visit->photo_before_path)) {
             *     Storage::disk('public')->delete($this->visit->photo_before_path);
             *     Log::info("Cleaned up local photo_before for visit #{$this->visit->id}");
             * }
             *
             * if ($this->visit->photo_after_path && Storage::disk('public')->exists($this->visit->photo_after_path)) {
             *     Storage::disk('public')->delete($this->visit->photo_after_path);
             *     Log::info("Cleaned up local photo_after for visit #{$this->visit->id}");
             * }
             * =========================================================================
             */
        } else {
            $this->visit->update(['trustmedis_sync_status' => 'failed']);
            Log::error("SyncVisitJob: Failed to sync visit #{$this->visit->id} to Trustmedis.");
        }
    }
}
