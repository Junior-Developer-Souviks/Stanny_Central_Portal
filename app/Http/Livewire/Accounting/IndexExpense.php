<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;


class IndexExpense extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $paymentDate = '';
    // protected $updatesQueryString = ['search'];    
   
   public function searchExpense($value)
    {
        $this->search = $value;
    }
  
    public function render()
    {
        $expenses = Payment::where('payment_for', 'debit')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('voucher_no', 'like', '%' . $this->search . '%')
                      ->orWhere('amount', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->paymentDate, function ($query) {
                $query->whereDate('payment_date', $this->paymentDate);
            })
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('livewire.accounting.index-expense', compact('expenses'));
    }
}
