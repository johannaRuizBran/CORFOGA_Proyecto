@extends('layouts.admin')
@section('page')
<!--m,nm,n,mn-->
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default shadow">
                    <div class="panel-heading">Cargar nuevos animales</div>
                    <div class="panel-body">
                        {!!Form::open(['route' => 'admin.animales.store', 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal'])!!}
                            {{ csrf_field() }}
                            <div class="form-group">
                                {!!Form::label('farm', 'Finca:', ['class' => 'col-md-4 control-label'])!!}
                                <div class="col-md-6">
                                    <select class="form-control"  name="farm">
                                        @foreach($farms as $farm)
                                            <option value="{{$farm->asocebuID}}">{{$farm->asocebuID}} / {{$farm->name}}</option>
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
                                    {!!Form::submit('Cargar archivo', ['class' => 'btn btn-success'])!!}
                                </div>
                            </div>
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
