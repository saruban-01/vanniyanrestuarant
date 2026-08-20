@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))
@section('description', __('Please wait a moment and try again.'))

@section('actions')
    <button onclick="window.location.reload()" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-vanniyan-green-800 transition-colors">Try Again</button>
@endsection
