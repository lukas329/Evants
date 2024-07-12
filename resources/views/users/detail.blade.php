@extends('layouts.app')

@section('content')
    <div id="app">
        <user-detail></user-detail>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
