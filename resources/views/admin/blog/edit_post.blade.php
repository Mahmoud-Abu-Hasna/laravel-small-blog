@extends('layouts.admin-master')
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/modal.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::to('css/form.css') }}" type="text/css">
@endsection
@section('content')
    <div class="container">
        @include('includes.info-box')
        <form action="{{ route('admin.blog.post.update') }}" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" {{ $errors->has('title')?'class=has-error':'' }} value="{{ Request::old('title') ? Request::old('title'): isset($post) ? $post->title : ''}}" />
            </div>

            <div class="input-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" {{ $errors->has('author')?'class=has-error':'' }} value="{{ Request::old('author') ? Request::old('author'): isset($post) ? $post->author : '' }}" />
            </div>

            <div class="input-group">
                <label for="category_select">Add Categories</label>
                <select name="category_select" id="category_select">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach

                </select>
                <button type="button" class="btn">Add Category</button>
                <div class="added-categories">
                    <ul>
                        @foreach($post_categories as $post_category)
                            <li><a href="#" data-id="{{$post_category->id}}">{{$post_category->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="categories" id="categories" value="{{ implode(',',$post_categories_ids) }}"/>
            </div>
            <div class="input-group">
                <label for="body">Body</label>
                <textarea name="body" id="body" rows="12" {{ $errors->has('body')?'class=has-error':'' }}>{{ Request::old('body') ? Request::old('body'): isset($post) ? $post->body : '' }}</textarea>
            </div>
            <div class="input-group">
                <label for="tags">Tags(seperated by ,)</label>
                <select name="tag_select" id="tag_select">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach

                </select>
                <button type="button" class="btn" id="tag_button">Add Tag</button>
                <textarea name="tags" style="margin-top: 15px;" id="tags" rows="1" {{ $errors->has('tags')?'class=has-error':'' }}>{{ Request::old('tags') ? Request::old('tags'): isset($post_tags_names)&& count($post_tags_names)>0 ? implode(',',$post_tags_names) : '' }}</textarea>
            </div>
            <div class="input-group">
                <label for="postImg[]">Image</label>
                <input type="file"  name="postImg[]" id="postImg"  multiple>
                <div class="added-photos">
                <ul  style="list-style: none;">
                @foreach($photos as $photo)
                    <li class="img-link" style="margin: 10px; display: inline-block;   padding: 5px; background-color: #bcbcbc; ">
                        <div class="img-choices" style="padding:1px; margin-bottom: 2px;">
                            <a class="edit-link"  href="#" data-id="{{$photo->id}}" data-post_id="{{isset($post) ? $post->id : ''}}"><img style="vertical-align: middle;" src="{{ asset('image/cross.png' ) }}" alt=""></a>
                            <a class="edit-link" href="#"  data-src="{{ asset($photo->name ) }}"><img style="vertical-align: middle;" src="{{ asset('image/expand.png' ) }}" alt=""></a>
                        </div>
                            <img class="img-thumbnail img-content" style=" text-align: center; background-color: #fff; margin: auto; width:90px; height: 90px;"  src="{{ asset($photo->name ) }}" alt="{{ $photo->id }}">



                    </li>
                @endforeach
                </ul>
                </div>
            </div>
            <button type="submit" class="btn"> Update Post </button>

            <input type="hidden" name="_token" value="{{ Session::token() }}" />
            <input type="hidden" name="post_id" value="{{ $post->id }}" />
        </form>
    </div>
    <div class="modal" >
        <button class="btn" id="modal-close">Close</button>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        var token = '{{ Session::token() }}';
    </script>
    <script type="text/javascript" src="{{ URL::to('js/modal.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('js/posts.js') }}"></script>
@endsection