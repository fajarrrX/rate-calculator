@extends('layouts.app')

@section('content')
<div class="container">
      <div class="row justify-content-center">
            <div class="col-md-8">
                  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('country.show', $country->id) }}">{{ $country->name }}</a></li>
                              <li class="breadcrumb-item active">Rates</li>
                        </ol>
                  </nav>
                  <div class="card">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="h5 font-weight-bold mt-1">{{$country->name}} rates</div>
                              </div>
                        </div>
                        <div class="card-body">
                              <form action="{{ route('country.rates', $country->id) }}">
                                    <div class="fw-bold mb-1">Select Type</div>
                                    <div class="mb-3 d-flex">
                                          @php 
                                                $rate_groups =  App\Enums\RateType::asSelectArray();
                                          @endphp
                                          @foreach($rate_groups as $key => $group)
                                                <div class="me-3">
                                                      <input type="radio" id="{{$group}}" name="type" value="{{$key}}" {{Request::input('type') == $key ? 'checked' : ''}}>
                                                      <label for="{{$group}}" class="form-label">{{$group}}</label>
                                                </div>
                                          @endforeach
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter Rates</button>
                              </form>
                        </div>
                  </div>
                  @if($rates->count() == 0)
                        <div class="card mt-2">
                              <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                          <div class="h5 font-weight-bold mt-1">Result</div>
                                    </div>
                              </div>
                              <div class="card-body">
                                    <div class="alert alert-light" role="alert">
                                          No rates found.
                                    </div>
                              </div>
                        </div>
                  @else
                  <div class="card mt-2">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="h5 font-weight-bold mt-1">Result</div>
                              </div>
                        </div>
                        <div class="card-body">
                              @php
                                    $package_types = $rates->groupBy('package_type');
                              @endphp

                              @foreach($package_types as $type => $package_type)
                              <div class="mt-3">
                                    <h5 class="mb-2 text-danger fw-bold">{{ App\Enums\PackageType::fromValue($type)->description }}</h5>
                                    @php
                                    $weight_groups = $package_type->groupBy('weight');
                                    @endphp
                                    <div class="table-responsive">
                                          <table class="table">
                                                <thead class="table-light">
                                                      <tr>
                                                            <th scope="col">Weight</th>
                                                            <!-- Loop from 1 to highest zone -->
                                                            @for($zone = 1; $zone <= $highest_zone; $zone++)
                                                                  <th scope="col">Zone {{$zone}}</th>
                                                            @endfor
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                      @foreach($weight_groups as $weight => $weight_group)
                                                            <tr>
                                                                  <th scope="row">{{$weight}}</th>
                                                                  @for($zone = 1; $zone <= $highest_zone; $zone++)
                                                                        @php
                                                                              $rate = $weight_group->where('zone', $zone)->first();
                                                                        @endphp
                                                                        <td>
                                                                              @if($rate)
                                                                                    {{$rate->price}}
                                                                              @else
                                                                                    -
                                                                              @endif
                                                                        </td>
                                                                  @endfor
                                                            </tr>
                                                      @endforeach
                                                </tbody>
                                          </table>
                                    </div>
                              </div>
                              @endforeach
                        </div>
                  </div>
                  @endif
            </div>
      </div>
</div>
@endsection