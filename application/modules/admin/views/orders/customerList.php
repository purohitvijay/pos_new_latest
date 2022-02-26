<style type="text/css">
.modal-dialog {
    margin: 30px auto;
    width: 80%;
}
.blacklistCustomer{
    color: red;
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

    
    <!-- Modal -->
    <div  class="modal fade" id="orderHistoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

    <div class="page-header">

        <form id="searchForm">
            <fieldset class="form-border">
                <legend class="form-border">Search Form</legend>
                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-2">
                                    Name
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                            </span>
                                            <input name="search_name" placeholder="Customer Name" class="searchFormClass pull-left form-control">
                                    </div>

                                </div>
                                
                                <div class="col-md-2">
                                    Mobile
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-mobile"></i>
                                            </span>
                                            <input name="search_mobile" placeholder="Mobile" class="searchFormClass pull-left form-control">
                                    </div>

                                </div>
                                
                                <div class="col-md-2">
                                    Phone
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                            </span>
                                            <input name="search_phone" placeholder="Phone" class="searchFormClass pull-left form-control">
                                    </div>

                                </div>
                                
                                <div class="col-md-2">
                                    Postal Code
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa glyphicon-google_maps"></i>
                                            </span>
                                            <input name="search_pin" placeholder="Postal Code" class="searchFormClass pull-left form-control">
                                    </div>

                                </div>
                                
                                <div class="col-md-2">
                                    Address
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa glyphicon-building"></i>
                                            </span>
                                            <input name="search_address" placeholder="Address" class="searchFormClass pull-left form-control">
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    Blacklist Customer
                                    <div class="input-group">
                                        <input type="checkbox" name="blacklistCustomer" value="1"  class="form-control searchFormClass" id="blacklistCustomer" style="-webkit-appearance: checkbox; width:15px;">
                                    </div>
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
                            <i class="fa fa-user"></i>
                            Customer Listing
                        </h3>
                        <div class="pull-right">
                            <!--<div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/order/orderBookingForm" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> </div>-->
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
    function initTables()
    {
        extraParams = collectParams();
       
        $('#menuTable').dataTable({  
//            'createdRow': function( row, data, dataIndex ) {
//                $(row).addClass('bill-row');
//            },
//            "fnDrawCallback": function(row, data, dataIndex ) {
//                console.log(data);
//              },
            "bFilter": false,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/order/getCustomerListData", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "name", "sTitle": "Name", "bSortable": true},
                {"mDataProp": "mobile", "sTitle": "Mobile", "bSortable": true},
                {"mDataProp": 'residence_phone', "sTitle": "Phone", "bSortable": true},
                {"mDataProp": 'pin', "sTitle": "Postal Code"},
                {"mDataProp": 'unit', "sTitle": "Unit", "bSortable": true},
                {"mDataProp": 'block', "sTitle": "Block", "bSortable": true},
                {"mDataProp": 'building', "sTitle": "Building", "bSortable": true},
                {"mDataProp": 'street', "sTitle": "Street", "bSortable": true},
                {"mDataProp": "order_history", "sTitle": "Order History", "bSortable": false},
                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
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
            "fnRowCallback": function (row, data) {                
                if (data.blacklist == "Yes") {
                    $(row).addClass('blacklistCustomer');
                }
            }
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
               if ($(tmpObj).attr('type') == 'checkbox')
                {
                    obj.name[index] = $(tmpObj).attr('name');
                    obj.val[index] = $(tmpObj).is(':checked') == true ? 1 : 0;
                }
                else
                {
                    obj.name[index] = $(tmpObj).attr('name');
                    obj.val[index] = $(tmpObj).val();
                }
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
    
    $('body').on('click', '.fake-customer-class', function (){
        customerId = $(this).attr('rel');
        customerName = $(this).attr('title');
        
        $.ajax({
            url: "<?=base_url()?>admin/order/showCustomerOrderHistory/" + customerId,
            dataType : 'html'
        })
        .done(function( response ) {
            $('#customerOrderHistoryContainer').html(response);
            $('#customerHistoryName').html(customerName);
            $('#orderHistoryModal').modal('toggle');
            $('#loadingDiv_bakgrnd').hide();
        });
    })
    
    initTables();
});
</script>