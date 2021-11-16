<?php

namespace App\Http\Controllers\Admin;

use App\SiteLogo;
use App\User;
use App\Models\UserAttendance;
use App\Models\User_batch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;
use DB;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Batch;

class DashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('admin.dashboard', compact('user'));
    }

    public function logout()
    {
        $user = Auth::logout();
        return redirect('admin/login');
    }

     public function dashboard1()
    {
        
        $vtp = User::where('role_type',1)->count();
        $student = User::where('role_type',2)->count();
           $batch = Batch::count();
        
        return view('admin.dashboard1',compact('vtp','student','batch'));
    }

    public function newdashboard(Request $request){
            // print_r($request->district); die;
            $districtbatchcenter['district'] = $request->district; 
            $districtbatchcenter['centers'] =  $request->centers;
            $districtbatchcenter['batch'] =  $request->batches;

            if($request->district || $request->centers || $request->batches){
                 $districts = DB::table('district')->get();

                if($request->batches){
                    $countstudentbatch = DB::table('user_batch')->where('batch_id',$request->batches)->count();
                    $countdistrict = 1;
                    $countcenters = 1;
                    $countbatches = 1;

                    $getcenteruser =array();
                    $office  = DB::table('offices')->where('id',$request->centers)->first();
                    $district = DB::table('district')->where('id', $request->district)->first();
                    $office->districtname = $district->district_name;
                    $office->centername = '';
                    array_push( $getcenteruser, $office);


                    $batchDatas = array();

                         $batchname = array();
                         $presenuser = array();
                         $absentuser = array();

                    $batch = Db::table('batch')->where('id',$request->batches)->first();
                    $batch->districtname = $district->district_name;
                    $batch->centername = '';
                    $batch->location = $office->location;
                    $batch->code = $office->code;
                    array_push( $batchDatas, $batch);

                        array_push( $batchDatas, $batch);
                         array_push( $batchname, $batch->batch_name);

                      $us = DB::table('user_batch')->join('users','user_batch.user_id','=','users.id')->where('batch_id',$request->batches)->groupBy('users.id')->pluck('users.id');

                        $presen = DB::table('user_attendance')->whereIn('user_id', $us)->whereDate('intime',date('Y-m-d'))->count();
                        // print_r($presen); die;
                        if($presen == 0){
                            array_push($presenuser, $presen);
                            array_push($absentuser, count($us));

                        }else{
                                 array_push($presenuser, $presen);
                                 $ab = count($us) - $presen;
                                 array_push($absentuser, $ab);
                        }



                    $alluser  = DB::table('user_batch')->where('batch_id',$request->batches)->pluck('user_id')->unique('user_id');

                    $allusers = Db::table('user_attendance')->join('users','user_attendance.user_id','=','users.id')->whereIn('user_attendance.user_id',$alluser)->get();



                     // print_r( $alluser); die;
                   
            }elseif($request->centers){

                    $countstudentbatch = DB::table('users')->where('office_id',$request->centers)->count();
                    $countdistrict = 1;
                    $countcenters = 1;
                    $countbatches = 0;

                    $getcenteruser =array();
                    $office  = DB::table('offices')->where('id',$request->centers)->first();
                    $district = DB::table('district')->where('id', $request->district)->first();
                    $office->districtname = $district->district_name;
                    $office->centername = '';
                    array_push( $getcenteruser, $office);

                    $allusers = DB::table('users')->join('user_batch','users.id','=','user_batch.user_id')->where('users.office_id', $request->centers)->pluck('user_batch.batch_id');
                    $batchDatass  = DB::table('batch')->whereIn('id',$allusers)->get();
                         $batchDatas = array();
                         $batchname = array();
                         $presenuser = array();
                         $absentuser = array();

                     foreach($batchDatass as  $batch){
                        
                        $batch->districtname = $district->district_name;
                        $batch->centername = '';
                        $batch->location = $office->location;
                        $batch->code = $office->code;
                        array_push( $batchDatas, $batch);



                         array_push( $batchDatas, $batch);
                         array_push( $batchname, $batch->batch_name);

                      $us = DB::table('user_batch')->join('users','user_batch.user_id','=','users.id')->where('batch_id',$batch->id)->groupBy('users.id')->pluck('users.id');

                        $presen = DB::table('user_attendance')->whereIn('user_id', $us)->whereDate('intime',date('Y-m-d'))->count();
                        // print_r($presen); die;
                        if($presen == 0){
                            array_push($presenuser, $presen);
                            array_push($absentuser, count($us));

                        }else{
                                 array_push($presenuser, $presen);
                                 $ab = count($us) - $presen;
                                 array_push($absentuser, $ab);
                        }

                    }

                    // print_r($batchDatass); die;


                    $allusers = DB::table('users')->join('user_batch','users.id','=','user_batch.user_id')->where('users.office_id',$request->centers )->pluck('user_batch.batch_id');

           			$allbatchs  = DB::table('batch')->whereIn('id',$allusers)->pluck('id');

                    $alluser  = DB::table('user_batch')->whereIn('batch_id',$allbatchs)->pluck('user_id')->unique('user_id');
                    // print_r($alluser); die;
                    
                    $allusers = Db::table('user_attendance')->join('users','user_attendance.user_id','=','users.id')->whereIn('user_attendance.user_id',$alluser)->get();

                    // print_r($allusers); die;
                    // $batchDatas = [];
           
            }else{
                    $alloffices = DB::table('offices')->where('district_id',$request->district)->pluck('offices.id');
                    $countstudentbatch = DB::table('users')->whereIn('office_id',$alloffices)->count();
                    $countdistrict = 1;
                    $countcenters = 0;
                    $countbatches = 0;

                    $getcenteruser = DB::table('offices')->where('district_id',$request->district)->get();
                    foreach ($getcenteruser as $getcenterusers) {
                         $district = DB::table('district')->where('id', $getcenterusers->district_id)->first();
                         $getcenterusers->districtname = $district->district_name;
                        $getcenterusers->centername = '';
                    }  

                     $batchDatas = array();
                     $batchname = array();
                     $presenuser = array();
                     $absentuser = array();



                    $allcenter = DB::table('offices')->where('district_id',$request->district)->get();

                    foreach ($allcenter as $allcenters) {
                        
                        $allusers = DB::table('users')->join('user_batch','users.id','=','user_batch.user_id')->where('users.office_id', $allcenters->id)->pluck('user_batch.batch_id');
                         $batchDatass  = DB::table('batch')->whereIn('id',$allusers)->get();


                     foreach($batchDatass as  $batch){
                        
                        $batch->districtname = $district->district_name;
                        $batch->centername = '';
                        $batch->location = $allcenters->location;
                        $batch->code = $allcenters->code;

                        array_push( $batchDatas, $batch);
                        array_push( $batchname, $batch->batch_name);

                      $us = DB::table('user_batch')->join('users','user_batch.user_id','=','users.id')->where('batch_id',$batch->id)->groupBy('users.id')->pluck('users.id');

                        $presen = DB::table('user_attendance')->whereIn('user_id', $us)->whereDate('intime',date('Y-m-d'))->count();
                        // print_r($presen); die;
                        if($presen == 0){
                            array_push($presenuser, $presen);
                            array_push($absentuser, count($us));

                        }else{

                                 array_push($presenuser, $presen);
                                 $ab = count($us) - $presen;
                                 array_push($absentuser, $ab);
                        }
                    }

                    }
            }
            // print_r($batchname); die;
                $all_dates =array();
                $usersdata= array();
                 $districts = DB::table('district')->get();
                    return view('admin.newdashboard',compact('districts','countstudentbatch','countdistrict','countcenters','countbatches','batchDatas','batchname','presenuser','absentuser','getcenteruser','districtbatchcenter','all_dates','usersdata'));
            }
                    $all_dates =array();
                    $usersdata= array();
                    $countstudentbatch =0;
                    $countdistrict = 0;
                    $countcenters = 0;
                    $countbatches = 0;
                    $districts = DB::table('district')->get();
                    $batchDatas = array();
                    $getcenteruser = array();
                    $batchname = array();
                    $presenuser = array();
                    $absentuser = array();
                    
         return view('admin.newdashboard',compact('districts','countstudentbatch','countdistrict','countcenters','countbatches','batchDatas','batchname','presenuser','absentuser','getcenteruser','districtbatchcenter','all_dates','usersdata'));
    }

    public function districts(Request $request){

        $id = $request->districts;
        $alloffices = DB::table('offices')->where('district_id',$id)->get();
        return response()->json($alloffices);

    }

    public function centers(Request $request){

            $id = $request->centers;

            $allusers = DB::table('users')->join('user_batch','users.id','=','user_batch.user_id')->where('users.office_id', $id )->pluck('user_batch.batch_id');
            $allbatchs  = DB::table('batch')->whereIn('id',$allusers)->get();
             return response()->json($allbatchs);

    }

    public function getonemonthatt(Request $request){

        if($request->sectedbatch){

            $startDate = new Carbon($request->date1);
            $endDate = new Carbon($request->date30);
            $all_dates = array();
            while ($startDate->lte($endDate)){
            $all_dates[] = $startDate->toDateString();

            $startDate->addDay();
            }
           
            $dates = count($all_dates);
            $usersdata = DB::table('user_batch')->join('users','user_batch.user_id','=','users.id')->where('batch_id',$request->sectedbatch)->select('users.id','users.name','users.uid','user_batch.batch_id')->get()->unique('id');
            
            foreach ($usersdata as $usersdatas) {
                for ($i=0; $i < $dates; $i++) {
                    $getattend = DB::table('user_attendance')->where('user_id', $usersdatas->id)->where('batch_id',$request->sectedbatch)->whereDate('intime', $all_dates[$i])->first();
                    if($getattend){
                        $usersdatas->attandancedates[] = "Yes";
                    }else{
                        $usersdatas->attandancedates[] = "No";
                    } 
                }
            }
           
                   $countstudentbatch =0;
                    $countdistrict = 0;
                    $countcenters = 0;
                    $countbatches = 0;
                    $districts = DB::table('district')->get();
                    $batchDatas = array();
                    $getcenteruser = array();
                    $districtbatchcenter['district'] = $request->secteddistrict;
                    $districtbatchcenter['centers'] =   $request->sectedcenters;
                    $districtbatchcenter['batch'] = $request->sectedbatch;
         return view('admin.newdashboard',compact('districts','countstudentbatch','countdistrict','countcenters','countbatches','batchDatas','getcenteruser','districtbatchcenter','all_dates','usersdata'));
        }
    }


    public function getattand(Request $request){

        $batchid = $request->batchid;
        $date11 = $request->date11;
        $date31 = $request->date31;
        $batchname = $request->batchname;
        
        $startDate = new Carbon($date11);
            $endDate = new Carbon($date31);
            $all_dates = array();
            while ($startDate->lte($endDate)){
            $all_dates[] = $startDate->toDateString();

            $startDate->addDay();
        }
        $dateal = count($all_dates);
        $usersdata = DB::table('user_batch')->join('users','user_batch.user_id','=','users.id')->where('batch_id',$batchid)->select('users.id','users.name','users.uid','user_batch.batch_id')->get()->unique('id');
        foreach($usersdata as $usersdatas){
            $getattend = DB::table('user_attendance')->where('user_id', $usersdatas->id)->where('batch_id' , $batchid)->whereBetween('intime', [$date11, $date31])->select( DB::raw('DATE(`intime`) as intime' ))
            ->get()->unique('intime')->pluck('intime');

            $usersdatas->attandance = $getattend;
            $usersdatas->countdata = count($getattend);
            $usersdatas->batchname = $batchname;
        }
        
       return response()->json(['dates' => $all_dates , 'data' => $usersdata]);
    }
   
}
