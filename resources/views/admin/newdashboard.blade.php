@extends('admin.layout.app')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
<style media="screen">
.btnDivDash{margin-top: 10px;}
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  padding: 16px;
  text-align: center;
  background:linear-gradient(to top, #ffffff 0%, #b5deff 100%);
}

.chartist-tooltip {
  opacity: 0;
  position: absolute;
  margin: 20px 0 0 10px;
  background: rgba(0, 0, 0, 0.8);
  color: #FFF;
  padding: 5px 10px;
  border-radius: 4px;
}
.tableDivList{
  display: block;
  overflow: auto;
  max-height: 1250px;
}
.chartist-tooltip.tooltip-show {
  opacity: 1;
  z-index:100000;
}
  @media (min-width:768px) {
    .col-lg-15{
      max-width: 20% !important;
    }
    .btnDivDash{margin-top: 36px;}
  }


  .overlay{
        display: none;
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 999;
        background: rgba(255,255,255,0.8) url("/examples/images/loader.gif") center no-repeat;
    }
    body{
        text-align: center;
    }
    body.loading{
        overflow: hidden;
    }
    body.loading .overlay{
        display: block;
    }

    .chartscroll{
        min-width: 100%;
        overflow-x:scroll;
        margin-bottom:20px;
        scroll-snap-align: center;
    }
    .ct-horizontal{
       font-size:16px;
    }
    .ct-series-b .ct-bar{
      stroke: red !important;
    }

    .ct-series-a .ct-bar{
      stroke: green; !important;

    }

    .ct-series-b:hover{

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

    /* div#dual_x_div > div>div>div{
      left: -237px !important;
    } */
</style>
<div id="pageloader">
   <img src="http://cdnjs.cloudflare.com/ajax/libs/semantic-ui/0.16.1/images/loader-large.gif" alt="processing..." />
</div>

@section('content')

<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
              <h3 class="m-0 text-dark">Dashboard</h3></h3>
            </div>
            <div class="row w-100">
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Total Number Of Student</h6>
                  <h1>{{$stucount}}</h1>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Total Present Student</h6>
                  <h1>{{$todayPre}}</h1>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Total Absent Student</h6>
                  <h1>{{$todayAp}}</h1>
                </div>
              </div>
            </div>
            <form class="" action="{{url('admin/newdashboard')}}" method="get" id="myform">
            <input type="hidden" id="district" value="{{$allids['district']}}">
            <input type="hidden" id="center" value="{{$allids['centers']}}">
            <input type="hidden" id="batch" value="{{$allids['batches']}}">
            <input type="hidden" id="date" value="{{$allids['date']}}">


                @csrf
              <div class="row w-100">
                <div class="col-lg-15 col-sm-3 col-xs-6 mt-1">
                  <label for="">Select District:</label>
                  <select class="form-control" name="district" aria-label="Default select example" id="districts">
                    <option value="">Select District</option>
                    @foreach($districts as $district)
                      <option value="{{$district->id}}" <?php if($allids['district'] == $district->id) echo"selected"; ?>>{{$district->district_name}}</option>
                    @endforeach

                  </select>

                </div>
                <div class="col-lg-15 col-sm-3 col-xs-6 mt-1">
                  <label for="">Select Centers:</label>
                  <select class="form-control form-select" name= "centers" id="centers" aria-label="Default select example">
                      <option value="">Select Center</option>
                  </select>

                </div>
                <div class="col-lg-15 col-sm-3 col-xs-6 mt-1">
                  <label for="">Select Batches:</label>
                  <select class="form-control form-select" name="batches" id="batches" aria-label="Default select example">
                      <option value="">Select Batches</option>

                  </select>

                </div>

                <div class="col-lg-15 col-sm-3 col-xs-6 mt-1">
                  <label for="">Select Date:</label>
                  <input class="form-control" type="date" name="date" id="selectdate">

                </div>
                <div class="col-lg-15 col-sm-3 col-xs-6 btnDivDash">
                  <button  type="submit" class="btn btn-primary" id="searchsubmit">Search</button>

                </div>
              </div>
            </form>
            <div class="container-fluid ">
              <!-- <div class="ct-chart" style="height: 400px;"></div> -->
              <!-- <div class="ct-chart ct-perfect-fourth " style="height: 400px;"></div> -->
              <div class="container-fluid chartscroll" id="dual_x_div" style="height: 500px;"></div>
            </div>
            <div class="row w-100">
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Number Of OnBoarded Students</h6>
                  <h1>{{$countstudentbatch}}</h1>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Total Number Of Present/Absent</h6>
                  <h1>{{$totalp}}/{{$totala}}</h1>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Number Of Centers</h6>
                  <h1>{{$countcenters}}</h1>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="card">
                  <h6>Number Of Batches</h6>
                  <h1>{{$countbatches}}</h1>
                </div>
              </div>
            </div>

            <div class="row container-fluid">
              <div class="col-lg-6 col-sm-12  p-1 ">
                <table class="table bg-white tableDivList">
                  <h4>Center List</h4>
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">District</th>
                      <th scope="col">Address</th>
                      <th scope="col">VTP/Center No</th>
                      <th scope="col">Center Head</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i =1;?>
                    @foreach($getcenteruser as $getcenterusers)
                    <tr>
                      <th scope="row">{{$i}}</th>
                      <td>{{$getcenterusers->name}}</td>
                      <td>{{$getcenterusers->districtname}}</td>
                      <td>{{$getcenterusers->location}}</td>
                      <td>{{$getcenterusers->code}}</td>
                      <td>{{$getcenterusers->centerhead}}</td>
                    </tr>
                    <?php $i++;?>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="col-lg-6 col-sm-12 p-1 ">
                <table class="table bg-white tableDivList">
                  <h4>Batch List</h4>
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">District</th>
                      <th scope="col">Address</th>
                      <th scope="col">VTP/Center No</th>
                      <th scope="col">Center Head</th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i =1;?>
                    @foreach($batchDatas as $batchData)
                    <tr>
                      <th scope="row">{{$i}}</th>
                      <td>{{$batchData->batch_name}}</td>
                      <td>{{$batchData->districtname}}</td>
                      <td>{{$batchData->location}}</td>
                      <td>{{$batchData->code}}</td>
                      <td>{{$batchData->centerhead}}</td>
                      <td><button class="btn btn-primary btn-sm attandence" data-id="{{$batchData->id}}" data-batch_name="{{$batchData->batch_name}}">Attandance</button></td>
                    </tr>
                    <?php $i++;?>
                    @endforeach

                  </tbody>
                </table>
                <div >


                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                <button  style="float:right;" class="btn btn-primary ml-2" onclick="ExportToExcel('xlsx')">Export Attandance</button>
                </div>
  
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <table class="table table-striped custom-table m-b-0" id= "tbl_exporttable_to_xls">
                      <thead id="dateall">

                      </thead>
                      <tbody id="dataall">

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
        </div>
    </div>

</div>

<div class="modal fade" id="showattandence" tabindex="-1" aria-labelledby="modalAttendanceDash11" aria-hidden="true">
<div class="overlay"></div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAttendanceDash11">Attendance</h5>
        <button type="button" class="btn-close btn" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="attid" value="attid">
        <input type="hidden" id="batchname" value="batchname">
        <div class=" row">
          <div class="col-6">
            <div class="form-group">
              <label for="fromDate">From Date</label>
              <input type="date" class="form-control" id="date11" name="date1" placeholder="From Date">
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <label for="fromDate">To Date</label>
              <input type="date" class="form-control" id="date31" name="date30" placeholder="To date">
            </div>
          </div>
        </div>
        <button type="submit"class="btn btn-primary" name="button" data-toggle="modal" data-target="#modalAttendanceDash" id="submitshow">View Attendence</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
<script src="https://unpkg.com/chartist-plugin-tooltips@0.0.17"></script>
<script src="https://unpkg.com/chartist-plugin-pointlabels@0.0.6"></script>
<!-- <script src="https://cdnjs.com/libraries/xlsx"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script> -->
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>



<script type="text/javascript">

function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('tbl_exporttable_to_xls');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('AttandanceSheet.' + (type || 'xlsx')));
    }



