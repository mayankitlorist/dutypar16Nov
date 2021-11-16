@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<link rel="stylesheet" href="http://davidstutz.github.io/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

<?php
// print_r(Auth::user()->id); die;
?>
    <style>
        .save-content{
            margin-top: 8px;
            float: right;
        }
     </style>

    <div class="content-wrapper" style="min-height: 1244.06px;">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                Support Detail
                            </h3>
                            <!-- tools box -->
                            
                          <!--   <div class="card-tools">
                                <button  class="btn btn-primary"  data-toggle="modal" data-target="#userAddModal" style="float: right">Add Atendence Detail</button>
                            </div> -->
                            
                            <!-- /. tools -->
                        </div>
                        <!-- /.card-header -->
                        

                                 
                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">
                                  <div class="card-body pad">
                                    <div class="tab-content">
                                        
                                      
                                 @if(Auth::user()->role_type==5 || Auth::user()->role_type==4 || Auth::user()->role_type==3 || Auth::user()->role_type==1)
                                
                                        <form action="{{ url('admin/userfilter') }}" method="post" 
                                          enctype="multipart/form-data">
                                           @csrf
                                     <div class='row'>
                                     <div class=col-md-3>
										  <div class=”search-box”>
												    <input type=”text” class=”search-by-name” name="user" placeholder=UserId-Search >
								         </div>
                                     </div>
                                     <div class=col-md-3>
										  <div class=”search-box”>
												    <input type=”text” class=”search-by-name” name="batch" placeholder=Batch-Search >
									      </div>
                                     </div>
                                       
		                              <div class=col-md-3>
		                                      <button class=”search-btn” type=”submit”>Search</button>
					    				</div>
									  
                                      </form>
                                    </div>

                              @endif
                              
                                  <div class="card-body">


                                         <table id="example2" class="table table-bordered table-hover">
                                           
                                            <thead>
                                            <tr>
                                                <th>Profile Picture</th>
                                                <th>Temp Image</th>
                                                <th>Last Time Attendence</th>
                                                <th>Mark Attendence</th>
                                               
                                                </tr>
                                            </thead>
                                             <tbody>
                                                <?php 
                                                foreach ($users as $userss) {
                                                	
                                                	//print_r($userss->user_id); die;
                                                	/*if($userss){
                                                		$date1 = $userss->intime;
                                                    $time =(date("H:i:s",strtotime($date1)));
                                                }*/
                                                    
											    ?>
                                                <tr>
                                                	<td><div>
                                                		<?php if($userss){ ?>
                                                		<img class="profile-img" src="{{$userss->profile_image }}" width="150px" height="150px"/> 
                                                <?php	} ?>
                                                		
                                                	</div></td>
                                                	<td><div>
                                                		<?php if($userss){ ?>
                                                		<img class="profile-img" src="{{$userss->temp_image }}" width="150px" height="150px"/>
                                                		  <?php	} ?>
                                               
                                                	</div></td>
                                                    <td>{{$userss->intime}} </td>
                                                	<!--<td> <a data-toggle="modal" data-target="#userModal" ><button class="btn-primary">Mark Attendence</button> </i></a> 
                                                	</td>-->
                                                	<td> <a href="{{url('admin/adduserbatch/'.$userss->user_id)}}"><button class="btn-primary">Mark Attendence</button></a>
                                                	</td>
                                                	<?php  }?>
                                                
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>
           	
           	<div id="userModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        
                       <form action="{{ route('adduser') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            <table id="example2" class="table table-bordered table-hover">
                                           
                                            <thead>
                                            <tr>
                                                <th>From Date Time</th>
                                                <th>To Date Time</th>
                                                <th>Batch Id</th>
                                                <th>Intime</th>
                                                <th>Outtime</th>
                                               
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                  <tr>
                                                  @foreach ($users as $userss) 
                                                  	<input type="hidden" name="uid" value="{{$userss->user_id}}">
                                                  @endforeach
                                                	<td><input type="date" name="date" required></td>
                                                	<td><input type="date" name="date1" required></td>
                                                	<td> <select name="batch_id" required> 
                                                	<option value="">Select Batch</option>
                                                		@foreach($batch as $batchs)
														   <option value="{{$batchs ->batch_id}}">
                                                       {{$batchs -> batch_name}}
                                                     </option>
                                                      @endforeach
                                                  </select>
                                                  </td>
                                                	<td><input type="time" name="time" required></td>
                                                	<td><input type="time" name="time1" ></td>
                                                </tr>

                                            </tbody>
                                        </table>
                       					<div class = "row"> 
                       						<div class = "col-md-10"> </div>
                       						<div class = "col-md-2"> 

                       						  <button type="submit" class="btn btn-primary text-uppercase">Submit</button>
                       						</div>
                       					</div>
                       					
                        </form>
                    </div>
                </div>
            </div>
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>
  	
  	 <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
     <script type="text/javascript" src="http://davidstutz.github.io/bootstrap-multiselect/dist/js/bootstrap-multiselect.js">
</script>

   
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script>
  
      
    </script>

@endsection
