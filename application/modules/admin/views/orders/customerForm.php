
<div id="dsmain" class="page-content main_container_padding">


    <div id="messageHolder" class="hide alert alert-warning alert-dismissable">
        <button data-dismiss="alert" class="close" type="button">×</button>
        <strong>Success!</strong>Record saved.
    </div>

    <div class="row">
            <div class="span12" style="width:95%">
                    <div class="box">
                        <div class="box-title">
                            <h3>
                                    <i class="fa fa-user"></i>
                                    Edit Customer
                            </h3>
                            <div class="pull-right">
                                <div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/order/customerList?haveSideBar=0" class="btn default">Back</a> </div>
                            </div>

                        </div>
                                
    
                        
                        
                    <div class="box-content nopadding" id="myForm">
                            <!--<form action="#" name="myForm" id="myForm" method="POST" class='form-horizontal form-bordered'>-->
                                    <div class="form-group">

                                            <label for="customer" class="control-label col-sm-2">Name
                                            </label>
                                            <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                            <input type="text" placeholder="Customer Name" name="customer_name" id='customer' class='form-control' data-rule-required="true" value='<?=$data['name']?>'>
                                                            <input type="hidden"  name='customer_id' id='customer_id' value="<?=$customer_id?>">
                                                            
                                                            <input type="hidden"  name='lattitude' id='lattitude' value='<?=$data['lattitude']?>'>
                                                            <input type="hidden"  name='longitude' id='longitude' value='<?=$data['longitude']?>'>
                                                            
                                                    </div>
                                            </div>
                                        
                                            <label for="textfield" class="control-label col-sm-2">
                                                Postal Code
                                                <a href="#" class="pull-right"  id="pinLookUpLink"><i class="glyphicon-search"></i></a>
                                                <a href="#" title="Clear Fields" style="padding-right:4px" class="pull-right" id="clearAddressFields"><i class="glyphicon-minus-sign"></i></a>
                                            </label>
                                            <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <input type="text" placeholder="Postal Code" class='form-control' id="pinCodeText" name="pin" value='<?=$data['pin']?>'>
                                                            <span id="pinCodeLocation" class="input-group-addon">
                                                                    <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                    </div>
                                            </div>
                                        
                                    </div>
                                    <br><br><br>
                                    <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Mobile / Residence</label>
                                            <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                                            <input type="text" placeholder="Mobile" id="mobile" class='form-control' name="mobile" data-rule-required="true" value='<?=$data['mobile']?>'>

                                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                            <input type="text" placeholder="Phone" id="phone" class='form-control' name="residence_phone" value='<?=$data['residence_phone']?>'>
                                                    </div>
                                            </div>
                                        
                                            
                                            <label for="textfield" class="control-label col-sm-2">Building/Estate</label>
                                            <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <input type="text" id="buildingTextBox" placeholder="Building/Estate" class='form-control' name="building" value='<?=$data['building']?>'>
                                                            <span class="input-group-addon">
                                                                    <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                    </div>
                                            </div>
                                            
                                    </div>
                                    <br><br>

                                    <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">Customer Type</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">                                                            
                                                <select id="customer_type" name="customer_type[]" multiple class="form-control">
                                                    <?php
                                                    foreach ($customer_type as $index => $row)
                                                    {                                                                      
                                                        $selected = array_key_exists($row['customer_type_id'], $customer_type_selected) ? "selected='selected'" : '';
                                                    ?>
                                                        <option value="<?=$row['customer_type_id']?>" <?= $selected ?>><?=$row['customer_type']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <label for="textfield" class="control-label col-sm-2">
                                            Media Type
                                        </label>
                                        <div class="col-sm-4">
                                            <div class="input-group">                                                         
                                                <select id="media_type" name="media_type[]" multiple class="form-control">
                                                    <?php
                                                    foreach ($media_type as $index => $row)
                                                    {  
                                                        $selected = array_key_exists($row['media_type_id'], $media_type_selected) ? "selected='selected'" : '';
                                                    ?>
                                                        <option value="<?=$row['media_type_id']?>" <?= $selected ?>><?=$row['media_type']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <br><br>

                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2">Email</label>
                                            <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <span class="input-group-addon">@</span>
                                                            <input type="text" placeholder="Email" id="email" class='form-control' name="email" value='<?=$data['email']?>'>
                                                    </div>
                                            </div>

                                            
                                            
                                            <label for="textfield" class="control-label col-sm-2">
                                                Street
                                            </label>
                                            <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <input type="text" placeholder="Street" class='form-control' id="streetTextBox" name="street" value='<?=$data['street']?>'>
                                                            <span id="pinCodeLocation" class="input-group-addon">
                                                                    <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                    </div>
                                            </div>
                                    </div>

                                    
                                    <br><br>
                                    <div class="form-group">
                                        <?php
                                                    $userId = $this->session->userdata['id'];
                                                    $checkBlacklist = canPerformAction('blacklist', $userId);
                                                    if($checkBlacklist == TRUE)
                                                    {
                                                        $readonly = "";
                                                    }
                                                    else
                                                    {
                                                        $readonly = "disabled = 'disabled'";
                                                    }
                                                        ?>
                                                    <!--<label for="textfield" class="control-label col-sm-2">Blacklist Customer </label>-->
                                                    <label for="textfield" class="control-label col-sm-2"><i class="fa fa-user"></i></label>
                                                    <div class="col-sm-4">

                                                        <div class="input-group">
                                                            <input type="checkbox" name="blacklistCustomer" value="1"  class="form-control searchFormClass" id="blacklistCustomer" style="-webkit-appearance: checkbox; width:15px;" <?php echo $readonly;?>>
                                                        </div>
                                                    </div>
                                                    <?php // } ?>
                                            <label for="textfield" class="control-label col-sm-2">Block : Unit </label>
                                            <div class="col-sm-4">

                                                <div class="input-group">
                                                    
                                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                        <input type="text" id="blockTextBox" placeholder="Block" class='form-control' name="block" value='<?=$data['block']?>'>
                                                        
                                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                        <input type="text" id="unitTextBox" placeholder="Unit" class='form-control' name="unit" value='<?=$data['unit']?>'>

                                                </div>
                                            </div>
                                    </div>
                                    <br><br>
                                    <input type="hidden" name="blacklistCustomerId" value="<?php echo $blacklist_customerId;?>"/>
                                    <?php
//                                    if($checkBlacklist == TRUE)
//                                                    {?>                                    
                                    <div class="form-group">                                                    
                                        <label for="textfield" class="control-label col-sm-2">Comment</label>
                                        <div class="col-sm-4">
                                            <textarea name="bcomments" id="b_comments" <?php echo $readonly;?> style="width:418px;height:83px"  class='form-control' placeholder="Comments"></textarea>
                                        </div>


                                    </div>
                                    <?php // } ?>
                                    <div class="form-group">
                                        <label for="textfield" class="control-label col-sm-2">&nbsp;</label>
                                        <div class="col-sm-4">
                                            <button type="reset" class="btn btn-primary" >Reset</button>
                                            <button type="button" class="btn btn-primary" id="saveCustomerOnlyButton">Save Customer</button>
                                            <?php
                                        
                                            $canEditAccess = canPerformAction('passport_img_update', $userId);
                                            if ($data['passport_img'] || $data['passport_id_number'])
                                            {
                                                ?>
                                                <span class="passport_phont_link">
                                                    <label for="textfield" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="#" path="<?= $data['passport_img'] ?>" class="passport_img_show_model_link"><?= $data['passport_id_number'] ?></a>
                                                    </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php if ($canEditAccess === TRUE)
                                                    {
                                                        ?>
                                                        <a href='#' customer_id='<?= $data['id'] ?>' customer_name='<?= $data['name'] ?>' id_number='<?= $data['passport_id_number'] ?>' passport_img="<?= $data['passport_img'] ?>" class='passport_img_update_model'>
                                                            <i class='fa fa-refresh'></i>
                                                        </a>
                                                <?php } ?>
                                                </span>
                                    <?php } ?>
                                        </div>
                                    </div>
                                    <!--
                                    <div class="form-actions">
                                            <button type="button" id="customerFormSubmitButton" class="btn btn-primary">Save changes</button>
                                            <button type="button" class="btn">Cancel</button>
                                    </div>
                            </form>-->
                    </div>
                        
                        
                        
                        
                    </div>
                
            </div> 
    </div> 
</div> 


<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


    <script src="<?php echo base_url(); ?>assets/public/js/jquery-validate/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function (){
    var blacklist = "<?php echo $blacklist;?>";
    if(blacklist == "Yes")
    {
        $('#blacklistCustomer').attr('checked', true);
        $("textarea#b_comments").text("<?php echo $blacklist_comment;?>");
    }
    else
    {
        $('#blacklistCustomer').attr('checked', false);
    }
    $("#customer_type").multiselect({
        buttonWidth: '100%'
    });
    $("#media_type").multiselect({
        buttonWidth: '400px'
    });
    $('#clearAddressFields').click(function (event){
        $('#buildingTextBox').val('');
        $('#streetTextBox').val('');
        $('#pinCodeText').focus();
    })
    
    $('.passport_img_show_model_link').click(function (){

        var path = $(".passport_img_show_model_link").attr("path");
        if(path != '')
        {
           var folder_path = "<?= base_url()?>./assets/img/customer_passport/"+path;
           $("#passport_img_show_model").find(".modal-body").html('<img src="'+folder_path+'" alt="Passport photo not found" style="height: auto; width: 100%">');
        }
        else
        {            
            $("#passport_img_show_model").find(".modal-body").html('Passport photo not found.');
        }
        $("#passport_img_show_model").modal("show");
    })
    
    $('.passport_img_update_model').click(function (){
            
        $("#passport_img_update_model").modal("show");
    })
    
    <?php  $lang = ($this->uri->segment(2)) ? $this->uri->segment(2) : "en"; ?>
    $('#form_horizontal').validate({
        rules: 
        {
            phone_input: {
                required: true
            },
            order_input: {
                required: true
            }
        },
        messages: 
        {
            phone_input: {
                required: "Please Enter Mobile Number."
            },
            order_input: {
                required: "Please Enter ID Number."
            }
        },
        submitHandler: function (form) 
        {
            var formData = new FormData($("#form_horizontal")[0]);
             $.ajax({
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                url: "<?php echo base_url().'admin/order/customer_passport_update'; ?>",
                success: function (res)
                {
                    var status = res.status;
                    var msg = res.msg;
                    var view = res.view;
                    if (status == "error")
                    {
                        $('#errorMsg_reg').removeClass('hidden');
                        $('#errorMsg_reg').html('<span>' + msg + '</span>');
                        alert(msg);
                    }
                    else
                    {
                        $(':input', '#form_horizontal')
                        .removeAttr('checked')
                        .removeAttr('selected')
                        .not(':button, :submit, :reset, :hidden, :radio, :checkbox')
                        .val('');
                        alert(msg);
                        location.reload();
                    }
                }
            });
        }
    });
    
    $('#pinLookUpLink, #pinLookUpLink_Customer').click(function(){
        obj = 'pinCodeText';
        var pincode =  $('#'+obj).val();
        
        if (pincode == '')
        {    
            bootbox.alert("Please enter postal code first.", function() {
            });
        }
        else
        {
            $('#img_load_chart').html('Pulling address info.');
            $('#loadingDiv_bakgrnd').show();
            
            $.ajax({
                data:{pincode:pincode},
                //url: "<?=base_url()?>admin/order/getAddressByPinCode",
                url: "<?=base_url()?>admin/order/getAddressByPinCodeFromDB",
                cache: false,
                dataType : 'json',
                type : 'post',
            })
            .done(function( response ) {
                if (obj == 'pinCodeText')
                {
                    $('#blockTextBox').val(response.block);
                    $('#streetTextBox').val(response.street);
                    $('#buildingTextBox').val(response.building);
                    $('#lattitude').val(response.lattitude);
                    $('#longitude').val(response.longitude);
                    
                }
                else
                {
                    $('#orderCustomerDetails_Street').val(response.street);
                    $('#orderCustomerDetails_Building').val(response.building);
                    $('#orderCustomerDetails_Block').val(response.block);
                }
                
                $('#loadingDiv_bakgrnd').hide();            
            });
        }
    })
    
        
    $('#saveCustomerOnlyButton').click(function (event){
        $('#loadingDiv_bakgrnd').show();
        
        $.ajax({
            data:$('#myForm :input').serializeObject(),
            url: "<?=base_url()?>admin/order/saveCustomer/1",
            type:'POST',
            dataType : 'json'
        })
        .done(function( response ) {
            if (response.status == 'success')
            {
                $('#messageHolder').removeClass('hide');
            }
            else
            {
                alert(response.message);
            }
            $('#loadingDiv_bakgrnd').hide();
        });
    });
    
    //alert box for delete button 
    $(".remove").click(function(){
        var customer_id = $(this).attr("customer_id");
        var passport_img = $(this).attr("passport_img");
        
        if(confirm('Are you sure to remove this record ?'))
        {    
            var postdata  = {customer_id :customer_id, passport_img : passport_img};
              $.ajax({
                type: 'POST',
                data :postdata,
                cache: false,
                dataType: 'json',
                url: '<?=base_url()?>admin/order/delete_passport_img_by_customer_id',
                success: function (res)
                {
                    var status = res.status;
                    var msg = res.msg;
                    if (status == "error")
                    {
                        alert(msg);
                    }
                    else
                    {
                        alert(msg);
                        location.reload();
                    }
                }
            })
            
        }
    });
});

