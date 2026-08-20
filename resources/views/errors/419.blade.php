@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Session Expired'))
@section('description', __('Your session has expired. Please try again.'))

@section('actions')
    <button onclick="window.location.reload()" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-vanniyan-green-800 transition-colors">Try Again</button>
    <a href="{{ url('/') }}" class="px-8 py-3 bg-white border border-gray-300 text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded hover:bg-gray-50 transition-colors">Back to Home</a>
@endsection
