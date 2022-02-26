<link href="http://local.postki.com/assets/css/bootstrap-responsive.min.css?1374862848" rel="stylesheet" type="text/css" />

<style type="text/css">
    .ordernumber_class
    {
        font-size: 420px;
        font-weight: bold;
        height: auto;
        text-align: left;
        transform: rotate(90deg);
        left: -349px;
        position: relative;
        top: 290px;
    }
    
    .table_class
    {
        text-align: left; 
        width: 33.35%;
        margin-top:-20px
    }
    
    .ordernumber_class
    {
        padding-left: 40px;
        stext-align: left;
        vertical-align: middle;
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
    {
    ?>    
    <div class="page-header">
        <div class="row">
            
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    
                    <div id="reportContainer">
                    <input type='hidden' id="order_ids" value="<?=$order_ids?>">
                    <?php   
                                foreach ($records as  $index => $row)
                                { 
                                    $margin = $index == 0 ? "-15px" : "-50px"; 
                                    $margin_image = $index == 0 ? "" : "margin-top:-3px"; 
                    ?>
                                <div class="box-content nopadding image-container" style="padding-left: 10px">
                                    <table class="table table-nomargin table-bordered" style="width:419px;margin-top: <?=$margin?>">
                                    
                                    
                                    <tr>
                                        <td style="padding-top:0px;padding-bottom: 0px;padding-left: 20px" colspan="2">
                                            <img style="<?=$margin_image?>" width="115px" height="115px" src="<?=$row['qrcode']?>">
                                            <img style="margin-left: 18px;<?=$margin_image?>" width="115px" height="115px" src="<?=$row['qrcode']?>">
                                            <img style="margin-left: 18px;<?=$margin_image?>" width="115px" height="115px" src="<?=$row['qrcode']?>">
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                         <?php    
                                         $block = isset($row['block']) ? $row['block'] : "";
                                         $unit = isset($row['unit']) ? $row['unit'] : "";
                                         $street = isset($row['street']) ? $row['street'] : "";
                                         $pin = isset($row['pin']) ? $row['pin'] : "";
                                         $customer_name = isset($row['customer_name']) ? $row['customer_name'] : "";
                                         $address = $block." ".$unit." ".$street." ".$pin;
                                         ?>
                                         <td width="30%" style="padding-left:0px;padding-right:125px;writing-mode: tb-rl; white-space:nowrap;"><h3  style="font-size:38px;"><b><?=$customer_name." ".$address;?></b></h3>
                                          <h3 style="font-size:38px;"><b>
                                         <?php
                                         $orders_destination_kabupaten = isset($row['orders_destination_kabupaten']) ? $row['orders_destination_kabupaten'] : "";  
                                         if(!empty($orders_destination_kabupaten))
                                         {
                                             $orders_destination_kabupaten = str_replace("@#@#", ", ", $orders_destination_kabupaten);
                                             echo $orders_destination_kabupaten;
                                         }
                                         ?>
                                         </b></h3>        
                                         </td>
                                        <td>
                                            <img width="300px;" src="<?=$row['orderno_image']?>" style="margin-bottom:80px;padding-left: 10px">
                                        </td>
                                    </tr> 
                                    <!--
                                    <tr style="width:100%">
                                        <td style="width:33%;text-align: center"><?=$row['order_number'];?></td>
                                        <td style="width:33%;text-align: center"><?=$row['order_number'];?></td>
                                        <td style="width:33%;text-align: center"><?=$row['order_number'];?></td>
                                    </tr>
                                    -->
                                </table>
                                                            <div class="after"></div>

                                </div>
                    <?php 
                                }
    }
                    else
                    {
                    ?>
                        <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">Ã</button>
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

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


<script>
jQuery(document).ready(function () {
    $("#btnPrint").click (function() {
        $('#loadingDiv_bakgrnd').show();
        $.ajax({
                data:{type : 'qrcode', order_ids : $('#order_ids').val()},
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
});
</script>