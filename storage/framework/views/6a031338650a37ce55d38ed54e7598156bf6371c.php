<?php
$json = json_encode($main_array);
$main_array = json_decode($json,true);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Customer Details</title>
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(env('IMAGE_URL','http://challenger-co.com/challenger2-1.png')); ?>">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page  {
                margin: 1cm 1cm;
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
            table, th, td {
                border: 1px solid black! important;
            }
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
                border-collapse: collapse;
                border: none;
            }
            .table_header2 td{
                text-align: center;
                vertical-align: middle;
                padding-bottom: 7px!important;
            }
            .table_header2{
                border: none!important;
            }
            .main_table td{
                padding-left: 10px!important;
            }
            .table_cust tr,td {
                border: 1px solid #333;
            }
        </style>
    <body>
    <div class="header" style="padding-bottom:30px!important; border: none !important;">
            <table class="table_header2" width="100%" id="myTable" cellpadding="20" style="padding-bottom: 30px!important;border: none !important;">
                <tbody>
                    <tr>
                        <td style="text-align:left;border: none !important;"><div style="width: 150px;height: 89px; background-color:none">
                          <img src="<?php echo e(env('IMAGE_URL','http://challenger-co.com/challenger2-1.png')); ?>" style="max-width: 20%; height:auto; margin-top:10px" ></div></td>
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
        <div style="font-size:15px;text-align:center;">Customer Details Report</div>
        <hr style="border-top: 1px solid #0C0D0E;">
        <div style="width:100%;margin-bottom: 8px;font-size: 20px;color: black;font-weight: 600;"><span>Date: <?php echo e(date("Y-m-d")); ?></span></div>
        <table class="table_cust" style="width: 100%;border-collapse: collapse;">
            <tr style="background-color: #808080;font-size: 10px;">
                <td style="text-align: left; padding:10px"><b>Customer Name</b></td>
                <td style="text-align: left; padding:20px"><b>Civil ID</b></td>
                <td style="text-align: left; padding:20px"><b>Nationality</b></td>
                <td style="text-align: left; padding:20px"><b>Mobile 1</b></td>
                <td style="text-align: left; padding:20px"><b>Mobile 2</b></td>
                <td style="text-align: left; padding:20px"><b>Fax</b></td>
            </tr>
             <?php foreach ($main_array as $key => $value) {
                $value1 = (array)$value;
              ?>
            <tr>
                <td style="text-align: left; padding-left:20px"><?php echo $value1['cust_name']; ?></td>
                <td style="text-align: left; padding-left:20px"><?php echo $value1['civil_id']; ?></td>
                <td style="text-align: left; padding-left:20px"><?php echo $value1['nationality']; ?></td>
                <td style="text-align: left; padding-left:20px"><?php echo $value1['phone'];?></td>
                <td style="text-align: left; padding-left:20px"><?php echo $value1['mobile'];?></td>
                <td style="text-align: left; padding-left:20px"><?php echo $value1['fax']; ?></td>

            </tr>
            <?php } ?>
        </table><br>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\challenger-app\resources\views/report/print_customer_detail.blade.php ENDPATH**/ ?>