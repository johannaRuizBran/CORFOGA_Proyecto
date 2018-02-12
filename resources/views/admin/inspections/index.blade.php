@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="panel panel-success shadow">
            <div class="panel-heading">Inspecciones realizadas</div>
            <div class="panel-body">
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{route('admin.inspecciones.create')}}">
                        <i class="fa fa-plus fa-fw"></i>Crear inspección
                    </a>
                </div>
                {!!Form::open(['route' => 'admin.inspecciones.index', 'method' => 'GET', 'class' => 'form-inline'])!!}
                    <div class="col-md-2 col-md-offset-6">
                        {!!Form::checkbox('inspectores', 'si')!!}Según inspector
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            {!!Form::text('finca', '', ['placeholder' => 'Finca', 'class' => 'form-control', 'id' => 'farm'])!!}
                            <span class="input-group-btn">
                                {!!Form::submit('Aplicar', ['class' => 'btn btn-default'])!!}
                            </span>
                        </div>
                    </div>
                {!!Form::close()!!}
            </div>
            <table class="table table-bordered text-center">
                <tr class="success">
                    <th>Finca</th>
                    <th>Responsable</th>
                    <th>Fecha y hora</th>
                    <th>Visita</th>
                    <th>Operaciones</th>
                </tr>
                <tbody>
                    @foreach($inspections as $inspection)
                        <tr>
                            <td>
                                {!!link_to_route('admin.fincas.show', $title=$inspection->asocebuFarmID, $parameters=$inspection->asocebuFarmID)!!}
                            </td>
                            <td>
                                {!!link_to_route('admin.usuarios.show', $title=$inspection->identification.' / '.$inspection->userFullName, $parameters=$inspection->userID)!!}
                            </td>
                            <td>{{$inspection->datetime}}</td>
                            <td>{{$inspection->visitNumber}}</td>
                            <td>
                                {!!link_to_route('admin.inspecciones.show', $title='Ver detalles', $parameters=$inspection->id, $attributes=['class'=>'btn btn-success'])!!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pull-right">
            {{$inspections->links()}}
        </div>
    </div>
@endsection
@section('javascript')
    {!!Html::script('js/admin/inspections/index.js')!!}
@endsection
