@extends('layouts.app')

@section('content')
    <div id="app">
        <user-review></user-review>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
