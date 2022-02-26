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
        <?php if($add_new){ ?>
        <div class="pull-right">
            <div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/luckyDraw/index" class="btn default">Add New</a> </div>
        </div>
        <?php } ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Lucky Draw List
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
            "sAjaxSource": "<?php echo base_url(); ?>admin/luckyDraw/getLuckyDrawData", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "name", "sTitle": "Name"},
                {"mDataProp": "created_at", "sTitle": "Created At"},
                {"mDataProp": "no_of_prizes" , 'sTitle' : "No Of Prizes"},
                {"mDataProp": "winner_order_number", "sTitle": "Winner Order No."},
                {"mDataProp": 'is_draw_awarded', "sTitle": "Draw Declared" ,"bSortable": false},
                {"mDataProp": 'created_by_username', "sTitle": "Created By" ,"bSortable": false},
                {"mDataProp": "excluded_agent_name", "sTitle": "Excluded Agent", "bSortable": false},
                {"mDataProp": "operation", "sTitle": "Operation", "bSortable": false},
            ],
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true
        });
    });
</script>