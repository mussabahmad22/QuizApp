@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="pcoded-inner-content">
                <div class="main-body">
                    <div class="page-wrapper">
                        <!-- <div class="page-header card">
                            <div class="card-block"> -->
                        <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                            <h2 class="display-5">
                                #USERS
                            </h2>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <td><a href="{{url('add_users')}}"><button type="button" class="btn btn-dark">+Add
                                                New</button></a></td>
                                </div>
                            </div>
                        </div>
                        <br>
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
                        <!-- <table class="table table-striped table-hover table-responsive"> -->
                        <div class="table table-striped table-hover table-responsive">
                            <table id="usertbl" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr.</th>
                                        <th scope="col">User Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <th scope="row">{{ $user->id }}</th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td><a href="{{url('edit_user/'.$user->id)}}"><button type="button"
                                                    class="btn btn-dark">Edit</button></a>
                                            <!-- Button trigger modal -->
                                            <button type="button" id="deletebtn" value="{{$user->id}}"
                                                class="btn btn-danger deletebtn">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete User
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form type="submit" action="{{url('delete_user')}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                            <div class="grid grid-cols-12 gap-4 row-gap-5">
                                                <input type="hidden" name="delete_user_id"
                                                    id="deleting_id"></input>
                                                <p>Are you sure! want to Delete User?</p>
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

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#usertbl').DataTable();
    });

    $(document).on('click', '.deletebtn', function () {
        var user_id = $(this).val();
        $('#deleteModal').modal('show');
        $('#deleting_id').val(user_id);
    });

    // $('#deletebtn').click(function () {
    //     swal({
    //         title: "Are you sure?",
    //         text: "Once deleted, you will not be able to recover this imaginary file!",
    //         icon: "warning",
    //         buttons: true,
    //         dangerMode: true,
    //     })
    //         .then((willDelete) => {
    //             if (willDelete) {
    //                 swal("Poof! Your imaginary file has been deleted!", {
    //                     icon: "success",
    //                 });
    //             } else {
    //                 swal("Your imaginary file is safe!");
    //             }
    //         });
    // });
</script>
@endsection