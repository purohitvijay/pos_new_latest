<style type="text/css">
    .modal-dialog {
        margin: 30px auto;
        width: 80%;
    }
</style>
<?php
if($can_perform_edit === true)
{
    $submit_hidden = "hidden";
}
else
{
    $submit_hidden = "";
}

if(!empty($Disable_Order)){
    $disabled = 'disabled';
    $display_none = 'Display:none;';
  }
else 
  {
    $disabled = '';
    $display_none = '';
}

?>

<div id="dsmain" class="page-content main_container_padding">

<?php
    if (!empty($message))
    {
    ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><?=$message?></div>
    <?php
}
?>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title pull-left" id="myModalLabel">Quick LookUp Form</h4>
                    <small style="padding-top:10px;padding-right:15px" class="pull-right"><b>NOTE</b> : Press enter or click on search to look up.</small>
                    <div class="clear">&nbsp;</div>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" role="form" id="searchForm">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="form-group row">
<!--                                    <div class="col-md-3">

                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-mobile"></i>
                                            </span>
                                            <input type="text"  name="mobile" placeholder="Mobile" class='searchFormClass input-large form-control'>
                                        </div>

                                    </div>-->

                                    <div class="col-md-3">

                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </span>
                                            <input type="text"  name="phone" placeholder="Phone" class='searchFormClass input-large form-control'>
                                        </div>

                                    </div>
                                    <div class="col-md-4">


                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </span>
                                            <input type="text" name="name" placeholder="Name" id="mytext" class='searchFormClass input-large form-control'>
                                        </div>

                                    </div>
                                    <div class="col-md-4">

                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-location-arrow"></i>
                                            </span>
                                            <input type="text"  name="pin" placeholder="Address" class='searchFormClass input-large form-control'>
                                        </div>

                                    </div>

                                    <div class="col-md-1">
                                        <button class="btn btn-danger" type="submit"   style="background-color:#e63a3a">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row" id="searchResultContainer">
                    </div>    

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="selectCustomerButton">Select</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="orderHistoryModal">Order History For <b id="customerHistoryName">JSK</b></h4>
                </div>
                <div class="modal-body">

                    <div class="row" id="customerOrderHistoryContainer">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="selectCustomerButton">Select</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="span12">
            <div class="box">
                <div class="box-title">
                    <h3>
                        <i class="fa fa-edit"></i>
<?php
$building = empty($order_details['order']['building']) ? '' : "{$order_details['order']['building']}, ";
                                            $unit = empty($order_details['order']['unit']) ? "": ", {$order_details['order']['unit']}";

                                            $address = "$building{$order_details['order']['block']}". $unit.", {$order_details['order']['street']}, {$order_details['order']['pin']}";
?>
                                            Order Edit Form for <b><?=$order_details['order']['order_number'].', '. $order_details['order']['customer_name']. ", $address"?></b>
                    </h3>
                </div>
                <div class="box-content">
                    <form method="POST" onsubmit="return false;" class='form-horizontal form-wizard' id="ss">
                                        <input type="hidden"  name='manual_order_number' id='manual_order_number' value="<?=$order_details['order']['order_number']?>">
                                            <input type="hidden"  name='order_id' id='order_id' value="<?=$order_id?>">
                                            <input type="hidden"  name='lattitude' id='lattitude' value="<?=$order_details['order']['lattitude']?>">
                                            <input type="hidden"  name='longitude' id='longitude' value="<?=$order_details['order']['longitude']?>">

                                            <input type="hidden"  name='monitor_redelivery_case' id='monitor_redelivery_case' value="<?=empty($monitor_redelivery_case) ? 0 : 1?>">
                        <input type="hidden"  name='save_redelivery_data' id='save_redelivery_data' value="0">

                        <div class="step" id="firstStep">
                            <ul class="wizard-steps steps-3">
                                <li class='active'>
                                    <div class="single-step" style="border:1px solid  #e63a3a">
                                        <span class="title">
                                            1</span>
                                        <span class="circle">
                                            <span class="active"></span>
                                        </span>
                                        <span class="description">
                                            Customer Information
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="single-step">
                                        <span class="title">
                                            2</span>
                                        <span class="circle">
                                        </span>
                                        <span class="description">
                                            Order Information
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="single-step">
                                        <span class="title">
                                            3</span>
                                        <span class="circle">
                                        </span>
                                        <span class="description">
                                            Delivery Information
                                        </span>
                                    </div>
                                </li>
                            </ul>
                            <div class="step-forms">




                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="box box-bordered" style="border-top:1px solid #e7e7e7">

                                            <div class="box-content" id="myForm">
                                                <!--<form action="#" name="myForm" id="myForm" method="POST" class='form-horizontal form-bordered'>-->
                                                <div class="form-group">

                                                    <label for="textfield" class="control-label col-sm-2">Customer Name
                                                        <a href="#" class="pull-right" data-toggle="modal" style="padding-right:4px" data-target="#myModal" id="myModal"><i class="glyphicon-search"></i></a>
                                                        <a href="#" class="pull-right" data-toggle="modal" style="padding-right:4px"  data-target="#orderHistoryModal" id="customerHistory"><i class="glyphicon-history"></i></a>
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                            <input disabled="disabled" readonly type="text" placeholder="Customer Name" name="customer_name" id='customer' class='form-control' value="<?=$order_details['order']['customer_name']?>">
                                                            <input type="hidden"  name='customer_id' id='customer_id' value="<?=$order_details['order']['customer_id']?>">
                                                            <input type="hidden"  name='repeated_customer' id='repeated_customer' value="<?=$order_details['order']['repeated_customer']?>">


                                                        </div>
                                                    </div>

                                                    <label for="textfield" class="control-label col-sm-2">
                                                        Postal Code
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <input disabled="disabled" readonly  type="text" placeholder="Postal Code" class='form-control' id="pinCodeText" name="pin" value="<?=$order_details['order']['customer_pin']?>" disabled >
                                                            <span id="pinCodeLocation" class="input-group-addon">
                                                                <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Mobile / Residence</label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                                            <input disabled="disabled" readonly  type="text" placeholder="Mobile" id="mobile" class='form-control' name="mobile" value="<?=$order_details['order']['customer_mobile']?>" disabled >

                                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                            <input disabled="disabled" readonly  type="text" placeholder="Phone" id="phone" class='form-control' name="phone"  value="<?=$order_details['order']['customer_phone']?>" disabled >
                                                        </div>
                                                    </div>


                                                    <label for="textfield" class="control-label col-sm-2">Building/Estate</label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <input disabled="disabled" readonly  type="text" id="buildingTextBox" placeholder="Building/Estate" class='form-control' name="building" value="<?=$order_details['order']['customer_building']?>" disabled >
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                </div>
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


                                                <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Email</label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">@</span>
                                                            <input disabled="disabled" readonly  type="text" placeholder="Email" id="email" class='form-control' name="email" value="<?=$order_details['order']['customer_email']?>" disabled >
                                                        </div>
                                                    </div>



                                                    <label for="textfield" class="control-label col-sm-2">
                                                        Street
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <input disabled="disabled" readonly  type="text" placeholder="Street" class='form-control' id="streetTextBox" name="street" value="<?=$order_details['order']['customer_street']?>" disabled >
                                                            <span id="pinCodeLocation" class="input-group-addon">
                                                                <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">

                                                    <label for="textfield" class="control-label col-sm-2">&nbsp;</label>
                                                    <div class="col-sm-4">&nbsp;
                                                    <?php 
                                                    $userId = $this->session->userdata['id'];
                                                    $canEditAccess = canPerformAction('passport_img_update', $userId);
                                                    if($order_details['order']['passport_img'] || $order_details['order']['passport_id_number'])
                                                    { ?>
                                                        <span class="passport_phont_link">
                                                            <label for="textfield" class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <a href="#" path="<?= $order_details['order']['passport_img']?>" class="passport_img_show_model_link"><?= $order_details['order']['passport_id_number']?></a>
                                                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </span>
                                                        <?php } ?>
                                                        <?php if($canEditAccess === TRUE)
                                                        { ?>
                                                            <a href='#' customer_id='<?= $order_details['order']['customer_id']?>' customer_name='<?= $order_details['order']['customer_name']?>' id_number='<?= $order_details['order']['passport_id_number']?>' class='passport_img_update_model'>
                                                                <i class='fa fa-refresh'></i>
                                                            </a>
                                                        <?php } ?>
                                                    </div>

                                                    <label for="textfield" class="control-label col-sm-2">Block : Unit </label>
                                                    <div class="col-sm-4">

                                                        <div class="input-group">

                                                            <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                        <input disabled="disabled" readonly type="text" id="blockTextBox" placeholder="Block" class='form-control' name="block" value="<?=$order_details['order']['customer_block']?>" disabled >

                                                            <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                        <input disabled="disabled" readonly  type="text" id="unitTextBox" placeholder="Unit" class='form-control' name="unit" value="<?=$order_details['order']['customer_unit']?>" disabled >

                                                        </div>
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
                        </div>
                        <div class="step" id="secondStep">
                            <ul class="wizard-steps steps-3">
                                <li>
                                    <div class="single-step">
                                        <span class="title">
                                            1</span>
                                        <span class="circle">
                                            <span class="active"></span>
                                        </span>
                                        <span class="description">
                                            Customer Information
                                        </span>
                                    </div>
                                </li>
                                <li  class='active'>
                                    <div class="single-step" style="border:1px solid  #e63a3a">
                                        <span class="title">
                                            2</span>
                                        <span class="circle">
                                        </span>
                                        <span class="description">
                                            Order Information
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="single-step">
                                        <span class="title">
                                            3</span>
                                        <span class="circle">
                                        </span>
                                        <span class="description">
                                            Delivery Information
                                        </span>
                                    </div>
                                </li>
                            </ul>







                            <div class="row">
