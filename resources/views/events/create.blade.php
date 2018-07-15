@extends('adminlte::page')

@section('title', 'Criar Evento')

@section('content_header')
    <h1>Criar Evento</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
        <li><a href="{{ route('eventos.criar') }}">Criar Evento</a></li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-body">
                <form method="post" action="{{ route('eventos.criar.enviar') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Nome do evento</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nome do Evento" value="{{ old('name') }}" >
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Descrição do evento" value="{{ old('description') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Data do Evento</label>
                        <input type="datetime-local" class="form-control" id="data" name="data" placeholder="Data" value="{{ old('data') }}">
                    </div>
                    <div class="form-group">
                        <label for="description">Tipo do Evento</label>
                        <select name="eventtype_id" id="eventtype_id" class="form-control mb-2">
                            <option selected>Escolha um Tipo</option>
                            @forelse($eventType as $type)
                                <option @if (old('eventtype_id') == $type->id) selected @endif value="{{$type->id}}">{{$type->name}}</option>
                            @empty
                                <option disabled>Nenhuma opção</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="states">Estado</label>
                        <select name="states" class="form-control mb-2" onchange="fillStates(this, '', '#cities')">
                            <option value="-1" selected>Escolha um estado</option>
                            @forelse($state as $s)
                                <option @if (old('states') == $s->uf) selected @endif value="{{$s->uf}}">{{$s->name}}</option>
                            @empty
                                <option disabled>Nenhuma opção</option>
                            @endforelse
                        </select>
                    </div>
                    
                    @if(old('states'))
                    <div class="form-group">
                        <label for="city_code">Data do Evento</label>
                        <select name="cities" id="cities" class="form-control mb-2">
                            @foreach($state as $s)
                                @if($s->uf == old('states'))
                                    @foreach($s->cities as $city)
                                        <option @if (old('cities') == $city->code) selected @endif value="{{$city->code}}">{{$city->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="form-group">
                        <label for="cities" class="control-label">Cidades</label>
                        <select name="cities" id="cities" class="form-control">
                        </select>
                    </div>
                    @endif

                    <button class="btn btn-primary" type="submit">Criar evento</button>
                </form>
            </div>
        </div>
    </div>
    <!-- col-md-6-->
    <div class="col-md-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif      
    </div>
</div>
<!-- row -->

@stop

@section('js')
<script src="{{ url ('js/util.js') }}"></script>
@stop