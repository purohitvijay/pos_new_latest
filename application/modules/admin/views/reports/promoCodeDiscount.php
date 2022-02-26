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
                                    Promo-Code
                                <?php
                                if (!empty($promoCodes))
                                { 
                                ?>
                                <Select required name="search_promo_id" id="search_promo_id" class="form-control searchFormClass">
                                    <option value="">--Select--</option>
                                    <?php
                                    foreach ($promoCodes as $index => $row)
                                    {  
                                        $selected = !empty($search_promo_id) && $search_promo_id == $row['id'] ? "Selected='Selected'" : '';
                                ?>
                                        <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                <?php
                                    }
                                    ?>
                                </Select>
                                <?php
                                }
                                else
                                {
                                    echo '<br><b>No Promo-Code Found.</b>';
                                }
                                ?>
                                </div> 
                                
                                <div class="col-md-2">
                                    Collection Date From
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input  type="text" class="searchFormClass datepicker input-large form-control" id="search_collection_date_from" placeholder="Collection Date From" name="search_collection_date_from" value="">
                                    </div>

                                </div>
                                
                                 <div class="col-md-2">
                                    Collection Date To
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input  type="text" class="searchFormClass datepicker input-large form-control" id="search_collection_date_to" placeholder="Collection Date To" name="search_collection_date_to" value="">
                                    </div>

                                 </div>
                                
                                 <div class="col-md-6">
                                    &nbsp;
                                    <div class="input-group">                                        
                                        <button class="btn-primary btn" id="clearSearchButton" type="button">Reset</button>
                                        <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>
                                    </div>
                                </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>
        
        
        <?php
        $class = empty($search_promo_id) ? 'hide' : '';
        ?>
        <div id="mainTableHolderDiv" class="row <?=$class?>">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            PromoCode List
                        </h3>
          
                    </div>
                    </div> 
                
                    <table style="width:100%" class="table table-hover table-nomargin table-bordered" id="menuTable"> 
                        <div class="pull-right hide" id="downloadXlsReportHolder">
                            <br/>
                            <button id="downloadXls" class="btn btn-primary" style="margin-right: 14px">
                               <i class="fa fa-download"></i>Download as Xls
                           </button>
                        </div>
 
                    </table> 
                    
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
            "sAjaxSource": "<?php echo base_url(); ?>admin/report/getPromoDiscountData", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "collection_date", "sTitle": "Collection Date"},
                {"mDataProp": "box_quantity", "sTitle": "Boxes"},
                {"mDataProp": "customer_name", "sTitle": "Customer Name"},
                {"mDataProp": "customer_mobile", "sTitle": "Contact Number"},
                {"mDataProp": "box_name", "sTitle": "Size"},
                {"mDataProp": "kabupaten_name", "sTitle": "Destination"}, 
                {"mDataProp": 'grand_total', "sTitle": "Order Total"},
                {"mDataProp": 'discount', "sTitle": "Discount"},
                {"mDataProp": 'nett_total', "sTitle": "Nett Total"}, 
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
       $('#mainTableHolderDiv').removeClass('hide')
    })

    $('#clearSearchButton').click(function(){
       window.location.href = ''
    })

    $("#search_collection_date_from").datepicker
    ({
        dateFormat:'dd/mm/yy',
        onSelect: function(selected) 
        {
            $("#search_collection_date_to").datepicker("option", "minDate", selected)
        }
    });
    
    $('#search_collection_date_to').datepicker
    ({
        dateFormat:'dd/mm/yy',
        onSelect: function(selected) 
        {
            $("#search_collection_date_from").datepicker("option", "maxDate", selected)
        }
    });

     $("#downloadXls").click(function () {
        var promo_id = $("#search_promo_id").val();
        var collection_date_from = $("#search_collection_date_from").val();
        var collection_date_to = $("#search_collection_date_to").val();
           
        var today = new Date();
        var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date+'_'+time;
 

        window.location.href = "<?= base_url();?>admin/report/downloadPromoCodeDataXlsReport?collection_date_from=" + collection_date_from+ "&collection_date_to=" + collection_date_to + "&promo_id=" +promo_id +"&current_datetime=" +dateTime;
    });
                
    
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        jsonText = $.parseJSON(xhr.responseText);
        if(jsonText.iTotalRecords > 0)
        { 
            $("#downloadXlsReportHolder").removeClass('hide');
        }
        else
        {
            $("#downloadXlsReportHolder").addClass('hide');
        }
        
      });
});
</script>