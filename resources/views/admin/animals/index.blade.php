@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="panel panel-success shadow">
            <div class="panel-heading">Animales registrados</div>
            <div class="panel-body">
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{route('admin.animales.create')}}">
                        <i class="fa fa-upload fa-fw"></i>Agregar animales
                    </a>
                </div>
                {!!Form::open(['route' => 'admin.animales.index', 'method' => 'GET', 'class' => 'form-inline'])!!}
                    <div class="col-md-2 col-md-offset-4">
                        {!!Form::checkbox('raza', 'si')!!}Según la raza
                    </div>
                    <div class="col-md-2">
                        {!!Form::checkbox('sexo', 'si')!!}Según el sexo
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
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <tr class="success">
                        <th>Finca</th>
                        <th>Registro</th>
                        <th>Código</th>
                        <th>Raza</th>
                        <th>Sexo</th>
                        <th>Nacimiento</th>
                        <th>Cód. padre</th>
                        <th>Reg. padre</th>
                        <th>Cód. madre</th>
                        <th>Reg. madre</th>
                    </tr>
                    <tbody>
                        @foreach($animals as $animal)
                            @if($animal->state == '1')
                                @php
                                    $trClass = '';
                                @endphp
                            @else
                                @php
                                    $trClass = 'danger';
                                @endphp
                            @endif
                            <tr class="{{$trClass}}">
                                <td>
                                    {!!link_to_route('admin.fincas.show', $title=$animal->asocebuFarmID, $parameters=$animal->asocebuFarmID)!!}
                                </td>
                                <td>{{$animal->register}}</td>
                                <td>{{$animal->code}}</td>
                                <td>{{$animal->breedName}}</td>
                                <td>
                                    @if($animal->sex == 'm')
                                        Macho
                                    @else
                                        Hembra
                                    @endif
                                </td>
                                <td>{{$animal->birthdate}}</td>
                                <td>{{$animal->fatherRegister}}</td>
                                <td>{{$animal->fatherCode}}</td>
                                <td>{{$animal->motherRegister}}</td>
                                <td>{{$animal->motherCode}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="pull-right">
            {{$animals->links()}}
        </div>
    </div>
@endsection
@section('javascript')
    {!!Html::script('js/admin/animals/index.js')!!}
@endsection
