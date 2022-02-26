 <style type="text/css" media="print">
         @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }
    </style>
    
<style type="text/css" media="all">
    th.printLabelClass
    {
        padding-top: 2px;
        padding-bottom: 2px;
    }
</style>
    
<div class="container-fluid">


    <div class="page-header">
        
        <div class="pull-right" style="margin-bottom: 20px">
                <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                    <i class="fa fa-print"></i>Print
                </button>
        </div>

        <div class="row" id="reportContainer">
            
            

            <div class="col-sm-12">
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
                                        <td style="vertical-align: bottom;pading-bottom:1px"><?= empty($order['order']['collection_date']) ? '--' : date('d/m/Y', strtotime($order['order']['collection_date'])) ?></td>
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
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['recipient_address'] ?>&nbsp;</td>
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
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['customer_mobile'] ?></td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['customer_phone'] ?></td>
                            <th colspan="2" style="padding-bottom:1px;padding-top:1px;">TELP</th>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2"><?= $order['order']['recipient_mobile'] ?></td>
                            <td style="padding-bottom:1px;padding-top:1px;border-bottom:1px solid black" colspan="2">&nbsp;</td>1
                        </tr>

                    </table>


                </div>
            </div>
        </div>
    </div>                                                        
</div>


<script type="text/javascript">
$(document).ready(function () {
                    
        $("#btnPrint").click (function() {
            printElement(document.getElementById("reportContainer"));
            window.print();
        });
})
</script>