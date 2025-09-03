<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Job Card</title>
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(env('IMAGE_URL')); ?>">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page  {
                margin: 0.5cm 0.5cm;
            }
            @font-face {
                font-family: calibri;
                src: url("<?php echo e(asset('public/fonts/calibri.ttf')); ?>");
                font-weight: normal;
            }
            @font-face {
                font-family: calibrib;
                src: url("<?php echo e(asset('public/fonts/calibrib.ttf')); ?>");
                font-weight: normal;
            }
            /* table, th, td {
                border: 1px solid black! important;
            } */
            td.print_container > div {
                width: 100%;
                overflow:hidden;
            }
            td.print_container {
                /*height: 120px;*/
            }
            .page-break {
                page-break-after: always;
            }
            .td{
                height: 10px!important;
            }
            body {
                font-family: calibri;
                margin-top: 4.7cm;
                margin-left: 0.7cm;
                margin-right: 0.7cm;
                margin-bottom: 1cm;
                color: black;
                /*background-color: blueviolet;*/
            }
            header {
                position: fixed;
                top: 0.5cm;
                left: 0.7cm;
                right: 0.7cm;
                height: 3cm;
                text-align: center;
                /*line-height: 1.5cm;*/
                font-weight: 600!important;
                /*background-color: red;*/
            }
            td div.inner{
                padding-left: 8px!important;
            }
            hr.divider{
                border-top: 1px solid black;
                margin-top: 5px;
                margin-bottom: 5px;
            }
            td{
                padding-top: 0px!important;
                padding-bottom: 2px!important;
            }
            .table.innertable td ,table {
                border: 0px solid black! important;
            }
            #myTable, td, th{
                /*text-align: center;*/
                vertical-align: middle;
            }
            .table_header2 td{
                text-align: center;
                vertical-align: middle;
                padding-bottom: 7px!important;
            }
            .table_header2{
                /* border: none!important; */
            }
            .main_table td{
                padding-left: 10px!important;
            }
           .table_cust tr,td {
               border: 1px solid #0C0D0E;
           }
           .span-heading {
            style="max-width: 200px;
           }
        </style>
    <body>
        <div class="container">
            <div style="margin-left:20px;">
                        <div class="header" style="padding-bottom:30px!important; border: none !important;">
                            <table class="table_header2" width="100%" id="myTable" cellpadding="20" style="padding-bottom: 30px!important;border: none !important;">
                                <tbody>
                                    <tr>
                                <td style="text-align:left;border: none !important;"><div style="width: 150px;height: 89px; background-color:none">
                                <img src="<?php echo e($main_array['image']); ?>" style="max-width: 20%; height:auto; margin-top:10px" ></div></td>
                                <td style="border: none !important;"></td>
                                <td style="text-align:right;border: none !important;">
                                <b><h4>شركة شالنجــر للسيــارات</h4></b>
                                <b><h4>CHALLENGER FOR CARS</h4></b>
                                <b><h4>تلفـون: 22277244</h4></b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h3 style="text-align:center;">Invoice</h3>
                
                <table class="main_table" style="width: 100%;border-collapse: collapse;text-align: left">
                    <tr>
                        <td style="width: 200px;"><b>Job Card No</b></td>
                        <td style="width: 200px;"><?php echo e($main_array['job_card_details']['job_no']); ?></td>
                        <td colspan="2"><b>Date</b></td>
                        <td colspan="4"><?php echo e(date("Y-m-d")); ?></td>
                    </tr>
                    <tr>
                        <td><b>Counter Read</b></td>
                        <td>0</td>
                        <td colspan="2"><b>Warranty</b></td>
                        <td colspan="4"><?php echo ($main_array['job_card_details']['warranty'] == "yes") ? $main_array['job_card_details']['warranty_days'] . ' Days' : "No Warranty" ?></td>
                    </tr>
                    <tr>
                        <td><b>Customer</b></td>
                        <td colspan=""><?php echo e($main_array['job_card_details']['cust_name']); ?></td>
                        <td colspan="2"><b>Phone</b></td>
                        <td colspan="4"><?php echo e($main_array['job_card_details']['phone']); ?></td>
                    </tr>
                    <tr>
                        <td><b>Car Type</b></td>
                        <td><?php echo e($main_array['job_card_details']['type']); ?></td>
                        <td colspan="2"><b>View</b></td>
                        <td colspan="4"><?php echo e($main_array['job_card_details']['view']); ?></td>                
                    </tr>
                    <tr>
                        <td><b>Color</b></td>
                        <td><?php echo e($main_array['job_card_details']['color']); ?></td>
                        <td colspan="2"><b>Model</b></td>
                        <td colspan="4"><?php echo e($main_array['job_card_details']['model']); ?></td>
                    </tr>
                    <tr>
                        <td><b>Plate No</b></td>
                        <td><?php echo e($main_array['job_card_details']['plate_no']); ?></td>
                        <td colspan="2"><b>Chasis No</b></td>
                        <td colspan="4"><?php echo e($main_array['job_card_details']['chasis']); ?></td>                
                    </tr>
                    <tr>
                        <td><b>Cab No</b></td>
                        <td><?php echo e($main_array['job_card_details']['cab_no']); ?></td>
                        <td colspan="2"><b>Delivery Date</b></td>
                        <td colspan="4"><?php echo e($main_array['job_card_details']['delivery_date']); ?></td>
                        
                    </tr>
                </table>
                <hr style="border-top: 1px solid #0C0D0E;">
                <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Used Spare Parts</span><span style="float:right;">قطع الغيار المستعملة</span></div>
                <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td><b>Item Name</b></td>
                        <td><b>Quantity</b></td>
                        <td><b>Unit Price</b></td>
                        <td><b>Total</b></td>
                    </tr>
                    <?php $__currentLoopData = $main_array['customer_used_spare_parts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($value['item_name']); ?></td>
                        <td><?php echo e($value['quantity']); ?></td>
                        <td><?php echo e($value['price']); ?></td>
                        <td><?php echo e($value['quantity'] * $value['price']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>    
                        <td colspan="2"></td>
                        <td><b>Total</b></td>
                        <td><?php echo e($main_array['job_card_calculation']['used_spare_parts_total']); ?></td>
                    </tr>
                </table><br>
                <hr style="border-top: 1px solid #0C0D0E;">
                <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Labours</span><span style="float:right;">الاجور</span></div>
                <table class="table_cust labour_div" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td width="55%"><b>Labours</b></td>
                        <td><b>Quantity</b></td>
                        <td><b>Service Type</b></td>
                        <td><b>Price</b></td>
                    </tr>
                    <?php $__currentLoopData = $main_array['customer_labours']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($value['labour_name']); ?></td>
                        <td><?php echo e($value['quantity']); ?></td>
                        <td><?php echo e($value['service_types']); ?></td>
                        <td><?php echo e($value['price']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Total</b></td>
                        <td><b><?php echo e($main_array['job_card_calculation']['labours_total']); ?></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Discount</b></td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><b>Totals</b></td>
                        <td><b><?php echo e($main_array['job_card_calculation']['labours_total']); ?></b></td>
                    </tr>
                </table><br>
                <hr style="border-top: 1px solid #0C0D0E;">
                <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Spare Parts</span><span style="float:right;">قطع الغيار</span></div>
                <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td><b>Item Code</b></td>
                        <td><b>Description</b></td>
                        <td><b>Quantity</b></td>
                        <td><b>Unit Price</b></td>
                        <td><b>Discount</b></td>
                        <td><b>Total</b></td>
                    </tr>
                    <?php $__currentLoopData = $main_array['customer_new_spare_parts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($value['item_code']); ?></td>
                        <td><?php echo e($value['item']); ?></td>
                        <td><?php echo e($value['quantity']); ?></td>
                        <td><?php echo e($value['price']); ?></td>
                        <td><?php echo e($value['discount']); ?></td>
                        <td><?php echo e($value['total']); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="4"></td>
                        <td><b>Total</b></td>
                        <td><?php echo e($main_array['job_card_calculation']['new_spare_parts_total']); ?></td>
                    </tr>
                </table><br><br>
                <hr style="border-top: 1px solid #0C0D0E;">
                <table style="width: 50%;border-collapse: collapse !important;">
                    <tr>
                    <td style="width: 25%;text-align:left"><b>Total Invoice:</b></td>
                    <td style="width: 25%;text-align:right"><?php echo e(($main_array['job_card_calculation']['grand_total'])?$main_array['job_card_calculation']['grand_total']:"0.00"); ?></td>
                    </tr>
                    <tr>
                    <td style="width: 25%;text-align:left"><b>Paid:</b></td>
                    <td style="width: 25%;text-align:right"><b><?php echo e(($main_array['job_card_calculation']['grand_total'] - $main_array['job_card_calculation']['balance'])); ?></b></td>
                    </tr> 
                    <tr>
                    <td style="width: 25%;text-align:left"><b>Overdue Amount:</b></td>
                    <td style="width: 25%;text-align:right"><b><?php echo e($main_array['overdue']); ?></b></td>
                    </tr>    
                    <tr>
                        <td style="width: 25%;text-align:left"><b>Remain:</b></td>
                        <td style="width: 25%;text-align:right"><?php echo e(($main_array['job_card_calculation']['balance'])?$main_array['job_card_calculation']['balance']:"0.00"); ?></td>
                    </tr>
                </table><br><br>
                <!-- <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Car Care </span><span style="float:right;">تلميع</span></div>
                <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td><b>U.Price</b></td>
                        <td><b>Qty</b></td>
                        <td><b>Description</b></td>
                        <td><b>Item Code</b></td>
                    </tr>
                    <?php if(isset( $main_array['customer_labours_car_care'] ) ){ ?>
                            <?php $__currentLoopData = $main_array['customer_labours_car_care']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($value['price']); ?></td>
                                <td><?php echo e($value['quantity']); ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php  } ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><b>Total</b></td>
                        <td></td>
                    </tr>
                </table><br><br> -->
                <!--  <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>body workshop</span><span style="float:right;"> ورشة عمل الجسم</span></div>
                <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td><b>U.Price</b></td>
                        <td><b>Qty</b></td>
                        <td><b>Description</b></td>
                        <td><b>Item Code</b></td>
                    </tr>
                    <?php if(isset( $main_array['customer_labours_workshop'] ) ){ ?>
                            <?php $__currentLoopData = $main_array['customer_labours_workshop']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($value['price']); ?></td>
                                <td><?php echo e($value['quantity']); ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php  } ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><b>Total</b></td>
                        <td></td>
                    </tr>
                </table><br><br> -->
                <!-- <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Service</span><span style="float:right;"> ورشة عمل الجسم</span></div>
                <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td><b>U.Price</b></td>
                        <td><b>Qty</b></td>
                        <td><b>Description</b></td>
                        <td><b>Item Code</b></td>
                    </tr>
                    <?php if(isset( $main_array['customer_labours_workshop'] ) ){ ?>
                            <?php $__currentLoopData = $main_array['customer_labours_workshop']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($value['price']); ?></td>
                                <td><?php echo e($value['quantity']); ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php  } ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><b>Total</b></td>
                        <td></td>
                    </tr>
                    </table><br><br> -->
                <!--  <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Electrical</span><span style="float:right;"> ورشة عمل الجسم</span></div>
                <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                    <tr style="background-color: #808080;color: Black;">
                        <td><b>U.Price</b></td>
                        <td><b>Qty</b></td>
                        <td><b>Description</b></td>
                        <td><b>Item Code</b></td>
                    </tr>
                    <?php if(isset( $main_array['customer_labours_electrical'] ) ){ ?>
                            <?php $__currentLoopData = $main_array['customer_labours_electrical']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($value['price']); ?></td>
                                <td><?php echo e($value['quantity']); ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php  } ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><b>Total</b></td>
                        <td></td>
                    </tr>
                </table><br><br> -->
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/report/job_card.blade.php ENDPATH**/ ?>