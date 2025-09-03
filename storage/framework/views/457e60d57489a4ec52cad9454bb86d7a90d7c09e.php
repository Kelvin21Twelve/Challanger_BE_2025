<?php $__env->startSection('content'); ?>
<?php
  $lang =  \Session::get('lang');
  if(empty($lang)){
    $lang = "en";
  }
  app()->setLocale($lang);
  ?>
  <section class="price-container p-0 about-us-container-details">
          <div class="container">
                <div class="row front-home-pricing-plan-all-one">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 py-2">
                                    <div class="section-title text-center">
                                        <h2><?php echo e(__('lang.About Us')); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-3">
                                <div class="col-md-6 wow fadeInUp">
                                    <div class="about-text">
                                        <h4><?php echo e(__('lang.Welcome')); ?></h4>
                                        <p><?php echo e(__('lang.info')); ?></p>
                                        <p> <?php echo e(__('lang.sections')); ?></p>
                                        <li> <?php echo e(__('lang.kind')); ?></li>
                                        <li> <?php echo e(__('lang.workshop')); ?></li>
                                        <li> <?php echo e(__('lang.car_care_center')); ?></li>
                                        <li> <?php echo e(__('lang.Spare_part_section')); ?></li>
                                        <!-- <a href="" class="read-more">Read more</a> -->
                                    </div>
                                </div>
                                <div class="col-md-6 wow fadeInRight">
                                    <div class="about-image-area">
                                        <img src="<?php echo e(URL::asset('/image/6.jpeg')); ?>" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                  </div>
              </section>
  <!-- fourth secotion view -->
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/pages/about-us.blade.php ENDPATH**/ ?>