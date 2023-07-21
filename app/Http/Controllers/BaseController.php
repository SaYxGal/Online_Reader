<?php

namespace App\Http\Controllers;

use App\Services\ServiceInterface;

class BaseController extends Controller
{
    public ServiceInterface $service;
}
