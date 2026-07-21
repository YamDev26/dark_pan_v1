@extends('app')
@section('title', 'dashboard')
@section('content')

  @livewire('dashboard.'.getUserDashboard())
  
@endsection