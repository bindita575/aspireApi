<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LoanDetail;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'amount',
        'term',
        'user_id',
    ];
    public function loanDetail()
    {
        return $this->hasMany(LoanDetail::class);
    }
    public function scopePendingLoanDetails($query,$loan_id){
        return $query->select('*','ld.amount as emi_amount')->from('loans as l')->join('loan_details as ld', 'ld.loan_id', 'l.id')->where('ld.status','PENDING')->where('l.id',$loan_id);
    }
    public function scopeUpdatePaidStatus($query,$loanId)
    {
        return $query->where('id',$loanId)->update(['status'=>'PAID']);
    }

}