</script>

<!-- Modal -->
<div id="passport_img_show_model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Show Passport</h4>
      </div>
        <div class="modal-body" style="height: auto">
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="passport_img_update_model" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" style="height: 800px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Passport</h4>
      </div>
        <div class="modal-body">
            <form id="form_horizontal" role="form" action="#" enctype="multipart/form-data">
                <div class="form-group customers" >
                    <input type="hidden" id="customer_id" name="customer_id[]" value="<?= $data['id']?>" ><?= $data['name']?>
                </div>
                <!-- Text input-->
                <div class="form-group">
                    <input id="id_number" name="id_number[]" placeholder="ID Number" value="<?= $data['passport_id_number']?>" class="form-control" required="" type="text">
                </div>
                <!-- file input-->
                <div class="form-group">
                    <input id="passport" name="passport[]" placeholder="Passport" style="border: 1px solid lightgrey; padding: 3px" class="col-md-11" type="file">
                   <?php if ($data['passport_img'])
                   { ?>
                        <button type="button" class="btn col-md-1 remove" passport_img="<?= $data['passport_img']?>" customer_id="<?= $data['id']?>"  style="background-color: #e63a3a; color: white">Delete</button>
                    <?php } ?>
                </div>                    
                <div class="modal-footer">
                   <button id="submit_button" style="margin-top:10px" name="submit_button" class="btn btn-primary" data-toggle="modal" href="result.php">upload</button>

                  <button type="submit" class="btn btn-default" style="margin-top:10px"  data-dismiss="modal">Close</button>
                  </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>
