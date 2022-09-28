<?php

namespace App\Controllers;

use App\Models\Users;

class Home extends BaseController
{
    public function __construct(){
        $this->usersModel = new users();
    }
    public function index()
    {
        $this->usersModel = new users();
        $data['data'] =  $this->usersModel->where( 'synced',   1)->findAll();
        return view('index', $data);
    }
}
