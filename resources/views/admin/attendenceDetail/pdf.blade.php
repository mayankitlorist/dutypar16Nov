<!DOCTYPE html>
<html>
<head>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
  
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>
</head>
<body>

<table id="customers">
  <tr>
  	<th>Candidate Id</th>
    <th>Name</th>
	<th>Batch_name</th>
	<th>Intime</th>
	<th>Outtime</th>
	<th>Date</th>
  </tr>
   @foreach ($users as $userss)

<?php 

    $date1 = $userss->intime;
    $time =(date("H:i:s",strtotime($date1)));

    $date2 = $userss->outtime;
    $time2 =(date("H:i:s",strtotime($date2)));

    $date21 = $userss->intime;
    $datenew =(date("Y-m-d",strtotime($date21)));
?>
<tr>
	<td>{{$userss->uid}} </td>
    <td>{{$userss->name}} </td>
    <td>
    	@if (!empty($userss->batch_name) )
		{{$userss->batch_name}}
		@else
		-
		@endif
     </td>
    <td> {{$time}} </td>
     <td>
    @if (!empty($date2) )
		{{$time2}}
		@else
		-
		@endif </td>
    <td>{{$datenew}} </td>

      </tr>
   @endforeach
 
</table>

</body>
</html>








