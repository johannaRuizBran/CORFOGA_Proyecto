@extends('layouts.admin')
@section('page')
<div class="container">
    <div class="panel panel-success shadow">
        <div class="panel-heading">Historial</div>
        <div class="panel-body">
            {!!Form::open(['route' => 'admin.historial', 'method' => 'GET', 'class' => 'form-inline'])!!}
                <div class="col-md-3 col-md-offset-6">
                    Acción: {!!Form::select('accion', [
                        '0' => ' ',
                        '1' => 'Crear usuario',
                        '2' => 'Editar usuario',
                        '7' => 'Activar usuario',
                        '8' => 'Desactivar usuario',
                        '3' => 'Crear finca',
                        '4' => 'Editar finca',
                        '9' => 'Activar finca',
                        '10' => 'Desactivar finca',
                        '5' => 'Registrar animales',
                        '6' => 'Terminar inspección'
                    ], '0', ['class' => 'form-control'])!!}
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        {!!Form::text('responsable', '', ['placeholder' => 'Responsable', 'class' => 'form-control'])!!}
                        <span class="input-group-btn">
                            {!!Form::submit('Aplicar', ['class' => 'btn btn-default'])!!}
                        </span>
                    </div>
                </div>
            {!!Form::close()!!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered shadow text-center">
                <tr class="success">
                    <th>Hora y fecha</th>
                    <th>Responsable</th>
                    <th>Tipo de acción</th>
                    <th>Descripción</th>
                </tr>
                <tbody>
                    @foreach($historicals as $historical)
                    <tr>
                        <td>{{$historical->datetime}}</td>
                        <td>{{$historical->fullName}}</td>
                        <td>{{$historical->typeName}}</td>
                        <td class="col-md-5">{{$historical->description}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pull-right">
            {{$historicals->links()}}
        </div>
    </div>
</div>
@endsection
