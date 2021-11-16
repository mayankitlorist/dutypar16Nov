@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->
<div id="pageloader">
   <img src="http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="processing..." />
</div>
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

        #pageloader
        {
        background: rgba( 255, 255, 255, 0.8 );
        display: none;
        height: 100%;
        position: fixed;
        width: 100%;
        z-index: 9999;
        }

        #pageloader img
        {
        left: 50%;
        margin-left: -32px;
        margin-top: -32px;
        position: absolute;
        top: 50%;
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
                        </div>

                                <!-- Tab panel -->
                                <div id="tab-pane1" class="tab-pane active">
                                  <div class="card-body pad">
                                    <div class="tab-content">
                                    <div class='row'>
                                     <div class=col-md-4>
                                            <label class="form-group">Batch Name*</label>
                                            <select class="form-control" name="batch" id="batchid">
                                                <option value="">Select Batch</option>
                                                @foreach($batches as $batche)
                                                <option value="{{$batche->batch_id}}">{{$batche->batch_name}}</option>
                                                @endforeach
                                            </select>
                                     </div>
                                    <div class=col-md-2>
                                        <button class="btn btn-primary batchsubmit" style="margin-top:40px">Submit</button>
                                    </div>  
                                </div>

                                    <div class="card-body">


                                        <!-- Card Title-->

                                        <table id="example2" class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Batch Name</th>
                                                <th>Student name</th>
                                                <th>Total Batch Sessions</th>
                                                <th>Number Of Sessions Attended</th>
                                                <th>% Count Of Attendance</th>
                                               </tr>
                                            </thead>

                                             <tbody class="addbatch">
                                                
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

        $(document).on('click', '.batchsubmit', function(){
           $("#pageloader").fadeIn();
           var batchid = $('#batchid').val();
           if(batchid){
                $.ajax({
                        url: "{{ url('admin/batchstudent') }}",
                        method: 'post',
                        data: { "_token" : "{{csrf_token()}}",
                            "batchid": batchid,
                        },
                        success: function(value){
                        console.log(value)
                        if(value){
                            $("#pageloader").fadeOut();
                            $('.addbatch').empty();
                            $.each(value, function(i,values) {
                                var peratt = (100 * values.attandancecount) / values.total_sessions;
                                var batchdata = '<tr>'+
                                                    '<td> '+values.batch_name+' </td>'+
                                                    '<td> '+values.name+' </td>'+
                                                    '<td> '+values.total_sessions+' </td>'+
                                                    '<td> '+values.attandancecount+' </td>'+
                                                    '<td> '+peratt+'%</td>'+
                                                '</tr>'
                                $('.addbatch').append(batchdata);
                            });
                        }
                        }
                });
           }
        })
 
    </script>


@endsection
