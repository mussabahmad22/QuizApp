@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
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
            <form type="submit" action="{{$url}}" method="POST">
                @csrf
                <div class="mb-3">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            value="{{ isset($record->name)?$record->name:'' }}">
                        <span class="text-danger">
                            @error('name')
                            {{$message}}
                            @enderror
                        </span>
                    </div>
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1"
                        aria-describedby="emailHelp" value="{{ isset($record->email)?$record->email:'' }}">
                    <span class="text-danger">
                        @error('email')
                        {{$message}}
                        @enderror
                    </span>
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                        value="{{ isset($record->password)?$record->password:'' }}">
                    <span class="text-danger">
                        @error('password')
                        {{$message}}
                        @enderror
                    </span>
                </div>
                <button type="submit" id="submit" class="btn btn-dark">{{$text}}</button>
            </form>
        </div>
    </div>
</div>

<script>
    $('#submit').click(function () {
        swal({
            title: "Good job!",
            text: "You clicked the button!",
            icon: "success",
            button: "Aww yiss!",
        });
    });
</script>
@endsection