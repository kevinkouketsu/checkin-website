@extends('adminlte::page')

@section('title', 'Tipos de Eventos')

@section('content_header')
    <h1>Tipos de Eventos</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
        <li><a href="{{ route('eventos.tipos') }}">Lista de Tipos</a></li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Todos os tipos</h3>
                </div>
                <!-- box-header -->
                
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($type as $t)
                                <tr>
                                    <td>{{ $t->name }}</td>
                                    <td><a class="editType" href="{{ route('eventos.tipo.get', $t->id) }}">editar</a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td>
                                        <td colspan="2">Nenhum tipo de evento registrado</td>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- box-body -->
            </div>
            <!-- box-->
        </div>
        <!-- col-lg-6-->
        <div class="col-lg-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Adicionar um novo evento</h3>
                </div>
                <form method="POST">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nome do Tipo</label>
                            <input type="type" class="form-control" id="name" placeholder="">
                        </div>
                    </div>
                    <!-- box-body-->
                    <div class="box-footer">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- box -->
        </div>
        <!-- col-lg-12-->
    </div>
    <!-- row-->

    
<div class="modal fade" id="modal-edit">
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
                    <form id="form_edit" class="form-horizontal" action="" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Nome <span class="required">*</span></label>
    
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="edit_name" id="edit_name" placeholder="Nome">
                            </div>
                        </div>
                        <input type="hidden" id="edit_type" value="-1">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                    <button type="button" id="btnEdit" class="btn btn-primary">Editar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    
@stop

@section('js')
<script src="{{ url ('js/util.js') }}"></script>
<script src="{{ url ('js/event/type.js') }}"></script>
@stop