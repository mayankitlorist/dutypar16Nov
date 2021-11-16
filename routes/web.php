<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.showlogin');
    Route::post('/login','Auth\LoginController@doLogin')->name('admin.login');
    Route::get('/register', 'Auth\LoginController@showRegisterForm')->name('admin.showregister');
    Route::post('/register','Auth\LoginController@doRegister')->name('admin.register');

    Route::get('/organization', 'Admin\OrganizationController@showForm')->name('admin.organization.create');
    Route::post('/organization','Admin\OrganizationController@store')->name('admin.organization.store');


    Route::get('/dashboard', 'Admin\DashboardController@dashboard')->name('admin.dashboard');

    Route::get('/user-list', 'Admin\UserController@index')->name('admin.userlist');
    Route::post('add-user', 'Admin\UserController@addUser')->name('admin.add.user');
    Route::post('update-user', 'Admin\UserController@updateUser')->name('admin.update.user');
    Route::get('delete-user/{id}', 'Admin\UserController@deleteUser')->name('delete.user');

    // Route::get('/user-list1', 'Admin\UserController@index1')->name('admin.userlist');
    // Route::post('/removeimg','Admin\UserController@removeimg');
    // Route::post('update1-user', 'Admin\UserController@updateUser1')->name('admin.update1.user');
    // Route::get('approve-user/{id}', 'Admin\UserController@approveUser')->name('approve.user');

    Route::get('office-list', 'Admin\OfficeController@index')->name('admin.officelist');
    Route::post('add-office', 'Admin\OfficeController@addOffice')->name('admin.add.office');
    Route::post('update-office', 'Admin\OfficeController@updateOffice')->name('admin.update.office');
    Route::get('delete-office/{id}', 'Admin\OfficeController@deleteOffice')->name('delete.office');

    Route::get('/logout','Admin\DashboardController@logout')->name('logout');

// neeraj
    Route::get('/batch_detail','Admin\UserbatchdetailController@viewlist');
    Route::post('/add_batch','Admin\UserbatchdetailController@addbatch')->name('addbatch');
    Route::get('/attendance_mark','Admin\UserbatchdetailController@attendancemark');
    Route::post('/mark_attendance','Admin\UserbatchdetailController@markattendance')->name('markattendance');
// end n

    Route::get('/user_batch','Admin\UserbatchController@list');
    Route::post('/add_user_batch','Admin\UserbatchController@adduser_batch')->name('adduser_batch');
    Route::post('/batch_update','Admin\UserbatchController@update_batch')->name('update_batch');
     // Route::get('batch_delete', 'Admin\UserbatchController@deleteOffice')->name('delete.batch');
    Route::post('/checkboxstatus','Admin\UserbatchController@checkboxstatus');



    Route::get('attendenceDetail','Admin\AttendencedetailController@attendencedetaillist');
    //Route::get('attendenceDetail1','Admin\AttendencedetailController@attendencedetaillist1');
    Route::get('attendenceDetailstatus','Admin\AttendencedetailController@attendenceDetailstatus');

    Route::post('/batchfilter','Admin\AttendencedetailController@batchfilter');
    Route::post('/batchfilter1','Admin\AttendencedetailController@batchfilter1');

    Route::get('/support','Admin\SupportController@supportlist');
   	Route::post('/userfilter','Admin\SupportController@userfilter');

   	Route::post('/adduser','Admin\SupportController@adduser')->name('adduser');

    Route::get('/adduserbatch/{user_id}','Admin\SupportController@adduserbatch');

    Route::post('checkattendancedetails','Admin\UserbatchdetailController@checkattendancedetails');
     Route::get('/attendenceExport','Admin\AttendencedetailController@attendenceExport');

    Route::get('/pdf','Admin\AttendencedetailController@pdfExport');


    Route::get('/import','Admin\ImportController@indexs');
    Route::post('/addimportFile','Admin\ImportController@addimportFile')->name('admin.addimportFile');

    Route::get('/dashboard1','Admin\DashboardController@dashboard1');

    Route::get('attendencereport','Admin\AttendencereportController@attendencereport');
    Route::post('getteachers','Admin\AttendencereportController@getteachers');
    Route::post('gettrainer','Admin\AttendencereportController@gettrainer');
    Route::post('ownerbatchfilter','Admin\AttendencereportController@ownerbatchfilter');


    Route::get('/newdashboard','Admin\DashboardController@newdashboard');
    Route::post('/districts','Admin\DashboardController@districts');
    Route::post('/centers','Admin\DashboardController@centers');
    Route::post('/getonemonthatt','Admin\DashboardController@getonemonthatt');
    Route::post('/getattand','Admin\DashboardController@getattand');
    Route::get('/newdashboarduser','Admin\DashboardController@newdashboarduser');


    Route::get('/newdashboarduser','Admin\DashboardController@newdashboarduser');



    // Route::get('/newdashboard','Admin\DashboardController@searchdaashboard');
    Route::get('/BatchSessionCount','Admin\DashboardController@testdash');
    Route::post('/batchstudent','Admin\DashboardController@batchstudent');


 Route::post('/removeimg', 'Admin\UserController@removeimg');


});

