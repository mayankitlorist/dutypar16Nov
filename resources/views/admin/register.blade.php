<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf_token" content="{{ csrf_token() }}" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{!! asset('admin/plugins/fontawesome-free/css/all.min.css') !!}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{!! asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') !!}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{!! asset('admin/assets/css/adminlte.min.css') !!}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        h4.error-msg {
            color: red;
            text-align: center;
            font-size: 1.25rem;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <!-- <a href="#"><b>Admin</b></a> -->
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register as an Admin</p>
            @if($errors->any())
                <h4 class="error-msg">{{$errors->first()}}</h4>
            @endif
            <form action="{{ route('admin.register') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="email-1">Organization*</label>
                    <div>
                        <select name="organization_id" requiredstyle="width: 100%;">
                            <option value="" disabled selected>Select Organization</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"/>
        
                               
                                {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{route('admin.organization.create')}}" style="cursor:pointer" title="Add Organization" target="_blank">Add more Organization</a>
                </div>

                <div class="form-group">
                    <label for="email-1">Role Type*</label>
                    <div>
                        <select name="roletype_id" requiredstyle="width: 200%;">
                            <option value="" disabled selected>Select Roletype</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}"/>
                                  {{ $type->role_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                <div class="form-group">
                    <label for="exampleInputEmail1">Profile Image</label>
                    <input type="file" class="form-control" name="profile_image" value="{{old('profile_image')}}" >
                </div>
                <div class="form-group">
                    <label for="email-1">Name*</label>
                    <div><input type="text" class="form-control" name="name" value="{{ old('name') }}" required></div>
                </div>

                <div class="form-group">
                    <label for="email-1">Uid*</label>
                    <div><input type="text" class="form-control" name="uid" value="{{ old('uid') }}" required></div>
                </div>

                <div class="form-group">
                    <label for="email-1">Password*</label>
                    <div><input type="password" class="form-control" name="password" value="{{ old('password') }}" required></div>
                </div>

                <div class="form-group">
                    <label for="email-1">Email*</label>
                    <div><input type="text" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email-1">Phone Number*</label>
                    <div><input type="number" class="form-control" name="phone" value="{{ old('phone') }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.form-box -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{!! asset('admin/plugins/jquery/jquery.min.js') !!}"></script>
<!-- Bootstrap 4 -->
<script src="{!! asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
<!-- AdminLTE App -->
<script src="{!! asset('admin/assets/js/adminlte.js') !!}"></script>

</body>
</html>
