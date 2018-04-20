@extends('layouts.admin-master')
@section('styles')
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ URL::to('css/form.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        @include('includes.info-box')
        <section id="post-admin">
            <a href="{{ route('admin.blog.create_post') }}" class="btn">New Post</a>
        </section>
        <section class="list">

                @if(count($posts) == 0)
                   No Posts
                @else
                    @foreach($posts as $post)

                            <article>
                                <div class="post-info">
                                    <h3>{{ $post->title }}</h3>
                                    <span class="info">{{ $post->author }} | {{date("F d, Y", strtotime($post->created_at))}} at {{date("g:i A", strtotime($post->created_at))}}</span>
                                </div>
                                <div class="edit">
                                    <nav>
                                        <ul>
                                            <li><a href="{{ route('admin.blog.post',['post_id'=>$post->id,'end'=>'admin']) }}">ViewPost</a></li>
                                            <li><a href="{{ route('admin.blog.post.edit',['post_id'=>$post->id]) }}">Edit</a></li>
                                            <li><a href="{{ route('admin.blog.post.delete',['post_id'=>$post->id]) }}" class="danger">Delete</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </article>

                    @endforeach
                @endif

        </section>
        @if( $posts->lastPage() > 1)
            <section class="pagination">
                @if($posts->currentPage() !== 1)
                    <a href="{{ $posts->previousPageUrl() }}"><i class="fa fa-caret-left"></i></a>
                @endif
                @if($posts->currentPage() !== $posts->lastPage())
                    <a href="{{ $posts->nextPageUrl() }}"><i class="fa fa-caret-right"></i></a>
                @endif
            </section>
        @endif
    </div>
@endsection