@extends('layouts.main')
@section('title', 'Register')
@section('content')
<user-verify
  error="{{$error}}">
</user-verify>
@endsection
