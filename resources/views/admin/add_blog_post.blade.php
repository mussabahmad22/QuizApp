@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <br>
                <h2 class="display-5">
                    {{$title}}
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
            <!-- @if(session()->has('error'))
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
            @endif -->
            <form type="submit" action="{{$url}}" method="post">
                @csrf
                <div class="intro-y col-span-12 lg:col-span-8 p-14">
                    <div class="grid grid-cols-12 gap-4 row-gap-5">
                        <input type="hidden" name="id" value="{{$cat->id}}"></input>
                        <div class="mb-3">
                            <label class="form-label">Post Title*</label>
                            <input type="text" class="form-control" name="post_title" value="{{ isset($post->post_title)?$post->post_title:'' }}">
                            <span class="text-danger">
                                @error('post_title')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <label><strong>Post Description*</strong></label>
                            <textarea class="summernote" name="desc">{{ isset($post->post_desc)?$post->post_desc:'' }}</textarea>
                            <span class="text-danger">
                                @error('desc')
                                {{$message}}
                                @enderror
                            </span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-dark">{{$text}}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    //============================== Script for Summernote=========================================
    $(document).ready(function () {
        $('.summernote').summernote({
            placeholder: 'Post Description Here!',
            tabsize: 2,
            height: 200
        });
    });
</script>

@endsection