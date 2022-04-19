@extends('admin.layouts.main')

@section('main-container')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    #{{$cat->name}}
                </h2>
            </div>
            @if(session()->has('success'))
            <div class="col-sm-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            @if(session()->has('error'))
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session()->get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            @if ($errors->any())
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">Add
                    Exercise</button>
            </div>

            <!-- BEGIN: Datatable -->
            <div class="intro-y datatable-wrapper box p-5 mt-1">
                <table id="exercisetbl" class="table table-report table-report--bordered display w-full">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">Exercise ID</th>

                            <th class="border-b-2 whitespace-no-wrap">Exercise Name*</th>

                            <th class="border-b-2 whitespace-no-wrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($query as $que)
                        <tr>
                            <th scope="row">{{ $que->id }}</th>
                            <td> <a href="{{url('questions/'.$que->id)}}"><button type="button" class="btn btn-dark">{{
                                        $que->exercise_name }}</button></a></td>
                            <td><button type="button" value="{{$que->id}}" class="btn btn-dark editbtn">Edit</button>
                                <!-- Button trigger modal -->
                                <button type="button" value="{{$que->id}}"
                                    class="btn btn-danger deletebtn">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- //======================Delete exercise Modal================================= -->
            <!-- Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Exercise
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{url('exercise_delete')}}" method="post">
                                @csrf
                                @method('DELETE')
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="delete_exercise_id" id="deleting_id"></input>
                                        <p>Are you sure! want to Delete Exercise?</p>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ==============================ADD Exercise Modal============================================ -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Exercise*</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{url('add_exercise/'.$cat->id)}}" method="post">
                                @csrf
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="id" value="{{$cat->id}}"></input>
                                        <div class="mb-3">
                                            <label class="form-label">Exercise Name*</label>
                                            <input type="text" class="form-control" name="exe_name">
                                            <span class="text-danger">
                                                @error('exe_name')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label class="form-label">Time Duration :</label>
                                            <select class="form-select" name="time_duration"
                                                aria-label="Default select example">
                                                @error('time_duration')
                                                {{$message}}
                                                @enderror
                                                <option value="">Select Quiz Time</option>
                                                <option value="30">30 minutes</option>
                                                <option value="60">1 Hour</option>
                                                <option value="90">1.5 Hour</option>
                                                <option value="120">2 Hour</option>
                                                <option value="150">2.5 Hour</option>
                                                <option value="180">3 Hour</option>
                                                <option value="210">3.5 Hour</option>
                                                <option value="240">4 Hour</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Save changes</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- ======================== Update Exercise Modal==================================== -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabe3"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabe3">Update Exercise</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{url('exercise_update')}}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="query_id" id="query_id"></input>
                                        <div class="mb-3">
                                            <label class="form-label">Exercise Name*</label>
                                            <input type="text" class="form-control" name="exe_name" id="exe_name">
                                            <span class="text-danger">
                                                @error('exe_name')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label class="form-label">Time Duration :</label>
                                            <select class="form-select" name="time_duration" id="time_duration"
                                                aria-label="Default select example">
                                                <option selected>Select Quiz Time</option>
                                                <option value="30">30 minutes</option>
                                                <option value="60">1 Hour</option>
                                                <option value="90">1.5 Hour</option>
                                                <option value="120">2 Hour</option>
                                                <option value="150">2.5 Hour</option>
                                                <option value="180">3 Hour</option>
                                                <option value="210">3.5 Hour</option>
                                                <option value="240">4 Hour</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- ======================== End Update Exercise Modal==================================== -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        //===================Script For Edit Exercise ====================================
        $(document).on('click', '.editbtn', function () {
            var query_id = $(this).val();
            $('#editModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit_exercise/" + query_id,
                success: function (response) {
                    console.log(response);
                    $('#query_id').val(query_id);
                    $('#exe_name').val(response.exercise.exercise_name);
                    $('#time_duration').val(response.exercise.time_duration);
                }
            });

        });
        //===================Script For Delete Exercise ====================================
        $(document).on('click', '.deletebtn', function () {
            var query_id = $(this).val();
            $('#deleteModal').modal('show');
            $('#deleting_id').val(query_id);
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#exercisetbl').DataTable();
    });
</script>

@endsection