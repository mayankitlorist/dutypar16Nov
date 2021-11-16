@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<style media="screen">
  .cardText{text-align: center;}
  .card-body{padding:1.25rem 0.8rem}
  .col h3{font-weight: 600;}
  .card-body1{background-color:#FFE3E3 }
  .card-body2{background-color:#CCF2F4 }
  .card-body3{background-color:#CAF7E3 }
  .card-body4{background-color: #F7DBF0}
  .cardBodyHead{font-weight: 600;}
</style>
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

            <div class="container-fluid row cardText"><!-- card Div Start -->
              <div class="col-md-6 col-lg-3">
                  <div class="card report-card">
                      <div class="card-body card-body1">
                          <div class="row d-flex justify-content-center">
                              <div class="col">
                                  <p class="text-dark mb-0 cardBodyHead">Total No of User Registration</p>
                                  <h3 class="m-1">4,17,813</h3>
                                  <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Registration Today</p>
                              </div>
                              <div class="col-auto align-self-center">
                                  <div class="report-main-icon bg-light-alt">
                                      <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                  </div>
                              </div>
                          </div>
                      </div><!--end card-body-->
                  </div><!--end card-->
              </div> <!--end col-->
              <div class="col-md-6 col-lg-3">
                  <div class="card report-card">
                      <div class="card-body card-body2">
                          <div class="row d-flex justify-content-center">
                              <div class="col">
                                  <p class="text-dark mb-0 cardBodyHead">Total Average Attendance Marked Daily for Last 30 Days</p>
                                  <h3 class="m-1">3,08,113</h3>
                                  <!-- <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>8.5%</span> New Sessions Today</p> -->
                              </div>
                              <div class="col-auto align-self-center">
                                  <div class="report-main-icon bg-light-alt">
                                      <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                  </div>
                              </div>
                          </div>
                      </div><!--end card-body-->
                  </div><!--end card-->
              </div> <!--end col-->
              <div class="col-md-6 col-lg-3">
                  <div class="card report-card">
                      <div class="card-body card-body3">
                          <div class="row d-flex justify-content-center">
                              <div class="col">
                                  <p class="text-dark mb-0 cardBodyHead">Total Number of Organisation Registered</p>
                                  <h3 class="m-1">7,737</h3>
                                  <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>12%</span> New Organisations Today</p>
                              </div>
                              <div class="col-auto align-self-center">
                                  <div class="report-main-icon bg-light-alt">
                                      <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                  </div>
                              </div>
                          </div>
                      </div><!--end card-body-->
                  </div><!--end card-->
              </div> <!--end col-->
              <div class="col-md-6 col-lg-3">
                  <div class="card report-card">
                      <div class="card-body card-body4">
                          <div class="row d-flex justify-content-center">
                              <div class="col">
                                  <p class="text-dark mb-0 cardBodyHead">Attendance Marked Today</p>
                                  <h3 class="m-1">3,24,972</h3>
                                  <p class="mb-0 text-truncate text-muted"><span class="text-success"><i class="mdi mdi-trending-up"></i>80%</span> Attendance Marked Today</p>
                              </div>
                              <div class="col-auto align-self-center">
                                  <div class="report-main-icon bg-light-alt">
                                      <i data-feather="users" class="align-self-center text-muted icon-sm"></i>
                                  </div>
                              </div>
                          </div>
                      </div><!--end card-body-->
                  </div><!--end card-->
              </div> <!--end col-->
            </div><!-- card Div end -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</div>
@endsection
<!-- /.content-wrapper -->
