@extends('layouts.admin-master')
@section('styles')
<link rel="stylesheet" href="{{URL::to('css/bootstrap.min.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        <section id="post-admin">
            <a href="{{ route('admin.blog.post.edit',['post_id'=>$post->id]) }}" class="btn">Edit Post</a>
            <a href="{{ route('admin.blog.post.delete',['post_id'=>$post->id]) }}" class="btn">Delete Post</a>
        </section>
        <section>
        <h1>{{ $post->title }}</h1>
        <!-- Author -->
        <p class="lead">
            by <a href="{{ route('blog.author.posts',['author'=>$post->author]) }}">{{ $post->author }}</a> | <span class="glyphicon glyphicon-time"></span> Posted on {{date("F d, Y", strtotime($post->created_at))}} at {{date("g:i A", strtotime($post->created_at))}}
        </p>

       @if(isset($post_tags_names)&& count($post_tags_names)>0)
                <hr>
                <p >

                    {{ implode(',',$post_tags_names)}}

                </p>
            @endif
            @if(isset($categories)&& count($categories)>0)
                <hr>
                <p >

                    {{ implode(',',$categories)}}

                </p>
            @endif
         <hr>

          <!-- Post Content class="lead"-->
            <p >{{ $post->body }}</p>
            @if(count($photos)>0)
                <hr>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    @php
                    $i=0;
                    @endphp
                    <ol class="carousel-indicators">
                        @foreach($photos as $photo)
                            <li data-target="#myCarousel" data-slide-to="{{$i++}}" class="{{$photo->id === $photos[0]->id?'active':'' }}"></li>

                        @endforeach
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        @foreach($photos as $photo)
                            <div class="item {{$photo->id === $photos[0]->id?'active':'' }}" style="text-align: center; margin:15px auto; padding: 30px;  width:600px; height: 400px;">
                                <img src="{{ asset($photo->name ) }}" alt="Los Angeles" style="width:550px; height: 350px;">
                                {{--<div class="carousel-caption" style="background-color: rgba(177, 183, 186, .6); ">--}}
                                    {{--<h3>Los Angeles</h3>--}}
                                    {{--<p>LA is always so much fun!</p>--}}
                                {{--</div>--}}
                            </div>
                        @endforeach

                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                {{--@foreach($photos as $photo)--}}
                    {{--<div style="text-align: center; margin:15px auto; padding: 30px; background-color: #bcbcbc; width:40%;">--}}
                        {{--<img class="img-thumbnail " style="background-color: #fff; margin: auto;width:80%;"  src="{{ asset($photo->name ) }}" alt="{{ $photo->id }}">--}}
                    {{--</div>--}}

                {{--@endforeach--}}
            @endif
            <hr>
        </section>


    </div>
@endsection