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
                                    Receiving Batch
                                <?php
                                if (!empty($receiving_batches))
                                {
                                ?>
                                <Select name="search_receiving_batch_id" id="search_receiving_batch_id" class="form-control searchFormClass">
                                    <option value="">--Select--</option>
                                    <?php
                                    foreach ($receiving_batches as $index => $row)
                                    {
                                        $selected = !empty($receiving_batch_id) && $receiving_batch_id == $row['id'] ? "Selected='Selected'" : '';
                                ?>
                                        <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']." (".$row['status'].")"?></option>
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
                                    Order No
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-pencil-square-o"></i>
                                            </span>
                                            <input type="text" class="searchFormClass input-large form-control" id="mytext" placeholder="Order No." name="search_order_number">
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
    <div class="modal fade" id="receivingBatchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #e63a3a;">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel" style="color: #fff;">Update Order Details for <b class="fake-receiving-batch-name" id="order_number_holder"></b></h4>
          </div>
          <div class="modal-body">

            <div class="row" id="orderDetailsContainer">
            </div>

          </div>
<!--          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="updateOrderDetails">Save</button>
          </div>-->
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
        $('body, input').bind('keydown', 'alt+p', function (){
            $('#phone-text').val('')
        });
        
        extraParams = collectParams();
       
        $('#menuTable').dataTable({
            "bFilter": false,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/receiving_batch/getOrdersDataAtJakartaSide", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": 'customer_name', "sTitle": "Pengirim",'bSortable':false},
                {"mDataProp": 'boxes', "sTitle": "Size","bSortable": false},
                {"mDataProp": 'quantities', "sTitle": "Qty","bSortable": false},
                {"mDataProp": 'locations', "sTitle": "Location(s)","bSortable": false},
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

    $('#clearSearchButton').click(function(){
       window.location.href = ''
    })

    initTables();
    
    $('body').on('click', '.fake-receiving-batch-class', function (){
            $('#loadingDiv_bakgrnd').show();
        
            order_id = $(this).attr('rel');
            order_number = $(this).attr('title');
            
            $('#order_number_holder').html(order_number)
            
            $.ajax({
                data:{order_id:order_id,order_number:order_number},
                url: "<?=base_url()?>admin/receiving_batch/getOrderDetailsByIdJkt",
                cache: false,
                dataType : 'html',
                type : 'post',
            })
            .done(function( response ) {
                $("#orderDetailsContainer").html(response);
        
                $('#loadingDiv_bakgrnd').hide();            
            });
        });
        $('#received_date').datepicker({
            format: "dd/mm/yyyy"
        });
 
});
</script>