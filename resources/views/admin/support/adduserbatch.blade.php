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

            	<strong>User name : </strong> &nbsp;&nbsp;<span>{{$users->name}}</span>&nbsp;&nbsp;&nbsp;
            	<strong>User uid : </strong> &nbsp;<span>{{$users->uid}}</span>

                <div class="col-md-12">
                    	 <form action="{{ route('adduser') }}" method="post" enctype="multipart/form-data">

                            @csrf

                            <div class="modal-body">
                            	<input type="hidden" name="uid" value="{{$users->id}}">
                            
                               <div class="row">

                               	  	<div class="col-md-3">
                               			 <label for="email-1">From date*</label>
                                    	 <input type="date" class="form-control" name="date" value="" required>
                               		</div>
                               
                                	<div class="col-md-3">
                               			 <label for="email-1">To date*</label>
                                    	 <input type="date" class="form-control" name="date1" value="" required>
                               		</div>
                               		<div class="col-md-3">

                               			 <label for="email-1">Batch*</label>
                               			 <div>
                                    	 <select name="batch_id" required> 
                                                	<option value="">Select Batch</option>
                                                		@foreach($data as $datas)
														   <option value="{{$datas ->batchId}}">
                                                       {{$datas -> batch_name}}
                                                     </option>
                                                      @endforeach
                                                  </select>
                                          </div>
                               		</div>
                               		<div class="col-md-3">
                               			 <label for="email-1">Intine*</label>
                                    	 <input type="time" class="form-control" name="time" value="" required>
                               		</div>
                               		<div class="col-md-3">
                               			 <label for="email-1">Outtime*</label>
                                    	 <input type="time" class="form-control" name="time1" value="" required>
                               		</div>
                               </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary text-uppercase">Submit</button>
                            </div>
                        </form>
                </div>    
           	</div>
           	
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
