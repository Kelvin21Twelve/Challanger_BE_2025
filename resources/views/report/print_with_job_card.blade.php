<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>All Spare Part Sales</title>
        <link rel="icon" type="image/png" sizes="16x16" href="{{env('IMAGE_URL','http://challenger-co.com/challenger2-1.png')}}">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                margin: 0cm 0cm;
            }
            @font-face {
                font-family: calibri;
                src: url("{{ asset('public/fonts/calibri.ttf') }}");
                font-weight: normal;
            }
            @font-face {
                font-family: calibrib;
                src: url("{{ asset('public/fonts/calibrib.ttf') }}");
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
              /* *{padding:30px;} */
        </style>
    <body>
        <div class="container">
            <div style="margin-left:20px;">
      <div class="header" style="padding-bottom:30px!important; border: none !important;">
              <table class="table_header2" width="100%" id="myTable" cellpadding="20" style="padding-bottom: 30px!important;border: none !important;">
                  <tbody>
                      <tr>
                          <td style="text-align:left;border: none !important;"><div style="width: 150px;height: 89px; background-color:none">
                           <img src="{{ env('IMAGE_URL')}}" style="max-width: 20%; height:auto; margin-top:10px" > </div></td>
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
        <div style="font-size:15px;text-align:center;"><center>Spare Part With Job Cards Report</center></div>
        <div style="width:100%;margin-bottom: 8px;font-size: 10px;color: black;font-weight: 600;text-align:right;"><span>Date: {{date("Y-m-d")}}</span></div>
        <div style="width:100%;margin-bottom: 8px;font-size: 10px;color: black;font-weight: 600;text-align:right;"><span>From Date: {{$from_date}}</span><span style="float:right;">To Date: {{$to_date}}</span></div>
        <hr style="border-top: 1px solid #0C0D0E;">
        <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
            <tr class="head_tr" style="background-color: #808080;">
                <td><b>Inv No</b></td>
                <td><b>Inv Date</b></td>
                <td><b>Job card No</b></td>
                <!-- <td><b>Department</b></td> -->
                <td><b>Name</b></td>
                <td><b>Total</b></td>
                <td><b>Is Posted</b></td>
            </tr>
            @foreach($main_array as $value)
            <tr>
                <td>{{$value['inv_no']}}</td>
                <td>{{$value['inv_date']}}</td>
                <td>{{$value['job_card_no']}}</td>
                <td>{{$value['name']}}</td>
                <td>{{$value['total']}}</td>
                <td>{{$value['is_posted']}}</td>
            </tr>
            @endforeach
        </table><br>
        
        <hr style="border-top: 1px solid #0C0D0E;">
        <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:center;"><span>Used Spare Parts</span></div>
        <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
            <tr class="head_tr" style="background-color: #808080;">
                <td><b>Inv No</b></td>
                <td><b>Inv Date</b></td>
                <td><b>Job card No</b></td>
                <td><b>Used Spare Part Item Name</b></td>
                <td><b>Used Spare Part Price</b></td>
                <td><b>Used Spare Part Quantity</b></td>
                <td><b>Used Spare Part Total</b></td>
            </tr>
            @foreach($main_array as $value)
            <tr>
                <td>{{$value['inv_no']}}</td>
                <td>{{$value['inv_date']}}</td>
                <td>{{$value['job_card_no']}}</td>
                <td>{{$value['customer_used_spare_parts_item_name']}}</td>
                <td>{{$value['customer_used_spare_parts_price']}}</td>
                <td>{{$value['customer_used_spare_parts_qty']}}</td>
                <td>{{$value['customer_used_spare_parts_total']}}</td>
            </tr>
            @endforeach
        </table><br>



        <hr style="border-top: 1px solid #0C0D0E;">
        <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:center;"><span>New Spare Parts</span></div>

        <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
            <tr class="head_tr" style="background-color: #808080;">
                <td><b>Inv No</b></td>
                <td><b>Inv Date</b></td>
                <td><b>Job card No</b></td>
                <td><b>New Spare Part Item</b></td>
                <td><b>New Spare Part Item Code</b></td>
                <td><b>New Spare Part Price</b></td>
                <td><b>New Spare Part Quantity</b></td>
                <td><b>New Spare Part Discount</b></td>
                <td><b>New Spare Part Total</b></td>
            </tr>
            @foreach($main_array as $value)
            <tr>
                <td>{{$value['inv_no']}}</td>
                <td>{{$value['inv_date']}}</td>
                <td>{{$value['job_card_no']}}</td>
                <td>{{$value['customer_new_spare_parts_item']}}</td>
                <td>{{$value['customer_new_spare_parts_item_code']}}</td>
                <td>{{$value['customer_new_spare_parts_price']}}</td>
                <td>{{$value['customer_new_spare_parts_qty']}}</td>
                <td>{{$value['customer_new_spare_parts_discount']}}</td>
                <td>{{$value['customer_new_spare_parts_total']}}</td>
            </tr>
            @endforeach
        </table><br></div></div>
    </body>
</html>
