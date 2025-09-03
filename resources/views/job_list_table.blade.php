<?php

use App\JobCard;
?>
<div class="table-responsive">
    <table class="table table-striped table-sm" id="master_search_tbl" style="width: 100%!important;">
        <thead>
            <tr>
                <th>Action</th>
                <th>Job&nbsp;Card</th>
                <th>Customer Name</th>
                <th>Agency</th>
                <th>View</th>
                <th>Type</th>
                <th>Model</th>
                <th>Color</th>
                <th>Plate&nbsp;No</th>
                <th>Phone</th>
                <th>Entry</th>
                <th>Remaining Balance</th>
                <th>Status</th>
                <!-- <th>Out</th> -->
            </tr>
        </thead>
        <tbody class="static_tbody">
            <?php
            if (count($main_array) > 0) {
                foreach ($main_array as $value) {
                    ?>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-square btn-primary edit_job_card" data-id="<?php echo htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </td>
                        <td>{{$value['job_no']}}</td>
                        <td>{{$value['cust_name']}}</td>
                        <td>{{$value['agency']}}</td>
                        <td>{{$value['view']}}</td>
                        <td>{{$value['type']}}</td>
                        <td>{{$value['model']}}</td>
                        <td>{{$value['color']}}</td>
                        <td>{{$value['plate_no']}}</td>
                        <td>{{$value['phone']}}</td>
                        <td>{{$value['entry_date'].":".$value['entry_time']}}</td>  
                        <td>{{$value['balance']}}</td>
                        <td>{{$value['status']}}</td>
                        <!-- <td>{{$value['delivery_date']}}</td> -->
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <td colspan="12" style="text-align:center">No record found</td>
            <?php } ?>
        </tbody>
    </table>
</div>
