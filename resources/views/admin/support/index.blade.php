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
                                                <th>Action</th>
                                                </tr>
                                            </thead>
                                            
                                        </table>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>
           
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>
  	
  	 <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    
@endsection
