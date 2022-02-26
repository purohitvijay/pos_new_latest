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
                        
                        
                        
                        
                        
                        
                        
                        
                <div class="col-sm-12 image-container" style="margin-top:0px;margin-bottom:20px;padding-bottom: 20px;border-bottom:0px dashed blue;">
                    <div class="box box-color">

                    <table style="width:100%;page-break-after: always;border:none" width="100%" class="table table-hover table-nomargin dataTable dataTable-scroll-y " id="menuTable">

                        <tr class="hide">
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                            <td style="width:8.33%">&nbsp;</td>
                        </tr>

                        <tr>
                            <td colspan="8" style="padding:0px;width:71%">
                                <table class="table table-hover table-nomargin dataTable dataTable-scroll-y" style="width: 100%">
                                    <?php
                                    if (!empty($boxes))
                                    {
                                        ?>
                                        <tr>
                                            <?php
                                            $width = (float) 100 / 4;
                                            foreach ($boxes as $index => $box)
                                            {
                                                if ($index == 4) break;
                                                
                                                
                                                ?>
                                                <td style="width:<?= $width ?>%">&nbsp;</th>
                                                <?php
                                                
                                            }
                                            ?>
                                        </tr>
                                        <!--
                                        <tr>
                                        <?php
                                            foreach ($boxes as $index => $box)
                                            {if ($index == 4) break;
                                                ?>
                                                <td style="height: 10px"></td>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        -->
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                            
                            <?php
                            $qrcode = base_url(). "assets/dynamic/bar_codes/". "{$order['order']['id']}.png";
                            ?>
                            <td colspan="4" rowspan="3" style="vertical-align:bottom;padding:0px;width:29%">
                                <table style="width: 100%">
                                    <tr>
                                        <td style="border:medium none;width:40%;">&nbsp;</th>
                                        <td style="border:medium none;width:60%;text-align: right">
                                            <img width="100px" height="100px" src="<?=$qrcode?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border:medium none;width:40%;padding-top: 40px;padding: 30px 10 16px">&nbsp;</th>
                                        <td style="border:medium none;width:60%;padding-top: 40px;padding: 30px 10 16px;font-weight: bolder;font-size: 16px"><?= $order['order']['order_number'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border:medium none;padding-top:25px">&nbsp;</th>
                                        <td style="border:medium none;padding-top:25px"><?= date('d/m/Y', strtotime($order['order']['order_date'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border:medium none;padding-top: 0px;vertical-align: bottom;pading-bottom:1px;white-space: nowrap">&nbsp;</th>
                                        <td style="border:medium none;padding-top: 0px;vertical-align: bottom;pading-bottom:1px"><?= empty($order['order']['collection_date']) ? '' : date('d/m/Y', strtotime($order['order']['collection_date'])) ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        
                        <tr>
                            <td colspan="8" style="padding:0px">
                                <table style="width: 100%" class="table table-hover table-nomargin dataTable-scroll-y">
                                    <?php
                                    if (!empty($locations))
                                    {
                                        ?>
                                        <!--
                                        <tr>
                                            <?php
                                            $width = (float) 100 / count($locations);
                                            foreach ($locations as $index => $location)
                                            {
                                                ?>
                                                <th style="width:<?= $width ?>%"><?= $location['name'] ?></th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                        
                                        <tr>
                                        <?php
                                        foreach ($locations as $index => $location)
                                        {
                                            ?>
                                            <td style="height: 10px"></td>
                                            <?php
                                        }
                                        ?>
                                        </tr>    
                                        -->
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                        
                        <tr>
                            <?php
                            $quantity = 0;
                            $box = '';
                            
                            $code_box_location_arr = array();
                            
                            if (!empty($order['order_trans']))
                            {
                                foreach ($order['order_trans'] as $index => $row)
                                {
                                    $quantity += $row['quantity'];
                                    $box = $row['box'];
                                    
                                    $code_box_location_arr[] = "{$row['box']} | {$row['location']} | {$row['quantity']}";
                                }
                            }
                            
                            $code = $location = '&nbsp;';
                            
                            if (!empty($order['order_code_trans']))
                            {
                                $code_row = array_pop($order['order_code_trans']);
                                $code = $code_row['code'];
                                $location = $code_row['location'];
                            }
                            ?>
                            <td colspan="2"></td>
                            <td colspan="6" style="text-align:left;vertical-align: bottom;padding-bottom: 0px"><?=implode(' <b>+</b> ', $code_box_location_arr)?></td>
                        </tr>



                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:15px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                        </tr>
                        <tr>

                            <td class="printLabelClass" style="padding-bottom:1px;padding-top:0px" colspan="2">&nbsp;</th>
                            <td class="printLabelClass" style="padding-bottom:1px;padding-top:0px;border-bottom:0px solid black" colspan="4"><?= $order['order']['customer_name'] ?>&nbsp;</td>
                            <td class="printLabelClass" style="padding-bottom:1px;padding-top:0px" colspan="2">&nbsp;</th>
                            <td class="printLabelClass" style="padding-bottom:1px;padding-top:0px;border-bottom:0px solid black" colspan="4"><?= $order['order']['recipient_name'] ?>&nbsp;</td>
                        </tr>
 
                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:5px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                        </tr>

                        
                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2"><?= $order['order']['block'] ?>&nbsp;</td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2"><?= $order['order']['unit'] ?>&nbsp;</td>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2">&nbsp;</td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2">&nbsp;</td>
                        </tr>

                        
                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:10px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                        </tr>
                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:19px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="4"><?= $order['order']['street'] ?></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="4">&nbsp;</td>
                        </tr>
                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:12px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                        </tr>

                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="4"><?= $order['order']['building'] ?></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="4">&nbsp;</td>
                        </tr>

                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:7px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;"></th>
                            <td style="padding-bottom:1px;padding-top:1px;" colspan="4"></td>
                        </tr>
                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="4"><?= $order['order']['pin'] ?></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="4">&nbsp;</td>
                        </tr>

                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;height:10px"></th>
                            <td style="padding-bottom:1px;padding-top:1px" colspan="4"></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px"></th>
                            <td style="padding-bottom:1px;padding-top:1px" colspan="4"></td>
                        </tr>

                        
                        <tr>

                            <td colspan="2" style="padding-bottom:1px;padding-top:1px">&nbsp;</th> 
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2"><?= $order['order']['mobile'] ?></td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2"><?= $order['order']['residence_phone'] ?></td>
                            <td colspan="2" style="padding-bottom:1px;padding-top:1px;">&nbsp;</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2"><?= $order['order']['recipient_mobile'] ?></td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:0px solid black" colspan="2">&nbsp;</td>
                        </tr>
                        
                        <tr>
 
                            <td colspan="8" style="padding-bottom:1px;padding-top:500px">&nbsp;<?= $order['order']['comments'] ?></th> 
                            <td colspan="2" style="padding-bottom:1px;padding-top:500px">&nbsp;</th> 
                            
                        </tr>

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