@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('dropify/css/dropify.css' )}}">
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
                            <h2>{{ __('lang.add_image') }}</h2>
                        </div>
                    </div>
                    <div class="col-sm-12 py-2">
                        <div class="col-xs-12 col-sm-12  col-md-12 col-lg-12 front-home-pricing-plan-all-doezz ">
                            <div class="front-view add_image">
                                <form method="post" action = "{{route('gallery_image_store')}}"   data-route="{{url('/')}}" accept-charset="UTF-8" id="gallery_image_store" enctype='multipart/form-data'>
                                        @csrf
                                    <input type="hidden" class="form-control"  name="id" id="id" value="@if(isset($datas->id)){{en_de_crypt($datas->id,'e')}}@endif">
                                    <div class="form-group">
                                        <label for="usr">{{ __('lang.gallery_image') }}</label>
                                        <input type="hidden" name="g_img" value="@if(isset($datas->image)) {{$datas->image}} @endif">
                                        <input type="file" name="g_image" id="g_image"  class="dropify" @if(isset($datas->image)) data-default-file="{{asset('/image')}}/{{$datas->image}}" @endif data-allowed-file-extensions="png jpg jpeg" value>  
                                    </div>
                                    <div class="form-group contact-front-send-data">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col">
                                                  <button type="button" class="btn btn-primary" onclick="ajaxCommonSubmitForm(this)">{{ __('lang.Send') }}</button>
                                                </div>
                                                <div class="col">
                                                    <a type="button"  style="width:100% !important" class="btn btn-success" href="{{route('add-image')}}">{{ __('lang.Back') }}</a>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
</section>
<script src="{{ asset('dropify/js/dropify.js' )}}"></script>
<script type="text/javascript">
 $('.dropify').dropify();
</script> 
@endsection

  



