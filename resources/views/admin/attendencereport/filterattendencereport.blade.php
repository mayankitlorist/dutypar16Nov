@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<link rel="stylesheet" href="http://davidstutz.github.io/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">


    <style>
        .save-content{
            margin-top: 8px;
            float: right;
        }
        .cardMainParaRow{
          padding: .75rem 1.25rem;
          border-bottom: 1px solid rgba(0,0,0,.125);
          position: relative;
          max-height: 130px;
          overflow: auto;
          scroll-behavior: smooth;

        }
        .cardMainAnimation{

          animation: slideIn 1s ease;
        }
        @keyframes slideIn {
          0%{
transform: translateY(250px);
            opacity: 0;}

          100%{
transform: translateY(0);
            opacity:1;
          }


        }

        .multiselect-container{
          max-height: 400px;
          overflow-y: scroll;
          top: 50px !important;
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
                                Attendence Detail List
                            </h3>
                            <!-- tools box -->

                          <!--   <div class="card-tools">
                                <button  class="btn btn-primary"  data-toggle="modal" data-target="#userAddModal" style="float: right">Add Atendence Detail</button>
                            </div> -->

                            <!-- /. tools -->
                        </div>
                        <!-- /.card-header -->

                        <!-- card-paragraph -->
                        <div class="cardMainParaRow " id="cardScrollAuto">
                          <p class="cardMainAnimation">Rules defined for the validating the attendance are as follows</p>
                          <ol class="cardMainAnimation">
                            <li>Total hours of the batch – The total batch hours for a day should be as per the data shared by VTP on Mahaswayam Portal at the time of registration of batch.</li>
                            <li>Minimum time difference between IN Time and OUT time – As per the daily batch hours defined by VTPs at the time of registration of that batch (relaxation of 30 minutes is being provided. Eg, if Batch Hours entered on Mahaswayam Portal is 4 hours for each day, the time difference between IN Time and Out Time should be at least 3.5 hours (3 hours and 30 minutes).</li>
                            <li>Maximum time difference between IN Time and OUT time- 18 Hours (In Time should be after 6 am in the morning and Out time should be before 12 midnight. In Time or Out Time attendance will NOT be considered between 12 midnight to 6 am in the morning).</li>
                            <li>VTPs can arrange the classes anytime between 6 am and 12 midnight.</li>
                            <li>Additionally, the student IN TIME and OUT TIME should be on the SAME day. The Batch should be taught at one stretch on the same day. In case, the batch is taught in an Offline and Online mode, both the online and offline sessions should be taught on the same day.</li>
                            <li>Rule for teacher/trainer attendance – Same rule as students. Relaxation time of 30 minutes in the total batch hours can be provided</li>
                          </ol>
                          <p class="cardMainAnimation"> The VTPs and students are thereby, instructed to strictly follow the guidelines mentioned above. Failure to do so will result in cancellation of the attendance. In case of any queries, please reach out to MSSDS team</p>
                        </div>

                        <!-- /.card-paragraph -->

                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">
                                  <div class="card-body pad">
                                    <div class="tab-content">


                                  <form action="{{ url('admin/ownerbatchfilter') }}" method="post"
                                          enctype="multipart/form-data">
                                           @csrf
                                     <div class='row'>
                                      <div class=col-md-2>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                              <label class="form-group">Teacher Name*</label>
                                                <select class="frameworks11" id="teacher" name="teacher" >
                                                     <option value="">Select teacher</option>
                                                   @foreach($teacher as $teachers)
                                                  <option value="{{$teachers ->id}}">
                                                       {{$teachers -> name}}
                                                     </option>
                                                      @endforeach
                                                </select>
                                            </div>
                                          <!-- </div> -->
                                       </div>
                                       <div class=col-md-2>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                              <label class="form-group">Trainer Name*</label>
                                                <select id="frameworks2" class="form-control" name="trainer" >
                                                    <option value="">Select trainer</option>
                                                </select>
                                            </div>
                                          <!-- </div> -->
                                       </div>

                                     <div class=col-md-2>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                              <label class="form-group">Batch Name*</label>
                                                <select id="frameworks1" class="form-control" name="batch" >
                                                    <option value="">Select batch</option>
                                                </select>
                                            </div>
                                          <!-- </div> -->
                                       </div>

                                        <div class=col-md-2>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                              <label class="form-group">From-Date*</label>
                                                <input type="date" name="date" id="fordate" required="">
                                            </div>
                                          <!-- </div> -->
                                       </div>
                                       <div class=col-md-2>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                              <label class="form-group">To-Date*</label>
                                                <input type="date" name="date1" id="todate" required="">
                                            </div>
                                          <!-- </div> -->
                                       </div>



                                       <div class=col-md-2>
                                          <label></label>
                                          <button>Submit</button>
                                        </div>

                                       <!-- <div class=col-md-2>
                                        <label></label>
                                          <div>
                                          <a href="{{ url('admin/pdf') }}" type="button"
                                    class="btn btn-success export-file btn-sm"> PDF Export </a>
                                    </div>
                                        </div> -->

                                      </form>
                                    </div>

                            
                                    <div class="card-body">


                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Scheme</th>
                                                <th>Teacher</th>
                                                <th>Teacher Uid</th>
                                                <th>Batch name</th>
                                                <th>Batch hours</th>
                                                <th>Student name</th>
                                                <th>Intime</th>
                                                <th>Outtime</th>
                                                <th>Status</th>
                                               </tr>
                                            </thead>

                                             <tbody>
                                                @foreach ($getall as $getalls)

                                                <?php

                                                    $date1 = $getalls->intime;
                                                    $time =(date("H:i:s",strtotime($date1)));

                                                    $date2 = $getalls->outtime;
                                                    $time2 =(date("H:i:s",strtotime($date2)));

                                                    
                                                ?>
                                                <tr>
                                                    <td>{{$getalls->scheme_name}} </td>
                                                    <td>{{$getalls->teacher_name}} </td>
                                                    <td>{{$getalls->teacher_uid}} </td>
                                                    <td>{{$getalls->batch_name}} </td>
                                                    <td>{{$getalls->hours}} </td>
                                                    <td>{{$getalls->name}} </td>

                                                    <td> {{$time}} </td>
                                                     <td>
                                                    @if (!empty($date2) )
                                                    {{$time2}}
                                                    @else
                                                    -
                                                    @endif </td>
                                                    <td>{{$getalls->types}}</td>
                                                      </tr>
                                                   @endforeach
                                            </tbody>


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
     <script type="text/javascript" src="http://davidstutz.github.io/bootstrap-multiselect/dist/js/bootstrap-multiselect.js">
</script>


    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>



    <script>


    $('.frameworks1').multiselect({
    nonSelectedText: 'Select teacher',
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    buttonWidth: '100%'
    });
    
    $('.frameworks11').multiselect({
    nonSelectedText: 'Select batch',
    enableFiltering: true,
    enableCaseInsensitiveFiltering: true,
    buttonWidth: '100%'
    });



     $(document).on('change','#teacher',function(){
          var id =$( "#teacher" ).val();
          // alert(id)

          $.ajax({
                  url: "{{ url('admin/getteachers') }}",
                  method: 'post',
                  data: { "_token" : "{{csrf_token()}}",
                     "teacher": id,

                  },
                success: function(value){


                 var _options =""
                    $.each(value, function(i,values) {
                    _options +=('<option value="'+ values.id+'">'+ values.name +'</option>');
                    console.log(_options);

                    });
                    
                     $('#frameworks2').append(_options);

                 
                }


                  })


           })



       $(document).on('change','#frameworks2',function(){
          var trainerid =$( "#frameworks2" ).val();
          // alert(trainerid)

          $.ajax({
                  url: "{{ url('admin/gettrainer') }}",
                  method: 'post',
                  data: { "_token" : "{{csrf_token()}}",
                     "trainer": trainerid,

                  },
                success: function(value){

                 var _options =""
                    $.each(value, function(i,values) {
                    _options +=('<option value="'+ values.batch_id+'">'+ values.btachn +'</option>');
                    console.log(_options);

                    });
                    
                     $('#frameworks1').append(_options);

                 
                }


                  })


           })
      

    </script>


@endsection
