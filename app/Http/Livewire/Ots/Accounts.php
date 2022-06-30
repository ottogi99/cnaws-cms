<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\Account;
use App\Models\Nonghyup;
use App\Models\Staff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Accounts extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $nonghyupsInSelectedCity = [];

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'search' => '',
        'nonghyup' => '',
        'account-name' => '',
        'account-number' => '',
        'accountable-type' => '',
    ];


    public Account $editing;
    public $editingNonghyup;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        return  [
            'editing.name' => 'required',
            'editing.number' => 'required',
            'editing.accountable_type' => 'required',
            'editing.accountable_id' => 'required',
            'editing.accountable.nonnghyup.id' => 'required',
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

    public function getOwnersProperty()
    {
        return $this->getOwers();
    }

    public function getOwers($accountable_type = null)
    {
        return Staff::all();
        // return $accountable_type == \App\Models\Staff::class
        //     ? Staff::all()
        //     : Farmers:all();
    }

    public function makeBlankModel()
    {
        return Account::make([
            'name' => '',
            'number' => '',
            'accountable_type' => 'App\Models\Staff',
        ]);
    }

    public function create()
    {
        if ($this->editing->getKey()) {  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankModel();
            $this->editingNonghyup = null;
        }

        $this->showEditModal = true;
    }

    public function edit(Account $model)
    {
        if ($this->editing->isNot($model)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editing = $model;
            $this->editingNonghyup = $this->editing->accountable->nonghyup->name;
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
            $query->from('accounts')
            ->join('staff', 'accountable_id', 'staff.id')
            ->select(
                'accounts.id as account_id', 'accounts.name as account_name', 'accounts.number as account_number', 'accountable_type', 'accountable_id',
                'staff.id as staff_id', 'staff.name as staff_name', 'staff.birthday as staff_birthday', 'staff.nonghyup_id as staff_nonghyup_id'
            );
        }, 'T1')
        ->join('nonghyups', 'T1.staff_nonghyup_id', 'nonghyups.id')
        ->select(
            'id as nonghyup_id', 'name as nonghyup_name',
            'account_id as id', 'account_name', 'account_number', 'accountable_type', 'accountable_id',
            'staff_id as id', 'staff_name', 'staff_birthday', 'staff_nonghyup_id',
        )
        ->when($this->filters['search'],
            fn($query, $search) => $query->where('staff_name', 'like', '%'.$search.'%')
                                    // ->orWhere('farmer_name', 'like', '%'.$search.'%')
        )
        ->when($this->filters['nonghyup'], fn($query, $search) => $query->where('nonghyups.id', '=', $search))
        ->when($this->filters['account-name'], fn($query, $search) => $query->where('account_name', 'like', '%'.$search.'%'))
        ->when($this->filters['account-number'], fn($query, $search) => $query->where('account_number', 'like', '%'.$search.'%'))
        ->when($this->filters['accountable-type'], fn($query, $search) => $query->where('accountable_type', '=', $search))


        // $query = DB::table('accounts')
        //     // ->join('farmers', 'accountable_id', 'farmers.id')
        //     ->join('staff', 'accountable_id', 'staff.id')
        //     ->select(
        //         'accounts.id as id', 'accounts.name as account_name', 'accounts.number as account_number', 'accountable_type',
        //         'staff.name as staff_name', 'staff.birthday as staff_birth'
        //     )
        //     ->when($this->filters['search'],
        //         fn($query, $search) => $query->where('staff_name', 'like', '%'.$search.'%')
        //                                 // ->orWhere('farmer_name', 'like', '%'.$search.'%')
        //     )
        //     ->when($this->filters['nonghyup'], fn($query, $search) => $query->where('account_name', 'like', '%'.$search.'%'))
        //     ->when($this->filters['account-name'], fn($query, $search) => $query->where('account_name', 'like', '%'.$search.'%'))
        //     ->when($this->filters['account-number'], fn($query, $search) => $query->where('account_number', 'like', '%'.$search.'%'))
        //     ->when($this->filters['accountable-type'], fn($query, $search) => $query->where('accountable_type', '=', $search))

        // $query = DB::query()->fromSub(function ($query) {
        //     $query->from('accounts')
        //     ->join('nonghyups', 'nonghyup_id', 'nonghyups.id')
        //     ->join('accounts', 'account_id', 'accounts.id')
        //     ->select(
        //         'staff.id as staff_id', 'staff.name as staff_name', 'staff.birthday', 'staff.created_at', 'staff.nonghyup_id',
        //         'nonghyups.name as nonghyup_name', 'nonghyups.city_id as city_id',
        //         'accounts.id as account_id', 'accounts.name as account_name', 'accounts.number as account_number', 'accounts.accountable_type as accountable_type'
        //     );
        // }, 'T1')
        // ->join('cities', 'T1.city_id', 'cities.id')
        // ->select(
        //     'id as city_id', 'name as city_name',
        //     'staff_id as id', 'staff_name', 'birthday', 'nonghyup_id',
        //     'nonghyup_name',
        //     'account_id', 'account_name', 'account_number', 'accountable_type'
        // )
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

        return view('livewire.ots.accounts', [
            'items' => $this->rows,
        ]);
    }
}
