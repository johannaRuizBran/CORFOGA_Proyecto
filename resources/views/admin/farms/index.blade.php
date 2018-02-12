@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="panel panel-success shadow">
            <div class="panel-heading">Fincas registradas</div>
            <div class="panel-body">
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{route('admin.fincas.create')}}">
                        <i class="fa fa-plus fa-fw"></i>Crear finca
                    </a>
                </div>
                {!!Form::open(['route' => 'admin.fincas.index', 'method' => 'GET', 'class' => 'form-inline'])!!}
                    <div class="col-md-2 col-md-offset-3">
                        {!!Form::checkbox('nombre', 'si')!!}Según el nombre
                    </div>
                    <div class="col-md-2">
                        {!!Form::checkbox('region', 'si')!!}Según la region
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            {!!Form::text('dueno', '', ['placeholder' => 'Dueño', 'class' => 'form-control'])!!}
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
                        <th>Dueño</th>
                        <th>ID ASOCEBU</th>
                        <th>Región</th>
                        <th>Nombre</th>
                        <th>Operaciones</th>
                    </tr>
                    <tbody>
                        @foreach($farms as $farm)
                            @if($farm->farmState == '1')
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
                                <td>
                                    {!!link_to_route('admin.usuarios.show', $title=$farm->identification.' / '.$farm->userFullName, $parameters=$farm->userID)!!}
                                </td>
                                <td>{{$farm->asocebuID}}</td>
                                <td>{{$farm->regionName}}</td>
                                <td>{{$farm->farmName}}</td>
                                <td>
                                    {!!link_to_route('admin.fincas.show', $title='Ver / Editar', $parameters=$farm->asocebuID, $attributes=['class'=>'btn btn-success'])!!}
                                    @if($farm->userState == '1')
                                        {!!link_to_route('admin.fincas.edit', $title=$action, $parameters=$farm->asocebuID, $attributes=['class'=>'btn'.$buttonClass])!!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{$farms->links()}}
        </div>
    </div>
@endsection
