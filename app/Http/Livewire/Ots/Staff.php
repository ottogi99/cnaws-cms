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

class Staff extends Component
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


    // public ModelStaff $editing;
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
            $query->from('staff')
            ->join('nonghyups', 'nonghyup_id', 'nonghyups.id')
            ->join('accounts', 'account_id', 'accounts.id')
            ->select(
                'staff.id as staff_id', 'staff.name as staff_name', 'staff.birthday', 'staff.created_at', 'staff.nonghyup_id',
                'nonghyups.name as nonghyup_name', 'nonghyups.city_id as city_id',
                'accounts.id as account_id', 'accounts.name as account_name', 'accounts.number as account_number', 'accounts.accountable_type as accountable_type'
            );
        }, 'T1')
        ->join('cities', 'T1.city_id', 'cities.id')
        ->select(
            'id as city_id', 'name as city_name',
            'staff_id as id', 'staff_name', 'birthday', 'nonghyup_id',
            'nonghyup_name',
            'account_id', 'account_name', 'account_number', 'accountable_type'
        )
        ->when($this->filters['search'], fn($query, $search) => $query->where('staff_name', 'like', '%'.$search.'%'))
        ->when($this->filters['city'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
        ->when($this->filters['account-name'], fn($query, $search) => $query->where('account_name', 'like', '%'.$search.'%'))
        ->when($this->filters['account-number'], fn($query, $search) => $query->where('account_number', 'like', '%'.$search.'%'))
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

        return view('livewire.ots.staff', [
            'items' => $this->rows,
        ]);
    }
}
