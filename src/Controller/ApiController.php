<?php

namespace App\Controller;

class ApiController
{
    public function __construct()
    {

    }

    public function test($request,$response)
    {
        return $response->write("Controller is working");
    }
}