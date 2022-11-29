<?php
namespace App\Http\Validators;

use App\Http\Validators\ValidationBase;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Stringy\Stringy;
use Auth;
use App\Rules\CheckAmount;


class  LoanValidator extends ValidationBase
{
    private  $amount;
    private  $term;
    private $date;
    private  $loan_id;
    private  $type;
    private  $status;

     
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): LoanValidator
    {
        $this->amount = $amount;
        return $this;
    }

    public function getTerm()
    {
        return $this->term;
    }
    
    public function setTerm( $term): LoanValidator
    {
        $this->term = $term;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }
    
    public function setDate($date): LoanValidator
    {
        $this->date = $date;
        return $this;
    }
    
    public function getLoanId()
    {
        return $this->loan_id;
    }
    
    public function setLoanId($loanId): LoanValidator
    {
        $this->loan_id = $loanId;
        return $this;
    }
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type): LoanValidator
    {
        $this->type = $type;
        return $this;
    } 
    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status): LoanValidator
    {
        $this->status = $status;
        return $this;
    } 
    /**
     * Set the props from request
     */
    public function setFromRequest(): LoanValidator
    {
        $this->setTerm($this->request->post('term'));
        $this->setAmount($this->request->post('amount'));
        $this->setDate($this->request->post('date'));
        $this->setLoanId($this->request->post('loan_id'));
        $this->setStatus($this->request->post('status'));

       return $this;
    }

    public function getRules(): array
    {
        if($this->getType() == 'add') {
        $rules =  [
        'amount' => 'required|integer|min:0',
        'term' => 'required|integer|min:1',
        'date' => 'nullable|date|date_format:Y-m-d|after_or_equal:today'
        ];
    } else if ($this->getType() == 'repayment') {
        $rules = [
            
                 'loan_id' => 'required|min:0|integer|exists:loans,id,user_id,'.Auth::user()->id.',status,APPROVED',
                 'amount' => ['required',new checkAmount($this->getAmount(),$this->getLoanId())],
                 'date' => 'nullable|date|date_format:Y-m-d|after_or_equal:today'
            ];
            
           
    }
    else if ($this->getType() == 'approve') {
        $rules = [
            'loan_id' => 'required|integer|min:0|exists:loans,id',
            'status' => 'required|in:PENDING,APPROVED'
            ];

    }
    
        return $rules;
    }

    public function getData(): array
    {
        $setKeys = [
            'amount',
            'date',
            'term',
            'loan_id'
        ];

        $data = [];
        foreach ($setKeys as $eachKey) {
            $getterMethod = 'get' . Stringy::create($eachKey)->toTitleCase()->replace('_', '');
            $data[$eachKey] = $this->$getterMethod();
        }
        return $data;
    }

    /**
     * @return array|bool
     * @throws BindingResolutionException
     */
    public function validateRequest()
    {
        return  parent::validateRequest();
        
    }
}