@extends('admin.layouts.main')

@section('main-container')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="display-5">
                    {{ $blog_posts->post_title }}
                </h2>
            </div>
            <br>
            <div>
                <?= $blog_posts->post_desc ?>
            </div>
        </div>
    </section>
</div>

</div>

@endsection