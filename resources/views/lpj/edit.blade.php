@extends('layouts.app')
@section('title', 'Edit LPJ')

@section('content')
    @include('lpj._form', [
        'mode'           => 'edit',
        'action'         => route('lpj.update', $lpj),
        'formMethod'     => 'PUT',
        'model'          => $lpj,
        'seed'           => $seed,
        'danaKeluarSeed' => $danaKeluarSeed,
    ])
@endsection
