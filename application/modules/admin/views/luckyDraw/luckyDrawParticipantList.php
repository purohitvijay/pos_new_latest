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
            <!--<div class="right-btn-add"> <a href="<?php echo base_url(); ?>admin/luckyDraw/index" class="btn default">Lucky Draw</a> </div>-->
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Lucky Draw Participant List
                        </h3>
                        <ul class="tabs">
                            <li class="active fake-link-days-class" rel="365">
                                <a  href="<?php echo base_url(); ?>admin/luckyDraw/luckyDrawList?haveSideBar=0">Back</a>
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

<script>
    jQuery(document).ready(function () {
//          TableAdvanced.init();
        $('#menuTable').dataTable({
            "bFilter": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/luckyDraw/getLuckyDrawParticipantData/<?=$lucky_draw_id;?>", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "order_number", "sTitle": "Order No."},
                {"mDataProp": "customer_name", "sTitle": "Customer Name"},
                {"mDataProp": 'block', "sTitle": "Block" , "bSortable": false},
                {"mDataProp": 'unit', "sTitle": "Unit","bSortable": false },
                {"mDataProp": "street", "sTitle": "Street", "bSortable": false},
                {"mDataProp": "building", "sTitle": "Building", "bSortable": false},
                {"mDataProp": "pin", "sTitle": "PIN", "bSortable": false},
                {"mDataProp": "mobile", "sTitle": "Contact1", "bSortable": false},
                {"mDataProp": "residence_phone", "sTitle": "Contact2", "bSortable": false}
            ],
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true
        });
    });
</script>