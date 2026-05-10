@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="station-grid">
        @foreach($stations as $station)
            <div class="station-card {{ $station->is_occupied ? 'occupied' : 'available' }}">
                <span class="station-name">{{ $station->name }}</span>
                <span class="station-status">
                    {{ $station->is_occupied ? $station->user->name : 'Available' }}
                </span>
            </div>
        @endforeach
    </div>
@endsection