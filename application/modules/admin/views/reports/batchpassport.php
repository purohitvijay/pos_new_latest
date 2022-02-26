<link href="http://local.postki.com/assets/css/bootstrap-responsive.min.css?1374862848" rel="stylesheet" type="text/css" />

<style type="text/css">
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border-top: medium none !important;
}
    
/*
    .image-container .after {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    color: #FFF;
    background-image: url('<?php echo base_url()?>/assets/img/print.png');
    background-repeat: no-repeat;
    background-position: center center;
}
.image-container:hover .after {
    display: block;
    background: rgba(0, 0, 0, .6); 
    background-image: url('<?php echo base_url()?>/assets/img/print.png');
    background-repeat: no-repeat;
    background-position: center center;
}

.image-container {
    position: relative;
}
*/
@media print {
      body, html, #reportContainer {
          width: 100%;
      }
}
</style>
<div class="container-fluid">    
    <br/>    
    <div class="row" >                
        <div class="pull-right">
            <?php
            if (!empty($records))
            {
            ?>
                <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                    <i class="fa fa-print"></i>Print
                </button>
            <?php             
            }
            ?>
        </div>
    </div>
<?php
if (!empty($records))
{ ?>    
    <div class="page-header">
        <div class="row">            
            <div class="col-sm-12">
                <div class="box box-color box-bordered">                    
                    <div id="reportContainer">
                            <input type='hidden' id="order_ids" value="<?=$order_ids?>">
                           <?php foreach ($records as $key => $value) { ?>
                            <div style="border: 1px solid white;margin: 5px;border-radius:5px;page-break-before: always">
                                <div class="form-group">                                    
                                    <label>Passport Id Number : &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <label style="font-size: 50px;"><?= $value["order"]["passport_id_number"] ?></label>
                                </div>
                                <div class="form-group">
                                    <label>Passport Image : &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <img src="<?= base_url("./assets/img/customer_passport/")."/".$value["order"]["passport_img"] ?>" alt="Passport Image" style="height: 50%;width: 80%">
                                </div>
                            </div>
                            <hr>
                            <br>
                            <p style="page-break-before: always"></p>
                            <?php  } }  else { ?>
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>Oops! </strong>No records Found.
                            </div>
                        <?php  } ?>
                    </div>
                </div>
            </div>   
        </div>   
    </div>   
</div>   
<script>
    jQuery(document).ready(function () {
        $('#delivery_date').datepicker({
            format: "dd/mm/yyyy"
        })
        
        $("#btnPrint").click (function() {
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                    data:{type : 'form', order_ids : $('#order_ids').val()},
                    url: "<?=base_url()?>admin/order/saveOrderPrintStatus",
                    type:'POST',
                    dataType : 'HTML'
            })
            .done(function( response ) {
                printElement(document.getElementById("reportContainer"));
                window.print();

                $('#loadingDiv_bakgrnd').hide();
            });
        });        
        
        $(".report-type-fake-class").click (function() {
            $('#report_type').val($(this).attr('rel'));
            $('#myForm').submit();
        });
        
    });
</script>