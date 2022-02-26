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
            <div class="pull-right" style="width:200px;">

                <select class="form-control searchFormClass pull-left" id="search_shipment_batch_status" style="width:50%;margin-right:10px" name="search_shipment_batch_status">
                    <option value="">--Select--</option>
                    <option selected="selected" value="yes">Active</option>
                    <option value="no">Inactive</option>
                </select>

                <?php
                if ($geo_type !== 'jakarta')
                {
                ?>
                <div class="right-btn-add pull-left">
                    <a href="<?php echo base_url(); ?>admin/masters/addShipmentBatch" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> 
                </div>
                <?php
                }
                ?>
            </div>
        </form>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Shipment Batch List
                        </h3>
                    </div>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin table-bordered " id="menuTable">

                    </table>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkUpdateShipmentBatchStatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Update Shipment Batch Status</h4>
            </div>
            <form id="bulkUpdateShipmentBatchStatusForm" method="post" action="#">
                <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <label>Shipment Batch</label>
                            </div>
                            <div class="col-lg-7">
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
                                } ?>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="escalateOrdersButton">Escalate Orders</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () 
    {
        
        $('#menuTable').on('click', '.bulkUpdateShipmentBatchStatus', function(e)
        {
            e.preventDefault();
            $('#bulkUpdateShipmentBatchStatusModal').modal('show');
        });
        
        $('#escalateOrdersButton').on('click', function(e)
        {
            var shipment_batch_id = $('#search_shipment_batch_id').val();
            $('#escalateOrdersButton').attr('disabled', true);
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                data: {
                    shipment_batch_id : shipment_batch_id
                },
                url: "<?= base_url(); ?>admin/masters/bulkUpdateShipmentBatchStatus",
                success: function(data)
                {
                    var status = data.status;
                    if (status == false)
                    {
                        $('#escalateOrdersButton').attr('disabled', false);
                        alert('Batch not ready.');
                        return false;
                    }
                    else
                    {
                        $('#escalateOrdersButton').attr('disabled', false);
                        $('#bulkUpdateShipmentBatchStatusModal').modal('hide');
                    }
                }
            });
        });
        
        $('#search_shipment_batch_status').change(function(){
            initTables();
        })

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
    
        function initTables()
        {
            extraParams = collectParams();    

            $('#menuTable').dataTable({
                "bFilter": true,
                "bDestroy": true,
                "bLengthChange": true,
                "iDisplayLength": 20,
                "sAjaxSource": "<?php echo base_url(); ?>admin/masters/getShipmentBatchData", //datasource
                "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
                "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
                "bProcessing": true,
                "aaSorting": [[ 0, "desc" ]],
                "aoColumns": [//tentukan kolom pd tabel dan value nya
                    {"mDataProp": "batch_name", "sTitle": "Batch"},
                    {"mDataProp": "booking_confirmation", "sTitle": "Booking Con."},
                    {"mDataProp": "container_type", "sTitle": "Cont.Type"},
                    {"mDataProp": "quantity", "sTitle": "Qty."},
                    {"mDataProp": 'vessel_name', "sTitle": "Vessel"},
                    {"mDataProp": 'voyage_number', "sTitle": "Voyager"},


                    {"mDataProp": 'eta', "sTitle": "ETA"},
                    {"mDataProp": 'ship_onboard', "sTitle": "Ship <br/>Onboard"},
                    {"mDataProp": 'bl_number', "sTitle": "Bl No."},
                    {"mDataProp": 'load_date', "sTitle": "Load Date"},
                    {"mDataProp": 'count', "sTitle": "Count"},
                    <?php
                    if ($geo_type !== 'jakarta')
                    {
                    ?>
                    {"mDataProp": "view_orders", "sTitle": "View<br> Orders", "bSortable": false},
                    {"mDataProp": "update_status", "sTitle": "Update Status", "bSortable": false},
                    {"mDataProp": "operation", "sTitle": "Operation", "bSortable": false},
                    <?php
                    }
                    else
                    {
                    ?>
                    {"mDataProp": "jakarta_operation", "sTitle": "Operation", "bSortable": false},
                    <?php
                    }
                    ?>
    //                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
    //                {"mDataProp": "delete", "sTitle": "Delete", "bSortable": false},
    //                {"mDataProp": "shipment_report", "sTitle": "Report", "bSortable": false}
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
    
        initTables()
    
});
</script>


