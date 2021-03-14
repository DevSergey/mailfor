<?php
namespace App\Http\Controllers;
use App\User;
class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->admin === true) {
            abort(403);
        }
        $users = User::all();
        return view('users.index', compact('users'));
    }
    public function destroy(User $user)
    {
        if (!auth()->user()->admin === true) {
            abort(403);
        }
        User::destroy($user->id);
        return redirect('/users');
    }
}