</script>

@endsection



<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>


<script type="text/javascript">


     var ccc= @json($batchname);
     var presenuser= @json($presenuser);
     var absentuser= @json($absentuser);
     var g= @json($graph);
     console.log(ccc)
     console.log(presenuser)
     console.log(absentuser)

    
      google.charts.load('current', {'packages':['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {

       
        var length = ccc.length;
        console.log(length)
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'batch');
        data.addColumn('number', 'present');
        data.addColumn('number', 'absent');
       
          console.log(ccc.length)
        for(i = 0; i < ccc.length; i++){
            data.addRow([ccc[i], presenuser[i], absentuser[i]]);
         
        };

        var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2,
                       { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" }]);

        var options = {
          width:length*160,
          colors: ['#3cb371', '#FF0000'],
          // bar: 20,
          legend: { position: 'top', alignment: 'start' },
          
          chart: {
            title: 'Attendance',
            subtitle: 'present on the left, absent on the right'
          },
          // series: {
          //   0: { axis: 'present' }, 
          //   1: { axis: 'absent' } 
          // },
          // axes: {
          //   y: {
          //     distance: {label: 'parsecs'}, 
          //     brightness: {side: 'top', label: 'apparent magnitude'} 
          //   }
          // }
        };

      // var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
      var chart = new google.visualization.ColumnChart(document.getElementById("dual_x_div"));
      chart.draw(view, options);
    };
   
  
    </script>


<script type="text/javascript">


$(document).on('change','#districts',function(){

  var id =$( "#districts" ).val();
          $.ajax({
                    url: "{{ url('admin/districts') }}",
                    method: 'post',
                    data: { "_token" : "{{csrf_token()}}",
                       "districts": id,
                       },
                    success: function(value){
                        $('#centers').empty();
                        $('#batches').empty();
                        $('#batches').val('');
                        $('#selectdate').val('');
                        var _options ="<option value=''>Select Center</option>"
                        $.each(value, function(i,values) {
                        _options +=('<option value="'+ values.id+'">'+ values.name +'</option>');
                        console.log(values);
                        });
                        $('#centers').append(_options);
                    }
                  })
});

$(document).on('change','#centers',function(){

  var id =$( "#centers" ).val();
          $.ajax({
                    url: "{{ url('admin/centers') }}",
                    method: 'post',
                    data: { "_token" : "{{csrf_token()}}",
                       "centers": id,
                       },
                    success: function(value){
                        $('#batches').empty();
                        var _options ="<option value=''>Select Batch</option>"
                        $.each(value, function(i,values) {
                        _options +=('<option value="'+ values.id+'">'+ values.batch_name +'</option>');
                        console.log(values);
                        });
                        $('#batches').append(_options);
                    }
                  })
});



$( document ).ready(function() {
  var id =$( "#districts" ).val();
    var cen = $("#center").val();
    var batch = $("#batch").val();
    var date = $("#date").val();
    
    $('#selectdate').val(date);
    
      $.ajax({
                url: "{{ url('admin/districts') }}",
                method: 'post',
                data: { "_token" : "{{csrf_token()}}",
                    "districts": id,
                    },
                  success: function(value){
                    $('#centers').empty();
                    var _options ="<option value=''>Select Center</option>"
                    $.each(value, function(i,values) {
                      if(values.id == cen){
                      _options +=('<option value="'+ values.id+'"  selected>'+ values.name +'</option>');
                      }else{
                      _options +=('<option value="'+ values.id+'"  >'+ values.name +'</option>');

                      }
                    console.log(values);
                    });
                    $('#centers').append(_options);
                  }
      })


      $.ajax({
                    url: "{{ url('admin/centers') }}",
                    method: 'post',
                    data: { "_token" : "{{csrf_token()}}",
                       "centers": cen,
                       },
                    success: function(value){
                        $('#batches').empty();
                        var _options ="<option value=''>Select Batch</option>"
                        $.each(value, function(i,values) {

                          if(values.id == batch){
                            _options +=('<option value="'+ values.id+'" selected>'+ values.batch_name +'</option>');
                          }else{
                            _options +=('<option value="'+ values.id+'">'+ values.batch_name +'</option>');

                          }
                        console.log(values);
                        });
                        $('#batches').append(_options);
                    }

      });


})






$(document).on('click', '.attandence', function(){

  var id = $(this).attr("data-id");
  var batch_name = $(this).attr("data-batch_name");
  $("#attid").val(id)
  $("#batchname").val(batch_name)

  if(id){
   $('#showattandence').modal('show');
  }
})

$(document).on('click', '#submitshow' , function(){
  $("body").addClass("loading");

  var batchid = $('#attid').val();
  var date11 = $('#date11').val();
  var date31 = $('#date31').val();
  var batchname = $('#batchname').val();

      $.ajax({
        url: "{{ url('admin/getattand') }}",
        method: 'post',
        data: { "_token" : "{{csrf_token()}}",
            "batchid": batchid,
            "date11": date11,
            "date31": date31,
            "batchname":batchname,
            },
        success: function(value){
           console.log(value)
          if(value){
            $('#showattandence').modal('hide');
            $("body").removeClass("loading");
            var tbl = '';
                       tbl += '<tr>';
                       tbl += '<th>Student Name</th>';
                       tbl += '<th>Student UID</th>';
                       tbl += '<th>Batch Name</th>';
                       tbl += '<th>Total Batch Sessions</th>';
                       tbl += '<th>Number Of Sessions Attended</th>';
                       tbl += '<th>% Count Of Attendance</th>';

                      $.each(value.dates , function (index, datess){
                       tbl += '<th>'+datess+'</th>';
                      })
                       tbl +='</tr>';
            $(document).find('#dateall').html(tbl);
            var tbl1 = '';
                      $.each(value.data , function (index, datas){
                        $allatt = datas.countdata;
                        tbl1 += '<tr>';
                        tbl1 += '<td>'+datas.name+'</td>';
                        tbl1 += '<td>'+datas.uid+'</td>';
                        tbl1 += '<td>'+datas.batchname+'</td>';
                        tbl1 += '<td> - </td>';
                        tbl1 += '<td> - </td>';
                        tbl1 += '<td> - </td>';

                        $.each(value.dates , function (index, datess){
                                if($allatt > 0){
                              if(jQuery.inArray(datess, datas.attandance) != -1) {
                                    // tbl1 += '<td><i class="fas fa-check text-success"></i></td>';
                                    tbl1 += '<td style="color:green">P</td>';
                                  } else {
                                    // tbl1 += '<td><i class="fas fa-times text-danger"></i></td>';
                                    tbl1 += '<td style="color:red">A</td>';
                                  }
                                }else{
                                  // tbl1 += '<td><i class="fas fa-times text-danger"></i></td>';
                                  tbl1 += '<td style="color:red">A</td>';
                                }
                        })
                        tbl1 +='</tr>';
                      })
              $(document).find('#dataall').html(tbl1);
          }
        }
      })
})


$(document).ready(function(){
  $("#myform").on("submit", function(){
    $("#pageloader").fadeIn();
  });//submit
});

</script>
