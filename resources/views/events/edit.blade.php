@extends('layouts.app')

@section('content')
    <div id="app">
        <edit-event></edit-event>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/app.js')
@endpush
