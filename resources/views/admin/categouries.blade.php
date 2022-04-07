@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    #CATEGORIES
                </h2>
            </div>
            <br>
            <div class="row">
                @foreach($categouries as $cat)
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="text-white">{{$cat->name}}</h3>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{url('/exercise/'.$cat->id)}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

@endsection