<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\City;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Cities extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'search' => '',
    ];

    public City $editing;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        return  [
            'editing.name' => [
                'required',
            ],
            'editing.sequence' => 'required',
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankCity();
    }

    public function updatedFilters($field)
    {
        // 필터가 변경될 때마다 페이지 번호를 초기화
        $this->resetPage();
    }

    public function deleteSelected()
    {
        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;
    }

    public function makeBlankCity()
    {
        return City::make(['name' => '', 'sequence' => 0]);
    }

    public function create()
    {
        if ($this->editing->getKey())  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankCity();

        $this->editingYear = null;
        $this->showEditModal = true;
    }

    public function edit(City $city)
    {
        if ($this->editing->isNot($city)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editing = $city;
        }

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->save();
        $this->showEditModal = false;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    // Dynamic property
    public function getRowsQueryProperty()
    {
        $query = City::query()
        ->when($this->filters['search'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
        ;

        return $this->applySorting($query, 'sequence', 'asc');
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function render()
    {
        if ($this->selectAll) {
            $this->selectPageRows();
        }

        return view('livewire.ots.cities', [
            // search --> AppServiceProvider boot() defined
            // 'management' => Management::search('title', $this->search)->paginate(10),
            'items' => $this->rows,
            // 'management' => Management::query()
            //     // ->when($this->filters['year'], function ($query,  $value) {
            //     //     $query->where('year', $value);
            //     // })
            //     ->when($this->filters['year'], fn ($query, $year) => $query->where('year', $year))
            //     ->when($this->filters['initiate'], fn ($query, $initiate) => $query->where('initiate', Carbon::parse($initiate)))
            //     ->when($this->filters['deadline'], fn ($query, $deadline) => $query->where('deadline', Carbon::parse($deadline)))
            //     ->when($this->filters['search'], fn ($query, $search) => $query->where('year', 'like', '%'.$this->search.'%'))
            //     // ->where('year', 'like', '%'.$this->search.'%')
            //     ->orderBy($this->sortField, $this->sortDirection)
            //     ->paginate(10),
        ]);
    }
}
