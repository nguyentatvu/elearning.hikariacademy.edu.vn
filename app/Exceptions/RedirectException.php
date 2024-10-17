<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class RedirectException extends Exception
{
    protected $response;

    public function __construct(RedirectResponse $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
