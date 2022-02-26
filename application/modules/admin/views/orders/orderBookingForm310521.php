<style type="text/css">
    .modal-dialog {
        margin: 30px auto;
        width: 80%;
    }
    .green_text
    {
        color:#008000;
    }
</style>


<div id="dsmain" class="page-content main_container_padding">


    <?php
    if (!empty($order_number))
    {
        ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><b><?=$order_number?></b> saved successfully.</div>
        <?php
    }

    if (!empty($message))
    {
        ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><?=$message?></div>
        <?php
    }
    ?>
    <div class="alert alert-warning hidden" style="margin-top:20px" role="alert"  id="warning"></div>
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
                                        <button class="btn btn-danger" type="submit" style="background-color:#e63a3a">
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
                        Order Booking Form
                    </h3>
                </div>
                <div class="box-content">
                    <form method="POST" onsubmit="return false;" class='form-horizontal form-wizard' id="ss">
                        <input type="hidden"  name='manual_order_number' id='manual_order_number'>

                        <div class="step" id="firstStep">
                            <ul class="wizard-steps steps-2">
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
                                <!--
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
                                -->
                            </ul>
                            <div class="step-forms">




                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="box box-bordered" style="border-top:1px solid #e7e7e7">

                                            <div class="box-content nopadding" id="myForm">
                                                <!--<form action="#" name="myForm" id="myForm" method="POST" class='form-horizontal form-bordered'>-->
                                                <div class="form-group">

                                                    <label for="textfield" class="control-label col-sm-2">Customer Name
                                                        <a href="#" class="pull-right" title="Clear Fields" style="padding-right:4px" id="clearFields"><i class="glyphicon-minus-sign"></i></a>
                                                        <a href="#" class="pull-right" data-toggle="modal" style="padding-right:4px" data-target="#myModal" id="myModal"><i class="glyphicon-search"></i></a>
                                                        <a href="#" class="hide pull-right" data-toggle="modal" style="padding-right:4px"  data-target="#orderHistoryModal" id="customerHistory"><i class="glyphicon-history"></i></a>
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                            <input type="text" placeholder="Customer Name" name="customer_name" id='customer' class='form-control' data-rule-required="true" tabindex="1">
                                                            <input type="hidden"  name='customer_id' id='customer_id' value="0">
                                                            <input type="hidden"  name='repeated_customer' id='repeated_customer'>
                                                            <input type="hidden"  name='isRepeatedCustomer' id='isRepeatedCustomer'>

                                                            <input type="hidden"  name='lattitude' id='lattitude'>
                                                            <input type="hidden"  name='longitude' id='longitude'>

                                                        </div>
                                                    </div>

                                                    <label for="textfield" class="control-label col-sm-2">
                                                        Postal Code
                                                        <a href="#" class="pull-right"  id="pinLookUpLink"><i class="glyphicon-search"></i></a>
                                                        <a href="#" title="Clear Fields" style="padding-right:4px" class="pull-right" id="clearAddressFields"><i class="glyphicon-minus-sign"></i></a>
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <input tabindex="5" type="text" placeholder="Postal Code" class='form-control' id="pinCodeText" name="pin">
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
                                                            <input type="text" tabindex="2" placeholder="Mobile" id="mobile" class='form-control' name="mobile">

                                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                            <input type="text" tabindex="3" placeholder="Phone" id="phone" class='form-control' name="phone">
                                                        </div>
                                                    </div>


                                                    <label for="textfield" class="control-label col-sm-2">
                                                        Building/Estate
                                                        <a href="#" class="pull-right"  id="reverseLookUpLink"><i class="glyphicon-step_backward"></i></a>
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <input tabindex="6" type="text" id="buildingTextBox" placeholder="Building/Estate" class='form-control' name="building">
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
                                                                {  ?>
                                                                    <option value="<?=$row['customer_type_id']?>"><?=$row['customer_type']?></option>
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
                                                                {  ?>
                                                                    <option value="<?=$row['media_type_id']?>"><?=$row['media_type']?></option>
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
                                                            <input tabindex="4" type="text" placeholder="Email" id="email" class='form-control' name="email">
                                                        </div>
                                                    </div>



                                                    <label for="textfield" class="control-label col-sm-2">
                                                        Street
                                                    </label>
                                                    <div class="col-sm-4">
                                                        <div class="input-group">
                                                            <input tabindex="7" type="text" placeholder="Street" class='form-control' id="streetTextBox" name="street">
                                                            <span id="pinCodeLocation" class="input-group-addon">
                                                                <i class="fa fa-location-arrow"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">                                                    
                                                    
                                                    <label for="textfield" class="control-label col-sm-2">Blacklist Customer </label>
                                                    <div class="col-sm-4">

                                                        <div class="input-group">
                                                            <input type="checkbox" name="blacklistCustomer" value="1"  class="form-control searchFormClass" id="blacklistCustomer" >

                                                        </div>
                                                    </div>
                                                    
                                                    <label for="textfield" class="control-label col-sm-2">Block : Unit </label>
                                                    <div class="col-sm-4">

                                                        <div class="input-group">

                                                            <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                            <input tabindex="8" type="text" id="blockTextBox" placeholder="Block" class='form-control' name="block">

                                                            <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                            <input tabindex="9" type="text" id="unitTextBox" placeholder="Unit" class='form-control' name="unit">

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group">                                                    
                                                    <label for="textfield" class="control-label col-sm-2">Comment</label>
                                                    <div class="col-sm-4">
                                                        <textarea name="bcomments" style="width:418px;height:83px"  class='form-control' placeholder="Comments"></textarea>
                                                    </div>


                                                </div>
                                                <div class="form-group">                                                    
                                                    <label for="textfield" class="control-label col-sm-2">&nbsp;</label>
                                                    <div class="col-sm-4">
                                                        <button tabindex="10" type="button" class="btn btn-primary" id="saveCustomerOnlyButton">Save Customer</button>
                                                        <span class="passport_phont_link">
                                                        </span>
                                                        <span class="passport_phont_update">
                                                        </span>
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
                            <ul class="wizard-steps steps-2">
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
                                <!--
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
                                -->
                            </ul>







                            <div class="row">
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



                                        <div class="box-content nopadding">
                                            <div class="form-group">
                                                <label for="order_date" class="control-label col-sm-2">Order Date</label>
                                                <div class="col-sm-4">
                                                    <div class='input-group date' id='datetimepicker1'>
                                                        <input type="text" name="order_date" id="order_date" class="form-control datepick1" value="<?= date('d/m/Y') ?>">
                                                    </div>

                                                </div>



                                                <label for="orderCustomerDetails_CustomerName" class="control-label col-sm-2">Customer Name</label>
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
                                                                    ?>
                                                                        <option  type="<?=$row['can_update_total']?>" href="<?=$row['order_no_type']?>" rel="<?=$row['commission']?>" value="<?=$row['id']?>"><?=$row['name']?></option>
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

                                            <div class="form-group" id="codeDropDownDivContainer">
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
                                                                    $str .= "<option $selected value='{$row['id']}'>{$row['name']}</option>";
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

                                            <div class="form-group">
                                                <label for="delivery_date" class="control-label col-sm-2">
                                                    Delivery Date
                                                </label>
                                                <div class="col-sm-4">
                                                    <div class='input-group date'>
                                                        <input type="text" name="delivery_date" id="delivery_date" class="form-control datepick2" value=''>
                                                    </div>

                                                </div>


                                                <label for="textfield" class="control-label col-sm-2">Collection Date</label>
                                                <div class="col-sm-4">
                                                    <div class='input-group date' id='datetimepicker3'>
                                                        <input type="text" name="collection_date" id="datepick3" class="form-control datepick3" value=''>
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
                                                        <input type="text" placeholder="Postal Code" class='form-control' id="orderCustomerDetails_Pin" name="pin_order">
                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                            <i class="fa fa-location-arrow"></i>
                                                        </span>
                                                    </div>
                                                </div>


                                                <label for="textfield" class="control-label col-sm-2">Building/Estate</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <input type="text" id="orderCustomerDetails_Building" placeholder="Building/Estate" class='form-control' name="building_order">
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
                                                        <input type="text" placeholder="Street" class='form-control' id="orderCustomerDetails_Street" name="street_order">
                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                            <i class="fa fa-location-arrow"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <label for="textfield" class="control-label col-sm-2">Unit : Block</label>
                                                <div class="col-sm-4">

                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                        <input type="text" id="orderCustomerDetails_Unit" placeholder="Unit" class='form-control' name="unit_order">

                                                        <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                        <input type="text" id="orderCustomerDetails_Block" placeholder="Block" class='form-control' name="block_order">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label for="textfield" class="control-label col-sm-2">Delivery Notes</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <textarea name="comments" style="width:418px;height:83px"  class='form-control' placeholder="Delivery Notes"></textarea>

                                                    </div>
                                                </div>

                                                <label for="collectionNotesClass" class="collectionNotesClass control-label col-sm-2">Collection Notes</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <textarea name="collection_notes" style="width:418px;height:83px" id="collectionNotesClass"  class='collectionNotesClass form-control' placeholder="Collection Notes"></textarea>

                                                    </div>
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
            <form class="form-horizontal" id="form_horizontal" action="#" enctype="multipart/form-data">
                <div class="form-outer div-center">
                    <div class="form-group customers" >

                    </div>
                    <!-- Text input-->
                    <div class="form-group">
                        <input id="id_number" name="id_number[]" placeholder="ID Number" class="form-control input" required="" type="text">
                    </div>
                    <!-- file input-->
                    <div class="form-group">
                        <input id="passport" name="passport[]" placeholder="Passport" class="col-md-11 input passport" style="border: 1px solid lightgrey; padding: 3px"  type="file">
                        <span class="remove_button"></span>
                    </div>
                    <div class="modal-footer">
                          <button id="submit_button" name="submit_button" class="btn btn-primary" data-toggle="modal" href="result.php">upload</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
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
                        window.location.replace("<?php echo base_url().'admin/order/orderBookingForm'; ?>");
                    }
                }
            });
        }
    });
    
    
    //alert box for delete button 
    $(".remove_button").click(".remove",function(){
        var customer_id = $(".remove_button").find(".remove").attr("customer_id");
        var passport_img = $(".remove_button").find(".remove").attr("passport_img");
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
        
        $('.passport_phont_link').click(".passport_img_show_model_link", function (){

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
        
        $('.passport_phont_update').click(".passport_img_update_model", function (){

            var customer_id = $(".passport_img_update_model").attr("customer_id");
            var customer_name = $(".passport_img_update_model").attr("customer_name");           
            var id_number = $(".passport_img_update_model").attr("id_number");          
            var passport_img = $(".passport_img_update_model").attr("passport_img");          
             $(".customers").html('<input type="hidden" id="customer_id" name="customer_id[]" value="'+customer_id+'" >'+customer_name);
            $("#id_number").val(id_number);
            if (passport_img != '')
            {
              $(".remove_button").html('<button type="button" class="btn col-md-1 remove" passport_img="'+passport_img+'" customer_id="'+customer_id+'"  style="background-color: #e63a3a; color: white">Delete</button>');
            } 
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
        //check for blacklist user
        $('#mobile, #phone').focusout(function(e){            
            $('#warning').addClass('hidden');
            $('#saveCustomerOnlyButton').removeClass('disabled');
            $('#next').removeClass('disabled'); 
            $('#loadingDiv_bakgrnd').show();
             var mobile = $("#mobile").val();
             var phone = $("#phone").val();
             
                $.ajax({
                data:{phone:phone , mobile:mobile},
                url: "<?=base_url()?>admin/order/getBlacklistByPhoneFromDB",
                    cache: false,
                dataType : 'json',
                type : 'post',
                })
            .done(function( response ) {
                if(response.warning != "")
                {
                    $('#warning').html(response.warning);
                    $('#warning').removeClass('hidden');
//                  alert(response.warning);
                    $('#saveCustomerOnlyButton').addClass('disabled');
                    $('#next').addClass('disabled');  
                    
                }
                 $('#loadingDiv_bakgrnd').hide();
            });
        });
    $('#pinLookUpLink, #pinLookUpLink_Customer').click(function(){
        $('#warning').addClass('hidden');
        $('#saveCustomerOnlyButton').removeClass('disabled');
        $('#next').removeClass('disabled'); 
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
                            if(response.warning != "")
                            {
                                $('#warning').html(response.warning);
                                $('#warning').removeClass('hidden');
//                                alert(response.warning);
                                $('#saveCustomerOnlyButton').addClass('disabled');
                                $('#next').addClass('disabled');  
                            }
                        });
            }
        })

    $('#reverseLookUpLink').click(function(){

        var streetTextVal =  $('#streetTextBox').val();
        var blockTextVal =  $('#blockTextBox').val();

            if (streetTextVal == '' && blockTextVal == '')
            {
            bootbox.alert("Please enter at least Street or Block value. Entering both would narrow the search.", function() {
                });
        }
        else
            {
                $('#img_load_chart').html('Pulling address info.');
                $('#loadingDiv_bakgrnd').show();

                $.ajax({
                data:{street:streetTextVal, block:blockTextVal},
                //url: "<?=base_url()?>admin/order/getAddressByPinCode",
                url: "<?=base_url()?>admin/order/getPinCodeByAddressFromDB",
                    cache: false,
                dataType : 'json',
                type : 'post',
                })
            .done(function( response ) {
                            if (response.length != 0)
                            {
                                $('#blockTextBox').val(response.block);
                                $('#streetTextBox').val(response.street);

                                $('#pinCodeText').val(response.postalcode);
                                $('#buildingTextBox').val(response.building);
                                //$('#unitTextBox').val(response.building);

                                $('#lattitude').val(response.lattitude);
                                $('#longitude').val(response.longitude);
                }
                else
                            {
                                alert('No records found.');
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
                    $('#delivery_date').focus();
                    return false;
                }

            if($('#lattitude').val() == '' || $('#longitude').val() == '')
                {
                    alert('Seems like there was some error while saving pin code. Kindly start the process again.');
                    return false;
                }

                grandTotalObj = $('#grandTotalRow');
            if(grandTotalObj.length == 0)
                {
                    $('#codeIdSelect').focus();
                    alert('Please select any code to proceed');
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

                var mobile = $("#mobile").val();
                var phone = $("#phone").val();
                var postal_code = $("#pinCodeText").val();
                var isRepeatedCustomer = $("#isRepeatedCustomer").val();
                var deliveryDate = $('#delivery_date').val();

            if($('#agentIdSelect option:selected').attr('href') == 'manual' && $('#manual_order_number').val() == '')
                {
                    manualOrderNo = prompt("Please enter order number");
                    $('#manual_order_number').val(manualOrderNo)
                    //            bootbox.prompt("Please enter order number.", function (result){
                    //                $('#manual_order_number').val(result);
                    //                $('#next').trigger('click')
                    //                return;
                    //            })
                }


                function saveOrder()
                {
                    $('#loadingDiv_bakgrnd').show();

                    $('#next').addClass('disabled');

                    data = $('#ss').serializeObject();

                    $.ajax({
                            data:data,
                            url: "<?=base_url()?>admin/order/saveFullOrder",
                            type:'POST',
                            dataType : 'JSON'
                    })
                    .done(function( response ) {
                                orderid = response.order_id;
                                order_number = response.order_number;

                                if (response.status == 'success')
                                {
                                    $('#img_load_chart').html('Generating QR Code...');
                                    $.ajax({
                                data:{raw_order_number : orderid, order_id : orderid},
                                url: "<?=base_url()?>admin/order/generateBarCode",
                                        type: "POST"
                                    })
                            .done(function( response ) {
                                                $('#loadingDiv_bakgrnd').hide();

                                                // Commenting this upon Imran's request
    //                            url = "<?=base_url()?>admin/order/printNow/"+orderid;
                                                //                            window.open(url, '_blank');

                                window.location.href='<?=base_url()?>admin/order/orderBookingForm/0/'+order_number +'?haveSideBar=0'
                                            });
                        }
                        else
                                {
                            window.location.href='<?=base_url()?>admin/order/orderBookingForm?haveSideBar=0'
                                }
                            });
                }

                $('#loadingDiv_bakgrnd').show();
                $('#img_load_chart').html('Loading...');
                $.ajax({
                data:{ mobile : mobile, phone : phone, postal_code : postal_code , isRepeatedCustomer : isRepeatedCustomer, deliveryDate : deliveryDate},
                url: "<?=base_url()?>admin/order/checkDuplicateCustomer",
                    type: "POST"
                })
            .done(function( response ) {
                            $('#loadingDiv_bakgrnd').hide();
                            var result = $(response).find(".order_numbers").html();
               if(result != null)
               {
                    bootbox.alert(response, function() {
                                });

                    $(".forcefully_save_orders").click(function()
                                {
                                    saveOrder();
                                });

                    $(".cancel_save_orders").click(function()
                                {
                           window.location.href='<?=base_url()?>admin/order/index?haveSideBar=0'
                                });

                                return false;
                }
                else
                {
                    saveOrder();
                    return false;
                }
            });
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
              .append( "<a>"+ item.code + "</a>" )
              .appendTo( ul );
            };
        }

        function initializeStreetAutoComplete() {
        $( "#streetTextBox" ).autocomplete({
            source: "<?=base_url()?>admin/order/fetchStreetAutoSuggestion",
                minLength: 3,
            open : function( event, ui ) {
                },
            search  : function(){$('#loadingDiv_bakgrnd').show();},
            open    : function(){$('#loadingDiv_bakgrnd').hide();},
            response    : function(){$('#loadingDiv_bakgrnd').hide();},
          select: function( event, ui ) {
            $("#streetTextBox" ).val(ui.item.street_name)

                    return false;
                }
        }).autocomplete("instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<a>"+ item.street_name + " </a>" )
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
//            $('#img_load_chart').html('Generating Bar Code...');
//            console.log('here os')
//            console.log(response.raw_order_number)
//            $.ajax({
//                data:{raw_order_number : response.raw_order_number},
//                url: "<?=base_url()?>admin/order/generateBarCode",
//                type: "POST"
//            })
//            .done(function( response ) {
//                $('#loadingDiv_bakgrnd').hide();
//                window.location.href='<?=base_url()?>admin/order/form?haveSideBar=0'
//            });
                    });

            return false;

            raw_order_number = $('#raw_order_number').val();
            //order_number = $();
        })

    $('#selectCustomerButton').click(function (event){
            $('#loadingDiv_bakgrnd').show();

            var customerId = $('#customerIdHidden').val()
            var isRepeatedCustomer = $('#isRepeatedCustomer_' + customerId).val();
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
            $('#isRepeatedCustomer').val(isRepeatedCustomer);
            
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
            $('#next').removeClass('disabled');  
            
            var passport_id_number = $('#passport_id_number_' + customerId).html();
            var passport_img = $('#passport_img_' + customerId).val();
            
            if((typeof(passport_img) !=  "undefined" && passport_img != "") || (typeof(passport_id_number) !=  "undefined" && passport_id_number != ""))
            {
            $('.passport_phont_link').html(`   <label for='textfield' class='control-label'>&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <a href='#' path='${passport_img}' class='passport_img_show_model_link'>${passport_id_number}</a>
                                                </label>
                                            `);
            }

            <?php 
            $userId = $this->session->userdata['id'];
            $canEditAccess = canPerformAction('passport_img_update', $userId);  
            if($canEditAccess === TRUE)  { ?>
            $('.passport_phont_update').html(`  &nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href='#' customer_id='${customerId}' customer_name='${nameValue}' id_number='${passport_id_number}' passport_img='${passport_img}' class='passport_img_update_model'>
                                                    <i class='fa fa-refresh'></i>
                                                </a>
                                            `);
            <?php } ?>
                
            updateGrandTotal();
            updateDiscount();
            updateNettTotal();

            $.ajax({
            url: "<?=base_url()?>admin/order/showCustomerOrderHistory/" + customerId,
            dataType : 'html'
            })
        .done(function( response ) {
                        $('#customerOrderHistoryContainer').html(response);
                        $('#loadingDiv_bakgrnd').hide();
                    });

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
            $('.passport_phont_link').html('');

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
            var data = $('#myForm :input').serializeObject();
            data['customer_repeated'] = "no";
            $.ajax({
            data:data,
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
            var selected_box_id = $(deleteHelperClass).find('.selectedBoxes').attr('box_id');
            var selected_box_quantity = $(deleteHelperClass).find('.selectedBoxQuantity').val();

            deletePromoDiscount(selected_box_id, selected_box_quantity);

            $(deleteHelperClass).remove();
            updateGrandTotal();
            var displayPromoCodeBtn = "no"; 

            if ($('#promoIdHidden').val())
            { 
                var promoIdWithAmount = $('#promoIdHidden').val();
                var splitDataGetToIdValue = promoIdWithAmount.split("@#");
                var promo_box_id = splitDataGetToIdValue[2];
                var array = promo_box_id.split(",");
                $('.selectedBoxes').each(function (index, obj)
                {
                    box_id = $(this).attr('box_id');
                    var index = $.inArray(box_id, array);
                    if (index != -1)
                    {
                        displayPromoCodeBtn = "yes";
                    }
                });
            } 
            
            if (displayPromoCodeBtn == "no")
            {    
                var box_id = [];
                $('.selectedBoxes').each(function (index, obj)
                {
                    box_id.push($(this).attr('box_id'));
                });

                if (box_id)
                {  
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
                    });
                } 
            }
            else
            { 
                updateNettTotal();
            }
        })

        initializeAutoComplete();
        initializeStreetAutoComplete();

        $('.counter').counterUp({
            delay: 10,
            time: 10
        });

        $('#codeSelectButton').click(function () {
            $('#loadingDiv_bakgrnd').show();
            codeId = $('#codeIdHidden').val();

            $.ajax({
                data: {code_id: codeId},
                url: "<?= base_url() ?>admin/order/getCodeDetails",
                type: 'POST',
                dataType: 'html'
            })
                    .done(function (response) {
                        if ($('.fakeCodeDetailsClass').length == 0)
                        {
                            lastObj = '#codeDropDownDivContainer';
                        } else
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
        });

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
            if ($('#isRepeatedCustomer').val() == "yes")
            {
                totalQuantity = 0;

                $('#discountRow').remove();

            $('.quantityTextBoxClass').each(function (index, row){
                    totalQuantity += parseInt($(row).val(), 10);
                })


            discount = parseFloat(<?=$PER_BOX_DISCOUNT?>) * totalQuantity;

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
                totalPrice = parseFloat($('#totalPriceContainer').html());
                //            discount = totalPrice * (discount/100);

                totalQuantity = 0;

            $('.quantityTextBoxClass').each(function (index, row){
                    totalQuantity += parseInt($(row).val(), 10);
                })

                discount = discount * totalQuantity;

            if(isNaN(discount) == true)
                {
                    discount = 0.00;
                }

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

//    $('#customerFormSubmitButton').click(function (){
//        console.log($('#myForm :input').serializeObject());
//        console.log('here')
//    })

        function promoCodeButton()
        { 
            //selected Box Ids
            var box_id = [];
            $('.selectedBoxes').each(function (index, obj)
            {
                box_id.push($(this).attr('box_id'));
            });

                
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                data: {box_id: box_id},
                url: "<?= base_url() ?>admin/order/getPromotionBoxes",
                type: 'POST',
                dataType: 'html',
            }).done(function (response) {
           $('#loadingDiv_bakgrnd').hide();
                //update promotion if select another
                if ($("#promoIdHidden").val())
                { 
                    checkDiscountByCollectionDate();
                    updateDiscountByGetAllBoxes();
                }
                 

                if (response != '0')
                {
                    $('.promoCodeButtons').remove();
//                            $('#promotionContainer').html(response);

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



                    $('#selectPromotion').click(function (event) {
                        $('#promotionModal').modal('hide');
                        if ($('#promoIdHidden').val())
                        {
                            checkDiscountByCollectionDate();
                            updateDiscountByGetAllBoxes();
                        }
                    });


                    $('.removePromotionBtn').click(function ()
                    {
                        removePromoCode();
                    });
                }


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
                        $('#loadingDiv_bakgrnd').hide();
                        $('#promoIdHidden').val(response);
                        $(".removePromotionBtn").show();
                            
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
                    $('.selectedBoxes').each(function (index, obj)
                    {
                        box_id = $(this).attr('box_id');
                        box_id_arr.push(box_id);

                        var index = $.inArray(box_id, array);
                        if (index != -1)
                        {
                            discountPayable = 'yes';

                            if ($(this).parents('.form-group').find('.box-label span').length <= 0)
                            {

                                $(this).parents('.form-group').find('.box-label').append("<span style='background-color:green;margin-left:20px;color:white'>Promocode Applied</span>");
                            }
                            quantity = $(this).parents('.form-group').find('.quantityTextBoxClass').val();
                            totalBoxQtyArr.push(quantity);
                        } else
                        {

                            if ($(this).parents('.form-group').find('.box-label span').length > 0) {
                                $(this).parents('.form-group').find('.box-label span').remove();
                            }

                        }
                    });


                    var totalBoxQuantity = 0;
                    for (var i = 0; i < totalBoxQtyArr.length; i++) {
                        totalBoxQuantity += totalBoxQtyArr[i] << 0;
                    }

                    discount = parseFloat(amount * totalBoxQuantity);

                    var grandTotal = $("div").find("#totalPriceContainer").html();
                    if (grandTotal < discount)
                    {
                        discount = "0.00";
                    }


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

                    } else
                    {
                        $('#discountRow').remove();
                        totalDiscount = '<div class="form-group" id="discountRow">\n\
                                                <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">0.00</b></label>\n\
                                                <input type="hidden" name="total_discount" value="0.00">\n\
                                                <div class="col-sm-2 pull-right"><b>Discount (No Discount)</b></div>\n\
                                            </div>';
                        $(totalDiscount).insertAfter('#grandTotalRow');
                    }

                    updateNettTotal();

                }
            });


        }

        function getPromocodeById()
        {
            promotion_id = $("#box_procomode_id").val();
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                data: {promotion_id: promotion_id},
                url: "<?= base_url() ?>admin/order/getAllPromoBoxesByBoxid",
                type: 'POST',
                dataType: 'html',
            })
            .done(function (response) {
           
                $('#promoIdHidden').val(response);
                if (response != '0')
                {
                    $(".removePromotionBtn").show();
                }
$('#loadingDiv_bakgrnd').hide();
            });
        }

        $(document).keypress(function (e)
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
                        } else
                        {
                            collection_date = "00:00:00";
                        }

                        if (collection_date > promotion_expiary_date)
                        {
                            $(".promoCodeButtons").hide();
                            if ($('.form-group').find('.box-label span').length > 0)
                            {
                                $('.form-group').find('.box-label span').remove();
                            }
                            updateDiscount();
                            updateNettTotal();
                        } else
                        {
                            var totalBoxQtyArr = []

                            $('.selectedBoxes').each(function (index, obj)
                            {
                                box_id = $(this).attr('box_id');

                                var index = $.inArray(box_id, array);
                                if (index != -1)
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

                                var grandTotal = $("div").find("#totalPriceContainer").html();
                                if (grandTotal < discount)
                                {
                                    discount = "0.00";
                                }

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
//                                      updateDiscount();
                            updateNettTotal();
                        }
                    }
$('#loadingDiv_bakgrnd').hide();
                });

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
                if (index != -1)
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
            } else
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
                                    $("#promoExpiryModal").css('position', '');

                                    set_promotion_collection_date = promotion_expiary_date.split('-')
                                    set_promotion_collection_date = set_promotion_collection_date[2] + '/' + set_promotion_collection_date[1] + '/' + set_promotion_collection_date[0];

                                    $("input[name='collection_date']").val(set_promotion_collection_date);
                                    promoCodeButton(); 
                                });


                                //Cancel Button
                                $("#promoExpiryCancelBtn").click(function ()
                                {
                                    $("#promoExpiryModal").css('position', '');
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
                                getPromocodeById();  
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

        $('#datepick3').trigger('change');
        $('#manualOrderSpanInfo').hide();
    })
</script>
