<link rel="stylesheet" href="<?php echo e(asset('dropify/css/dropify.css' )); ?>">
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
                            <h2><?php echo e(__('lang.add_image')); ?></h2>
                        </div>
                    </div>
                    <div class="col-sm-12 py-2">
                        <div class="col-xs-12 col-sm-12  col-md-12 col-lg-12 front-home-pricing-plan-all-doezz ">
                            <div class="front-view add_image">
                                <form method="post" action = "<?php echo e(route('gallery_image_store')); ?>"   data-route="<?php echo e(url('/')); ?>" accept-charset="UTF-8" id="gallery_image_store" enctype='multipart/form-data'>
                                        <?php echo csrf_field(); ?>
                                        
                                    <input type="hidden" class="form-control"  name="id" id="id" value="<?php if(isset($datas->id)): ?><?php echo e(en_de_crypt($datas->id,'e')); ?><?php endif; ?>">
                                    <div class="form-group">
                                        <label for="usr"><?php echo e(__('lang.gallery_image')); ?></label>
                                        <input type="hidden" name="g_img" value="<?php if(isset($datas->image)): ?> <?php echo e($datas->image); ?> <?php endif; ?>">
                                        <input type="file" name="g_image" id="g_image"  class="dropify" <?php if(isset($datas->image)): ?> data-default-file="<?php echo e(asset('/image')); ?>/<?php echo e($datas->image); ?>" <?php endif; ?> data-allowed-file-extensions="png jpg jpeg" value>  
                                    </div>
                                    <div class="form-group contact-front-send-data">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col">
                                                  <button type="button" class="btn btn-primary" onclick="ajaxCommonSubmitForm(this)"><?php echo e(__('lang.Send')); ?></button>
                                                </div>
                                                <div class="col">
                                                    <a type="button"  style="width:100% !important" class="btn btn-success" href="<?php echo e(route('add-image')); ?>"><?php echo e(__('lang.Back')); ?></a>
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
<script src="<?php echo e(asset('dropify/js/dropify.js' )); ?>"></script>
<script type="text/javascript">
 $('.dropify').dropify();
</script> 
<?php $__env->stopSection(); ?>

  




<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/pages/add_image.blade.php ENDPATH**/ ?>