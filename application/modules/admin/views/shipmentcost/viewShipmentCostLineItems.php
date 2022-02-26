<div class="container-fluid">
    
    <div class="page-header">
        
        <div class="pull-right">
            <div style="margin-left:15px" class="right-btn-add pull-right"><button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">Print</button></div>
            <div class="right-btn-add pull-right"> <button type="button" class="btn btn-primary fake-back-class">Back</button></div>
        </div>
        <br>
        <br>
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                        <table style="page-break-after: always;" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                            <tr>
                                <td style="text-align: center" colspan="5">
                                    Shipment Batch : <b><?=$shipment_batch['batch_name']?> |</b> Exchange Rate : <b><?=$shipment_batch['exchange_rate']?></b> |</b> Payment Reference : <b><?=$shipment_batch['payment_reference']?> </b>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:15%">ITEM</th>
                                <th style="width:20%">DESCRIPTION</th>
                                <th style="width:15%">QUANTITY</th>
                                <th style="width:20%">LOCAL ($)</th>
                                <th style="width:20%">FOREIGN (IDR)</th>
                            </tr>

                                <?php
                                
                                $subtotal = 0;
                                
                                $previous_section = '';
                                if (!empty($data))
                                {            
                                    $subtotal_in_local_currency = $subtotal_in_foreign_currency = 
                                            $grand_total_in_local_currency = $grand_total_in_foreign_currency = 0;

                                    foreach ($data as $index => $record)
                                    {
                                        if ($previous_section != $record['section'])
                                        {
                                            if ($index > 0)
                                            {
                                                echo "<tr>"
                                                . "<td>&nbsp;</td>"
                                                . "<td><b>SUBTOTAL</b></td>"
                                                . "<td>$subtotal</td>"
                                                . "<td>".$subtotal_in_local_currency."</td>"
                                                . "<td>".$subtotal_in_foreign_currency."</td>"
                                                . "</tr>";
                                                
                                                $subtotal_in_local_currency = $subtotal_in_foreign_currency = $subtotal = 0;
                                            }
                                            
                                            echo "<tr><th colspan='5'>{$record['section']}</td></tr>";
                                        }
                                        
                                        if ($record['section'] === 'DISTRIBUTION:(overseas)' || $record['section'] === 'SPECIAL BOXES')
                                        {
                                            $count = $record['count'];
                                            $subtotal += $count;
                                        }
                                        else
                                        {
                                            $count = '&nbsp;';                                            
                                        }
                                        
                                        echo ""
                                        . "<td>{$record['line_item']}</td>"
                                        . "<td>{$record['description']}</td>"
                                        . "<td>$count</td>"
                                        . "<td>".$record['local_currency_amount']."</td>"
                                        . "<td>".$record['foreign_currency_amount']."</td>"
                                        . "";

                                        $subtotal_in_local_currency += $record['local_currency_amount'];
                                        $subtotal_in_foreign_currency += $record['foreign_currency_amount'];

                                        $grand_total_in_local_currency += $record['local_currency_amount'];
                                        $grand_total_in_foreign_currency += $record['foreign_currency_amount'];

                                        echo "</tr>";
                                        
                                        $previous_section = $record['section'];
                                    }
                                
                                    echo "<tr>"
                                    . "<td>&nbsp;</td>"
                                    . "<td>&nbsp;</td>"
                                    . "<td><b>SUBTOTAL</b></td>"
                                    . "<td class='local_sub_total_fake_class'>".$subtotal_in_local_currency."</td>"
                                    . "<td class='foreign_sub_total_fake_class'>".$subtotal_in_foreign_currency."</td>"
                                    . "</tr>";

                                    echo "<tr>"
                                        . "<td>&nbsp;</td>"
                                        . "<td>&nbsp;</td>"
                                        . "<td><b>TOTAL</b></td>"
                                        . "<td id='foreign_grand_total_fake_class'>".$grand_total_in_local_currency."</td>"
                                        . "<td id='local_grand_total_fake_class'>".$grand_total_in_foreign_currency."</td>"
                                        . "</tr>";
                                }
                                
                        ?>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


<script type="text/javascript">
$(document).ready(function () {

    $('.fake-back-class').click(function(){
        window.location.href = "<?=base_url()?>admin/shipmentcost/shipmentPaymentProcessingList";
    })
    
<?php
if (!empty($data))
{
?>            
    document.getElementById("btnPrint").onclick = function() {
        printElement(document.getElementById("reportContainer"));
        window.print();
    }
<?php
}
?>
})
</script>