<?php
                    if(!empty($Disable_Order)){
    ?>
                                    <div class="alert alert-warning" role="alert" style="text-align: center;">
                Order has attained <?= $Current_Status ?> and box related information can not be updated when case has attained <?= $Pramissable_Status?> status and beyond.
                                    </div>
                                <?php } ?>
                                <div class="col-sm-12">
                                    <div class="box box-bordered" style="border-top:1px solid #e7e7e7"  id="orderDataContainer">

                                        <div class="modal fade" id="promotionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4 class="modal-title pull-left" id="myModalLabel">Prom Code List</h4>

                                                        <div class="clear">&nbsp;</div>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row" id="promotionContainer">
                                                        </div>    

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary" id="selectPromotion">Select</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        
                                            <!--Modal for display when promo code is expired Start-->                                        
                                        <div class="modal fade" id="promoExpiryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                                            <div class="modal-dialog">
                                                <div class="modal-content" >
                                                    <div class="modal-header box-title" style='background-color:#e63a3a'>
                                                        <h4 class="modal-title pull-left" style='color:white' id="myModalLabel">Alert</h4>

                                                        <div class="clear">&nbsp;</div>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row" id="promotionExpiryTextContainer">
                                                        </div>    

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="promoExpiryOkBtn">OK</button>
                                                        <button type="button" class="btn btn-default" id="promoExpiryCancelBtn">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--Modal for display when promo code is expired End-->
                                        
                                        
                                        
                                        
                                        <input type="hidden" id='promoDetails'>        
                                        <input type='hidden' id='selectedPromoCodeDetails'>
                                        <input type='hidden' id='promoCodeDiscount'>
                                        <input type='hidden' id='discount'>



                                        <div class="box-content">
                                            <div class="form-group">
                                                <label for="order_date" class="control-label col-sm-2">Order Date</label>
                                                <div class="col-sm-4">
                                                    <div class='input-group date' id='datetimepicker1'>
                                        <?php
                                        $order_date = explode(' ', $order_details['order']['order_date']);
                                        $order_date = $order_date[0];
                                        list($year, $month, $day) = explode('-', $order_date);
                                        $order_date = "$day/$month/$year";
                                        ?>
                                                        <input readonly type="text" name="order_date" id="order_date" class="form-control datepick1" value="<?=$order_date?>">
                                                    </div>

                                                </div>



                                                <label for="textfield" class="control-label col-sm-2">Customer Name</label>
                                                <div class="col-sm-1">
                                                    <input type="hidden" id="orderCustomerDetails_RepeatedCustomer" name="repeated_customer">

                                                    <div class="input-group" id="orderCustomerDetails_CustomerName">
                                                    </div>
                                                </div>
                                                <label for="textfield" class="control-label col-sm-1">Agent</label>
                                                <div class="col-sm-1">
                                                    <div class="input-group">
