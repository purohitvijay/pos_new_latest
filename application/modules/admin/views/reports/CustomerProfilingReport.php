<div class="container-fluid">    
    <div class="page-header">
        <form id="searchForm" action="<?=base_url()?>admin/report/CustomerProfilingReport" method="post">
            <input class="searchFormClass" type="hidden" name="search_customer_id" value="" id="customerIdHidden">
            <fieldset class="form-border">
                <legend class="form-border">Search Form</legend>
                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-12">      
                            <div class="form-group row">     
<!--                                <div class="col-md-3">
                                    Year
                                    <div class="input-group" style="width:80%">
                                        <div class='input-group date' style="width:80%">
                                            <select id="year" name="year" class="form-control searchFormClass">
                                                <?php
                                                foreach ($start_year as $index => $year)
                                                {
                                                    $selected = ($year == $year_selected) ? 'Selected' : '';
                                                    ?>
                                                    <option <?=$selected?> value="<?= $year ?>"><?= $year ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    Months
                                    <div class="input-group">
                                        <div class='input-group date'>
                                            <select id="months" name="months[]" multiple class="form-control searchFormClass">
                                                
                                                <?php
                                                 $month_name = array();
                                                foreach ($months as $index => $month)
                                                {
                                                    $selected = !empty($months_selected) && in_array($index, $months_selected) ? 'Selected' : '';

                                                    if (!empty($months_selected) && in_array($index, $months_selected))
                                                    {
                                                        $month_name[] = "<b>{$month}</b>";
                                                    } ?>
                                                    <option <?=$selected?> value="<?= $index ?>"><?= $month ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>  
                                    </div>
                                </div>-->

                                <div class="col-md-3">
                                    <label for="from" class="control-label pull-left">
                                        From
                                    </label>
                                    <div class="pull-left" style="padding-left:10px">
                                        <div class='input-group date'>
                                            <input type="text" name="from" id="from" class="form-control big datepick2" required value='<?= $from ?>'>
                                        </div>    
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="to" class="control-label pull-left">
                                       To
                                    </label>
                                    <div class="pull-left" style="padding-left:10px">
                                        <div class='input-group date'>
                                            <input type="text" name="to" id="to" class="form-control big datepick2" required value='<?= $to ?>'>
                                        </div>    
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="to" class="control-label pull-left">
                                       Type
                                    </label>                    
                                    <div class="pull-left" style="padding-left:10px">
                                        <div class='input-group date'>
                                            <select id="types" name="type[]" multiple class="form-control searchFormClass">                                                
                                            <?php foreach (array("Customer Type", "Media Type") as $index => $type) {
                                                    $selected = !empty($types_selected) && in_array($type, $types_selected) ? 'Selected' : '';

                                                    if (!empty($types_selected) && in_array($type, $types_selected))
                                                    {
                                                        $type_name[] = "<b>{$type}</b>";
                                                    } ?>
                                                    <option <?=$selected?> value="<?= $type ?>"><?= $type ?></option>
                                          <?php }  ?>
                                            </select>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    &nbsp;
                                    <div class="pull-left" style="padding-left:10px">
                                        <div class="input-group">                                        
                                           <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>
        
        <?php if(empty($types_selected) || in_array("Customer Type", $types_selected)) { ?>
        <div id="mainTableHolderDiv" >
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Customer Type  
                        </h3>                        
                        <!--<div class="col-sm-6">-->
                            <?php if (!empty($CustomerType)) { ?>
                                <button id="DownloadCustomerTypeXls" class="btn btn-success pull-right"><i class="fa fa-download"></i>Download as Xls</button>
                            <?php } ?>
                        <!--</div>-->
                    </div>
                    </div>
                    <table style="width:100%" class="table table-hover table-nomargin table-bordered" id="CustomerTypeTable">
                        <thead>
                            <tr>
                              <th scope="col">Customer Type</th>
                              <th scope="col">Order Count</th>
                              <th scope="col">Pecentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            if(!empty($CustomerType))
                            {
                                foreach ($CustomerType as $key => $value) { ?>                                
                                <tr>
                                  <td><?= $value["customer_type"]?></td>
                                  <td><?= $value["orders_count"]?></td>
                                  <td><?= $value["pecentage"]?></td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        
        <?php if(empty($types_selected) || in_array("Media Type", $types_selected)) { ?>
        <div id="mainTableHolderDiv" >
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Media Type  
                        </h3>                        
                        <!--<div class="col-sm-6">-->
                            <?php if (!empty($MediaType)) { ?>
                                <button id="DownloadMediaTypeXls" class="btn btn-success pull-right"><i class="fa fa-download"></i>Download as Xls</button>
                            <?php } ?>
                        <!--</div>-->
                    </div>
                    </div>
                    <table style="width:100%" class="table table-hover table-nomargin table-bordered" id="MediaTypeTable">
                        <thead>
                            <tr>
                              <th scope="col">Media Type</th>
                              <th scope="col">Order Count</th>
                              <th scope="col">Pecentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            if(!empty($MediaType))
                            {
                                foreach ($MediaType as $key => $value) { ?>                             
                                <tr>
                                  <td><?= $value["media_type"]?></td>
                                  <td><?= $value["orders_count"]?></td>
                                  <td><?= $value["pecentage"]?></td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        </div>        
    </div>
<script>
jQuery(document).ready(function () {
        $('.datepick2').datepicker({
            format: "dd/mm/yyyy"
        })
    
    $("#months").multiselect();
    $("#types").multiselect();
    
    $('#CustomerTypeTable').dataTable();
    $('#MediaTypeTable').dataTable();
    
    $("#DownloadCustomerTypeXls").click(function ()
    {
        var from = $("#from").val();
        var to = $("#to").val();

        window.open("<?= base_url(); ?>admin/report/downloadCustomerTypeXlsReport?from=" + from + "&to=" + to);
    });
    
    $("#DownloadMediaTypeXls").click(function ()
    {
        var from = $("#from").val();
        var to = $("#to").val();

        window.open("<?= base_url(); ?>admin/report/downloadMediaTypeXlsReport?from=" + from + "&to=" + to);
    });
        
    function initTables()
    {        
        extraParams = collectParams();
       
        $('#orderTable').dataTable({
            "bFilter": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/report/getCustomerLoyaltyOrder", //datasource
            "sAjaxDataProp": "aaData", 
            "bProcessing": true,
            "bDestroy": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Box ID"},
                {"mDataProp": "quantity", "sTitle": "Order Qty"},
                {"mDataProp": "collection_date", "sTitle": "Collection Date"}
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
});
</script>