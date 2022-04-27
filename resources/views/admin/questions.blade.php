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
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">Add
                    Question</button>
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
                            <td>{{ $que->question_title }}</td>
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
                            <td>
                                <?= $que->question_review ?>
                            </td>
                            <td>{{ $que->yt_link }}</td>
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

            <!-- ================ ADD Question Modal============================ -->
            <div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" style="width:100%;" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Questions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{route('add_questions',['id' => $exe->id])}}" method="post">
                                @csrf
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="id" value="{{$exe->id}}"></input>
                                        <div class="mb-3">
                                            <label class="form-label">Questions Title*</label>
                                            <input type="text" class="form-control" name="question_title">
                                            <span class="text-danger">
                                                @error('question_title')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 1*</strong></label>
                                            <textarea class="summernote" name="option1"></textarea>
                                            <span class="text-danger">
                                                @error('option1')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 1*</strong></label>
                                            <textarea class="summernote" name="statement1"></textarea>
                                            <span class="text-danger">
                                                @error('statement1')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 2*</strong></label>
                                            <textarea class="summernote" name="option2"></textarea>
                                            <span class="text-danger">
                                                @error('option2')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 2*</strong></label>
                                            <textarea class="summernote" name="statement2"></textarea>
                                            <span class="text-danger">
                                                @error('statement2')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 3*</strong></label>
                                            <textarea class="summernote" name="option3"></textarea>
                                            <span class="text-danger">
                                                @error('option3')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 3*</strong></label>
                                            <textarea class="summernote" name="statement3"></textarea>
                                            <span class="text-danger">
                                                @error('statement3')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 4*</strong></label>
                                            <textarea class="summernote" name="option4"></textarea>
                                            <span class="text-danger">
                                                @error('option4')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 4*</strong></label>
                                            <textarea class="summernote" name="statement4"></textarea>
                                            <span class="text-danger">
                                                @error('statement4')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label class="form-label">Right Option*</label>
                                            <select class="form-control form-select-lg mb-3" name="options"
                                                aria-label=".form-select-lg example">
                                                <option value="">Select Right Option</option>
                                                <option value="1">Option 1</option>
                                                <option value="2">Option 2</option>
                                                <option value="3">Option 3</option>
                                                <option value="4">Option 4</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Right Answer Statement*</strong></label>
                                            <textarea class="summernote" name="right_answer_statement"></textarea>
                                            <span class="text-danger">
                                                @error('right_answer_statement')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Question Review*</strong></label>
                                            <textarea class="summernote" name="question_review"></textarea>
                                            <span class="text-danger">
                                                @error('question_review')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Youtube Link*</label>
                                            <input type="text" class="form-control" name="link"
                                                placeholder="example@example.com">
                                                <span class="text-danger">
                                                    @error('link')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- ======================== END ADD Question Modal==================================== -->

            <!-- ======================== Update Question Modal==================================== -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabe3"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" style="width:100%;" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabe3">Update Question</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form type="submit" action="{{route('question_update')}}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                                        <input type="hidden" name="query_id" id="query_id"></input>
                                        <div class="mb-3">
                                            <label class="form-label">Questions Title*</label>
                                            <input type="text" class="form-control" name="question_title"
                                                id="question_title">
                                                <span class="text-danger">
                                                    @error('question_title')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 1*</strong></label>
                                            <textarea class="summernote" name="option1" id="option1"></textarea>
                                            <span class="text-danger">
                                                @error('option1')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 1*</strong></label>
                                            <textarea class="summernote" name="statement1" id="statement1"></textarea>
                                            <span class="text-danger">
                                                @error('statement1')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 2*</strong></label>
                                            <textarea class="summernote" name="option2" id="option2"></textarea>
                                            <span class="text-danger">
                                                @error('option2')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 2*</strong></label>
                                            <textarea class="summernote" name="statement2" id="statement2"></textarea>
                                            <span class="text-danger">
                                                @error('statement2')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 3*</strong></label>
                                            <textarea class="summernote" name="option3" id="option3"></textarea>
                                            <span class="text-danger">
                                                @error('option3')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 3*</strong></label>
                                            <textarea class="summernote" name="statement3" id="statement3"></textarea>
                                            <span class="text-danger">
                                                @error('statement3')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Option 4*</strong></label>
                                            <textarea class="summernote" name="option4" id="option4"></textarea>
                                            <span class="text-danger">
                                                @error('option4')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Statement 4*</strong></label>
                                            <textarea class="summernote" name="statement4" id="statement4"></textarea>
                                            <span class="text-danger">
                                                @error('statement4')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label class="form-label">Right Option*</label>
                                            <select class="form-control form-select-lg mb-3" name="options"
                                            id="options" aria-label=".form-select-lg example">
                                                <option value="">Select Right Option</option>
                                                <option value="1">Option 1</option>
                                                <option value="2">Option 2</option>
                                                <option value="3">Option 3</option>
                                                <option value="4">Option 4</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Right Answer Statement*</strong></label>
                                            <textarea class="summernote" name="right_answer_statement" id="right_answer_statement"></textarea>
                                            <span class="text-danger">
                                                @error('right_answer_statement')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Question Review*</strong></label>
                                            <textarea class="summernote"  id="question_review" name="question_review"></textarea>
                                            <span class="text-danger">
                                                @error('question_review')
                                                {{$message}}
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Youtube Link*</label>
                                            <input type="text" class="form-control" name="link" id="link">
                                            <span class="text-danger">
                                                @error('link')
                                                {{$message}}
                                                @enderror
                                            </span>
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
            <!-- ======================== End Update Question Modal==================================== -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        //========================Script For Edit Question======================================
        $(document).on('click', '.editbtn', function () {
            var query_id = $(this).val();
            $('#editModal').modal('show');

            $.ajax({
                type: "GET",
                url: "/edit_question/" + query_id,
                success: function (response) {
                    console.log(response);
                    $('#query_id').val(query_id);
                    $('#question_title').val(response.question.question_title);
                    $('#options').val(response.question.right_ans);
                    $('#link').val(response.question.yt_link);
                    $('#option1').summernote('code', response.question.option_1);
                    $('#statement1').summernote('code', response.question.statement_1);
                    $('#option2').summernote('code', response.question.option_2);
                    $('#statement2').summernote('code', response.question.statement_2);
                    $('#option3').summernote('code', response.question.option_3);
                    $('#statement3').summernote('code', response.question.statement_3);
                    $('#option4').summernote('code', response.question.option_4);
                    $('#statement4').summernote('code', response.question.statement_4);
                    $('#right_answer_statement').summernote('code', response.question.right_ans_statement);
                    $('#question_review').summernote('code', response.question.question_review);

                }
            });

        });
    });
    //==========================Script For Delete Question======================================
    $(document).on('click', '.deletebtn', function () {
        var query_id = $(this).val();
        $('#deleteModal').modal('show');
        $('#deleting_id').val(query_id);
    });
</script>

<script type="text/javascript">
    //============================== Script for Summernote=========================================
    $(document).ready(function () {
       
        $('.summernote').summernote({
            tabsize: 2,
            height: 100
        });

    });
</script>
<script>
    $(document).ready(function () {
        $('#questiontbl').DataTable();
    });
</script>
@endsection