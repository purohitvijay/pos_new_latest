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
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: mistyrose">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Follow up call details for <b class="fake-order-number"></b></h4>
          </div>
          <div class="modal-body">

            <form class="form-horizontal" role="form" id="followUpForm">
                <input type="hidden" name="order_id" class="fake-order-id">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="control-label col-sm-3"><b>Customer Name</b></label>
                            <div class="col-sm-3"><label class="control-label fake-customer-name"></label></div>
                            
                            
                            <label class="control-label col-sm-3"><b>Mobile / Phone</b></label>
                            <div class="col-sm-3"><label class="control-label fake-customer-mobile-phone"></label></div>
                            
                            <br/><br/><br/>
                            
                            <label for="textfield" class="control-label col-sm-3">Call Back Date</label>
                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="followupcall_date" id="callBackDate" class="form-control datepicker" value='<?=date('d/m/Y');?>'>
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <div class="input-group clockpicker">
                                    <input type="text" id="time" name="followupcall_time" class="form-control" value="">
                                    <span class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </span>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="textfield" class="control-label col-sm-3">Comments</label>
                            <div class="col-md-9">
                                <div class="input-group clockpicker">
                                    <textarea name="comments" style="width:418px;height:83px" id="order_followup_comments" class='form-control' placeholder="Comments"></textarea>
                                </div>
                                <?php
                                if(!empty($order_followup_comments))
                                {
                                    ?>
                                    <div class="box box-color box-bordered">
                                        <div class="box-title" style="padding:0px!important;">
                                                <h3>
                                                    <i class="fa fa-comment"></i>
                                                    Comment Tags
                                                </h3>
                                                <div class="actions">                                                   
                                                    <a class="btn btn-mini content-slideUp" href="#">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="box-content" >
                                                <?php
                                    foreach($order_followup_comments as $idx => $val)
                                    {
                                        echo "<span class='label label-danger order_comments'>".$val['comments']."</span>&nbsp;&nbsp;";
                                    }
                                    ?>
                                            </div>
                                    </div>
                                <?php
                                }
                                ?>
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>

            

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveCallBackInfo">Save</button>
          </div>
        </div>
      </div>
    </div>
        
    
    <!-- Modal -->
    <div class="modal fade" id="collectionDateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="background-color: mediumaquamarine">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" >Update collection date details for <b class="fake-order-number"></b></h4>
          </div>
          <div class="modal-body">

            <form class="form-horizontal" role="form" id="collectionForm">
                <input type="hidden" name="order_id" class="fake-order-id">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="control-label col-sm-3"><b>Customer Name</b></label>
                            <div class="col-sm-3"><label class="control-label fake-customer-name"></label></div>
                            
                            
                            <label class="control-label col-sm-3"><b>Mobile / Phone</b></label>
                            <div class="col-sm-3"><label class="control-label fake-customer-mobile-phone"></label></div>
                            
                            <br/><br/><br/>
                            
                            <label for="textfield" class="control-label col-sm-3">Collection Date / Time</label>
                            
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="collection_date" id="collectionDate" class="form-control datepicker" value='<?=date('d/m/Y');?>'>
                                    <span class="input-group-addon">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-5">
                                <div class="input-group clockpicker">
                                    <input type="text" id="collectionTime" name="collection_time" class="form-control" value="">
                                    <span class="input-group-addon">
                                        <span class="fa fa-clock-o"></span>
                                    </span>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!--
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="textfield" class="control-label col-sm-3">Shipment Batch</label>
                            <div class="col-md-9">
                                
                                <?php
                                if (!empty($shipment_batches))
                                {
                                ?>
                                <Select name="shipment_batch_id" id="shipment_batch_id" class="form-control">
                                    <?php
                                    foreach ($shipment_batches as $index => $row)
                                    {
                                ?>
                                        <option value="<?=$row['id']?>"><?=$row['batch_name']?></option>
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
                    -->
                    
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="textfield" class="control-label col-sm-3">Comments</label>
                            <div class="col-md-9">
                                <div class="input-group clockpicker">
                                    <textarea name="comments" style="width:418px;height:83px"  class='form-control' id="order_followup_comments" placeholder="Comments"></textarea>
                                </div>
                                <?php
                                if(!empty($order_followup_comments))
                                {
                                    ?>
                                    <div class="box box-color box-bordered">
                                        <div class="box-title" style="padding:0px!important;">
                                                <h3>
                                                    <i class="fa fa-comment"></i>
                                                    Comment Tags
                                                </h3>
                                                <div class="actions">
                                                  
                                                    <a class="btn btn-mini content-slideUp" href="#">
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="box-content" style="display: block;">
                                                <?php
                                    foreach($order_followup_comments as $idx => $val)
                                    {
                                        echo "<span class='label label-danger order_comments'>".$val['comments']."</span>&nbsp;&nbsp;";
                                    }
                                    ?>
                                            </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>

            

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveCollectionDateInfo">Save</button>
          </div>
        </div>
      </div>
    </div>
        
        
    
    <!-- Modal -->
    <div class="modal fade" id="followUpHistoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel">Follow up call history for <b class="fake-order-number"></b></h4>
          </div>
          <div class="modal-body">

            <div class="row" id="followUpHistoryContainer">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveCallBackInfo">Select</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="page-header">
        <div class="row">
            <div class="alert alert-success hide" id="msgContainer">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong>Success!</strong> Record saved successfully.
            </div>
            
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Collection Call List
                        </h3>
                        <ul class="tabs">
                            <li class="active fake-link-days-class" rel="7">
                                <a data-toggle="tab" href="#t7">7 Days</a>
                            </li>
                            <li class="fake-link-days-class" rel="30">
                                <a data-toggle="tab" href="#t8">30 Days</a>
                            </li>
                            <li class="fake-link-days-class" rel="60">
                                <a data-toggle="tab" href="#t9">60 Days</a>
                            </li>
                            <li class="fake-link-days-class" rel="365">
                                <a data-toggle="tab" href="#t9">1 Year</a>
                            </li>
                        </ul>
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
    function initTables(obj)
    {
        extraParams = collectParams(obj);
       
        $('#menuTable').dataTable({
            "bFilter": true,
            "bDestroy": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/report/getCollectionCallData", //datasource
            "sAjaxDataProp": "aData",
            "bServerSide": true, //serverside , 
            "bProcessing": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "name", "sTitle": "Customer Name", "sClass" : "fake-customer-name-class"},
                {"mDataProp": "mobile", "sTitle": "Mobile", "sClass" : "fake-mobile-class"},
                {"mDataProp": "amount", "sTitle": "$"},
                {"mDataProp": "delivery_date", "sTitle": "Delivery Date"},
                {"mDataProp": "address", "sTitle": "Address"},
                {"mDataProp": "boxes", "sTitle": "Boxes"},
                {"mDataProp": "quantities", "sTitle": "Qty"},
                {"mDataProp": 'locations', "sTitle": "Location(s)"},
                {"mDataProp": 'kabupatens', "sTitle": "Kabupaten(s)"},
                {"mDataProp": "remarks", "sTitle": "Remarks"},
                {"mDataProp": 'operations', "sTitle": "Operations"}
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
            "aaSorting": [[4, 'desc']]
        });
    }
    
    $('.fake-link-days-class').click(function(index, tmpObj){
         initTables($(this));
    })
    
    function collectParams(linkObj)
    {
        console.log($(linkObj).attr('rel'))
       var obj = ['name', 'val'];
       obj.name = new Array();
       obj.val = new Array();
       
        obj.name[0] = 'days';
        obj.val[0] = $(linkObj).attr('rel');
       
       return obj;
    }
       
//    initTables();

        
        
        
        
        $('.updateCollectionDetails').click(function (){
                $('#myModal').modal('toggle');
                $('#collectionDateModal').modal('toggle');
        })
        
        $('body').on('click', '.fake-followup-class, .fake-collection-date-class, .fake-followup-history-class', function (){
            $("textarea#order_followup_comments").val('');
            $('.fake-order-id').val($(this).attr('rel'));
            $('.fake-order-number').html($(this).attr('title'));
            
            parentObj = $(this).closest('tr');
            
            customerName = $(parentObj).find('.fake-customer-name-class').html();
            mobile = $(parentObj).find('.fake-mobile-class').html();
            
            $('.fake-customer-name').html(customerName);
            $('.fake-customer-mobile-phone').html(mobile);
            
            
        })
        
        $('body').on('click', '.fake-followup-history-class', function (){
            $('#loadingDiv_bakgrnd').show();
        
            order_id = $(this).attr('rel');
            
            $.ajax({
                data:{order_id:order_id},
                url: "<?=base_url()?>admin/report/fetchCallFollowUp",
                cache: false,
                dataType : 'html',
                type : 'post',
            })
            .done(function( response ) {
                $("#followUpHistoryContainer").html(response);
        
                $('#loadingDiv_bakgrnd').hide();            
            });
        })
        
        $('#saveCallBackInfo').click(function (){
            $('#loadingDiv_bakgrnd').show();            
            data = $('#followUpForm').serialize();
            
            $.ajax({
                data:data,
                url: "<?=base_url()?>admin/report/saveCallFollowUp",
                cache: false,
                dataType : 'json',
                type : 'post',
            })
            .done(function( response ) {
                $('#loadingDiv_bakgrnd').hide();
                $('#myModal').modal('toggle');
                $('#msgContainer').removeClass('hide')
                
                window.location.href = "<?=base_url()?>admin/report/getCollectionCall?haveSideBar=0";
            });
        })
        
        $('#saveCollectionDateInfo').click(function (){
            $('#loadingDiv_bakgrnd').show();            
            data = $('#collectionForm').serialize();
            
            $.ajax({
                data:data,
                url: "<?=base_url()?>admin/report/saveCollectionDate",
                cache: false,
                dataType : 'json',
                type : 'post',
            })
            .done(function( response ) {
                $('#loadingDiv_bakgrnd').hide();
                $('#collectionDateModal').modal('toggle');
                $('#msgContainer').removeClass('hide')
                
                window.location.href = "<?=base_url()?>admin/report/getCollectionCall?haveSideBar=0#7t";
            });
        })


        $('#callBackDate').datepicker({
            format: "dd/mm/yyyy"
        })
        $('#collectionDate').datepicker({
            format: "dd/mm/yyyy"
        })
        
        var input = $('#time');
            input.clockpicker({
            autoclose: true
        });
        var collectionInput = $('#collectionTime');
            collectionInput.clockpicker({
            autoclose: true
        });
        
        $('.order_comments').on('click',function(){
           var order_comment = $(this).text();
           $("textarea#order_followup_comments").val(order_comment);
        });
        
        $('.content-slideUp').on('click',function(){
            $('.box-content').toggle();
        });        
    });
</script>
<style>
    .order_comments:hover{
        cursor : pointer!important;
        font-weight: bold!important;
        
        /*border : 1px solid red!important;*/
    }
    .order_comments{
        float: left;
    margin-left: 5px;
    margin-bottom: 5px;
    }
    </style>