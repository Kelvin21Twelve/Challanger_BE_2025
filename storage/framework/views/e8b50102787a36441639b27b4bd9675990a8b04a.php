<?php

use App\JobCard;

if (count($main_array) > 0) {
    foreach ($main_array as $value) {
        ?>
        <?php if ($value['empty']) { ?>
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                <div class="dashborad-chard-change-view"> 
                    <div class="wrapper">
                        <div class="card dashboard-challenge-common-one ">
                            <div class="front dashboard-empty-one">
                                <div class="empty-card-text"> <?php echo e($value['cab_no']); ?>  </div>
                            </div>
                            <div class="dashboard-second-view-card right dashboard-empty-one">
                                <div class="dashboard-second-view-card-hide">
                                    <span class="empty-card-text"> <?php echo e($value['cab_no']); ?>  </span>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        <?php } else { ?>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <div class="dashborad-chard-change-view edit_job_card" data-id="<?php echo htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8'); ?>"> 
                    <div class="wrapper">
                        <div class="card">
                            <div class="front dashboard-<?php echo e($value['status']); ?>-one" style="color:white">
                                <h2 style="color: white;border:1px solid #fff"><?php echo e($value['cab_no']); ?></h2>
                                <div><?php echo e($value['cust_name']); ?></div>
                                <div><?php echo e($value['phone']); ?></div>
                                <div><?php echo e($value['view']. "-".$value['type']); ?></div>
                                <div><?php echo e($value['agency']); ?></div>
                                <div><?php echo e("PN ".$value['plate_no']); ?></div>
                            </div>
                            <div class="dashboard-second-view-card right dashboard-<?php echo e($value['status']); ?>-two">
                                <div class="dashboard-second-view-card-hide">
                                    <h2><?php echo e($value['cab_no']); ?></h2>
                                    <div><?php echo e($value['cust_name']); ?></div>
                                    <div><?php echo e($value['phone']); ?></div>
                                    <div><?php echo e($value['view']. "-".$value['type']); ?></div>
                                    <div><?php echo e($value['agency']); ?></div>
                                    <div><?php echo e("PN ".$value['plate_no']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>     
                </div>
            </div>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <div class="alert alert-secondary" role="alert" style="width: 100%;font-size: 16px;">
        <center>No Result Found.</center>
    </div>
<?php } ?>
<?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/job_list.blade.php ENDPATH**/ ?>