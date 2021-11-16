@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<?php
// print_r(Auth::user()->id); 
?>
    <style>
        .save-content{
            margin-top: 8px;
            float: right;
        }

         .plus{

            position:relative;
            left:1px;
            top:-70px;
            
        }
        .plus:hover{

            background-color:#f1f1f1;
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
                                User List  
                            </h3>
                            <!-- tools box -->
                          <@if (Auth::user()->role_type==1)
                            <div class="card-tools">
                                <button  class="btn btn-primary"  data-toggle="modal" data-target="#userAddModal" style="float: right">Add User</button>
                            </div>
                            @endif
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
                                                <th>Profile</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Center</th>
                                                <th>Trainer</th>

                                                @if (Auth::user()->role_type==1)
                                                <th>Action</th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                            <?php 
                                            // print_r($user); die;
                                            ?>
                                                <tr>
                                                    <td><div><img class="profile-img" src="{{$user['profile_image'] }}" width="150px" height="150px"/><span class="plus deleteimg"  data-id="{{$user['id']}}">&times;</span></div></td>
                                                    <td>{{ $user['name'] }}</td>
                                                    <td>{{ $user['email']}}</td>
                                                    <td>

                                                        @if($user['role_type'] == 1)
                                                        Teacher
                                                        @elseif($user['role_type'] == 2)
                                                        Student
                                                        @else
                                                        Trainer
                                                        @endif
                                                    <!-- {{ $user['role_type'] }} -->
                                                    </td>
                                                    <td>
                                                        @foreach($user['office_id'] as $office)
                                                           <li>{{ $office->name }}</li>
                                                        @endforeach
                                                    </td>
                                                    <td>{{$user['trainer']}}</td>
                                                     @if (Auth::user()->role_type==1)
                          
                                                    <td>
                                                        <a class="delete-material" href="{{ route('approve.user',$user['id']) }}"><i class="fa fa-thumbs-up"></i></a
                                                        >
                                                        <a class="js-edit-logo" data-toggle="modal" data-target="#userEditModal" data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}" data-email="{{ $user['email'] }}" data-role="{{ $user['role_type'] }}" data-office="{{ $user['office_id'] }}" data-parent="{{ $user['parent_id'] }}"  data-uid="{{ $user['uid'] }}" data-phone="{{ $user['phone'] }}" style="cursor:pointer" title="edit state"><i class="fa fa-edit"></i></a>
                                                        <a class="delete-material" href="{{ route('delete.user',$user['id']) }}"  data-id="{{ $user['id'] }}"  title="delete logo" onClick="return  confirm('Are you sure you want to delete ?')"><i class="fa fa-trash-alt"></i></a
                                                        ></td>
                                                        @endif
                                                </tr>
                                            @endforeach
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

            <div id="userAddModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add User</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <form action="{{ route('admin.add.user') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            <input type="hidden" name="id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Profile Image</label>
                                    <input type="file" class="form-control" name="profile_image" value="{{old('profile_image')}}" >
                                </div>
                                <div class="form-group">
                                    <label for="email-1">Name*</label>
                                    <div><input type="text" class="form-control" name="name" value="" required></div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Uid*</label>
                                    <div><input type="text" class="form-control" name="uid" value="" required></div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Password*</label>
                                    <div><input type="password" class="form-control" name="password" value="" required></div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Email*</label>
                                    <div><input type="text" class="form-control" name="email" value="" required>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="email-1">Phone Number*</label>
                                    <div><input type="text" class="form-control" name="phone" value="" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Role*</label>
                                    <div>
                                        <select name="role_type" required>

                                        <option value="" disabled selected>Select Organization</option>
                                         @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"/>
                                              {{ $role->role_name }}
                                            </option>
                                        @endforeach
                                           <!--  <option value="" disabled selected>Select Role Type</option>
                                            <option value="employee" {{old('role_type')=='employee'?'selected':''}}>Student</option> -->
                                            <!-- <option value="manager" {{old('role_type')=='manager'?'selected':''}}>Manager</option> -->
                                            <!-- <option value="admin" {{old('role_type')=='admin'?'selected':''}}>Teacher</option> -->
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Center*</label>
                                    <div>
                                        <select name="office_id[]" required multiple style="width: 100%;">
                                            <option value="" disabled selected>Select Center Type</option>
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}"
                                                        {{ (collect(old('office_id'))->contains($office->id)) ? 'selected':'' }}
                                                />
                                                {{ $office->name }}
                                                </option>
                                            @endforeach
                                        </select>
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
            <div id="userEditModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit User Details</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                        </div>
                        <form action="{{ route('admin.update1.user') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            <input type="hidden" name="id" id="userid">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Profile Image</label>
                                    <input type="file" class="form-control" name="profile_image" id="profile">
                                </div>
                                <div class="form-group">
                                    <label for="email-1">Name</label>
                                    <div><input type="text" id="name" class="form-control" name="name" required></div>
                                </div>

                              

                                <div class="form-group">
                                    <label for="email-1">Uid</label>
                                    <div><input type="text" id="uid" class="form-control" name="uid" required></div>
                                </div>

                                  <div class="form-group">
                                    <label for="email-1">Email</label>
                                    <div><input type="text" id="email" class="form-control" name="email" required>
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="phone-1">Phone</label>
                                    <div><input type="text" id="phone" class="form-control" name="phone" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Role</label>
                                    <div>
                                        <select name="role_type" id="role">
                                             @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"/>
                                              {{ $role->role_name }}
                                            </option>
                                        @endforeach
                                           <!--  <option value="" disabled selected>Select Role Type</option>
                                            <option value="employee">Student</option> -->
                                            <!-- <option value="manager">Manager</option> -->
                                            <!-- <option value="admin">Teacher</option> -->
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email-1">Center</label>
                                    <div>
                                        <select name="office_id[]" id="office" multiple style="width: 100%;">
                                            @foreach ($offices as $office)
                                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email-1">Reporting To</label>
                                    <div>
                                        <select name="parent_id" id="parent_id" style="width: 100%;">
                                            @foreach ($admins as $admin)
                                                <option value="{{ $admin->id }}">{{ ucwords($admin->name) }}</option>
                                            @endforeach
                                        </select>
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

    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(".js-edit-logo").on('click',function (e) {
            var id =   $(this).attr('data-id');
            var name =   $(this).attr('data-name');
            var email =   $(this).attr('data-email');
            var role =   $(this).attr('data-role');
            var office =   $(this).attr('data-office');
            var parent = $(this).attr('data-parent');
            var uid = $(this).attr('data-uid');
            var phone = $(this).attr('data-phone');



            $("#userEditModal .modal-dialog #userid").val(id);
            $("#userEditModal .modal-dialog #name").val(name);
            $("#userEditModal .modal-dialog #email").val(email);
            $("#userEditModal .modal-dialog #uid").val(uid);
            $("#userEditModal .modal-dialog #phone").val(phone);


            $("#userEditModal .modal-dialog #role option:selected").removeAttr("selected");
            var roleid = '#userEditModal .modal-dialog #role option[value=' + role +']';
            $(roleid).attr('selected', 'selected');

            $("#userEditModal .modal-dialog #parentid option:selected").removeAttr("selected");
            var parentid = '#userEditModal .modal-dialog #parentid option[value=' + parent +']';
            $(parentid).attr('selected', 'selected');

            $("#userEditModal .modal-dialog #office option:selected").removeAttr("selected");
            JSON.parse(office).forEach(function (offic) {
                var officeid = '#userEditModal .modal-dialog #office option[value=' + offic.id +']';
                $(officeid).attr('selected', 'selected');
            });
        });
        

         $(document).on('click','.deleteimg',function(){
            var id = $(this).attr('data-id');
            // alert(id)
            var deleteFile = confirm("Do you really want to Delete?");
            //alert(deleteFile) 
              if (deleteFile == true) {
                  // AJAX request
               $.ajax({
                  url: "{{ url('admin/removeimg') }}",
                  method: 'post',
                  data: { "_token" : "{{csrf_token()}}","id": id, },
                  success: function(value){

                    location.reload();
                }

               })

        }

})
    </script>
@endsection