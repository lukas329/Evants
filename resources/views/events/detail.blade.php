@extends('layouts.app')

@section('content')
    <div id="app">
        <events-detail></events-detail>
        <regis-users></regis-users>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
