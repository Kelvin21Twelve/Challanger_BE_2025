<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Job Card</title>
        <link rel="icon" type="image/png" sizes="16x16" href="{{ env('IMAGE_URL')}}">
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
                line-height: 1.5cm;
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
        </style>
    <body>
        <header style="padding-bottom:30px!important; border: none !important;">
            <table class="table_header2" width="100%" id="myTable" cellpadding="20" style="padding-bottom: 30px!important;border: none !important;">
                <tbody>
                    <tr>
                        <td style="text-align:left;border: none !important;"><img src="{{ env('IMAGE_URL')}}" style="max-width: 100px;max-height: 100px;"></td>
                        <td style="border: none !important;"></td>
                        <td style="text-align:right;border: none !important;">
                            <b><h3>شركة شالنجــر للسيــارات</h3></b>
                            <b><h3>CHALLENGER FOR CARS</h3></b>
                            <b><h3>تلفـون: 22277244</h3></b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <h3><center>Visa Expiry</center></h3>
        <hr style="border-top: 1px solid #0C0D0E;">
        <div style="width:100%;margin-bottom: 8px;font-size: 16px;color: black;font-weight: 600;"><span>Date: {{date("Y-m-d")}}</span></div>
        <h3> Dear Employee,</h3>

        <h4 style="margin-left: 10px;">
             This application is to request a renew of Vacation. Your visa is going to expire on the <span>{{$vacation_end_date}}</span>. Your visa expires in 2 days, Please renew your visa.
        </h4>
        <h3> Sincerely,</h3>
        <h3> CHALLENGER FOR CARS</h3>
        <br>
    </body>
</html>


