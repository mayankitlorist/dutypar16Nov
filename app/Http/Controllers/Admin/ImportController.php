<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Batch;
use App\Models\FileImports;
use App\Models\Offices;
use App\Models\Schema;
use App\Models\User_batch;
use App\Models\User_office;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function indexs(Request $request)
    { 
        
        $data = $request->session()->get('id');
        if(!empty($data)){
            $alldatas = FileImports::where('user_id',$data)->get();         
        }else{
            $alldatas = array();
        }
        
        return view('admin.import.import',compact('alldatas'));
    }

    public function addimportFile(Request $request)
    {
        $excell = Excel::import(new UsersImport(), $request->file);

        $isdata = $request->session()->get('isdata');

        if ($isdata == 0) {
            return back()->with('error', 'Import data Error!');
        }

        $data = $request->session()->get('id');
       
        $getallimportdata = FileImports::where('user_id', $data)->get();
        $allteacherid = [];
        foreach ($getallimportdata as $getallimportdatas) {
            $addoffice = Offices::where('name', $getallimportdatas->vtp_name)->first();

            if (empty($addoffice)) {
                $obj1 = [
                      'name' => $getallimportdatas->vtp_name,
                      'location' => $getallimportdatas->vtp_address,
                      'latitude' => $getallimportdatas->latitude,
                      'longitude' => $getallimportdatas->longitude,
                      'distance' => '200',
                      'organization_id' => 1,
                      'code' => $getallimportdatas->vtp_no,
                      ];

                $getofficeid = Offices::insertGetId($obj1);
            } else {
                $getofficeid = $addoffice->id;
                
            }

            $addscheme = Schema::where('name', $getallimportdatas->scheme_name)->first();
            if (empty($addscheme)) {
                $obj2 = [
                            'name' => $getallimportdatas->scheme_name,
                            'is_active' => 1,
                        ];
                $getschemeid = Schema::insertGetId($obj2);
            } else {
                $getschemeid = $addscheme->id;
                
            }

            $addbatch = Batch::where('batch_name', $getallimportdatas->tbn_number)->first();

            if (empty($addbatch)) {
                $obj3 = [
                            'batch_name' => $getallimportdatas->tbn_number,
                            'scheme_id' => $getschemeid,
                            'is_active' => 1,
                            'hours' => $getallimportdatas->perday_time,
                        ];

                $getbatchid = Batch::insertGetId($obj3);
            } else {
                $getbatchid = $addbatch->id;
                // print_r($getbatchid); die;
            }

            $teacherget = User::where('uid', $getallimportdatas->vtp_no)->first();
            
            if (empty($teacherget)) {
                
                $teachadd = [
                                        'uid' => $getallimportdatas->vtp_no,
                                        'name' => $getallimportdatas->spoc_Name,
                                        'email' => $getallimportdatas->spoc_email_id,
                                        'password' => bcrypt('123456'),
                                        'role_type' => 1,
                                        'office_id' => $getofficeid,
                                        'phone' => $getallimportdatas->spoc_mobile_no,
                                        'organization_id' => 1,
                            ];
                       
                $teacherid = User::insertGetId($teachadd);
                
                array_push($allteacherid, $teacherid);
            } else {
                
                $teacherid = $teacherget->id;
                array_push($allteacherid, $teacherid);
                

            }

            $studentget = User::where('uid', $getallimportdatas->id)->first();
            if (empty($studentget)) {
                echo($getallimportdatas->id);

                $studentdd = [
                                   'uid' => $getallimportdatas->id,
                                   'name' => $getallimportdatas->first_name.' '.$getallimportdatas->last_name,
                                   'email' => $getallimportdatas->id.'@gmail.com',
                                   'password' => bcrypt('123456'),
                                   'role_type' => 2,
                                   'office_id' => $getofficeid,
                                   'phone' => $getallimportdatas->mobile,
                                   'organization_id' => 1,
                                   'parent_id' => $teacherid,
                             ];
                     
                $studentid = User::insertGetId($studentdd);
           
            } else {

                $studentid = $studentget->id;
            }

            $tarinerget = User::where('uid', $getallimportdatas->trainer_id)->first();

            if (empty($tarinerget)) {
                $tarinerdd = [
                                          'uid' => $getallimportdatas->trainer_id,
                                          'name' => $getallimportdatas->trainer_name,
                                          'email' => $getallimportdatas->trainer_id.'@gmail.com',
                                          'password' => bcrypt('123456'),
                                          'role_type' => 3,
                                          'office_id' => $getofficeid,
                                          'organization_id' => 1,
                                          'parent_id' => $teacherid,
                              ];
                           // echo "tttt";  print_r($tarinerdd );

                $tarnerid = User::insertGetId($tarinerdd);
            } else {
                $tarnerid = $tarinerget->id;
            }

            $userbatch = User_batch::where('user_id', $studentid)->where('batch_id', $getbatchid)->first();
            // print_r($userbatch); die;
            if (empty($userbatch)) {
                $userbatchadd = [
                                                  'user_id' => $studentid,
                                                  'batch_id' => $getbatchid,
                                                  'is_online' => 0,
                                                  'is_active' => 1,
                                      ];

                $userbatchid = User_batch::insertGetId($userbatchadd);
            } else {
                $userbatchid = $userbatch->id;
            }

            $userbatchteacher = User_batch::where('user_id', $teacherid)->where('batch_id', $getbatchid)->first();
            // print_r($userbatch); die;
            if (empty($userbatchteacher)) {
                $userbatchteacheradd = [
                                                  'user_id' => $teacherid,
                                                  'batch_id' => $getbatchid,
                                                  'is_online' => 0,
                                                  'is_active' => 1,
                                      ];

                $userbatchteacherid = User_batch::insertGetId($userbatchteacheradd);
            } else {
                $userbatchteacherid = $userbatchteacher->id;
            }

            $userbatchtrainer = User_batch::where('user_id', $tarnerid)->where('batch_id', $getbatchid)->first();
            // print_r($userbatch); die;
            if (empty($userbatchtrainer)) {
                $userbatchtraineradd = [
                                              'user_id' => $tarnerid,
                                              'batch_id' => $getbatchid,
                                              'is_online' => 0,
                                              'is_active' => 1,
                                  ];

                $userbatchtrainerid = User_batch::insertGetId($userbatchtraineradd);
            } else {
                $userbatchtrainerid = $userbatchtrainer->id;
            }

            $useroffice = User_office::where('user_id', $studentid)->where('user_batch_id', $userbatchid)->where('office_id', $getofficeid)->first();
            if (empty($useroffice)) {
                $userofficeadd = [
                                        'user_batch_id' => $userbatchid,
                                        'user_id' => $studentid,
                                        'office_id' => $getofficeid,
                                        'is_active' => 1,
                                        ];

                $userofficeid = User_office::insertGetId($userofficeadd);
            } else {
                $userofficeid = $useroffice->id;
            }

            $userofficeteacher = User_office::where('user_id', $teacherid)->where('user_batch_id', $userbatchteacherid)->where('office_id', $getofficeid)->first();
            if (empty($userofficeteacher)) {
                $userofficeteacheradd = [
                                        'user_batch_id' => $userbatchteacherid,
                                        'user_id' => $teacherid,
                                        'office_id' => $getofficeid,
                                        'is_active' => 1,
                                        ];

                $userofficeteacherid = User_office::insertGetId($userofficeteacheradd);
            } else {
                $userofficeteacherid = $userofficeteacher->id;
            }

            $userofficetrainer = User_office::where('user_id', $tarnerid)->where('user_batch_id', $userbatchtrainerid)->where('office_id', $getofficeid)->first();

            if (empty($userofficetrainer)) {
                $userofficetraineradd = [
                                        'user_batch_id' => $userbatchtrainerid,
                                        'user_id' => $tarnerid,
                                        'office_id' => $getofficeid,
                                        'is_active' => 1,
                                        ];

                $userofficetrainerid = User_office::insertGetId($userofficetraineradd);
            } else {
                $userofficetrainerid = $userofficetrainer->id;
            }
        }
        $ides = array_unique($allteacherid);

        $teachusers = User::whereIn('id', $ides)->get();

     

            $alldata = FileImports::where('user_id',$data)->count();
            $alldatas = FileImports::where('user_id',$data)->get();

        // print_r($alldatas);
        //     die;
        return back()->with('success', 'Import data successfully!');
        // return view('admin.import.importdata',compact('alldatas'));

    }

}
