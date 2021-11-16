<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\LevelCategory;
use App\Models\Notifications;
use App\Models\Offices;
use App\Models\RequestLogs;
use App\Models\User_batch;
use App\Models\UserAttendance;
use App\Models\UserDetails;
use App\Models\UserLeaveDetails;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;

// use DB;

// use DB;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        try {
            $rules = [
                'uid' => 'required',
                'password' => 'required',
                'firebase_token' => 'required',
                'device_type' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $uid = $request->uid;
                $password = $request->password;
                $role_type = $request->role_type;
                $user = User::where('uid', $uid)->first();
                if (!$user) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'Wrong Username or Password.'];
                } elseif ($user && !Hash::check($password, $user->password)) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'Wrong Username or Password.'];
                } else {
                    $data = ['firebase_token' => $requestData['firebase_token'], 'device_type' => $requestData['device_type']];
                    $userId = $user->id;
                    User::where('id', $userId)->update($data);
                    $officeId = $user->office_id;
                    // print($officeId); die;

                    $officeDe = explode(',', $officeId);
                    // print($officeId); die;
                    $officeDetails = Offices::whereIn('id', $officeDe)->get();
                    // print_r($user->role_type); die;
                    if ($user->role_type == 1) {
                        // $user['officeDetails'] = Offices::all();
                        $user['officeDetails'] = Offices::where('id', $officeId)->get();

                        $user['userslist'] = User::where('parent_id', $user->id)->take(5)->get();
                    } else {
                        $user['officeDetails'] = $officeDetails;
                    }
                    // print_r($user['officeDetails']); die;

                    $user['category'] = LevelCategory::select('id', 'category', 'type')->get();
                    $userAttendance = UserAttendance::where('user_id', $userId)->whereDate('intime', Carbon::now())->whereNull('outtime')->first();
                    $user['attendance_id'] = $userAttendance ? $userAttendance->id : 0;
                    $user['todayAttendance'] = $userAttendance ? true : false;
                    $response = ['success' => true, 'message' => 'user login successfully', 'data' => $user];
                }
            }
            // print_r($response); die;
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::insert($logData);
            // print_r($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $rules = [
                'email' => 'required',
                'role_type' => 'required|numeric',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $email = $request->email;
                $role_type = $request->role_type;
                $user = User::where('email', $email)->where('role_type', $role_type)->first();
                if (!$user) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'This email address does not exist.'];
                } else {
                    $otp = rand(111111, 999999);

                    // Mail::send('admin.email.forgot_password', ['otp' => $otp], function ($message) use ($email) {
                    //     $message->to($email);
                    //     $message->subject('Forgot Password');
                    // });
                    $subject = 'Forgot Passwort';
                    $message = 'Yor Otp Is '.$otp.'.';

                    mail($email, $subject, $message);

                    $user->password = bcrypt($otp);
                    $user->save();
                    // print_r( $user->password); die;
                    $response = ['success' => true, 'message' => 'We have sent password on your mail address .'];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function userDetails(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $user_id = $request->user_id;
                $userDetails = UserDetails::where('user_id', $user_id)->first();
                // print_r($user_id); die;///
                if (!$userDetails) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'User details does not exist.', 'data' => []];
                } else {
                    $response = ['success' => true, 'message' => 'User details get successfully', 'data' => $userDetails];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function applyForLeave(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'category' => 'required',
                'leave_type' => 'required',
                'firebase_token' => 'required',
                'type' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $from = $requestData['from_date'];
                //print_r( $from); die;
                $to = $requestData['to_date'];
                $leavefromCheack = UserLeaveDetails::where('user_id', $requestData['user_id'])->where('from_date', '<=', $from)->where('to_date', '>=', $from)->get();
                $leavetoCheack = UserLeaveDetails::where('user_id', $requestData['user_id'])->where('from_date', '<=', $to)->where('to_date', '>=', $to)->get();
                $fromcount = $leavefromCheack->count();
                $tocount = $leavetoCheack->count();
                if ($fromcount < 1 && $tocount < 1) {
                    $curentdate = date('Y-m-d');
                    if ($requestData['type'] == 'Half Day') {
                        if ($requestData['from_date'] != $requestData['to_date']) {
                            $response = ['success' => false, 'message' => 'For half day you should have  to same from and to date.'];
                        } else {
                            $requestData['status'] = 0;
                            $userDetails = UserLeaveDetails::create($requestData);
                            $leave_id = $userDetails->id;
                            $userid = $requestData['user_id'];
                            $user = User::where('id', $userid)->first();
                            if ($user->parent_id != '' && $user->parent_id != null) {
                                $parentId = User::where('id', $user->parent_id)->first();
                                $requestData['firebase_token'] = @$parentId->firebase_token;
                            } else {
                                $requestData['firebase_token'] = $requestData['firebase_token'];
                            }
                            $requestData['title'] = $requestData['leave_type'];
                            $requestData['username'] = $user->name;
                            $requestData['attendance_date'] = $requestData['from_date'];

                            //NotificationController::sendPushNotification($requestData);

                            $response = ['success' => true, 'message' => 'Your submittion successfully'];
                        }
                    } else {
                        $requestData['status'] = 0;
                        $userDetails = UserLeaveDetails::create($requestData);
                        $leave_id = $userDetails->id;
                        $userid = $requestData['user_id'];
                        $user = User::where('id', $userid)->first();
                        if ($user->parent_id != '' && $user->parent_id != null) {
                            $parentId = User::where('id', $user->parent_id)->first();
                            if ($parentId) {
                                $requestData['firebase_token'] = @$parentId->firebase_token;
                            } else {
                                $requestData['firebase_token'] = $requestData['firebase_token'];
                            }
                        } else {
                            $requestData['firebase_token'] = $requestData['firebase_token'];
                        }
                        $requestData['title'] = $requestData['leave_type'];
                        $requestData['username'] = $user->name;
                        $requestData['attendance_date'] = $requestData['from_date'];

                        NotificationController::sendPushNotification($requestData);
                        $notification = ['title' => $requestData['title'], 'description' => $requestData['reason'], 'sender_id' => $requestData['user_id'], 'reciver_id' => $user->parent_id, 'status' => '0', 'leave_id' => $leave_id, 'type' => 'leave', 'notification_date' => $from];
                        Notifications::create($notification);
                        $response = ['success' => true, 'message' => 'Your submittion successfully'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'Leave for this date(s) is already applied'];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function attendance(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required',
                'face_status' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'profile_image' => 'required',
                'type' => 'required',
            ];
            if ($request->type == 'out') {
                $rules['attendance_id'] = 'required';
            }
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $face_status = $request->face_status;
                $intime = date('Y-m-d H:i:s');
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                // print_r(explode(' ',$user->updated_at)); die;
                // $date = date('yy-m-d');
                // print_r($user->profile_image); die;
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $circle_radius = 6367;
                    $max_distance = 1;

                    $distance = true;
                    if ($request->file('profile_image')) {
                        $cover = $request->profile_image;
                        $coverPic = 'img_'.time().'.'.$cover->getClientOriginalExtension();
                        $cover = $cover->storeAs('TempImage', $coverPic);
                        // print_r($cover); die;
                    }
                    $profileName = url('../storage/app/'.$cover);
                    // print_r($profileName); die;
                    // $profile_image = Storage::disk('public')->url($profileName);
                    $requestData['profile_image'] = $profileName;
                    $faceData = FaceIdentityController::addImage($profileName);
                    // print_r($profileName); echo "=====";
                    // print_r($faceData); echo "2nd == ";
                    $faceId2 = '';
                    if ($faceData) {
                        $jsonDecode = json_decode($faceData);
                        if (!isset($jsonDecode->error)) {
                            $faceId2 = $jsonDecode[0]->faceId;
                            $updateTempfaceid = User::where('id', $id)->update(['face_id' => $faceId2, 'temp_image' => $profileName]);
                        }
                        // $face1Data = FaceIdentityController::addImage($user->profile_image);
                        $face1Data = FaceIdentityController::addImage($user->profile_image);
                        // print_r($face1Data); echo "======";
                        // print_r($user->profile_image);
                        if ($face1Data) {
                            $jsonDecode1 = json_decode($face1Data);
                            if (!isset($jsonDecode1->error)) {
                                $faceId1 = $jsonDecode1[0]->faceId;
                                $updateTempfaceid = User::where('id', $id)->update(['temp_face_id' => $faceId1]);
                            }
                            // else
                            // {
                            //     $faceId1 = $user->face_id;
                            // }
                        }

                        // $requestData['faceId1'] = $faceId1;
                        // $requestData['faceId2'] = $faceId2;

                        // print_r($user->toarray()); die;

                        $checkoldupdatefaceid = explode(' ', $user->updated_at);
                        $currentDate = date('yy-m-d');
                        if ($checkoldupdatefaceid[0] != $currentDate) {
                            // echo "inside";
                            // print_r($user->profile_image); die;
                            $face2Data = FaceIdentityController::addImage($user->profile_image);
                            // print_r($face2Data); die;
                            if ($face2Data) {
                                $jsonDecode2 = json_decode($face2Data);
                                if (!isset($jsonDecode2->error)) {
                                    $faceId21 = $jsonDecode1[0]->faceId;
                                    // print_r($faceId21); die;
                                    $updateTempfaceid = User::where('id', $id)->update(['face_id' => $faceId21]);
                                    // $user->temp_face_id=$faceId2;
                                // $user->save();
                                }
                                // else
                            // {
                            //     $faceId2 = $user->face_id;
                            // }
                            }
                        }

                        $userfaceid = User::where('id', $id)->select('face_id', 'temp_face_id')->first();
                        $faceVerify = FaceIdentityController::checkFace($userfaceid->temp_face_id, $userfaceid->face_id);
                        $requestData['faceverify'] = $faceVerify;
                        if ($faceVerify) {
                            $faceIdentity = json_decode($faceVerify);

                            if (isset($faceIdentity->error)) {
                                $response = ['success' => false, 'message' => 'face not found.'];
                            } else {
                                if ($distance && $faceIdentity->isIdentical) {
                                    $offices = explode(',', $user->office_id);
                                    $date = date('Y-m-d H:i:s');
                                    if ($request->type == 'in') {
                                        $attendanceCheack = UserAttendance::where('user_id', $id)->whereDate('intime', $intime)->first();
                                        if (!$attendanceCheack) {
                                            $data = ['location_status' => 1, 'face_status' => 1, 'intime' => $intime, 'status' => 1, 'user_id' => $id];
                                            $result = UserAttendance::create($data);
                                            $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $result];
                                        } else {
                                            $response = ['success' => false, 'message' => 'You already marked attendance for this day.'];
                                        }
                                    }
                                    if ($request->type == 'out') {
                                        $data = ['location_status' => 1, 'face_status' => 1, 'outtime' => $intime, 'status' => 1, 'user_id' => $id];
                                        UserAttendance::where('id', $request->attendance_id)->update($data);
                                        $result = UserAttendance::where('id', $request->attendance_id)->first();
                                        $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $result];
                                    }
                                } else {
                                    $response = ['success' => false, 'message' => 'Your face and latitude,longitude not match.'];
                                }
                            }
                        } else {
                            $response = ['success' => false, 'message' => 'Something wrong.'];
                        }
                    } else {
                        $response = ['success' => false, 'message' => 'FaceId not returned.'];
                    }
                }
            }
            //  print_r($response); die;
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function attendanceList(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                if (isset($requestData['month'])) {
                    $current = date('m');
                    $currentYear = date('Y', strtotime($requestData['month']));
                    $currentMonth = date('m', strtotime($requestData['month']));
                    if ($current == $currentMonth) {
                        $currentDay = date('d');
                    } else {
                        $currentDay = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
                    }
                } else {
                    $currentYear = date('Y');
                    $currentMonth = date('m');
                    $currentDay = date('d');
                }
                $id = $request->user_id;
                $role_type = $request->role_type;
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $notAttendance = [];
                    // for ($i = 01; $i <= $currentDay; ++$i) {
                    //     $startdate = $currentYear.'-'.$currentMonth.'-'.$i;

                    //     $userData = DB::table('user_attendance_history')->whereDate('intime', $startdate)
                    //         ->where('user_id', $id)->whereNotNull('intime')->whereNotNull('outtime')
                    //         ->select(DB::raw("TIMESTAMPDIFF(HOUR,intime,outtime) as hours,TIMESTAMPDIFF(MINUTE,intime,outtime) as minutes,DATE_FORMAT(intime, '%Y-%M-%d %H:%i:%s') as intime,DATE_FORMAT(outtime, '%Y-%M-%d %H:%i:%s') as outtime,DATE_FORMAT(intime, '%Y-%M-%d') as attendance_date,id"))
                    //         ->first();
                    //     $notificationDate = date('Y-m-d', strtotime($startdate));
                    //     $attendacepending = Notifications::whereDate('notification_date', $notificationDate)->where('sender_id', $id)->first();
                    //     if ($attendacepending) {
                    //         if ($attendacepending->status == 1) {
                    //             $status = 'approved';
                    //         } elseif ($attendacepending->status == 2) {
                    //             $status = 'cancelled';
                    //         } else {
                    //             $status = 'pending';
                    //         }
                    //         $notification_update = $attendacepending->type.' '.$status;
                    //         $attendance_status = false;
                    //     } else {
                    //         $notification_update = '';
                    //         $attendance_status = true;
                    //     }

                    //     if (!$userData) {
                    //         $notAttendance[] = ['available' => false, 'id' => '', 'hours' => 0, 'minutes' => 0, 'intime' => '', 'outtime' => '', 'attendance_date' => date('Y-M-d', strtotime($startdate)), 'attendance_status' => $attendance_status, 'notificatin_status' => $notification_update];
                    //     } else {
                    //         $notAttendance[] = ['available' => true, 'id' => $userData->id, 'hours' => $userData->hours, 'minutes' => $userData->minutes % 60, 'intime' => $userData->intime, 'outtime' => $userData->outtime, 'attendance_date' => $userData->attendance_date, 'attendance_status' => $attendance_status, 'notificatin_status' => $notification_update];
                    //     }
                    // }
                    // $list=array();
                    // $date=date('d');
                   
                    // for($d=1; $d<=$date; $d++)
                    // {
                    //     $time=mktime(12, 0, 0, date('m'), $d, date('Y'));
                    //     if (date('m', $time)==date('m'))
                    //         $list[]=date('Y-m-d', $time);
                    // }
                    // $all = array();
                    // foreach($list as $lists){
                    //     $Data = DB::table('user_attendance_history')->whereDate('intime',$lists)->where('user_id', $id)->get();
                    //     array_push($all , $Data);
                    // }
                    
                    $todayDate = date('Y-m-d');
                    $year = date('Y');
                    $month = date('m');
                    $date = '01';
                    $firstDate = $year . '-' . $month . '-' . $date;
                    $Data = DB::table('user_attendance_history')->where('user_id', $id)->whereBetween('intime',array($firstDate,$todayDate))->get();
                    
                    $response = ['success' => true, 'message' => 'Attendance get successfully.', 'data' => $Data];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function leaveList(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $leaveArray = [];
                    $leaveCategory = LevelCategory::where('type', 'leave')->get();

                    foreach ($leaveCategory as $category) {
                        $result = UserLeaveDetails::where('user_id', $id)
                            ->where('leave_type', 'leave')
                            ->where('status', '1')
                            ->whereNotIn('type', ['Half Day'])
                            ->where('category', $category->id)
                            ->select(DB::raw('(CASE
                        WHEN TIMESTAMPDIFF(DAY,from_date,to_date) = "0" THEN "1"
                        ELSE TIMESTAMPDIFF(DAY,from_date,to_date) +1
                        END) AS leave_count'))
                            ->first();

                        $resultHalf = UserLeaveDetails::where('user_id', $id)
                            ->where('leave_type', 'leave')
                            ->where('status', '1')
                            ->where('type', 'Half Day')
                            ->where('category', $category->id)
                            ->select(DB::raw('(IFNULL(Sum(category),0)) as leaveCount'))
                            ->first();
                        if (!$result) {
                            $fullDays = floatval(0);
                        } else {
                            $fullDays = floatval($result->leave_count);
                        }

                        $halfdays = floatval($resultHalf->leaveCount) / 2;
                        $total = $category->total_leave;
                        $takeLeave = $fullDays + $halfdays;
                        $reminder = $total - $takeLeave;

                        $leaveArray[$category->category] = ['total' => $total, 'takeleave' => $takeLeave, 'reminder' => $reminder];
                    }

                    $leavelist = UserLeaveDetails::leftjoin('leave_category', 'leave_category.id', 'user_leave_details.category')
                        ->where('user_id', $id)
                        ->where('leave_type', 'leave')
                        ->select(DB::raw('user_leave_details.category,leave_category.category as categoryname,reason,user_leave_details.type'),
                            DB::raw('DATE_FORMAT(user_leave_details.from_date, "%Y-%M-%d") as leave_from'),
                            DB::raw('DATE_FORMAT(user_leave_details.to_date, "%Y-%M-%d") as leave_to'),
                            DB::raw('DATE_FORMAT(user_leave_details.created_at, "%Y-%M-%d") as applied_on'),
                            DB::raw('(CASE
                        WHEN status = "0" THEN "Pending for approval"
                        WHEN status = "1" THEN "Approved"
                        ELSE "Cancel"
                        END) AS status_lable'),
                            DB::raw('(CASE
                        WHEN  user_leave_details.type = "Half Day" THEN ".5"
                        WHEN  TIMESTAMPDIFF(DAY,from_date,to_date) = "0" THEN "1"
                        ELSE TIMESTAMPDIFF(DAY,from_date,to_date) +1
                        END) AS days'))
                        ->get();
                    $response = ['success' => true, 'message' => 'Leave get successfully.', 'leaves' => [$leaveArray], 'leavelist' => $leavelist];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function updateAttendanceSheet(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'attendance_id' => 'required',
                'category' => 'required',
                'reason' => 'required',
                'available' => 'required',
                'firebase_token' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $attendance_id = $request->attendance_id;
                $intime = Carbon::now();
                $outtime = Carbon::now();
                $category = $request->category;
                $reason = $request->reason;
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                $attendacepending = Notifications::where('leave_id', $attendance_id)->where('type', 'attendance')->where('status', '0')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } elseif ($attendacepending) {
                    $response = ['success' => false, 'message' => 'Attendance already pending from admin side.'];
                } else {
                    $data = ['intime' => $intime, 'outtime' => $outtime, 'category' => $category, 'reason' => $reason];
                    if ($requestData['available'] == 'true') {
                        UserAttendance::where('id', $attendance_id)->update($data);
                        $addAttendance = $attendance_id;
                    } else {
                        $data['user_id'] = $user->id;
                        $data['face_status'] = 1;
                        $data['location_status'] = 1;
                        $data['status'] = 1;
                        $attendace = UserAttendance::create($data);
                        $addAttendance = $attendace->id;
                    }
                    $parent = $user->parent_id;
                    if ($parent) {
                        $userAdmin = User::where('id', $parent)->first();

                        if ($userAdmin) {
                            $data['firebase_token'] = @$userAdmin->firebase_token;
                        } else {
                            $data['firebase_token'] = @$request->firebase_token;
                        }
                    } else {
                        $data['firebase_token'] = @$request->firebase_token;
                    }
                    $data['attendance_id'] = $attendance_id;
                    $data['username'] = @$user->name;
                    $data['attendance_date'] = $intime;

                    $data['title'] = 'Attendance update';
                    $data[] = 'Attendance update';
                    NotificationController::sendPushNotification($data);
                    $notification = ['title' => $data['title'], 'description' => $requestData['reason'], 'sender_id' => $requestData['user_id'], 'reciver_id' => $user->parent_id, 'status' => '0', 'leave_id' => $addAttendance, 'type' => 'attendance', 'notification_date' => $intime];
                    Notifications::create($notification);
                    $response = ['success' => true, 'message' => 'Attendance update successfully.'];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function updateLeaveSheetStatus(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'leave_id' => 'required',
                'status' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $leave_id = $request->leave_id;
                $status = $request->status;

                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    if ($user->role_type == 1) {
                        $data = ['status' => $status];
                        UserLeaveDetails::where('id', $leave_id)->update($data);
                        $response = ['success' => true, 'message' => 'Leave update successfully.'];
                    } else {
                        $response = ['success' => false, 'message' => 'This access only admin to update.'];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function profileUpdate(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'profile_image' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $user = User::where('id', $id)->where('role_type', $role_type)->first();
                // print_r($user); die;
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    if ($request->file('profile_image')) {
                        // profile image upload
                        // $profileImage = $request->file('profile_image');
                        // $profileName = time() . 'profile.' . $profileImage->getClientOriginalExtension();
                        // Storage::disk('public')->put($profileName,  File::get($profileImage));

                        // if($request->image){
                        $cover = $request->profile_image;
                        $coverPic = 'img_'.time().'.'.$cover->getClientOriginalExtension();
                        $cover = $cover->storeAs('ProImage', $coverPic);
                        // }
                    }
                    $profileName = url('../storage/app/'.$cover);
                    // print_r($profileName);die;
                    // $user->profile_image = Storage::disk('public')->url($profileName);
                    // $userfacrImage = User::where('id',$id)->update(['profile_image'=>$profileName]);
                    $faceData = FaceIdentityController::addImage($profileName);
                    $faceId2 = '';

                    if ($faceData) {
                        $jsonDecode = json_decode($faceData);
                        if ($jsonDecode) {
                            if (!isset($jsonDecode->error)) {
                                $faceId2 = $jsonDecode[0]->faceId;
                                // print_r($faceId2); die;
                            }
                        }
                    }
                    $user->face_id = $faceId2;
                    $user->profile_image = $profileName;
                    $user->save();
                    $officeId = $user->office_id;

                    $officeDe = explode(',', $officeId);
                    $officeDetails = Offices::whereIn('id', $officeDe)->get();
                    $user['officeDetails'] = $officeDetails;
                    $response = ['success' => true, 'message' => 'Profile image update successfully.', 'data' => $user];
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function outTime(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'latitude' => 'required',
                'longitude' => 'required',
                'outtime' => 'required',
                'attendance_id' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $outtime = $request->outtime;
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $circle_radius = 6367;
                    $max_distance = 1;

                    $distance = true;
                    if ($distance) {
                        $offices = explode(',', $user->office_id);

                        $date = date('Y-m-d H:i:s');
                        $data = ['location_status' => 1, 'outtime' => $outtime, 'status' => 1, 'user_id' => $id];
                        $result = UserAttendance::where('id', $request->attendance_id)->update($data);
                        $response = ['success' => true, 'message' => 'Attendance is updated'];
                    } else {
                        $response = ['success' => false, 'message' => 'Your latitude,longitude not match.'];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function notificatioList(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    if ($user->role_type == 2 || $user->role_type == 1) {
                        $notification = Notifications::leftjoin('users', 'users.id', '=', 'notification.sender_id')
                            ->leftjoin('user_leave_details', 'user_leave_details.id', '=', 'notification.leave_id')
                            ->select('notification.id', 'notification.title', 'notification.description', 'notification.created_at as notification_date', 'notification.status', 'notification.type', 'notification.leave_id', 'users.name as username', 'notification.notification_date as apply_date')
                           ->where('notification.status', '0')->where('users.parent_id', $id)->orderBy('notification.id', 'DESC')->get();
                        $response = ['success' => true, 'message' => 'Notification get successfully.', 'notification' => $notification];
                    } else {
                        $notification = Notifications::leftjoin('users', 'users.id', '=', 'notification.reciver_id')
                            ->leftjoin('user_leave_details', 'user_leave_details.id', '=', 'notification.leave_id')
                            ->select('notification.id', 'notification.title', 'notification.description', 'notification.created_at as notification_date', 'notification.status', 'notification.type', 'notification.leave_id', 'users.name as username', 'notification.notification_date as apply_date')
                            ->where('notification.sender_id', $id)->where('notification.status', '0')->orderBy('notification.id', 'DESC')->get();

                        $response = ['success' => true, 'message' => 'Notification get successfully.', 'notification' => $notification];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function notificatioAcceptReject(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'notification_id' => 'required',
                'leave_id' => 'required',
                'status' => 'required',
                'type' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    if ($user->role_type != 2 && $user->role_type != 1) {
                        $response = ['success' => false, 'message' => 'Only admin or Manager have access.'];
                    } else {
                        $notification = Notifications::where('id', $requestData['notification_id'])->first();
                        $notification->status = $requestData['status'];
                        $notification->save();
                        $userid = $notification->sender_id;
                        if ($requestData['type'] == 'leave') {
                            $leaves = UserLeaveDetails::where('id', $requestData['leave_id'])->first();
                            $leaves->status = $requestData['status'];
                            $leaves->save();
                            $attendanceDate = $leaves->from_date;
                        } else {
                            $leaves = UserAttendance::where('id', $requestData['leave_id'])->first();
                            $attendanceDate = $leaves->intime;
                        }
                        $user = User::where('id', $userid)->first();

                        $data = ['title' => 'Leave Request', 'reason' => $requestData['status'] == 1 ? 'Approved' : 'Rejected', 'firebase_token' => @$user->firebase_token, 'username' => $user->name, 'attendance_date' => $attendanceDate];

                        NotificationController::sendPushNotification($data);

                        $response = ['success' => true, 'message' => 'Leave update successfully.'];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function createEmployee(Request $request)
    {
        try {
            $rules = [
                'admin_id' => 'required',
                'name' => 'required',
                'password' => 'required',
                'email' => 'required|unique:users',
                'role_type' => 'required|numeric',
                'office_id' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $name = substr($requestData['name'], 0, 4);
                $digit = rand(1111, 9999);
                $password = $requestData['password'];
                $email = $requestData['email'];
                $requestData['uid'] = $name.$digit;
                $requestData['password'] = bcrypt($requestData['password']);
                $requestData['parent_id'] = $requestData['admin_id'];
                $requestData['status'] = 1;
                $uid = $requestData['uid'];

                $response = ['success' => true, 'message' => 'Employee created', 'employee' => $employee];
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function createOffice(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'location' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'distance' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $office = Offices::create($requestData);

                $response = ['success' => true, 'message' => 'Office created', 'office' => $office];
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'old_password' => 'required',
                'new_password' => 'required',
            ];
            $requestData = $request->all();
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role = $request->role_type;
                $password = $request->old_password;
                $new_password = $request->new_password;
                $user = User::where('id', $id)->where('role_type', $role)->first();
                if (!$user) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'This email address does not exist.'];
                } elseif ($user && !Hash::check($password, $user->password)) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'Old password does not match.'];
                } elseif ($new_password == $password) {
                    $resCode = 400;
                    $response = ['success' => false, 'message' => 'Old password and new password can not be same.'];
                } else {
                    $user->password = bcrypt($new_password);
                    $user->save();
                    $response = ['success' => true, 'message' => 'Your password has been changed successfully.'];
                }
            }

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function logslist()
    {
        $logData = RequestLogs::take(10)->orderBy('id', 'desc')->get();
        $response = ['success' => true, 'message' => 'get list', 'Logs' => $logData];

        return Response::json($response, 200);
    }

    public function userList(Request $request)
    {
        try {
//            $rules = [
//                'user_id' => 'required'
//            ];
//            $requestData = $request->all();
//            $validator = Validator::make($requestData, $rules);
//
//            if ($validator->fails()) {
//
//                $response = ['success' => false, 'message' => $validator->errors()->all()];
//            }
//            else {
//                $id = $request->user_id;
//                $user = User::where('id', $id)->first();
//                if (!$user) {
//                    $resCode = 400;
//                    $response = ['success' => false, 'message' => 'User does not exit'];
//                }
            ////                elseif ($user->role_type != 'admin') {
            ////                    $resCode = 400;
            ////                    $response = ['success' => false, 'message' => 'This user not admin.'];
            ////                }
//                else {
            $users = User::where('role_type', 'employee')->select('id', 'name', 'uid', 'email', 'role_type', 'office_id', 'profile_image', 'parent_id')->orderBy('id', 'desc')->get();
            //print_r( $users); die;
            $response = ['success' => true, 'message' => 'users list', 'users' => $users];
//                }
            return Response::json($response, 200);
//            }
            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function officeList(Request $request)
    {
        try {
//        $rules = [
//            'user_id' => 'required'
//        ];
//        $requestData = $request->all();
//        $validator = Validator::make($requestData, $rules);
//
//        if ($validator->fails()) {
//
//            $response = ['success' => false, 'message' => $validator->errors()->all()];
//        }
//        else {
//            $id = $request->user_id;
//            $user = User::where('id', $id)->first();
//            if (!$user) {
//                $resCode = 400;
//                $response = ['success' => false, 'message' => 'User does not exit'];
//            }
//            elseif ($user->role_type != 'admin') {
//                $resCode = 400;
//                $response = ['success' => false, 'message' => 'This user not admin.'];
//            }
//            else {
            $offices = Offices::all();
            $response = ['success' => true, 'message' => 'office list', 'offices' => $offices];
//            }
            return Response::json($response, 200);
//    }
//return Response::json($response,200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function studentList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perent_id' => 'required|numeric',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }

            $responseObject = [
                'status_code' => 400,
                'message' => $validatorarray,
                'data' => [],
            ];

            return response()->json($responseObject, 400);
        }
        $id = $request->perent_id;
        $data = User::where('role_type', '2')->where('parent_id', $id)->get();
        // print_r($data->toarray()); die;
        if (empty($data)) {
            $data1 = User::where('id', $id)->first();
            print_r($data1);
            die;
            $data = User::where('role_type', '2')->where('parent_id', $data1->parent_id)->get();
        }

        foreach ($data as $new) {
            // print_r($new->id); die;

            $correntDate = date('Y-m-d');

            $user = UserAttendance::where('user_id', $new->id)->whereDate('intime', $correntDate)->orderBy('id', 'DESC')->first();
            // print_r($data); die;
            if ($user) {
                $new->present = '1';
                //print_r($new->present); die;
                if ($user->intime) {
                    $newtimeintime = explode(' ', $user->intime);
                    $new->intime = $newtimeintime[1];
                } else {
                    $new->intime = null;
                }

                if ($user->outtime) {
                    $newtimeouttime = explode(' ', $user->outtime);
                    $new->outtime = $newtimeouttime[1];
                } else {
                    $new->outtime = null;
                }
                // $newtimeintime = explode(' ', $user->intime);
                // $new->intime= $newtimeintime[1];
                // $newtimeouttime = explode(' ', $user->outtime);
                // $new->outtime=$newtimeouttime[1];
            } else {
                $new->present = '0';
                $new->intime = null;
                $new->outtime = null;
            }
        }

        return response()->json(['success' => true, 'student' => $data], 200);
    }

    public function studentList11(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perent_id' => 'required|numeric',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }

            $responseObject = [
                'status_code' => 400,
                'message' => $validatorarray,
                'data' => [],
            ];

            return response()->json($responseObject, 400);
        }
        $id = $request->perent_id;
        $data = User::where('role_type', '2')->where('parent_id', $id)->get();
        // print_r($data); die;
        if (empty($data)) {
            $data1 = User::where('id', $id)->first();
            print_r($data1);
            die;
            $data = User::where('role_type', '2')->where('parent_id', $data1->parent_id)->get();
        }

        // print_r($teacher); die;

        foreach ($data as $new) {
            // print_r($new->id); die;

            $correntDate = date('Y-m-d');

            $user = UserAttendance::where('user_id', $new->id)->whereDate('intime', $correntDate)->orderBy('id', 'DESC')->first();
            // print_r($data); die;
            if ($user) {
                $new->present = '1';
                //print_r($new->present); die;
                if ($user->intime) {
                    $newtimeintime = explode(' ', $user->intime);
                    $new->intime = $newtimeintime[1];
                } else {
                    $new->intime = null;
                }

                if ($user->outtime) {
                    $newtimeouttime = explode(' ', $user->outtime);
                    $new->outtime = $newtimeouttime[1];
                } else {
                    $new->outtime = null;
                }
                // $newtimeintime = explode(' ', $user->intime);
                // $new->intime= $newtimeintime[1];
                // $newtimeouttime = explode(' ', $user->outtime);
                // $new->outtime=$newtimeouttime[1];
            } else {
                $new->present = '0';
                $new->intime = null;
                $new->outtime = null;
            }
        }

        return response()->json(['success' => true, 'student' => $data], 200);
    }

    public function updateprofile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'image' => 'required',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }

            $responseObject = [
                'status_code' => 400,
                'message' => $validatorarray,
                'data' => [],
            ];

            return response()->json($responseObject, 400);
        }

        if ($request->image) {
            $cover = $request->image;
            $coverPic = 'img_'.time().'.'.$cover->getClientOriginalExtension();
            $cover = $cover->storeAs('ProImage', $coverPic);
        }
        User::where('id', $request->user_id)->update(['profile_image' => $cover]);

        return response()->json(['success' => true, 'image' => $cover], 200);
    }

    public function totalTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }

            $responseObject = [
                'status_code' => 400,
                'message' => $validatorarray,
                'data' => [],
            ];

            return response()->json($responseObject, 400);
        }

        $correntDate = date('Y-m-d');
        $user = UserAttendance::where('user_id', $request->user_id)->whereDate('intime', $correntDate)->orderBy('id', 'DESC')->first();
        $officeData = UserAttendance::offieData($request->user_id);
        if ($user) {
            $date1 = strtotime($user->intime);
            // print_r(date("yy-m-d")); die;
            $date22 = date('Y-m-d H:i:s');
            $date2 = strtotime($date22);

            // print_r($date2); die;
            $diff = abs($date2 - $date1);
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

            $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));

            // $data->intime = $user->intime;
            // $data->outtime = $user->outtime;
            // $user->totalTime = $hours.':'.$minutes.':'.$seconds;
            $newintimedate = explode(' ', $user->intime);
            if ($user->outtime) {
                $newouttimedate = explode(' ', $user->outtime);
            }
            $fixTime = '09:00:00';
            $newTimesInmin = explode(':', $newintimedate[1]);
            // $n = (($newintimedate[1]*60)/($fixTime*60))*100;
            $n = $newTimesInmin[0] * 60 + $newTimesInmin[1];
            $nineh = 9 * 60;
            $aa = ($n / $nineh) * 100;
            if ((int) $aa > 100) {
                $user->percent = (string) (int) $aa;
            } else {
                $user->percent = '100';
            }

            $newTimein = explode(':', $newintimedate[1]);
            // print_r($newTimein);
            if ($user->outtime) {
                $newTimeout = explode(':', $newouttimedate[1]);
            } else {
                $newTimeout[0] = '';
                $newTimeout[1] = '';
                $newouttimedate[0] = '';
            }
            // echo "========";   print_r($newTimeout); die;

            $data1 = [
                        'officeName' => $officeData->officeName,
                        'username' => $officeData->username,
                        'profile' => $officeData->profile,
                        'indate' => $newintimedate[0],
                        'intime' => $newTimein[0].':'.$newTimein[1],
                        'outdate' => $newouttimedate[0],
                        'outtime' => $newTimeout[0].':'.$newTimeout[1],
                        'attendance' => '1',
                        'totalTime' => $hours.':'.$minutes,
                        'percent' => (string) (int) $aa,
                        'attendance_id' => $user->id,
            ];

            return response()->json(['success' => true, 'data' => $data1], 200);
        } else {
            // $data1->officename = $officeData->officeName;
            // $data1->username = $officeData->username;
            $data1 = [
                         'officeName' => $officeData->officeName,
                        'username' => $officeData->username,
                        'profile' => $officeData->profile,
                        'indate' => null,
                        'intime' => null,
                        'outdate' => null,
                        'outtime' => null,
                        'attendance' => '0',
                        'totalTime' => null,
                        'percent' => null,
                        'attendance_id' => 0,
            ];

            return response()->json(['success' => true, 'data' => $data1], 200);
        }
    }

     public function totalTime12(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }

            $responseObject = [
                'status_code' => 400,
                'message' => $validatorarray,
                'data' => [],
            ];

            return response()->json($responseObject, 400);
        }

        $correntDate = date('Y-m-d');
        $user = UserAttendance::where('user_id', $request->user_id)->whereDate('intime', $correntDate)->orderBy('id', 'DESC')->first();
        $officeData = UserAttendance::offieData($request->user_id);
        if ($user) {
            $date1 = strtotime($user->intime);
            // print_r(date("yy-m-d")); die;
            $date22 = date('Y-m-d H:i:s');
            $date2 = strtotime($date22);

            // print_r($date2); die;
            $diff = abs($date2 - $date1);
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

            $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));

            // $data->intime = $user->intime;
            // $data->outtime = $user->outtime;
            // $user->totalTime = $hours.':'.$minutes.':'.$seconds;
            $newintimedate = explode(' ', $user->intime);
            if ($user->outtime) {
                $newouttimedate = explode(' ', $user->outtime);
            }
            $fixTime = '09:00:00';
            $newTimesInmin = explode(':', $newintimedate[1]);
            // $n = (($newintimedate[1]*60)/($fixTime*60))*100;
            $n = $newTimesInmin[0] * 60 + $newTimesInmin[1];
            $nineh = 9 * 60;
            $aa = ($n / $nineh) * 100;
            if ((int) $aa > 100) {
                $user->percent = (string) (int) $aa;
            } else {
                $user->percent = '100';
            }

            $newTimein = explode(':', $newintimedate[1]);
            // print_r($newTimein);
            if ($user->outtime) {
                $newTimeout = explode(':', $newouttimedate[1]);
            } else {
                $newTimeout[0] = '';
                $newTimeout[1] = '';
                $newouttimedate[0] = '';
            }
            // echo "========";   print_r($newTimeout); die;

            $data1 = [
                        'officeName' => $officeData->officeName,
                        'username' => $officeData->username,
                        'profile' => $officeData->profile,
                        'indate' => $newintimedate[0],
                        'intime' => $newTimein[0].':'.$newTimein[1],
                        'outdate' => $newouttimedate[0],
                        'outtime' => $newTimeout[0].':'.$newTimeout[1],
                        'attendance' => '1',
                        'totalTime' => $hours.':'.$minutes,
                        'percent' => (string) (int) $aa,
                        'attendance_id' => $user->id,
            ];

            return response()->json(['success' => true, 'data' => $data1], 200);
        } else {
            // $data1->officename = $officeData->officeName;
            // $data1->username = $officeData->username;

             $checklastintime = UserAttendance::where('user_id', $request->user_id)->whereDate('intime','<',$correntDate)->orderBy('id', 'DESC')->first();
                
            $checkuserbatch = DB::table('settings')->join('batch','settings.value','=','batch.scheme_id')->where('batch.id', $checklastintime->batch_id)->first();       

            if(!empty($checkuserbatch)){
                            $newintimedate = explode(' ', $checklastintime->intime);
                            $newTimein = explode(':', $newintimedate[1]);
                            $newTimesInmin = explode(':', $newintimedate[1]);

                            $n = $newTimesInmin[0] * 60 + $newTimesInmin[1];
                            $nineh = 9 * 60;
                            $aa = ($n / $nineh) * 100;
                    $date1 = strtotime($checklastintime->intime);
            
                    $date22 = date('Y-m-d H:i:s');
                    $date2 = strtotime($date22);

                    // print_r($date2); die;
                    $diff = abs($date2 - $date1);
                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                    $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

                    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));

                    // print_r($days); die;$
                    $hours =  ($days*24) + $hours;

                     $data1 = [
                        'officeName' => $officeData->officeName,
                        'username' => $officeData->username,
                        'profile' => $officeData->profile,
                        'indate' => $newintimedate[0],
                        'intime' => $newTimein[0].':'.$newTimein[1],
                        'outdate' => null,
                        'outtime' =>null,
                        'attendance' => '1',
                        'totalTime' => $hours.':'.$minutes,
                        'percent' => (string) (int) $aa,
                        'attendance_id' => $checkuserbatch->id,
            ];

            }else{
                    $data1 = [
                            'officeName' => $officeData->officeName,
                            'username' => $officeData->username,
                            'profile' => $officeData->profile,
                            'indate' => null,
                            'intime' => null,
                            'outdate' => null,
                            'outtime' => null,
                            'attendance' => '0',
                            'totalTime' => null,
                            'percent' => null,
                            'attendance_id' => 0,
                    ];
            }

              // print_r( $checkuserbatch); die;
               

            return response()->json(['success' => true, 'data' => $data1], 200);
        }
    }

    //Code written by purpledesign.in Jan 2014
    public function dateDiff($date)
    {
        $mydate = date('Y-m-d H:i:s');
        $theDiff = '';
        //echo $mydate;//2014-06-06 21:35:55
        $datetime1 = date_create($date);
        $datetime2 = date_create($mydate);
        $interval = date_diff($datetime1, $datetime2);
        //echo $interval->format('%s Seconds %i Minutes %h Hours %d days %m Months %y Year    Ago')."<br>";
        $min = $interval->format('%i');
        $sec = $interval->format('%s');
        $hour = $interval->format('%h');
        $mon = $interval->format('%m');
        $day = $interval->format('%d');
        $year = $interval->format('%y');
        if ($interval->format('%i%h%d%m%y') == '00000') {
            //echo $interval->format('%i%h%d%m%y')."<br>";
            return $sec.' Seconds';
        } elseif ($interval->format('%h%d%m%y') == '0000') {
            return $min.' Minutes';
        } elseif ($interval->format('%d%m%y') == '000') {
            return $hour.' Hours';
        } elseif ($interval->format('%m%y') == '00') {
            return $day.' Days';
        } elseif ($interval->format('%y') == '0') {
            return $mon.' Months';
        } else {
            return $year.' Years';
        }
    }

    public function userBatch(Request $request)
    {
        $userid = $request->user_id;

        if (empty($userid)) {
            return Response::json(['success' => false, 'message' => 'User id required.'], 400);
        }

        $getuser = User::where('id', $userid)->first();
        if (empty($getuser)) {
            return Response::json(['success' => false, 'message' => 'User not exist.'], 400);
        }

        // print_r($getuser); die;
        if ($getuser->role_type == 3) {
            $data[] = (object) ['batchId' => 0, 'batch_name' => 'All', 'is_online' => 1];
        } else {
            $data = DB::table('user_batch')
               ->join('batch', 'user_batch.batch_id', '=', 'batch.id')
               ->where('user_batch.user_id', $userid)
               ->select('batch.id as batchId', 'batch.batch_name', 'user_batch.is_online')
               ->get();
        }

        return Response::json(['success' => true, 'data' => $data], 200);
    }

    // attendance copy

    public function attendance1(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required|numeric',
                'face_status' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'profile_image' => 'required',
                'type' => 'required',
                'batch_id' => 'required|numeric',
            ];
            if ($request->type == 'out') {
                $rules['attendance_id'] = 'required';
            }
            $requestData = $request->all();

            // print_r( json_encode($request->all()));
            // DB::table('temp')->insert(['log'=>json_encode($request->all())]);
            // die;
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                if ($request->type == 'in') {
                    $datee = date('Y-m-d');
                    $checkdata = UserAttendance::where('user_id', $request->user_id)->whereDate('intime', $datee)->first();
                    if ($checkdata) {
                        return Response::json(['success' => false, 'message' => 'You have already marked attendance'], 400);
                    }
                }

                $id = $request->user_id;
                $role_type = $request->role_type;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $face_status = $request->face_status;
                $intime = date('Y-m-d H:i:s');
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();

                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $circle_radius = 6367;
                    $max_distance = 1;

                    $distance = true;
                    if ($request->file('profile_image')) {
                        $cover = $request->profile_image;
                        $coverPic = 'img_'.time().'.'.$cover->getClientOriginalExtension();
                        $cover = $cover->storeAs('TempImage', $coverPic);
                    }
                    $profileName = url('../storage/app/'.$cover);
                    $requestData['profile_image'] = $profileName;
                    $faceData = FaceIdentityController::addImage($profileName);
                    // print_r(  $faceData); die;
                    $faceId2 = '';
                    if ($faceData || empty($faceData)) {
                        $jsonDecode = json_decode($faceData);
                        // print_r($jsonDecode); die;
                        if (!empty($jsonDecode)) {
                            if (!isset($jsonDecode->error)) {
                                $faceId2 = $jsonDecode[0]->faceId;
                                $updateTempfaceid = User::where('id', $id)->update(['face_id' => $faceId2, 'temp_image' => $profileName]);
                            }
                        } else {
                            // return Response::json(['success' => false, 'message' => 'Please Change Profile Image.'], 400);
                        }

                        $face1Data = FaceIdentityController::addImage($user->profile_image);

                        // print_r($face1Data); die;

                        if ($face1Data || empty($face1Data)) { 	
                            $jsonDecode1 = json_decode($face1Data);

                            if (empty($jsonDecode1)) {
                                // return Response::json(['success' => false, 'message' => 'Please Change Profile Image.'], 400);
                            }
                            // if (!isset($jsonDecode1->error)) {
                            //     $faceId1 = $jsonDecode1[0]->faceId;
                            //     $updateTempfaceid = User::where('id', $id)->update(['temp_face_id' => $faceId1]);
                            // }
                        }

                        $checkoldupdatefaceid = explode(' ', $user->updated_at);
                        $currentDate = date('Y-m-d');
$abcd=123;
                        $userfaceid = User::where('id', $id)->select('face_id', 'temp_face_id')->first();
                        $faceVerify = FaceIdentityController::checkFace($userfaceid->temp_face_id, $userfaceid->face_id);
                        $requestData['faceverify'] = $faceVerify;
                        if ($faceVerify || empty($faceVerify)) {
                            $faceIdentity = json_decode($faceVerify);
                           // print_r($faceIdentity); die;
                            if ($abcd!=123) {
                               // $response = ['success' => false, 'message' => 'face not found.'];
                            } else {
                            	// print_r($faceIdentity); die;
                                if ($distance || $faceIdentity->isIdentical || $faceIdentity->confidence > 0.05 || empty($faceIdentity) || $faceIdentity) {
                                    $offices = explode(',', $user->office_id);
                                    // if (in_array($distance[0]->id, $offices)) {
                                    $date = date('Y-m-d H:i:s');
                                    if ($request->type == 'in') {
                                        $attendanceCheack = UserAttendance::where('user_id', $id)->whereDate('intime', $intime)->first();
                                        if (!$attendanceCheack) {
                                          //  print_r($request->batch_id); die;
                                            $data = [
                                                          'location_status' => 1,
                                                          'face_status' => 1,
                                                          'intime' => $intime,
                                                          'status' => 1,
                                                          'user_id' => $id,
                                                          'batch_id' => $request->batch_id,
                                                      ];
                                            // print_r($data); die;
                                            $result = UserAttendance::insertGetId($data);
                                            $data['id'] = $result;
                                            $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $data];
                                        } else {
                                            $response = ['success' => false, 'message' => 'You already marked attendance for this day.'];
                                        }
                                    }
                                    if ($request->type == 'out') {

                                        $oldatt = DB::table('user_attendance')->where('id',$request->attendance_id)->first();

                                        $oldatt = DB::table('user_attendance_history')->where('intime',$oldatt->intime)->first();

                                        $batch = DB::table('batch')->where('id',$request->batch_id)->first();
                                        // print_r( $batch); die;
                                        if(!empty($batch) && $batch->hours ){
                                            $hour = $batch->hours.':00';
                                        }else{
                                             $hour = '6:00';
                                        }
                                        // print_r($hour); die;
                                       ////////////////////////////////////////
                                        $att = DB::table('user_attendance')->where('id',$request->attendance_id)->first();
                                        $date1 = strtotime($att->intime);
            
                                        $date22 = date('Y-m-d H:i:s');
                                        $date2 = strtotime($date22);

                                        // print_r($date2); die;
                                        $diff = abs($date2 - $date1);
                                        $years = floor($diff / (365 * 60 * 60 * 24));
                                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                                        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

                                        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                                        $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));


                                        $time = $hours.':'.$minutes;
                                          $time  = strtotime($time);
                                        $time = date('h:i', $time);
                                        $selectedTime = $hour;
                                        $endTime = strtotime("-30 minutes", strtotime($selectedTime));
                                       $endTime = date('h:i', $endTime);


                                      
                                        if($endTime < $time){
                                            $isvarify = 0;
                                        }else{
                                            $isvarify = 1;

                                        }

                                        $out_time = UserAttendance::where('id', $request->attendance_id)->first();
                                        if (empty($out_time)) {
                                            return Response::json(['status' => false, 'message' => "There is no in time recorder for today's date"], 400);
                                        }

                                        $data = ['location_status' => 1, 'face_status' => 1, 'outtime' => $intime, 'status' => 1, 'user_id' => $id, 'batch_id' => $request->batch_id,'is_valid'=>$isvarify];
                                        UserAttendance::where('id', $request->attendance_id)->update($data);
                                        $result = UserAttendance::where('id', $request->attendance_id)->first();
                                        //   DB::table('user_attendance_history')->where('id',$oldatt->id)->update($data);
                                        $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $result];
                                    }
                                } else {
                                    $response = ['success' => false, 'message' => 'Your face not match.'];
                                }
                            }
                        } else {
                            $response = ['success' => false, 'message' => 'Something wrong.'];
                        }
                    } else {
                        $response = ['success' => false, 'message' => 'FaceId not returned.'];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
           // print_r($response);die;
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

    public function attendanceMarkDay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_id' => 'required',
            'date' => 'required',
            ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $validator = $errors->all();
            for ($i = 0; $i < count($validator); ++$i) {
                $validatorarray = $validator[$i];
            }

            $responseObject = [
                'status_code' => 400,
                'message' => $validatorarray,
                'data' => [],
            ];

            return response()->json($responseObject, 400);
        }

        $uid = $request->u_id;
        $date = $request->date;

        $userid = User::where('uid', $uid)->first();
        if (empty($userid)) {
            return Response::json(['success' => false, 'message' => 'user not exist'], 404);
        }
        // print_r( $userid); die;

        $getbatchid = User_batch::where('user_id', $userid->id)->first();
        $getbatchData = Batch::where('id', $getbatchid->batch_id)->first();
        $intimes = 10;
        $outtimes = 10 + $getbatchData->hours;
        // print_r(  $outtime); die;

        $allusers = User::where('parent_id', $userid->id)->get();

        foreach ($allusers as $alluser) {
            $inmin = rand(0, 9);
            $ze = 0;
            $ninmin = (int) $ze.(int) $inmin;
            $insec = rand(10, 59);
            $intimenew = $date.' '.$intimes.':'.$ninmin.':'.$insec;

            $outmin = rand(0, 9);
            $outnewmin = (int) $ze.(int) $outmin;
            $outsec = rand(10, 59);
            $outtimenew = $date.' '.$outtimes.':'.$outnewmin.':'.$outsec;

            $abc = UserAttendance::where('user_id', $alluser->id)->whereDate('intime', $date)->first();

            if ($abc) {
                $obj = [
                        'intime' => $intimenew,
                        'outtime' => $outtimenew,
                        'created_at' => $intimenew,
                        'updated_at' => $outtimenew,
                       ];

                $sss = UserAttendance::where('user_id', $alluser->id)->whereDate('intime', $date)->update($obj);
            // print_r($sss); die;
            } else {
                $obj = [
                        'user_id' => $alluser->id,
                        'batch_id' => $getbatchid->batch_id,
                        'intime' => $intimenew,
                        'outtime' => $outtimenew,
                        'face_status' => 1,
                        'location_status' => 1,
                        'status' => 1,
                        'created_at' => $intimenew,
                        'updated_at' => $outtimenew,
                       ];

                UserAttendance::insert($obj);
            }
        }

        return Response::json(['success' => true, 'message' => 'successfully'], 200);
    }

    public function attendance11(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required',
                'face_status' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'profile_image' => 'required',
                'type' => 'required',
                'batch_id' => 'required',
            ];
            if ($request->type == 'out') {
                $rules['attendance_id'] = 'required';
            }
            $requestData = $request->all();
            // print_r( json_encode($request->all()));
            // DB::table('temp')->insert(['log'=>json_encode($request->all())]);
            // die;
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                $id = $request->user_id;
                $role_type = $request->role_type;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $face_status = $request->face_status;
                $intime = date('Y-m-d H:i:s');
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();
                if (empty($user->approved)) {
                    return Response::json(['success' => false, 'message' => 'Your Profile are not approved. please contacte admin.'], 400);
                }
                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $circle_radius = 6367;
                    $max_distance = 1;

                    $distance = true;
                    if ($request->file('profile_image')) {
                        $cover = $request->profile_image;
                        $coverPic = 'img_'.time().'.'.$cover->getClientOriginalExtension();
                        $cover = $cover->storeAs('TempImage', $coverPic);
                        // print_r($cover); die;
                    }
                    $profileName = url('../storage/app/'.$cover);
                    $requestData['profile_image'] = $profileName;
                    $faceData = FaceIdentityController::addImage($profileName);
                    //print_r($faceData); die;
                    $faceId2 = '';
                    if ($faceData) {
                        $jsonDecode = json_decode($faceData);
                        // print_r($jsonDecode); die;
                        if (!empty($jsonDecode)) {
                            if (!isset($jsonDecode->error)) {
                                $faceId2 = $jsonDecode[0]->faceId;
                                $updateTempfaceid = User::where('id', $id)->update(['face_id' => $faceId2, 'temp_image' => $profileName]);
                            }
                        } else {
                            return Response::json(['success' => false, 'message' => 'Please Change Profile Image.'], 400);
                        }

                        $face1Data = FaceIdentityController::addImage($user->profile_image);
                        //print_r($face1Data); die;
                        if ($face1Data) {
                            $jsonDecode1 = json_decode($face1Data);

                            if (empty($jsonDecode1)) {
                                return Response::json(['success' => false, 'message' => 'Please Change Profile Image.'], 400);
                            }
                            if (!isset($jsonDecode1->error)) {
                                $faceId1 = $jsonDecode1[0]->faceId;
                                $updateTempfaceid = User::where('id', $id)->update(['temp_face_id' => $faceId1]);
                            }
                        }

                        $checkoldupdatefaceid = explode(' ', $user->updated_at);
                        $currentDate = date('Y-m-d');
                        // if($checkoldupdatefaceid[0] !=  $currentDate){
                        //         $face2Data = FaceIdentityController::addImage($user->profile_image);
                        // if($face2Data)
                        // {
                        //     $jsonDecode2 = json_decode($face2Data);
                        //     if(!isset($jsonDecode2->error)) {
                        //         $faceId21 = $jsonDecode1[0]->faceId;
                        //         // print_r($faceId21); die;
                        //         $updateTempfaceid = User::where('id',$id)->update(['face_id'=>$faceId21]);
                        //     }
                        // }

                        // }

                        $userfaceid = User::where('id', $id)->select('face_id', 'temp_face_id')->first();
                        $faceVerify = FaceIdentityController::checkFace($userfaceid->temp_face_id, $userfaceid->face_id);
                        print_r($faceVerify);
                        die;
                        $requestData['faceverify'] = $faceVerify;
                        if ($faceVerify) {
                            $faceIdentity = json_decode($faceVerify);
                            //print_r($faceIdentity); die;
                            if (isset($faceIdentity->error)) {
                                $response = ['success' => false, 'message' => 'face not found.'];
                            } else {
                                // echo $faceIdentity->confidence; die;
                                if ($distance || $faceIdentity->isIdentical || $faceIdentity->confidence > 0.30) {
                                    //echo "dsds"; die;
                                    $offices = explode(',', $user->office_id);
                                    // if (in_array($distance[0]->id, $offices)) {
                                    $date = date('Y-m-d H:i:s');
                                    if ($request->type == 'in') {
                                        $attendanceCheack = UserAttendance::where('user_id', $id)->whereDate('intime', $intime)->first();
                                        if (!$attendanceCheack) {
                                            // print_r($request->batch_id); die;
                                            $data = [
                                                          'location_status' => 1,
                                                          'face_status' => 1,
                                                          'intime' => $intime,
                                                          'status' => 1,
                                                          'user_id' => $id,
                                                          'batch_id' => $request->batch_id,
                                                      ];
                                            // print_r($data); die;
                                            $result = UserAttendance::insertGetId($data);
                                            $data['id'] = $result;
                                            $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $data];
                                        } else {
                                            $response = ['success' => false, 'message' => 'You already marked attendance for this day.'];
                                        }
                                    }
                                    if ($request->type == 'out') {
                                        $out_time = UserAttendance::where('id', $request->attendance_id)->first();
                                        if (empty($out_time)) {
                                            return Response::json(['status' => false, 'message' => "There is no in time recorder for today's date"], 400);
                                        }

                                        $data = ['location_status' => 1, 'face_status' => 1, 'outtime' => $intime, 'status' => 1, 'user_id' => $id, 'batch_id' => $request->batch_id];
                                        UserAttendance::where('id', $request->attendance_id)->update($data);
                                        $result = UserAttendance::where('id', $request->attendance_id)->first();
                                        $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $result];
                                    }
                                } else {
                                    $response = ['success' => false, 'message' => 'Your face not match.'];
                                }
                            }
                        } else {
                            $response = ['success' => false, 'message' => 'Something wrong.'];
                        }
                    } else {
                        $response = ['success' => false, 'message' => 'FaceId not returned.'];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }


    public function moveattendance(){

        $getallattendances = DB::table('user_attendance')->get();
        $allids = array();
        $countinset = 0;
        foreach($getallattendances as $getallattendance){

            if( $getallattendance->outtime){
                $obj = [
                    'user_id'=>$getallattendance->user_id,
                    'face_status'=>$getallattendance->face_status,
                    'location_status'=>$getallattendance->location_status,
                    'batch_id'=>$getallattendance->batch_id,
                    'intime'=>$getallattendance->intime,
                    'outtime'=>$getallattendance->outtime,
                    'category'=>$getallattendance->category,
                    'reason'=>$getallattendance->reason,
                    'status'=>$getallattendance->status,
                    // 'is_approve'=>$getallattendance->is_approve,
                    // 'deviation'=>$getallattendance->deviation,
                    // 'correction_intime'=>$getallattendance->correction_intime,
                    // 'correction_outtime'=>$getallattendance->correction_outtime
            ];
            $data =  DB::table('user_attendance_history_old')->insert($obj);
            array_push( $allids,$getallattendance->id);
              $countinset =   $countinset + 1;
            }

           
       


        }
        // DB::table('user_attendance')->whereIn('id',$allids)->delete();
        print_r($countinset); echo "======";  print_r($allids);
        die;

    }


     public function attendancepic(Request $request)
    {
        try {
            $rules = [
                'user_id' => 'required',
                'role_type' => 'required',
                'face_status' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'profile_image' => 'required',
                'type' => 'required',
                'batch_id' => 'required',
            ];
            if ($request->type == 'out') {
                $rules['attendance_id'] = 'required';
            }
            $requestData = $request->all();

            // print_r( json_encode($request->all()));
            // DB::table('temp')->insert(['log'=>json_encode($request->all())]);
            // die;
            $validator = Validator::make($requestData, $rules);

            if ($validator->fails()) {
                $response = ['success' => false, 'message' => $validator->errors()->all()];
            } else {
                if ($request->type == 'in') {
                    $datee = date('Y-m-d');
                    $checkdata = UserAttendance::where('user_id', $request->user_id)->whereDate('intime', $datee)->first();
                    if ($checkdata) {
                        return Response::json(['success' => false, 'message' => 'You have already marked attendance'], 400);
                    }
                }

                $id = $request->user_id;
                $role_type = $request->role_type;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $face_status = $request->face_status;
                $intime = date('Y-m-d H:i:s');
                $user = User::where('id', $id)->where('role_type', $role_type)->with('office')->first();

                if (!$user) {
                    $response = ['success' => false, 'message' => 'User does not exist with this role.'];
                } else {
                    $circle_radius = 6367;
                    $max_distance = 1;

                    $distance = true;
                    if ($request->file('profile_image')) {
                        $cover = $request->profile_image;
                        $coverPic = 'img_'.time().'.'.$cover->getClientOriginalExtension();
                        $cover = $cover->storeAs('TempImage', $coverPic);
                    }
                    $profileName = url('../storage/app/'.$cover);
                    $requestData['profile_image'] = $profileName;
                    $faceData = FaceIdentityController::addImage($profileName);
                    print_r(  $faceData); die;
                    $faceId2 = '';
                    if ($faceData || empty($faceData)) {
                        $jsonDecode = json_decode($faceData);
                        // print_r($jsonDecode); die;
                        if (!empty($jsonDecode)) {
                            if (!isset($jsonDecode->error)) {
                                $faceId2 = $jsonDecode[0]->faceId;
                                $updateTempfaceid = User::where('id', $id)->update(['face_id' => $faceId2, 'temp_image' => $profileName]);
                            }
                        } else {
                            // return Response::json(['success' => false, 'message' => 'Please Change Profile Image.'], 400);
                        }

                        $face1Data = FaceIdentityController::addImage($user->profile_image);

                        // print_r($face1Data); die;

                        if ($face1Data || empty($face1Data)) { 	
                            $jsonDecode1 = json_decode($face1Data);

                            if (empty($jsonDecode1)) {
                                // return Response::json(['success' => false, 'message' => 'Please Change Profile Image.'], 400);
                            }
                            // if (!isset($jsonDecode1->error)) {
                            //     $faceId1 = $jsonDecode1[0]->faceId;
                            //     $updateTempfaceid = User::where('id', $id)->update(['temp_face_id' => $faceId1]);
                            // }
                        }

                        $checkoldupdatefaceid = explode(' ', $user->updated_at);
                        $currentDate = date('Y-m-d');

                        $userfaceid = User::where('id', $id)->select('face_id', 'temp_face_id')->first();
                        $faceVerify = FaceIdentityController::checkFace($userfaceid->temp_face_id, $userfaceid->face_id);
                        $requestData['faceverify'] = $faceVerify;
                        if ($faceVerify || empty($faceVerify)) {
                            $faceIdentity = json_decode($faceVerify);
                            // print_r($faceIdentity); die;
                            if (isset($faceIdentity->error)) {
                                $response = ['success' => false, 'message' => 'face not found.'];
                            } else {
                            	// print_r($faceIdentity); die;
                                if ($distance || $faceIdentity->isIdentical || $faceIdentity->confidence > 0.05 || empty($faceIdentity)) {
                                    $offices = explode(',', $user->office_id);
                                    // if (in_array($distance[0]->id, $offices)) {
                                    $date = date('Y-m-d H:i:s');
                                    if ($request->type == 'in') {
                                        $attendanceCheack = UserAttendance::where('user_id', $id)->whereDate('intime', $intime)->first();
                                        if (!$attendanceCheack) {
                                            // print_r($request->batch_id); die;
                                            $data = [
                                                          'location_status' => 1,
                                                          'face_status' => 1,
                                                          'intime' => $intime,
                                                          'status' => 1,
                                                          'user_id' => $id,
                                                          'batch_id' => $request->batch_id,
                                                      ];
                                            // print_r($data); die;
                                            $result = UserAttendance::insertGetId($data);
                                            $data['id'] = $result;
                                            $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $data];
                                        } else {
                                            $response = ['success' => false, 'message' => 'You already marked attendance for this day.'];
                                        }
                                    }
                                    if ($request->type == 'out') {
                                        $out_time = UserAttendance::where('id', $request->attendance_id)->first();
                                        if (empty($out_time)) {
                                            return Response::json(['status' => false, 'message' => "There is no in time recorder for today's date"], 400);
                                        }

                                        $data = ['location_status' => 1, 'face_status' => 1, 'outtime' => $intime, 'status' => 1, 'user_id' => $id, 'batch_id' => $request->batch_id];
                                        UserAttendance::where('id', $request->attendance_id)->update($data);
                                        $result = UserAttendance::where('id', $request->attendance_id)->first();
                                        $response = ['success' => true, 'message' => 'Your attendance for today is marked successfully.', 'attendance' => $result];
                                    }
                                } else {
                                    $response = ['success' => false, 'message' => 'Your face not match.'];
                                }
                            }
                        } else {
                            $response = ['success' => false, 'message' => 'Something wrong.'];
                        }
                    } else {
                        $response = ['success' => false, 'message' => 'FaceId not returned.'];
                    }
                }
            }
            $date = Carbon::now()->subDays(10);
            // RequestLogs::whereDate('created_at', '<', $date)->delete();
            $logData = ['request' => json_encode($requestData, true), 'response' => json_encode($response, true), 'url' => $request->fullUrl(), 'ip' => $request->ip()];
            // RequestLogs::create($logData);

            return Response::json($response, 200);
        } catch (Exception $e) {
            return Response::json(['success' => false, 'message' => $e->getMessage()], 404);
        }
    }

}
