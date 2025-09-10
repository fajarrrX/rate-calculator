@extends('layouts.app')

@push('scripts')
<script src="https://cdn.tiny.cloud/1/0dit4skicnmru1blbiabh55hw96759ugygfkbn1r4jnx45b9/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
      tinymce.init({
            selector: 'textarea',
            plugins: 'link',
            toolbar: 'bold italic underline strikethrough | link | align | numlist bullist | forecolor backcolor',
            menubar: false,
      });
</script>
@endpush

@section('content')
<div class="container">
      <div class="row justify-content-center">
            <div class="col-md-8">
                  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                              <li class="breadcrumb-item"><a href="{{ route('country.show', $country->id) }}">{{ $country->name }}</a></li>
                              <li class="breadcrumb-item active">Edit</li>
                        </ol>
                  </nav>
                  <div class="card">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="h5 font-weight-bold mt-1">Edit Country</div>
                              </div>
                        </div>
                        <div class="card-body">
                              
                              @if(session('success'))
                                    <div class="alert alert-success">
                                          {{ session('success') }}
                                    </div>
                              @endif
                              @if($errors->any())
                                    <div class="alert alert-danger">
                                          <ul>
                                                @foreach($errors->all() as $error)
                                                      <li>{{ $error }}</li>
                                                @endforeach
                                          </ul>
                                    </div>
                              @endif

                              <form action="{{ route('country.update', $country->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group">
                                          <label for="name">Country Name:</label>
                                          <input type="text" name="name" id="name" value="{{ $country->name }}" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="code">Country Code:</label>
                                          <input type="text" value="{{ $country->code }}" class="form-control" disabled required>
                                          <input type="hidden" value="{{ $country->code }}" name="code">
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="currency_code">Currency Code:</label>
                                          <input type="text" name="currency_code" id="currency_code" value="{{ $country->currency_code }}" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="decimal_places">Decimal Places:</label>
                                          <input type="number" name="decimal_places" id="decimal_places" value="{{ $country->decimal_places }}" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="symbol_after_business_price">Symbol after Business Price:</label>
                                          <input type="text" name="symbol_after_business_price" id="symbol_after_business_price" value="{{ $country->symbol_after_business_price }}" class="form-control">
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="symbol_after_personal_price">Symbol after Personal Price:</label>
                                          <input type="text" name="symbol_after_personal_price" id="symbol_after_personal_price" value="{{ $country->symbol_after_personal_price }}" class="form-control">
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="share_rates_with_country">Share rates with country</label>
                                          <select name="share_rates_with_country" id="share_rates_with_country" class="form-control">
                                                <option value="">Select Country</option>
                                                @php
                                                      $countries = App\Models\Country::all();
                                                @endphp
                                                @foreach($countries as $_country)
                                                      <option value="{{ $_country->id }}" {{$_country->id == $country->share_country_id ? 'selected' : ''}}>{{ $_country->name }}</option>
                                                @endforeach
                                          </select>
                                    </div>

                                    <div class="form-group mt-3">
                                          <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="hide_package_opt_en" id="hide_package_opt_en" {{$country->hide_package_opt_en ? 'checked' : ''}}>
                                                <label class="form-check-label" for="hide_package_opt_en">
                                                    Hide Package Option (English)
                                                </label>
                                          </div>

                                          <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="hide_package_opt_local" id="hide_package_opt_local" {{$country->hide_package_opt_local ? 'checked' : ''}}>
                                                <label class="form-check-label" for="hide_package_opt_local">
                                                     Hide Package Option (Local)
                                                </label>
                                          </div>

                                          <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" name="hide_step_1" id="hide_step_1" {{$country->hide_step_1 ? 'checked' : ''}}>
                                                <label class="form-check-label" for="hide_step_1">
                                                     Hide Step 1 (Personal Details)
                                                </label>
                                          </div>
                                    </div>

                                    <hr />

                                    <h5 class="mt-3 bg-light p-2 rounded rounded-lg">Business Section</h5>

                                    @php
                                          $is_set = $country->quote_langs ? true : false;

                                          $business_title_en = $is_set ? $country->quote_langs()->where('name', 'business_title_en')->first() : '';
                                          $business_title_local = $is_set ? $country->quote_langs()->where('name', 'business_title_local')->first() : '';
                                          $business_content_en = $is_set ? $country->quote_langs()->where('name', 'business_content_en')->first() : '';
                                          $business_content_local = $is_set ? $country->quote_langs()->where('name', 'business_content_local')->first() : '';
                                          $business_additional_list_en = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_en')->first() : '';
                                          $business_additional_list_local = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_local')->first() : '';
                                          $business_additional_list_value_en = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_value_en')->first() : '';
                                          $business_additional_list_value_local = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_value_local')->first() : '';
                                          $business_condition_list_en = $is_set ? $country->quote_langs()->where('name', 'business_condition_list_en')->first() : '';
                                          $business_condition_list_local = $is_set ? $country->quote_langs()->where('name', 'business_condition_list_local')->first() : '';
                                          $business_cta_btn_text_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_en')->first() : '';
                                          $business_cta_btn_text_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_local')->first() : '';
                                          $business_cta_btn_link_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_en')->first() : '';
                                          $business_cta_btn_link_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_local')->first() : '';
                                          $business_cta_btn_text_2_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_2_en')->first() : '';
                                          $business_cta_btn_text_2_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_2_local')->first() : '';
                                          $business_cta_btn_link_2_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_2_en')->first() : '';
                                          $business_cta_btn_link_2_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_2_local')->first() : '';
                                          

                                          $personal_title_en = $is_set ? $country->quote_langs()->where('name', 'personal_title_en')->first() : '';
                                          $personal_title_local = $is_set ? $country->quote_langs()->where('name', 'personal_title_local')->first() : '';
                                          $personal_content_en = $is_set ? $country->quote_langs()->where('name', 'personal_content_en')->first() : '';
                                          $personal_content_local = $is_set ? $country->quote_langs()->where('name', 'personal_content_local')->first() : '';
                                          $personal_additional_list_en = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_en')->first() : '';
                                          $personal_additional_list_local = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_local')->first() : '';
                                          $personal_additional_list_value_en = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_value_en')->first() : '';
                                          $personal_additional_list_value_local = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_value_local')->first() : '';
                                          $personal_condition_list_en = $is_set ? $country->quote_langs()->where('name', 'personal_condition_list_en')->first() : '';
                                          $personal_condition_list_local = $is_set ? $country->quote_langs()->where('name', 'personal_condition_list_local')->first() : '';
                                          $personal_cta_btn_text_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_en')->first() : '';
                                          $personal_cta_btn_text_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_local')->first() : '';
                                          $personal_cta_btn_link_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_link_en')->first() : '';
                                          $personal_cta_btn_link_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_link_local')->first() : '';
                                          $personal_cta_btn_text_2_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_2_en')->first() : '';
                                          $personal_cta_btn_text_2_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_2_local')->first() : '';
                                          $personal_cta_btn_2_link_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_2_link_en')->first() : '';
                                          $personal_cta_btn_2_link_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_2_link_local')->first() : '';
                                          $personal_caption_en = $is_set ? $country->quote_langs()->where('name', 'personal_caption_en')->first() : '';
                                          $personal_caption_local = $is_set ? $country->quote_langs()->where('name', 'personal_caption_local')->first() : '';

                                          $footer_en = $is_set ? $country->quote_langs()->where('name', 'footer_en')->first() : '';
                                          $footer_local = $is_set ? $country->quote_langs()->where('name', 'footer_local')->first() : '';
                                    @endphp
                                    
                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_title_en"><strong>Title</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_title_en" id="business_title_en" value="{{$business_title_en ? $business_title_en->description : ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_title_local"><strong>Title</strong> (Local)</label>
                                                <input type="text" name="business_title_local" id="business_title_local" value="{{$business_title_local ? $business_title_local->description: ''}}" placeholder="ex: Business Shipper" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_content_en"><strong>Content</strong> (English)</label>
                                                <textarea name="business_content_en" id="business_content_en" cols="5" rows="3" class="form-control">{{$business_content_en ? $business_content_en->description : ''}}</textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_content_local"><strong>Content</strong> (Local)</label>
                                                <textarea name="business_content_local" id="business_content_local" cols="5" rows="3" class="form-control">{{$business_content_local ? $business_content_local->description : ''}}</textarea>
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_additional_list_en"><strong>Additional List</strong> (English)</label>
                                                <input type="text" name="business_additional_list_en" id="business_additional_list_en" value="{{$business_additional_list_en ? $business_additional_list_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_additional_list_local"><strong>Additional List</strong> (Local)</label>
                                                <input type="text" name="business_additional_list_local" id="business_additional_list_local" value="{{$business_additional_list_local ? $business_additional_list_local->description: ''}}" placeholder="ex: Shipment Supplies || Live Tracking" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_additional_list_value_en"><strong>Additional List Value</strong> (English)</label>
                                                <input type="text" name="business_additional_list_value_en" id="business_additional_list_value_en" value="{{$business_additional_list_value_en ? $business_additional_list_value_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_additional_list_value_local"><strong>Additional List Value</strong> (Local)</label>
                                                <input type="text" name="business_additional_list_value_local" id="business_additional_list_value_local" value="{{$business_additional_list_value_local ? $business_additional_list_value_local->description: ''}}" placeholder="ex: FREE || FREE" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_condition_list_en"><strong>Condition List</strong> (English)</label>
                                                <input type="text" name="business_condition_list_en" id="business_condition_list_en" value="{{$business_condition_list_en ? $business_condition_list_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_condition_list_local"><strong>Condition List</strong> (Local)</label>
                                                <input type="text" name="business_condition_list_local" id="business_condition_list_local" value="{{$business_condition_list_local ? $business_condition_list_local->description: ''}}" placeholder="ex: ^Applicable for new customers only || *You will receive your DHL account within 2 hours for submission made during office hours." class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_en"><strong>CTA Button Text</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_text_en" id="business_cta_btn_text_en" value="{{$business_cta_btn_text_en ? $business_cta_btn_text_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_local"><strong>CTA Button Text</strong> (Local)</label>
                                                <input type="text" name="business_cta_btn_text_local" id="business_cta_btn_text_local" value="{{$business_cta_btn_text_local ? $business_cta_btn_text_local->description: ''}}" placeholder="ex: Open Business Account" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_en"><strong>CTA Button Link</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_link_en" id="business_cta_btn_link_en" value="{{$business_cta_btn_link_en ? $business_cta_btn_link_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_local"><strong>CTA Button Link</strong> (Local) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_link_local" id="business_cta_btn_link_local" value="{{$business_cta_btn_link_local ? $business_cta_btn_link_local->description: ''}}" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_2_en"><strong>CTA Button Text 2</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_text_2_en" id="business_cta_btn_text_2_en" value="{{$business_cta_btn_text_2_en ? $business_cta_btn_text_2_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_2_local"><strong>CTA Button Text 2</strong> (Local)</label>
                                                <input type="text" name="business_cta_btn_text_2_local" id="business_cta_btn_text_2_local" value="{{$business_cta_btn_text_2_local ? $business_cta_btn_text_2_local->description: ''}}" placeholder="ex: Open Business Account" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_2_en"><strong>CTA Button Link 2</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_link_2_en" id="business_cta_btn_link_2_en" value="{{$business_cta_btn_link_2_en ? $business_cta_btn_link_2_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_2_local"><strong>CTA Button Link 2</strong> (Local) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_link_2_local" id="business_cta_btn_link_2_local" value="{{$business_cta_btn_link_2_local ? $business_cta_btn_link_2_local->description: ''}}" class="form-control">
                                          </div>
                                    </div>

                                    <hr />

                                    <h5 class="mt-3 bg-light p-2 rounded rounded-lg">Personal Section</h5>
                                    
                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_title_en"><strong>Title</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_title_en" id="personal_title_en" value="{{$personal_title_en ? $personal_title_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_title_local"><strong>Title</strong> (Local)</label>
                                                <input type="text" name="personal_title_local" id="personal_title_local" value="{{$personal_title_local ? $personal_title_local->description: ''}}" placeholder="ex: Business Shipper" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_content_en"><strong>Content</strong> (English)</label>
                                                <textarea name="personal_content_en" id="personal_content_en" cols="5" rows="3" class="form-control">{{$personal_content_en ? $personal_content_en->description : ''}}</textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_content_local"><strong>Content</strong> (Local)</label>
                                                <textarea name="personal_content_local" id="personal_content_local" cols="5" rows="3" class="form-control">{{$personal_content_local ? $personal_content_local->description : ''}}</textarea>
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_en"><strong>Additional List</strong> (English)</label>
                                                <input type="text" name="personal_additional_list_en" id="personal_additional_list_en" value="{{$personal_additional_list_en ? $personal_additional_list_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_local"><strong>Additional List</strong> (Local)</label>
                                                <input type="text" name="personal_additional_list_local" id="personal_additional_list_local" value="{{$personal_additional_list_local ? $personal_additional_list_local->description: ''}}" placeholder="ex: Shipment Supplies || Live Tracking" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_value_en"><strong>Additional List Value</strong> (English)</label>
                                                <input type="text" name="personal_additional_list_value_en" id="personal_additional_list_value_en" value="{{$personal_additional_list_value_en ? $personal_additional_list_value_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_value_local"><strong>Additional List Value</strong> (Local)</label>
                                                <input type="text" name="personal_additional_list_value_local" id="personal_additional_list_value_local" value="{{$personal_additional_list_value_local ? $personal_additional_list_value_local->description: ''}}" placeholder="ex: FREE || FREE" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_condition_list_en"><strong>Condition List</strong> (English)</label>
                                                <input type="text" name="personal_condition_list_en" id="personal_condition_list_en" value="{{$personal_condition_list_en ? $personal_condition_list_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_condition_list_local"><strong>Condition List</strong> (Local)</label>
                                                <input type="text" name="personal_condition_list_local" id="personal_condition_list_local" value="{{$personal_condition_list_local ? $personal_condition_list_local->description: ''}}" placeholder="ex: *You will receive an email within an hour to complete the booking process for submission made during office hours." class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_en"><strong>CTA Button Text</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_cta_btn_text_en" id="personal_cta_btn_text_en" value="{{$personal_cta_btn_text_en ? $personal_cta_btn_text_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_local"><strong>CTA Button Text</strong> (Local)</label>
                                                <input type="text" name="personal_cta_btn_text_local" id="personal_cta_btn_text_local" value="{{$personal_cta_btn_text_local ? $personal_cta_btn_text_local->description: ''}}" placeholder="ex: Submit a booking request*" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_link_en"><strong>CTA Button URL</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_cta_btn_link_en" id="personal_cta_btn_link_en" value="{{$personal_cta_btn_link_en ? $personal_cta_btn_link_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_link_local"><strong>CTA Button URL</strong> (Local) </label>
                                                <input type="text" name="personal_cta_btn_link_local" id="personal_cta_btn_link_local" value="{{$personal_cta_btn_link_local ? $personal_cta_btn_link_local->description: ''}}" placeholder="(if any)" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_2_en"><strong>CTA Button Text 2</strong> (English)</label>
                                                <input type="text" name="personal_cta_btn_text_2_en" id="personal_cta_btn_text_2_en" value="{{$personal_cta_btn_text_2_en ? $personal_cta_btn_text_2_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_2_local"><strong>CTA Button Text 2</strong> (Local)</label>
                                                <input type="text" name="personal_cta_btn_text_2_local" id="personal_cta_btn_text_2_local" value="{{$personal_cta_btn_text_2_local ? $personal_cta_btn_text_2_local->description: ''}}" placeholder="ex: Chat with us to book" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_2_link_en"><strong>CTA Button 2 URL</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_cta_btn_2_link_en" id="personal_cta_btn_2_link_en" value="{{$personal_cta_btn_2_link_en ? $personal_cta_btn_2_link_en->description: ''}}" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_2_link_local"><strong>CTA Button 2 URL</strong> (Local)</label>
                                                <input type="text" name="personal_cta_btn_2_link_local" id="personal_cta_btn_2_link_local" value="{{$personal_cta_btn_2_link_local ? $personal_cta_btn_2_link_local->description: ''}}" placeholder="(if any)" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_caption_en"><strong>Caption</strong> (English)</label>
                                                <textarea name="personal_caption_en" id="personal_caption_en" cols="5" rows="3" class="form-control">{{ $personal_caption_en ? $personal_caption_en->description : '' }}</textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_caption_local"><strong>Caption</strong> (Local)</label>
                                                <textarea name="personal_caption_local" id="personal_caption_local" cols="5" rows="3" class="form-control">{{ $personal_caption_local ? $personal_caption_local->description : '' }}</textarea>
                                          </div>
                                    </div>

                                    <hr />

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="footer_en"><strong>Footer</strong> (English)</label>
                                                <textarea name="footer_en" id="footer_en" cols="5" rows="3" class="form-control">{{$footer_en ? $footer_en->description : ''}}</textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="footer_local"><strong>Footer</strong> (Local)</label>
                                                <textarea name="footer_local" id="footer_local" cols="5" rows="3" class="form-control">{{$footer_local ? $footer_local->description : ''}}</textarea>
                                          </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                          <button type="submit" class="btn btn-primary mt-3">Update</button>
                                          <a href="{{ route('country.show', $country->id) }}" class="btn btn-secondary mt-3">Cancel</a>
                                    </div>
                              </form>
                        </div>
                  </div>
            </div>
      </div>
</div>
@endsection