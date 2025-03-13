@extends('layouts.app')

@push('scripts')
<script src="https://cdn.tiny.cloud/1/928v2n8zo0vbh93l5nyf30iqq886q98otin22xvu9vxzpwlcl/tinymce/6/tinymce.min.js" integrity="sha384-x1xU2sWA0hmS6vGzeZMqOcbQzTsv6oXVJgpuNvhGnle0c+dDYILNUlFIqyhYRmTw" crossorigin="anonymous"></script>
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
                              <li class="breadcrumb-item active">Create Country</li>
                        </ol>
                  </nav>
                  <div class="card">
                        <div class="card-header">
                              <div class="d-flex justify-content-between">
                                    <div class="h5 font-weight-bold mt-1">Add Country</div>
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

                              <form action="{{ route('country.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="form-group">
                                          <label for="name">Country Name:</label>
                                          <input type="text" name="name" id="name" value="" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="code">Country Code:</label>
                                          <input type="text" name="code" id="code" value="" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="currency_code">Currency Code:</label>
                                          <input type="text" name="currency_code" id="currency_code" value="" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="decimal_places">Decimal Places:</label>
                                          <input type="number" name="decimal_places" id="decimal_places" value="" class="form-control" required>
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="symbol_after_business_price">Symbol after Business Price:</label>
                                          <input type="text" name="symbol_after_business_price" id="symbol_after_business_price" value="" class="form-control">
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="symbol_after_personal_price">Symbol after Personal Price:</label>
                                          <input type="text" name="symbol_after_personal_price" id="symbol_after_personal_price" value="" class="form-control">
                                    </div>

                                    <div class="form-group mt-3">
                                          <label for="share_rates_with_country">Share rates with country</label>
                                          <select name="share_rates_with_country" id="share_rates_with_country" class="form-control">
                                                <option value="">Select Country</option>
                                                @php
                                                      $countries = App\Models\Country::all();
                                                @endphp
                                                @foreach($countries as $country)
                                                      <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                          </select>
                                    </div>

                                    <div class="form-group mt-3">
                                          <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="hide_package_opt_en" name="hide_package_opt_en">
                                                <label class="form-check-label" for="hide_package_opt_en">
                                                    Hide Package Option (English)
                                                </label>
                                          </div>

                                          <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="hide_package_opt_local" name="hide_package_opt_local">
                                                <label class="form-check-label" for="hide_package_opt_local">
                                                     Hide Package Option (Local)
                                                </label>
                                          </div>

                                          <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="hide_step_1" name="hide_step_1">
                                                <label class="form-check-label" for="hide_step_1">
                                                     Hide Step 1 (Personal Details)
                                                </label>
                                          </div>
                                    </div>

                                    <hr />

                                    <h5 class="mt-3 bg-light p-2 rounded rounded-lg">Business Section</h5>
                                    
                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_title_en"><strong>Title</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_title_en" id="business_title_en" value="Business Shipper?" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_title_local"><strong>Title</strong> (Local)</label>
                                                <input type="text" name="business_title_local" id="business_title_local" placeholder="ex: Business Shipper" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_content_en"><strong>Content</strong> (English)</label>
                                                <textarea name="business_content_en" id="business_content_en" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_content_local"><strong>Content</strong> (Local)</label>
                                                <textarea name="business_content_local" id="business_content_local" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_additional_list_en"><strong>Additional List</strong> (English)</label>
                                                <input type="text" name="business_additional_list_en" id="business_additional_list_en" value="Shipment Supplies || Live Tracking" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_additional_list_local"><strong>Additional List</strong> (Local)</label>
                                                <input type="text" name="business_additional_list_local" id="business_additional_list_local" placeholder="ex: Shipment Supplies || Live Tracking" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_additional_list_value_en"><strong>Additional List Value</strong> (English)</label>
                                                <input type="text" name="business_additional_list_value_en" id="business_additional_list_value_en" value="FREE || FREE" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_additional_list_value_local"><strong>Additional List Value</strong> (Local)</label>
                                                <input type="text" name="business_additional_list_value_local" id="business_additional_list_value_local" placeholder="ex: FREE || FREE" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_condition_list_en"><strong>Condition List</strong> (English)</label>
                                                <input type="text" name="business_condition_list_en" id="business_condition_list_en" value="^Applicable for new customers only || *You will receive your DHL account within 2 hours for submission made during office hours." class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_condition_list_local"><strong>Condition List</strong> (Local)</label>
                                                <input type="text" name="business_condition_list_local" id="business_condition_list_local" placeholder="ex: ^Applicable for new customers only || *You will receive your DHL account within 2 hours for submission made during office hours." class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_en"><strong>CTA Button Text</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_text_en" id="business_cta_btn_text_en" value="Open Business Account" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_local"><strong>CTA Button Text</strong> (Local)</label>
                                                <input type="text" name="business_cta_btn_text_local" id="business_cta_btn_text_local" placeholder="ex: Open Business Account" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_en"><strong>CTA Button Link</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_link_en" id="business_cta_btn_link_en" value="" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_local"><strong>CTA Button Link</strong> (Local) <span class="text-danger">*</span></label>
                                                <input type="text" name="business_cta_btn_link_local" id="business_cta_btn_link_local" value="" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_2_en"><strong>CTA Button Text 2</strong> (English)</label>
                                                <input type="text" name="business_cta_btn_text_2_en" id="business_cta_btn_text_2_en" value="Open Business Account" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_text_2_local"><strong>CTA Button Text</strong> (Local)</label>
                                                <input type="text" name="business_cta_btn_text_2_local" id="business_cta_btn_text_2_local" placeholder="ex: Open Business Account" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_2_en"><strong>CTA Button Link</strong> (English)</label>
                                                <input type="text" name="business_cta_btn_link_2_en" id="business_cta_btn_link_2_en" value="" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="business_cta_btn_link_2_local"><strong>CTA Button Link</strong> (Local)</label>
                                                <input type="text" name="business_cta_btn_link_2_local" id="business_cta_btn_link_2_local" value="" class="form-control">
                                          </div>
                                    </div>


                                    <hr />


                                    <h5 class="mt-3 bg-light p-2 rounded rounded-lg">Personal Section</h5>
                                    
                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_title_en"><strong>Title</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_title_en" id="personal_title_en" value="Ready to ship now?" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_title_local"><strong>Title</strong> (Local)</label>
                                                <input type="text" name="personal_title_local" id="personal_title_local" placeholder="ex: Business Shipper" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_content_en"><strong>Content</strong> (English)</label>
                                                <textarea name="personal_content_en" id="personal_content_en" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_content_local"><strong>Content</strong> (Local)</label>
                                                <textarea name="personal_content_local" id="personal_content_local" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_en"><strong>Additional List</strong> (English)</label>
                                                <input type="text" name="personal_additional_list_en" id="personal_additional_list_en" value="Shipment Supplies || Live Tracking" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_local"><strong>Additional List</strong> (Local)</label>
                                                <input type="text" name="personal_additional_list_local" id="personal_additional_list_local" placeholder="ex: Shipment Supplies || Live Tracking" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_value_en"><strong>Additional List Value</strong> (English)</label>
                                                <input type="text" name="personal_additional_list_value_en" id="personal_additional_list_value_en" value="FREE || FREE" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_additional_list_value_local"><strong>Additional List Value</strong> (Local)</label>
                                                <input type="text" name="personal_additional_list_value_local" id="personal_additional_list_value_local" placeholder="ex: FREE || FREE" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_condition_list_en"><strong>Condition List</strong> (English)</label>
                                                <input type="text" name="personal_condition_list_en" id="personal_condition_list_en" value="*You will receive an email within an hour to complete the booking process for submission made during office hours." class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_condition_list_local"><strong>Condition List</strong> (Local)</label>
                                                <input type="text" name="personal_condition_list_local" id="personal_condition_list_local" placeholder="ex: *You will receive an email within an hour to complete the booking process for submission made during office hours." class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_en"><strong>CTA Button Text</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_cta_btn_text_en" id="personal_cta_btn_text_en" value="Submit a booking request*" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_local"><strong>CTA Button Text</strong> (Local)</label>
                                                <input type="text" name="personal_cta_btn_text_local" id="personal_cta_btn_text_local" placeholder="ex: Submit a booking request*" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_link_en"><strong>CTA Button URL</strong> (English) <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_cta_btn_link_en" id="personal_cta_btn_link_en" value="" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_link_local"><strong>CTA Button URL</strong> (Local)</label>
                                                <input type="text" name="personal_cta_btn_link_local" id="personal_cta_btn_link_local" value="" placeholder="(if any)" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_2_en"><strong>CTA Button Text 2</strong> (English)</label>
                                                <input type="text" name="personal_cta_btn_text_2_en" id="personal_cta_btn_text_2_en" value="Chat with us to book" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_text_2_local"><strong>CTA Button Text 2</strong> (Local)</label>
                                                <input type="text" name="personal_cta_btn_text_2_local" id="personal_cta_btn_text_2_local" placeholder="ex: Chat with us to book" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_2_link_en"><strong>CTA Button 2 URL</strong> <span class="text-danger">*</span></label>
                                                <input type="text" name="personal_cta_btn_2_link_en" id="personal_cta_btn_2_link_en" value="" class="form-control">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_cta_btn_2_link_local"><strong>CTA Button 2 URL</strong></label>
                                                <input type="text" name="personal_cta_btn_2_link_local" id="personal_cta_btn_2_link_local" value="" placeholder="(if any)" class="form-control">
                                          </div>
                                    </div>

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="personal_caption_en"><strong>Caption</strong> (English)</label>
                                                <textarea name="personal_caption_en" id="personal_caption_en" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="personal_caption_local"><strong>Caption</strong> (Local)</label>
                                                <textarea name="personal_caption_local" id="personal_caption_local" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                    </div>

                                    <hr />

                                    <div class="row mt-3">
                                          <div class="col-md-6">
                                                <label for="footer_en"><strong>Footer</strong> (English)</label>
                                                <textarea name="footer_en" id="footer_en" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                          <div class="col-md-6">
                                                <label for="footer_local"><strong>Footer</strong> (Local)</label>
                                                <textarea name="footer_local" id="footer_local" cols="5" rows="3" class="form-control"></textarea>
                                          </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                          <button type="submit" class="btn btn-primary mt-3">Create</button>
                                          <a href="{{ route('home') }}" class="btn btn-secondary mt-3">Cancel</a>
                                    </div>
                              </form>
                        </div>
                  </div>
            </div>
      </div>
</div>
@endsection