<?php
                                                            if (!empty($agents))
                                                            {
    ?>
                                                            <Select  style="margin-bottom:2px;width:200px;" name="agent_id" id="agentIdSelect"  class="form-control">
                                                                <option value="">--Select--</option>
    <?php
                                                                    foreach ($agents as $index => $row)
                                                                    {
        $selected = $order_details['order']['agent_id'] == $row['id'] ? "selected='selected'" : '';
        ?>
                                                                        <option  <?=$selected?> type="<?=$row['can_update_total']?>" href="<?=$row['order_no_type']?>" rel="<?=$row['commission']?>" value="<?=$row['id']?>"><?=$row['name']?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                            </Select>
                                                            <span class="label label-warning" id="manualOrderSpanInfo">Manual Order No Entry Is Required</span>

                                                                <?php
                                                            }
                                                            ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="codeDropDownDivContainer" style="<?= $display_none ?>">
                                                <label for="textfield" class="control-label col-sm-2">Code</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group pull-left">
                                                        <?php
                                                        if (!empty($locations))
                                                        {

                                                            ?>
                                                            <select  id="locationCodeSelect"  class="pull-left form-control" style="width:100px">
                                                                <option value="">--Locations--</option>
    <?php
    $str = '';


                                                            foreach ($locations as $index => $row)
                                                            {
        $selected = '';
        $str .= "<option $selected rel='{$row['capture_weight']}' value='{$row['id']}'>{$row['name']}</option>";
    }
    echo $str;
    ?>
                                                            </select>
                                                                <?php
                                                        }
                                                        else
                                                        {
                                                                echo "No locations found.";
                                                            }
                                                            ?>
                                                    </div>


                                                    <div class="input-group pull-left">
                                                            <?php
                                                        if (!empty($boxes))
                                                        {

                                                                ?>
                                                            <select  id="codeBoxSelect"  class="pull-left form-control" style="width:100px">
                                                                <option value="">--Box--</option>
                                                            <?php
                                                            $str = '';


                                                            foreach ($boxes as $index => $row)
                                                            {
                                                                $selected = '';
                                                                $str .= "<option $selected value='{$row['id']}'>{$row['name']}</option>";
                                                            }
                                                            echo $str;
                                                            ?>
                                                            </select>
                                                            <?php
                                                        }
                                                        else
                                                        {
                                                            echo "No boxes found.";
                                                        }
                                                        ?>
                                                    </div>

                                                    <label style="padding:4px" class="pull-left" >Select</label>
                                                    <select  id="codeIdSelectDropDown"  class="pull-left form-control" style="width:100px" name="code_id_select">
                                                        <option>--Code--</option>
                                                    </select>
                                                    <label style="padding:4px" class="pull-left" >Or enter</label>
                                                    <div class="input-group pull-left">
                                                        <input name="code_id" id="codeIdSelect"  class="codeTextBoxClass pull-left form-control" style="width:171px;margin-left:10px">
                                                        <input name="code_id_hidden" id="codeIdHidden" type="hidden">
                                                    </div>
                                                </div>

                                                <div class="col-sm-2 text_height" id="codeDescriptionText">&nbsp;</div>

                                                <div  style="padding-left:3px"  class="col-sm-1 text_height">
                                                    <button class="btn-primary btn hide" id="codeSelectButton" type="button">Select Code</button>
                                                </div>

                                            </div>


                                        <?=$code_box_html?>

                                            <div class="form-group">
                                                <label for="delivery_date" class="control-label col-sm-2">
                                                    Delivery Date
                                                </label>
                                                <div class="col-sm-4">
<?php
$delivery_date = explode(' ', $order_details['order']['delivery_date']);
$delivery_date = $delivery_date[0];
list($year, $month, $day) = explode('-', $delivery_date);
$delivery_date = "$day/$month/$year";
?>
                                                    <div class='input-group date'>
                                                        <input readonly type="text" name="delivery_date" id="delivery_date" class="form-control datepick2" value='<?=$delivery_date?>'>
                                                    </div>

                                                </div>


                                                <label for="textfield" class="control-label col-sm-2">Collection Date</label>
                                                <div class="col-sm-4">
<?php
                                                    if (empty($order_details['order']['collection_date']) || $order_details['order']['collection_date'] == '0000-00-00 00:00:00')
                                                    {
    $collection_date = '';
                                                    }
                                                    else
                                                    {
    $collection_date = explode(' ', $order_details['order']['collection_date']);
    $collection_date = $collection_date[0];
    list($year, $month, $day) = explode('-', $collection_date);
    $collection_date = "$day/$month/$year";

}
                                                    
?>
                                                    <div class='input-group date pull-left' id='datetimepicker3'>
                                                        <input readonly type="text" name="collection_date" id="datepick3" class="form-control datepick3" value='<?=$collection_date?>'>
                                                    </div>                                                  
                                                    <a style="padding-left:20px" onclick="$('#datepick3').val('')" class="pull-left" href="#"><i class="glyphicon-delete"></i></a>

                                                    <span style="color:green" class="pull-right"><b>Deposit : <i>$ <?=empty($order_details['order']['deposit_collected']) ? 0.0 : number_format($order_details['order']['deposit_collected'], 2)?></i></b></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="picture_receive_date" class="control-label col-sm-2">
                                                    Picture Receive Date
                                                </label>
                                                <div class="col-sm-10">
                                                    <?php
                                                    $picture_receive_date = '';

                                                    if (!empty($order_details['order']['picture_receive_date']))
                                                    {
                                                        $picture_receive_date = $order_details['order']['picture_receive_date'];
                                                        list($year, $month, $day) = explode('-', $picture_receive_date);
                                                        $picture_receive_date = "$day/$month/$year";
                                                    }
                                                    ?>
                                                    <div class='input-group date'>
                                                        <input readonly type="text" name="picture_receive_date" id="picture_receive_date" class="form-control datepick4" value='<?=$picture_receive_date?>'>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">
                                                    Postal Code
                                                    <a href="#" class="pull-right"  id="pinLookUpLink_Customer"><i class="glyphicon-search"></i></a>
                                                    <a href="#" title="Clear Fields" style="padding-right:4px" class="pull-right" id="clearAddressFields"><i class="glyphicon-minus-sign"></i></a>
                                                </label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                                <input type="text" placeholder="Postal Code" class='form-control' id="orderCustomerDetails_Pin" name="pin_order" value="<?=$order_details['order']['pin']?>">
                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                            <i class="fa fa-location-arrow"></i>
                                                        </span>
                                                    </div>
                                                </div>


                                                <label for="textfield" class="control-label col-sm-2">Building/Estate</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                                <input type="text" id="orderCustomerDetails_Building" placeholder="Building/Estate" class='form-control' name="building_order" value="<?=$order_details['order']['building']?>">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-location-arrow"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">
                                                    Street
                                                </label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                                <input type="text" placeholder="Street" class='form-control' id="orderCustomerDetails_Street" name="street_order" value="<?=$order_details['order']['street']?>">
                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                            <i class="fa fa-location-arrow"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <label for="textfield" class="control-label col-sm-2">Unit : Block</label>
                                                <div class="col-sm-4">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                            <input type="text" id="orderCustomerDetails_Unit" placeholder="Unit" class='form-control' name="unit_order" value="<?=$order_details['order']['unit']?>">

                                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                            <input type="text" id="orderCustomerDetails_Block" placeholder="Block" class='form-control' name="block_order" value="<?=$order_details['order']['block']?>">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Delivery Notes</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <textarea name="comments" style="width:403px;height:83px"  class='form-control' placeholder="Delivery Notes"><?=$order_details['order']['comments']?></textarea>

                                                    </div>
                                                </div>

                                                <label for="collectionNotesClass" class="collectionNotesClass control-label col-sm-2">Collection Notes</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                            <textarea name="collection_notes" style="width:418px;height:83px" id="collectionNotesClass"  class='collectionNotesClass form-control' placeholder="Collection Notes"><?=$order_details['order']['collection_notes']?></textarea>

                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Memo</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                    <textarea name="memo" style="width:403px;height:83px"  class='form-control' placeholder="Memo"><?=$order_details['order']['memo']?></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group OrderButton">
                                                <label for="textfield" class="control-label col-sm-2">&nbsp;</label>

                                                <p style="padding-left:10px">
<?php
                                                if ($can_cancel_order == true)
                                                {
    ?>

                                                    <button type="button" class="btn btn-primary btn-inverse" id="cancelOrderButton">
                                                            <i class="fa glyphicon-ban"></i>&nbsp;&nbsp;Cancel Order</button>

    <?php
}
                                                if($order_details['order']['kiv_status'] == 'yes')
                                                {
    ?>
                                                        <button type="button" class="btn btn-primary btn-inverse" rel="no" style="background-color:#339933" id="KIVOrderButton">
                                                            <i class="fa glyphicon-circle_plus"></i>&nbsp;&nbsp;Rollback KIV</button>
    <?php
                                                }
                                                else
                                                {
    ?>
                                                        <button type="button" class="btn btn-primary btn-inverse" rel="yes" id="KIVOrderButton">
                                                            <i class="fa glyphicon-circle_minus"></i>&nbsp;&nbsp;Enforce KIV</button>
    <?php
}
?>
                                                </p>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>                  









                        </div>



















                        <div class="step" id="thirdStep">
                            <ul class="wizard-steps steps-3">
                                <li>
                                    <div class="single-step">
                                        <span class="title">
                                            1</span>
                                        <span class="circle">
                                            <span class="active"></span>
                                        </span>
                                        <span class="description">
                                            Customer Information
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="single-step">
                                        <span class="title">
                                            2</span>
                                        <span class="circle">
                                        </span>
                                        <span class="description">
                                            Order Information
                                        </span>
                                    </div>
                                </li>
                                <li  class='active'>
                                    <div class="single-step" style="border:1px solid  #e63a3a">
                                        <span class="title">
                                            3</span>
                                        <span class="circle">
                                        </span>
                                        <span class="description">
                                            Delivery Information
                                        </span>
                                    </div>
                                </li>

                            </ul>







                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box box-bordered" style="border-top:1px solid #e7e7e7"  id="orderDataContainer">

                                        <div class="box-content">


                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Recipient Name</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                    <input type="text" placeholder="Recipient Name" class='form-control' name="recipient_name" value="<?=$order_details['order']['recipient_name']?>">
                                                    </div>
                                                </div>
                                                <label for="textfield" class="control-label col-sm-2">Contact Number</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                                    <input type="text" placeholder="Mobile" class='form-control' name="recipient_mobile" value="<?=$order_details['order']['recipient_mobile']?>">
                                                        <!--
                                                                                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                                                            <input type="text" placeholder="Phone" id="phone" class='form-control' name="delivery_recipient_phone">
                                                        -->
                                                    </div>
                                                </div>
                                            </div>

                                            <!--
                                                <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">
                                                            Postal Code
                                                            <a href="#" class="pull-right"  id="pinLookUpLink_Customer"><i class="glyphicon-search"></i></a>
                                                            <a href="#" title="Clear Fields" style="padding-right:4px" class="pull-right" id="clearAddressFields"><i class="glyphicon-minus-sign"></i></a>
                                                        </label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <input type="text" placeholder="Postal Code" class='form-control' id="DeliverDetails_Pin" name="delivery_pin">
                                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                                                <i class="fa fa-location-arrow"></i>
                                                                        </span>
                                                                </div>
                                                        </div>
                
                
                                                        <label for="textfield" class="control-label col-sm-2">Building/Estate</label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <input type="text" id="DeliverDetails_Building" placeholder="Building/Estate" class='form-control' name="delivery_building">
                                                                        <span class="input-group-addon">
                                                                                <i class="fa fa-location-arrow"></i>
                                                                        </span>
                                                                </div>
                                                        </div>
                                                </div>
                
                                            -->
                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">
                                                    Address
                                                </label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                    <textarea name="recipient_address" style="width:418px;height:83px"  class='form-control' placeholder="Address"><?=$order_details['order']['recipient_address']?></textarea>
                                                    </div>
                                                </div>

                                                <label for="textfield" class="control-label col-sm-2">Item List</label>
                                                <div class="col-sm-4">

                                                    <div class="input-group">
                                                <textarea name="recipient_item_list" style="width:418px;height:83px"  class='form-control' placeholder="Item List"><?=$order_details['order']['recipient_item_list']?></textarea>
                                                    </div>
                                                </div>

                                            </div>




                                            <div class="form-group weightContainer">
                                                <label for="textfield" class="control-label col-sm-2">Weight</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa glyphicon-dumbbell"></i>
                                                        </span>
                                                        <input type="text" data-rule-required="true" name="weight"  value='<?=empty($order_details['order']['weight']) ? '0.00' : $order_details['order']['weight']?>' placeholder='0.00'  class='input-large form-control' style="width:90px">
                                                    </div>
                                                </div>

                                            </div>

                                            <h3>
                                                <i class="fa glyphicon-cargo"></i>&nbsp;Jakarta side attributes
                                            </h3>


                                            <div class="form-group weightContainer">
                                                <label for="textfield" class="control-label col-sm-2">Weight</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa glyphicon-dumbbell"></i>
                                                        </span>
                                            <input type="text" data-rule-required="true" name="jkt_weight"  value='<?=empty($order_details['order']['jkt_weight']) ? '0.00' : $order_details['order']['jkt_weight']?>' placeholder='0.00'  class='input-large form-control' style="width:90px">
                                                    </div>
                                                </div>
                                                <label for="textfield" class="control-label col-sm-2">Reference Number</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa glyphicon-link"></i></span>
                                                <input type="text" placeholder="Reference Number" class='form-control' name="jkt_reference_no" value="<?=$order_details['order']['jkt_reference_no']?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Received Date</label>
<?php
                                  if($current_order_status == "received_at_jakarta_warehouse")
                                  {
    ?>
                                                    <div class="col-sm-4">
    <?php
    $jkt_received_date = $order_details['order']['jkt_received_date'];
                                            if (!empty($jkt_received_date) && ($jkt_received_date !== '0000-00-00'))
                                            {
        list($year, $month, $day) = explode('-', $jkt_received_date);
        $jkt_received_date = "$day/$month/$year";
                                            }
                                            else
                                            {
        $jkt_received_date = '';
    }
    ?>
                                                        <div class='input-group date'>
                                            <input type="text" name="jkt_received_date" class="form-control datepick5" value='<?=$jkt_received_date?>'>

                                                        </div>

                                                    </div>
                                <?php  }  else {  ?>
                                                    <div class="col-sm-4">
                                                        <div class='input-group date'>
                                        <input style="width:150px;float:left;" readonly  name="jkt_received_date" class="form-control" value='<?=$jkt_received_date?>'>
                                        <label style="width:150px;float:left;"><?=strtoupper($Current_Status);?></label>
                                                        </div>
                                                    </div>
                                <?php }  ?>
                                                <label for="textfield" class="control-label col-sm-2">Penerima</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa glyphicon-user"></i></span>
                                                <input type="text" placeholder="Penerima" class='form-control' name="jkt_receiver" value="<?=$order_details['order']['jkt_receiver']?>">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Diff in Date Collected & Date Received(In Days)</label>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="textfield">
                                        <b><?= empty($dcdr) ? 0 : $dcdr;?></b>
                                                    </label>

                                                </div>
                                                <label for="textfield" class="control-label col-sm-2">Diff in Date Shipped & Date Received(In Days)</label>
                                                <div class="col-sm-4">
                                                    <label class="control-label" for="textfield">
                                        <b><?= empty($dsdr) ? 0 : $dsdr;?></b>
                                                    </label>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>                                             






                        </div>


















                        <div class="form-actions">
                            <input type="reset" class="btn" value="Back" id="back">
                            <input type="submit" class="btn btn-primary" value="Submit" id="next">
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

<!-- Modal -->
<div id="passport_img_show_model" class="modal fade" role="dialog">
  <div class="modal-dialog">
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
  <div class="modal-dialog" style="height: 800px;">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Passport</h4>
      </div>
        <div class="modal-body">            
            <form id="form_horizontal" role="form" action="#" enctype="multipart/form-data">
                <div class="form-group customers" >
                    <input type="hidden" id="customer_id" name="customer_id[]" value="<?= $order_details['order']['customer_id']?>" ><?= $order_details['order']['customer_name']?>
                </div>
                <!-- Text input-->
                <div class="form-group">
                    <input id="id_number" name="id_number[]" placeholder="ID Number" value="<?= $order_details['order']['passport_id_number']?>" class="form-control" required="" type="text">
                </div>
                <!-- file input-->
                <div class="form-group">
                    <input id="passport" name="passport[]" placeholder="Passport" style="border: 1px solid lightgrey; padding: 3px" class="col-md-<?= ($order_details['order']['passport_img']) ? "11" : "12";?>" type="file">
                    <?php if ($order_details['order']['passport_img'])
                    { ?>
                         <button type="button" class="btn col-md-1 remove" passport_img="<?= $order_details['order']['passport_img']?>" customer_id="<?= $order_details['order']['customer_id']?>"  style="background-color: #e63a3a; color: white">Delete</button>
                     <?php } ?>
                    </div>                
                <div class="modal-footer">
                   <button id="submit_button" style="margin-top:10px" name="submit_button" class="btn btn-primary" data-toggle="modal" href="result.php">upload</button>

                  <button type="submit" class="btn btn-default" style="margin-top:10px"  data-dismiss="modal">Close</button>
                  </div>
                </div>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">

    var location_id = box_id = 0;
    var code_auto_suggest_box_id = orderid = 0

    $(document).ready(function (){

    $("#customer_type").multiselect({
        buttonWidth: '100%'
    });
    $("#media_type").multiselect({
        buttonWidth: '400px'
    });
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
    
    promoCodeButton();
    getPromocodeById();

    $('#locationCodeSelect').change(function (){
    if ($(this).val() > 0)
    {
    fetchCodeByLocationBox();
    }
    })
            $('#codeBoxSelect').change(function (){
    if ($(this).val() > 0)
    {
    fetchCodeByLocationBox();
    }
    })
    
    $('.passport_img_show_model_link').click(function (){

        var path = $(".passport_img_show_model_link").attr("path");
        var folder_path = "<?= base_url()?>./assets/img/customer_passport/"+path;
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

        var customer_id = $(".passport_img_update_model").attr("customer_id");
        var customer_name = $(".passport_img_update_model").attr("customer_name");           
        var id_number = $(".passport_img_update_model").attr("id_number");          
        $(".customers").html('<input type="hidden" id="customer_id" name="customer_id[]" value="'+customer_id+'" >'+customer_name);
        $("#id_number").val(id_number);
        $("#passport_img_update_model").modal("show");
    })

    function fetchCodeByLocationBox()
    {
        locationCodeSelect = $('#locationCodeSelect').val();
        codeBoxSelect = $('#codeBoxSelect').val();
        if (locationCodeSelect > 0 && codeBoxSelect > 0)
        {
            $('#loadingDiv_bakgrnd').show();

            $.ajax({
                data:{location_id:locationCodeSelect, box_id:codeBoxSelect},
            url: "<?=base_url()?>admin/order/fetchCodeByLocationBox",
                        cache: false,
                        dataType : 'json',
                        type : 'get',
                })
            .done(function( response ) {

                $("#codeIdSelectDropDown").html("<option value=''>--Code--</option>");
                        $(response).each(function (index, row){
                 $("#codeIdSelectDropDown").append("<option rel='"+row.description+"' value='"+row.id+"'>"+row.code+"</option>");
                })
                        //$('#codeIdSelectDropDown').
                        $('#loadingDiv_bakgrnd').hide();
                });
        }
    };
    
    $("#codeIdSelectDropDown").change(function (){
        if ($(this).val() > 0)
        {
                $(".codeTextBoxClass" ).val('');

                $("#codeIdHidden" ).val($(this).val());
                $('#codeDescriptionText').html(  $(this).find('option:selected').attr('rel')  );
                $('#codeSelectButton').removeClass('hide');
        }
        else
        {
                $("#codeIdHidden" ).val(0);
                $('#codeDescriptionText').html(  ''  );
                $('#codeSelectButton').addClass('hide');
        }
    })


    $('#datepick3').change(function (){
        if ($(this).val() != '')
        {
            $('.collectionNotesClass').show();
        }
        else
        {
            $('.collectionNotesClass').hide();
        }
    })

    $('#pinLookUpLink, #pinLookUpLink_Customer').click(function(){
        if($(this).attr('id') == 'pinLookUpLink')
        {
            obj = 'pinCodeText';
        }
        else
        {
            obj = 'orderCustomerDetails_Pin';
        }

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
                        $('#lattitude').val(response.lattitude);
                        $('#longitude').val(response.longitude);
                }

                $('#loadingDiv_bakgrnd').hide();
                });
        }
    })

    $('#next').click(function (event){
    event.preventDefault();
            if ($(this).val() == 'Submit')
    {
            if($.trim($('#mobile').val()) == '' && $.trim($('#phone').val()) == '')
    {
    alert('Please enter Phone/Mobile to proceed');
            $('#mobile').focus();
            return false;
    }

            if($('#delivery_date').val() == '')
    {
    alert('Please enter delivery date to proceed');
            return false;
    }

    grandTotalObj = $('#grandTotalRow');
            if(grandTotalObj.length == 0)
    {
    alert('Please select any code to proceed');
            return false;
    }

            if($('#lattitude').val() == '' || $('#longitude').val() == '')
    {
    alert('Seems like there was some error while saving pin code. Kindly start the process again.');
            return false;
    }

    fromDate = $('#order_date').val().split('/');
            EndDate = $('#delivery_date').val().split('/');
            
            fromDate = new Date(fromDate.pop(), fromDate.pop() - 1, fromDate.pop());
            EndDate = new Date(EndDate.pop(), EndDate.pop() - 1, EndDate.pop());
            
            if (Date.parse(fromDate) > Date.parse(EndDate))
    {
    alert("Delivery date should be later than Order Date.")
            $('#delivery_date').focus()
            return false;
    }

            if($('input[name="kabupatens_name_selected[]"]').length > 0)
    {
    returnVal = true;
            $('input[name="kabupatens_name_selected[]"]').each(function(index, obj){
    if ($.trim($(obj).val()) == '')
    {
    alert('Please enter Kabupaten.');
            $(obj).focus();
            returnVal = false;

            return false;
    }
    })

            if (returnVal == false)
    {
    return false;
    }
    }


        if($('#agentIdSelect option:selected').attr('href') == 'manual' && $('#manual_order_number').val() == '')
    {
    manualOrderNo = prompt("Please enter order number");
            $('#manual_order_number').val("<?=$order_details['order']['order_number']?>")
    }

    $('#next').addClass('disabled');
        
            $('#loadingDiv_bakgrnd').show();
            
            
            if ($('#monitor_redelivery_case').val() == 1)
    {
    isQtySame = isQuantitySame();

            if (isQtySame == false)
    {
    saveOrig = confirm("System has detected quantity change in box(es). Do you want to save original quantity of boxes treating this case as redelivery edit?");
            if (saveOrig == true)
    {
    $('#save_redelivery_data').val(1);
    }
    }
    }

    data = $('#ss').serializeObject();
            
            $.ajax({
            data:data,
                    url: "<?=base_url()?>admin/order/saveFullOrder",
                    type:'POST',
                    dataType : 'JSON'
            })
            .done(function( response ) {
            orderid = response.order_id;
                    $('#loadingDiv_bakgrnd').hide();

// Commenting this upon Imran's request
//                url = "<?=base_url()?>admin/order/printNow/"+orderid;
//                window.open(url, '_blank');

                window.location.href='<?=base_url()?>admin/order/orderBookingForm?haveSideBar=0'
            });

            return false;
    }
    })

            $('.box_checboxes, .location_checboxes').click(function(){
    var className = $(this).hasClass('location_checboxes') ? 'location' : 'box';
            var classNameChkBox = $(this).hasClass('location_checboxes') ? 'location_checboxes' : 'box_checboxes';
        
        $('.'+classNameChkBox).find('input').removeAttr('checked');
        
            var radioBtn = $(this).children('input');
            radioBtn.attr('checked', 'checked')

            if (className == 'location')
    {
    location_id = radioBtn.val();
    }
    else
    {
    box_id = radioBtn.val();
    }

    if (box_id > 0 && location_id > 0)
    {
    $.ajax({
    data:{box_id:box_id, location_id:location_id},
            url: "getPriceByBoxLocation.php"
    })
            .done(function( response ) {
                $( "#price" ).val( response);
            });
    }
    })

            $('#codeBoxSelect').change(function(){
    code_auto_suggest_box_id = $('#codeBoxSelect').val();
            code_ids = '';
        
        if($('input[name="codes[]"]').length > 0)
    {
    code_ids = '';
            code_id_arr = new Array();
            $('input[name="codes[]"]').each(function(index, obj){
    code_id_arr.push($(obj).val());
    })
//            console.log(code_id_arr)
            code_ids = code_id_arr.join(',')
    }

    initializeAutoComplete(code_ids)
    })

            function initializeAutoComplete(code_ids) {
            if (code_ids == 'undefined')
                    code_ids = '';
        
        $( ".codeTextBoxClass" ).autocomplete({
            source: "<?=base_url()?>admin/order/fetchCodeByLocationBox?code_ids="+code_ids+"&box_id=" + code_auto_suggest_box_id ,
                    minLength: 1,
            open : function( event, ui ) {
                    },
            search  : function(){$('#loadingDiv_bakgrnd').show();},
            open    : function(){$('#loadingDiv_bakgrnd').hide();},
            response    : function(){$('#loadingDiv_bakgrnd').hide();},
          select: function( event, ui ) {
            $(".codeTextBoxClass" ).val(ui.item.code)
            $("#codeIdHidden" ).val(ui.item.id);
                            $('#codeDescriptionText').html(ui.item.description);
                            $('#codeSelectButton').removeClass('hide');
                            $('#codeIdSelectDropDown').val('');
            
                            return false;
                    }
        }).autocomplete("instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<a>"+ item.code + " </a>" )
            .appendTo( ul );
            };
            }


    function initializeKabupatenAutoComplete(locationId, kabupatenTextObj, kabupatenIdObj) {
        $( '#'+kabupatenTextObj ).autocomplete({
            source: "<?=base_url()?>admin/order/fetchKabupatenAutoSuggestion/" + locationId,
            minLength: 3,
            open : function( event, ui ) {
            },
            search  : function(){$('#loadingDiv_bakgrnd').show();},
            open    : function(){$('#loadingDiv_bakgrnd').hide();},
            response    : function(){$('#loadingDiv_bakgrnd').hide();},
          select: function( event, ui ) {
            $("#"+kabupatenIdObj ).val(ui.item.id)
            $("#"+kabupatenTextObj ).val(ui.item.name)

                    return false;
            }
        }).autocomplete("instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<a>"+ item.name + " </a>" )
            .appendTo( ul );
    };
    }

    $('body').on('change', '.quantityTextBoxClass', function (){
    quantity = $(this).val();
        
            parentObj = $(this).parents('.form-group');
            price = $(parentObj).find('.priceHiddenClass').val();
        
            $(parentObj).find('.individualPriceFakeClass').html(quantity * price);
        
            updateGrandTotal();
            if($('#promoIdHidden').val() == "" || $('#promoIdHidden').val() == null)
            {
                updateDiscount();
            }
            else
            {
                updatePromoDiscount();
            }
            updateNettTotal();
    })

            $('body').on('change', '.boxLocationFakeClass', function (){
    /*
     if($(this).attr('name') == 'locations_selected[]')
     {
     type = 'location';
     location_obj = $(this);
     box_obj = $(this).closest('.fakeCodeDetailsClass').find('select[name="boxes[]"]');
     
     }
     else
     {
     type = 'box';
     box_obj = $(this);
     location_obj = $(this).closest('.fakeCodeDetailsClass').find('select[name="locations_selected[]"]');
     }
     
     price_obj = $(this).closest('.fakeCodeDetailsClass').find('.priceTextBoxClass');
     individual_price_obj = $(this).closest('.fakeCodeDetailsClass').find('.individualPriceFakeClass');
     quantity_obj = $(this).closest('.fakeCodeDetailsClass').find('.quantityTextBoxClass');
     
     location_info  = $(location_obj).val().split('_#_')
     box_info  = $(box_obj).val().split('_#_')
     console.log($(location_obj).val() , $(box_obj).val())
     
     if(location_info[0] > 0 && box_info[0] > 0)
     {
     $('#loadingDiv_bakgrnd').show();
     
     $.ajax({
     data:{location_id:location_info[0], box_id:box_info[0]},
                url: "<?=base_url()?>admin/order/fetchPriceByLocationBox",
     type : 'POST',
     dataType : 'json'
     })
     .done(function( response ) {
     //                /if (response.price != 'undefined')
     {
     $(price_obj).val(response.price);
     
     tmp = parseInt($(quantity_obj).val(), 10)
     
     $(individual_price_obj).val(tmp * response.price);
     }
     });
     }
     */
    $('.priceTextBoxClass').trigger('change');
    })

            $('body').on('keyup', '.priceHiddenClass', function (){
    price = parseInt($(this).val(), 10);
            parentObj = $(this).parents('.form-group');
            val = price * parseInt($(parentObj).find('.quantityTextBoxClass').val(), 10);
            if (val >= 0)
            $(parentObj).find('.priceTextBoxClass').val(val);
            else
            $(parentObj).find('.priceTextBoxClass').val(0);
        
            $('.priceTextBoxClass').trigger('change');
    })

