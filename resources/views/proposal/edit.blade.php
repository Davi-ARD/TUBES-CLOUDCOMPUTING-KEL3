@extends('layouts.app')
@section('title', 'Edit Proposal Kegiatan')

@section('content')
    @include('proposal._form', [
        'mode'       => 'edit',
        'action'     => route('proposal.update', $proposal),
        'formMethod' => 'PUT',
        'model'      => $proposal,
        'seed'       => $seed,
    ])
@endsection
