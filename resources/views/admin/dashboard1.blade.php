@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
    
    /* Remove the jumbotron's default bottom margin */ 
     .jumbotron {
      margin-bottom: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>



<div class="container">    
  <div class="row">
    <div class="col-sm-4">
      <div class="panel panel-primary">
        
        <div class="panel-body">VTP</div>
        <div class="panel-footer">{{$vtp}}</div>
      </div>
    </div>
    <div class="col-sm-4"> 
      <div class="panel panel-danger">
       
        <div class="panel-body">Student</div>
        <div class="panel-footer">{{$student}}</div>
      </div>
    </div>
    <div class="col-sm-4"> 
      <div class="panel panel-success">
        
        <div class="panel-body">Batch</div>
        <div class="panel-footer">{{$batch}}</div>
      </div>
    </div>
  </div>
</div><br>





@endsection
<!-- /.content-wrapper -->
