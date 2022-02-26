<form class="form-horizontal" role="form" id="updateOrderForm" name="updateOrderForm" method="post" action="">
    <input type="hidden" name="order_id" class="fake-order-id" value="<?= $order_id;?>">
    <input type="hidden" name="is_weight_capture" value="<?= $is_weight_capture;?>" id="isWeightCapture" />
    <input type="hidden" name="weight" value="<?= $weight;?>" id="weight" />
        <?php
        if($is_weight_capture == "yes")
        {
            ?>              
                <div class="form-group" id="weightContainer">
                    <label for="textfield" class="control-label col-sm-4"><b>Weight *</b></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa glyphicon-dumbbell"></i>
                                </span>
                                <input type="text" data-rule-required="true" name="jkt_weight"  value='<?= empty($jkt_weight) ? '0.00' : $jkt_weight ?>' placeholder='0.00'  class='input-large form-control' style="width:90px" id="jkt_weight"/>
                                
                                <span style="color:green;padding-right: 20px" class="pull-right"><b>Singapore Weight : <i id="singapore_weight"><?= $weight;?></i></b></span>
                            </div>
                        </div>
            </div>
            <div class="form-group ">                
                <label class="control-label col-sm-4"><b>Reference Number *</b></label>
                <div class="col-sm-3">
                    <input type="text" data-rule-required="true" name="jkt_reference_no" value='<?= empty($jkt_reference_no) ? '' : $jkt_reference_no ?>'  class='input-large form-control' style="width:350px" />
                </div>
            </div>
        <?php }?>           
            <div class="form-group ">                
                <label for="textfield" class="control-label col-sm-4"><b>Received Date</b></label>

                <div class="col-md-4">
                        <?php if($order_status == "received_at_jakarta_warehouse") { ?>
                        <div class="input-group">
                        <input type="text" name="received_date" id="received_date" class="form-control datepicker" value='<?=$jkt_received_date;?>' style="width:150px">
                            
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                        </div>
                        <?php } else { ?>
                        <div class="input-group" style="width:350px;">
                        <input style="width:150px;" readonly name="received_date"  class="form-control" value='<?=$jkt_received_date;?>' style="width:150px">
                        <span style="width:150px;"> <b><?=strtoupper($display_status_text);?></b></span>
                        </div>
                        <?php } ?>
                </div>
            </div>
             <div class="form-group ">                
                <label class="control-label col-sm-4"><b>Receiver</b></label>
                <div class="col-sm-3">
                    <input type="text" name="jkt_receiver" value='<?= empty($jkt_receiver) ? '' : $jkt_receiver ?>'  class='input-large form-control' style="width:350px" />
                </div>
            </div>


    </div>
    <div>
     <input type="submit" class="btn btn-primary" id="updateOrderDetails" value="Save" />
    </div>
</form>
<script>
jQuery(document).ready(function () {
  $('#received_date').datepicker({
            format: "dd/mm/yyyy"
        });
        
        $('#updateOrderForm').submit(function (e){
            e.preventDefault();
            if ($('#updateOrderForm').valid())
           {
            var isWeightCapture = $('#isWeightCapture').val();
            if(isWeightCapture == "yes")
            {
                var jkt_weight = parseFloat($('#jkt_weight').val());
                var weight = parseFloat($('#weight').val());
               if(jkt_weight <= 0.00)
               {
                   alert('Please enter weight');
                    return false;
               }
               else
               {
                    if (jkt_weight != weight)  
                    {
                       var isCheck = confirm('Weight mismatch. Do you still want to continue?');
                          if (!isCheck)
                            return false;
                    } 
               }
            }
            
                $('#loadingDiv_bakgrnd').show();            
                data = $('#updateOrderForm').serialize();
               
                $.ajax({
                    data:data,
                    url: "<?=base_url()?>admin/receiving_batch/updateOrderDetails",
                    cache: false,
                    dataType : 'json',
                    type : 'post',
                })
                .done(function( response ) { 
//                    console.log(response);return false;
                    $('#loadingDiv_bakgrnd').hide();
                    var status = response.status;      
                    var msg = response.msg;
                    if(status == "error")
                    {
                        alert(msg);
                    }
                    else
                    {
                    alert('Order details updated successfully!');
                    $('#receivingBatchModal').modal('toggle');
//                    $('#msgContainer').removeClass('hide')

//                    window.location.href = "<?=base_url()?>admin/receiving_batch/";
                  }
                });
                }
        });
         $('#updateOrderForm').validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
//                 ignore: "",
                rules: { 
                   jkt_weight : {
                       required : true                      
                   },
                   jkt_reference_no :
                   {
                       required : true
                    }
                },
                });

});
</script>
