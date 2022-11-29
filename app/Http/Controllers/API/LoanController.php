<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Loan;
use App\Models\LoanDetail;
use Auth;
use App\Http\Validators\LoanValidator;
use App\Services\LoanService;
use App\Http\Responses\Loan\FailureResponse;
use App\Http\Responses\Loan\SuccessResponse;


class LoanController extends Controller
{
    public function requestLoan(Request $request,LoanValidator $loanValidator, LoanService $loanService)
    {
        try
        {
            $validateRequest = $loanValidator->setRequest($request)->setFromRequest()->setType('add')->validateRequest();
            if (is_array($validateRequest)) {
                return FailureResponse::handleValidation($validateRequest);
            }
            return SuccessResponse::handleSuccess($loanService->setRequest($request)->process(),'Loan request sent');
        }
        catch(\Exception $ex)
        {
            return FailureResponse::handleException($ex->getMessage());
        }
    }
    public function getLoanList()
    {
        try
        {
            $loan = Loan::with('loanDetail');
            if(Auth::user()->hasRole('Admin'))
            {
                $response = $loan->get();
            }
            else
            {
                $response = $loan->whereUserId(Auth::user()->id)->get();
            }
            $response['total_count'] = count($response);
            return SuccessResponse::handleSuccess($response,'Success');
            
        }
        catch(\Exception $ex)
        {
            return FailureResponse::handleException($ex->getMessage());
        }
        
        
    }

    public function rePayment(Request $request,LoanValidator $loanValidator, LoanService $loanService)
    {
        try {
            $validateRequest = $loanValidator->setRequest($request)->setFromRequest()->setType('repayment')->validateRequest();
            if (is_array($validateRequest)) {
                if(array_key_exists('loan_id',$validateRequest)){
                    $validateRequest['loan_id']='The selected loan id is invalid or not approved yet';
                }
                return FailureResponse::handleValidation($validateRequest);
            }
            return SuccessResponse::handleSuccess($loanService->setRequest($request)->processRepayment(),'Thank you for Payment!');
            
        } catch(\Exception $ex) {
            return FailureResponse::handleException($ex->getMessage());
        }

    }

}
