<?php

namespace App\Http\Controllers;

use App\Services\CryptoCompareAPI;

class IndexController extends Controller
{
    public function index()
    {
        return CryptoCompareAPI::index();
    }
}
