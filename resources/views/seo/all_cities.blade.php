@extends('layouts.main')
@section('title', 'Home')
@section('content')
    <div class="homepage__section homepage__section--testimonial">
        <h4 class="cities_view homepage__section__title font-weight-bold">Dove puoi fare sport? Ovunque!</h4>
        <b-row class="pl-0 pl-lg-5 pt-3">
            @foreach($cities as $city)
                <b-col class="cities_section" cols="12" sm="6" lg="3">
                    <a href="/comune/{{$city}}">{{$city}}</a>
                </b-col>
            @endforeach
        </b-row>
    </div>
@endsection