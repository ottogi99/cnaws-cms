<?php

namespace App\Http\Livewire;

use App\Models\Management;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        return view('livewire.dashboard', [
            // search --> AppServiceProvider boot() defined
            // 'management' => Management::search('title', $this->search)->paginate(10),
            'management' => Management::where('input_year', 'like', '%'.$this->search.'%')->paginate(10),
        ]);
    }
}
