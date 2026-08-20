@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Something Went Wrong'))
@section('description', __('We couldn\'t complete that request. Please try again in a moment.'))

@section('actions')
    <button onclick="window.location.reload()" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-vanniyan-green-800 transition-colors">Try Again</button>
    <a href="{{ url('/') }}" class="px-8 py-3 bg-white border border-gray-300 text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded hover:bg-gray-50 transition-colors">Back to Vanniyan</a>
@endsection
