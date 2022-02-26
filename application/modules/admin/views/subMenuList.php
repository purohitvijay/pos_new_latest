<div class="page-content">
    <div class="row">
        <div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/submenu/addSubMenu" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($message)) {
                ?>
                <div class="alert alert-success active">
                    <button class="close " data-dismiss="alert"></button>
                    <span><?php echo $message; ?></span>
                </div>
            <?php }
            ?>
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-cogs"></i><?php echo mlLang('lblSubMenuListTitle'); ?></div>                    
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="subMenuTable">

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    jQuery(document).ready(function() {
//          TableAdvanced.init();
        $('#subMenuTable').dataTable({
            "bFilter": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/submenu/getSubMenuData", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "subMenuName", "sTitle": "Sub Menu Name"},
                {"mDataProp": "menuName", "sTitle": "Menu Name"},
                {"mDataProp": "permissionName", "sTitle": "Permission Name"},
                {"mDataProp": 'orderId', "sTitle": "Order Id"},
                {"mDataProp": "edit", "sTitle": "Edit", "bSortable": false},
                {"mDataProp": "delete", "sTitle": "Delete", "bSortable": false}
            ],
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true
        });
    });
</script>