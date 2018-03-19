<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    {!!Html::style('css/app.css')!!}
    {!!Html::style('css/base.css')!!}
    {!!Html::style('css/font-awesome.min.css')!!}
    @yield('css')
</head>
<body>
     

    <header>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Opciones</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="{{asset('img/logo.png')}}" alt="Logo" class="img-circle">
                <a class="navbar-brand">{{config('app.name', 'CORGOGA Web Site')}}</a>
            </div>
            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{route('inicio')}}"><i class="fa fa-home fa-fw"></i>Inicio</a></li>
                    @if (Auth::guest())
                        <li><a href="{{route('login')}}"><i class="fa sign-in fa-fw"></i>Acceder</a></li>
                    @else
                        @yield('admin-options')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-user-circle-o fa-fw"></i>{{Auth::user()->name}}<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{route('logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>
                                    <form class="hidden" id="logout-form" action="{{route('logout')}}" method="POST">
                                        {{csrf_field()}}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        @if(Session::has('state'))
            <div class="container alert {{Session::get('alert_class')}} alert-dismissible show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>¡{{Session::get('state')}}!</strong> {{Session::get('message')}}
            </div>
        @endif
        @yield('content')
    </header>
    {!!Html::script('js/app.js')!!}
    {!!Html::script('js/plugins/jquery.maskedinput.js')!!}
    {!!Html::script('js/general.js')!!}
    
    @yield('javascript')
 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @include('sweet::alert')
</body>
</html>
