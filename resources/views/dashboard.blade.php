@extends('layouts.app')

@section('content')
    <div id="app">
        <dashboard></dashboard>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
