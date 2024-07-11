@extends('layouts.app')

@section('content')
    <div id="app">
        <events-detail></events-detail>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
