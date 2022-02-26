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
                                Container Type : <b><?= $shipment_batch['container_type'] ?></b> | Shipment Batch : <b><?= $shipment_batch['batch_name'] ?> |</b> Exchange Rate : <b><?= set_locale_money_format($shipment_batch['exchange_rate']) ?></b> |</b> Shipment Reference : <b><?= $shipment_batch['shipment_reference'] ?> </b>
                            </td>
                        </tr>                        
                        <tr>
                            <th style="width:15%">ITEM</th>
                            <th style="width:15%">QUANTITY</th>
                            <th style="width:20%">LOCAL ($)</th>
                        </tr>                                    
                        <tr>
                            <td style="width:15%"><b>BOX QUANTITY:</b></td>
                        </tr>                                     
                        <tr>
                            <td style="width:15%">POSTKI</td>
                            <td style="width:15%"><?= set_locale_money_format(($shipment_batch["records_count"]) ? $shipment_batch["records_count"] : "0" )?></td>
                            <td style="width:15%"><?= set_locale_money_format(($shipment_batch["total_seles"]) ? $shipment_batch["total_seles"] - $shipment_batch["Saftri_total_seles"] : "0") ?></td>
                        </tr>
                        <tr>
                            <td style="width:15%">SAFTRI</td>
                            <td style="width:15%"><?= set_locale_money_format(($shipment_batch["Saftri_records_count"]) ? $shipment_batch["Saftri_records_count"] : "0") ?></td>
                            <td style="width:15%"><?= set_locale_money_format(($shipment_batch["Saftri_total_seles"]) ? $shipment_batch["Saftri_total_seles"] : "0") ?></td>
                        </tr>
                        <tr>
                            <th><b>TOTAL</b></th> 
                            <th><b><?= ($shipment_batch["total_records_count"]) ? $shipment_batch["total_records_count"] : "0" ?></b></th>
                            <th><b><?= set_locale_money_format(($shipment_batch["total_seles"]) ? $shipment_batch["total_seles"] : "0") ?></b></th>
                        </tr>
                         <tr>
                            <td style="width:15%"><b>Trade Discount:</b></td>
                            <td style="width:15%"></td>
                            <td style="width:15%"><b><?= set_locale_money_format(($shipment_batch["discount"]) ? $shipment_batch["discount"] : "0") ?></b></td>
                        </tr>  
                        <?php
                        $previous_section = '';
                        if (!empty($data)) 
                        {
                            $grand_total_in_local_currency = ($shipment_batch["total_seles"]) ? $shipment_batch["total_seles"] : "0";
                            $grand_total_in_local_currency -= ($shipment_batch["discount"]) ? $shipment_batch["discount"] : "0";
                            $subtotal_in_local_currency = 0;
                            $quantity = "";

                            foreach ($data as $index => $record) 
                            {
                                if ($previous_section != $record['section']) 
                                {
                                    if ($index > 0) 
                                    {
                                        echo "<tr>"
                                        . "<td><b>SUBTOTAL</b></td>"
                                        . "<td>$quantity</td>"
                                        . "<td>" . set_locale_money_format($subtotal_in_local_currency) . "</td>"
                                        . "</tr>";

                                        $subtotal_in_local_currency = 0;
                                        $quantity = "";
                                    }

                                    echo "<tr><th colspan='5'>{$record['section']}</td></tr>";
                                }
                                
                                echo ""
                                . "<td>{$record['line_item']}</td>"
                                . "<td>" . $record['quantity'] . "</td>"
                                . "<td>" . set_locale_money_format($record['local_currency_amount']) . "</td>"
                                . "";

                                $subtotal_in_local_currency += $record['local_currency_amount'];
                                if($record['quantity'])
                                    $quantity += $record['quantity'];
                                else
                                    $quantity = "";

                                $grand_total_in_local_currency -= $record['local_currency_amount'];

                                echo "</tr>";

                                $previous_section = $record['section'];
                            }

                            echo "<tr>"
                            . "<td><b>SUBTOTAL</b></td>"
                            . "<td>".$quantity."</td>"
                            . "<td class='local_sub_total_fake_class'>" . set_locale_money_format($subtotal_in_local_currency) . "</td>"
                            . "</tr>";

                            echo "<tr>"
                            . "<td><b>TOTAL</b></td>"
                            . "<td></td>"
                            . "<td id='foreign_grand_total_fake_class'>" . set_locale_money_format($grand_total_in_local_currency) . "</td>"
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

        $('.fake-back-class').click(function () {
            window.location.href = "<?= base_url() ?>admin/shipmentcost/shipmentCostingReportList";
        })

<?php
if (!empty($data)) {
    ?>
            document.getElementById("btnPrint").onclick = function () {
                printElement(document.getElementById("reportContainer"));
                window.print();
            }
    <?php
}
?>
    })
</script>