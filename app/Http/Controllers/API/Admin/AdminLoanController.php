<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Loan;
use App\Models\LoanDetail;
use App\Http\Validators\LoanValidator;
use App\Services\LoanService;
use App\Http\Responses\Loan\FailureResponse;
use App\Http\Responses\Loan\SuccessResponse;


class AdminLoanController extends Controller
{
    public function approveLoan(Request $request,LoanValidator $loanValidator, LoanService $loanService)
    {
        try
        {
            $validateRequest = $loanValidator->setRequest($request)->setFromRequest()->setType('approve')->validateRequest();
            if (is_array($validateRequest)) {
                return FailureResponse::handleValidation($validateRequest);
            }
            $approveLoan = Loan::whereId($loanValidator->getLoanId())->update(['status' => $loanValidator->getStatus()]);
            if($approveLoan){
                return SuccessResponse::handleSuccess(Loan::whereId($loanValidator->getLoanId())->get(),'Loan is '.$loanValidator->getStatus() . '..!');
            }
            return FailureResponse::handleException($ex->getMessage());
            
        }
        catch(\Exception $ex)
        {
            return FailureResponse::handleException($ex->getMessage());
        }
    }
    
    public function pendingLoan()
    {
        $response['data'] = Loan :: with('loanDetail')->where('status','PENDING')->get();
        $response['total_record'] = count($response['data']);
         return response()->json($response, 200);
    }
}
