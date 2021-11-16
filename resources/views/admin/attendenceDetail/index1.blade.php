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


                                @if(Auth::user()->role_type==5 || Auth::user()->role_type==4 || Auth::user()->role_type==3 || Auth::user()->role_type==1)

                                        <form action="{{ url('admin/batchfilter1') }}" method="post"
                                          enctype="multipart/form-data">
                                           @csrf
                                     <div class='row'>
                                     <div class=col-md-3>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                            	<label class="form-group">Batch Name*</label>
                                                <select id="frameworks11" name="batch" >
                                                    <option value="">Select Batch</option>
                                                   @foreach($batch as $batchs)
                                                  <option value="{{$batchs ->batch_id}}">
                                                       {{$batchs -> batch_name}}
                                                     </option>
                                                      @endforeach
                                                </select>
                                            </div>
                                          <!-- </div> -->
                                       </div>

                                        <div class=col-md-3>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                            	<label class="form-group">From-Date*</label>
                                                <input type="date" name="date" id="fordate" required="">
                                            </div>
                                          <!-- </div> -->
                                       </div>
                                       <div class=col-md-3>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                            	<label class="form-group">To-Date*</label>
                                                <input type="date" name="date1" id="todate" required="">
                                            </div>
                                          <!-- </div> -->
                                       </div>



                                       <div class=col-md-1>
                                          <label></label>
                                          <button>Submit</button>
                                        </div>

                                       <div class=col-md-2>
                                    		<label></label>
                                       		<div>
                                          <a href="{{ url('admin/pdf') }}" type="button"
                            				class="btn btn-success export-file btn-sm"> PDF Export </a>
                            				</div>
                                        </div>

                                      </form>
                                    </div>

                                       <!--<button style="text-align: right; margin-top: 20px">Excel Sheet</button>-->

                              @endif

                                    <!-- Card Body -->
                                    <div class="card-body">


                                        <!-- Card Title-->

                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Batch name</th>
                                                <th>Intime</th>
                                                <th>Outtime</th>
                                                <th>Date</th>
                                                <th>Status</th>

                                               </tr>
                                            </thead>

                                             <tbody>
                                                @foreach ($users as $userss)

                                                <?php

                                                    $date1 = $userss->intime;
                                                    $time =(date("H:i:s",strtotime($date1)));

                                                    $date2 = $userss->outtime;
                                                    $time2 =(date("H:i:s",strtotime($date2)));

                                                     $date21 = $userss->intime;
                                                    $datenew =(date("Y-m-d",strtotime($date21)));
                                                ?>
                                                <tr>
                                                    <td>{{$userss->name}} </td>
                                                    <td>{{$userss->batch_name}} </td>
                                                    <td> {{$time}} </td>
                                                     <td>
                                                    @if (!empty($date2) )
                                                		{{$time2}}
                                                		@else
                                                		-
                                                		@endif </td>
                                                    <td>{{$datenew}} </td>
                                                     <td>{{$userss->userstatus}} </td>
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

    	$(document).on('change','#todate',function(){

    		var fromdate = $('#fordate').val();
    		var todate = $('#todate').val();

    		if(!fromdate){
    			alert("Please enter from Date");
    			$('#todate').val('');
    		}

    		 var getmonth = fromdate.split("-");
    		 console.log(getmonth);

    		 var a = getmonth[1];
			var b = "02";
			var c = parseInt(a, 10) + parseInt(b, 10);
			// if(c < 10){
			// 	alert("ffff")
			// 	var c = 0+c;
			// }
			// alert("Parsed result: " + c);


    		 // var aa = parseInt(getmonth[1]'+'2);
    		 // alert(c);
    		 var newda = getmonth[0]+'-'+'0'+c+'-'+getmonth[2]
			console.log(newda);

			if(todate > newda){

				alert("The data is huge please select less number of month");
				$('#todate').val('');

			}

    	})


        $(".js-edit-logo").on('click',function (e) {
            var id =   $(this).attr('data-id');
            var name =   $(this).attr('data-name');
            var batchname =   $(this).attr('data-batchname');
            var starttime =   $(this).attr('data-starttime');
            var endtime =   $(this).attr('data-endtime');
            var parent = $(this).attr('data-parent');
            var uid = $(this).attr('data-uid');
            var phone = $(this).attr('data-phone');

// alert(batchname);
// alert(endtime);
// alert(starttime);


   var tempDate = starttime.split(' ');
   var newDateArray = tempDate[0].split('-');
   var newDate =[];
   newDate.push(newDateArray[2]);
   newDate.push(newDateArray[1]);
   newDate.push(newDateArray[0]);
   newDate = newDate.join('-');
   newDate = newDate+' '+tempDate[1];
   // alert(newDate);

    $('#batchName').val(batchname);
    $('#starttime').val(newDate);
    $('#endtime').val(endtime);



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

     $('#frameworks11').multiselect({
        nonSelectedText: 'Select User',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        buttonWidth: '100%'
    });


        $(document).on('change','#teacher',function(){
          var id =$( "#teacher" ).val();
          //alert(id)

          $.ajax({
                  url: "{{ url('admin/teacher') }}",
                  method: 'post',
                  data: { "_token" : "{{csrf_token()}}",
                     "teacher": id,

                  },
                success: function(value){

                  $('#batch_list').empty();

                  var _options =""
                  $.each(value, function(i,values) {
                    // console.log(values);

                  _options +=('<option value="'+ values.batch_id+'">'+ values.batch_name +'</option>');
                  console.log(values);
                  });
                  $('#batch_list').append(_options);
  }


                  })


           })
           // var varAutoScroll = getElementById("cardScrollAuto");
           // function pageScroll() {
           //     varAutoScroll.scrollDown(0,1);
           //     scrolldelay = setTimeout(pageScroll,10);
           // }
           window.setInterval(function() {
             var elem = document.getElementById('cardScrollAuto');
             elem.scrollBy(0,50)
           }, 3000);


    </script>


@endsection
