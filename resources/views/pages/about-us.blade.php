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
                                        <h2>{{ __('lang.About Us') }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-3">
                                <div class="col-md-6 wow fadeInUp">
                                    <div class="about-text">
                                        <h4>{{ __('lang.Welcome') }}</h4>
                                        <p>{{ __('lang.info') }}</p>
                                        <p> {{ __('lang.sections') }}</p>
                                        <li> {{ __('lang.kind') }}</li>
                                        <li> {{ __('lang.workshop') }}</li>
                                        <li> {{ __('lang.car_care_center') }}</li>
                                        <li> {{ __('lang.Spare_part_section') }}</li>
                                        <!-- <a href="" class="read-more">Read more</a> -->
                                    </div>
                                </div>
                                <div class="col-md-6 wow fadeInRight">
                                    <div class="about-image-area">
                                        <img src="{{URL::asset('/image/6.jpeg')}}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
              </section>
  <!-- fourth secotion view -->
  @endsection
