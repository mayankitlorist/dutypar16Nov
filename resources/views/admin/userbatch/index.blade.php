@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')

<link rel="stylesheet" href="http://davidstutz.github.io/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">


 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  

    <style>

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}



         .multiselect-container.dropdown-menu.show{
                    top: 40px !important;
                    transform: initial !important;
                    right: 0 !important;
                    max-height: 350px;
                    overflow-y: auto;
                }
                .custom-select{
                    text-align: left;
                }


        .save-content{
            margin-top: 8px;
            float: right;
        }


         .multiselect-container.dropdown-menu.show {
        top: 40px !important;
        transform: initial !important;
        right: 0 !important;
        max-height: 350px;
        overflow-y: auto;
    }

    .custom-select {
        text-align: left;
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
                                Online Batch Detail List
                            </h3>
                            <!-- tools box -->

			 

                            <!--<div class="card-tools">
                                <button  class="btn btn-primary"  data-toggle="modal" data-target="#userAddModal" style="float: right">Add User Batch</button>
                            </div>-->
                            <!-- /. tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body pad">
                            <div class="tab-content">

                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">

                                    <!-- Card Body -->
                                    <div class="card-body">

                                        <!-- Card Title-->
                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                               <th>Batch name</th>
                                                <th>User name</th>
                                                <th>Office name</th>
                                                <th>isonline</th>
                                                <th>Url generate</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                                    
                                            <tbody>
                                            @foreach($user_batchs as $userss)
                                          
                                                <tr>
                                                     <td>{{ $userss->batch_name }}</td>
                                                     <td>{{ $userss->name }}</td>
                                                     <td>{{ $userss->officename}}</td>


                                                      <td>
                                                          
                                                           @if($userss->is_online==1)
                                                    <div class="custom_switch">
                                                        <label class="switch">
                                                            <input type="checkbox" class="statusToogle" data-status="0" data-id="{{$userss->user_batch_id}}"  checked>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                @else
                                                    <div class="custom_switch">
                                                        <label class="switch">
                                                            <input  type="checkbox" class="statusToogle" data-status="1" data-id="{{$userss->user_batch_id}}"  >
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div> 
                                                @endif     

                                                      </td>
                                                    
                                                     <td >
                                                          
                                                           <a class="js-edit-logo" id="generateurl" data-toggle="modal" data-target="#usereditModal12"><i>Generate Url</i>


                                                          </div>         
                                                          <!-- <button class="btn btn-white" ><a href="">Generate Url</a></button>
   -->                                                   <!--  https://itlorist.com/dutyparnew/public/admin/login -->
                                                     </td>
                                                      </td> 
                                                     



                                                      <td>
                                                        <a class="js-edit-logo" data-toggle="modal" data-target="#usereditModal" data-role="{{$userss->batch_id}}" data-role1="{{$userss->user_id}}"data-role2="{{$userss->office_id}}"
                                                            data-userbatchid="{{$userss->userbatchid}}" data-officeid="{{$userss->userofficeid}}"
                                                            data-username="{{$userss->name}}"
                                                         style="cursor:pointer" title="edit state"><i class="fa fa-edit"></i>

                                                        <!-- <a class="delete-material" data-role="{{$userss->batch_id}}" data-role1="{{$userss->user_id}}"data-role2="{{$userss->office_id}}" title="delete logo" onClick="return  confirm('Are you sure you want to delete ?')"><i class="fa fa-trash-alt"></i></a
                                                        ></a>
                                                         -->
                                                        </td>

                                                     </tr>
                                            @endforeach
                                            </tbody>
                                                                                            
                                            </tbody>

                                           
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>


 <div id="usereditModal12" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Generate Url</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                       
                            <div class="modal-body">

                               
                                 <div class="col-md-12">
                                    <div>
                                       <input type="textarea" value=" https://itlorist.com/dutyparnew/public/admin/login" readonly size="70">  

                                                      
                                         <div>
                                         
                                 
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                       
                    </div>
                </div>
            </div>


            <div id="userAddModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Batch Detail</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <form action="{{ url('admin/add_user_batch') }}" method="post" enctype="multipart/form-data" data-parsley-validate>

                            @csrf
                            <div class="modal-body">

                               
                                

                               		 <div class="form-group position-relative">
                                            <label for="projectinput2">Batch name*</label>
                                           
                                            <select id="frameworks1"  class="form-control select_custom_new"
                                                name="batch_id" required data-parsley-error-message="Field is required" title="Select Batch">
                                                <option value="">Select Batch</option>
                                               @foreach($batch as $batchs)
                                              <option value="{{$batchs->batch_id}}">
                                                   {{$batchs -> batch_name}}
                                                  @endforeach
                                            </select>
                                        </div>

                                <div class="form-group position-relative">
                                            <label for="projectinput2">Users*</label>
                                           
                                            <select id="frameworks" multiple class="form-control select_custom_new"
                                                name="user_id[]" required data-parsley-error-message="Field is required" title="Select User">
                                                @foreach($user as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                         <div class="form-group position-relative">
                                            <label for="projectinput2">Center name*</label>
                                           
                                            <select id="frameworks2"  class="form-control select_custom_new"
                                                name="office_id" required data-parsley-error-message="Field is required" title="Select Center">
                                                <option value="">Select Center</option>

                                               @foreach($office as $offices)
                                        <option value="{{$offices->id}}">
                                             {{$offices->name}}
                                            @endforeach
                                            </select>
                                        </div>



                               
                                 <!-- <div class="form-group">
                                    <div>
                                        <label >Center name*</label>                                  
                                         <div>
                                            <select name="office_id" required>
                                            <option value="">Select Center</option>
                                              @foreach($office as $offices)
                                        <option value="{{$offices->id}}">
                                             {{$offices->name}}
                                            @endforeach
                                       
                                          </select>
                                 
                                    </div>
                                    </div>
                                </div> -->
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary text-uppercase">Submit</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>




            <div id="usereditModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Batch Detail</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <form action="{{ url('admin/batch_update') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            <input type="hidden" name="batchId" id="batchid">
                            <input type="hidden" name="officeId" id="officeid">

                            <div class="modal-body">

                                <div class="form-group position-relative">
                                            <label for="projectinput2">Users*</label>
                                            <input type="text" id="username" value="" readonly=""     style="width: 100%">
                                            <!-- <select id="frameworks3" class="form-control select_custom_new" id="role1"
                                                 disabled="true" data-parsley-error-message="Field is required" title="Select User">
                                                @foreach($user as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select> -->
                                  </div>


                                 <div class="form-group">
                                    <div>
                                        <label >Batch name*</label>                                  
                                         <div>
                                           <select  id="frameworks4" name="batch_id" id="role" required>
                                             @foreach ($batch as $role)
                                            <option value="{{ $role->batch_id }}"/>
                                              {{ $role->batch_name }}
                                            </option>
                                        @endforeach
                                            </select>
                                 
                                    </div>
                                    </div>
                                </div>

                                




                              <!-- <div class="form-group position-relative">
                                        <label >User name*</label>                                  
                                         <div>
                                        <select name="user_id" id="role1" required>
                                             @foreach ($user as $role)
                                            <option value="{{ $role->id }}"/>
                                              {{ $role->name }}
                                            </option>
                                        @endforeach
                                          </select>
                                                               
                                    </div>
                                 </div> -->

                                 <div class="form-group">
                                    <div>
                                        <label >Center name*</label>                                  
                                         <div>
                                         <select  id="frameworks5" name="office_id" id="role2" required>
                                             @foreach ($office as $role)
                                            <option value="{{ $role->id }}"/>
                                              {{ $role->name }}
                                            </option>
                                        @endforeach
                                          </select>
                                 
                                    </div>
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
            </div>
           
            <!-- ./row -->
        </section>
        <!-- /.content -->
    </div>
   
   <div class="popup" onclick="myFunction()">Click me!
  <span class="popuptext" id="myPopup">Popup text...</span>
</div>


<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript" src="http://davidstutz.github.io/bootstrap-multiselect/dist/js/bootstrap-multiselect.js">
</script>

<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>





  <script type="text/javascript">
      $(".js-edit-logo").on('click',function (e) {
            var id =   $(this).attr('data-id');
            var name =   $(this).attr('data-name');
            var email =   $(this).attr('data-email');
            var role =   $(this).attr('data-role');
            var role1 =   $(this).attr('data-role1');
            var role2 =   $(this).attr('data-role2');
            var username =   $(this).attr('data-username');



            var batchid =   $(this).attr('data-userbatchid');
            var officeid = $(this).attr('data-officeid');
            
            
            $('#batchid').val(batchid);
            $('#officeid').val(officeid);

            $('#username').val(username);




 $("#usereditModal .modal-dialog #role option:selected").removeAttr("selected");
            var roleid = '#usereditModal .modal-dialog #role option[value=' + role +']';
            $(roleid).attr('selected', 'selected');



      $("#usereditModal .modal-dialog #role1 option:selected").removeAttr("selected");
            var roleid = '#usereditModal .modal-dialog #role1 option[value=' + role1 +']';
            $(roleid).attr('selected', 'selected');

             $("#usereditModal .modal-dialog #role2 option:selected").removeAttr("selected");
            var roleid = '#usereditModal .modal-dialog #role2 option[value=' + role2 +']';
            $(roleid).attr('selected', 'selected');

 });

      $(document).on('click','#generateurl',function(){

        alert(url());

      });
  
  



 $('#frameworks').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });
 
 $('#frameworks1').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });

 $('#frameworks2').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });

 $('#frameworks3').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });

 $('#frameworks4').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });
 $('#frameworks5').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });

 $(document).on('click','.statusToogle',function(){

          var status = $(this).attr('data-status');
          var id = $(this).attr('data-id');
          
           $.ajax({
           type: "POST",
            url:"{{url('admin/checkboxstatus')}}",
           data: { "_token": "{{ csrf_token() }}","status" : status, "id" : id },
           success: function(data)
           {
               console.log("====="+data);
               location.reload(); 
           }
         }); 
 });
 
</script>


  

@endsection


