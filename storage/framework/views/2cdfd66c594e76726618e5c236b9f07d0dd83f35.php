<?php $__env->startSection('content'); ?>
<?php
  $lang =  \Session::get('lang');
  if(empty($lang)){
    $lang = "en";
  }
  app()->setLocale($lang);
  ?>
  <style>
 

.auth.auth-img-bg .auth-form-transparent {
    width: 55%;
    margin: auto;
    margin-top: 132px;
}
  </style>
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
    <div class="row flex-grow">
        <div class="col-lg-6 d-flex align-items-center justify-content-center login-forgot-commontext">
        <div class="auth-form-transparent text-left p-3">
            <!-- <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><img src="<?php echo e(asset('images/logo.png')); ?>" alt="logo"/></a> -->
            
            <div class="brand-logo">
                <?php if(session('status')): ?>
                    <p class="alert alert-success"><?php echo e(session('status')); ?></p>
                <?php endif; ?>
                <?php if(session('failure')): ?>
                    <p class="alert alert-danger"><?php echo e(session('failure')); ?></p>
                <?php endif; ?>
            </div>
            <h6 class="font-weight-light"> Welcome! Happy to see you! </h6>
            <form method="POST" action="<?php echo e(route('login')); ?>" class="pt-3">
                <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="email"><?php echo e(__('E-Mail Address')); ?> </label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                    <span class="input-group-text bg-transparent border-right-0">
                    <i class="la la-user mdi-account-outline text-primary"></i>
                    </span>
                </div>
                <input id="email" type="email" class="form-control-lg border-left-0 form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>

                <?php if($errors->has('email')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('email')); ?></strong>
                    </span>
                <?php endif; ?>
                </div>
            </div>
            <div class="form-group" id="show_hide_password1">
                <label for="password"><?php echo e(__('Password')); ?></label>
                <div class="input-group">
                <div class="input-group-prepend bg-transparent">
                   <span class="input-group-text bg-transparent border-right-0">
                    <a href=""><i class="la la-lock" aria-hidden="true"></i></a>
                    </span>
                </div>
                <input id="password" type="password" class="form-control-lg border-left-0 form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" required autocomplete="current-password">
                <?php if($errors->has('password')): ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($errors->first('password')); ?></strong>
                    </span>
                <?php endif; ?>
                </div>
            </div>
            <div class="my-2 d-flex justify-content-between align-items-center">
                <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <?php echo e(__('Remember Me')); ?>

                </label>
                </div>
               
            </div>
            <div class="my-3">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">  <?php echo e(__('Login')); ?> </button>
            </div>

            <!-- <div class="text-center mt-4 font-weight-light">
                Don't have an account? <a href="<?php echo e(route('register')); ?>" class="text-primary">Create</a>
            </div> -->
            </form>
        </div>
        </div>
        <div class="col-lg-6 d-flex flex-row login-half-bg login-forgot-commontext  " style="padding:0px">
          <img src="<?php echo e(URL::asset('/image/5.jpeg')); ?>"/>    
        <!-- <p class="text-white font-weight-medium text-center flex-grow align-self-end"> <?php echo e(__('a.Copyrightaccount-anrest')); ?> </p> -->
        </div>
    </div>
    </div>
    <!-- content-wrapper ends -->
</div>
<!-- page-body-wrapper ends -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/auth/login.blade.php ENDPATH**/ ?>