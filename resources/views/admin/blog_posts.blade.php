@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    #{{$blog_category->blog_name}}
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
                <a href="{{url('add_blog_posts/'.$blog_category->id)}}"><button type="button" class="btn btn-dark">Add
                        Blogs</button></a>
            </div>

            <div class="intro-y datatable-wrapper box p-5 mt-1">
                <table id="blogtypetbl" class="table table-report table-report--bordered display w-full">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">ID</th>

                            <th class="border-b-2 whitespace-no-wrap">Post Title</th>

                            <th class="border-b-2 whitespace-no-wrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($query as $que)
                        <tr>
                            <th scope="row">{{ $que->id }}</th>
                            <td> <a href="{{url('show_blog_post/'.$que->id)}}"><button type="button" class="btn btn-dark">{{
                                        $que->post_title }}</button></a></td>
                            <td><a href="{{url('edit_blog_posts/'.$que->id)}}"><button type="button"
                                        class="btn btn-dark editbtn">Edit</button></a>
                                <!-- Button trigger modal -->
                                <button type="button" value="{{$que->id}}"
                                    class="btn btn-danger deletebtn">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- //======================Delete categoury Modal================================= -->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Blog Post
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{url('blog_post_delete')}}" method="post">
                                @csrf
                                @method('DELETE')
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="delete_blog_post_id" id="deleting_id"></input>
                                        <p>Are you sure! want to Delete Blog Post?</p>
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
    </section>
</div>

</div>
<script>
    $(document).ready(function () {
        $('#blogtypetbl').DataTable();
    });
</script>

<script>
//=============== Script For delete Categoury=====================
$(document).on('click', '.deletebtn', function () {
    var cat_id = $(this).val();
    $('#deleteModal').modal('show');
    $('#deleting_id').val(cat_id);
});
</script>
@endsection