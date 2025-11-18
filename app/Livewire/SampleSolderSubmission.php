<?php

namespace App\Livewire;

use App\Models\Status;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class SampleSolderSubmission extends Component
{
    public function render()
    {
        return view('livewire.sample-solder-submission');
    }
}
