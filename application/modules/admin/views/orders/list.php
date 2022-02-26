<style type="text/css">
    #ui-id-1
    {
        overflow:auto;
        height:300px;
    }
</style>
<div class="container-fluid">
    
    <?php
    if (!empty($message))
    {
    ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><?=$message?></div>
    <?php
    }
    ?>
    
    <div class="page-header">

        <form id="searchForm">
            <input class="searchFormClass" type="hidden" name="search_customer_id" id="customerIdHidden">
            <fieldset class="form-border">
                <legend class="form-border">Search Form</legend>
                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    Phone &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><b>Alt+p reset</b></small>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa glyphicon-phone"></i>
                                            </span>
                                            <input type="text" class="searchFormClass input-large form-control mask_singapore_phone" id="phone-text" placeholder="Phone" name="search_phone">
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    Order No
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-pencil-square-o"></i>
                                            </span>
                                            <input type="text" class="searchFormClass input-large form-control" id="mytext" placeholder="Order No." name="search_order_number">
                                    </div>

                                </div>

                                <div class="col-md-2">
                                    Current Status
                                    <?php
                                    if (!empty($statutes))
                                    {
                                    ?>
                                        <Select  style="margin-bottom:2px" name="search_current_status" class="pull-left form-control searchFormClass">
                                            <option value="">--Select--</option>
                                    <?php
                                            foreach ($statutes as $index => $row)
                                            {
                                    ?>
                                                <option value="<?=$index?>"><?=$row['display_text']?></option>
                                    <?php
                                            }
                                    ?>
                                        </Select>

                                    <?php
                                    }
                                    ?>
                                </div>

                                <div class="col-md-2">
                                    Delivery Date
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="searchFormClass datepicker input-large form-control" id="search_delivery_date" placeholder="Delivery Date" name="search_delivery_date" value="">
                                    </div>

                                </div>
                                
                                <div class="col-md-2">
                                    Collection Date
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="searchFormClass datepicker input-large form-control" id="search_collection_date" placeholder="Collection Date" name="search_collection_date" value="">
                                    </div>

                                </div>
                                 
                                
                                </div>
                            
                            <div class="form-group row">
                                
                                <div class="col-md-2">
                                    Shipment Batch
                                <?php
                                if (!empty($shipment_batches))
                                {
                                ?>
                                <Select name="search_shipment_batch_id" id="search_shipment_batch_id" class="form-control searchFormClass">
                                    <option value="">--Select--</option>
                                    <?php
                                    foreach ($shipment_batches as $index => $row)
                                    {
                                        $selected = !empty($shipment_batch_id) && $shipment_batch_id == $row['id'] ? "Selected='Selected'" : '';
                                ?>
                                        <option <?=$selected?> value="<?=$row['id']?>"><?=$row['batch_name']?></option>
                                <?php
                                    }
                                    ?>
                                </Select>
                                <?php
                                }
                                else
                                {
                                    echo '<br><b>No Active Batch Found.</b>';
                                }
                                ?>
                                </div>
                                <div class="col-md-2">
                                    Customer
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                            </span>
                                            <input name="customer" id="codeIdSelect" placeholder="Customer Name" class="customerTextBoxClass pull-left form-control">
                                    </div>

                                </div>
                                
                                <div class="col-md-2">
                                    Order Date From
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="searchFormClass datepicker input-large form-control" id="search_order_date_from" placeholder="Order Date From" name="search_order_date_from" value="">
                                    </div>

                                </div>
                                 <div class="col-md-2">
                                    Order Date To
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="searchFormClass datepicker input-large form-control" id="search_order_date_to" placeholder="Order Date To" name="search_order_date_to" value="">
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    &nbsp;
                                    <div class="input-group">                                        
                                        <button class="btn-primary btn" id="clearSearchButton" type="button">Reset</button>
                                        <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Order List
                        </h3>
                        <div class="pull-right">
                            <div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/order/orderBookingForm" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> </div>
                        </div>
                    </div>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin table-bordered" id="menuTable">

                    </table>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>


<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


