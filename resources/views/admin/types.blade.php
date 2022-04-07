<!-- @extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    {{$blog->blog_name}}:
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
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">Add Blog
                    Type</button>
            </div> -->

            <!-- BEGIN: Datatable -->
            <!-- <div class="intro-y datatable-wrapper box p-5 mt-1">
                <table id="blogtypetbl" class="table table-report table-report--bordered display w-full">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">ID</th>

                            <th class="border-b-2 whitespace-no-wrap">Type Name</th>

                            <th class="border-b-2 whitespace-no-wrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($query as $que)
                        <tr>
                            <th scope="row">{{ $que->id }}</th>
                            <td> <a href="{{url('posts/'.$que->id)}}"><button type="button" class="btn btn-dark">{{
                                        $que->type_name }}</button></a></td>
                            <td><button type="button" value="{{$que->id}}" class="btn btn-dark editbtn">Edit</button> -->
                                <!-- Button trigger modal -->
                                <!-- <button type="button" value="{{$que->id}}"
                                    class="btn btn-danger deletebtn">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> -->

        <!-- ==============================ADD blog type Modal============================================ -->
        <!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Exercise</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form type="submit" action="{{url('add_type/'.$blog->id)}}" method="post">
                            @csrf
                            <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                <div class="grid grid-cols-12 gap-4 row-gap-5">
                                    <input type="hidden" name="id" value="{{$blog->id}}"></input>
                                    <div class="mb-3">
                                        <label class="form-label">Type Name</label>
                                        <input type="text" class="form-control" name="name">
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
        </div> -->
        <!-- ======================== Update Blog type Modal==================================== -->
        <!-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabe3"
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
                        <form type="submit" action="{{url('type_update')}}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                <div class="grid grid-cols-12 gap-4 row-gap-5">
                                    <input type="hidden" name="type_id" id="query_id"></input>
                                    <div class="mb-3">
                                        <label class="form-label">Type Name</label>
                                        <input type="text" class="form-control" name="name" id="name">
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
        </div> -->
        <!-- ======================== End Update Blog type Modal==================================== -->

        <!-- //======================Delete exercise Modal================================= -->
        <!-- Modal -->
        <!-- <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <form type="submit" action="{{url('delete_type')}}" method="post">
                            @csrf
                            @method('DELETE')
                            <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                <div class="grid grid-cols-12 gap-4 row-gap-5">
                                    <input type="hidden" name="delete_type_id" id="deleting_id"></input>
                                    <p>Are you sure! want to Delete Blog Type?</p>
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
    </section>
</div>

<script>

    $(document).ready(function () { -->

        <!-- ===================Script For Edit Blog type ==================================== -->
        <!-- $(document).on('click', '.editbtn', function () {
            var query_id = $(this).val();
            $('#editModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit_type/" + query_id,
                success: function (response) {
                    console.log(response);
                    $('#query_id').val(query_id);
                    $('#name').val(response.type.type_name);
                }
            });

        }); -->

        <!-- //===================Script For Delete Blog Type ==================================== -->
        <!-- $(document).on('click', '.deletebtn', function () {
            var query_id = $(this).val();
            $('#deleteModal').modal('show');
            $('#deleting_id').val(query_id);
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#blogtypetbl').DataTable();
    });
</script>
@endsection -->