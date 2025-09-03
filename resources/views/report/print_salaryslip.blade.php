<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Salary Certificate</title>
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

            *{padding:30px;}
        </style>
    <body>
        <div class="container">
            <div style="margin-left:20px;">
        <div class="header" style="padding-bottom:30px!important; border: none !important;">
            <table class="table_header2" width="100%" id="myTable" cellpadding="20" style="padding-bottom: 30px!important;border: none !important;">
                <tbody>
                    <tr>

                        <td style="text-align:left;border: none !important;"><img src="{{ env('IMAGE_URL')}}" style="max-width: 100px;max-height: 100px;"></td>
                        <td style="border: none !important;"></td>
                        <td style="text-align:right;border: none !important;">
                            <b><h4>شركة شالنجــر للسيــارات</h4></b>
                            <b><h4 class="trn" >CHALLENGER FOR CARS</h4></b>
                            <!-- <h5><span style="color:black;">Phone: 22277244</h5> -->
                            <h5><span style="color:black;">Civil No:</span> {{ $my_array['civil_id'] }}</h5>
                            <h5><span style="color:black;">Date of hiring:</span> {{ $my_array['join_date'] }}</h5>
                            <h5><span style="color:black;">Department:</span> {{ $my_array['department'] }} </h5>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
         <h4 style="text-align:center;" >Salary Certificate</h4>
        <hr style="border-top: 1px solid #0C0D0E;">

        <table class="table_cust" style="width: 100%;text-align: center;border-collapse: collapse;">
            <tr style="background-color: #e8e4e4;">
                <td><strong>Statement</strong></td>
                <td><strong>Amount</strong></td>
            </tr>

            <tr>
                <td>Salary</td>
                <td>{{ $my_array['salary'] }}</td>
            </tr>

            <tr>
                <td>Allowance</td>
                <td>0.000</td>
            </tr>

            <tr>
                <td>Net salary</td>
                <td>{{ $my_array['salary'] }}</td>
            </tr>

        </table><br>
        <p style="text-align: center; font-size:12px;">This certificate has been given to him at his request, without any liability on the part of the company.</p><br><br>
        <p style="text-align: right; font-size:12px;"><strong>Executing Director</strong></p>
            </div></div>
    </body>
</html>
