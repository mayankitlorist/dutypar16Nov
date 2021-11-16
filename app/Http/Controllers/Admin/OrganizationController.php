<?php

namespace App\Http\Controllers\Admin;

use App\Models\Offices;
use App\Models\Organization;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;

class OrganizationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application Office.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showForm()
    {
        return view('admin.organization.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:organizations',
        ];

        $requestData = $request->all();
        $validator = Validator::make($requestData, $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();

        } else {
            unset($requestData['_token']);
            $success = Organization::create($requestData);
            return redirect()->back()->with('success','Organization added successfully!');
        }

    }
}
