@extends('layouts.master')
@section('title')
    {{ $post->title }}
@endsection
@section('styles')
    <link href="{{URL::to('css/blog-post.css') }}" rel="stylesheet">

    @endsection
@section('content')
 <!-- Blog Post -->
    <!-- Title -->
    <h1>{{ $post->title }}</h1>
    <!-- Author -->
    <p class="lead">
        by <a href="{{ route('blog.author.posts',['author'=>$post->author]) }}">{{ $post->author }}</a>
    </p>

    <hr>
    <!-- Date/Time -->
    <p><span class="glyphicon glyphicon-time"></span> Posted on {{date("F d, Y", strtotime($post->created_at))}} at {{date("g:i A", strtotime($post->created_at))}}</p>
    <hr>
    <!-- Preview Image -->

    @if(count($photos)>0)
    <div style="text-align: center; margin:15px auto; padding: 30px; background-color: #bcbcbc; width:80%;">
        <img class="img-responsive " style="background-color: #fff; margin: auto;width:90%;"  src="{{ asset($photos[0]->name ) }}" alt="{{ $photos[0]->id }}">
    </div>
    <hr>
   @endif

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
        {{--<div style="text-align: center; margin:15px auto; padding: 30px; background-color: #bcbcbc; width:80%;">--}}
            {{--<img class="img-responsive " style="background-color: #fff; margin: auto;width:90%;"  src="{{ asset($photo->name ) }}" alt="{{ $photo->id }}">--}}
        {{--</div>--}}

    {{--@endforeach--}}
    @endif
    <hr>
    <!-- Blog Comments -->
    <!-- Comments Form -->
    <div class="well">
        <h4>Leave a Comment:</h4>
        <form role="form">
            <div class="form-group">
                <input class="form-control" type="text" name="cusername" id="cusername" placeholder="UserName">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="cname" id="cname"  rows="3" data-post_id="{{ $post->id }}"></textarea>
            </div>
            <button type="submit"  id="comment_btn" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <hr>
    <!-- Posted Comments -->
@if(count($comments)>0)
    @foreach($comments as $comment)
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="http://placehold.it/64x64" alt="">
            </a>
            <div class="media-body">
                <h4 class="media-heading">{{ $comment->username }}
                    {{--August 25, 2014 at 9:30 PM--}}
                    <small>{{date("F d, Y", strtotime($comment->created_at))}} at {{date("g:i A", strtotime($comment->created_at))}}</small>
                </h4>
                {{$comment->name}}
                <!-- Nested Comment -->
                @if(count($commentsOnComments[$comment->id])>0)
                    @foreach($commentsOnComments[$comment->id] as $commentsOnComment)
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="http://placehold.it/64x64" alt="">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading">{{ $commentsOnComment->username }}
                                    <small>{{date("F d, Y", strtotime($commentsOnComment->created_at))}} at {{date("g:i A", strtotime($commentsOnComment->created_at))}}</small>
                                </h4>
                                {{$commentsOnComment->name}}
                            </div>
                        </div>
                        @endforeach

                    @endif
                <a href="#" class="replay" style="margin-bottom: 10px;margin-top: 10px; ">Replay to Comment</a>
                <div class="comment-on-comment" style="margin-bottom: 10px;margin-top: 10px; ">
                    <div class="media">
                        <div class="well">
                            <h4>Replay</h4>
                            <form role="form">
                                <div class="form-group user-container">
                                    <input class="form-control ccusername" type="text" name="ccusername"  id="ccusername" placeholder="UserName">
                                </div>
                                <div class="form-group comment-container">
                                    <textarea class="form-control ccname" name="ccname" id="ccname"  rows="3" data-comment_id="{{ $comment->id }}"></textarea>
                                </div>
                                <button type="submit"  id="comment_on_comment_btn" class="btn btn-primary comment_on_comment_btn">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Nested Comment -->
            </div>
        </div>
        @endforeach



    @endif

@endsection
@section('scripts')
    <script type="text/javascript">
        var token = '{{ Session::token() }}';
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.comment-on-comment').hide();
            $('.replay').click(function (event) {
                event.preventDefault();
               // $('.comment-on-comment').toggle();
               $(this).siblings(".comment-on-comment").animate({
                    //opacity: 1,
                    height: "toggle"
                }, 1000, function() {
                    // Animation complete.
                });
            });
            $("#comment_btn").click(function(event){
                event.preventDefault();
                var username=$('#cusername').val();
                var name = $('#cname').val();
                var post_id =$('#cname').data('post_id');

                $.ajax({
                    url: baseUrl+'/blog/post/comment',
                    type: 'POST',
                    data: { name: name, username : username,post_id:post_id,_token:token} ,
//                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        alert(response.status);
                    },
                    error: function (response) {
                        alert(response.status);
                        alert("error");
                    }
                });
            });
            $(".comment_on_comment_btn").click(function(event){
                event.preventDefault();
                var username=$(this).siblings('.user-container').children('.ccusername').val();
                var name = $(this).siblings('.comment-container').children('.ccname').val();
                var comment_id =$(this).siblings('.comment-container').children('.ccname').data('comment_id');

                $.ajax({
                    url: baseUrl+'/post/comment/commentoncomment',
                    type: 'POST',
                    data: { name: name, username : username,comment_id:comment_id,_token:token} ,
//                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function (response) {
                        alert(response.status);
                    },
                    error: function (response) {
                        alert(response.status);
                        alert("error");
                    }
                });
            });

        });

    </script>
@endsection
