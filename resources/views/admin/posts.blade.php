<!-- @extends('admin.layouts.main')

@section('main-container')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    {{$type->type_name}}
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
                    Post</button>
            </div>
            <br>
            <div class="#">
                <table class="table table-bordered table-responsive" id="questiontbl">
                    <thead>
                        <tr>
                            <th scope="col">Post ID</th>
                            <th scope="col">Post Title</th>
                            <th scope="col">Post Description</th>
                            <th scope="col">Image</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($db_query as $que)
                        <tr>
                            <th scope="row">{{ $que->id }}</th>
                            <td>{{ $que->post_title }}</td>
                            <td>{{ $que->post_desc}}</td>
                            <td>{{ $que->img }}</td>
                            <td><button type="button" value="{{$que->id}}" class="btn btn-dark editbtn">Edit</button>
                                Button trigger modal -->
                                <!-- <button type="button" value="{{$que->id}}"
                                    class="btn btn-danger deletebtn">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready( function () {
        $('#questiontbl').DataTable();
    } );
</script>
@endsection -->