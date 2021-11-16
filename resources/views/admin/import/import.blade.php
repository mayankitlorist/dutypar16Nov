@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<!-- <?php
// print_r(Auth::user()->id); 
?> -->
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
                                Import Excel
                            </h3>
                            <!-- tools box -->
                            <!-- /. tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body pad">
                            <div class="tab-content">

                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">

                                    <!-- Card Body -->
                              <div class="card-body">

                                    <div class="container">
                                      <div class="card bg-light mt-3">
                                          <div class="card-header">
                                              Import File
                                          </div>
                                          <div class="card-body">
                                              <form action="{{ route('admin.addimportFile') }}" method="POST" enctype="multipart/form-data">
                                                   @csrf
                                                  <input type="file" name="file" class="form-control">
                                                  <br>
                                                  <button class="btn btn-success">Import Bulk Data</button>
                                              </form>
                                          </div>
                                      </div>
                                  </div>
                                
                              </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>

@endsection