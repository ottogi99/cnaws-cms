<?php

namespace App\Http\Livewire\Ots;

use App\Csv;
use App\Models\Management;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class ImportManagement extends Component
{
    use WithFileUploads;

    public $showModal = true;
    public $upload;
    public $columns;
    public $fieldColumnMap = [
        'year' => '',
        'initiate' => '',
        'deadline' => '',
    ];

    public function updatingUpload($value)
    {
        Validator::make(
            ['upload' => $value],
            ['upload' => 'required|mimes:txt,csv'],
        )->validate();
    }

    protected $rules = [
        'fieldColumnMap.year' => 'required',
        'fieldColumnMap.initiate' => 'required',
        'fieldColumnMap.deadline' => 'required',
    ];

    protected $messages = [
        'fieldColumnMap.year.required' => '년도값을 입력하세요.',
        'fieldColumnMap.initiate.required' => '개시일을 입력하세요.',
        'fieldColumnMap.deadline.required' => '마감일을 입력하세요.',
    ];

    // 동작 안됨.
    protected $validationAttributes = [
        'fieldColumnMap.year' => '년도',
        'fieldColumnMap.initiate' => '개시일',
        'fieldColumnMap.deadline' => '마감일',
    ];

    public function updatedUpload()
    {
        $this->columns = Csv::from($this->upload)->columns();

        // $this->guessWhichColumnsMapToWhichFields();
    }

    public function import ()
    {
        $result = $this->validate();

        $importCount = 0;

        Csv::from($this->upload)
            ->eachRow(function ($row) use (&$importCount) {
                Management::create(
                    $this->extractFieldsFromRow($row)
                );

                $importCount++;
            });

        // $this->showModal = false;

        $this->reset();

        $this->emit('refreshManagement');

        // $this->dispatchBrowserEvent('notify', 'Imported '.$importCount.' management!');

        $this->notify('Imported '.$importCount.' management!');
    }

    public function extractFieldsFromRow($row)
    {
        $attributes = collect($this->fieldColumnMap)
            ->filter()
            ->mapWithKeys(function ($heading, $field) use ($row) {
                return [$field => $row[$heading]];
            })
            ->toArray();

        return $attributes + ['year' =>  Carbon::now()->year, 'initiate' => now(), 'deadline' => now()];
    }

    public function guessWhichColumnsMapToWhichFields()
    {
        $guesses = [
            'year' => ['year'],
            'initiate' => ['initiate'],
            'deadline' => ['deadline'],
        ];

        foreach ($this->columns as $column) {
            $match = collect($guesses)->search(fn($options) => in_array(strtolower($column), $options));    // $options === $guesses
            if ($match) $this->fieldColumnMap[$match] = $column;
        }
    }

    // public function extractFieldsFromRow($row)
    // {
    //     $attributes = collect($this->fieldCoilumnMap)
    //         ->filter()
    //         ->mapWithKeys(function ($heading, $field) use ($row) {
    //             return [$field => $row[$heading]];
    //         })
    //         ->toArray();

    //     return $attributes + ['status' => 'success', 'date_for_editing' => now()];
    // }

    // public function render()
    // {
    //     return view('livewire.ots.import-management');
    // }
}
