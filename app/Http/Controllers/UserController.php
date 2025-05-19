<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        $approvers = User::where('role', 'approver')->get();

        return view('users.index', compact('admins', 'approvers'));
    }
}

