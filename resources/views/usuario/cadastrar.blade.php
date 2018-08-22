@extends('adminlte::page')

@section('title', 'Tipos de Eventos')

@section('content_header')
    <h1>Cadastrar novo Usuário</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
        <li><a href="{{ route('usuario.cadastrar') }}">Cadastrar Usuário</a></li>
    </ol>
@stop

@section('content')
    <div class="row">
        <form class="form-horizontal" method="post" action="{{ route('cadastrar.completo') }}" id="form_cadastra_principal">
            {{ csrf_field() }}
            <div class="col-lg-8">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#principal" data-toggle="tab">Principal</a></li>
                        <li><a href="#endereco" data-toggle="tab">Endereço</a></li>
                        <li><a href="#equipe" data-toggle="tab">Equipe</a></li>
                    </ul>
                    <!-- nav nav-tabs -->
                    
                    <div class="tab-content">
                        <div class="tab-pane active" id="principal">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Nome Completo</label>
                
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ old('name') }}" name="name" id="name" placeholder="Nome completo">
                                    </div>
                                </div>
                                <!-- form-group name -->
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" value="{{ old('email') }}"  name="email" id="email" placeholder="Email">
                                    </div>
                                </div>
                                <!-- form-group email -->
                                <div class="form-group">
                                    <label for="date" class="col-sm-2 control-label">Data de Nascimento</label>
                
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" value="{{ old('date') }}" name="date" id="date" placeholder="Data de Nascimento">
                                    </div>
                                </div>
                                <!-- form-group date -->
                                <div class="form-group">
                                    <label for="telephone" class="col-sm-2 control-label">Telefone</label>
                
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ old('telephone') }}" name="telephone" id="telephone" placeholder="Telefone">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="graduation" class="col-sm-2 control-label">Graduação</label>

                                    <div class="col-sm-10">
                                        <select name="graduation" id="graduation" class="form-control mb-2">
                                            @foreach($graduation as $g)
                                                    <option @if (old('graduation') == $g->id) selected @endif value="{{$g->id}}">{{$g->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- form-group graduation-->
                            </div>
                            <!-- box-body-->
                        </div>
                        <!-- principal -->
                        <div class="tab-pane" id="endereco">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="address" class="col-sm-2 control-label">Endereço</label>
                
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ old('address') }}" name="address" id="address" placeholder="Endereço">
                                    </div>
                                </div>
                                <!-- form-group endereco-->
                                <div class="form-group">
                                    <label for="neighborhood" class="col-sm-2 control-label">Bairro</label>
                
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ old('neighborhood') }}" name="neighborhood" id="neighborhood" placeholder="Bairro">
                                    </div>
                                </div>
                                <!-- form-group neighborhood-->
                                <div class="form-group">
                                    <label for="number" class="col-sm-2 control-label">Número</label>
                
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ old('number') }}" name="number" id="number" placeholder="Número">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="complement" class="col-sm-2 control-label">Complemento</label>
                
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ old('complement') }}" name="complement" id="complement" placeholder="Complemento">
                                    </div>
                                </div>
                                <!-- form-group number-->
                                <div class="form-group">
                                    <label for="states" class="col-sm-2 control-label">Estado</label>
                                    <div class="col-sm-10">
                                        <select name="states" class="form-control mb-2" onchange="fillStates(this, '', '#cities')">
                                            <option value="-1" selected>Escolha um estado</option>
                                            @forelse($state as $s)
                                                <option @if (old('states') == $s->uf) selected @endif value="{{$s->uf}}">{{$s->name}}</option>
                                            @empty
                                                <option disabled>Nenhuma opção</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                
                                @if(old('states'))
                                <div class="form-group">
                                    <label for="cities" class="col-sm-2 control-label">Cidade</label>
                                    <div class="col-sm-10">
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
                                </div>
                                @else
                                <div class="form-group">
                                    <label for="cities" class="col-sm-2 control-label">Cidade</label>
                                    <div class="col-sm-10">
                                        <select name="cities" id="cities" class="form-control mb-2">
                                        </select>
                                    </div>
                                </div>
                                @endif

                            </div>
                            <!-- box-body-->
                        </div>
                        <!-- endereço -->
                        <div class="tab-pane" id="equipe">
                            <div class="form-group">
                                <label for="number" class="col-sm-2 control-label">Número</label>
            
                                <div class="col-sm-10">
                                    <input placeholder="Procurar quem convidou..." type="text" class="form-control mb-2 mr-sm-2" id="searchMonitor" />
                                    <input type="hidden" name="father" value="-1">
                                </div>
                            </div>
                        </div>
                        <!-- endereço -->
                    </div>
                </div>
                <!-- div nav-tabs-custom-->

                <div class="box-body">
                    <input type="submit" class="form-control btn btn-success" value="Cadastrar">
                </div>
            </div>
            <!-- col-lg-8-->
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
        </form>
    </div>
<!-- row-->

@stop
@section('css')
<link rel="stylesheet" href="{{ url('css/plugin/jquery.autocomplete.css') }}">
@endsection

@section('js')
<script src="{{ url ('js/util.js') }}"></script>
<script src="{{ url('js/plugin/jquery.autocomplete.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.js"></script>
<script src="{{ url ('js/user/register.js') }}"></script>
@stop