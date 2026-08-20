@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('We Couldn\'t Find That Page'))
@section('description', __('The page you\'re looking for may have moved or no longer exists.'))

@section('actions')
    <a href="{{ url('/') }}" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-vanniyan-green-800 transition-colors">Back to Home</a>
    <a href="{{ route('menu') }}" class="px-8 py-3 bg-white border border-gray-300 text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded hover:bg-gray-50 transition-colors">View Menu</a>
@endsection
