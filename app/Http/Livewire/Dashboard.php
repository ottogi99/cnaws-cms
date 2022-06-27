<?php

namespace App\Http\Livewire;

use App\Models\Management;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    // public $search = '';
    public $sortField = 'year';
    public $sortDirection = 'desc';
    public $showEditModal = false;
    public $showFilters = false;
    public $selected = [];
    public $filters = [
        'search' => '',
        'year' => '' ,
        'initiate' => null,
        'deadline' => null,
    ];

    public $editingYear = null;

    public Management $editing;
    public $initiate = null;
    public $deadline = null;

    protected $queryString = ['sortField', 'sortDirection'];

    // Validation 속성
    // protected $rules = [
    //     'editing.year' => 'required|date',
    //     'editing.initiate' => 'required|date',
    //     'editing.deadline' => 'required|date',
    // ];

    // Validation 함수
    public function rules()
    {
        return  [
            'editing.year' => [
                'required',
                'date_format:Y',
                'unique:management,year,'.$this->editingYear.',year'
                // Rule::unique('management')->ignore('1999', 'editing.year')
            ],
            'editing.initiate' => 'required|date',
            'editing.deadline' => 'required|date',
            'editing.initiate_for_editing' => 'required',
            'editing.deadline_for_editing' => 'required',
        ];
    }

    public function mount()
    {
        // $this->inputStartDate = now()->toDateString();
        // $this->inputEndDate = now()->toDateString();
        // $this->editing = Management::first();
        $this->editing = $this->makeBlankManagement();
    }

    public function updatedFilters($field)
    {
        // 필터가 변경될 때마다 페이지 번호를 초기화
        $this->resetPage();
    }

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function exportSelected()
    {
        return response()->streamDownload(function () {
            echo Management::whereKey($this->selected)->toCsv();
        }, 'management.csv');
    }

    public function deleteSelected()
    {
        $management = Management::whereKey($this->selected);

        $management->delete();
    }

    public function makeBlankManagement()
    {
        return Management::make(['initiate' => now(), 'deadline' => now()]);
    }

    public function create()
    {
        if ($this->editing->getKey())  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankManagement();

        $this->editingYear = null;
        $this->showEditModal = true;
    }

    public function edit(Management $management)
    {
        if ($this->editing->isNot($management)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editing = $management;
            $this->editingYear = $this->editing->year;
        }
        // $this->inputStartDate = $management->start_date;
        // $this->inputEndDate = $management->end_date;

        $this->showEditModal = true;
    }

    public function save()
    {
        // $this->editing->initiate = Carbon::parse($this->inputStartDate);
        // $this->editing->deadline = Carbon::parse($this->inputEndDate);

        // dd($this->editing);
        $this->validate();
        $this->editing->save();
        $this->showEditModal = false;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function render()
    {
        return view('livewire.dashboard', [
            // search --> AppServiceProvider boot() defined
            // 'management' => Management::search('title', $this->search)->paginate(10),
            'management' => Management::query()
                // ->when($this->filters['year'], function ($query,  $value) {
                //     $query->where('year', $value);
                // })
                ->when($this->filters['year'], fn ($query, $year) => $query->where('year', $year))
                ->when($this->filters['initiate'], fn ($query, $initiate) => $query->where('initiate', Carbon::parse($initiate)))
                ->when($this->filters['deadline'], fn ($query, $deadline) => $query->where('deadline', Carbon::parse($deadline)))
                ->when($this->filters['search'], fn ($query, $search) => $query->where('year', 'like', '%'.$this->search.'%'))
                // ->where('year', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
        ]);
    }
}
