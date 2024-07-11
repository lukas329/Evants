@extends('layouts.app')

@section('content')
    <div id="app">
        <create-event></create-event>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
