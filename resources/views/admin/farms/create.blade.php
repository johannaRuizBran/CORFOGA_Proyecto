@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default shadow">
                    <div class="panel-heading">Crear una nueva finca</div>
                    <div class="panel-body">
                        {!!Form::open(['route' => 'admin.fincas.store', 'method' => 'POST', 'class' => 'form-horizontal', 'onsubmit' => 'return confirmAction("El ID ASOCEBU y la región no prodrán ser cambiados después.")'])!!}
                            {{ csrf_field() }}
                            <div class="form-group">
                                {!!Form::label('asocebuID', 'ID ASOCEBU:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('asocebuID', '', ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus'])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('owner', 'Dueño:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="owner">
                                        @foreach($producers as $producer)
                                            <option value="{{$producer->id}}">{{$producer->identification." / ".$producer->fullName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('region', 'Región:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="region">
                                        @foreach($regions as $region)
                                            <option value="{{$region->id}}">{{$region->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                {!!Form::label('name', 'Nombre:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('name', '', ['class' => 'form-control', 'required' => 'required'])!!}
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!!Form::submit('Crear finca', ['class' => 'btn btn-success'])!!}
                                </div>
                            </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    {!!Html::script('js/admin/farms/create.js')!!}
@endsection
