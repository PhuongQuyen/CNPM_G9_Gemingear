<?php

namespace App\Http\Controllers;

use App\Banner_model;


class Customer extends Controller
{
    protected $banner_model;

    public function __construct()
    {
        $this->banner_model = new Banner_model();
    }

    public function index(Request $request)
    {
        $banner = $this->banner_model->getInfo();
        return view('customer.home', ['banner' => $banner]);
    }
}
