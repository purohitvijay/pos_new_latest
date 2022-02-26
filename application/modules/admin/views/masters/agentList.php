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
            <div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/masters/addAgent" class="btn default"><?php echo mlLang('lblAddNewBtn'); ?></a> </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Agent List
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

<script>
    jQuery(document).ready(function () {
//          TableAdvanced.init();
        $('#menuTable').dataTable({
            "bFilter": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/masters/getAgentData", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "name", "sTitle": "Name"},
                {"mDataProp": "email", "sTitle": "Email"},
                {"mDataProp": 'mobile', "sTitle": "Mobile"},
                {"mDataProp": 'phone', "sTitle": "Phone"},
                {"mDataProp": 'address', "sTitle": "Address"},
                {"mDataProp": 'joining_date', "sTitle": "DOJ"},
                {"mDataProp": 'commission', "sTitle": "Comm ($)"},
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