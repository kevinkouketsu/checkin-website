@extends('adminlte::page')

@section('title', $eventInfo->name )

@section('content_header')
    <h1>{{ $eventInfo->name }}</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
        <li><a href="{{ route('eventos.listar') }}">Lista de Eventos</a></li>
        <li><a href="{{ route('eventos.visualizar', ['nome'=> kebab_case ($eventInfo->name), 'id'=>$eventInfo->id]) }}">{{ $eventInfo->name }}</a></li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-md-4 col-sm-5 col-lg-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Local do Evento</b> <a class="pull-right">{{ $eventInfo->city->name }} - {{ $eventInfo->city->state_uf }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Região do Evento</b> <a class="pull-right">{{ $eventInfo->city->state->region->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Data do Evento</b> <a class="pull-right">{{ $eventInfo->data->format('d/m/Y H:i') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Tipo de Evento</b> <a class="pull-right">{{ $eventInfo->type->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Descrição do Evento</b> <a class="pull-right">{{ $eventInfo->description }}</a>
                    </li>
                </ul>
                <div class="col-md-6">
                    <a href="#" class="btn btn-primary btn-block mb-2"><b>Editar</b></a>
                </div>
                <div class="col-md-6">
                    <a href="#" class="btn btn-primary btn-block mb-2" id="btnDelete"><b>Deletar</b></a>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="totalGraduados" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Graduação</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <table id="totalVendidos" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 col-sm-7 col-lg-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs ">
                <li class="active"><a href="#checkin" data-toggle="tab">Checkin</a></li>
                <li><a href="#search" data-toggle="tab">Relatório por equipe</a></li>
            </ul>
            <div class="tab-content no-padding">
                <div id="checkin" class="tab-pane active">
                    <div class="box">
                        <div class="box-header">
                            <div id="showError" style="display:none;" >
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Alerta!</h4>
                                    <div id="errorMsg">
                                        
                                    </div>
                                </div>
                            </div>
                            <div id="showSuccess" style="display: none;">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> Sucesso!</h4>
                                    <div id="successMsg">
                                        
                                    </div>
                                </div>
                            </div>
                            <form class="form-inline">
                                <input placeholder="Procurar convidado..." type="text" name="search" class="form-control mb-2 mr-sm-2" id="searchMonitor" />
                                <input placeholder="Procurar quem convidou..." type="text" name="search" class="form-control mb-2 mr-sm-2" id="searchInvited" />

                                <div class="checkbox">
                                    <select name="type" id="sell_form" class="form-control mb-2">
                                            <option value="0" selected>Sem possibilidade de venda</option>
                                            <option value="1">Possibilidade de venda</option>
                                    </select>
                                </div>

                                <button  type="button" class="btn btn-success mb-2 mr-sm-2" id="btnDoCheckin">CHECKIN</button>
                                <button  type="button" class="btn btn-success mb-2" id="addNewuser" data-toggle="modal" data-target="#modal-cadastro">CADASTRAR</button>
                            </form>

                            <form method="post" action="{{ route('eventos.checkin') }}" id="doCheckin">
                                {{ csrf_field() }}
                                <input type="hidden" name="pai_id" value="-1">
                                <input type="hidden" name="convidado_id" value="-1">
                                <input type="hidden" name="event_id" value="{{ $eventInfo->id }}">
                                <input type="hidden" name="sell" id="sell" value="0">
                            </form>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="presenceList" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome do Convidado</th> 
                                                <th>Categoria</th>
                                                <th>Hora de Chegada</th>
                                                <th>Convidado por</th>
                                                <th>Equipe</th>
                                                <th>UserID</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3>Possibilidade de Vendas</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="sellList" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome do Convidado</th> 
                                                <th>Categoria</th>
                                                <th>Status</th>
                                                <th>Convidado por</th>
                                                <th>Equipe</th>
                                                <th>UserID</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- box-->
                </div>
                <!-- checkin-->
                <div id="search" class="tab-pane">
                    <div class="box box-primary">
                        <div class="box-header">
                            <form class="form-inline" method="post" id="report" action="{{ route ('eventos.relatorio.equipe') }}">
                                <input placeholder="Procurar equipe..." type="text" name="search" class="form-control mb-2 mr-sm-2" id="searchStaff" />
                                {{ csrf_field() }}

                                <input type="hidden" value="-1" name="pai_id_staff">
                                <input type="hidden" name="event_id" value="{{ $eventInfo->id }}">

                                <button type="button" class="btn btn-info mb-2 mr-sm-2" id="btnGenerate">Gerar</button>
                            </form>
                        </div>
                        <!-- box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="reportList" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome do Convidado</th> 
                                                <th>Categoria</th>
                                                <th>Status</th>
                                                <th>Convidado por</th>
                                                <th>Equipe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <canvas id="myChart"></canvas>
                            </div>
                            <div class="col-md-6">
                                <canvas id="myChart2"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- box-->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-cadastro">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastrar Convidado</h4>
            </div>
            <div class="modal-body">
                
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                <form id="form_cadastro" class="form-horizontal" action="{{ route('cadastrar.convidado') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Nome completo <span class="required">*</span></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nome">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="pai_cadastra" class="col-sm-3 control-label">Pai <span class="required">*</span></label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="pai_cadastra" id="pai_cadastra" placeholder="Pai">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="graduation" class="col-sm-3 control-label">Graduação <span class="required">*</span></label>

                        <div class="col-sm-9">
                            <select name="graduation" id="graduation" class="form-control mb-2">
                                <option selected>Escolha um Tipo</option>
                                @forelse($graduate as $type)
                                    <option @if (old('graduation') == $type->id) selected @endif value="{{$type->id}}">{{$type->name}}</option>
                                @empty
                                    <option disabled>Nenhuma opção</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
            
                    <input type="hidden" name="pai_id_cadastra" value="-1">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                <button type="button" id="btnCadastrar" class="btn btn-primary">Cadastrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


@stop

@section('css')
    <link rel="stylesheet" href="{{ url('css/plugin/jquery.autocomplete.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
@stop

@section('js')
<script src="{{ url('js/plugin/jquery.autocomplete.js') }}"></script>
<script src="{{ url ('js/event/view.js') }}"></script>
@stop