@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="panel panel-success shadow">
            <div class="panel-heading">Usuarios registrados</div>
            <div class="panel-body">
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{route('admin.usuarios.create')}}">
                        <i class="fa fa-plus fa-fw"></i>Crear usuario
                    </a>
                </div>
                {!!Form::open(['route' => 'admin.usuarios.index', 'method' => 'GET', 'class' => 'form-inline'])!!}
                    <div class="col-md-2 col-md-offset-5">
                        {!!Form::checkbox('rol', 'si')!!}Según el rol
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            {!!Form::text('nombre', '', ['placeholder' => 'Nombre', 'class' => 'form-control'])!!}
                            <span class="input-group-btn">
                                {!!Form::submit('Aplicar', ['class' => 'btn btn-default'])!!}
                            </span>
                        </div>
                    </div>
                {!!Form::close()!!}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <tr class="success">
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Rol</th>
                        <th>Operaciones</th>
                    </tr>
                    <tbody>
                        @foreach($users as $user)
                            @if($user->state == '1')
                                @php
                                    $trClass = '';
                                    $buttonClass = ' btn-danger';
                                    $action = 'Desactivar';
                                @endphp
                            @else
                                @php
                                    $trClass = 'danger';
                                    $buttonClass = ' btn-default';
                                    $action = 'Activar';
                                @endphp
                            @endif
                            <tr class="{{$trClass}}">
                                <td>{{$user->identification}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->lastName}}</td>
                                <td>
                                    @if($user->role == 'a')
                                        Administrador
                                    @elseif($user->role == 'i')
                                        Inspector
                                    @else
                                        Productor
                                    @endif
                                </td>
                                <td>
                                    {!!link_to_route('admin.usuarios.show', $title='Ver / Editar', $parameters=$user->id, $attributes=['class'=>'btn btn-success'])!!}
                                    {!!link_to_route('admin.usuarios.edit', $title=$action, $parameters=$user->id, $attributes=['class'=>'btn'.$buttonClass])!!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{$users->links()}}
        </div>
    </div>
@endsection
