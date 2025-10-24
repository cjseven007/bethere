<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ScanAttendance extends Component
{
    public $threshold;

    public function mount()
    {
        $this->threshold = optional(DB::table('app_settings')->first())->threshold ?? 0.40;
    }

    public function render()
    {
        return view('livewire.scan-attendance', [
            'threshold' => $this->threshold,
        ]);
    }
}