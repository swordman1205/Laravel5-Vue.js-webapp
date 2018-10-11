@extends('layouts.main')
@section('title', 'Home')
@section('content')
    <div style="height: 100vh;"></div>
    <sportsman reservation-id="{{$reservation->id}}"></sportsman>
@endsection
