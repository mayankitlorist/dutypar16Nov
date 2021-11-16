<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Models\Offices;
use App\Models\User_office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Models\OfficeAddUserModel;

class OfficeController extends Controller
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
          $offices = User_office::loginuseroffices($user->id);

          // $offices = User_office::loginuseroffices($user->id);

          return view('admin.office.index', compact('user','offices'));
    }

    public function addOffice(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'distance' => 'required|digits_between:1,3'
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();

        } else {
            unset($requestData['_token']);
            $requestData['organization_id'] = auth()->user()->organization_id;
            $success = Offices::insertGetId($requestData);
            OfficeAddUserModel::insert(['user_id'=>$user->id,'office_id'=>$success]);
            // print_r($success); die;
            return Redirect::route('admin.officelist')->with('success','Office added successfully!');
        }

    }

    public function updateOffice(Request $request)
    {
        $rules = [
            'name' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'distance' => 'required|digits_between:1,3'
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();

        } else {
            unset($requestData['_token']);
            Offices::where('id',$request->id)->update($requestData);
            return Redirect::route('admin.officelist')->with('success','Office updated successfully!');
        }

    }

    public function deleteOffice($id)
    {
        Offices::where('id',$id)->delete();
        return Redirect::route('admin.officelist');
    }

}
