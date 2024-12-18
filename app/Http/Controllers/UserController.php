<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request) {
        // get user with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%'.$name.'%');
        })
        ->paginate(10);
        return view('pages.user.index', compact('users'));
    }

    public function create() {
        return view('pages.user.create');
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        User::create($data);
        return redirect()->route('user.index')->with('success', 'User created successfully');
    }

    public function show() {

    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('pages.user.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $user = User::findOrFail($id);
        if ($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        } else {
            $data['password'] = $user->password;
        }
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}
