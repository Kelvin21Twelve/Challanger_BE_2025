<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>All Spare Part Purchase</title>
        <link rel="icon" type="image/png" sizes="16x16" href="{{env('IMAGE_URL','http://challenger-co.com/challenger2-1.png')}}">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                margin: 0.5cm 0.5cm;
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

            *{padding:30px;}

           .table_cust td {
                padding: 9px !important;
           }
           .table_cust_total >td {
               border: 1px solid #808080;
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
                                     <img src="{{ env('IMAGE_URL')}}" style="max-width: 20%; height:auto; margin-top:10px"> </div></td>
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
                    <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:right;"><span>Date: {{date("Y-m-d")}}</span></div>
                  @if(!empty($from_date) && !empty($to_date))
                  <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;text-align:right;"><span>From Date: {{$from_date}}</span><span style="float:right;"> To Date: {{$to_date}}</span></div>
                  @endif
                  <hr style="border-top: 1px solid #0C0D0E;">
                    <table class="table_cust_total" style="width: 50%;border-collapse: collapse;float:left">
                     <tr>
                          <td style="text-align: left;padding-left:10px"><b>Net Sale</b></td>
                          <td style="text-align: right;padding-right:10px;">{{$total_jobs_amount}}</td>
                     </tr>
                     <tr>
                          <td style="text-align: left;padding-left:10px"><b>Total Expense</b></td>
                          <td style="text-align: right;padding-right:10px;">{{$total_exp}}</td>
                     </tr>
                     <tr>
                          <td style="text-align: left;padding-left:10px;border-top:1px solid #808080;font-weight:bold"><b>Net Profit</b></td>
                          <td style="text-align: right;padding-right:10px;border-top:1px solid #808080;font-weight:bold">{{($total_jobs_amount - $total_exp)}}</td>
                     </tr>
                    </table>
                  <h3 style="text-align:center;">Expenses Report</h3>
                  
                  <table class="table_cust" style="width: 100%;border-collapse: collapse;">
                      <tr style="background-color: #808080;color: Black;">
                          <td><b>Date</b></td>
                          <td><b>Type of Expense</b></td>
                          <td><b>Account Number</b></td>
                          <td><b>Account Name</b></td>
                          <td><b>Note</b></td>
                          <td><b>Amount</b></td>
                      </tr>
                      @foreach($main_array as $value)
                      <tr>
                          <td>{{$value['date']}}</td>
                          <td>{{$value['exp_type']}}</td>
                          <td>{{$value['acc_num']}}</td>
                          <td>{{$value['acc_name']}}</td>
                          <td>{{$value['note']}}</td>
                          <td>{{$value['amount']}}</td>
                      </tr>
                      @endforeach
                      <tr>
                          <td colspan="4" style="border-top:1px solid #0C0D0E;"></td>
                          <td style="border-top:1px solid #0C0D0E;font-weight:bold">Total Expense</td>
                          <td style="border-top:1px solid #0C0D0E;font-weight:bold">{{$total_exp}}</td>
                      </tr>
                  </table><br><hr style="border-top: 1px solid #0C0D0E;">
                  <h3 style="text-align:center;">Job Cards Report</h3>
                  <br>
                  <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
                     <tr style="background-color: #808080;color: Black;">
                          <td style="width: 50px;"><b>Job Card No</b></td>
                          <td><b>Customer Name</b></td>
                          <td><b>Services</b></td>
                          <td><b>Used</b></td>
                          <td><b>New</b></td>
                          <td><b>Is Posted</b></td>
                          <td><b>Total</b></td>
                      </tr>
                      @foreach($job_array as $value)
                      <tr>
                          <td style="width: 50px;">{{$value['job_card_no']}}</td>
                          <td>{{$value['customer']}}</td>
                          <td>{{$value['service']}}</td>
                          <td>{{$value['used']}}</td>
                          <td>{{$value['new']}}</td>
                          <td>{{$value['is_posted']}}</td>
                          <td style="text-align: right;">{{$value['total']}}</td>
                      </tr>
                      @endforeach
                      <tr>
                          <td colspan="5" style="border-top:1px solid #0C0D0E;"></td>
                          <td style="border-top:1px solid #0C0D0E;font-weight:bold">Total Sale</td>
                          <td style="border-top:1px solid #0C0D0E;font-weight:bold">{{$total_jobs_amount}}</td>
                      </tr>
                  </table><br>
            </div>
        </div>
    </body>
</html>
