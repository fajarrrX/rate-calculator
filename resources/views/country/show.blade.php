@extends('layouts.app')

@section('content')
<div class="container">
      <div class="row justify-content-center">
            <div class="col-md-8">
                  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                              <li class="breadcrumb-item active">{{ $country->name }}</li>
                        </ol>
                  </nav>
                  <div class="card">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="h5 font-weight-bold mt-1">{{$country->name}}</div>
                                    <div class="d-flex">
                                          <a href="{{route('country.edit', $country->id)}}" class="btn btn-sm btn-primary me-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="14" width="14" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#ffffff" d="M441 58.9L453.1 71c9.4 9.4 9.4 24.6 0 33.9L424 134.1 377.9 88 407 58.9c9.4-9.4 24.6-9.4 33.9 0zM209.8 256.2L344 121.9 390.1 168 255.8 302.2c-2.9 2.9-6.5 5-10.4 6.1l-58.5 16.7 16.7-58.5c1.1-3.9 3.2-7.5 6.1-10.4zM373.1 25L175.8 222.2c-8.7 8.7-15 19.4-18.3 31.1l-28.6 100c-2.4 8.4-.1 17.4 6.1 23.6s15.2 8.5 23.6 6.1l100-28.6c11.8-3.4 22.5-9.7 31.1-18.3L487 138.9c28.1-28.1 28.1-73.7 0-101.8L474.9 25C446.8-3.1 401.2-3.1 373.1 25zM88 64C39.4 64 0 103.4 0 152V424c0 48.6 39.4 88 88 88H360c48.6 0 88-39.4 88-88V312c0-13.3-10.7-24-24-24s-24 10.7-24 24V424c0 22.1-17.9 40-40 40H88c-22.1 0-40-17.9-40-40V152c0-22.1 17.9-40 40-40H200c13.3 0 24-10.7 24-24s-10.7-24-24-24H88z"/></svg>
                                          </a>

                                          <form action="{{ route('country.destroy', $country->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger pb-2">
                                                      <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#ffffff" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                                </button>
                                          </form>
                                    </div>
                              </div>
                        </div>
                        <div class="card-body">
                              @if($country->share_country)
                                    <div class="mt-2">
                                          <a href="{{ route('country.rates', $country->id) }}">{{$country->share_country->rates->count()}} rates</a> available <br/>
                                          <a href="{{ route('country.receivers', $country->id) }}">{{$country->share_country->receivers->count()}} receivers</a> available
                                          <div>Share rates with <a href="{{ route('country.show', $country->share_country->id) }}">{{$country->share_country->name}}</a></div>
                                    </div>
                              @else
                                    <div class="mt-2">
                                          <a href="{{ route('country.rates', $country->id) }}">{{$country->rates->count()}} rates</a> available <br/>
                                          <a href="{{ route('country.receivers', $country->id) }}">{{$country->receivers->count()}} receivers</a> available
                                    </div>
                              @endif
                        </div>
                  </div>

                  <div class="card mt-3">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="font-weight-bold mt-1">Upload Ratecard</div>
                              </div>
                        </div>
                        <div class="card-body">
                              <div class="p-1">
                                    @if(session('success'))
                                          <div class="alert alert-success">
                                                {{ session('success') }}
                                          </div>
                                    @endif
                                    @if(session('error'))
                                          <div class="alert alert-danger">
                                                {{ session('error') }}
                                          </div>
                                    @endif
                              </div>

                              <form action="{{ route('rate.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="country_id" value="{{ $country->id }}">
                                    <div class="mb-3 d-flex">
                                          @php 
                                                $rates = App\Enums\RateType::asSelectArray();
                                          @endphp
                                          @foreach($rates as $key => $rate)
                                                <div class="me-3">
                                                      <input type="radio" id="{{$key}}" name="type" value="{{$key}}" {{$key == App\Enums\RateType::Original ? 'checked' : ''}}>
                                                      <label for="{{$key}}" class="form-label">{{$rate}}</label>
                                                </div>
                                          @endforeach
                                    </div>
                                    <div class="mb-3">
                                          <label for="file" class="form-label">Select File:</label>
                                          <input type="file" class="form-control" id="file" name="file">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                              </form>
                        </div>
                  </div>
                  @if($ratecards->count() > 0)
                  <div class="card mt-3">
                        <div class="card-header">
                              Uploaded Ratecards
                        </div>
                        <div class="card-body">
                              @php
                                    $original = $ratecards->where('type', App\Enums\RateType::Original);
                                    $personal = $ratecards->where('type', App\Enums\RateType::Personal);
                                    $business = $ratecards->where('type', App\Enums\RateType::Business);
                              @endphp
                                                            
                              <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingOriginal">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOriginal" aria-expanded="false" aria-controls="collapseOriginal">
                                                      Original Ratecards
                                                </button>
                                          </h2>
                                          <div id="collapseOriginal" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                      <div class="list-group">
                                                            @foreach($original as $key => $file)
                                                            <form action="{{route('rate.download')}}" method="POST">
                                                                  @csrf
                                                                  <input type="hidden" value="{{$file->id}}" name="file_id" />
                                                                  <button class="list-group-item list-group-item-action" aria-current="true">
                                                                        <strong>{{$file->name}}</strong> uploaded at {{$file->created_at->isoFormat('lll')}}
                                                                  </button>
                                                            </form>
                                                            @endforeach
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingPersonal">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="false" aria-controls="collapsePersonal">
                                                      Personal Ratecards
                                                </button>
                                          </h2>
                                          <div id="collapsePersonal" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                      <div class="list-group">
                                                            @foreach($personal as $key => $file)
                                                            <form action="{{route('rate.download')}}" method="POST">
                                                                  @csrf
                                                                  <input type="hidden" value="{{$file->id}}" name="file_id" />
                                                                  <button class="list-group-item list-group-item-action" aria-current="true">
                                                                        <strong>{{$file->name}}</strong> uploaded at {{$file->created_at->isoFormat('lll')}}
                                                                  </button>
                                                            </form>
                                                            @endforeach
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="accordion-item">
                                          <h2 class="accordion-header" id="headingBusiness">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBusiness" aria-expanded="false" aria-controls="collapseBusiness">
                                                      Business Ratecards
                                                </button>
                                          </h2>
                                          <div id="collapseBusiness" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                      <div class="list-group">
                                                            @foreach($business as $key => $file)
                                                            <form action="{{route('rate.download')}}" method="POST">
                                                                  @csrf
                                                                  <input type="hidden" value="{{$file->id}}" name="file_id" />
                                                                  <button class="list-group-item list-group-item-action" aria-current="true">
                                                                        <strong>{{$file->name}}</strong> uploaded at {{$file->created_at->isoFormat('lll')}}
                                                                  </button>
                                                            </form>
                                                            @endforeach
                                                      </div>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  @endif
            </div>
      </div>
</div>
@endsection
