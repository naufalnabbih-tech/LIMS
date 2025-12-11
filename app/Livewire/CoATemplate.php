<?php

namespace App\Livewire;

use Livewire\Component;

class CoATemplate extends Component
{
    public function render()
    {
        return view('livewire.coa-template', [
            'documentNumber' => null,
            'material' => null,
            'batchLot' => null,
            'inspectionDate' => null,
            'releaseDate' => null,
            'netWeight' => null,
            'poNo' => null,
            'customFields' => [],
            'tests' => [],
            'approverQRSignature' => null,
            'approver' => null,
            'approverRole' => null,
        ]);
    }
}
