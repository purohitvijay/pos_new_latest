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
                                            <input type="text" class="searchFormClass datepicker input-large form-control" id="search_collection_date" placeholder="Delivery Date" name="search_collection_date" value="">
                                    </div>

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
                            Stationery Printing
                        </h3>
                        <div class="pull-right">
                            <button class="btn" id="printFormButton" type="button" value="Forms"><i class='fa fa-paperclip'></i></button>
                            <button class="btn" id="printLabelButton" type="button" value="Labels"><i class='fa fa-qrcode'></i></button>
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

<form class="hidden" action="<?php echo base_url(); ?>admin/report/getBatchPrintReport" method="post" target="_new" id="hiddenSubmissionForm">
    <input type="hidden" name="type"  id="reportType">
    <input type="hidden" name="order_ids" id="orderIds">
</form>

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


<script>
jQuery(document).ready(function () {
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
            "sAjaxSource": "<?php echo base_url(); ?>admin/order/getBatchPrintData", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "order_date", "sTitle": "Order Date"},
                {"mDataProp": "delivery_date", "sTitle": "Delivery Date"},
                {"mDataProp": "collection_date", "sTitle": "Collection Date"},
                {"mDataProp": 'grand_total', "sTitle": "Grand Total"},
                {"mDataProp": 'discount', "sTitle": "Discount"},
                {"mDataProp": 'nett_total', "sTitle": "Nett Total"},
                {"mDataProp": "operations", "sTitle": "Operations", "bSortable": false},
                {"mDataProp": "checkbox", "sTitle": "<input id='checkUncheckAll' type='checkbox'>", "bSortable": false}
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
            "bSort": true
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

    function generateReport(order_ids, report_type)
    {
        $('#reportType').val(report_type);
        $('#orderIds').val(order_ids);
        $('#hiddenSubmissionForm').submit();
    }
    
    $('#printFormButton, #printLabelButton').click(function (event){
        checkBoxObjs = $('.fake-checkall-checkbox:checked');
        
        reportType = $(this).attr('id') == 'printLabelButton' ? 'labels' : 'forms';
        
        if (checkBoxObjs.length == 0)
        {
            alert ('Please select at least one order to proceed.');
        }
        else
        {
            order_ids_arr = new Array();
            $(checkBoxObjs).each(function (index, obj) {
                order_ids_arr.push($(obj).val());
            })
            
            generateReport(order_ids_arr.join(','), reportType)
        }
    })
    
    $('body').on('click', '#checkUncheckAll', function (){
        $('input:checkbox').each(function(){
            $(this).attr('checked', $('#checkUncheckAll').is(':checked'));
        }) 
    })
    
    $('body').on('click', '.forms-fake-class', function (){
        generateReport($(this).attr('rel'), 'forms')
    })
    
    $('body').on('click', '.labels-fake-class', function (){
        generateReport($(this).attr('rel'), 'labels')
    })

    $('#printFormButton').click(function (event){
       
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

    $( ".customerTextBoxClass" ).autocomplete({
            source: "<?=base_url()?>admin/order/fetchCustomerAutoSuggestion",
            minLength: 4,
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
            .append( "<a>"+ item.name+ " </a>" )
            .appendTo( ul );
    };
//    
//    if ($('.mask_singapore_phone').length > 0) {
//        $(".mask_singapore_phone").mask("(999) 999-9999");
//    }
    
    initTables();
});
</script>