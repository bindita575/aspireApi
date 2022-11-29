<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Loan;
use App\MOdels\LoanDetails;

class CheckAmount implements Rule
{
    private ?float $emiAmount;
    private ?int $loanId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($amount,$loanId)
    {
        $this->emiAmount = 0 ;
        $this->loanId = $loanId ;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $checkAmount = Loan::pendingLoanDetails($this->loanId)->first(); 
        
        if($checkAmount){
            if( $value >= $checkAmount->emi_amount ){
                return true;
            }
            else{
                $this->emiAmount = $checkAmount->emi_amount ;
            }
        } else {
            return true;
        }
         
        
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {

        return "Amount should be greater or equals to $this->emiAmount";
    }
}