<script>
jQuery(document).ready(function () {
    
    $('#menuTable').on("click",".passport_img_show_model_link", function (){

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
    
    function initTables()
    {
        $('body, input').bind('keydown', 'alt+p', function (){
            $('#phone-text').val('')
        });
        
        extraParams = collectParams();
       
        $('#menuTable').dataTable({
            "bFilter": false,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/order/getOrderData", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "dates", "sTitle": "Dates","bSortable": false},
                {"mDataProp": 'customer_name', "sTitle": "Customer Name"},
                {"mDataProp": 'statuses', "sTitle": "Statuses","bSortable": false},
                {"mDataProp": 'eta_jakarta', "sTitle": "Eta Jkt","bSortable": false},
                {"mDataProp": 'boxes', "sTitle": "Box(es)","bSortable": false},
                {"mDataProp": 'quantities', "sTitle": "Qty","bSortable": false},
                {"mDataProp": 'locations', "sTitle": "Location(s)","bSortable": false},
                {"mDataProp": 'kabupatens', "sTitle": "Kabupaten(s)","bSortable": false},
                {"mDataProp": 'address', "sTitle": "Address","bSortable": false},
                {"mDataProp": 'batch', "sTitle": "SB","bSortable": false},
                {"mDataProp": 'ship_onboard', "sTitle": "SOB","bSortable": false},
                {"mDataProp": 'deposit_collected', "sTitle": "Deposit","bSortable": false},
                {"mDataProp": 'balance', "sTitle": "Balance $","bSortable": false},
                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
                <?php
                if ($can_edit_status == true)
                {
                ?>
                {"mDataProp": "edit_status", "sTitle": "Edit Status", "bSortable": false}
                <?php
                }
                ?>
            ],
            "fnServerParams": function ( aoData ) {
                if (typeof extraParams !== 'undefined')
                {
                    $(extraParams.name).each(function(index, varName){
                        aoData.push({"name":varName, "value" :extraParams.val[index]});
                    })
                }
            },
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true,
        });
    }
    
    function collectParams()
    {
       var obj = ['name', 'val'];
       obj.name = new Array();
       obj.val = new Array();

       $('#searchForm *').each(function(index, tmpObj){
           if($(tmpObj).hasClass('searchFormClass'))
           {
               obj.name[index] = $(tmpObj).attr('name');
               obj.val[index] = $(tmpObj).val();
           }
       });
       
       return obj;
    }

    $('#searchForm').submit(function (event){
       event.preventDefault();
       
       initTables();
    })

    $('#clearSearchButton').click(function(){
       window.location.href = ''
    })

    $('#search_delivery_date').datepicker({
        dateFormat: "dd/mm/yy"
    })

    $('#search_collection_date').datepicker({
        dateFormat: "dd/mm/yy"
    })

    $("#search_order_date_from").datepicker
    ({
        dateFormat:'dd/mm/yy',
        onSelect: function(selected) 
        {
            $("#search_order_date_to").datepicker("option", "minDate", selected)
        }
    });
    
    $('#search_order_date_to').datepicker
    ({
        dateFormat:'dd/mm/yy',
        onSelect: function(selected) 
        {
            $("#search_order_date_from").datepicker("option", "maxDate", selected)
        }
    });   
    

    $( ".customerTextBoxClass" ).autocomplete({
            source: "<?=base_url()?>admin/order/fetchCustomerAutoSuggestion",
            minLength: 3,
            open : function( event, ui ) {
        },
        search  : function(){$('#loadingDiv_bakgrnd').show();},
        open    : function(){$('#loadingDiv_bakgrnd').hide();},
        select: function( event, ui ) {
            $(".customerTextBoxClass" ).val(ui.item.name)
            $("#customerIdHidden" ).val(ui.item.id);

            return false;
        }
    }).autocomplete("instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a>"+ item.name +" "+ item.block+ " " + item.unit+ " " + item.street+  " </a>" )
            .appendTo( ul );
    };
//    
//    if ($('.mask_singapore_phone').length > 0) {
//        $(".mask_singapore_phone").mask("(999) 999-9999");
//    }

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