<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice <?php if(!empty($inv_no) && !empty($inv_no)): ?>
        <?php endif; ?>
    </title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page  {
            margin: 0cm 0cm;
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

        table,
        th,
        td {
            border: 1px solid black ! important;
        }

        td.print_container>div {
            width: 100%;
            overflow: hidden;
        }

        td.print_container {
            /*height: 120px;*/
        }

        .page-break {
            page-break-after: always;
        }

        .td {
            height: 10px !important;
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
            font-weight: 600 !important;
            /*background-color: red;*/
        }

        td div.inner {
            padding-left: 8px !important;
        }

        hr.divider {
            border-top: 1px solid black;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        td {
            padding-top: 0px !important;
            padding-bottom: 2px !important;
        }

        .table.innertable td,
        table {
            border: 0px solid black ! important;
        }

        #myTable,
        td,
        th {
            /*text-align: center;*/
            vertical-align: middle;
            border-collapse: collapse;
            border: none;
        }

        .table_header2 td {
            text-align: center;
            vertical-align: middle;
            padding-bottom: 7px !important;
        }

        .table_header2 {
            border: none !important;
        }

        .main_table td {
            padding-left: 10px !important;
        }

        * {
            padding: 30px;
        }

        .table_cust td {
            padding: 9px !important;
        }

        .temp {
            display: none !important;
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
                            <img src="<?php echo e($main_array['image']); ?>" style="max-width: 20%; height:auto; margin-top:10px" style="padding-top: 15px;" ></div></td>
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
            <h3 style="text-align:center;">Invoice Details</h3>
            <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:right;">
                <span>Date: <?php echo e(date('Y-m-d')); ?></span></div>
            <?php if(!empty($inv_no) && !empty($inv_no)): ?>
                <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:right;">
                    <span>Invoice No: <?php echo e($inv_no); ?></span><span style="float:right;">To Date: <?php echo e($to_date); ?></span>
                </div>
            <?php endif; ?>
            <hr style="border-top: 1px solid #0C0D0E;">
            <table class="table_cust" style="width: 100%;border-collapse: collapse;">
                <tr style="background-color: #808080;color: Black;">
                    <td><b>Sr.no</b></td>
                    <td><b>Purchase Order Number</b></td>
                    <td><b>Invoice Number</b></td>
                    <td><b>Item Code</b></td>
                    <td><b>Item Name</b></td>
                    <td><b>Supplier Name</b></td>
                    <td><b>Purchase Qty</b></td>
                    <td><b>Total Amount</b></td>
                    <td><b>Purchase Date</b></td>
                </tr>
                <?php
                    $i = 0;
                    $final_amt = 0;
                ?>
                <?php $__currentLoopData = $main_array['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i = $i + 1); ?></td>
                        <td><?php echo e($value->id); ?></td>
                        <td><?php echo e($value->inv_no); ?></td>
                        <td><?php echo e($value->item_code); ?></td>
                        <td><?php echo e($value->item_name); ?></td>
                        <td><?php echo e($value->supplier_name); ?></td>
                        <td><?php echo e($value->purchase_qty); ?></td>
                        <td><?php echo e($value->total_amt); ?></td>
                        <td><?php echo e($value->date); ?></td>
                        <?php
                            $final_amt = $final_amt + $value->total_amt;
                        ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
            <hr style="border-top: 1px solid #0C0D0E;">
            <div class="container" style="float:right; width:100%;">
                <div class="row">
                    <div class="col" style="float:right; margin-left: 20px;">
                        Total Amount : <?php echo e($final_amt); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/report/print_invoice_details.blade.php ENDPATH**/ ?>