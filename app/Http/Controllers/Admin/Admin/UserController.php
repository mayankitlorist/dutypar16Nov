<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offices;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;

use App\Models\RoleType;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application Office.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        // print_r($user['role_type']); die;
        if($user['role_type'] == 3){
            $user->id =  $user['parent_id'];
        }
        $users = User::where('parent_id',$user->id)->orWhere('id',$user->id)->orderBy('created_at', 'desc')->get();
        $trai =      User::where('parent_id',Auth::id())->where('role_type',3)->first();
        foreach ( $users as $key =>  $user) {
        	if($trai){
        		$user->trainer=$trai->name;
        	}else{
        		$user->trainer='';
        	}	
        }
        // print_r($users); die;
        $users =  $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'uid' => $user->uid,
                'email' => $user->email,
                'role_type' => $user->role_type,
                'created_at' => $user->created_at,
                'status' => $user->status,
                'parent_id' => $user->parent_id,
                'office_id' => $this->getOffices($user->office_id),
                'profile_image' => $user->profile_image,
                'phone' => $user->phone,
                'trainer'=>$user->trainer

            ];
        });
        $admins = User::where('role_type','1')->where('organization_id',$user->organization_id)->get();
        $offices = Offices::where('organization_id',$user->organization_id)->get();
        $roles = RoleType::all();

        return view('admin.user.index', compact('user','users','offices','admins','roles'));
    }

    public function getOffices($id) {
        $officeDe = explode(',',$id);
        $officeDetails = Offices::whereIn('id',$officeDe)->get();
        return $officeDetails;
    }
    public function addUser(Request $request)
    {
        $rules = [
            'name' => 'required',
            'password' => 'required',
            'uid' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'role_type' => 'required',
            'office_id' => 'required',
            // 'profile_image' => 'mimes:jpeg,jpg,png,gif|max:2048'
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));

        } else {
            unset($requestData['_token']);
            $offices = $request->office_id;
            $office = implode(',', $offices);
            $requestData['office_id'] = $office;
            $requestData['password'] = bcrypt($request->password);
            $requestData['status'] =1;
            $requestData['parent_id'] = Auth::id();
            $requestData['organization_id'] = auth()->user()->organization_id;

            if ($request->file('profile_image')) {

                // print_r($request->profile_image); die;
                        // if($request->profile_image){
                           $cover = $request->profile_image;
                           $coverPic = 'img_' . time() . '.' . $cover->getClientOriginalExtension();
                           $cover = $cover->storeAs('ProImage', $coverPic);
                        // }
                            $profileName = url('../storage/app/'. $cover);
                            // print_r($profileName); die;
                            $requestData['profile_image'] = $profileName;


//            profile image upload
                // $profileImage = $request->file('profile_image');
                // $profileName = time() . 'profile.' . $profileImage->getClientOriginalExtension();
                // Storage::disk('public')->put($profileName,  File::get($profileImage));
                // $requestData['profile_image'] = Storage::disk('public')->url($profileName);
            }
            $success = User::create($requestData);
            return Redirect::route('admin.userlist')->with('success','User added successfully!');
        }

    }

    public function updateUser(Request $request)
    {
        $rules = [
            'name' => 'required',
            'role_type' => 'required',
            'office_id' => 'required',
            'profile_image' => 'mimes:jpeg,jpg,png,gif|max:2048',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'uid' => 'required|unique:users,uid,'.$request->id,
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput($request->except('password'));
        }
        else {
            $offices = $request->office_id;
            $office = implode(',', $offices);
            $requestData['office_id'] = $office;
            if ($request->file('profile_image')) {

               $cover = $request->profile_image;
               $coverPic = 'img_' . time() . '.' . $cover->getClientOriginalExtension();
               $cover = $cover->storeAs('ProImage', $coverPic);
                // $requestData['profile_image'] = $cover;
                $profileName = url('../storage/app/'. $cover);
                            // print_r($profileName); die;
                            $requestData['profile_image'] = $profileName;

//            profile image upload
               // $profileImage = $request->file('profile_image');
               // $profileName = time() . 'profile.' . $profileImage->getClientOriginalExtension();
               // Storage::disk('public')->put($profileName,  File::get($profileImage));
               // $requestData['profile_image'] = Storage::disk('public')->url($profileName);
            }
            unset($requestData['_token']);
            User::where('id',$request->id)->update($requestData);
            return Redirect::route('admin.userlist')->with('success','User updated successfully!');
        }
    }

    public function deleteUser($id)
    {
        User::where('id',$id)->delete();
        return Redirect::route('admin.userlist');
    }



























    public function index1()
    {
        $user = Auth::user();
        // print_r($user['role_type']); die;
        if($user['role_type'] == 3){
            $user->id =  $user['parent_id'];
        }
        $users = User::where('parent_id',$user->id)->orWhere('id',$user->id)->orderBy('created_at', 'desc')->get();
        $trai =      User::where('parent_id',Auth::id())->where('role_type',3)->first();
        foreach ( $users as $key =>  $user) {
            if($trai){
                $user->trainer=$trai->name;
            }else{
                $user->trainer='';
            }   
        }
        // print_r($users); die;
        $users =  $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'uid' => $user->uid,
                'email' => $user->email,
                'role_type' => $user->role_type,
                'created_at' => $user->created_at,
                'status' => $user->status,
                'parent_id' => $user->parent_id,
                'office_id' => $this->getOffices($user->office_id),
                'profile_image' => $user->profile_image,
                'phone' => $user->phone,
                'trainer'=>$user->trainer

            ];
        });
        $admins = User::where('role_type','1')->where('organization_id',$user->organization_id)->get();
        $offices = Offices::where('organization_id',$user->organization_id)->get();
        $roles = RoleType::all();

        return view('admin.user.user', compact('user','users','offices','admins','roles'));
    }

    public function removeimg(Request $request)
    {
        

        $id = $request->id;
        //print_r($id); die;
        
        User::where('id',$id)->update(['profile_image'=>null]);
        
         //print_r($userdelete); die;

       return response()->json();

    }

    public function updateUser1(Request $request)
    {
        $rules = [
            'name' => 'required',
            'role_type' => 'required',
            'office_id' => 'required',
            'profile_image' => 'mimes:jpeg,jpg,png,gif|max:2048',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'uid' => 'required|unique:users,uid,'.$request->id,
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput($request->except('password'));
        }
        else {
            $offices = $request->office_id;
            $office = implode(',', $offices);
            $requestData['office_id'] = $office;
            if ($request->file('profile_image')) {

               $cover = $request->profile_image;
               $coverPic = 'img_' . time() . '.' . $cover->getClientOriginalExtension();
               $cover = $cover->storeAs('ProImage', $coverPic);
                // $requestData['profile_image'] = $cover;
                $profileName = url('../storage/app/'. $cover);
                            // print_r($profileName); die;
                            $requestData['profile_image'] = $profileName;

          }

            $requestData['approved'] = 1;
            unset($requestData['_token']);
            // print_r($requestData); die;
            User::where('id',$request->id)->update($requestData);
            return Redirect::route('admin.userlist')->with('success','User updated successfully!');
        }
    }

     public function approveUser($id)
    {
        
        User::where('id',$id)->update(['approved'=>1]);
        return Redirect::route('admin.userlist');

    }

}
