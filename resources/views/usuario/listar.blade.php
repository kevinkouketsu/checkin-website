@extends('adminlte::page')

@section('title', 'Equipe')

@section('content_header')
    <h1>Sua equipe</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
        <li><a href="{{ route('lista.equipe') }}">Listar Equipe</a></li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
            </div>
            <!-- box-header -->
            
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <td>#</td>
                        <td>Nome</td>
                        <td>Pai</td>
                        <td>Graduação</td>
                        <td>Ações</td>
                    </thead>
                    <tbody>
                        @foreach($staff as $s)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $s->name }}</td>
                                <td>{{ ($s->father) != NULL ? $s->father->name : ''  }} </td>
                                <td>{{ $s->graduate->name }}</td>
                                <td><a href="{{ route('perfil', $s->username)}}"><i class="fa fa-user" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- box-body -->
        </div>
        <!-- box-->
    </div>
    <!-- col-lg-12-->
</div>
<!-- row-->
@stop
@section('css')
@endsection

@section('js')
@stop