@extends('layouts.admin-master')
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/form.css') }}" type="text/css">
    @endsection
@section('content')
    <div class="container">
        @include('includes.info-box')
        <form action="{{ route('admin.blog.post.create') }}" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" {{ $errors->has('title')?'class=has-error':'' }} value="{{ Request::old('title') }}" />
            </div>

            <div class="input-group">
                <label for="author">Author</label>
                <input type="text" name="author" id="author" {{ $errors->has('author')?'class=has-error':'' }} value="{{ Request::old('author') }}" />
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
                    <ul></ul>
                </div>
                <input type="hidden" name="categories" id="categories"/>
            </div>
            <div class="input-group">
                <label for="body">Body</label>
                <textarea name="body" id="body" rows="12" {{ $errors->has('body')?'class=has-error':'' }}>{{ Request::old('body') }}</textarea>
            </div>
            <div class="input-group">
                <label for="tags">Tags(seperated by ,)</label>
                <select name="tag_select" id="tag_select">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach

                </select>
                <button type="button" class="btn" id="tag_button">Add Tag</button>
                <textarea name="tags" id="tags" style="margin-top: 15px;" rows="1" {{ $errors->has('tags')?'class=has-error':'' }}>{{ Request::old('tags') }}</textarea>
            </div>
            <div class="input-group">
                <label for="postImg[]">Image</label>
                <input type="file"  name="postImg[]" id="postImg"  multiple>
            </div>
           <button type="submit" class="btn"> Create Post </button>
            <input type="hidden" name="_token" value="{{ Session::token() }}" />
        </form>
    </div>
    @endsection
@section('scripts')
    <script type="text/javascript" src="{{ URL::to('js/posts.js') }}"></script>
    @endsection