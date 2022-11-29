<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'loan_id',
        'amount',
        'scheduled_payment_date',
        'status'
    ];
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
    public function scopeUpdatePaidStatus($query,$loanDetails)
    {
        return $query->where('id',$loanDetails)->update(['status'=>'PAID']);
    }
    
    
}
