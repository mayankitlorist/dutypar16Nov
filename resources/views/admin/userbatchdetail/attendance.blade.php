@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
    <style>
        .save-content{
            margin-top: 8px;
            float: right;
        }
        .card-body .snapshotResultImage{
        	height: 100vh !important;
        	width: 100% !important;
        	overflow-x: scroll;
        	overflow-y: scroll;
        }
    </style>
    <style>
#my_camera{
 width: 320px;
 height: 240px;
 border: 1px solid black;
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
                                Attendance
                            </h3>
                            <!-- tools box -->
                            <!-- <div class="card-tools">
                                <button  class="btn btn-primary"  data-toggle="modal" data-target="#userAddModal" style="float: right">Add Batch</button>
                            </div> -->
                            <!-- /. tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body pad">
                            <div class="tab-content">
                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">
                                    <!-- Card Body -->
                                    <div class="card-body">

                                        <form action="{{ url('admin/mark_attendance') }}" method="post" enctype="multipart/form-data">
                                             @csrf

                                             @if(session()->has('msg'))
                                              <div class="error-display">
                                              <h6 class="text-center" style="color:green; font-size: 26px;">
                                             Attendance Successfully
                                              </h6> 
                                              </div>
                                              @endif

                                              <!-- @if($errors->any())
                                                  <h4 class="error-msg">{{$errors->first()}}</h4>
                                              @endif -->

                                        <div class="form-group">
                                       
                                         <label >Batch*</label>                                  
                                            <div class="form-group">

                                            <select name="batch_id" required id="batch_id">
                                            <option value="">Select Batch</option>
                                            @foreach($batchs as $batch)
                                                         <option value="{{$batch->id}}" >{{$batch->batch_name}}</option>
                                             @endforeach 
                                            </select>
                                               
                                                </div>
                                        </div>

                                          <div class="form-group">
                                       
                                         <label >Join Time </label>                                  
                                            <div class="form-group">

                                            <select name="jointime" required id="timeselect">
                                           <option value="">Select Time</option>
                                           
                                            </select>
                                               
                                                </div>
                                        </div>

                                        <div id="my_camera"></div>
                                        <input type=button value="Take Snapshot" onClick="take_snapshot()" required width="80%">
                                        <input type="hidden" name="image" class="image-tag" >
                                        <div id="results" style="display: none;"></div>
                                        <div id="results1"  ></div>

                                         <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary text-uppercase">Submit</button>
                                    </div>



                                         </form>   
                                        
                                        
                                    </div>
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
   <script type="text/javascript" src="{{ asset('admin/plugins/jquery/webcam.min.js') }}" ></script>

   <script language="JavaScript">

 // Configure a few settings and attach camera
 Webcam.set({
  width: 320,
  height: 240,
  image_format: 'jpeg',
  jpeg_quality: 90
 });
 Webcam.attach( '#my_camera' );

 // preload shutter audio clip
 var shutter = new Audio();
 shutter.autoplay = true;
 shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';

function take_snapshot() {
 // shutter.play();

 // take snapshot and get image data
 Webcam.snap( function(data_uri) {
 // display results in page
 $(".image-tag").val(data_uri);
 document.getElementById('results').innerHTML = 
  '<img src="'+data_uri+'" height="5500px" width="5500px"/>';
  document.getElementById('results1').innerHTML = 
  '<img src="'+data_uri+'" height="100%" width="100%"/>';
// '<input type="image" name="image" value="'+data_uri+'"/>';

 } );
}

$(document).on('change','#batch_id',function(){
  var batchid= $('#batch_id').val();
  

   $.ajax({
           type: "POST",
            url:"{{url('admin/checkattendancedetails')}}",
           data: { "_token": "{{ csrf_token() }}","batchid" : batchid },
           success: function(data)
           {
            $('#timeselect').empty();
              console.log(data.status );
              if(data.status=='success1'){
                var options = ('<option value="">Select Time</option>'+
                                '<option value="intime" disabled>IN TIME</option>'+
                                '<option value="outtime" disabled>OUT TIME</option>');

                  $('#timeselect').append(options);
              }else if(data.status=='success'){
                  var options = ('<option value="">Select Time</option>'+
                                '<option value="intime" disabled>IN TIME</option>'+
                                '<option value="outtime">OUT TIME</option>');

                  $('#timeselect').append(options);          

              }else{

                  var options = ('<option value="">Select Time</option>'+
                                '<option value="intime" >IN TIME</option>'+
                                '<option value="outtime" disabled>OUT TIME</option>');

                  $('#timeselect').append(options);

              }
           }
         }); 

});


</script>
@endsection
