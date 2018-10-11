@extends('layouts.main')
@section('title', 'Search Results')
@section('content')
<search-view :results="{{$results->getCollection()}}"
             current_page="{{$results->currentPage()}}"
             next_page_url="{{$results->nextPageUrl()}}"
             prev_page_url="{{$results->previousPageUrl()}}"
></search-view>
@endsection
