<?php

namespace App\Http\Livewire;

use App\Models\Management;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.dashboard', [
            'management' => Management::paginate(10),
        ]);
    }
}
