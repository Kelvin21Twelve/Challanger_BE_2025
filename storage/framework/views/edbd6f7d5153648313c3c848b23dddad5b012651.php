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
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <div class="alert alert-secondary" role="alert" style="width: 100%;font-size: 16px;">
        <center>No Result Found.</center>
    </div>
<?php } ?>
<?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/empty_job_list.blade.php ENDPATH**/ ?>