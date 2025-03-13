@extends('layouts.app')

@section('content')
<div class="container">
      <div class="row justify-content-center">
            <div class="col-md-8">
                  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('country.show', $country->id) }}">{{ $country->name }}</a></li>
                              <li class="breadcrumb-item active">Receiver list</li>
                        </ol>
                  </nav>
                  <div class="card">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="h5 font-weight-bold mt-1">{{$country->name}} receiver list</div>
                              </div>
                        </div>
                        <div class="card-body">
                              @if($country->receivers->count() == 0)
                                    <div class="alert alert-light" role="alert">
                                          No receivers found.
                                    </div>
                              @else
                              <div class="table-responsive">
                                    <table class="table">
                                          <thead class="table-light">
                                                <th>No.</th>
                                                <th>Name</th>
                                                <th>Zone</th>
                                                <th>Transit Day</th>
                                          </thead>
                                          <tbody>
                                                @foreach($country->receivers as $receiver)
                                                      <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $receiver->name }}</td>
                                                            <td>{{ $receiver->zone }}</td>
                                                            <td>{{ $receiver->transit_day ?? '-' }} day(s)</td>
                                                      </tr>
                                                @endforeach
                                          </tbody>
                                    </table>
                              </div>
                              @endif
                        </div>
                  </div>
            </div>
      </div>
</div>
@endsection