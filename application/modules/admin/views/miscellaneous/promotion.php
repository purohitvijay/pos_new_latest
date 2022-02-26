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

                <select class="form-control searchFormClass pull-left" id="search_promotion_status" style="width:50%;margin-right:10px" name="search_promotion_status">
                    <option value="">--Select--</option>
                    <option selected="selected" value="yes">Active</option>
                    <option value="no">Inactive</option>
                </select>
 
                <div class="right-btn-add pull-left">
                    <a href="<?php echo base_url(); ?>admin/miscellaneous/addPromotion" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> 
                </div>
  
            </div>
        </form>
         
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Promo-Code List
                        </h3>
                    </div>
                    
                    <table class="table table-hover table-nomargin table-bordered" id="menuTable">

                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function () {

$('#search_promotion_status').change(function(){
        initTables();
    });
     
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
            "bDestroy": true,
            "bFilter": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/miscellaneous/getPromotionData", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "name", "sTitle": "Name"},
                {"mDataProp": "date_from", "sTitle": "Date From"},
                {"mDataProp": 'date_to', "sTitle": "Date To"},
                {"mDataProp": 'amount', "sTitle": "Amount"}, 
                {"mDataProp": 'boxes', "sTitle": "Boxes"}, 
                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
                {"mDataProp": "delete", "sTitle": "Delete", "bSortable": false}
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