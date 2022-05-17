@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <br>
                <h2 class="display-5">
                    #{{$title}}
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
            <form type="submit" action="{{$url}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="intro-y col-span-12 lg:col-span-8 p-5">
                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                        <input type="hidden" name="id" value="{{$exe->id}}"></input>
                        <div class="mb-3">
                            <label class="form-label">Questions Title*</label>
                            <textarea class="summernote"
                                name="question_title">{{ isset($ques->question_title)?$ques->question_title:'' }}</textarea>
                            <span class="text-danger">
                                @error('question_title')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title Image :</label><br>
                            <input type="file" name="file_title" value="{{ isset($ques->path_title)?$ques->path_title:'' }}">
                        </div>
                        <div class="form-group">
                            <label><strong>Option 1*</strong></label>
                            <textarea class="summernote"
                                name="option1">{{ isset($ques->option_1)?$ques->option_1:'' }}</textarea>
                            <span class="text-danger">
                                @error('option1')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Statement 1*</strong></label>
                            <textarea class="summernote"
                                name="statement1">{{ isset($ques->statement_1)?$ques->statement_1:'' }}</textarea>
                            <span class="text-danger">
                                @error('statement1')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Option 2*</strong></label>
                            <textarea class="summernote"
                                name="option2">{{ isset($ques->option_2)?$ques->option_2:'' }}</textarea>
                            <span class="text-danger">
                                @error('option2')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Statement 2*</strong></label>
                            <textarea class="summernote"
                                name="statement2">{{ isset($ques->statement_2)?$ques->statement_2:'' }}</textarea>
                            <span class="text-danger">
                                @error('statement2')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Option 3*</strong></label>
                            <textarea class="summernote"
                                name="option3">{{ isset($ques->option_3)?$ques->option_3:'' }}</textarea>
                            <span class="text-danger">
                                @error('option3')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Statement 3*</strong></label>
                            <textarea class="summernote"
                                name="statement3">{{ isset($ques->statement_3)?$ques->statement_3:'' }}</textarea>
                            <span class="text-danger">
                                @error('statement3')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Option 4*</strong></label>
                            <textarea class="summernote"
                                name="option4">{{ isset($ques->option_4)?$ques->option_4:'' }}</textarea>
                            <span class="text-danger">
                                @error('option4')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Statement 4*</strong></label>
                            <textarea class="summernote"
                                name="statement4">{{ isset($ques->statement_4)?$ques->statement_4:'' }}</textarea>
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
                                <option value="1" @if(isset($ques->right_ans)) {{ $ques->right_ans == 1?'selected':'' }}
                                    @endif >Option 1</option>
                                <option value="2" @if(isset($ques->right_ans)) {{ $ques->right_ans == 2?'selected':'' }}
                                    @endif >Option 2</option>
                                <option value="3" @if(isset($ques->right_ans)) {{ $ques->right_ans == 3?'selected':'' }}
                                    @endif">Option 3</option>
                                <option value="4" @if(isset($ques->right_ans)) {{ $ques->right_ans == 4?'selected':'' }}
                                    @endif">Option 4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><strong>Right Answer Statement*</strong></label>
                            <textarea class="summernote"
                                name="right_answer_statement">{{ isset($ques->right_ans_statement)?$ques->right_ans_statement:'' }}</textarea>
                            <span class="text-danger">
                                @error('right_answer_statement')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Question Review*</strong></label>
                            <textarea class="summernote"
                                name="question_review">{{ isset($ques->question_review)?$ques->question_review:'' }}</textarea>
                            <span class="text-danger">
                                @error('question_review')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review Image :</label><br>
                            <input type="file" name="file_reveiw" value="{{ isset($ques->path_review)?$ques->path_review:'' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Youtube Link*</label>
                            <input type="text" class="form-control" name="link"
                                value="{{ isset($ques->yt_link)?$ques->yt_link:'' }}" placeholder="example@example.com">
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
            <button type="submit" class="btn btn-dark">{{$text}}</button>
        </div>
        </form>
    </div>
</div>
</div>

<script type="text/javascript">
    //============================== Script for Summernote=========================================
    $(document).ready(function () {
        $('.summernote').summernote({
            placeholder: 'Description Here!',
            tabsize: 2,
            height: 200
        });
    });
</script>

@endsection