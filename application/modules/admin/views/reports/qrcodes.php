<link href="http://local.postki.com/assets/css/bootstrap-responsive.min.css?1374862848" rel="stylesheet" type="text/css" />

<style type="text/css">
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

@page {
    counter-increment: page;
    counter-reset: page 1;
    @top-right {
        content: "Page " counter(page) " of " counter(pages);
    }
}
</style>

<div class="container-fluid">
    
    <br/>
    
    <div class="row" >
        <form action="<?=base_url()?>admin/report/getReport" method="post" id="myForm">
            <input type="hidden" name="type" id="report_type">
            <div class="pull-left form-group">
                    <label for="delivery_date" class="control-label col-sm-4">
                        Delivery Date
                    </label>
                    <div class="col-sm-3">
                        <div class='input-group date'>
                            <input type="text" name="delivery_date" id="delivery_date" class="form-control big datepick2" required value='<?=date('d/m/Y', strtotime($delivery_date))?>'>
                        </div>    
                    </div>
                    <div class="col-sm-5">
                        <button rel="labels" class="btn btn-primary report-type-fake-class" style="margin-right: 14px">
                            <i class="fa  fa-qrcode"></i>&nbsp;Labels
                        </button>
                        <button rel="forms" class="btn btn-primary report-type-fake-class">
                            <i class="fa  fa-paperclip"></i>&nbsp;Forms
                        </button>
                    </div>
            </div>
        </form>
        
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
                    <div class="box-title">
                        <h3>
                            <?php
                            if ($type == 'labels')
                            {
                            ?>
                            <i class="fa fa-qrcode"></i>
                            Print QR Codes
                            <?php
                            }
                            else
                            {
                            ?>
                            <i class="fa fa-paperclip"></i>
                            Print Labels
                            <?php
                            }
                            ?>
                        </h3>
                    </div>
                    
                    <div id="reportContainer">
                    <?php
                        if ($type == 'forms')
                        {
                            foreach ($records as  $index => $order)
                            {
                    ?>
                        
                        
                        
                        
                        
                        
                        
                        
                <div class="col-sm-12 image-container" style="margin-top:20px;margin-bottom:20px;padding-bottom: 20px;border-bottom:1px dashed blue;">
                    <div class="box box-color box-bordered">

                    <table style="width:100%" width="100%" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">

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
                            <td colspan="8" style="padding:0px">
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
                                                <th style="width:<?= $width ?>%"><?= $box['name'] ?></th>
                                                <?php
                                                
                                            }
                                            ?>
                                        </tr>
                                        
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

                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>

                            <td colspan="4" rowspan="3" style="vertical-align:bottom;padding:0px">
                                <table style="width: 100%">
                                    <tr>
                                        <th style="width:40%">BOX NO</th>
                                        <td style="width:60%"><?= $order['order']['order_number'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>ORDER DATE</th>
                                        <td><?= date('d/m/Y', strtotime($order['order']['order_date'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align: bottom;pading-bottom:1px;">COLLECTION DATE</th>
                                        <td style="vertical-align: bottom;pading-bottom:1px"><?= date('d/m/Y', strtotime($order['order']['collection_date'])) ?></td>
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
                                        <?php
                                    }
                                    ?>
                                </table>
                            </td>
                        </tr>
                        
                        <tr>
                            <?php
                            $quantity = 0;
                            
                            if (!empty($order['order_trans']))
                            {
                                foreach ($order['order_trans'] as $index => $row)
                                {
                                    $quantity += $row['quantity'];
                                }
                            }
                            
                            $code = $location = '&nbsp;';
                            
                            if (!empty($order['order_code_trans']))
                            {
                                $code_row = array_pop($order['order_code_trans']);
                                $code = $code_row['code'];
                                $location = $code_row['kabupaten'];
                            }
                            ?>
                            <td colspan="3">&nbsp;</td>
                            <td colspan="4"><?="$code | $location | $quantity"?></td>
                            <td>&nbsp;</td>
                        </tr>



                        <tr>

                            <th class="printLabelClass" style="padding-bottom:1px;padding-top:1px" colspan="2">PENGIRIM</th>
                            <td class="printLabelClass" style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4"><?= $order['order']['customer_name'] ?>&nbsp;</td>
                            <th class="printLabelClass" style="padding-bottom:1px;padding-top:1px" colspan="2">PENERIMA</th>
                            <td class="printLabelClass" style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4"><?= $order['order']['recipient_name'] ?>&nbsp;</td>
                        </tr>
 
                        <tr>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">BLK/NO</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['block'] ?>&nbsp;</td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['unit'] ?>&nbsp;</td>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">BLK/NO</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2">&nbsp;</td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2">&nbsp;</td>
                        </tr>

                        <tr>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">JALAN</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4"><?= $order['order']['street'] ?></td>
                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">JALAN</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4">&nbsp;</td>
                        </tr>

                        <tr>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px;height:13px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;height:13px" colspan="4"></td>
                            <th colspan="2" style="padding-bottom:1px;padding-top:1px;height:13px"></th>
                            <td style="padding-bottom:1px;padding-top:1px;height:13px" colspan="4"></td>
                        </tr>

                        <tr>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">BANGUNAN</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4"><?= $order['order']['building'] ?></td>
                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">BANGUNAN</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4">&nbsp;</td>
                        </tr>

                        <tr>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">KODE POS</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4"><?= $order['order']['pin'] ?></td>
                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">KODE POS</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="4">&nbsp;</td>
                        </tr>

                        <tr>

                            <th colspan="2" style="padding-bottom:1px;padding-top:1px">TELP</th> 
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['mobile'] ?></td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['residence_phone'] ?></td>
                            <th colspan="2" style="padding-bottom:1px;padding-top:1px;">TELP</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['recipient_mobile'] ?></td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2">&nbsp;</td>
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
                                foreach ($records as  $index => $row)
                                {
                    ?>
                                <div class="box-content nopadding image-container">
                                <table class="table table-nomargin table-bordered" style="width:100%">
                                    
                                    <tr>
                                        <td colspan="3" style="font-weight:bold;text-align: center; font-weight: bold; transform: rotate(90deg); height: auto; line-height: 8; font-size: 96px;">
                                            <?php
                                            echo $row['order_number'];
                                            ?>
                                        </td>
                                    </tr>
                                    
                                    <tr style="width:100%">
                                        <td style="width:33%;text-align: center;vertical-align: middle"><img width="150px" height="135px" src="<?=$row['qrcode']?>"></td>
                                        <td style="width:33%;text-align: center;vertical-align: middle"><img width="150px" height="135px" src="<?=$row['qrcode']?>"></td>
                                        <td style="width:33%;text-align: center;vertical-align: middle"><img width="150px" height="135px" src="<?=$row['qrcode']?>"></td>
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
            printElement(document.getElementById("reportContainer"));
            window.print();
        });
        
        $(".report-type-fake-class").click (function() {
            $('#report_type').val($(this).attr('rel'));
            $('#myForm').submit();
        });
        
    });
</script>