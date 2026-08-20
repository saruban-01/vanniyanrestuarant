@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __('Access Not Available'))
@section('description', __('You don\'t have permission to access this page.'))

@section('actions')
    <a href="{{ url('/') }}" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-vanniyan-green-800 transition-colors">Back to Home</a>
@endsection
