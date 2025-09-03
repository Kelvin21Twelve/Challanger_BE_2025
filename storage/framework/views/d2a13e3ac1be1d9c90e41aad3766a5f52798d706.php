<!doctype html>
<html  lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" style="padding: 0;margin: 0;">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
      <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('iconss/apple-icon-57x57.png')); ?>">
      <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(asset('iconss/apple-icon-60x60.png')); ?>">
      <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('iconss/apple-icon-72x72.png')); ?>">
      <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('iconss/apple-icon-76x76.png')); ?>">
      <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('iconss/apple-icon-114x114.png')); ?>">
      <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('iconss/apple-icon-120x120.png')); ?>">
      <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('iconss/apple-icon-144x144.png')); ?>">
      <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('iconss/apple-icon-152x152.png')); ?>">
      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('iconss/apple-icon-180x180.png')); ?>">
      <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo e(asset('iconss/android-icon-192x192.png')); ?>">
      <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('iconss/favicon-32x32.png')); ?>">
      <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('iconss/favicon-96x96.png')); ?>">
      <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('iconss/favicon-16x16.png')); ?>">
      <link rel="manifest" href="/manifest.json">
      <meta name="msapplication-TileColor" content="#ffffff">
      <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
      <meta name="theme-color" content="#ffffff">


    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title><?php echo e(config('app.name', 'Challenger')); ?></title>
  <link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.1.0/css/line-awesome.min.css">
  <link rel="stylesheet" href=" https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css" rel="stylesheet" />
   

    <link rel="canonical" href="#">

    <!-- Bootstrap core CSS -->
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo e(asset('css/main.css')); ?>" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/additional-methods.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/validator/12.2.0/validator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="<?php echo e(asset('js/ajax-js.js')); ?>"></script>
</head>

  <body  class="scroll-top-nav" style="padding: 0;margin: 0;" >
    <nav class="navbar navbar-expand-lg navbar-light py-1  shadow-sm custom-nav-expand" >
      <div class="container">
      <?php
      $lang =  \Session::get('lang');
      if(empty($lang)){
        $lang = "en";
      }
      app()->setLocale($lang);
      ?>
      <a href="<?php echo e(url('/')); ?>" class="navbar-brand font-weight-bold d-block "> <span class="logo-header-page-main"><img src="<?php echo e(URL::asset('/image/challenger2-1.png')); ?>" alt=""> </span> <span class="doezz-header-text"><?php echo e(__('lang.Challenger')); ?> </span></a>
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
            <?php if($lang == "en"): ?>
             <li class="nav-item flag"><a href="javascript:void(0);" data-route="<?php echo e(url('/')); ?>" data-lang="ar" class="nav-link font-weight-bold get-custom-cl setlang" onclick="setlanguage()"> <img src="https://img.icons8.com/color/21/000000/kuwait.png"></a></li>
            <?php else: ?>
            <li class="nav-item flag"><a href="javascript:void(0);"  data-route="<?php echo e(url('/')); ?>" data-lang="en" class="nav-link font-weight-bold get-custom-cl setlang" onclick="setlanguage()"> <img src="https://img.icons8.com/cute-clipart/21/000000/great-britain.png"></a></li>
            <?php endif; ?> 
            <!-- <li class="nav-item"><a href="<?php echo e(url('login')); ?>" class="nav-link font-weight-bold get-custom-cl">Login</a></li> -->
              <li class="nav-item"><a href="<?php echo e(url('about-us/')); ?>" class="nav-link font-weight-bold get-custom-cl"><?php echo e(__('lang.About Us')); ?></a></li>
              <li class="nav-item"><a href="<?php echo e(url('contact-us/')); ?>" class="nav-link font-weight-bold get-custom-cl"><?php echo e(__('lang.Contact Us')); ?></a></li>
              <li class="nav-item"><a href="<?php echo e(url('gallery/')); ?>" class="nav-link font-weight-bold get-custom-cl"><?php echo e(__('lang.Gallery')); ?></a></li>
              <?php if(Auth::check()): ?>
              <?php 
             if( Auth::user()->department =='1'){
              ?>
                 <li class="nav-item"><a href="<?php echo e(url('add-image/')); ?>" class="nav-link font-weight-bold get-custom-cl"><?php echo e(__('lang.gallery_image')); ?></a></li>
             <?php  
             } 
             ?>
             <li class="nav-item">
                    <a class="nav-link font-weight-bold get-custom-cl" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout text-primary"></i>
                        <?php echo e(__('lang.logout')); ?>

                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
              </li> 
            <?php endif; ?>
          </ul>
      </div>
      </div>
    </nav>

    <div class="content-start">
    <?php echo $__env->yieldContent('content'); ?>
    </div>
    <div class="cutomer-bottom-fullbackgound">
  <footer class="container cutomer-bottom-footer">

    <div class="footer-copy-write">
    <p> <?php echo e(__('lang.footer')); ?> | <?php echo e(__('lang.footer_left')); ?>  <a href="https://mrafie.com/" target="_blank"> Marafie IT Services & Consultation Co.</a></p>
    </div>
  </footer>
</div>
<script src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/additional-methods.min.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/validator/12.2.0/validator.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>


<script type="text/javascript">
       
        
    </script>
<!-- <script src="<?php echo e(asset('js/validatior.js')); ?>"></script> -->

<script src="<?php echo e(asset('js/ajax-js.js')); ?>"></script>
<script src="<?php echo e(asset('js/jquery-slim.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap.min.js')); ?>"></script>

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
<?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>