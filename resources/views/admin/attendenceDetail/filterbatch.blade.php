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
     </style>

    <div class="content-wrapper" style="min-height: 1244.06px;">
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                Attendence Batch Detail List
                            </h3>
                            <!-- tools box -->
                            
                          <!--   <div class="card-tools">
                                <button  class="btn btn-primary"  data-toggle="modal" data-target="#userAddModal" style="float: right">Add Atendence Detail</button>
                            </div> -->
                            
                            <!-- /. tools -->
                        </div>
                        <!-- /.card-header -->
                        

                                 
                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">
                                  <div class="card-body pad">
                                    <div class="tab-content">
                                        
                                         @if(Auth::user()->role_type==5 || Auth::user()->role_type==4 || Auth::user()->role_type==3 || Auth::user()->role_type==1)
                                
                                        <form action="{{ url('admin/batchfilter') }}" method="post" 
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
                                                       {{$batchs->batch_name}}
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
                                                <input type="date" name="date" required="">
                                            </div>
                                          <!-- </div> -->
                                       </div>
                                         
                                       <div class=col-md-3>

                                          <!-- <div class="col-md-4"> -->
                                            <div>
                                            	<label class="form-group">To-Date*</label>
                                                <input type="date" name="date1" required="">
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
                                                		@endif
                                                	</td>
                                                    <td>{{$datenew}} </td>
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

       
    </script>


@endsection
