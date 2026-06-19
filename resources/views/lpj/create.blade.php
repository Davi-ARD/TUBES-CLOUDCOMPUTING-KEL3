@extends('layouts.app')
@section('title', 'Buat Laporan Pertanggungjawaban')

@section('content')
    @include('lpj._form', [
        'mode'           => 'create',
        'action'         => route('lpj.store'),
        'formMethod'     => 'POST',
        'model'          => null,
        'seed'           => null,
        'danaKeluarSeed' => null,
    ])
@endsection
