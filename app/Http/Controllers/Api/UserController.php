<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.admin');
    }

    public function index()
    {
        return User::with('roles')->paginate(10);
    }
}