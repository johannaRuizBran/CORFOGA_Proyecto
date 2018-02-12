@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default shadow">
                    <div class="panel-heading">Ver y editar finca</div>
                    <div class="panel-body">
                        {!!Form::open(['route' => ['admin.fincas.update', $farm->asocebuID], 'method' => 'PUT', 'class' => 'form-horizontal'])!!}
                            {{ csrf_field() }}
                            <div class="form-group">
                                {!!Form::label('asocebuID', 'ID ASOCEBU:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('asocebuID', $farm->asocebuID, ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus', 'readonly' => 'readonly'])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('owner', 'Dueño:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="owner">
                                        @foreach($producers as $producer)
                                            <option value="{{$producer->id}}" <?php if($producer->id == $farm->userID) echo 'selected'?>>{{$producer->identification.' / '.$producer->fullName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('region', 'Región:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('region', $region->name, ['class' => 'form-control', 'readonly' => 'readonly'])!!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                {!!Form::label('name', 'Nombre:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('name', $farm->name, ['class' => 'form-control', 'required' => 'required'])!!}
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!!Form::submit('Actualizar finca', ['class' => 'btn btn-success'])!!}
                                </div>
                            </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
