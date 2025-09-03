 @extends('layouts.app')

@section('content')
@php
  $lang =  \Session::get('lang');
  if(empty($lang)){
    $lang = "en";
  }
  app()->setLocale($lang);
@endphp
<section class="clearfix  welcomediv mh100-fist-one pt-0">
      <!-- <div class="container background-image-section-one">
        <div class="row justify-content-center align-self-center" style="margin:0 auto;"> -->
                      <!--Carousel Wrapper-->
          <div id="carousel-example-1z" class="carousel slide carousel-fade" data-ride="carousel">
           <!--Indicators-->
           <ol class="carousel-indicators">
             <li data-target="#carousel-example-1z" data-slide-to="0" class="active"></li>
             <li data-target="#carousel-example-1z" data-slide-to="1"></li>
             <li data-target="#carousel-example-1z" data-slide-to="2"></li>
           </ol>
           <!--/.Indicators-->
           <!--Slides-->
           <div class="carousel-inner" role="listbox">
             <!--First slide-->
             <div class="carousel-item active">
               <img class="d-block w-100" src="{{URL::asset('/image/4.jpeg')}}"
                 alt="First slide">
             </div>
             <!--/First slide-->
             <!--Second slide-->
             <div class="carousel-item">
               <img class="d-block w-100" src="{{URL::asset('/image/5.jpeg')}}"
                 alt="First slide">
             </div>
             <!--/Second slide-->
             <!--Third slide-->
             <div class="carousel-item">
               <img class="d-block w-100" src="{{URL::asset('/image/2.jpeg')}}"
                 alt="First slide">
             </div>
             <!--/Third slide-->
           </div>
           <!--/.Slides-->
           <!--Controls-->
           <a class="carousel-control-prev" data-target="#carousel-example-1z" role="button" data-slide="prev">
             <span class="carousel-control-prev-icon" aria-hidden="true"></span>
             <span class="sr-only">Previous</span>
           </a>
           <a class="carousel-control-next" data-target="#carousel-example-1z" role="button" data-slide="next">
             <span class="carousel-control-next-icon" aria-hidden="true"></span>
             <span class="sr-only">Next</span>
           </a>
           <!--/.Controls-->
          <!-- </div>

        </div> -->
      </div>
        <!-- <div class="container background-image-section-one">
          <div class="row justify-content-center align-self-center" style="margin:0 auto;">
            <div class="col-md-12 middle-sction-header">
              <div class="welcome-msg">
                Automate Work office. Reduce Chaos
              </div>
              <div class="welcome-msg-meta" >
                The #1 Work Office Softwares trusted by 10,000+ customers
              </div>
              <div class="download-mac-links" >
                  <a href="#" class="btn-primary1"> <img src="image/google_play.png" alt="">  </a>
                  <a href="#" class="btn-primary1"> <img src="image/google_play-1.png" alt=""> </a>
              </div>
            </div>
          </div>
        </div> -->



    </section>
    <section class="ht-vh-second-down" >
       <h3 class="scroll-down-text"> Scroll Down</h3>
        <div class="icons-scroll-one">
          <a href="#scroll-down">
          <div class="icon-scroll"></div> </a>
        </div>
    </section>
    <section class="price-container" id="sectiona">
      <div class="container" id="scroll-down" >
        <div class="world-most">
            <h2> {{ __('lang.Feature') }} </h2>
          </div>
        <div class="card-deck mb-3 px-2 text-center">
          <div class="testimonial_content ">

           <div class="row">
           <div class="col-md-6 make-list">
                <div class="testimonial_box ">
                    <div class="testimonial_inner">
                        <div class="media">
                            <i class="make-logo toyota" style="background-position: -4px -302px; "></i>                              
                            <div class="media-body">
                                <h3 class="text-center text-uppercase mt-2"> {{ __('lang.Toyota') }}  </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 make-list">
                <div class="testimonial_box">
                    <div class="testimonial_inner">
                        <div class="media">
                            <i class="make-logo chery" style="background-position: -4px -1094px;"></i>                               
                            <div class="media-body">
                            <h3 class="text-center text-uppercase mt-2"> {{ __('lang.lexus') }}   </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="price-container" id="sectiona">
            <div class="container" id="scroll-down" >
              <div class="world-most">
                  <h2> {{ __('lang.feed') }} </h2>
                </div>
              <div class="card-deck mb-3 px-2 text-center">
                <div class="testimonial_content ">
                <div class="row">
                @php
                        if(!empty($nonPrivateAccountMedias)){
                            foreach ($nonPrivateAccountMedias as $kedy => $value) {
                              //print_r($value); die();
                              $url = $value->getImageHighResolutionUrl();
                              $path = parse_url($url, PHP_URL_PATH);
                              $iname = basename($path);
                              $filename =  public_path()."/insta/".$iname;
                              if (file_exists($filename)) {
                                $img = url("/")."/insta/".$iname;
                              }else{
                                $context = stream_context_create(
                                    array(
                                        'http' => array(
                                            'follow_location' => false
                                        )
                                    )
                                );
                                $html = file_get_contents($url, false, $context);
                                file_put_contents($filename,$html);
                                $img = url("/")."/insta/".$iname;
                              }
                              if($kedy==0){
                                echo '<div class="col-md-3 gallery">';
                                echo '<a data-fancybox-trigger="preview" title="'. $value->getCaption() .'"  href="javascript:;">';
                                echo '<img src="'.$img.'" height="200"  />';
                                echo '</a>';
                                echo '</div>';
                              }else{
                                echo '<div class="col-md-3 gallery">';
                                echo '<a title="'. $value->getCaption() .'" href="'.$img.'" data-fancybox="preview" data-width="1500" data-height="1000">';
                                echo '<img src="'.$img.'" height="200"  />';
                                echo '</a>';
                                echo '</div>';
                              }
                              
                            }
                        }
                       
                    @endphp
                </div>
                
              </div> 
            </div>
          </section>
    <!-- third section view  -->
    <section class="price-container pb-0 scroll-customer" >
      <div class="container" >
        <div class="card-deck-third-section">
          <div class="world-most">
            <h2> {{ __('lang.desc') }}</h2>

          </div>
          <div class="customer-deck-third-section">
             <div class="row">
               <div class="col-sm-6">
                 <div class="customer-view-image">
                   <img src="{{URL::asset('/image/1.jpeg')}}" alt="">
                 </div>
               </div>
               <div class="col-sm-6">
                <div class="customer-details-team">
                    <h3>{{ __('lang.team') }}</h3>
                    <p> {{ __('lang.about') }}</p>
                      <div class="row py-2">
                        <div class="col-xs-4 col-sm-4">
                          <div class="customer-stification">
                              <div class="stification-customer-head">
                                 100%
                              </div>
                              <p> {{ __('lang.Satisfaction') }} </p>
                          </div>
                        </div>
                        <div class="col-xs-4 col-sm-4">
                          <div class="customer-stification">
                              <div class="stification-customer-head">
                                 24/7
                              </div>
                              <p> {{ __('lang.support') }} </p>
                          </div>
                        </div>
                        <div class="col-xs-4 col-sm-4">
                          <div class="customer-stification cutomer-remove-border ">
                              <div class="stification-customer-head">
                                 88k+
                              </div>
                              <p> {{ __('lang.Customers') }} </p>
                          </div>
                        </div>
                      </div>
                </div>
               </div>
             </div>
          </div>
        </div>
      </div>
    </section>
    <!-- fourth secotion view -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.js"></script>
<script>
     
    $(document).ready(function() {
      $('[data-fancybox="preview"]').fancybox({
        thumbs : {
            autoStart : true
        }
         
     });
     
});
</script> 
    
@endsection
