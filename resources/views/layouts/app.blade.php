<!doctype html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="padding: 0;margin: 0;">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
      <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('iconss/apple-icon-57x57.png') }}">
      <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('iconss/apple-icon-60x60.png') }}">
      <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('iconss/apple-icon-72x72.png') }}">
      <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('iconss/apple-icon-76x76.png') }}">
      <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('iconss/apple-icon-114x114.png') }}">
      <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('iconss/apple-icon-120x120.png') }}">
      <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('iconss/apple-icon-144x144.png') }}">
      <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('iconss/apple-icon-152x152.png') }}">
      <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('iconss/apple-icon-180x180.png') }}">
      <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('iconss/android-icon-192x192.png') }}">
      <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('iconss/favicon-32x32.png') }}">
      <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('iconss/favicon-96x96.png') }}">
      <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('iconss/favicon-16x16.png') }}">
      <link rel="manifest" href="/manifest.json">
      <meta name="msapplication-TileColor" content="#ffffff">
      <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
      <meta name="theme-color" content="#ffffff">


    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>{{ config('app.name', 'Challenger') }}</title>
  <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.1.0/css/line-awesome.min.css">
  <link rel="stylesheet" href=" https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
   

    <link rel="canonical" href="#">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/validator/12.2.0/validator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="{{ asset('js/ajax-js.js') }}"></script>
</head>

  <body  class="scroll-top-nav" style="padding: 0;margin: 0;" >
    <nav class="navbar navbar-expand-lg navbar-light py-1  shadow-sm custom-nav-expand" >
      <div class="container">
      @php
      $lang =  \Session::get('lang');
      if(empty($lang)){
        $lang = "en";
      }
      app()->setLocale($lang);
      @endphp
      <a href="{{ url('/')}}" class="navbar-brand font-weight-bold d-block "> <span class="logo-header-page-main"><img src="{{URL::asset('/image/challenger2-1.png')}}" alt=""> </span> <span class="doezz-header-text">{{ __('lang.Challenger') }} </span></a>
      <button type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbars" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
              <span class="navbar-toggler-icon"></span>
          </button>
      <div id="navbarContent" class="collapse navbar-collapse float-right">
        <ul class="navbar-nav mx-auto" style="flex: auto;">

        </ul>
        <ul class="navbar-nav mx-auto">
           <?php  
               $page_name=Request::segment(1) ;
               if(empty($page_name)){
                  $page_name="index"; 
               }
            ?> 
            @if($lang == "en")
             <li class="nav-item flag"><a href="javascript:void(0);" data-route="{{url('/')}}" data-lang="ar" class="nav-link font-weight-bold get-custom-cl setlang" onclick="setlanguage()"> <img src="https://img.icons8.com/color/21/000000/kuwait.png"></a></li>
            @else
            <li class="nav-item flag"><a href="javascript:void(0);"  data-route="{{url('/')}}" data-lang="en" class="nav-link font-weight-bold get-custom-cl setlang" onclick="setlanguage()"> <img src="https://img.icons8.com/cute-clipart/21/000000/great-britain.png"></a></li>
            @endif 
            <!-- <li class="nav-item"><a href="{{ url('login') }}" class="nav-link font-weight-bold get-custom-cl">Login</a></li> -->
              <li class="nav-item"><a href="{{ url('about-us/')}}" class="nav-link font-weight-bold get-custom-cl">{{ __('lang.About Us') }}</a></li>
              <li class="nav-item"><a href="{{ url('contact-us/') }}" class="nav-link font-weight-bold get-custom-cl">{{ __('lang.Contact Us') }}</a></li>
              <li class="nav-item"><a href="{{ url('gallery/')  }}" class="nav-link font-weight-bold get-custom-cl">{{ __('lang.Gallery') }}</a></li>
              @if(Auth::check())
              <?php 
             if( Auth::user()->department =='1'){
              ?>
                 <li class="nav-item"><a href="{{ url('add-image/')}}" class="nav-link font-weight-bold get-custom-cl">{{ __('lang.gallery_image') }}</a></li>
             <?php  
             } 
             ?>
             <li class="nav-item">
                    <a class="nav-link font-weight-bold get-custom-cl" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout text-primary"></i>
                        {{ __('lang.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
              </li> 
            @endif
          </ul>
      </div>
      </div>
    </nav>

    <div class="content-start">
    @yield('content')
    </div>
    <div class="cutomer-bottom-fullbackgound">
  <footer class="container cutomer-bottom-footer">

    <div class="footer-copy-write">
    <p> {{ __('lang.footer') }} | {{ __('lang.footer_left') }}  <a href="https://mrafie.com/" target="_blank"> Marafie IT Services & Consultation Co.</a></p>
    </div>
  </footer>
</div>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/additional-methods.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/validator/12.2.0/validator.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>


<script type="text/javascript">
       
        
    </script>
<!-- <script src="{{ asset('js/validatior.js') }}"></script> -->

<script src="{{ asset('js/ajax-js.js') }}"></script>
<script src="{{ asset('js/jquery-slim.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>



<style>
  .content-start{min-height: calc( 100vh - 171px );}
  </style>
<script type="text/javascript">
$(window).scroll(function(){
    if ($(this).scrollTop() > 100) {
       $('.custom-nav-expand').addClass('nav-scroll');

    } else {
       $('.custom-nav-expand').removeClass('nav-scroll');
   }
});
$(window).scroll(function(){
    if ($(this).scrollTop() > 550) {

        $('.scroll-top-nav').addClass('cus-nav-scroll');
    } else {
        $('.scroll-top-nav').removeClass('cus-nav-scroll');
    }
});

</script>

<script type="text/javascript">
let anchorlinks = document.querySelectorAll('a[href^="#"]')

for (let item of anchorlinks) { // relitere
    item.addEventListener('click', (e)=> {
		let hashval = item.getAttribute('href')
		let target = document.querySelector(hashval)
		target.scrollIntoView({
			behavior: 'smooth'
		})
		history.pushState(null, null, hashval)
		e.preventDefault()
	})
}

</script>

</body>
</html>
