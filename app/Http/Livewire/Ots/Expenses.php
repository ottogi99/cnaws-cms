<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\Expense;
use App\Models\Nonghyup;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Expenses extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $nonghyupsInSelectedCity = [];

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'search' => '',
        'city' => '',
        'name' => '',
        'address' => '',
        'contact' => '',
        'representative' => '',
    ];

    public Expense $editing;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        return  [
            'editing.name' => ['required',],
            'editing.city_id' => ['required',],
            'editing.address' => ['required',],
            'editing.contact' => ['required',],
            'editing.representative' => ['required',],
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

    public function updatedEditing($field)
    {
        dd($field);
        if ($field === 'editing.city_id') {
            $nonghyupsInSelectedCity = Nonghyup::where('city_id', '=', $field);
        }
    }

    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll,fn($query) => $query->whereIn('nonghyups.id', $this->selected));
    }

    public function deleteSelected()
    {
        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;
    }

    public function makeBlankCity()
    {
        return Expense::make([
            'year' => '',
            'total' => 0,
            'do' => 0,
            'sigun' => 0,
            'center' => 0,
            'unit' => 0
        ]);
    }

    public function create()
    {
        if ($this->editing->getKey())  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankCity();

        $this->showEditModal = true;
    }

    public function edit(Expense $model)
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
    public function getRowsQueryProperty()
    {
        $query = DB::table('expenses')
            ->join('nonghyups', 'nonghyup_id', 'nonghyups.id')
            ->join('cities', 'city_id', 'nonghyups.city_id')
            ->select(
                'expenses.id', 'expenses.year', 'expenses.total','expenses.do', 'expenses.sigun', 'expenses.center', 'expenses.unit', 'expenses.created_at',
                'nonghyups.id as nonghyup_id', 'nonghyups.name as nonghyup_name',
                'cities.name as city_name'
            )
            // ->when($this->filters['search'], fn($query, $search) => $query->where('nonghyups.name', 'like', '%'.$search.'%'))
            // ->when($this->filters['city'], fn($query, $search) => $query->where('cities.name', 'like', '%'.$search.'%'))
            // ->when($this->filters['address'], fn($query, $search) => $query->where('address', 'like', '%'.$search.'%'))
            // ->when($this->filters['contact'], fn($query, $search) => $query->where('contact', 'like', '%'.$search.'%'))
            // ->when($this->filters['representative'], fn($query, $search) => $query->where('representative', 'like', '%'.$search.'%'))
        ;
        $query = $this->applySorting($query, 'expenses.created_at', 'desc');
        // JOIN
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

        return view('livewire.ots.expenses', [
            'items' => $this->rows,
        ]);
    }
}
