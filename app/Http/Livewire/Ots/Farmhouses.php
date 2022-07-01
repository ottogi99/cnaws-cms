<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\Nonghyup;
use App\Models\Staff as ModelStaff;
use App\Models\Account as ModelAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Farmhouses extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $nonghyupsInSelectedCity = [];

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'search' => '',
        'city' => '',
        'account-name' => '',
        'account-number' => '',
    ];


    public ModelStaff $editing;
    public ModelStaff $editingStaff;
    public ModelAccount $editingAccount;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        return  [
            'editingStaff.nonghyup_id' => ['required',],
            'editingStaff.name' => 'required',
            'editingStaff.birthday' => 'required',

            'editingAccount.name' => 'required',
            'editingAccount.number' => 'required',
            'editingAccount.accountable_type' => 'required',
        ];
    }

    public function mount()
    {
        $this->editingStaff = $this->makeBlankStaff();
        $this->editingAccount = $this->makeBlankAccount();
    }

    public function updatedFilters($field)
    {
        // 필터가 변경될 때마다 페이지 번호를 초기화
        $this->resetPage();
    }

    // public function updatedCityForEditing($value, $name)
    // {
    //     $this->nonghyupsInSelectedCity = Nonghyup::where('city_id', '=', $value)->get();
    // }

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

    public function getNonghyupsProperty()
    {
        return $this->getNonghyups();
    }

    public function getNonghyups($city = null)
    {
        return $city
            ? Nonghyup::where('city_id', '=', $city)->get()
            : Nonghyup::all();
    }

    public function makeBlankStaff()
    {
        return ModelStaff::make([
            'name' => '',
            'birthday' => null,
            'nonghyup_id' => '',
            'account_id' => null,
        ]);
    }

    public function makeBlankAccount()
    {
        return ModelAccount::make([
            'name' => '',
            'number' => '',
            'accountable_type' => 'App\Models\Staff',
        ]);
    }

    public function create()
    {
        if ($this->editingStaff->getKey()) {  // $this->editing 오버라이딩 하는 조건을 담
            $this->editingStaff = $this->makeBlankStaff();
            $this->editingAccount = $this->makeBlankAccount();
        }

        $this->showEditModal = true;
    }

    public function edit(ModelStaff $model)
    {
        if ($this->editingStaff->isNot($model)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editingStaff = $model;
            $this->editingAccount = ModelAccount::where('accountable_id', $model->account_id)
                ->where('accountable_type', \App\Models\Staff::class)->first();
        }

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function() {
            $this->editingStaff->save();

            if (isset($this->editingAccount)) {     // 신규 추가 (create -> Save) 처리
                $this->editingAccount->accountable_id = $this->editingStaff->id;
                $this->editingAccount->save();
                $this->editingStaff->account_id = $this->editingAccount->id;
                $this->editingStaff->save();
            } else {
                $this->editingAccount->save();
            }
        });

        $this->showEditModal = false;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    // Dynamic property
    public function getRowsQueryProperty()
    {
        $query = DB::query()->fromSub(function ($query) {
            $query->from('farmhouses')
            ->join('nonghyups', 'nonghyup_id', 'nonghyups.id')
            ->leftJoin('accounts', function($join) {
                $join->on('account_id', '=', 'accounts.accountable_id');
                $join->on('accounts.accountable_type', '=', DB::raw("'".\App\Models\Farmhouse::class."'"));
            })
            ->select(
                'farmhouses.id as farmhouse_id', 'size', 'farmhouses.name as farmhouse_name', 'birthday', 'gender', 'farmhouses.address as farmhouse_address', 'farmhouses.contact as farmhouse_contact',
                'rice_field', 'field', 'other_field',
                'area', 'items', 'farmhouses.created_at as farmhouse_created_at', 'farmhouses.updated_at as farmhouse_updated_at',
                'nonghyups.id as nonghyup_id', 'nonghyups.name as nonghyup_name', 'nonghyups.city_id as city_id',
                'accounts.id as account_id', 'accounts.name as account_name', 'accounts.number as account_number'
            );
        }, 'T1')
        ->join('cities', 'T1.city_id', 'cities.id')
        ->join('management_farmhouse', 'T1.farmhouse_id', 'management_farmhouse.farmhouse_id')
        ->select(
            'management_farmhouse.management_year as year',
            'cities.id as city_id', 'name as city_name',
            'T1.farmhouse_id as id', 'size', 'farmhouse_name', 'birthday', 'gender', 'farmhouse_address', 'farmhouse_contact',
            'rice_field', 'field', 'other_field',
            'area', 'items', 'farmhouse_created_at', 'farmhouse_updated_at',
            'nonghyup_id', 'nonghyup_name', 'city_id',
            'account_id', 'account_name', 'account_number'
        )
        // ->when($this->filters['search'], fn($query, $search) => $query->where('staff_name', 'like', '%'.$search.'%'))
        // ->when($this->filters['city'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
        // ->when($this->filters['account-name'], fn($query, $search) => $query->where('account_name', 'like', '%'.$search.'%'))
        // ->when($this->filters['account-number'], fn($query, $search) => $query->where('account_number', 'like', '%'.$search.'%'))
        ;

        // $query = $this->applySorting($query, 'expenses.created_at', 'desc');
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

        return view('livewire.ots.farmhouses', [
            'items' => $this->rows,
        ]);
    }
}
