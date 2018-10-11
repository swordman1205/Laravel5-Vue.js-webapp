@extends('layouts.main')
@section('title', 'Companies')
@section('content')
    <div class="row companies-list">
        @foreach($companies as $company)
            <div class="col-lg-3 col-xs-12 col-sm-12 col-md-6 companies-list__list-item">
                <a href="/societa_sportive/{{$company->slug}}/dashboard">{{$company->name}}</a>
            </div>
        @endforeach
    </div>

@endsection
