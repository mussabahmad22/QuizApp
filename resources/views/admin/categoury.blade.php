@extends('admin.layouts.main')

@section('main-container')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h2 class="display-5">
                ADD QUIZ CATEGORY:
            </h2>
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
            <!-- ==========================ADD Categoury Form ==================================== -->
            <form type="submit" action="{{route('addCategoury')}}" method="POST">
                @csrf
                <div class="mb-3">
                    <div class="mb-3">
                        <label class="form-label">Category Name*</label>
                        <input type="text" class="form-control" name="name" value="">
                        <span class="text-danger">
                            @error('name')
                            {{$message}}
                            @enderror
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark">Create</button>
            </form>
            <br>
            <!-- ==========================END ADD Categoury Form =============================== -->

            <!-- =================== Display Categouries ========================================= -->
            <div class="table table-striped table-hover table-responsive">
                <table id="categorytbl" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Sr.</th>
                            <th scope="col">Category Name*</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categoury as $cat)
                        <tr>
                            <th scope="row">{{ $cat->id }}</th>
                            <td>{{ $cat->name }}</td>
                            <td><button type="button" value="{{$cat->id}}" class="btn btn-dark editbtn">Edit</button>
                                <!-- Button trigger modal -->
                                <button type="button" value="{{$cat->id}}"
                                    class="btn btn-danger deletebtn">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- //======================Delete categoury Modal================================= -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Category
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form type="submit" action="{{route('categoury_delete')}}" method="post">
                        @csrf
                        @method('DELETE')
                        <div class="intro-y col-span-12 lg:col-span-8 p-5">
                            <div class="grid grid-cols-12 gap-4 row-gap-5">
                                <input type="hidden" name="delete_categoury_id" id="deleting_id"></input>
                                <p>Are you sure! want to Delete Category?</p>
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

    <!-- ======================== Update Categoury Modal==================================== -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabe3"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabe3">Update Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form type="submit" action="{{route('categoury_update')}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="intro-y col-span-12 lg:col-span-8 p-5">
                            <div class="grid grid-cols-12 gap-4 row-gap-5">
                                <input type="hidden" name="cat_id" id="cat_id"></input>
                                <div class="mb-3">
                                    <label class="form-label">Category Name*</label>
                                    <input type="text" class="form-control" name="cat_name" id="cat_name">
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
    <!-- ======================== End Update Categoury Modal==================================== -->
</div>

<script>
    $(document).ready(function () {

        //=============== Script For Edit Categoury=====================
        $(document).on('click', '.editbtn', function () {
            var cat_id = $(this).val();
            $('#editModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit_categoury/" + cat_id,
                success: function (response) {
                    console.log(response);
                    $('#cat_id').val(cat_id);
                    $('#cat_name').val(response.categoury.name);
                }
            });

        });

        //=============== Script For delete Categoury=====================
        $(document).on('click', '.deletebtn', function () {
            var cat_id = $(this).val();
            $('#deleteModal').modal('show');
            $('#deleting_id').val(cat_id);
        });
    });
</script>
<script>
    $(document).ready( function () {
        $('#categorytbl').DataTable();
    } );
</script>

@endsection