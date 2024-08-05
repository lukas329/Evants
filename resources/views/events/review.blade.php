@extends('layouts.app')

@section('content')
    <div id="app">
        <event-review></event-review>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
