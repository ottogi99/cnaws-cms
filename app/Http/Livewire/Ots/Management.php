<?php

namespace App\Http\Livewire\Ots;

use App\Models\Management as ModelsManagement;
use Livewire\Component;

class Management extends Component
{
    public $inputYear;
    public $inputStartDate;
    public $inputEndDate;

    public function create () {}
    public function read () {
        return ModelsManagement::all();
    }
    public function update () {}
    public function delete () {}

    public function render()
    {
        return view('livewire.ots.management', [
            'data' => $this->read(),
        ]);
    }
}
