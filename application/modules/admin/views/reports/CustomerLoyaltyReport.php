<div class="container-fluid">    
    <div class="page-header">
        <form id="searchForm" action="<?=base_url()?>admin/report/CustomerLoyaltyReport" method="post">
            <input class="searchFormClass" type="hidden" name="search_customer_id" value="" id="customerIdHidden">
            <fieldset class="form-border">
                <legend class="form-border">Search Form</legend>
                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-12">      
                            <div class="form-group row">     
                                <div class="col-md-3">
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
                                </div>
                                 <div class="col-md-2">
                                    Order Count Min
                                    <div class="input-group">
                                        <input required type="number" class="input-large form-control searchFormClass" min="0" id="order_count_min" name="order_count_min" value="<?= $order_count_min_selected?>">
                                    </div>
                                 </div>
                                 <div class="col-md-2">
                                    Order Count Max
                                    <div class="input-group">
                                        <input required type="number" class="input-large form-control searchFormClass" min="0" id="order_count_max" name="order_count_max" value="<?= $order_count_max_selected?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    &nbsp;
                                    <div class="input-group">                                        
                                        <!--<button class="btn-primary btn" id="clearSearchButton" type="button">Reset</button>-->
                                        <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>
        
        <div id="mainTableHolderDiv" >
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Customer 
                        </h3>                        
                        <!--<div class="col-sm-6">-->
                            <?php if (!empty($records)) { ?>
                                <button id="downloadXls" class="btn btn-success pull-right"><i class="fa fa-download"></i>Download as Xls </button>
                            <?php } ?>
                        <!--</div>-->
                    </div>
                    </div>
                    <table style="width:100%" class="table table-hover table-nomargin table-bordered" id="menuTable">
                        <thead>
                            <tr>
                              <th scope="col">Customer name</th>
                              <th scope="col">Contact</th>
                              <th scope="col">address</th>
                              <th scope="col">Frequency</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            if(!empty($records))
                            {
                                foreach ($records as $key => $value) { ?>                             
                            <tr>
                              <th scope="row"><?= $value["name"]?></th>
                              <td><?= $value["mobile"]?></td>
                              <td><?= $value["block"].' '.$value["street"].' '.$value["unit"]?></td>
                              <td><a href="#" class="ModalOrdersCount" search_customer_id="<?= $value["customer_id"]?>"><?= $value["orders_count"]?></a></td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">          
        <table style="width:100% !important" class="table table-bordered" id="orderTable">
        </table>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
      </div>
    </div>
  </div>
</div>
<script>
jQuery(document).ready(function () {
    
    $("#months").multiselect();
    $('#menuTable').dataTable();
    $("#order_count_min").on("mouseup keyup",function()
    {
        if($("#order_count_max").val() < $(this).val())
        {
            $("#order_count_max").attr("min",$(this).val())
            $("#order_count_max").val($(this).val())
        }
    });
    
    $("#downloadXls").click(function ()
    {
        var year = $("#year").val();
        var months = $("#months").val();
        var order_count = $("#order_count").val();

        window.open("<?= base_url(); ?>admin/report/downloadCustomerLoyaltyXlsReport?year=" + year + "&months=" + months + "&order_count=" + order_count);
    });
            
    $('.ModalOrdersCount').on('click', function() {
        var search_customer_id = $(this).attr("search_customer_id"); 
        $("#customerIdHidden").val(search_customer_id);
        $("#exampleModal").modal('show');
           initTables();           
          $("#orderTable").css("width","100%");
        })
        
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

    $('#clearSearchButton').click(function(){
       window.location.href = ''
    })
});
</script>