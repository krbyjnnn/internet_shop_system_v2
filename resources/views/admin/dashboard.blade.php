@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="station-grid">
    @foreach($stations as $station)
        <div class="station-card {{ $station->is_occupied ? 'occupied' : 'available' }}">
            <span class="station-name">{{ $station->name }}</span>
            <span class="station-status">
                {{ $station->is_occupied ? $station->user->name : 'Available' }}
            </span>
            @if($station->is_occupied)
                <form method="POST" 
                    action="{{ route('admin.stations.force-logout', $station->id) }}"
                    style="margin-top: 8px;">
                    @csrf
                    <button type="submit" class="btn-force-logout"
                        onclick="return confirm('Force logout {{ $station->name }}?')">
                        Force Logout
                    </button>
                </form>
            @endif
        </div>
    @endforeach
</div>

@endsection