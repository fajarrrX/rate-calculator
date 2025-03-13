@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Home</li>
                </ol>
            </nav>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="h5 font-weight-bold mt-1">{{ __('Select Country') }}</div>
                        <a href="{{ route('country.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row align-items-md-stretch">
                    @foreach($countries as $key => $country)
                        <div class="col-md-6 mb-3">
                            <div class="h-100 p-5 {{$key % 2 == 0 ? 'text-bg-dark' : 'bg-body-tertiary'}} rounded-3">
                                <h2>{{$country->name}}</h2>
                                <p>Explore rates worldwide! Choose a country and discover personalized rates just for you.</p>
                                <a href="{{ route('country.show', $country->id) }}" class="btn {{$key % 2 == 0 ? 'btn-outline-light' : 'btn-outline-secondary' }}" type="button">View Details</a>

                                <div class="mt-2 small">
                                    {{$country->receivers ? $country->receivers->count() : 0}} available countries
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
