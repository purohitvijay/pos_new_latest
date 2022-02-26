<link href="http://local.postki.com/assets/css/bootstrap-responsive.min.css?1374862848" rel="stylesheet" type="text/css" />

<style type="text/css">
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border-top: medium none !important;
}

@media print {
      body, html, #reportContainer {
          width: 100%;
      }
}

td.printClass
{
    font-size:19px !important;
    font-weight: bold;
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
    {
    ?>    
    <div class="page-header">
        <div class="row">
            
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <input type='hidden' id="order_ids" value="<?=$order_ids?>">

                    <div id="reportContainer">
            <?php
                    foreach ($records as  $index => $order)
                    {
            ?>
                        <div class="col-sm-12 image-container" style="border-bottom:0px dashed blue;">
                            <div class="box box-color">

                            <table style="width:80%;page-break-after: always;border:none" width="70%" class="table table-hover table-nomargin dataTable dataTable-scroll-y " id="menuTable">

                                            <tr>
                                                <td style="width:40%;padding:0px;border:none" rowspan="4"><img src="<?=base_url()?>/assets/img/logo-big.png"></td>
                                                <td style="width:60%;padding:0px;border:none">POS TKI SERVICES (Business Registration No. 53084672A)</td>
                                           </tr>
                                            <tr>
                                                <td style="padding:0px;border:none" colspan="2">NO 1 KAKI BUKIT ROAD 1 #03-50 ENTERPRISE ONE</td>
                                           </tr>
                                           <tr>
                                                <td style="padding:0px;border:none" colspan="2">SINGAPORE 415934</td>
                                           </tr>
                                           <tr>
                                                <td style="padding:0px;border:none" colspan="2">TEL 65-62974805 FAX: 65-62974827 EMAIL: POSTKI@POSTKI.COM</td>
                                           </tr>
                                        
                                           <tr><td colspan="2">&nbsp;</td></tr>
                                <tr><td style="margin-top:40px">&nbsp;</td><td class="printClass">DO <?=$order['order']['order_number']?></td></tr>
                                <tr><td></td><td class="printClass"><?=$order['order']['customer_name']?></td></tr>
                                <tr><td></td><td class="printClass"><?=$order['order']['block'].' '. $order['order']['unit']?></td></tr>
                                <?php
                                if(!empty($order['order']['building']))
                                {
                                ?>
                                    <tr><td></td><td class="printClass"><?=$order['order']['building']?></td></tr>
                                <?php
                                }
                                if(!empty($order['order']['street']))
                                {
                                ?>
                                    <tr><td></td><td class="printClass"><?=$order['order']['street']?></td></tr>
                                <?php
                                }
                                ?>
                                <tr><td></td><td class="printClass">Singapore <?=$order['order']['pin']?></td></tr>
                            </table>


                        </div>

                        <div class="after"></div>

                    </div>
                        
            <?php
                }
            }
            else
            {
            ?>
                    <div class="alert alert-warning alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Oops! </strong>No records Found.
                    </div>
            <?php
            }
            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    jQuery(document).ready(function () {
        
        $("#btnPrint").click (function() {
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                    data:{type : 'picture_receive_label', order_ids : $('#order_ids').val()},
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