<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Models\Organization;
use App\Models\RoleType;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $rules = array(
            'email' => 'required',
            'password' => 'required');

        $validator = Validator::make($request->all() , $rules);
        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)
                ->withInput($request->except('password'));
        }
        else
        {
            $emailUser = User::where('email',$request->email)->first();

            $uidUser = User::where('uid',$request->email)->first();
            // print_r($emailUser); die;
            if($emailUser){
                $userdata = array(
                'email' => $request->email,
                'password' => $request->password,
                // 'role_type' => '1'
            );
                $userdata1 = array(
                'email' => $emailUser->email ,
                'password' => $request->password,
                // 'role_type' => '2'
            );

                // print_r(Auth::attempt($userdata)); die;

                if (Auth::attempt($userdata))
            {
                return redirect()->route('admin.dashboard');
            }

            }else if($uidUser){
            // print_r($uidUser->email); die;
                 $userdata3 = array(
                'email' =>$uidUser->email,
                'password' => $request->password,
                // 'role_type' => '1'
            );
            $userdata4 = array(
                'email' => $uidUser->email ,
                'password' => $request->password,
                // 'role_type' => '2'
            );
                // print_r(Auth::attempt($userdata3)); die;
            
            if (Auth::attempt($userdata3))
            {
                return redirect()->route('admin.dashboard');
            }

            }
            
            else
            {
                return Redirect::back()->withInput($request->except('password'))
                    ->withErrors(['Something went wrong!']);
            }
        }
    }

    public function showRegisterForm()
    {
        $roles = Organization::all();
        $types = RoleType::all();

        // print_r($roles); die;
        return view('admin.register',compact('roles','types'));
    }

    public function doRegister(Request $request)
    {
        
        $rules = [
            'name' => 'required',
            'password' => 'required',
            'uid' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'profile_image' => 'mimes:jpeg,jpg,png,gif|max:2048',
            'organization_id' => 'required'
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);
        $phone = $requestData['phone'];

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));

        } else {
            unset($requestData['_token']);
            $requestData['password'] = bcrypt($request->password);
            $requestData['status'] =1;
            $requestData['role_type'] = $request->organization_id;
            $requestData['phone'] = $phone;

            if ($request->file('profile_image')) {
//            profile image upload
                $profileImage = $request->file('profile_image');
                $profileName = time() . 'profile.' . $profileImage->getClientOriginalExtension();
                Storage::disk('public')->put($profileName,  File::get($profileImage));
                $requestData['profile_image'] = Storage::disk('public')->url($profileName);
            }
            $user = User::create($requestData);
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }
    }


}
