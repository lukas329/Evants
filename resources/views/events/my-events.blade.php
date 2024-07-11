@extends('layouts.app')

@section('content')
    <div id="app">
        <events-by-me></events-by-me>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
