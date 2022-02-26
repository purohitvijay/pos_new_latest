<style type="text/css">
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
                                $grand_total = $order['order']['grand_total'];
                                
                                $statuses = $order['order']['statuses'];
                                $statuses = explode('@@##@@', $statuses);
                                
                                $cash_collections = $order['order']['cash_collections'];
                                $cash_collections = explode('@@##@@', $cash_collections);
                                
                                $box_delivered_index = array_search('box_delivered', $statuses);
                                
                                $cash_collected = $cash_collections[$box_delivered_index];
                                
                                $discount = $order['order']['discount'];
                                $grand_total = $order['order']['grand_total'];
                                
//                                $balance = $grand_total - $discount - $cash_collected - $voucher_cash;
                                $balance = $grand_total - $discount - $cash_collected;
                    ?>
                        
                        
                        
                        
                        
                        
                        
                        
                <div class="col-sm-12 image-container" style="margin:20px 0;margin-left: 1px;padding-left:0px;padding-right:0px;padding-bottom: 20px;border-bottom:0px dashed blue;width:99.9%">
                    <div class="box box-color">

                    <?php
                        $qrcode = base_url(). "assets/dynamic/bar_codes/". "{$order['order']['id']}.png";
                    ?>
                    <table style="width:100%;page-break-after: always;border:none" width="100%" class="table table-hover table-nomargin dataTable dataTable-scroll-y " id="menuTable">

                         <tr>
                             <td style="padding:0px;border:none" rowspan="4"><img src="<?=base_url()?>/assets/img/logo-big.png"></td>
                             <td style="padding:0px;border:none" colspan="4">POS TKI LOGISTICS PTE LTD (Business Registration No. 201804139H)</td>
                             <td style="padding:0px;border:none" rowspan="4"><img width="95px" height="95px"  src="<?=$qrcode?>"></td>
                        </tr>
                         <tr>
                             <td style="padding:0px;border:none" colspan="4">NO 1 KAKI BUKIT ROAD 1 #03-50 ENTERPRISE ONE</td>
                        </tr>
                        <tr>
                             <td style="padding:0px;border:none" colspan="4">SINGAPORE 415934</td>
                        </tr>
                        <tr>
                             <td style="padding:0px;border:none" colspan="4">TEL 65-62974805 FAX: 65-62974827 EMAIL: POSTKI@POSTKI.COM</td>
                        </tr>
                        
                        <tr>
                            <td style="padding:0px;border:none;text-align:center;font-size:30px" colspan="6">Official Receipt</td>
                        </tr>
                        
                        <tr>
                            <td colspan="3" style="padding:0px;border:none">
                                <table width="100%" class="table table-hover table-nomargin dataTable dataTable-scroll-y">
                                    <tr>
                                        <td style="width:20%">Sender</td>
                                        <td colspan="2" style="width:90%"><?=$order['order']['customer_name']?></td>
                                    </tr>
                                    <tr>
                                        <td>Blk/No</td>
                                        <td style="width:40%"><?=$order['order']['block']?></td>
                                        <td style="width:40%"><?=$order['order']['unit']?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><?=$order['order']['building']?>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><?=$order['order']['street'].' '.$order['order']['pin']?></td>
                                    </tr>
                                    <tr>
                                        <td>Tel:</td>
                                        <td><?=$order['order']['mobile']?></td>
                                        <td><?=$order['order']['residence_phone']?></td>
                                    </tr>
                                </table>
                            </td>
                            <td colspan="3" style="padding:0px;border:none">
                                <table width="100%" class="table table-hover table-nomargin dataTable dataTable-scroll-y">
                                    <tr>
                                        <td style="width:50%">DO/Box No:</td>
                                        <td style="width:50%"><?=$order['order']['order_number']?></td>
                                    </tr>
                                    <tr>
                                        <td>Date:</td>
                                        <td><?=date('d/m/Y')?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="6" style="padding:0px;border:0px">
                                <table width="100%" class="table table-hover table-nomargin dataTable dataTable-scroll-y">
                                    <tr>
                                        <td style="width:10.5%">Remarks (Del.)</td>
                                        <td style="width:89.5%"><?=$order['order']['comments']?></td>
                                    </tr>
                                    <tr>
                                        <td>Remarks (Coll.)</td>
                                        <td><?=$order['order']['collection_notes']?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" style="border:1px solid black">Being payment for:</td>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: bold;width:17%;border:1px solid black">Item</td>
                            <td style="font-weight: bold;width:17%;border:1px solid black">Qty</td>
                            <td style="font-weight: bold;width:17%;border:1px solid black">Box Type</td>
                            <td style="font-weight: bold;width:17%;border:1px solid black">Destination</td>
                            <td style="font-weight: bold;width:16%;border:1px solid black">Price 1</td>
                            <td style="font-weight: bold;width:16%;border:1px solid black">Total</td>
                        </tr>

                        <?php
                        if (!empty($order['order_trans']))
                        {
                            foreach ($order['order_trans'] as $index => $row)
                            {
                        ?>
                                <tr>
                                    <td style="border:1px solid black">&nbsp;</td>
                                    <td style="border:1px solid black"><?=$row['quantity']?></td>
                                    <td style="border:1px solid black"><?=$row['box']?></td>
                                    <td style="border:1px solid black"><?=$row['location']. ' '. $row['kabupaten']?></td>
                                    <td style="border:1px solid black">$ <?=$row['price_per_unit']?></td>
                                    <td style="border:1px solid black">$ <?=$row['total_price']?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        <tr>
                            <td style="border:1px solid black" rowspan="4" colspan="4"><span style="padding-left:5px;font-size:25px" class="pull-left">CONTENTS RECEIVED</span><span class="pull-right" style="padding-right:5px;font-size:25px">UNCHECKED</span> </td>
                            <td style="font-weight: bold;border:1px solid black">Price 1</td>
                            <td style="border:1px solid black">$  <?=$grand_total?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border:1px solid black">Discount</td>
                            <td style="border:1px solid black">$ <?=$discount?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border:1px solid black">Deposit</td>
                            <td style="border:1px solid black">$ <?=$cash_collected?></td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;border:1px solid black">Balance</td>
                            <td style="border:1px solid black">$ <?=  number_format($balance, 2);?></td>
                        </tr>
                        
                        <tr>
                            <td colspan="6">THANK YOU FOR CHOOSING POS TKI SERVICES AS YOUR PREFERRED COURIER!</td>
                        </tr>
                        
                        <tr>
                            <td colspan="6">This is Computer Generated. No Signature required.</td>
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
        $("#btnPrint").click (function() {
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                    data:{type : 'receipt', order_ids : $('#order_ids').val()},
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