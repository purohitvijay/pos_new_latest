<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<style type="text/css">
.modal-dialog {
    margin: 30px auto;
    width: 80%;
}
</style>


<div id="dsmain" class="page-content main_container_padding">
    <div class="container-fluid">
            <div class="page-header">
                    <div class="pull-left">
                            <h3><i class="fa fa-edit"></i>Book Order</h3>
                    </div>
            </div>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Quick LookUp Form</h4>
              </div>
              <div class="modal-body">

                <form class="form-horizontal" role="form" id="searchForm">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-group row">
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
                                <div class="col-md-4">

                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-mobile"></i>
                                            </span>
                                            <input type="text"  name="mobile" placeholder="Mobile" class='searchFormClass input-large form-control'>
                                    </div>

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


        <div class="row">
                <div class="col-sm-12">
                        <div class="box box-bordered" style="border-top:1px solid #e7e7e7">

                                <div class="box-content nopadding">
                                        <form action="#" name="myForm" id="myForm" method="POST" class='form-horizontal form-bordered'>
                                                <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">Order Date</label>
                                                        <div class="col-sm-4">
                                                            <div class='input-group date' id='datetimepicker1'>
                                                                <input type="text" name="order_date" id="textfield" class="form-control datepick1" value="<?=date('Y-m-d')?>">
                                                            </div>
                                                            
                                                        </div>



                                                        <label for="textfield" class="control-label col-sm-2">Customer Name
                                                            <a href="#" class="pull-right" data-toggle="modal" data-target="#myModal" id="myModal"><i class="glyphicon-search"></i></a>
                                                            <a href="#" title="Clear Fields" style="padding-right:4px" class="pull-right" id="clearFields"><i class="glyphicon-minus-sign"></i></a>
                                                            <a href="<?=base_url()?>admin/order/showCustomerOrderHistory/" target="_new" title="History" style="padding-right:4px" class="pull-right hide" id="customerHistory"><i class="glyphicon-history"></i></a>
                                                        </label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                                        <input type="text" placeholder="Customer Name" name="customer_name" id='customer' class='form-control'>
                                                                        <input type="hidden"  name='customer_id' id='customer_id'>
                                                                        <input type="hidden"  name='repeated_customer' id='repeated_customer'>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">Email</label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <span class="input-group-addon">@</span>
                                                                        <input type="text" placeholder="Email" id="email" class='form-control' name="email">
                                                                </div>
                                                        </div>
                                                        <label for="textfield" class="control-label col-sm-2">Mobile / Residence</label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                                                                        <input type="text" placeholder="Mobile" id="mobile" class='form-control' name="mobile">
                                                                        
                                                                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                                        <input type="text" placeholder="Phone" id="phone" class='form-control' name="phone">
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="form-group" id="codeDropDownDivContainer">
                                                        <label for="textfield" class="control-label col-sm-2">Code</label>
                                                        <div class="col-sm-3">
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
                                                                <div class="input-group pull-left">
                                                                    <input name="code_id" id="codeIdSelect"  class="codeTextBoxClass pull-left form-control" style="width:171px;margin-left:10px">
                                                                    <input name="code_id_hidden" id="codeIdHidden" type="hidden">
                                                                </div>
                                                        </div>
                                                        
                                                        <div class="col-sm-2 text_height" id="codeDescriptionText">&nbsp;</div>
                                                            
                                                        <div  style="padding-left:3px"  class="col-sm-1 text_height">
                                                            <button class="btn-primary btn hide" id="codeSelectButton" type="button">Select Code</button>
                                                        </div>
                                                        <label for="textfield" class="control-label col-sm-1">Agent</label>
                                                        <div class="col-sm-3">
                                                                <div class="input-group">
                                                                    <?php
                                                                    if (!empty($agents))
                                                                    {
                                                                    ?>
                                                                        <Select name="agent_id" id="agentIdSelect"  class="form-control">
                                                                            <option value="">--Select--</option>
                                                                    <?php
                                                                            foreach ($agents as $index => $row)
                                                                            {
                                                                    ?>
                                                                                <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                                                    <?php
                                                                            }
                                                                    ?>
                                                                        </Select>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="form-group">
                                                        <label for="delivery_date" class="control-label col-sm-2">
                                                            Delivery Date
                                                        </label>
                                                        <div class="col-sm-4">
                                                            <div class='input-group date'>
                                                                <input type="text" name="delivery_date" id="delivery_date" class="form-control datepick2" value='<?=date('Y-m-d')?>'>
                                                            </div>
                                                            
                                                        </div>


                                                        <label for="textfield" class="control-label col-sm-2">Collection Date</label>
                                                        <div class="col-sm-4">
                                                            <div class='input-group date' id='datetimepicker1'>
                                                                <input type="text" name="collection_date" id="textfield" class="form-control datepick3" value='<?=date('Y-m-d')?>'>
                                                            </div>                                                            
                                                        </div>
                                                </div>
                                            
                                                <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">
                                                            Postal Code
                                                            <a href="#" class="pull-right"  id="pinLookUpLink"><i class="glyphicon-search"></i></a>
                                                            <a href="#" title="Clear Fields" style="padding-right:4px" class="pull-right" id="clearAddressFields"><i class="glyphicon-minus-sign"></i></a>
                                                        </label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <input type="text" placeholder="Postal Code" class='form-control' id="pinCodeText" name="pin">
                                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                                                <i class="fa fa-location-arrow"></i>
                                                                        </span>
                                                                </div>
                                                        </div>


                                                        <label for="textfield" class="control-label col-sm-2">Building/Estate</label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <input type="text" id="buildingTextBox" placeholder="Building/Estate" class='form-control' name="building">
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
                                                                        <input type="text" placeholder="Street" class='form-control' id="streetTextBox" name="street">
                                                                        <span id="pinCodeLocation" class="input-group-addon">
                                                                                <i class="fa fa-location-arrow"></i>
                                                                        </span>
                                                                </div>
                                                        </div>

                                                        <label for="textfield" class="control-label col-sm-2">Comments</label>
                                                        <div class="col-sm-4">
                                                                <div class="input-group">
                                                                        <input type="text" id="commenttTextBox" placeholder="Comments" class='form-control input-large' name="unit" style="width:389px    ">

                                                                </div>
                                                        </div>
                                                        
                                                </div>

                                                <div class="form-group">
                                                        <label for="textfield" class="control-label col-sm-2">Unit : Block</label>
                                                        <div class="col-sm-4">
                                                            
                                                            <div class="input-group">
                                                                    <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                                    <input type="text" id="unitTextBox" placeholder="Unit" class='form-control' name="unit">

                                                                    <span class="input-group-addon"><i class="fa fa-location-arrow"></i></span>
                                                                    <input type="text" id="blockTextBox" placeholder="Block" class='form-control' name="block">
                                                            </div>
                                                        </div>
                                                        
                                                </div>

                                                <div class="form-actions">
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                        <button type="button" class="btn">Cancel</button>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>


    </div>
</div>

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Generating Bar Code...</span>
</div>



<script type="text/javascript">
var location_id = box_id = 0;
var code_auto_suggest_box_id = 0
    
$(document).ready(function (){
    $('#pinLookUpLink').click(function(){
        var pincode =  $('#pinCodeText').val();
        
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
                url: "<?=base_url()?>admin/order/getAddressByPinCode",
                cache: false,
                dataType : 'json',
                type : 'post',
            })
            .done(function( response ) {
                $('#blockTextBox').val(response.street);
                $('#streetTextBox').val(response.building);
                $('#loadingDiv_bakgrnd').hide();            
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
        initializeAutoComplete()
    })
    
    function initializeAutoComplete() {
        $( ".codeTextBoxClass" ).autocomplete({
            source: "<?=base_url()?>admin/order/fetchCodeByLocationBox?box_id=" + code_auto_suggest_box_id ,
            minLength: 1,
            open : function( event, ui ) {
          },
          select: function( event, ui ) {
            $(".codeTextBoxClass" ).val(ui.item.code)
            $("#codeIdHidden" ).val(ui.item.id);
            $('#codeDescriptionText').html(ui.item.description);
            $('#codeSelectButton').removeClass('hide');

            return false;
          }
        }).autocomplete("instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<a>"+ item.code + " </a>" )
            .appendTo( ul );
        };
    }

    $('body').on('change', '.quantityTextBoxClass', function (){
        quantity = $(this).val();
        
        parentObj = $(this).parents('.form-group');
        $(parentObj).find('.priceTextBoxClass').val(quantity * $(parentObj).find('.priceHiddenClass').val());
        
        $('.priceTextBoxClass').trigger('change');
    })

    $('body').on('change', '.boxLocationFakeClass', function (){
        
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
        
        $('.priceTextBoxClass').trigger('change');
    })
    
    $('body').on('keyup', '.priceHiddenClass', function (){
        price = parseInt($(this).val(), 10);
        console.log('here we go', price)
        console.log($(parentObj).find('.quantityTextBoxClass').val())
        parentObj = $(this).parents('.form-group');
        val = price * parseInt($(parentObj).find('.quantityTextBoxClass').val(), 10);
        if (val >= 0)
        $(parentObj).find('.priceTextBoxClass').val(val);
    else
        $(parentObj).find('.priceTextBoxClass').val(0);
        
        $('.priceTextBoxClass').trigger('change');
    })
    
    $('#mytext').change(function (event){
        console.log('hi')
    })
    
    $('.searchFormClass').keyup(function (event){
        data = $('#searchForm').serializeObject();
        console.log(data);
        $.ajax({
            data:data,
            url: "<?=base_url()?>admin/order/searchUser",
            type:'POST'
        })
        .done(function( response ) {
            $('#searchResultContainer').html(response)
        });
    })
    
    $('#myForm').submit(function (event){
        event.preventDefault();
        
        data = $('form').serializeObject();
        
        $('#loadingDiv_bakgrnd').show();
        
        $.ajax({
                data:data,
                url: "<?=base_url()?>admin/order/saveOrder",
                type:'POST',
                dataType : 'JSON'
        })
        .done(function( response ) {
            $('#img_load_chart').html('Generating Bar Code...');
            console.log('here os')
            console.log(response.raw_order_number)
            $.ajax({
                data:{raw_order_number : response.raw_order_number},
                url: "<?=base_url()?>admin/order/generateBarCode",
                type: "POST"
            })
            .done(function( response ) {
                $('#loadingDiv_bakgrnd').hide();
                window.location.href='<?=base_url()?>admin/order/form?haveSideBar=0'
            });
        });
        
        return false;
        
        raw_order_number = $('#raw_order_number').val();
        //order_number = $();
    })
    
    $('#selectCustomerButton').click(function (event){
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
        console.log(repeatedCustomerValue + ' is the value')
        
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
        $('#repeated_customer').val(repeatedCustomerValue);
        
        $('#customerHistory').attr('href', $('#customerHistory').attr('href') + customerId);
        $('#customerHistory').removeClass('hide');
                
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
        $('#customer_id').val('');
        $('#repeated_customer').val('');
        
        $('#customerHistory').addClass('hide');
    })
    
    $('#clearAddressFields').click(function (event){
        $('#buildingTextBox').val('');
        $('#streetTextBox').val('');
        $('#pinCodeText').focus();
    })
    
//    $('#selectCustomerButton').on('click', 'input', function (event){
//        var customerId = $('input[name=selectCustomerRadio]:checked', '#searchForm').val()
//        console.log(customerId)
//    })
    
    $('.selectCustomerRadio').on('click', 'input', function (event){
        console.log($(this))
        $('#searchedCustomerId').val($(this).val())
    })
    
    $('input[name=selectCustomerRadio]').click(function (event){
        console.log($(this))
        $('#searchedCustomerId').val($(this).val())
    })
    
    $('.selectCustomerRadio').click(function (event){
        console.log($(this))
        $('#searchedCustomerId').val($(this).val())
    })
    
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
    
    $('body').on('click', '.deleteRowClass', function (){
        parentObj = $(this).parents('.form-group');
        $(parentObj).fadeOut('fast', function(){ $(parentObj).remove(); });
    })
    
    $('body').on('change', '.priceTextBoxClass', function (){
        console.log('here')
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
        $(deleteHelperClass).remove();
        updateGrandTotal();
        updateDiscount();
        updateNettTotal();
    })
    
    initializeAutoComplete();
    
     $('.counter').counterUp({
        delay: 10,
        time: 10
    });
    
    $('#codeSelectButton').click(function (){
        codeId = $('#codeIdHidden').val();
        
        $.ajax({
            data:{code_id:codeId},
            url: "<?=base_url()?>admin/order/getCodeDetails",
            type:'POST',
            dataType : 'html'
        })
        .done(function( response ) {
            console.log($('#codeDropDownDivContainer'))
            
            if ($('.fakeCodeDetailsClass').length == 0)
            {
                lastObj = '#codeDropDownDivContainer';
            }
            else
            {
                lastObj = '.fakeCodeDetailsClass:last';
            }
            
            
            $(response).insertAfter(lastObj);
            
            updateGrandTotal();
            updateDiscount();
            updateNettTotal();
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
                    <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalPriceContainer">'+totalPrice+'</b></label>\n\
                    <input type="hidden" name="total_price" value="'+totalPrice + '">\n\
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
            
            discount = parseFloat(<?=$PER_BOX_DISCOUNT?>) * totalQuantity;

            totalRow = '<div class="form-group" id="discountRow">\n\
                        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">'+discount+'</b></label>\n\
                        <input type="hidden" name="total_discount" value="'+discount + '">\n\
                        <div class="col-sm-2 pull-right"><b>Discount (Repeated Customer)</b></div>\n\
                        <input type="hidden" name="discount_type" value="repeated_customer">\n\
                    </div>';
            $(totalRow).insertAfter('#grandTotalRow');
        }
        else if ($('#agentIdSelect').val() > 0)
        {
            

            $('#discountRow').remove();
            
            discount = parseFloat(<?=$AGENT_DISCOUNT?>);
            totalRow = '<div class="form-group" id="discountRow">\n\
                        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer">'+discount+'</b></label>\n\
                        <input type="hidden" name="total_discount" value="'+discount + '">\n\
                        <div class="col-sm-2 pull-right"><b>Discount (Agent Booking)</b></div>\n\
                        <input type="hidden" name="discount_type" value="agent">\n\
                    </div>';
            $(totalRow).insertAfter('#grandTotalRow');
        }
        else
        {
            $('#discountRow').remove();
        }
    }

    function updateNettTotal()
    {
        $('#nettTotalRow').remove();
        totalPrice = parseFloat($('#totalPriceContainer').html());
        totalDiscount = parseFloat($('#totalDiscountContainer').html());
        
        nettTotal = totalPrice - totalDiscount;
        
        nettTotalRow = '<div class="form-group" id="nettTotalRow">\n\
                        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="nettTotalContainer">'+nettTotal+'</b></label>\n\
                        <input type="hidden" name="nett_total" value="'+nettTotal + '">\n\
                        <div class="col-sm-2 pull-right"><b>Nett Total</b></div>\n\
                    </div>';
        $(nettTotalRow).insertAfter('#discountRow');
    }
    
    $('#agentIdSelect').change(function (){
        updateGrandTotal();
        updateDiscount();
        updateNettTotal();
    })
    
    $('.datepick1').datepicker()
})
</script>
