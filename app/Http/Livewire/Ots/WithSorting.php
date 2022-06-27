<?php
namespace App\Http\Livewire\Ots;

trait WithSorting
{
    public $sorts = [];

    // public $sortField = 'year';
    // public $sortDirection = 'desc';

    public function sortBy($field)
    {
        if (! isset($this->sorts[$field]) ) {
            $this->sorts[$field] = 'asc';
            return;
        }

        if ($this->sorts[$field] === 'asc') {
            $this->sorts[$field] = 'desc';
            return;
        }

        unset($this->sorts[$field]);

        // $this->sortDirection = $this->sortField === $field
        //     ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
        //     : 'asc';

        // $this->sortField = $field;
    }

    public function applySorting($query)
    {
        // return $query->orderBy($this->sortField, $this->sortDirection);

        foreach ($this->sorts as $field => $direction) {
            $query->orderby($field, $direction);
        }

        return $query;
    }
}
