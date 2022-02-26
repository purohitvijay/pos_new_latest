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
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Pending Orders Details</h4>
      </div>
      <div class="modal-body" id="orderDetails">

      </div>
      
    </div>
  </div>
</div>
    <div class="page-header">
        
        <div class="row" >
            <form action="<?=base_url()?>admin/report/EODReports" method="post" id="searchForm">
                <div class="pull-left form-group">
                        <label for="date" class="control-label col-sm-2">
                            Date
                        </label>
                        <div class="col-sm-5">
                            <div class='input-group date'>
                                <input type="text" name="date" id="date" class="form-control datepick1 ui-wizard-content hasDatepicker" required value='<?=$date?>'>
                            </div>    
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-primary" >Report</button>
                        </div>
                </div>
            </form>
        </div>
        
        <div class="row">
            
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            EOD Reports
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
            "bFilter": false,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/report/getEODReportsData/<?=$formatted_date?>", //datasource
            "sAjaxDataProp": "aData", //menentukan array/json dibaca dari mana
            "bServerSide": true, //serverside , ini yg bermasalah, kalo di delete beres gak ada error, tapi gak SSP
            "bProcessing": true,
            "aoColumns": [//tentukan kolom pd tabel dan value nya
                {"mDataProp": "s_no", "sTitle": "Serial No"},
                {"mDataProp": "driver_name", "sTitle": "Driver Name"},
                {"mDataProp": "eod_status", "sTitle": "EOD Status"},
                {"mDataProp": "order_details", "sTitle": "Pending Order Details"},
                {"mDataProp": "warehouse_tally_sheet", "sTitle": "Warehouse Tally Sheet", "bSortable": false},
                {"mDataProp": "deposit_collected", "sTitle": "Grand Amount Collected", "bSortable": false},
                {"mDataProp": "cash_report", "sTitle": "Cash Report", "bSortable": false}
            ],
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true
        });
    });
    
    $('#date').datepicker({
        format: "dd/mm/yyyy"
    })
    
    $('body').on('click', '.fake-warehouse-button', function (){
        employee_id = $(this).attr('rel');
        
        url = '<?=base_url()?>admin/report/getWareHouseTallySheet/' + employee_id +'/' + "<?=$formatted_date?>";
        window.open(url, '_blank');
    })
    
    $('body').on('click', '.fake-cash-report-button', function (){
        employee_id = $(this).attr('rel');
        
        url = '<?=base_url()?>admin/report/getCashReport/' + employee_id +'/' + "<?=$formatted_date?>";
        window.open(url, '_blank');
    }) 
    
     $('body').on('click', '.order-details', function (){ 
         employee_id = $(this).attr('rel');
         url = '<?=base_url()?>admin/report/getEODReportOrder/' + employee_id +'/' + "<?=$formatted_date?>";
          $.ajax({
            type : 'GET',
            url : url,
            dataType : 'html',
            })
        .done(function( response ) {
            $('#orderDetails').html(response);
            $('#myModal').modal('toggle');
        });         
    })
</script>