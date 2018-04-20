<!doctype html>
        <html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>


    <link rel="stylesheet" href="{{URL::to('css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{URL::to('css/blog-home.css') }}" type="text/css">
    <link rel="stylesheet" href="{{URL::to('css/tags.css') }}" type="text/css">
    {{--<link rel="stylesheet" href="{{URL::to('css/main.css') }}" type="text/css">--}}
    @yield('styles')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
@include('includes.header')
        <!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            @yield('content')

        </div>

        <!-- Blog Sidebar Widgets Column -->
        <div class="col-md-4">

            <!-- Blog Search Well -->
            <div class="well">
                <h4>Blog Search</h4>
                <form action="{{route('blog.search.posts')}}" method="get">
                    <div class="input-group">
                        <input type="hidden" name="_token"  value="{{Session::token()}}">
                        <input type="text" name="search" id="search" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>

                        </span>
                    </div>
                </form>
                <!-- /.input-group -->
            </div>

            <!-- Blog Categories Well -->
            @if(isset($categories_even))
            <div class="well">
                <h4>Blog Categories</h4>
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            @foreach($categories_even as $item)
                            <li><a href="{{ route('blog.category.posts',['category_id'=>$item->id]) }}">{{ ucfirst($item->name) }}</a>
                            </li>
                           @endforeach
                        </ul>
                    </div>
                    <!-- /.col-lg-6 -->
                    <div class="col-lg-6">
                        <ul class="list-unstyled">
                            @foreach($categories_odd as $item)
                                <li><a href="{{ route('blog.category.posts',['category_id'=>$item->id]) }}">{{ ucfirst($item->name) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.col-lg-6 -->
                </div>
                <!-- /.row -->
            </div>
@endif
            <!-- Side Widget Well -->
            @if(isset($categories_even) && count($tags)>0)
            <div class="well">
                <h4>Side Widget Well</h4>
                <div class="tagcloud04">
                    <ul>

                        @foreach($tags as $item)
                            <li><a href="{{ route('blog.tag.posts',['tag_id'=>$item->tag_id]) }}">{{$item->name}}</a><span>{{$item->post_count}}</span></li>

                        @endforeach
                    </ul>
                </div>

               </div>
@endif
        </div>

    </div>
    <!-- /.row -->

    <hr>

    @include('includes.footer')
</div>
<!-- /.container -->
{{--<div class="main">--}}
    {{--@yield('content')--}}
{{--</div>--}}
  <!-- jQuery -->

<script src="{{URL::to('js/jquery.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{URL::to('js/bootstrap.min.js') }}"></script>
<script type="text/javascript">
    var baseUrl='{{ URL::to('/') }}';
</script>
@yield('scripts')

</body>
</html>