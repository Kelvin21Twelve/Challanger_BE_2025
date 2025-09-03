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
                                        <h2><?php echo e(__('lang.Gallery')); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row py-3">
                               <div class="col-md-3 gallery">
                                 <a data-fancybox-trigger="preview" href="javascript:;">
                                          <img src="<?php echo e(URL::asset('/image')); ?>/<?php echo $datas[0]['image'] ?>" height="200" width:100% />
                                    </a>
                                 </div> 
                                 <div class="col-md-3 gallery" style="display:none">
                                    <a href="<?php echo e(URL::asset('/image')); ?>/<?php echo $datas[0]['image'] ?>" data-fancybox="preview" data-width="1500" data-height="1000">
                                       <img src="<?php echo e(URL::asset('/image')); ?>/<?php echo $datas[0]['image'] ?>" height="200"   />
                                    </a>
                                 </div> 
                                 <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                 <?php if($key > 0): ?>
                                 <div class="col-md-3 gallery">
                                       <a href="<?php echo e(URL::asset('/image')); ?>/<?php echo e($data->image); ?>" data-fancybox="preview" data-width="1500" data-height="1000">
                                          <img src="<?php echo e(URL::asset('/image')); ?>/<?php echo e($data->image); ?>" height="200"   />
                                       </a>
                                 </div> 
                                 <?php endif; ?> 
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                            </div>
                        </div>
                     </div>
                  </div>
              </section>
  <!-- fourth secotion view -->
  <?php $__env->stopSection(); ?>

  
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



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/pages/gallery.blade.php ENDPATH**/ ?>