//    $('#mytext').change(function (event){
//        console.log('hi')
//    })
            $('#searchForm').submit(function (event){
    event.preventDefault();
            $('#loadingDiv_bakgrnd').show();
            data = $('#searchForm').serializeObject();
            $.ajax({
            data:data,
            url: "<?=base_url()?>admin/order/searchUser",
                    type:'POST'
            })
        .done(function( response ) {
            $('#searchResultContainer').html(response)
                    $('#loadingDiv_bakgrnd').hide();
            });
    })

            function isQuantitySame()
            {
            returnVal = true;
        
        redel_objs =  $('input[name^="redel_orig_box_qty"]');
        boxes_objs =  $('input[name="boxes[]"]');
        
        quantities_objs =  $('*[name="quantity[]"]');
        
                    if (redel_objs.length != boxes_objs.length)
            {
            returnVal = false;
            }

            if (boxes_objs.length > 0)
            {
            $(boxes_objs).each(function (index, val){
            tmp_box_id = $(this).attr('data-box-id');
                
                tmp_redel_box_obj = $('input[data-redelivery-box-id='+tmp_box_id+']');
                
                if(tmp_redel_box_obj.length == 0)
            {
            returnVal = false;
            }
            else
            {
            orig_redel_quantity = $(tmp_redel_box_obj).attr('data-quantity');
                    redel_quantity = $(quantities_objs[index]).val();
                    if (redel_quantity != orig_redel_quantity)
            {
            returnVal = false;
            }
            }
            })
            }

            return returnVal;
            }

    $('#ss').submit(function (event){
    event.preventDefault();
        
            data = $('#ss').serializeObject();
       
            $('#loadingDiv_bakgrnd').show();

            $.ajax({
            data:data,
                url: "<?=base_url()?>admin/order/saveFullOrder",
                    type:'POST',
                    dataType : 'JSON'
            })
        .done(function( response ) {
            });

            return false;
        
            raw_order_number = $('#raw_order_number').val();
            //order_number = $();
    })

            function fetchCustomerHistoryData(customerId)
            {
            $('#loadingDiv_bakgrnd').show();
                    $.ajax({
            url: "<?=base_url()?>admin/order/showCustomerOrderHistory/" + customerId,
                            dataType : 'html'
                    })
        .done(function( response ) {
                    $('#customerOrderHistoryContainer').html(response);
                            $('#loadingDiv_bakgrnd').hide();
                    });
            }

    $('#selectCustomerButton').click(function (event){
    $('#loadingDiv_bakgrnd').show();

            var customerId = $('#customerIdHidden').val()

            var emailValue = $('#email_' + customerId).val();
            var nameValue = $('#name_' + customerId).html();
            var mobileValue = $('#mobile_' + customerId).html();
            var residencePhoneValue = $('#residence_phone_' + customerId).html();
        
            var pinValue = $('#pin_' + customerId).html();
        
            var unitValue = $('#unit_' + customerId).html();
            var buildingValue = $('#building_' + customerId).html();
            var blockValue = $('#block_' + customerId).html();
            var streetValue = $('#street_' + customerId).html();
            var repeatedCustomerValue = $('#repeated_customer_' + customerId).val();
            var customer_type_idValue = $('#customer_type_id_' + customerId).val().split("@#@#");
            var media_type_idValue = $('#media_type_id_' + customerId).val().split("@#@#");
        
            var lattitudeValue = $('#lattitude_' + customerId).val();
            var longitudeValue = $('#longitude_' + customerId).val();
        
            $('#email').val(emailValue);
            $('#customer').val(nameValue);
            $('#mobile').val(mobileValue);
            $('#phone').val(residencePhoneValue);
        
            $('#pinCodeText').val(pinValue);
            $('#blockTextBox').val(blockValue);
            $('#streetTextBox').val(streetValue);
            $('#buildingTextBox').val(buildingValue);
            $('#unitTextBox').val(unitValue);
            $('#customer_id').val(customerId);
        
            $('#lattitude').val(lattitudeValue);
            $('#longitude').val(longitudeValue);
        
        
            $('#repeated_customer').val(repeatedCustomerValue);
            
             for(var i = 0; i < customer_type_idValue.length; i++)
             {
                 $("#customer_type").find("option[value="+customer_type_idValue[i]+"]").prop("selected", true);
             }
             $("#customer_type").multiselect("refresh");
            
             for(var i = 0; i < media_type_idValue.length; i++)
             {
                 $("#media_type").find("option[value="+media_type_idValue[i]+"]").prop("selected", true);
             }
             $("#media_type").multiselect("refresh");
        
            $('#customerHistory').attr('href', $('#customerHistory').attr('href') + customerId);
            $('#customerHistoryName').html(nameValue);
        
            $('#customerHistory').removeClass('hide');
        
            $('#saveCustomerOnlyButton').addClass('disabled');
        
            updateGrandTotal();
            updateDiscount();
            updateNettTotal();
        
            fetchCustomerHistoryData(customerId)

            $('#myModal').modal('toggle');
    })

            $('#clearFields').click(function (event){
    $('#email').val('');
            $('#customer').val('');
            $('#mobile').val('');
        
            $('#phone').val('');
        
            $('#pinCodeText').val('');
            $('#blockTextBox').val('');
            $('#streetTextBox').val('');
            $('#buildingTextBox').val('');
            $('#unitTextBox').val('');
            $('#customer_id').val(0);
            $('#repeated_customer').val('');
        
            $('#customerHistory').addClass('hide');
            $('#saveCustomerOnlyButton').removeClass('disabled');
            $('#customer_type').multiselect("uncheckAll");
            $('#media_type').multiselect("uncheckAll");
        
            updateGrandTotal();
            updateDiscount();
            updateNettTotal();
    })

            $('#clearAddressFields').click(function (event){
    $('#buildingTextBox').val('');
            $('#streetTextBox').val('');
            $('#pinCodeText').focus();
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
            $('#customer_id').val(response.customer_id);
                    $('#saveCustomerOnlyButton').addClass('disabled');
                    $('#saveCustomerOnlyButton').html('Saved');
            }
            else
            {
            alert(response.message);
            }
            $('#loadingDiv_bakgrnd').hide();
            });
    })

