@extends('layouts.admin')
@section('page')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default shadow">
                    <div class="panel-heading">Ver y editar usuario</div>
                    <div class="panel-body">
                        {!!Form::open(['route' => ['admin.usuarios.update', $user->id], 'method' => 'PUT', 'class' => 'form-horizontal'])!!}
                            {{csrf_field()}}
                            <div class="form-group">
                                {!!Form::label('role', 'Rol:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::select('role', ['a' => 'Administrador', 'i' => 'Inspector', 'p' => 'Productor'], $user->role, ['class' => 'form-control', 'disabled' => 'disabled'])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('identification', 'Cédula:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('identification', $user->identification, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly', 'id' => 'identification'])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('state', 'Estado:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('state', ($user->state == '1') ? 'Activo' : 'Inactivo', ['class' => 'form-control', 'readonly' => 'readonly'])!!}
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                {!!Form::label('name', 'Nombre:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required'])!!}
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('lastName') ? ' has-error' : '' }}">
                                {!!Form::label('lastName', 'Apellidos:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('lastName', $user->lastName, ['class' => 'form-control', 'required' => 'required'])!!}
                                    @if ($errors->has('lastName'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lastName') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                {!!Form::label('password', 'Nueva contraseña:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::password('password', ['class' => 'form-control', 'required' => 'required', 'autofocus' => 'autofocus'])!!}
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                {!!Form::label('password-confirm', 'Confirmar contraseña:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                {!!Form::label('email', 'Email:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::email('email', $user->email, ['class' => 'form-control', 'required' => 'required'])!!}
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('phoneNumber') ? ' has-error' : '' }}">
                                {!!Form::label('phoneNumber', 'Número de teléfono:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    {!!Form::text('phoneNumber', $user->phoneNumber, ['class' => 'form-control', 'id' => 'phoneNumber'])!!}
                                    @if ($errors->has('phoneNumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phoneNumber') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                @if($anyFarm)
                                    <div class="col-md-6 col-md-offset-4">
                                        {!!link_to_route('admin.fincas.index', $title='Ver fincas', $parameters=['dueno' => $user->name.' '.$user->lastName], $attributes=['class' => 'btn btn-default'])!!}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    {!!Form::submit('Actualizar usuario', ['class' => 'btn btn-success'])!!}
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
    {!!Html::script('js/admin/users/edit.js')!!}
@endsection