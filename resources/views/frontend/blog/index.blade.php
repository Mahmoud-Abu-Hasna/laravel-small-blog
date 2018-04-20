@extends('layouts.master')
@section('title')
    Blog Index
    @endsection
@section('styles')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    @endsection
@section('content')
@if(isset($category))
    <h1 class="page-header">
        {{ucfirst($category->name)}}
    </h1>
    @endif
@if(isset($tag))
    <h1 class="page-header">
        {{ucfirst($tag->name)}}
    </h1>
    @endif
@if(isset($author))
    <h1 class="page-header">
        {{ucfirst($author)}}
    </h1>
    @endif

    @include('includes.info-box')
@if(count($posts)==0)
<h2>
    No Posts
</h2>
@else
    @foreach($posts as $post)


        <!-- First Blog Post -->
        <h2>
            <a href="{{ route('blog.single',['post_id'=>$post->id,'end'=>'frontend']) }}">{{ $post->title }}</a>
        </h2>
        <p class="lead">
            by <a href="{{ route('blog.author.posts',['author'=>$post->author]) }}">{{ $post->author }}</a>
        </p>
        <p><span class="glyphicon glyphicon-time"></span> Posted on  {{date("F d, Y", strtotime($post->created_at))}} at {{date("g:i A", strtotime($post->created_at))}}</p>
        <hr>
    @if(count($photos[$post->id])>0)
        <div style="text-align: center; margin:15px auto; padding: 30px; background-color: #bcbcbc; width:80%;">
            <img class="img-thumbnail " style="background-color: #fff; margin: auto; width:50%; height: 50%;"  src="{{ asset($photos[$post->id][0]->name ) }}" alt="{{ $photos[$post->id][0]->id }}">
        </div>
        <hr>
        @endif

        <p>{{ $post->body }}</p>
        <a class="btn btn-primary" href="{{ route('blog.single',['post_id'=>$post->id,'end'=>'frontend']) }}">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

        <hr>

   @endforeach

    @if( $posts->lastPage() > 1)
           <!-- Pager -->
    <ul class="pager">
        @if($posts->currentPage() !== 1)
            <li class="previous">
                <a href="{{ $posts->previousPageUrl() }}">&larr; Older</a>
            </li>

        @endif

            @if($posts->currentPage() !== $posts->lastPage())
                <li class="next">
                    <a href="{{ $posts->nextPageUrl() }}">Newer &rarr;</a>
                </li>

            @endif

    </ul>

@endif
@endif
    @endsection