@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h2 class="display-5">
                Dashboard
            </h2>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-12 mt-4">
                        <div class="card  bg-dark mb-3">
                            <div class="card-header">Total Users</div>
                            <div class="card-body">
                                <span class="display-4">{{ $users }}</span>
                                <i class="nav-icon fas fa-users"></i>
                                <p class="card-text" id="users"> Manage Users <i class="fas fa-arrow-circle-right"></i></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 mt-4">
                        <div class="card  bg-danger mb-3">
                            <div class="card-header">Total Category</div>
                            <div class="card-body">
                                <span class="display-4">{{ $categoury }}</span>
                                <i class="ion ion-pie-graph"></i>
                                <p class="card-text" id="category">Manage Category  <i class="fas fa-arrow-circle-right"></i></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        $('#users').on('click', function(){
            window.location.href = '/users';
        });

        $('#category').on('click', function(){
            window.location.href = '/categoury';
        });

    });
</script>
@endsection