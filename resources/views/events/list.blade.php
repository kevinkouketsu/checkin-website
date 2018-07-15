@extends('adminlte::page')

@section('title', 'Lista de Eventos')

@section('content_header')
    <h1>Lista de Eventos</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
        <li><a href="{{ route('eventos.listar') }}">Lista de Eventos</a></li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <form method="GET" action="{{ route('eventos.listar') }}">
                        <div class="form-row">
                            <div class="col-md-1">
                                <label for="nameEvent" class="control-label">Nome</label>
        
                                <input type="text" class="form-control mb-2" name="name" id="nameEvent" value="{{ old('name') }}">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="minDate" class="control-label">Data inicial</label>
        
                                <input type="date" class="form-control mb-2"  name="dateInitial" id="minDate"
                                @if (old('dateInitial'))
                                    value="{{ old('dateInitial') }}">
                                @else
                                    value="{{ $date->subDays(7)->format('Y-m-d') }}">
                                @endif
                            </div>

                            <div class="col-md-2">
                                <label for="maxDate" class="control-label">Data Final</label>
        
                                <input type="date" class="form-control mb-2" name="dateMax" id="maxDate"
                                @if (old('dateMax'))
                                    value="{{ old('dateMax') }}">
                                @else
                                    value="{{ $date->now()->format('Y-m-d') }}">
                                @endif
                            </div>
                            <div class="col-md-2   ">
                                <label for="type" class="control-label">Tipo de Evento</label>
                                <select name="type" id="type" class="form-control mb-2">
                                    <option value="-1" selected>Escolha um Tipo</option>
                                    @forelse($eventType as $type)
                                        <option @if (old('type') == $type->id) selected @endif value="{{$type->id}}">{{$type->name}}</option>
                                    @empty
                                        <option disabled>Nenhuma opção</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="states" class="control-label">Estado</label>
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
                            <div class="col-md-2">
                                <label for="cities" class="control-label">Cidades</label>
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
                            <div class="col-md-2">
                                <label for="cities" class="control-label">Cidades</label>
                                <select name="cities" id="cities" class="form-control">
                                </select>
                            </div>
                            @endif
                            <div class="col-md-2">
                                <br />
                                <button type="submit" class="btn btn-primary"  >Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </div> 
                <!-- box-header-->
                <div class="box-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome do Evento</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Cidade</th>
                                <th>Descrição</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eventList as $event)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ $event->data->format('d/m/Y H:i') }}</td>
                                    <td>{{ $event->type->name }}</td>
                                    <td>{{ $event->city->name }}/{{ $event->city->state_uf }}</td>
                                    <td>{{ $event->description }} </td>
                                    <td><a href="{{ route('eventos.visualizar', ['nome'=> kebab_case ($event->name), 'id'=>$event->id]) }}"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                    @if(isset($dataForm))
                        {{ $eventList->appends($dataForm)->links() }}
                    @else
                        {{ $eventList->links() }}
                    @endif
                </div>
            </div>
            <!--box-->
        </div>
        <!-- col-xs-12-->
    </div>
    <!-- row-->

@stop

@section('js')
<script src="{{ url ('js/util.js') }}"></script>
@stop