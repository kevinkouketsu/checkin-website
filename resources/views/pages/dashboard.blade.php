@extends('adminlte::page')

@section('title', 'Painel de Controle')

@section('content_header')
    <h1>Painel de Controle</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}">Painel de Controle</a></li>
    </ol>
@stop

@section('content')
    <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
        <div class="inner">
        <h3>{{ $totalNetwork }}</h3>

        <p>Usuários na sua rede</p>
        </div>
        <div class="icon">
        <i class="ion ion-person-add"></i>
        </div>
    </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop