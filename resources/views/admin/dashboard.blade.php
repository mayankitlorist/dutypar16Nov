@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Welcome, {{ Auth::user()->name }}!</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
            <div class="row ml-2 mt-4">
                <p class="text-dark">Welcome to the administrative Panel.</p>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</div>
@endsection
<!-- /.content-wrapper -->
