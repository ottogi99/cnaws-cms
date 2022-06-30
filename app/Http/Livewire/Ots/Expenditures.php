<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\Nonghyup;
use App\Models\Staff;
use App\Models\Account as ModelAccount;
use App\Models\Expenditure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Expenditures extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $nonghyupsInSelectedCity = [];

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'search' => '',
        'nonghyup' => '',
        'expenditure_type' => '',
        'expenditure_item' => '',
        'expenditure_target' => '',
        'expenditure_details' => '',
        'expenditure_amount' => '',
        'amount_min' => '',
        'amount_max' => '',
        'payment_at_min' => '',
        'payment_at_max' => '',
    ];


    public Expenditure $editing;
    public Staff $editingStaff;
    public ModelAccount $editingAccount;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        $ExpendituresRules = [
            'editing.nonghyup_id' => 'required',
            'editing.type' => 'required',
            'editing.item' => 'required',
            'editing.target' => 'required',
            'editing.details' => 'required',
            'editing.amount' => 'required',
            'editing.payment_at' => 'required',
        ];

        if ($this->editing->type == \App\Models\Expenditure::$LABOR_TYPE) {
            unset($ExpendituresRules['editing.target']);
            $ExpendituresRules = array_merge($ExpendituresRules, array('editing.staff_id' => 'required'));
        }

        return $ExpendituresRules;
        // return  [
        //     'editing.nonghyup_id' => 'required',
        //     'editing.type' => 'required',
        //     'editing.item' => 'required',
        //     'editing.target' => 'required',
        //     'editing.details' => 'required',
        //     'editing.amount' => 'required',
        //     'editing.payment_at' => 'required',
        //     'editing.staff_id' => '',
        // ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankModel();
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

    public function makeBlankModel()
    {
        return Expenditure::make([
            'nonghyup_id' => '',
            'type' => '',
        ]);
    }

    public function makeBlankStaff()
    {
        return Staff::make([
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
        if ($this->editing->getKey()) {  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankModel();
        }

        $this->showEditModal = true;
    }

    public function edit(Expenditure $model)
    {
        if ($this->editing->isNot($model)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editing = $model;
            // $this->editingAccount = ModelAccount::where('accountable_id', $model->account_id)
            //     ->where('accountable_type', \App\Models\Staff::class)->first();
        }

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();
        $this->editing->save();
        // DB::transaction(function() {
        //     $this->editingStaff->save();

        //     if (isset($this->editingAccount)) {     // 신규 추가 (create -> Save) 처리
        //         $this->editingAccount->accountable_id = $this->editingStaff->id;
        //         $this->editingAccount->save();
        //         $this->editingStaff->account_id = $this->editingAccount->id;
        //         $this->editingStaff->save();
        //     } else {
        //         $this->editingAccount->save();
        //     }
        // });

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
            $query->from('expenditures')
            ->join('nonghyups', 'nonghyup_id', 'nonghyups.id')
            ->select(
                'expenditures.id as expenditure_id', 'type as expenditure_type', 'item as expenditure_item', 'target as expenditure_target',
                'details as expenditure_details', 'amount as expenditure_amount',
                'staff_id', 'expenditures.created_at', 'payment_at',
                'nonghyups.name as nonghyup_name', 'nonghyups.city_id as city_id',
            );
        }, 'T1')
        ->leftJoin('staff', 'T1.staff_id', 'staff.id')
        ->select(
            'id as staff_id', 'name as staff_name', 'birthday',
            'expenditure_id as id', 'expenditure_type', 'expenditure_item', 'expenditure_target', 'expenditure_details', 'expenditure_amount', 'payment_at',
            'nonghyup_id', 'nonghyup_name',
        )
        ->when($this->filters['search'], fn($query, $search) => $query->where('expenditure_item', 'like', '%'.$search.'%'))
        ->when($this->filters['nonghyup'], fn($query, $search) => $query->where('nonghyup_name', 'like', '%'.$search.'%'))
        ->when($this->filters['expenditure_type'], fn($query, $search) => $query->where('expenditure_type', '=', $search))
        ->when($this->filters['expenditure_target'], fn($query, $search) => $query->where('expenditure_target', 'like', '%'.$search.'%'))
        ->when($this->filters['expenditure_details'], fn($query, $search) => $query->where('expenditure_details', 'like', '%'.$search.'%'))
        ->when($this->filters['amount_min'], fn($query, $search) => $query->where('expenditure_amount', '>=', $search))
        ->when($this->filters['amount_max'], fn($query, $search) => $query->where('expenditure_amount', '<=', $search))
        ->when($this->filters['payment_at_min'], fn($query, $search) => $query->where('payment_at', '>=', $search))
        ->when($this->filters['payment_at_max'], fn($query, $search) => $query->where('payment_at', '<=', $search))
        ;

        // $query = $this->applySorting($query, 'expenses.created_at', 'desc');
        $query = $this->applySorting($query);
        return $query;
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless(
                $this->selectAll,
                fn($query) => $query->whereIn('expenditure_id', $this->selected)
            );
    }

    public function getStaffInNonghyupProperty()
    {
        if ($this->editing->nonghyup_id)
            return Staff::where('nonghyup_id', $this->editing->nonghyup_id)->get();

        return Staff::get();
    }

    public function deleteSelected()
    {
        // $this->selectedRowsQuery->delete();

        DB::table('expenditures')
            ->whereIn('id', $this->selected)
            ->delete();

        $this->showDeleteModal = false;
    }



    public function render()
    {
        if ($this->selectAll) {
            $this->selectPageRows();
        }

        return view('livewire.ots.expenditures', [
            'items' => $this->rows,
        ]);
    }
}
