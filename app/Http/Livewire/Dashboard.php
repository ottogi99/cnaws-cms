<?php

namespace App\Http\Livewire;

use App\Models\Management;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'management' => Management::all(),
        ]);
    }
}
