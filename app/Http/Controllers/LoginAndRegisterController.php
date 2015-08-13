<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\User;
use App\Models\UsersGroups;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libraries\ApiResponseClass;
use Illuminate\Support\Facades\Hash;
use DB;
use Validator;
use Auth;

class LoginAndRegisterController extends Controller
{


    public function getSignIn()
    {
        return view('user.account.login');
    }

    public function getCreate()
    {
        return view('user.account.register');
    }

    public function getForgotPassword()
    {
        return view('user.account.forgot-password');
    }

    public function postCreate(Request $request)
    {

        $email = $request->input('email');
        $password = $request->input('password');
        $password_again = $request->input('password_again');

        $inputs = [
            'email' => $email,
            'password' => $password,
            'password_again' => $password_again
        ];

        $validator = validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required|max:16|min:6',
            'password_again' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return ApiResponseClass::errorResponse('You Have Some Input Errors', $inputs, $validator->errors());
        }

        $isUrlUser = $request->is('user/*');
        $isUrlAdmin = $request->is('admin/*');
        $isUrlTeacher = $request->is('teacher/*');

        $group_id = null;

        if ($isUrlUser) {
            $group_id = Groups::Student_Group_Id;
        } elseif ($isUrlAdmin) {
            $group_id = Groups::Administrator_Group_ID;
        } elseif ($isUrlTeacher) {
            $group_id = Groups::Teacher_Group_Id_Group_ID;
        }

        DB::beginTransaction();

        try {

            Groups::findorFail($group_id);

            $user = new User();
            $user->email = $email;
            $user->password = Hash::make('password');
            $user->activated = 0;
            $user->email_updated_at = date("Y-m-d h:i:s");
            $user->password_updated_at = date("Y-m-d h:i:s");
            $user->activation_code = str_random(64);

            if (!$user->save()) {
                throw new \ErrorException();
            }

            $user_group = new UsersGroups();
            $user_group->user_id = $user->id;
            $user_group->groups_id = $group_id;

            if (!$user_group->save()) {
                throw new \ErrorException();
            }

            DB::commit();

        } catch (ModelNotFoundException  $e) {
            DB::rollback();
            return ApiResponseClass::errorResponse('ModelNotFoundException', $inputs);
        } catch (\ErrorException $e) {
            DB::rollback();
            return ApiResponseClass::errorResponse('ModelNotSavedException', $inputs);
        }

        // Send mail to the user if not the test Shop Id.

        return ApiResponseClass::successResponse($user, $inputs);
    }

    public function postSignIn(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember');

        $inputs = [
            'email' => $email,
            'password' => $password,
        ];

        $validator = validator::make($request->all(), [
            'email' => 'required|unique:users|email',
            'password' => 'required|max:16|min:6'
        ]);

        if ($validator->fails()) {
            return ApiResponseClass::errorResponse('You Have Some Input Errors', $inputs, $validator->errors());
        }else{
            $auth = Auth::attemp([
                'email' => $email,
                'password' => $password,
                'active' => 1
            ], $remember);

            if($auth){
                $user = Auth::user();
                $login_flag = 1;
                $result = array(
                    'user' => $user,
                    'login_flag' => $login_flag
                );
                return ApiResponseClass::successResponse($result, $inputs);
            }
        }
    }
}
