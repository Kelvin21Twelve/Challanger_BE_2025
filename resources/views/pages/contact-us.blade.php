@extends('layouts.app')

@section('content')
@php
  $lang =  \Session::get('lang');
  if(empty($lang)){
    $lang = "en";
  }
  app()->setLocale($lang);
@endphp
  <section class="price-container p-0 about-us-container-details">
          <div class="container">
                <div class="row front-home-pricing-plan-all-one">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 py-2">
                                    <div class="section-title text-center">
                                        <h2>{{ __('lang.Contact Us') }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-3">
                              <div class="col-xs-12 col-sm-12  col-md-12 col-lg-6 front-home-pricing-plan-all-doezz ">
                                <div class="front-viewcontactus">
                                  <form method="post" action = "{{route('coontactus_store')}}"  accept-charset="UTF-8" id="contactus">
                                        @csrf
                                        <div class="form-group">
                                          <label for="usr">{{ __('lang.Name') }}</label>
                                          <input type="text" class="form-control" name="name" placeholder="{{ __('lang.palce_name') }}" required/>
                                        </div>
                                        <div class="form-group">
                                          <label for="email"> {{ __('lang.Email') }}</label>
                                          <input type="text" class="form-control" name="email" placeholder="{{ __('lang.palce_email') }}" required/>
                                        </div>
                                        <div class="form-group">
                                          <label for="comment"> {{ __('lang.Message') }}</label>
                                          <textarea class="form-control" rows="5" name="message" placeholder="{{ __('lang.palce_message') }}" required="required"></textarea>
                                        </div>
                                        <div class="form-group contact-front-send-data">
                                        <button type="button" class="btn btn-primary" onclick="ajaxCommonSumitForm(this)">{{ __('lang.Send') }}</button>
                                        </div>
                                      </form>
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12  col-md-6 col-lg-6">
                                <div class="front-viewsecond-divcontactus">
                                  <div class="front-viewsecond-u-divcontactus">
                                    <div class="media">
                                    <div class="media-left">
                                      <i class="fa fa-map-marker" aria-hidden="true" class="media-object" ></i>
          
                                    </div>
                                    <div class="media-body">
                                      <h4 class="media-heading contect-left-us-ha">{{ __('lang.Address') }}</h4>
                                      <p>{{ __('lang.Address_info') }}</p>
                                    </div>
                                  </div>
                                  <div class="media">
                                    <div class="media-left">
                                      <i class="fa fa-phone" aria-hidden="true" class="media-object" ></i>
          
                                    </div>
                                    <div class="media-body">
                                      <h4 class="media-heading">{{ __('lang.Mobile') }}</h4>
                                      <p><a href="tel:22277240">22277240</a>  |  
                                        <a href="tel:66234660">66234660</a>  |  
                                        <a href="tel:97327979">97327979</a>  |  
                                      </p>
                                    </div>
                                  </div> 
                                  <div class="media">
                                      <div class="media-left">
                                        <i class="fa fa-email" aria-hidden="true" class="media-object" ></i>
              
                                      </div>
                                      <div class="media-body">
                                        <h4 class="media-heading">{{ __('lang.Email') }}</h4>
                                        <p><a href = "mailto:info@challenger-co.com">info@challenger-co.com</a></p>
                                      </div>
                                    </div> 
                                  </div>
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12  col-md-6 col-lg-6">
                                <div class="front-viewsecond-divcontactus">
                                  <div class="front-viewsecond-u-divcontactus">
                                    <!-- <div class="media">
                                    <div class="media-left">
                                      <i class="fa fa-whatsapp" aria-hidden="true" class="media-object" ></i>
          
          
                                    </div>
                                    <div class="media-body">
                                      <h4 class="media-heading">Whatsapp </h4>
                                      <p>+91-8888874142</p>
                                    </div>
                                  </div> -->
                                  <!-- <div class="media ">
                                    <div class="media-left">
                                      <i class="fa fa-envelope" aria-hidden="true" class="media-object" ></i>
          
                                    </div>
                                    <div class="media-body">
                                      <h4 class="media-heading contect-left-us-ha">Email id </h4>
                                      <p> ahmed.marafie@mrafie.com</p>
                                    </div>
                                  </div> -->
                                  
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-12 map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3478.3395854288788!2d47.9383587150984!3d29.331042082147494!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fcf9ab63779fe5b%3A0x7c6ff4cbd450f8c0!2z2LTYsdmD2Kkg2LTYp9mE2YbYrNixINmE2KrYtdmE2YrYrSDYp9mE2LPZitin2LHYp9iq!5e0!3m2!1sen!2sus!4v1581404082603!5m2!1sen!2sus" width:100% frameborder="0"height="450" style="border:0;" allowfullscreen=""></iframe>
                            </div>
                            </div>
                            </div>
                        </div>
                     </div>
                  </div>
              </section>
  <!-- fourth secotion view -->
  @endsection




