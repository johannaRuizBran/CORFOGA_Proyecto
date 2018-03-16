@extends('layouts.app')
@section('admin-options')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <i class="fa fa-pencil fa-fw"></i>Ver<span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
       
        <li><a href="{{route('farmer.animales.index')}}"><i class="fa fa-user fa-fw"></i>Animales</a></li>
       
        </ul>
    </li>
    
@endsection
@section('content')
@yield('page')
@endsection
