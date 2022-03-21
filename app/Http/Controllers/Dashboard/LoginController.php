<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    //

    public function login(){
        return view('dashboard.auth.login');
    }

    public function postLogin(AdminLoginRequest $request){

        //return $request;

        //validation

        $remember_me = $request->has('remember_me') ? true : false;

        if (auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
           // notify()->success('تم الدخول بنجاح  ');
            return redirect() -> route('admin.dashboard');
        }
       // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);



    }



    public function logout(){

        $gaurd = $this -> getGaurd();
        $gaurd -> logout();

        return Redirect() -> route('admin.login');
    }


    private function getGaurd(){

        return auth('admin');
    }

}
