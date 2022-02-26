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
        <div class="pull-right">
            <div class="right-btn-add">
                <?php
                if (empty($receivingBatchesArr))
                {
                ?>
                    <a data-toggle='modal' data-target='#myModal' href="#" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> 
                <?php
                }
                else
                {
                    echo '<div class="alert alert-warning" style="margin-top:20px" role="alert">Seems there is receiving batch opened yet. Can not create more before closure.</div>';
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Receiving Batches List
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

 <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #e63a3a;">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel" style="color: #fff;">Add Receiving Batches <b class="fake-order-number"></b></h4>
          </div>
            <form class="form-horizontal" role="form" id="shipmentBatchesForm">
          <div class="modal-body">

            
                <input type="hidden" name="receiving_batch_id" class="fake-order-id">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-group row">
                            
                            <label for="textfield" class="control-label col-sm-3"><b>Name</b><span class="required">*</span></label>
                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="name" id="receiving_batch_name" class="form-control" required="required">
                                </div>
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label for="textfield" class="control-label col-sm-3"><b>Select Shipment Batches</b><span class="required">*</span></label>
                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <?php
                                if (!empty($shipmentBatchesArr))
                                {
                                    
                                ?>
                                    <select id="shipment_batches" name="shipment_batches_selected[]" multiple class="multiselect" required="required">
                                <?php
                                    $str = '';
                                    foreach ($shipmentBatchesArr as $index => $row)
                                    {              
                                       
                                        $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    echo $str;
                                ?>
                                    </select>
                                <?php
                                }
                                else
                                {
                                    echo "No shipment batches found.";
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>                  
                </div>

            

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveShipmentBatch">Save</button>
          </div>
</form>
        </div>
      </div>
    </div>
 
 <!-- Modal -->
    <div class="modal fade" id="receivingBatchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #e63a3a;">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel" style="color: #fff;">Edit Receiving Batch<b class="fake-receiving-batch-name"></b></h4>
          </div>
          <div class="modal-body">

            <div class="row" id="recevingBatchContainer">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="updateShipmentBatch">Save</button>
          </div>
        </div>
      </div>
    </div>
 
 <!-- Modal -->
    <div class="modal fade" id="pendingOrderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #e63a3a;">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel" style="color: #fff;">Pending Orders<b class="fake-receiving-batch-name"></b></h4>
          </div>
          <div class="modal-body">

            <div class="row" id="pendingOrdersReport">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
 
<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>
<script>
    jQuery(document).ready(function () {
//          TableAdvanced.init();
        $('#menuTable').dataTable({
            "bFilter": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/receiving_batch/getReceivingBatchesData", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "name", "sTitle": "Name"},
                {"mDataProp": "status", "sTitle": "Status"},
                {"mDataProp": "shipment_batches", "sTitle": "Shipment Batches","bSortable": false},
                {"mDataProp": 'count', "sTitle": "Count","bSortable": false},
                {"mDataProp": 'pending_orders', "sTitle": "Pending Orders","bSortable": false},
//                {"mDataProp": "view", "sTitle": "View", "bSortable": false},
                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
                {"mDataProp": "report", "sTitle": "Report", "bSortable": false},
                {"mDataProp": "download", "sTitle": "Download", "bSortable": false},
                
            ],
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true
        });
        
        $(".multiselect").multiselect();
        
         $('#saveShipmentBatch').click(function (e){
            e.preventDefault();
            var receiving_batch = $('#receiving_batch_name').val();
            var shipment_batches = $('#shipment_batches').val();
            
            if(receiving_batch == "")
            {
                alert('Please insert receiving batch name');
            }
            else
            { 
                if(shipment_batches == null)
                {
                    alert('Please select shipment batch');
                }
                else
                {
                $('#loadingDiv_bakgrnd').show();            
                data = $('#shipmentBatchesForm').serialize();
               
                $.ajax({
                    data:data,
                    url: "<?=base_url()?>admin/receiving_batch/saveReceivingBatch",
                    cache: false,
                    dataType : 'json',
                    type : 'post',
                })
                .done(function( response ) { 
                    $('#loadingDiv_bakgrnd').hide();
                    var status = response.status;      
                    var msg = response.msg;
                    if(status == "error")
                    {
                        alert(msg);
                    }
                    else
                    {
                    $('#myModal').modal('toggle');
                    $('#msgContainer').removeClass('hide')

                    window.location.href = "<?=base_url()?>admin/receiving_batch/index?haveSideBar=0";
                  }
                });
            }
            }
        });
        
        $('#updateShipmentBatch').click(function (e){
            e.preventDefault();
            
            $("#shipment_batches_update > option").each(function() {
                $(this).removeAttr('disabled');
            });
            
            var receiving_batch = $('#receiving_batch_name_update').val();
            var shipment_batches = $('#shipment_batches_update').val();
            
            if(receiving_batch == "")
            {
                alert('Please insert receiving batch name');
            }
            else
            { 
                if(shipment_batches == null)
                {
                    alert('Please select shipment batch');
                }
                else
                {
                $('#loadingDiv_bakgrnd').show();            
                data = $('#shipmentBatchesUpdateForm').serialize();
               
                $.ajax({
                    data:data,
                    url: "<?=base_url()?>admin/receiving_batch/saveReceivingBatch",
                    cache: false,
                    dataType : 'json',
                    type : 'post',
                })
                .done(function( response ) { 
                    $('#loadingDiv_bakgrnd').hide();
                    var status = response.status;      
                    var msg = response.msg;
                    if(status == "error")
                    {
                        alert(msg);
                    }
                    else
                    {
                    $('#myModal').modal('toggle');
                    $('#msgContainer').removeClass('hide')

                    window.location.href = "<?=base_url()?>admin/receiving_batch/index?haveSideBar=0";
                  }
                });
            }
            }
        });
        
        $('body').on('click', '.fake-receiving-batch-class', function (){
            $('#loadingDiv_bakgrnd').show();
        
            receiving_batch_id = $(this).attr('rel');
            
            $.ajax({
                data:{receiving_batch_id:receiving_batch_id},
                url: "<?=base_url()?>admin/receiving_batch/receivingBatchEditForm",
                cache: false,
                dataType : 'html',
                type : 'post',
            })
            .done(function( response ) {
                $("#recevingBatchContainer").html(response);
        
                $('#loadingDiv_bakgrnd').hide();            
            });
        });
        //get pending reports details
        $('body').on('click', '.fake-pending-orders-class', function (){
            $('#loadingDiv_bakgrnd').show();
        
            receiving_batch_id = $(this).attr('rel');
            
            $.ajax({
                data:{receiving_batch_id:receiving_batch_id},
                url: "<?=base_url()?>admin/receiving_batch/getReceivingBatchPendingOrders",
                cache: false,
                dataType : 'html',
                type : 'post',
            })
            .done(function( response ) {
                $("#pendingOrdersReport").html(response);
        
                $('#loadingDiv_bakgrnd').hide();            
            });
        })
        
    });
</script>