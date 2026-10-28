<?php

namespace App\Contracts;

use App\Models\Visit;

interface TrustmedisServiceInterface
{
    /**
     * Synchronize visit, patient, doctor, and treatment record to Trustmedis EHR.
     *
     * @param Visit $visit
     * @return bool
     */
    public function syncVisit(Visit $visit): bool;
}
