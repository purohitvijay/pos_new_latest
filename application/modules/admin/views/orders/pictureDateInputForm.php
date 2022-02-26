<div class="page-content main_container_padding">
    <?php
     if (!empty($message))
     {
     ?>
         <div class="alert alert-success" style="margin-top:20px" role="alert"><?=$message?></div>
     <?php
     }
     ?>
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Picture Date Input</h3>
                            </div> 
                      
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/order/savePictureDateInput" class="form-horizontal form-bordered" method='post' id="pictureReceiveDate" name='boxForm'>
                                            
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Picture Receive Date<span class="required">*</span></label>
                                                    <div class="col-sm-10">                                                       
                                                        <input type="text" id="picture_receive_date" class="form-control" placeholder="Enter Picture Receive Date" name="pictureReceiveDate" style="width:25% ; margin-left: 10px!important;" required/>
                                                    </div>
                                            </div>  
                                            <div class="form-group" >
                                                    <label for="textfield" class="control-label col-sm-2">Order No.</label>
                                                    <div class="col-sm-10">     
                                                       <?php for($i=0; $i < 20 ; $i++)
                                                       {?>
                                                        <div class="order_number_div">
                                                        <input type="text" id="order_no_<?php echo $i;?>" class="ordernumber_textbox" placeholder="Order No." name="orderNo[]" maxlength="<?=$order_number_size_in_digits?>"/>
                                                        </div>
                                                       <?php }?>
                                                    </div>
                                            </div>  
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary" id="submitBtn"><?php echo mlLang('lblSubmitBtn'); ?></button>                                                    
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

<script type="text/javascript">
$(document).ready(function() {
    $("#picture_receive_date").datepicker
    ({
//            dateFormat: 'dd/mm/yy'
    });
    $('.ordernumber_textbox').on('focusout',function(e){ 

       var order_number = $(this).val();
       var orderTxtId = $(this).attr('id');

        if(order_number != "")
        {
            $('#loadingDiv_bakgrnd').show();

             $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        data: {order_number : order_number},
                        url: "<?= base_url(); ?>admin/order/checkOrderNumberExistAndStatus",
                        success: function(data) { 

                              $('#'+orderTxtId).removeClass('okBox');
                              $('#'+orderTxtId).removeClass('errorBox');
                              $('#'+orderTxtId).next('div .errorTxt').remove();

                          var status = data.status;
                          if(status == "error")
                          {
                               var errorMsg = data.errorMsg;
                              $('#'+orderTxtId).addClass('errorBox');
                              $('#'+orderTxtId).after('<div class="errorTxt"> '+errorMsg+'</div>');
                          }
                          else
                          {
                              $('#'+orderTxtId).addClass('okBox');
                          }
                        var isError = $("#pictureReceiveDate").find(".errorBox");                
                        if(isError.length == 0)
                        {
                             $('#submitBtn').attr('disabled',false);
                        }
                        else
                        {
                            $('#submitBtn').attr('disabled',true);
                        }   

                        $('#loadingDiv_bakgrnd').hide();
                    }
                    
                });
        }
        else
        {
            $('#'+orderTxtId).removeClass('okBox');
            $('#'+orderTxtId).removeClass('errorBox');
            $('#'+orderTxtId).next('div .errorTxt').remove();
        }
    });
    
    $('#submitBtn').attr('disabled',true);
});
</script>