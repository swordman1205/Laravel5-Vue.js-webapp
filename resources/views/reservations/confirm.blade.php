@extends('layouts.main')
@section('title', 'Home')
@section('content')
  <div style="height: 100vh;"></div>
  <reservation-success :course="{{$reservation->course}}" :reservation="{{$reservation}}">
  </reservation-success>
@endsection
