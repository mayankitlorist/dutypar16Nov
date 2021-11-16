<?php

namespace App\Imports;

use App\Models\FileImports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        // print_r($row); die;
        $user = Auth::user();
        $ldate = date('H:i');
        $usertime = $user->id.'_'.$ldate;

        session(['id'=>$usertime]);
        // print_r($row['']); die;
        // // if(isset($row['vtp_name']) && isset($usertime) && isset($row['vtp_no']) && isset($row['vtp_address']) &&  isset($row['longitude']) && isset($row['latitude']) && isset($row['spoc_name']) && isset($row['spoc_mobile_no'])  && isset($row['spoc_phone']) && isset($row['spoc_email_id']) && isset($row['scheme_name']) && isset($row['tbn_number']) && isset($row['perday_time']) && isset($row['id']) && isset($row['first_name']) && isset($row['last_name']) && isset($row['mobile']) && isset($row['trainer_name']) && isset($row['trainer_id']) && isset($row['trainer_qualification']) && isset($row['trainers_experience'])){

        if(isset($row[''])){
            
             session(['isdata'=>0]);
         
           
        }else{
           
            $obj = [

                'vtp_name' => $row['vtp_name'],
                'user_id' => $usertime,
                'vtp_no' => $row['vtp_no'],
                'vtp_address' => $row['vtp_address'],
                'longitude' => $row['longitude'],
                'latitude' => $row['latitude'],
                'spoc_Name' => $row['spoc_name'],
                'spoc_mobile_no' => $row['spoc_mobile_no'],
                'spoc_phone' => $row['spoc_phone'],
                'spoc_email_id' => $row['spoc_email_id'],
                'scheme_name' => $row['scheme_name'],
                'tbn_number' => $row['tbn_number'],
                'perday_time' => $row['perday_time'],
                'id' => $row['id'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'mobile' => $row['mobile'],
                'trainer_name' => $row['trainer_name'],
                'trainer_id' => $row['trainer_id'],
                'trainer_qualification' => $row['trainer_qualification'],
                'trainers_experience' => $row['trainers_experience'],
                
            ];
            
           $i =  FileImports::insert($obj); 
           session(['isdata'=>1]);
           
        }
        
    }

     
        
}
