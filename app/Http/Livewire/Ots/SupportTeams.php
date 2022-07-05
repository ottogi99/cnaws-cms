<?php

namespace App\Http\Livewire\Ots;

use App\Http\Livewire\Ots\WithBulkActions;
use App\Http\Livewire\Ots\WithPerPagePagination;
use App\Http\Livewire\Ots\WithSorting;
use App\Models\Account;
use App\Models\Machinery;
use App\Models\Nonghyup;
use App\Models\SupportTeam;
use App\Models\Training;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SupportTeams extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions;

    public $nonghyupsInSelectedCity = [];

    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showFilters = false;
    public $filters = [
        'search' => '',
        'city' => '',
        'nonghyup_name' => '',
        'size' => '',
        'gender' => '',
    ];
    public $yearForEditing = '';
    public $machineriesForEditing = [];
    public $trainingsForEditing = [];
    public $inputStartDate;
    public $inputEndDate;

    public SupportTeam $editing;
    public Account $editingAccount;

    protected $queryString = ['perPage'];

    protected $listeners = ['refreshItems' => '$refresh'];

    // Validation 함수
    public function rules()
    {
        return  [
            'year_for_editing' => 'required',
            'editing.nonghyup_id' => ['required',],
            'editing.name' => 'required',
            'editing.birthday' => 'required',
            'editing.gender' => 'required',
            'editing.address' => 'required',
            'editing.contact' => 'required',
            'editing.size' => 'required',
            // S
            'editing.rice_field' => 'required',
            'editing.field' => 'required',
            'editing.other_field' => 'required',
            // L
            'editing.area' => 'required',
            'editing.items' => 'required',
            'editingAccount.name' => 'required',
            'editingAccount.number' => 'required',
            'editingAccount.accountable_type' => 'required',
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankModel();
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

    public function makeBlankModel()
    {
        return SupportTeam::make([
            'gender' => '',
            'size' => '',
            'rice_field' => 0,
            'field' => 0,
            'other_field' => 0,
            'nonghyup_id' => '',
            'account_id' => '',
        ]);
    }

    // public function makeBlankStaff()
    // {
    //     return ModelStaff::make([
    //         'name' => '',
    //         'birthday' => null,
    //         'nonghyup_id' => '',
    //         'account_id' => null,
    //     ]);
    // }

    public function makeBlankAccount()
    {
        return Account::make([
            'accountable_type' => \App\Models\Farmhouse::class,
        ]);
    }

    public function create()
    {
        if ($this->editing->getKey()) {  // $this->editing 오버라이딩 하는 조건을 담
            $this->editing = $this->makeBlankModel();
            $this->editingAccount = $this->makeBlankAccount();
            $this->yearForEditing = '';
        }

        $this->showEditModal = true;
    }

    public function edit(SupportTeam $model, $editYear)
    {
        if ($this->editing->isNot($model)) {   // 편집중인 editing이면 오버라이딩 하지 않도록
            $this->editing = $model;
            $this->machineriesForEditing = null;
            $this->trainingsForEditing = null;
            $this->yearForEditing = $editYear;

            $account = Account::where('id', $model->account_id)
                ->where('accountable_type', \App\Models\SupportTeam::class)->first();

            if ($this->editing->size == 'L') {
                $machineries = DB::table('support_team_machinery')
                    ->where('support_team_id', $model->id);
            } else {
                $trainings = Training::where('support_team_id', $model->id);
            }

            // if (isset($machineries))
            //     $this->machineriesForEditing = $machineries;

            // if (isset($trainings))
            //     $this->trainingsForEditing = $trainings;

            if (isset($account))
                $this->editingAccount = $account;
        }

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function() {
            $this->editing->save();

            if (! isset($this->editingAccount)) {     // 신규 추가 (create -> Save) 처리
                $this->editingAccount->accountable_id = $this->editing->id;
                $this->editingAccount->save();
                $this->editing->account_id = $this->editingAccount->id;
                $this->editing->save();
            } else {
                $this->farmhouse->management()->create([
                    'year_for_editing'
                ]);
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
            $query->from('support_teams')
            ->join('nonghyups', 'nonghyup_id', 'nonghyups.id')
            ->leftJoin('accounts', function($join) {
                $join->on('account_id', '=', 'accounts.id');
                $join->on('accounts.accountable_type', '=', DB::raw("'App\\\Models\\\SupportTeam'"));
            })
            ->select(
                'support_teams.id as support_team_id',
                'support_teams.name as support_team_name',
                'birthday',
                'gender',
                'support_teams.address as support_team_address',
                'support_teams.contact as support_team_contact',
                'size',
                'insurance',
                'support_teams.created_at as support_team_created_at', 'support_teams.updated_at as support_team_updated_at',
                'nonghyups.id as nonghyup_id', 'nonghyups.name as nonghyup_name', 'nonghyups.city_id as city_id',
                'accounts.id as account_id', 'accounts.name as account_name', 'accounts.number as account_number'
            );
        }, 'T1')
        ->join('cities', 'T1.city_id', 'cities.id')
        ->join('management_support_team', 'T1.support_team_id', 'management_support_team.support_team_id');

        if ($this->editing->size == 'S') {
            $query->join('support_team_machinery', 'T1.support_team_id', 'support_team_machinery.support_team_id')
            ->select(
                'management_support_team.management_year as year',
                'cities.id as city_id', 'name as city_name',
                'T1.support_team_id as id', 'size', 'support_team_name', 'birthday', 'gender', 'support_team_address', 'support_team_contact',
                'support_team_created_at', 'support_team_updated_at',
                'nonghyup_id', 'nonghyup_name', 'city_id',
                'account_id', 'account_name', 'account_number',
                'support_team_machinery.machinery_id as machinery_id',
            );
        } else {
            $query->select(
                'management_support_team.management_year as year',
                'cities.id as city_id', 'name as city_name',
                'T1.support_team_id as id', 'size', 'support_team_name', 'birthday', 'gender', 'support_team_address', 'support_team_contact',
                'support_team_created_at', 'support_team_updated_at',
                'nonghyup_id', 'nonghyup_name', 'city_id',
                'account_id', 'account_name', 'account_number',
            );
        }

        $query
        ->when($this->filters['search'], fn($query, $search) => $query->where('support_team_name', 'like', '%'.$search.'%'))
        ->when($this->filters['city'], fn($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
        ->when($this->filters['nonghyup_name'], fn($query, $search) => $query->where('nonghyup_name', 'like', '%'.$search.'%'))
        ->when($this->filters['size'], fn($query, $search) => $query->where('size', '=', $search))
        ->when($this->filters['gender'], fn($query, $search) => $query->where('gender', '=', $search))
        ;

        // dd($query->toSql());

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

        return view('livewire.ots.support-teams', [
            'items' => $this->rows,
            // dd($this->rows),
        ]);
    }
}
