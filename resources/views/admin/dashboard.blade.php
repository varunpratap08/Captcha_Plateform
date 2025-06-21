@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <h3>Total Agents: {{ $totalAgents }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <h3>Total Revenue: {{ $totalRevenue }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <h3>Total Subscriptions: {{ $totalSubscriptions }}</h3>
            </div>
        </div>
    </div>
@endsection