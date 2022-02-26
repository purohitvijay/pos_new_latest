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
                                    Agent
                                <?php
                                if (!empty($agents))
                                {
                                ?>
                                <Select required name="search_agent_id" id="search_agent_id" class="form-control searchFormClass">
                                    <option value="">--Select--</option>
                                    <?php
                                    foreach ($agents as $index => $row)
                                    {
                                        $selected = !empty($search_agent_id) && $search_agent_id == $row['id'] ? "Selected='Selected'" : '';
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
                                    echo '<br><b>No Agent Found.</b>';
                                }
                                ?>
                                </div> 
                                <div class="col-md-2">
                                    Collection Date From
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input required type="text" class="searchFormClass datepicker input-large form-control" id="search_collection_date_from" placeholder="Collection Date From" name="search_collection_date_from" value="">
                                    </div>

                                </div>
                                 <div class="col-md-2">
                                    Collection Date To
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                            </span>
                                            <input required type="text" class="searchFormClass datepicker input-large form-control" id="search_collection_date_to" placeholder="Collection Date To" name="search_collection_date_to" value="">
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    &nbsp;
                                    <div class="input-group">                                        
                                        <button class="btn-primary btn" id="clearSearchButton" type="button">Reset</button>
                                        <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>
                                        <div style="margin-left:20px;margin-bottom: 0px!important;margin-top: 0px !important" class="alert alert-info pull-right" >NOTE : Only orders with <strong>GIANT</strong> boxes would be listed in report.</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>
        
        
        <?php
        $class = empty($search_agent_id) ? 'hide' : '';
        ?>
        <div id="mainTableHolderDiv" class="row <?=$class?>">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Agent Order List
                        </h3>
                        <h3 id="totalCommissionHolder" class="pull-right hide" style="padding-right:10px"></div>
                    </div>
                    <!--<div class="box-content nopadding">-->
                    <table style="width:100%" class="table table-hover table-nomargin table-bordered" id="menuTable">

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
            "sAjaxSource": "<?php echo base_url(); ?>admin/report/getAgentCommissionData", //datasource
            "sAjaxDataProp": "aData", 
            "bServerSide": true, 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "collection_date", "sTitle": "Collection Date"},
                {"mDataProp": "total_boxes", "sTitle": "Boxes","bSortable": false},
                {"mDataProp": 'grand_total', "sTitle": "Order Total"},
                {"mDataProp": 'discount', "sTitle": "Discount"},
                {"mDataProp": 'nett_total', "sTitle": "Nett Total"},
                {"mDataProp": 'total_commission', "sTitle": "Commission","bSortable": false},
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
    
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        jsonText = $.parseJSON(xhr.responseText)
        $('#totalCommissionHolder').html( jsonText.totalCommissionAmount ).removeClass('hide')
      });
});
</script>