@extends('layouts.admin-master')
@section('styles')
    <link rel="stylesheet" href="{{URL::to('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ URL::to('css/login.css') }}" type="text/css">
@endsection

@section('content')

    <div class="container">
        @include('includes.info-box')
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <h1 class="text-center login-title">Admin Dashboard</h1>
                <div class="account-wall">
                    <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                         alt="">
                    <form class="form-signin" action="{{route('login')}}" method="post">
                        <input type="text" class="form-control" name="email" id="email" {{$errors->has('email')?'class=has-error':''}} value="{{Request::old('email')}}" placeholder="E-Mail" required="" autofocus="" />
                        <input type="password" class="form-control" name="password" id="password" {{$errors->has('password')?'class=has-error':''}} placeholder="Password" required=""/>
                        <input type="hidden" name="_token" value="{{Session::token()}}">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">
                            Sign in</button>

                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
