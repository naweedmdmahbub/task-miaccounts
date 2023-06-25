<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Events\SendVerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // public function __construct()
    // {
    //    $this->middleware('auth');
    // }

    public function index()
    {
        // dd('hi index');
        $users = User::all();
        // Return the users as a response
        return response()->json($users);
    }

    public function store(UserRequest $request)
    {
        // dd(config('app.url'));
        try {
            $userData = $request->only('name', 'email');

            DB::beginTransaction();
            $userData['invitation_token'] = random_int(100000, 999999);
            $userData['password'] = Hash::make('123456');
            $userData['role'] = 'admin';
            $user = User::create($userData);
            DB::commit();
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }


    public function edit($id)
    {
        // dd('hi index');
        $user = User::find($id);

        // Return the customers as a response
        return response()->json($user);
    }


    public function update(UserRequest $request, $id)
    {
        try {
            $userData = $request->only('name', 'email');
            $user = User::find($id);
            DB::beginTransaction();
            $user->update($userData);
            DB::commit();
            return $user;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json( new \Illuminate\Support\MessageBag(['catch_exception'=>$ex->getMessage()]), 403);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            DB::beginTransaction();
            $user->delete();
            DB::commit();

            $users = User::all();
            return response()->json($users);
        } catch (Exception $ex) {
            DB::rollBack();
            return 'Delete Failed';
        }
    }

    public function logged_in_user()
    {
        $logged_in_user = Auth::user();
        return response()->json($logged_in_user);
    }

}
