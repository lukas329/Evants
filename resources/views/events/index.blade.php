@extends('layouts.app')

@section('content')
    <div id="app">
        <events-index></events-index>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
