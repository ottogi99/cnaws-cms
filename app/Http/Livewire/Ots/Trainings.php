<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\Machinery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Trainings extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $nonghyupsInSelectedCity = [];

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'subject' => 'rerquired',
        'start_date' => 'rerquired|date',
        'end_date' => 'rerquired|date',
    ];

    public $yearForEditing = '';

    public Machinery $editing;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        $editing = $this->editing;
        return  [
            'editing.type' => Rule::unique('trainings', 'type')->where(function ($query) {
                return $query->where('spec', $this->editing->spec);
            }),
            'editing.spec' => 'max:120',
            // 'editing.type' => 'required|unique:machineries,type,NULL,NULL,spec,'.$this->editing->spec,
            // 'editing.spec' => 'required|unique:machineries,spec,NULL,NULL,spec,'.$this->editing->name,
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankModel();
    }

    public function updatedFilters($field)
    {
        // 필터가 변경될 때마다 페이지 번호를 초기화
        $this->resetPage();
    }

    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll,fn($query) => $query->whereIn('id', $this->selected));
    }

    public function deleteSelected()
    {
        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;
    }

    public function makeBlankModel()
    {
        return Machinery::make();
    }

    public function create()
    {
        if ($this->editing->getKey()) {  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankModel();
        }

        $this->showEditModal = true;
    }

    public function edit(Machinery $model)
    {
        if ($this->editing->isNot($model)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editing = $model;
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
    public function getMachineriesProperty()
    {
        return Machinery::orderby('type', 'asc')->get();
    }

    public function getRowsQueryProperty()
    {
        $query = Machinery::query()
        ->when($this->filters['type'], fn ($query, $typeId) => $query->where('id', $typeId))
        ;

        $query = $this->applySorting($query);
        return $query;
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

        return view('livewire.ots.trainings', [
            'items' => $this->rows,
        ]);
    }
}
