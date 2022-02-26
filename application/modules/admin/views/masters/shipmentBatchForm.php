<div class="page-content main_container_padding">
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form id="myForm" action="<?php echo base_url(); ?>admin/masters/addShipmentBatch" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            
                                            <?php
                                            $errors = validation_errors();
                                            if (!empty($errors)) {
                                                ?>
                                                <div class="alert alert-danger active">
                                                    <button class="close" data-dismiss="alert"></button>
                                                    <span><?php echo $errors; ?></span>
                                                </div>
                                                <?php
                                            }
                                            ?> 
                                            <input id="ship_onboard_sms" type="hidden" class="form-control" name="ship_onboard_sms" value=""/>
                                            
                                            <div class="form-group">
                                                    <label for="batch_name" class="control-label col-sm-2">Batch Name/Id<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input id="batch_name" type="text" style="width:85px" class="form-control" placeholder="Batch Id" name="batch_name" value="<?php echo set_value('batch_name', empty($batch_name) ? "" : $batch_name); ?>" size="5" maxlength="5" required/>
                                                    </div>
                                            </div>
                                    
                                            
                                            <div class="form-group">
                                                    <label for="booking_confirmation" class="control-label col-sm-2">Booking Confirmation<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                                        <input id="booking_confirmation" type="text" class="form-control" placeholder="Enter Booking Confirmation" name="booking_confirmation" value="<?php echo set_value('booking_confirmation', empty($booking_confirmation) ? "" : $booking_confirmation); ?>" style="width:50%" required/>
                                                    </div>
                                            </div>
                                    
                                            <div class="form-group">
                                                    <label for="container_type" class="control-label col-sm-2">Container Type<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        if (!empty($container_types))
                                                        {
                                                        ?>
                                                        <Select required name="container_type" id="container_type"  class="form-control" style="width:117px">
                                                            <option value="">--Select--</option>
                                                            <?php
                                                            foreach ($container_types as $index => $type)
                                                            {
                                                                $selected = !empty($container_type) && $container_type == $type ? "Selected = 'selected'" : '';
                                                            ?>
                                                                <option <?=$selected?> value="<?=$type?>"><?=$type?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </Select>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                            </div>
                                    
                                            <div class="form-group">
                                                    <label for="quantity" class="control-label col-sm-2">Quantity<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <Select required name="quantity" id="quantity"  class="form-control" style="width:90px">
                                                            <?php
                                                            for($i = 1; $i <= 10; $i++)
                                                            {
                                                                $selected = !empty($quantity) && $quantity == $i ? "Selected = 'selected'" : '';
                                                            ?>
                                                                <option <?=$selected?> value="<?=$i?>"><?=$i?></option>
                                                            <?php
                                                            }
                                                            ?>
                                                        </Select>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="vessel_name" class="control-label col-sm-2">Vessel Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="vessel_name" class="form-control" placeholder="Enter Vessel Name" name="vessel_name" value="<?php echo set_value('vessel_name', empty($vessel_name) ? "" : $vessel_name); ?>" style="width:25%"  required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="voyage_number" class="control-label col-sm-2">Voyage Number<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="voyage_number" class="form-control" placeholder="Enter Voyage Name" name="voyage_number" value="<?php echo set_value('voyage_number', empty($voyage_number) ? "" : $voyage_number); ?>" style="width:25%"  required/>
                                                    </div>
                                            </div>
                                                        
                                            <div class="form-group">
                                                    <label for="volume" class="control-label col-sm-2">ETAs<span class="required">*</span></label>
                                                    
                                                    <div class="col-sm-3">
                                                        <b>Singapore<span class="required">*</span></b>
                                                        <input type="text" id="eta_singapore" class="form-control" name="eta_singapore" value="<?=empty($eta_singapore) ? '' : $eta_singapore;?>" style="width:40%" required/>
                                                    </div>
                                                    
                                                    <div class="col-sm-3">
                                                        <b>Jakarta<span class="required">*</span></b>
                                                        <input type="text" id="eta_jakarta" class="form-control" name="eta_jakarta" value="<?=empty($eta_jakarta) ? '' : $eta_jakarta;?>" style="width:40%"  required/>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <b>POS Indonesia<span class="required">*</span></b>
                                                        <input type="text" id="eta_postki" class="form-control" name="eta_postki" value="<?=empty($eta_postki) ? '' : $eta_postki;?>" style="width:25%"  required/>
                                                    </div>
                                            </div>
                                                    
                                            <div class="form-group">
                                                    <label for="bl_number" class="control-label col-sm-2">BL Number</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="bl_number" class="form-control" placeholder="Enter BL Number" id="bl_number" name="bl_number" value="<?php echo set_value('bl_number', empty($bl_number) ? "" : $bl_number); ?>" style="width:25%"/>
                                                    </div>
                                            </div>

                                            <div class="form-group">
                                                    <label for="container_number" class="control-label col-sm-2">Container Number</label>
                                                    <div class="col-sm-10">
                                                        <input maxlength="16" type="text" id="container_number" class="form-control" placeholder="Enter Container No." id="container_number" name="container_number" value="<?php echo set_value('container_number', empty($container_number) ? "" : $container_number); ?>" style="width:15%"/>
                                                    </div>
                                            </div>

                                    
                                            <div class="form-group">
                                                    <label for="seal_number" class="control-label col-sm-2">Seal Number</label>
                                                    <div class="col-sm-10">
                                                        <input maxlength="16" type="text" id="seal_number" class="form-control" placeholder="Enter Seal No." id="seal_number" name="seal_number" value="<?php echo set_value('seal_number', empty($seal_number) ? "" : $seal_number); ?>" style="width:15%"/>
                                                    </div>
                                            </div>
                                                                                                        
                                            <div class="form-group">
                                                    <label for="ship_onboard" class="control-label col-sm-2">Ship Onboard</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="ship_onboard" class="form-control" placeholder="Ship Onboard" id="ship_onboard" name="ship_onboard" value="<?php echo set_value('ship_onboard', empty($ship_onboard) ? "" : $ship_onboard); ?>" style="width:11%" />
                                                    </div>
                                            </div>
                                                    
                                            <div class="form-group">
                                                    <label for="load_date" id='load_date_title' class="control-label col-sm-2">Load Date</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" id="load_date" class="form-control" placeholder="Load Date" id="load_date" name="load_date" value="<?php echo set_value('load_date', empty($load_date) ? "" : $load_date); ?>" style="width:11%" />
                                                    </div>
                                            </div>
                                    
                                            <div class="form-group">
                                                <label for="consignee_order_id" class="control-label col-sm-2">Consignee Order Id<span class="required">*</span></label>
                                                <div class="col-sm-10">
                                                    <input type="text" id="consignee_order_id" class="pull-left form-control" placeholder="Consignee Id" id="load_date" name="consignee_order_id" value="<?php echo set_value('consignee_order_id', empty($consignee_order_id) ? "" : $consignee_order_id); ?>" size="<?=$order_number_size_in_digits?>"style="width:120px" maxlength="<?=$order_number_size_in_digits?>" required/>
                                                    <div id="orderNumberErrorMessageContainer" style="margin-bottom:0px;margin-left:10px;padding-left: 10px;padding-top:7px;padding-bottom:7px;" role="alert" class="hide pull-left alert alert-danger">Order number does not exist.</div>
                                                </div>
                                                
                                            </div>
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-4">
                                                <button id="submitBtn" type="button" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                                <a href="<?php echo base_url(); ?>admin/masters/shipmentBatchList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>

<div class="modal fade" id="ship_onboardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Ship onboard is updated. Please enter password to send sms notification to customer</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-3">
                        <label>Password</label>
                    </div>
                    <div class="col-lg-7">
                        <input type="password" id="password" class="form-control" name="password" autocomplete="off">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="checkPasswordButton">Confirm</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function (){
    $('#eta_jakarta').datepicker({
        dateFormat: "dd/mm/yy"
    })
    $('#eta_singapore').datepicker({
        dateFormat: "dd/mm/yy"
    })
    $('#eta_postki').datepicker({
        dateFormat: "dd/mm/yy"
    })
    $('#load_date').datepicker({
        dateFormat: "dd/mm/yy"
    })
    $('#ship_onboard').datepicker({
        dateFormat: "dd/mm/yy"
    })
    
    $('#submitBtn').on('click', function(e)
    {
        e.preventDefault();
        
        var id_value = '<?= set_value('id', empty($id) ? "" : $id); ?>';
//        if(typeof(id_value) == 'undefined' || id_value == null || id_value == '')
        {
            var ship_onboard = $('#ship_onboard').val();
            if(typeof(ship_onboard) != 'undefined' && ship_onboard != null && ship_onboard != '')
            {
                $("#ship_onboardModal").modal('show');
            }
            else
            {
                $('#myForm').trigger('submit');
            }
        }
//        else
//        {
//            $('#myForm').trigger('submit');
//        }
    });
    
    $('#checkPasswordButton').on('click', function(e)
    {
        var password = $('#password').val();
        var configurable_password = '<?= $configurable_password ?>';
        if(configurable_password == password)
        {
            $('#ship_onboard_sms').val('1');
            $('#myForm').trigger('submit');
        }
        else
        {
            $('#ship_onboard_sms').val('0');
             $('#myForm').trigger('submit');
        }
//        else if(typeof(password) == 'undefined' || password == null || password == '')
//        {
//           alert('please insert password.');
//        }
//        else
//        {
//            alert('please insert correct password.');
//        }
    });
    
    $('#ship_onboard').on('change', function()
    {
       var ship_onboard = $('#ship_onboard').val();
       if(typeof(ship_onboard) != 'undefined' && ship_onboard != null && ship_onboard != '')
       {
           $('#load_date_title').html('Load Date<span class="required">*</span>'); 
       }
       else
       {
           $('#load_date_title').html('Load Date'); 
       }
    });
    
    $('#ship_onboard').trigger('change');
    
    $('#myForm').submit(function(event) {
        
        var fromDate = $('#load_date').val();
        var EndDate = $('#ship_onboard').val();
        
        if(typeof(EndDate) != 'undefined' && EndDate != null && EndDate != '')
        {
            if(typeof(fromDate) == 'undefined' || fromDate == null || fromDate == '')
            {
                event.preventDefault()
                alert("Load Date is required.")
                return false;
            }
            fromDate = fromDate.split('/')
            fromDate = fromDate[2] + '-' + fromDate[1] + '-' + fromDate[0];

            EndDate = EndDate.split('/')
            EndDate = EndDate[2] + '-' + EndDate[1] + '-' + EndDate[0];

            fromDate = new Date(fromDate);
            EndDate = new Date(EndDate);

            if (fromDate > EndDate)
            {
                event.preventDefault()
                alert("Load Date should be lesser than Ship Onboard Date.")
                return false;
            }
        }
    });
    
    $('#consignee_order_id').on('focusout',function(e){ 

       var order_number = $(this).val();
       var orderTxtId = $(this).attr('id');

        if(order_number != "")
        {
            $('#loadingDiv_bakgrnd').show();

             $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        data: {order_number : order_number, check_only_order_number :1},
                        url: "<?= base_url(); ?>admin/order/checkOrderNumberExistAndStatus",
                        success: function(data) { 

                            $('#orderNumberErrorMessageContainer').addClass('hide');

                            var status = data.status;
                            if(status == "error")
                            {
                                $('#submitBtn').attr('disabled', true);
                                
                                var errorMsg = data.errorMsg; 
                                $('#orderNumberErrorMessageContainer').removeClass('hide');
                            }
                            else
                            {
                                $('#submitBtn').attr('disabled', false);
                                $('#orderNumberErrorMessageContainer').addClass('hide');
                            }
                            
                            $('#loadingDiv_bakgrnd').hide();
                    }
                    
                });
        }
        else
        {
            $('#orderNumberErrorMessageContainer').addClass('hide');
            $('#submitBtn').attr('disabled', true);
        }
    });
    
    <?php 
    if (empty($id)) {
    ?>
    $('#submitBtn').attr('disabled',true);
    <?php
    }
    ?>
})
</script>