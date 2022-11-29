<?php

namespace App\Http\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

/**
 * Class Validation
 *
 * @package App\Http\Validation
 */
abstract class ValidationBase
{
    /**
     * @var Request
     */
    public Request $request;

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest($request): ValidationBase
    {
        $this->request = $request;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function validate()
    {
        $validatorFactory = app('Illuminate\Validation\Factory');
        $validator = $validatorFactory->make($this->request->all(), $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            return $validator->errors();
        }
    }

    /**
     * Get validation rules for the current request.
     *
     * @return array
     */
    public function getRules(): array
    {
        return [];
    }

    /**
     * Get messages if rules not met.
     *
     * @return array
     */
    public function getMessages(): array
    {
        return [];
    }

    /**
     * validateRequest
     *
     * @return string
     */
    public function validateRequest()
    {
        $data = $this->validate();
        $errorMsg = [];

        if (!is_a($data, MessageBag::class)) {
            return;
        }

        $errors = $data->getMessages();

        foreach ($errors as $key=>$error) {
            foreach ($error as $msg) {
                $errorMsg[$key][] = $msg;
            }
        }

        return  $errorMsg;
    }
}