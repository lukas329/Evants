<!-- resources/views/chat.blade.php -->
@extends('layouts.app')

@section('content')
    <div id="app">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    Chats
                    <div class="card-body">
                        <chat-messages :user="{{ Auth::user() }}"></chat-messages>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
