@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @php
        $user = Auth::user();
    @endphp
    <div id="app">
    </div>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</div>
@endsection
