@extends('admin.layouts.main')

@section('main-container')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    #{{$exe->exercise_name}}
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
                <a href="{{route('show_add_question',['id' => $exe->id])}}"><button type="button" class="btn btn-dark">Add
                        Question</button></a>
            </div>
            <br>
         
            <div class="#">
                <table class="table table-bordered table-responsive" id="questiontbl">
                    <thead>
                        <tr>
                            <th scope="col">Questions ID</th>
                            <th scope="col">Question Title*</th>
                            <th scope="col">Option 1</th>
                            <th scope="col">Option 2</th>
                            <th scope="col">Option 3</th>
                            <th scope="col">Option 4</th>
                            <th scope="col">Correct Answer</th>
                            <th scope="col">Correct Statement</th>
                            <th scope="col">Question Review</th>
                            <th scope="col">Youtube Link</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($db_query as $que)
                        <tr>
                            <th scope="row">{{ $que->id }}</th>
                            <td> <?= $que->question_title ?></td>
                            <td>
                                <?= $que->option_1?>
                            </td>
                            <td>
                                <?=$que->option_2 ?>
                            </td>
                            <td>
                                <?= $que->option_3 ?>
                            </td>
                            <td>
                                <?= $que->option_4 ?>
                            </td>
                            <td>{{ $que->right_ans }}</td>
                            <td>
                                <?= $que->right_ans_statement ?>
                            </td>
                            <td width="50">
                                <?= $que->question_review ?>
                            </td>
                            <td>{{ $que->yt_link }}</td>
                            <td>
                                <!-- <button type="button" value="{{$que->id}}" class="btn btn-dark editbtn">Edit</button> -->
                                <a href="{{route('edit_question_page',['id' => $que->id])}}"><button type="button"
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
            <!-- //======================Delete Question Modal================================= -->
            <!-- Modal -->
            <div class="modal fade " id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Question
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{route('question_delete')}}" method="post">
                                @csrf
                                @method('DELETE')
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="delete_question_id" id="deleting_id"></input>
                                        <p>Are you sure! want to Delete Question?</p>
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

<script>
  
    //==========================Script For Delete Question======================================
    $(document).on('click', '.deletebtn', function () {
        var query_id = $(this).val();
        $('#deleteModal').modal('show');
        $('#deleting_id').val(query_id);
    });

</script>

<script>
    $(document).ready(function () {
        $('#questiontbl').DataTable();
    });
</script>
@endsection