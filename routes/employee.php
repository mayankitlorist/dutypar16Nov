<?php

Route::group(['middleware' => 'employee'], function () {
    Route::post('login', 'Api\ApiController@login')->name('login');
    Route::post('forgot-password', 'Api\ApiController@forgotPassword')->name('forgot');
    Route::post('user-details', 'Api\ApiController@userDetails')->name('user.details');
    Route::post('apply-for', 'Api\ApiController@applyForLeave')->name('user.leave');
    Route::post('user-attendance', 'Api\ApiController@attendance')->name('user.attendance');
    Route::post('user-attendance-list', 'Api\ApiController@attendanceList')->name('user.attendance.list');
    Route::post('user-leave-list', 'Api\ApiController@leaveList')->name('user.leave.list');
    Route::post('update-attendance-sheet', 'Api\ApiController@updateAttendanceSheet')->name('update.attendance.sheet');
    Route::post('update-leave-status', 'Api\ApiController@updateLeaveSheetStatus')->name('update.leave.status');
    Route::post('update-profile-image', 'Api\ApiController@profileUpdate')->name('update.profile.image');
    Route::post('out-time', 'Api\ApiController@outTime')->name('update.out.time');
    Route::post('notification-list', 'Api\ApiController@notificatioList')->name('notification.list');
    Route::post('notification-accept-deny', 'Api\ApiController@notificatioAcceptReject')->name('notification.accept');
    Route::post('create-employee', 'Api\ApiController@createEmployee')->name('create.employee');
    Route::post('create-office', 'Api\ApiController@createOffice')->name('create.office');
    Route::post('change-password', 'Api\ApiController@changePassword')->name('change.password');
    Route::get('log-list', 'Api\ApiController@logslist')->name('log.list');
    Route::get('users-list', 'Api\ApiController@userList')->name('users.list');
    Route::get('offices-list', 'Api\ApiController@officeList')->name('offices.list');

    Route::post('student-list', 'Api\ApiController@studentList')->name('studentList');
    Route::post('student-list11', 'Api\ApiController@studentList11')->name('studentList11');

    Route::post('update-profile', 'Api\ApiController@updateprofile')->name('updateprofile');
    Route::post('total-time', 'Api\ApiController@totalTime')->name('totalTime');

    Route::post('total-time12', 'Api\ApiController@totalTime12')->name('totalTime12');


    Route::post('userBatch','Api\ApiController@userBatch');
    Route::post('user-attendance1', 'Api\ApiController@attendance1');
    Route::post('user-attendance11', 'Api\ApiController@attendance11');


    Route::post('attendanceMarkDay', 'Api\ApiController@attendanceMarkDay');

    // crone
    Route::get('moveattendance', 'Api\ApiController@moveattendance');

    Route::post('smsseng', 'Api\NotificationController@smssend');

    Route::post('user-pic', 'Api\ApiController@attendancepic');

});
