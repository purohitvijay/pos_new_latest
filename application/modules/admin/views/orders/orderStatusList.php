<div class="container-fluid">
    

    
<!-- Modal -->
<div class="modal fade" id="redeliveryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Redelivery Management for <b><?=$order_number?></b></b></h4>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" role="form" id="redeliveryForm">
            <input type="hidden" name="order_status_trans_id" id="order_status_trans_id" value="">
            <input type="hidden" name="order_id" value="<?=$order_id?>">
            <input type="hidden" name="order_number" value="<?=$order_number?>">
            <input type="hidden" name="first_redelivery" value="<?=$first_redelivery?>">
            <div class="form-group">
                <div class="col-md-12 fake-redelivery-entry-form">
                    <div class="form-group row">
                        <label class="control-label col-sm-3">Driver</label>
                        <div class="col-sm-3">
                            <?php
                            if (empty($drivers))
                            {
                                echo "No driver(s) found.";
                            }
                            else
                            {
                            ?>
                                <select name="employee_id" required class="form-control" id="redelivery_employee_id" style="width:409px">
                                    <option value="">--Select--</option>
                                    <?php
                                    foreach ($drivers as $index => $row)
                                    {
                                    ?>
                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <?php
                            }
                            ?>
                        </div>
                        

                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-3"></div>

                        <br/><br/><br/>

                        <label for="textfield" class="control-label col-sm-3">Driver Paid</label>

                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="radio" value="yes"  checked="checked" name="paid_to_driver"> Yes
                                <input type="radio" value="no" name="paid_to_driver"> No
                            </div>
                        </div>
                        
                        <div id="amount_container">
                            <br/><br/><br/>

                            <label for="textfield" class="control-label col-sm-3">Commission Amount</label>

                            <div class="col-md-5">
                                <div class="input-group">
                                    <b>$</b> <input min="0" type="number" value="0" name="commission_amount" id="commission_amount">
                                </div>
                            </div>    
                        </div>

                    </div>
                    
                    
                </div>
                
                
                <div class="col-lg-12" id="redelivery-history-container">
                <?php
                if (empty($redelivery_history))
                {
                    echo "No redelivery history found.";
                }
                else
                {
                ?>
                    <h3>Redelivery History</h3>
                    <div class="form-group row">
                        <label class="control-label col-lg-2"><b>Driver</b></label>
                        <label class="control-label col-lg-2"><b>Initial?</b></label>
                        <label class="control-label col-lg-2"><b>Paid?</b></label>
                        <label class="control-label col-lg-2"><b>Comm Amount ($)</b></label>
                        <label class="control-label col-lg-2"><b>Date/Time</b></label>
                        <label class="control-label col-lg-2"><b>Delete</b></label>
                    </div>
                        
                <?php
                    $loop_count = 1;
                    $redelivery_total = count($redelivery_history);
                    $previous_driver_id = $previous_driver = '';
                    foreach ($redelivery_history as $row)
                    {
                        if ($loop_count++ == $redelivery_total)
                        {
                            $delete_message = "This action would reset driver name from $previous_driver to {$row['driver']} for order status delivered.Are you sure you want to continue?";
                            $last_record = 1;
                        }
                        else
                        {
                            $delete_message = 'Are you sure you want to delete?';
                            $last_record = 0;
                        }

                        $amount = $row['initial_delivery'] == 'yes' ? 'N/A' : '<b>$</b> '. $row['amount_paid'];
                        
                        $date = date_create($row['created_at']);
                ?>
                        <div class="form-group row" id="redelivery_<?=$row['id']?>">
                            <label class="control-label col-lg-2"><?=$row['driver']?></label>
                            <label class="control-label col-lg-2"><?=  ucwords($row['initial_delivery'])?></label>
                            <label class="control-label col-lg-2"><?=  ucwords($row['paid_to_driver'])?></label>
                            <label class="control-label col-lg-2"><?=$row['commission_amount']?></label>
                            <label class="control-label col-lg-2"><?=date_format($date, 'd/m/Y H:i:s')?></label>
                            <label class="control-label col-lg-2">
                                <?php
                                if ($loop_count == 2)
                                {
                                    echo '--';
                                }
                                else
                                {
                                ?>
                                    <a data-update-driver-id-in-status="<?=$last_record?>" data-message="<?=$delete_message?>" data-previous-driver-id="<?=$previous_driver_id?>" href="javascript:void(0)" class="fake-class-redelivery-delete-history" rel="<?=$row['id']?>" href="<?php echo base_url(); ?>admin/order/deleteReliver/12403/81941"><i class="fa glyphicon-circle_remove"></i></a>
                                <?php
                                }
                                ?>    
                            </label>
                        </div>
                <?php
                        $previous_driver = $row['driver'];
                        $previous_driver_id = $row['employee_id'];
                    }
                }
                ?>
                </div>

            </div>
        </form>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary fake-redelivery-entry-form" id="redeliveryButton">Save</button>
      </div>
    </div>
  </div>
</div>

    
    
<?php
if (!empty($next_status))
{
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Escalate status to <b><?=$next_status['display_text']?></b></h4>
      </div>
      <div class="modal-body">

        <form class="form-horizontal" role="form" id="escalateForm">
            <input type="hidden" name="order_id" value="<?=$order_id?>">
            <input type="hidden" name="order_number" value="<?=$order_number?>">
            <input type="hidden" name="status" value="<?=$next_raw_status?>">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="control-label col-sm-3">Employee</label>
                        <div class="col-sm-3">
                            <?php
//                            if ($order_status_details['responsibility_completed'] == 'yes' || $order_status_details['status'] == 'order_booked')
//                            if ($order_status_details['responsibility_completed'] == 'yes' && $show_driver_drop_down_in_escalation === true)
                            if ($show_driver_drop_down_in_escalation === true)
                            {
                            ?>
                                <select name="employee_id" class="form-control" id="employee_id" style="width:409px">
                                    <?php
                                    if (!empty($employees))
                                    {
                                        foreach ($employees as $index => $row)
                                        {
                                    ?>
                                            <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            <?php
                            }
                            else
                            {
                                echo $order_status_details['employee_name'];
                                echo "<input type='hidden' name='employee_id' value='{$order_status_details['employee_id']}'>";
                            }
                            ?>
                        </div>
                        

                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-3"></div>

                        <br/><br/><br/>

                        <?php
                        if ($next_raw_status !== 'collected_at_warehouse')
                        {
                        ?>
                        <label for="textfield" class="control-label col-sm-3">Cash Collected</label>

                        <div class="col-md-3">
                            <div class="input-group">
                                <?php
                                if (!empty($next_status['cash_collection']))
                                {
                                ?>
                                <input type="number" name="cash_collected" class="form-control datepicker">
                                <span class="input-group-addon">
                                    <span class="fa glyphicon-usd"></span>
                                </span>
                                <?php
                                }
                                else
                                {
                                    echo '--';
                                }
                                ?>
                            </div>
                        </div>
                        
                        <label for="textfield" class="control-label col-sm-3">Voucher Cash</label>

                        <div class="col-md-3">
                            <div class="input-group clockpicker">
                                <?php
                                if (!empty($next_status['voucher_cash']))
                                {
                                ?>
                                <input type="number" name="voucher_cash" class="form-control" value="">
                                <span class="input-group-addon">
                                    <span class="fa glyphicon-usd"></span>
                                </span>
                                <?php
                                }
                                else
                                {
                                    echo '--';
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        }
                        else
                        {
                        ?>
                            <label for="textfield" class="control-label col-sm-3">Shipment Batch Id</label>

                            <div class="col-md-3">
                                <select data-rule-required="true" name="shipment_batch_id" class="form-control" id="shipment_batch_id" style="width:409px">
                                    <?php
                                    if (!empty($shipment_batches))
                                    {
                                        foreach ($shipment_batches as $index => $row)
                                        {
                                    ?>
                                            <option value="<?=$row['id']?>"><?=$row['batch_name']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php
                        }
                        ?>


                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row">
                        <label for="textfield" class="control-label col-sm-3">Comments</label>
                        <div class="col-md-9">
                            <div class="input-group clockpicker">
                                <textarea name="comments" style="width:418px;height:83px"  class='form-control' placeholder="Comments"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="escalateButton">Save</button>
      </div>
    </div>
  </div>
</div>
    
<?php
    }
    
    if (!empty($message))
    {
        $class = empty($error) ? 'alert-success' : 'alert-danger';
    ?>
        <div class="alert <?=$class?> alert-dismissable" style="margin-top:20px" role="alert"><?=$message?></div>
    <?php
    }

    if (!empty($payment_reference_details))
    {
        $payment_ref_message = "Payment reference <a href='".base_url() . "admin/commission/viewPaymentRefLineItemsDetailed/" . $payment_reference_details['id']. "'>{$payment_reference_details['payment_reference']}</a> created for this order. Escalation/deescalation may leave database in incosistent stage. ";
    ?>
        <div class="alert alert-danger alert-dismissable" style="margin-top:20px" role="alert"><?=$payment_ref_message?></div>
    <?php
    }
    ?>
    
    <div class="page-header">
        <div class="pull-right">
            <div class="right-btn-add"> 
                <?php 
                if (!empty($next_status))
                {
                ?>
                <button data-target="#myModal" data-toggle="modal" class="btn btn-primary"><i class="fa glyphicon-up_arrow"></i>Escalate Status</button>
                <?php
                }
                else if(empty($message) && empty($manual_esc_not_possible))
                {
                    echo '<div class="alert alert-warning" style="margin-top:20px" role="alert">Order Cycle Escalation Not Possible.</div>';
                }
                else if (empty($message))
                {
                    echo '<div class="alert alert-warning" style="margin-top:20px" role="alert">Can\'t escalate, order cycle completed.</div>';
                }
                ?>
                <!--<a href="<?php echo base_url(); ?>admin/order/orderBookingForm" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a>-->
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <form action="" id="myForm">
                    <input type="hidden" name="order_id" value="<?=$order_id?>">
                    <div class="box box-color box-bordered">
                        <div class="box-title">
                            <h3>
                                <i class="fa fa-table"></i>
                                Order Status List For <b><?=$order_number?></b>
                            </h3>
                        </div>
                        <!--<div class="box-content nopadding">-->
                            <table class="table table-hover table-nomargin table-bordered" id="menuTable">

                            </table>
                        <!--</div>-->
                    </div>
                    
                    <div style="text-align: center;margin-top:20px">
                        <button id="submitButtonForm" type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                        <a href="<?php echo base_url(); ?>admin/order/index" class="btn default">Reset</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>


<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


<script>
jQuery(document).ready(function () {
    function initTables()
    {   
        $('#menuTable').dataTable({
            "bFilter": false,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/order/getOrderStatusData/"+ <?=$order_id?>, //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "serial_no", "sTitle": "Serial #", "bSortable": false},
                {"mDataProp": "status", "sTitle": "Status", "bSortable": false},
                {"mDataProp": 'updated_at', "sTitle": "Updated At", "bSortable": false},
                {"mDataProp": 'employee', "sTitle": "Employee", "bSortable": false},
                {"mDataProp": 'qr_manual_entry', "sTitle": "QR Manual Entry", "bSortable": false},
                {"mDataProp": 'cash_collected', "sTitle": "Cash Collected ($)", "bSortable": false},
                {"mDataProp": 'voucher_cash', "sTitle": "Voucher Cash ($)", "bSortable": false},
                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
                {"mDataProp": "delete", "sTitle": "Delete", "bSortable": false},
                {"mDataProp": "redelivery", "sTitle": "Redelivery", "bSortable": false}
            ],
            "bLengthChange": false,
            "bSort": true
        });
    }
    
    $('input[name="paid_to_driver"]').click(function (event){
        if ($(this).val() == 'yes')
        {
            $('#amount_container').show('fast');
        }
        else
        {
            $('#amount_container').hide('fast');
            $('#commission_amount').val("0")
        }
    })
    
    $('#myForm').submit(function (event){
        event.preventDefault();
        $('#loadingDiv_bakgrnd').show();
        data = $('#myForm').serializeObject();
        
        $.ajax({
            data:data,
            url: "<?=base_url()?>admin/order/saveCashVoucherCollectionDetails",
            cache: false,
            dataType : 'json',
            type : 'post',
        })
        .done(function( response ) {            
            $('#loadingDiv_bakgrnd').hide();
            window.location.href='<?= base_url() ?>admin/order?haveSideBar=0'
        });
        
    })
    
    
    $('#escalateButton').click(function (){
        $('#loadingDiv_bakgrnd').show();
         $(this).attr('disabled', true);
        $.ajax({
            data:$('#escalateForm').serialize(),
            url: "<?=base_url()?>admin/order/manualStatusEscalation",
            cache: false,
            dataType : 'json',
            type : 'post',
        })
        .done(function( response ) {
            if (response.status == 'error')
            {
                redirectURL = "<?=base_url()?>admin/order/orderStatusListing/<?=$order_id?>/error";
            }
            else
            {
                redirectURL = "<?=base_url()?>admin/order/orderStatusListing/<?=$order_id?>";
            }
            
            $('#myModal').modal('toggle');
            $('#loadingDiv_bakgrnd').hide(); 
            window.location.href = redirectURL;
        });
    })
    
    $('body').on('click', '.fake-redelivery-class', function (){
        $('#loadingDiv_bakgrnd').show();
        $('#redelivery-history-container').hide();
        $('.fake-redelivery-entry-form').show();
        
        status_trans_id = $(this).attr('rel');
        $("#order_status_trans_id").val(status_trans_id);
        
        $('#redeliveryModal').modal('toggle');
        $('#loadingDiv_bakgrnd').hide(); 
    })
    
    $('body').on('click', '.fake-redelivery-history-class', function (){
        status_trans_id = $(this).attr('rel');
        $("#order_status_trans_id").val(status_trans_id);
        
        $('#loadingDiv_bakgrnd').show(); 
        $('.fake-redelivery-entry-form').hide();
        $('#redelivery-history-container').show();
        $('#redeliveryModal').modal('toggle');
        $('#loadingDiv_bakgrnd').hide(); 
    })
    
    $('body').on('click', '.fake-class-redelivery-delete-history', function (){
        delete_message = $(this).attr('data-message');
        if (confirm(delete_message) == true)
        {
            $('#loadingDiv_bakgrnd').show();
            previous_driver_id = $(this).attr('data-previous-driver-id');
            update_driver_id_in_status = $(this).attr('data-update-driver-id-in-status');

            redelivery_trans_id = $(this).attr('rel');
            order_status_trans_id = $('#order_status_trans_id').val();

            $.ajax({
                data:{redelivery_trans_id:redelivery_trans_id, order_status_trans_id:order_status_trans_id, previous_driver_id:previous_driver_id, update_driver_id_in_status:update_driver_id_in_status},
                url: "<?=base_url()?>admin/order/deleteRedeliveryHistory",
                cache: false,
                type : 'post',
            })
            .done(function( response ) {
                            
                $('#redeliveryModal').modal('toggle');
                $('#loadingDiv_bakgrnd').hide();

                alert('Redelivery info deleted successfully.');

                redirectURL = "<?=base_url()?>admin/order/orderStatusListing/<?=$order_id?>";
                window.location.href = redirectURL;

            });
            $('#loadingDiv_bakgrnd').hide(); 
        }
    })
    
    $('#redeliveryButton').click(function (event){
    
        if ($('#redelivery_employee_id').val() == '')
        {
            alert('Please select driver.');
            event.preventDefault();
            return;
        }
        
        $('#loadingDiv_bakgrnd').show();
        
        $.ajax({
            data:$('#redeliveryForm').serialize(),
            url: "<?=base_url()?>admin/order/saveRedeliveryData",
            cache: false,
            dataType : 'html',
            type : 'post',
        })
        .done(function( response ) {            
            $('#redeliveryModal').modal('toggle');
            $('#loadingDiv_bakgrnd').hide();
            
            alert('Redelivery info saved successfully.');
            
            redirectURL = "<?=base_url()?>admin/order/orderStatusListing/<?=$order_id?>";
            window.location.href = redirectURL;
        });
    })
    
    initTables();
});
</script>
