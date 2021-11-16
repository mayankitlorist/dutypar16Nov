<?php

namespace App\Exports;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\UserAttendance;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
class AttendencedetailExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();
        
        if(date('Y-m-d')){
         $date =  session('date3');
         $date1 = null;
        
         $users = UserAttendance::getalldataattendencenew($date,$user->id);
        return $users;
        }else{

        $batchid = null;
        $date1 =  session('date');
        $date2 =  session('date1');

         $users = UserAttendance::getalldataattendencenewfilter($batchid,$date1,$date2);
         return $users;
   		}
       
    }


    public function headings(): array
    {
    	
        return [
        	'Name',
            'Batch name',
            'Intime',
            'Outtime',
            
        ];
    }

    public function registerEvents(): array
        {
        return [
            AfterSheet::class    => function(AfterSheet $event) 
            {

                       $cellRange = 'A1:D1'; // All headers
                       $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Calibri');
               $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

        
            },
              ];
       }
    
}
