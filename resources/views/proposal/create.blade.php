@extends('layouts.app')
@section('title', 'Buat Proposal Kegiatan')

@section('content')
    @include('proposal._form', [
        'mode'       => 'create',
        'action'     => route('proposal.store'),
        'formMethod' => 'POST',
        'model'      => null,
        'seed'       => null,
    ])
@endsection
