@extends('admin.layout.app')
<!-- Content Wrapper. Contains page content -->

@section('content')
<?php
// print_r(Auth::user()->id); die;
?>
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
                            Center List
                        </h3>
                        <!-- tools box -->
                        @if (Auth::user()->id==54506)
                        <div class="card-tools">
                            <button  class="btn btn-primary"  data-toggle="modal" data-target="#officeAddModal" style="float: right">Add Center</button>

                        </div>
                        @endif
                        <!-- /. tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body pad">
                        <div class="tab-content">

                            <!-- Tab panel -->
                            <div id="tab-pane1" class="tab-pane active">

                                <!-- Card Body -->
                                <div class="card-body">

                                    <!-- Card Title-->
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th>Distance</th>
                                            @if (Auth::user()->id==54506)
                                            <th>Action</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(@$offices as $office)
                                            <tr>
                                                <td>{{ @$office->name }}</td>
                                                <td>{{ @$office->location }}</td>
                                                <td>{{ @$office->latitude }}</td>
                                                <td>{{ @$office->longitude }}</td>
                                                <td>{{ @$office->distance }}</td>
                                                
                                                @if (Auth::user()->id==54506)
                                            
                                                <td>
                                                    <a class="js-edit-logo" data-toggle="modal" data-target="#officeEditModal" data-id="{{ @$office->id }}" data-name="{{ @$office->name }}"  data-location="{{ @$office->location }}" data-latitude="{{ @$office->latitude }}" data-longitude="{{ @$office->longitude }}" data-distance="{{ @$office->distance }}" style="cursor:pointer" title="edit state"><i class="fa fa-edit"></i></a>
                                                    <a class="delete-material" href="{{ route('delete.office',@$office->id) }}"  data-id="{{ @$office->id }}"  title="delete logo" onClick="return  confirm('Are you sure you want to delete ?')"><i class="fa fa-trash-alt"></i></a
                                                    ></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>

        <div id="officeAddModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Center Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <form action="{{ route('admin.add.office') }}" method="post" enctype="multipart/form-data">

                        @csrf
                        <input type="hidden" name="id" id="officeid">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="email-1">Name</label>
                                <input type="text" id="name" class="form-control" name="name" value="{{old('name')}}" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Location</label>
                                <input type="text" id="location" class="form-control" name="location" value="{{old('location')}}" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Latitude</label>
                                <input type="text" id="latitude" class="form-control" name="latitude" value="{{old('latitude')}}" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Longitude</label>
                                <input type="text" id="longitude" class="form-control" name="longitude" value="{{old('longitude')}}" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Distance</label>
                                <input type="text" id="distance" class="form-control" name="distance" value="{{old('distance')}}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary text-uppercase">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div id="officeEditModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Center Details</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                    </div>
                    <form action="{{ route('admin.update.office') }}" method="post" enctype="multipart/form-data">

                        @csrf
                        <input type="hidden" name="id" id="officeid">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="email-1">Name</label>
                                <input type="text" id="name" class="form-control" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Location</label>
                                <input type="text" id="location" class="form-control" name="location" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Latitude</label>
                                <input type="text" id="latitude" class="form-control" name="latitude" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Longitude</label>
                                <input type="text" id="longitude" class="form-control" name="longitude" required>
                            </div>

                            <div class="form-group">
                                <label for="email-1">Distance</label>
                                <input type="text" id="distance" class="form-control" name="distance" required>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary text-uppercase">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- ./row -->
    </section>
    <!-- /.content -->
</div>
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        $(".js-edit-logo").on('click',function (e) {
            var id =   $(this).attr('data-id');
            var name =   $(this).attr('data-name');
            var location =   $(this).attr('data-location');
            var latitude =   $(this).attr('data-latitude');
            var longitude =   $(this).attr('data-longitude');
            var distance =   $(this).attr('data-distance');
            $("#officeEditModal .modal-dialog #officeid").val(id);
            $("#officeEditModal .modal-dialog #name").val(name);
            $("#officeEditModal .modal-dialog #location").val(location);
            $("#officeEditModal .modal-dialog #latitude").val(latitude);
            $("#officeEditModal .modal-dialog #longitude").val(longitude);
            $("#officeEditModal .modal-dialog #distance").val(distance);
        });
    </script>
@endsection
