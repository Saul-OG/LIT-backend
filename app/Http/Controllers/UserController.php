<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()  { return User::paginate(20); }
    public function show($id){ return User::findOrFail($id); }

    public function getLives() { return response()->json(['lives'=>3]); }
    public function getStats() { return response()->json(['total_users'=>User::count()]); }
}
