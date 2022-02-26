<div class="container-fluid">

    <div class="page-header">
        
        <form id="searchForm">
            <input class="searchFormClass" type="hidden" name="search_customer_id" id="customerIdHidden">
            <fieldset class="form-border">
                <legend class="form-border">Search Form</legend>
                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-12">
                            
                            
                            <div class="form-group row">
                                
                                <div class="col-md-2">
                                    Shipment Batch
                                    <?php
                                    if (!empty($shipmentBatchesArr))
                                    { ?>
                                        <select required name="search_shipment_batch_id" id="search_shipment_batch_id" class="form-control searchFormClass">
                                            <option value="">--Select--</option>
                                            <?php
                                            foreach($shipmentBatchesArr as $idx => $shipmentRecord)
                                            { ?>
                                                <option value='<?= $shipmentRecord['shipment_id']?>'><?= $shipmentRecord['batch_name']?></option>;
                                            <?php
                                            } ?>
                                        </select>
                                    <?php
                                    }
                                    else
                                    {
                                        echo '<br><b>No Shipment Batch Found.</b>';
                                    } ?>
                                </div>  
                                
                                <div class="col-md-2" style="margin-left:15px;margin-top:25px;">
                                    Photo
                                    <input type="checkbox" name="is_available" value="1"  class="form-control searchFormClass" id="is_available" style="margin-top:-25px; margin-left:60px; width:20px;">

                                </div>

                                <div class="col-md-5">
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
                            Photo
                        </h3>                        
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
            "bFilter": false,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/report/getPhotoReportsData", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "delivery_date", "sTitle": "Date delivered at Jkt"},
                {"mDataProp": "image_status", "sTitle": "Status","bSortable": false},
                {"mDataProp": 'date_uploaded', "sTitle": "Date uploaded"},
                {"mDataProp": 'date_uploaded_to_date_delivered', "sTitle": "Date uploaded – Date delivered"},
            ],
            "fnServerParams": function ( aoData ) {
                if (typeof(extraParams) != 'undefined')
                {

                    $(extraParams.name).each(function (index, varName) {
                        if (typeof(varName) != 'undefined')
                        {
                            aoData.push({"name": varName, "value": extraParams.val[index]});
                        }
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
            if ($(tmpObj).hasClass('searchFormClass'))
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
       $('#mainTableHolderDiv').removeClass('hide')
    })

    $('#clearSearchButton').click(function(){
       window.location.href = ''
    })

    
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        jsonText = $.parseJSON(xhr.responseText)
        $('#totalCommissionHolder').html( jsonText.totalCommissionAmount ).removeClass('hide')
      });
});
</script>