//    $('#selectCustomerButton').on('click', 'input', function (event){
//        var customerId = $('input[name=selectCustomerRadio]:checked', '#searchForm').val()
//        console.log(customerId)
//    })

            $('.selectCustomerRadio').on('click', 'input', function (event){
    $('#searchedCustomerId').val($(this).val())
    })

            $('input[name=selectCustomerRadio]').click(function (event){
    $('#searchedCustomerId').val($(this).val())
    })

            $('.selectCustomerRadio').click(function (event){
    $('#searchedCustomerId').val($(this).val())
    })

            /*
             $('body').on('click', '.addRowClass', function (){
             //codeRowClass
             html = '<div class="form-group codeRowClass">\n\
             <label for="textfield" class="control-label col-sm-2">Code</label>\n\
             <div class="col-sm-1">\n\
             <div class="input-group">\n\
             <input  style="width:80px;height:35px" type="text" class="codeTextBoxClass input-small" name="code[]">\n\
             <input type="hidden" class="codeIdHiddenClass" name="code_id[]">\n\
             </div>\n\
             </div>\n\
             <label for="textfield" class="control-label col-sm-1">Location</label>\n\
             <div class="col-sm-1">\n\
             <div class="input-group">\n\
             <input type="text" readonly="readonly" placeholder="Location" class="locationTextBoxClass form-control" name="location[]">\n\
             <input type="hidden" name="location_id[]" class="locationHiddenClass">\n\
             </div>\n\
             </div>\n\
             <label for="textfield" class="control-label col-sm-1">Box</label>\n\
             <div class="col-sm-1">\n\
             <div class="input-group">\n\
             <input type="text" readonly="readonly" placeholder="Box" class="boxTextBoxClass form-control" name="box[]">\n\
             <input type="hidden" name="box_id[]" class="boxHiddenClass">\n\
             </div>\n\
             </div>\n\
             <label for="textfield" class="control-label col-sm-1">Quantity</label>\n\
             <div class="col-sm-1">\n\
             <div class="input-group">\n\
             <input type="text" value="1" class="quantityTextBoxClass" name="quantity" style="width:80px;height:35px">\n\
             </div>\n\
             </div>\n\
             <label for="textfield" class="control-label col-sm-1">Price</label>\n\
             <div class="col-sm-1">\n\
             <div class="input-group">\n\
             <input readonly="readonly" class="priceTextBoxClass" type="text" value="" style="width:80px;height:35px"  name="price[]">\n\
             <input class="priceHiddenClass" type="hidden" value="" name="price_per_box[]">\n\
             </div>\n\
             </div>\n\
             <label for="textfield" class="control-label col-sm-1">\n\
             <a href="#" class="pull-right addRowClass"><i class="glyphicon-plus"></i></a>\n\
             &nbsp;<a href="#" class="pull-right deleteRowClass"><i class="glyphicon-minus"></i></a>\n\
             </label>\n\
             </div>';
             $(html).insertAfter('.codeRowClass:last');
             initializeAutoComplete();
             })
             */

            $('body').on('click', '.deleteRowClass', function (){
    parentObj = $(this).parents('.form-group');
            $(parentObj).fadeOut('fast', function(){ $(parentObj).remove(); });
    })

            $('body').on('change', '.priceTextBoxClass', function (){
    totalPrice = 0;
            $('.individualPriceFakeClass').each(function (index, row){
            tmpVal = parseInt($(row).html(),10);
            totalPrice += tmpVal;
    })

        $('#totalPriceContainer').html(parseInt(totalPrice,10));
            $('.counter').counterUp({
    delay: 10,
            time: 1000
    });
    })

            $('body').on('click', '.deleteHelperButton', function (){
                deleteHelperClass = '.deleteHelperClass_' + $(this).attr('rel');
                var selected_box_id = $(deleteHelperClass).find('input[name="boxes[]"]').attr('select-box-id');
                var selected_box_quantity = $(deleteHelperClass).find('.quantityTextBoxClass').val();
                deletePromoDiscount(selected_box_id, selected_box_quantity);
                $(deleteHelperClass).remove();
                updateGrandTotal();
                var displayPromoCodeBtn =  "no";
                 
                if ($('#promoIdHidden').val())
                {
                    var promoIdWithAmount = $('#promoIdHidden').val();
                    var splitDataGetToIdValue = promoIdWithAmount.split("@#");
                    var promo_box_id = splitDataGetToIdValue[2];
                    var array = promo_box_id.split(",");
                    $('input[name="boxes[]"]').each(function (index, obj)
                    {
                        var boxesArr = $(this).val();
                        var splitBoxesToId = boxesArr.split("_#");
                        var box_id = splitBoxesToId[0];
                        var index = $.inArray(box_id, array);
                        if (index != - 1)
                        {
                            displayPromoCodeBtn = "yes";
                        }
                    });
                }

                if (displayPromoCodeBtn == "no")
                {
                    var box_id = [];
                    $('input[name="boxes[]"]').each(function (index, obj)
                    {
                        var boxesArr = $(this).val();
                        var splitBoxesToId = boxesArr.split("_#");
                        var boxes_id = splitBoxesToId[0];
                         box_id.push(boxes_id);
                    });
                   
                    if (box_id)
                    { 
                    $('#loadingDiv_bakgrnd').show();
                        $.ajax({
                            data: {box_id: box_id},
                            url: "<?= base_url() ?>admin/order/getPromotionBoxes",
                            type: 'POST',
                            dataType: 'html',
                        })
                        .done(function (response) { 
                        if (response != '0')
                        {
                            $('#promotionContainer').html(response);
                                    updateDiscount();
                                    updateNettTotal();

                        }
                        else
                        {
                            $('#discountRow').remove();
                            $('.promoCodeButtons').remove();
                            
                            removePromoCode(); 
      
                        }
                        
                     $('#loadingDiv_bakgrnd').hide();
                      });
                    }
                }
                else
                { 
                    updateNettTotal();
                } 
            

    })

            initializeAutoComplete();
    
            $('.counter').counterUp({
    delay: 10,
            time: 10
    });
    
            $('#codeSelectButton').click(function (){
    $('#loadingDiv_bakgrnd').show();
            codeId = $('#codeIdHidden').val();
            fetchCodeDetails(codeId);
    });
    
            function fetchCodeDetails(codeId)
            {
            $.ajax({
            data:{code_id:codeId},
            url: "<?=base_url()?>admin/order/getCodeDetails",
                    type:'POST',
                    dataType : 'html'
            })
        .done(function( response ) {
                    if ($('.fakeCodeDetailsClass').length == 0)
                    {
                    lastObj = '#codeDropDownDivContainer';
                    }
                    else
                    {
                    lastObj = '.fakeCodeDetailsClass:last';
                    }

                    $(response).insertAfter(lastObj);
            
                            kabupatenTextObj = $(response).find('input[name="kabupatens_name_selected[]"]');
                            kabupatenTextObjId = $(kabupatenTextObj[0]).attr('id')

                            kabupatenIdObj = $(response).find('input[name="kabupatens_selected[]"]');
                            kabupatenIdObj = $(kabupatenIdObj[0]).attr('id')

                            locationObj = $(response).find('input[name="locations_selected[]"]');
                            locationIdArr = $(locationObj[0]).val().split('_#_'); //e.g.  6_#_Luar Jawa

                            initializeKabupatenAutoComplete(locationIdArr[0], kabupatenTextObjId, kabupatenIdObj);
            
                            updateGrandTotal();
                            updateDiscount();
                            promoCodeButton();
                            updateNettTotal();
            
                            $('#codeIdHidden').val('')
                            $('#codeIdSelect').val('')
                            $('#codeDescriptionText').html('')
                            $('#codeSelectButton').addClass('hide');

                            $('#codeBoxSelect').trigger('change')

                            $('#loadingDiv_bakgrnd').hide();
                    });
            }

