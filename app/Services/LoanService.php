<?php
namespace App\Services;

use App\Services\BaseService;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanDetail;
use Auth;
use Carbon\Carbon;

// use App\Http\Resources\LoanResource;


class LoanService extends BaseService{

    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var Loan
     */
    public Loan $model;

    public function __construct(Loan $model)
    {
        $this->model = $model;
    }

    public function setRequest(Request $request) : self
    {
        $this->request = $request;
        return $this;
    }

    public function process()
    {
        $input = $this->request->all();
        $input['date']=$input['date']?? date('Y-m-d');
        $input['user_id'] = Auth::user()->id;
        $Loan = Loan :: create($input);
        $loanDetails = [];
    
        for($i = 1;$i<=$input['term'];$i++)
        {
            if($i==$input['term']){
                $amount[$i]=$input['amount']-array_sum($amount);
            }
            else{
            $amount[$i]=round($input['amount']/$input['term'],2);
            }
            $input['scheduled_payment_date'][$i]= date('Y-m-d',strtotime("+".(7)*($i)." day", strtotime($input['date'])));
            $loanDetails[] = new LoanDetail([
                $Loan->getKey(),
                'scheduled_payment_date' => $input['scheduled_payment_date'][$i],
                'amount' =>$amount[$i]
            ]);
            
        }
        
        return $Loan->loanDetail()->saveMany($loanDetails);
             
    }
  
    public function processRepayment() {
        $loanDetails = Loan::pendingLoanDetails($this->request->loan_id)->get(); 
        $updatedLoanDetailsStatus = LoanDetail::updatePaidStatus($loanDetails[0]->id);
        if($updatedLoanDetailsStatus){
            if(count($loanDetails)==1){
               $updatedLoanStatus = Loan::updatePaidStatus($this->request->loan_id);
            }
        }
        return Loan::whereId($this->request->loan_id)->get();
    }
}