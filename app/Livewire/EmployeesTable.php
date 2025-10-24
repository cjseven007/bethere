<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Support\OrgContext;
use App\Models\Employee;

class EmployeesTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sort = 'id';
    public $dir  = 'asc';

    protected $updatesQueryString = ['search', 'sort', 'dir'];
    protected $listeners = ['embeddingSaved' => '$refresh'];

    public function updatingSearch() { $this->resetPage(); }

    public function sortBy($column)
    {
        if ($this->sort === $column) {
            $this->dir = $this->dir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->dir  = 'asc';
        }
        $this->resetPage();
    }

    public function getEmployeesProperty()
    {
        $org = OrgContext::current();

        $q = Employee::with('embedding')
            ->where('organization_id', $org->id);

        if ($this->search) {
            $q->where(function ($s) {
                $s->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        return $q->orderBy($this->sort, $this->dir)->paginate(20);
    }

    public function render()
    {
        return view('livewire.employees-table', ['employees' => $this->employees]);
    }
}

