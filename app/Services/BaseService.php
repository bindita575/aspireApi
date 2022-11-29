<?php
namespace App\Services;
use Illuminate\Http\Request;

abstract class BaseService {

    /**
     * @var Request
     */
    protected $request;
    
    public function setRequest(Request $request) : self
    {
        $this->request = $request;
        return $this;
    }

   

}