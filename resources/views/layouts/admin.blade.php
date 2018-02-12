@extends('layouts.app')
@section('admin-options')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <i class="fa fa-pencil fa-fw"></i>Administrar<span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{route('admin.usuarios.index')}}"><i class="fa fa-user fa-fw"></i>Usuarios</a></li>
            <li><a href="{{route('admin.fincas.index')}}"><i class="fa fa-truck fa-fw"></i>Fincas</a></li>
            <li><a href="{{route('admin.animales.index')}}"><i class="fa fa-paw fa-fw"></i>Animales</a></li>
            <li><a href="{{route('admin.inspecciones.index')}}"><i class="fa fa-list-alt fa-fw"></i>Inspecciones</a></li>
        </ul>
    </li>
    <li><a href="{{route('admin.historial')}}"><i class="fa fa-history fa-fw"></i>Historial</a></li>
@endsection
@section('content')
@yield('page')
@endsection