//    $('#codeIdSelect').change(function (){
//        if ($(this).val() > 0)
//        {
//            option = $('option:selected', this).attr('rel');
//            description = $('option:selected', this).attr('title');
//            $('#codeSelectButton').removeClass('hide');
//            
//            $('#locationIdSelect').val(option);
//            $('#codeDescriptionText').html(description);
//
//        }
//        else
//        {
//            $('#codeSelectButton').addClass('hide');
//            $('#codeDescriptionText').html('');
//
//        }
//    })

    function updateGrandTotal()
    {

    totalPrice = 0;
        
            $('#grandTotalRow').remove();
        
            $('.individualPriceFakeClass').each(function (index, row){
    tmpVal = parseFloat($(row).html());
            totalPrice += tmpVal;
    })

            totalRow = '<div class="form-group" id="grandTotalRow">\n\
                    <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalPriceContainer">' + totalPrice + '</b></label>\n\
                    <input type="hidden" name="total_price" value="' + totalPrice + '">\n\
                    <div class="col-sm-2 pull-right"><b>Total</b></div>\n\
                </div>';
            $(totalRow).insertAfter('.fakeCodeDetailsClass:last');
    }

    function updateDiscount()
    {
    if ($('#repeated_customer').val() > 0)
    {
    totalQuantity = 0;

            $('#discountRow').remove();

            $('.quantityTextBoxClass').each(function (index, row){
    totalQuantity += parseInt($(row).val(), 10);
    })


            discount = parseFloat(<?= $PER_BOX_DISCOUNT ?>) * totalQuantity;
            totalRow = '<div class="form-group" id="discountRow">\n\
                        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">' + discount + '</b></label>\n\
                        <input type="hidden" name="total_discount" value="' + discount + '">\n\
                        <div class="col-sm-2 pull-right"><b>Discount (Repeated Customer)</b></div>\n\
                        <input type="hidden" name="discount_type" value="repeated_customer">\n\
                    </div>';
            $(totalRow).insertAfter('#grandTotalRow');
    }
    else if ($('#agentIdSelect').val() > 0)
    {
    $('#discountRow').remove();
            
            discount = parseFloat($('#agentIdSelect').find('option:selected').attr('rel'));
            
            totalQuantity = 0;
 
            $('.quantityTextBoxClass').each(function (index, row){
    totalQuantity += parseInt($(row).val(), 10);
    })

            discount = discount * totalQuantity;
            
            if(isNaN(discount) == true)
            {
                discount = 0.00;
            }


    totalPrice = parseFloat($('#totalPriceContainer').html());
//            discount = totalPrice * (discount/100);

            totalRow = '<div class="form-group" id="discountRow">\n\
                        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">' + discount + '</b></label>\n\
                        <input type="hidden" name="total_discount" value="' + discount + '">\n\
                        <div class="col-sm-2 pull-right"><b>Discount (Agent Booking)</b></div>\n\
                        <input type="hidden" name="discount_type" value="agent">\n\
                    </div>';
            $(totalRow).insertAfter('#grandTotalRow');
    }
    else
    {
        $('#discountRow').remove();
            
            totalRow = '<div class="form-group" id="discountRow">\n\
                        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">0.00</b></label>\n\
                        <input type="hidden" name="total_discount" value="0.00">\n\
                        <div class="col-sm-2 pull-right"><b>Discount (No Discount)</b></div>\n\
                    </div>';
            $(totalRow).insertAfter('#grandTotalRow');
    }
    }

    $(".form-wizard").bind("before_step_shown", function(event, data){
        switch(data.currentStep)
    {
    case "secondStep" :
            if (data.isBackNavigation == false)
    {
    $('#orderCustomerDetails_CustomerName').html($('#customer').val());
    }

    if ($('#orderCustomerDetails_Pin').val() == '')
    {
    $('#orderCustomerDetails_Pin').val($('#pinCodeText').val());
            $('#orderCustomerDetails_Building').val($('#buildingTextBox').val());
            $('#orderCustomerDetails_Street').val($('#streetTextBox').val());
            $('#orderCustomerDetails_Unit').val($('#unitTextBox').val());
            $('#orderCustomerDetails_Block').val($('#blockTextBox').val());
    }
//                    
//                            $('#loadingDiv_bakgrnd').show();
//                            $.ajax({
//                                data:$('#myForm :input').serializeObject(),
//                                url: "<?=base_url()?>admin/order/saveCustomer",
//                                type: "POST",
//                                dataType: "JSON",
//                            })
//                            .done(function( response ) {
//                                console.log(response )
//                                $('#orderCustomerDetails_CustomerName').html(response.name);
//                                $('#orderCustomerDetails_Pin').val(response.pin);
//                                $('#orderCustomerDetails_Building').val(response.building);
//                                $('#orderCustomerDetails_Street').val(response.street);
//                                $('#orderCustomerDetails_Unit').val(response.unit);
//                                $('#orderCustomerDetails_Block').val(response.block);
//                                $('#orderCustomerDetails_CustomerId').val(response.customer_id);
//                                $('#orderCustomerDetails_RepeatedCustomer').val(response.repeated_customer);
//                                $('#loadingDiv_bakgrnd').hide();
//                            });
    break;

            case "thirdStep" :
               var submit_hidden = '<?php echo $submit_hidden;?>';
                    if(submit_hidden == 'hidden')
    {
    $('#next').addClass('hidden');
    }
    showWeightControl = false;
            $("input[name='capture_weight[]']").each(function (index, row){
    if ($(this).val() == 'yes')
    {
    showWeightControl = true;
    }
    })

            if (showWeightControl == true)
    {
    $('.weightContainer').show()
    }
    else
    {
    $('.weightContainer').hide()
    }
//                            $('#loadingDiv_bakgrnd').show();
//                            $.ajax({
//                                data:$('#ss').serializeObject(),
//                                url: "<?=base_url()?>admin/order/saveOrder",
//                                type: "POST",
//                                dataType: "JSON",
//                            })
//                            .done(function( response ) {
//                                $('#loadingDiv_bakgrnd').hide();
//                            });
    break;
    }
    });
    
            $('#customerFormSubmitButton').click(function (){
    console.log($('#myForm :input').serializeObject());
            console.log('here')
    })

    function promoCodeButton()
    {
        var box_id = []; 
        $('input[name="boxes[]"]').each(function (index, obj)
        {
            var boxesArr = $(this).val();
            var splitBoxesToId = boxesArr.split("_#");
            var boxes_id = splitBoxesToId[0];
            box_id.push(boxes_id);
        });
       
$('#loadingDiv_bakgrnd').show();
        $.ajax({
            data: {box_id: box_id},
                    url: "<?= base_url() ?>admin/order/getPromotionBoxes",
                    type: 'POST',
                    dataType: 'html',
            })
            .done(function (response) {
                if ($("#promoIdHidden").val())
                {  
                    updateDiscountByGetAllBoxes();
                    checkDiscountByCollectionDate();
                }
 
                if (response != '0')
                {
                    $('.promoCodeButtons').remove();
                    promotionBtn = '<div id="promCodeRow" class="form-group promoCodeButtons">\n\
                                   <div class="col-sm-2 pull-right"><b><input type="button" style="background:red;color:white" value="Remove PromoCode" class="removePromotionBtn"></b></div>\n\
               \n\<div class="col-sm-2 pull-right"><b><input type="button" style="background:green;color:white" value="Apply PromoCode" class="promotionBtn"> Alt+P</b></div>\n\
                               </div>';
                    $(promotionBtn).insertAfter('#discountRow');
                    showHidePromoCodeRemoveBtn();
                    $('#promotionContainer').html(response);

                    $('.promotionBtn').click(function () {
                        $('#promotionModal').modal('toggle');
                    });

                    $('#selectPromotion').click(function (event)                     {
                        $('#promotionModal').modal('hide');
                        if ($('#promoIdHidden').val())
                        {

                            updateDiscountByGetAllBoxes();
                            checkDiscountByCollectionDate();
                        }
                    }); 
                    $('.removePromotionBtn').click(function ()
                    { 
                            removePromoCode();
                    }); 
                }
               

$('#loadingDiv_bakgrnd').hide();

                function updateDiscountByGetAllBoxes()
                { 
                    var promoIdWithAmount = $('#promoIdHidden').val();
                    var splitDataGetToIdValue = promoIdWithAmount.split("@#");
                    var promotion_id = splitDataGetToIdValue[0];
$('#loadingDiv_bakgrnd').show();

                    $.ajax({
                    data: {promotion_id: promotion_id},
                            url: "<?= base_url() ?>admin/order/getAllPromoBoxesByBoxid",
                            type: 'POST',
                            dataType: 'html',
                    })
                    .done(function (response) {
                        $('#promoIdHidden').val(response);
                        if($("#promoIdHidden").val())
                        {
                            $(".removePromotionBtn").show();
                        }
  
                        //set check-box btn true of selected promo-code    
                        var promoCodeRadioInputBtn  = $("#promotionContainer").find("div table tbody tr td input");
                        if(promoCodeRadioInputBtn)
                        {
                            $(promoCodeRadioInputBtn).each(function (index, obj)
                            {
                                var promoIdWithAmountInModal = $(this).val();
                                var splitDataGetToIdValueInModal = promoIdWithAmountInModal.split("@#");
                                var promotionIdInModal = splitDataGetToIdValueInModal[0];

                                if(promotion_id == promotionIdInModal)
                                { 
                                    $(this).prop('checked', true);
                                } 

                            });
                        }
$('#loadingDiv_bakgrnd').hide();

                    });



                    var promoIdWithAmount = $('#promoIdHidden').val();
                    var splitDataGetToIdValue = promoIdWithAmount.split("@#");
                    var promotion_id = splitDataGetToIdValue[0];
                    var amount = splitDataGetToIdValue[1];
                    var promo_box_id = splitDataGetToIdValue[2];
                    var promo_name = splitDataGetToIdValue[3];
                    var array = promo_box_id.split(",");
                    var totalBoxQtyArr = [];
                    var box_id_arr = [];
                    var discountPayable = 'no'; 

                    $('input[name="boxes[]"]').each(function (index, obj)
                    { 
                        var boxesArr = $(this).val();
                        var splitBoxesToId = boxesArr.split("_#");
                        var box_id = splitBoxesToId[0];
                        var index = $.inArray(box_id, array);
                        if (index != - 1)
                        {

                            discountPayable = 'yes';
                            if ($(this).parents('.form-group').find('.box-label span').length <= 0)
                            {
                                $(this).parents('.form-group').find('.box-label').append("<span style='background-color:green;margin-left:20px;color:white'>Promocode Applied</span>");
                            }
                            quantity = $(this).parents('.form-group').find('.quantityTextBoxClass').val();
                            totalBoxQtyArr.push(quantity);
                        }
                        else
                        {

                             if ($(this).parents('.form-group').find('.box-label span').length > 0)                                   {
                             $(this).parents('.form-group').find('.box-label span').remove();
                             }

                        }       
                    });

                    var totalBoxQuantity = 0;
                    for (var i = 0; i < totalBoxQtyArr.length; i++) {
                        totalBoxQuantity += totalBoxQtyArr[i] << 0;
                        }

                    discount = parseFloat(amount * totalBoxQuantity);
                    $("#discount").val(discount);
                    if (discountPayable == 'yes')
                    {
                        $('#discountRow').remove();
                                totalDiscount = '<div class="form-group" id="discountRow">\n\
                                                  <label for="textfield" class="control-label col-sm-2 pull-right">$ -<b id="totalDiscountContainer">' + discount + '</b></label>\n\
                                                  <input type="hidden" name="total_discount" value="' + discount + '">\n\
                                                  <div class="col-sm-2 pull-right"><b>Discount<br/><span class="green_text">' + promo_name + ' Applied ($' + amount + ')</span></b></div>\n\
                                                  <input type="hidden" name="discount_type" value="promocode_discount">\n\
                      \n\<input type="hidden" name="promocode_id" value="' + promotion_id + '">\n\
                                              </div>';
                                $(totalDiscount).insertAfter('#grandTotalRow');
                    }
                    else
                    {
                         $('#discountRow').remove();
                                 totalRow = '<div class="form-group" id="discountRow">\n\
                                                 <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">0.00</b></label>\n\
                                                 <input type="hidden" name="total_discount" value="0.00">\n\
                                                 <div class="col-sm-2 pull-right"><b>Discount (No Discount)</b></div>\n\
                                             </div>';
                         $(totalRow).insertAfter('#grandTotalRow');
                    }
                    updateNettTotal();
                }

            });
    }


    function getPromocodeById()
    {
$('#loadingDiv_bakgrnd').show();
        promotion_id = $("#box_procomode_id").val();
           $.ajax({
        data: {promotion_id: promotion_id},
                url: "<?= base_url() ?>admin/order/getAllPromoBoxesByBoxid",
                type: 'POST',
                dataType: 'html',
        })
        .done(function (response) {
            if (response != '0' && response != '@#@#@#')
            {  
               $('#promoIdHidden').val(response);
                $(".removePromotionBtn").show();
                 
            
                var promoIdWithAmount = $('#promoIdHidden').val();
                if(promoIdWithAmount)
                {
                    $(".removePromotionBtn").show();
                    var splitDataGetToIdValue = promoIdWithAmount.split("@#");
                    var promotion_id = splitDataGetToIdValue[0];

                    //set check-box btn true of selected promo-code    
                    var promoCodeRadioInputBtn  = $("#promotionContainer").find("div table tbody tr td input");
                    if(promoCodeRadioInputBtn)
                    {
                        $(promoCodeRadioInputBtn).each(function (index, obj)
                        {
                            var promoIdWithAmountInModal = $(this).val();
                            var splitDataGetToIdValueInModal = promoIdWithAmountInModal.split("@#");
                            var promotionIdInModal = splitDataGetToIdValueInModal[0];

                            if(promotion_id == promotionIdInModal)
                            { 
                                $(this).prop('checked', true);
                            } 

                        });
                    }
                } 
            } 
        });
    }


    $(document).keypress(function(e)
    {
        if(e.altKey && e.charCode == 112)
        { 
            $('#promotionModal').modal('show');
        }
    });
    
    
    function showHidePromoCodeRemoveBtn()
    {
        if ($('#promoIdHidden').val())
        {
            $(".removePromotionBtn").show();
        }
        else
        {
            $(".removePromotionBtn").hide();
        }
    }

    function removePromoCode()
    {
        if ($('#promoIdHidden').val())
        {
            $('#discountRow').remove();
                totalRow = '<div class="form-group" id="discountRow">\n\
                                <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">0.00</b></label>\n\
                                <input type="hidden" name="total_discount" value="0.00">\n\
                                <div class="col-sm-2 pull-right"><b>Discount (No Discount)</b></div>\n\
                            </div>';
                $(totalRow).insertAfter('#grandTotalRow');
            if ($("div .form-group").find('.box-label span').length > 0)
            {   
                $("div .form-group").find('.box-label span').remove();
            }

            $('#promoIdHidden').val('');
            $("#promotionContainer").find('.selectPromoRadio').prop('checked', false);
            $(".removePromotionBtn").hide();
                
            var promoCodeRadioInputBtn  = $("#promotionContainer").find("div table tbody tr td input");
            if(promoCodeRadioInputBtn)
            {
                $(this).prop('checked', false);
            }
        }

        updateDiscount();
        updateNettTotal();
    }

    function updatePromoDiscount()
    {
        if ($('#promoIdHidden').val())
        {
            var promoIdWithAmount = $('#promoIdHidden').val();
            var splitDataGetToIdValue = promoIdWithAmount.split("@#");
            var promotion_id = splitDataGetToIdValue[0];
            var amount = splitDataGetToIdValue[1];
            var promo_box_id = splitDataGetToIdValue[2];
            var promo_name = splitDataGetToIdValue[3];
            var array = promo_box_id.split(",");
            var totalBoxQtyArr = []

            $('input[name="boxes[]"]').each(function (index, obj)
            {
                var boxesArr = $(this).val();
                var splitBoxesToId = boxesArr.split("_#");
                var box_id = splitBoxesToId[0];
                var index = $.inArray(box_id, array);
                if (index != - 1)
                {
                    $(this).parents('.form-group').find('.quantityTextBoxClass')
                    quantity = $(this).parents('.form-group').find('.quantityTextBoxClass').val();
                    totalBoxQtyArr.push(quantity);
                }
            });
            
            var totalBoxQuantity = 0;
            for (var i = 0; i < totalBoxQtyArr.length; i++) {
                totalBoxQuantity += totalBoxQtyArr[i] << 0;
                }

            if (totalBoxQuantity != '0')
            {
                $('#discountRow').remove();
                discount = parseFloat(amount * totalBoxQuantity);
                $("#discount").val(discount);
                totalDiscount = '<div class="form-group" id="discountRow">\n\
                                        <label for="textfield" class="control-label col-sm-2 pull-right">$ -<b id="totalDiscountContainer">' + discount + '</b></label>\n\
                                        <input type="hidden" name="total_discount" value="' + discount + '">\n\
                                        <div class="col-sm-2 pull-right"><b>Discount<br/><span class="green_text">' + promo_name + ' Applied ($' + amount + ')</span></b></div>\n\
                                        <input type="hidden" name="discount_type" value="promocode_discount">\n\
            \n\<input type="hidden" name="promocode_id" value="' + promotion_id + '">\n\
                                    </div>';
                $(totalDiscount).insertAfter('#grandTotalRow');
            }
        }
    }


    function deletePromoDiscount(selected_box_id, selected_box_quantity)
    {
        if ($('#promoIdHidden').val())
        {
            var totalDiscount = $("input[name='total_discount']").val();
            var promoIdWithAmount = $('#promoIdHidden').val();
            var splitDataGetToIdValue = promoIdWithAmount.split("@#");
            var promotion_id = splitDataGetToIdValue[0];
            var amount = splitDataGetToIdValue[1];
            var promo_box_id = splitDataGetToIdValue[2];
            var promo_name = splitDataGetToIdValue[3];
            var array = promo_box_id.split(",");
            var index = $.inArray(selected_box_id, array);
             
            if (index != - 1)
            {
             
                $('#discountRow').remove();
                discount = totalDiscount - parseFloat(selected_box_quantity * amount);
                var grandTotal = $("div").find("#totalPriceContainer").html();
                if (grandTotal < discount)
                {
                    discount = "0.00";
                }

                totalDiscount = '<div class="form-group" id="discountRow">\n\
                                            <label for="textfield" class="control-label col-sm-2 pull-right">$ -<b id="totalDiscountContainer">' + discount + '</b></label>\n\
                                            <input type="hidden" name="total_discount" value="' + discount + '">\n\
                                            <div class="col-sm-2 pull-right"><b>Discount<br/><span class="green_text">' + promo_name + ' Applied ($' + amount + ')</span></b></div>\n\
                                            <input type="hidden" name="discount_type" value="promocode_discount">\n\
                \n\<input type="hidden" name="promocode_id" value="' + promotion_id + '">\n\
                                        </div>';
                        $(totalDiscount).insertAfter('#grandTotalRow'); 

            }
        }
    }

    function updateNettTotal()
    {
    $('#nettTotalRow').remove();
            totalPrice = parseFloat($('#totalPriceContainer').html());
            totalDiscount = parseFloat($('#totalDiscountContainer').html());

            updateTotalFlag = $('#agentIdSelect option:selected').attr('type');
        
            nettTotal = totalPrice - totalDiscount;
        
            if (updateTotalFlag == 'yes')
    {
    nettTotalRow = '<div class="form-group" id="nettTotalRow">\n\
                            <label class="control-label col-sm-2 pull-right">$ \n\
                            <input type="text" id="nettTotalContainer" name="nett_total" value="' + nettTotal + '"></b></label>\n\
                            <div class="col-sm-2 pull-right"><b>Nett Total</b></div>\n\
                        </div>';
    }
    else
    {
    nettTotalRow = '<div class="form-group" id="nettTotalRow">\n\
                            <label class="control-label col-sm-2 pull-right">$ <b id="nettTotalContainer">' + nettTotal + '</b></label>\n\
                            <input type="hidden" name="nett_total" value="' + nettTotal + '">\n\
                            <div class="col-sm-2 pull-right"><b>Nett Total</b></div>\n\
                        </div>';
    }

    if ($("#promCodeRow").find(".removePromotionBtn").val())
    {
        $(nettTotalRow).insertAfter('#promCodeRow');
    }
    else
    {
        $(nettTotalRow).insertAfter('#discountRow');
    }

    }

    $('#agentIdSelect').change(function (){
    orderNumberType = $('option:selected', this).attr('href');
            $('#orderNumberAgentName').html($('option:selected', this).attr('html'));
        
            if (orderNumberType == 'manual')
    {
    $('#manualOrderSpanInfo').show('slow');
    }
    else
    {
    $('#manualOrderSpanInfo').hide();
    }  
    if ($('#promoIdHidden').val() == "" || $('#promoIdHidden').val() == null)
    {
        updateGrandTotal();
        updateDiscount();
        updateNettTotal();

    }
   })


    $('input[name="collection_date"]').change(function ()
    { 
        if ($('#promoIdHidden').val())
        {
            var promoIdWithAmount = $('#promoIdHidden').val();
            var splitDataGetToIdValue = promoIdWithAmount.split("@#");
            var promotion_id = splitDataGetToIdValue[0];
$('#loadingDiv_bakgrnd').show();
            $.ajax({
                data: {promotion_id: promotion_id},
                        url: "<?= base_url() ?>admin/order/getPromoCodeByid",
                        type: 'POST',
                        dataType: 'html',
                })
                .done(function (response) {

                    if (response != "0")
                    {
                        var data = jQuery.parseJSON(response);
                        var promotion_expiary_date = data.date_to;
                        var collection_date = $('input[name="collection_date"]').val();
                        if (collection_date)
                        {
                            collection_date = collection_date.split('/')
                            collection_date = collection_date[2] + '-' + collection_date[1] + '-' + collection_date[0];
                            if (collection_date > promotion_expiary_date)
                            {
                                $('#promoExpiryModal').modal('show'); 

                                $("#promotionExpiryTextContainer").html("<h3>Promocode Expiry date is: <b>" + promotion_expiary_date + "</b>, Please select collection date less than " + promotion_expiary_date + "</h3>");
                                //Click Button
                                $("#promoExpiryOkBtn").click(function ()
                                { 
                                    set_promotion_collection_date = promotion_expiary_date.split('-')
                                    set_promotion_collection_date = set_promotion_collection_date[2] + '/' + set_promotion_collection_date[1] + '/' + set_promotion_collection_date[0];
                                $("input[name='collection_date']").val(set_promotion_collection_date);
                                    promoCodeButton();
                                });

                                //Cancel Button
                                $("#promoExpiryCancelBtn").click(function ()
                                { 
                                    $('#promoExpiryModal').modal('hide');
                      
                                    $('.removePromotionBtn').hide();
                                    if ($('.form-group').find('.box-label span').length > 0)
                                    {
                                        $('.form-group').find('.box-label span').remove();
                                    }
                                    removePromoCode();
                                    updateGrandTotal();
                                    updateDiscount();
                                    updateNettTotal();
                                });
                            }
                            else
                            {
                               promoCodeButton();
                            }
                        }
                    }
$('#loadingDiv_bakgrnd').hide();

                    });

        } 

    });
            
            
    function checkDiscountByCollectionDate()
    {
        var promoIdWithAmount = $('#promoIdHidden').val();
        var splitDataGetToIdValue = promoIdWithAmount.split("@#");
        var promotion_id = splitDataGetToIdValue[0];
$('#loadingDiv_bakgrnd').show();
        $.ajax({
        data: {promotion_id: promotion_id},
                url: "<?= base_url() ?>admin/order/getPromoCodeByid",
                type: 'POST',
                dataType: 'html',
        })
        .done(function (response) {

        if (response != "0")
        {
            var data = jQuery.parseJSON(response);
            var promotion_expiary_date = data.date_to;
            var collection_date = $('input[name="collection_date"]').val();
            if (collection_date)
            {
                collection_date = collection_date.split('/')
                collection_date = collection_date[2] + '-' + collection_date[1] + '-' + collection_date[0];
                if (collection_date > promotion_expiary_date)
                {
                    $('#promoExpiryModal').modal('show');
                    $("#promotionExpiryTextContainer").html("<h3>Promocode Expiry date is: <b>" + promotion_expiary_date + "</b>, Please select collection date less than " + promotion_expiary_date + "</h3>");
                //Click Button
                    $("#promoExpiryOkBtn").click(function ()
                    {
                         $('.removePromotionBtn').show();
                        set_promotion_collection_date = promotion_expiary_date.split('-')
                        set_promotion_collection_date = set_promotion_collection_date[2] + '/' + set_promotion_collection_date[1] + '/' + set_promotion_collection_date[0];
                        $("input[name='collection_date']").val(set_promotion_collection_date);
                        promoCodeButton();

                    });
                    //Cancel Button
                    $("#promoExpiryCancelBtn").click(function ()
                    {
                        $('#promoExpiryModal').modal('hide');
                        removePromoCode();
                        updateGrandTotal();
                        updateDiscount();
                        updateNettTotal();
                    });
                }
            }
        }

$('#loadingDiv_bakgrnd').hide();
        });
    }


            $('.datepick1').datepicker({
            dateFormat: "dd/mm/yy"
            })
                    $('.datepick2').datepicker({
            dateFormat: "dd/mm/yy"
            })
                    $('.datepick3').datepicker({
            dateFormat: "dd/mm/yy"
            })
                    $('.datepick4').datepicker({
            dateFormat: "dd/mm/yy"
            })
                    $('.datepick5').datepicker({
            dateFormat: "dd/mm/yy"
            })

                    $('#datepick3').trigger('change')
                    $('.weightContainer').hide()

    fetchCustomerHistoryData(<?=$order_details['order']['customer_id']?>);
                    $('#datepick3').trigger('change');
    
                    $('#cancelOrderButton').click(function(){
                        if (confirm('Are you sure you want to cancel this Order?'))
                        {
                            window.location.href = "<?php echo base_url().'admin/order/cancelOrder/'. $order_id?>"
                        }
                    })
                    
                    $('#KIVOrderButton').click(function(){
                        if (confirm('Are you sure you want to update KIV Status?'))
                        {
                            window.location.href = "<?php echo base_url().'admin/order/updateOrderKIVStatus/'. $order_id?>/" + $(this).attr('rel')
                        }
                    })

                    $('.fakeCodeDetailsClass').each(function (){
        if($(this).find('input[name="locations_selected[]"]').length > 0)
            {
            kabupatenTextObj = $(this).find('input[name="kabupatens_name_selected[]"]');
                    kabupatenTextObjId = $(kabupatenTextObj[0]).attr('id')

                    kabupatenIdObj = $(this).find('input[name="kabupatens_selected[]"]');
                    kabupatenIdObj = $(kabupatenIdObj[0]).attr('id')

                    locationObj = $(this).find('input[name="locations_selected[]"]');
                    locationIdArr = $(locationObj[0]).val().split('_#_'); //e.g.  6_#_Luar Jawa

                    initializeKabupatenAutoComplete(locationIdArr[0], kabupatenTextObjId, kabupatenIdObj);
            }
            })

                    $('#manualOrderSpanInfo').hide();
                    })
</script>