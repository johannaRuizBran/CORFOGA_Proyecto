@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default shadow">
                    <div class="panel-heading">Crear inspección manualmente</div>
                    <div class="panel-body">
                        {!!Form::open(['route' => 'admin.inspecciones.store', 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal'])!!}
                            {{ csrf_field() }}
                            <div class="form-group">
                                {!!Form::label('farm', 'Finca:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="farm">
                                        @foreach($farms as $farm)
                                            <option value="{{$farm->asocebuID}}">{{$farm->asocebuID}} ─ {{$farm->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('inspector', 'Inspector:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="inspector">
                                        @foreach($inspectors as $inspector)
                                            <option value="{{$inspector->id}}">{{$inspector->identification}} / {{$inspector->fullName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('datetime', 'Fecha y hora:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <input name="datetime" type="datetime-local" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('visitNumber', 'Visita:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="visitNumber">
                                        @foreach($visitNumbers as $visitNumber)
                                            <option value="{{$visitNumber}}">{{$visitNumber}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('excel', 'Excel:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::file('excel', ['class' => 'form-control', 'accept' => '.xlsx', 'required' => 'required'])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!!Form::submit('Cargar inspección', ['class' => 'btn btn-success'])!!}
                                </div>
                            </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
