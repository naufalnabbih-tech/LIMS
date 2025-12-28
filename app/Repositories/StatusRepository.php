<?php

namespace App\Repositories;

use App\Models\Status;

class StatusRepository
{
    /**
     * Find status by name
     *
     * @param string $name
     * @return Status|null
     */
    public function findByName(string $name): ?Status
    {
        return Status::where('name', $name)->first();
    }

    /**
     * Get analysis completed status ID
     *
     * @return int|null
     */
    public function getAnalysisCompletedStatusId(): ?int
    {
        $status = $this->findByName('analysis_completed');
        return $status?->id;
    }
}
