<div class="container-fluid">
    
    
    <div class="page-header">
        
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
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div>
                        <h3>
                            <i class="fa fa-table"></i>
                            Cash Report as of <b><?=$date?></b>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records))
                    {
                    ?>
                    <!--<div class="box-content nopadding">-->
                    
                            <?php
                            $sno= 1;
                            
                            $grand_total = $total_discrepant_amount = 0;

                            foreach ($records as $type => $row_arr)
                            {
                                if (!empty($row_arr))
                                {
                                $deposit_collected = $voucher_cash_collected = $tot_count_dep_uncollected = $total_active = $total_cancelled =
                                                    $boxes_count = $total_orders_amount =  0;

                                $type = ucwords($type);
                            ?>
                            <table style="page-break-after: always" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                                    <thead>
                                            <?php
                                            if (strtolower($type) == 'delivery')
                                            {
                                            ?>
                                            <tr>
                                                    <th>S.No.</th>
                                                    <th>Date</th>
                                                    <th>Order Number</th>
                                                    <th>Box</th>
                                                    <th>Qty</th>
                                                    <th>Sender</th>
                                                    <th>Contact</th>
                                                    <th>Address</th>
                                                    <th>Driver</th>
                                                    <th>Amt Collected</th>
                                                    <th>Cash Total</th>
                                                    <th>Voucher Total</th>
                                                    <th>Comments</th>
                                            </tr>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                            <tr>
                                                    <th>S.No.</th>
                                                    <th>Date</th>
                                                    <th>Order Number</th>
                                                    <th>Box</th>
                                                    <th>Qty</th>
                                                    <th>Destination</th>
                                                    <th>Sender</th>
                                                    <th>Contact</th>
                                                    <th>Address</th>
                                                    <th>Driver</th>
                                                    <th>Amt to Collect</th>
                                                    <th>Amt Collected</th>
                                                    <th>VA Collected</th>
                                                    <th>Discrepanct Amt</th>
                                                    <th>Cash Total</th>
                                                    <th>Voucher Total</th>
                                                    <th>Comments</th>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                    </thead>

                                    <tbody>
                            <?php
                                echo "<tr><td colspan='17'>$type</td></tr>";
                                foreach ($row_arr as $index => $row)
                                {
                                    if ($row['order_status'] !== 'cancelled')
                                    {
                                        if (strtolower($type) == 'delivery')
                                        {
                                            $deposit_collected += $row['cash_collected_delivery'];
                                            $voucher_cash_collected += $row['voucher_cash_delivery'];

                                            if ($row['cash_collected_delivery'] == 0)
                                            {
                                                $tot_count_dep_uncollected++;
                                            }
                                        }
                                        else
                                        {
                                            $deposit_collected += $row['cash_collected_collection'];
                                            $voucher_cash_collected += $row['voucher_cash_collection'];

                                            if ($row['cash_collected_collection'] == 0)
                                            {
                                                $tot_count_dep_uncollected++;
                                            }
                                        }
                                        
                                        $total_active++;
                                    }
                                    else
                                    {
                                        $total_cancelled++;
                                    }
                                    
                                    $s_start = $row['order_status'] == 'cancelled' ? '<s>' : '';
                                    $s_end = $row['order_status'] == 'cancelled' ? '</s>' : '';
                                    
                                    $boxes_count += $row['quantity'];
                                    
                                    
                                    $address = "{$row['building']}<br/>{$row['unit']}, {$row['block']}<br> {$row['street']} <br>{$row['pin']}";
                                    
                                    if (strtolower($type) == 'delivery')
                                    {
                                        $total = $row['nett_total'];
                                        $total .=  $row['discount'] == 0 ? '' : "<br>({$row['grand_total']} - <span style='color:red'>{$row['discount']}</span>)";
                            ?>
                                    <tr>
                                            <td><?=$s_start. $sno++.$s_end?></td>
                                            <td><?=$s_start. date('d/m/Y', strtotime($row['order_date'])).$s_end?></td>
                                            <td><?=$s_start. ucwords($row['order_number']).$s_end?></td>
                                            <td><?=$s_start. $row['boxes'].$s_end?></td>
                                            <td><?=$s_start. $row['quantity'].$s_end?></td>
                                            <td><?=$s_start. ucwords($row['customer_name']).$s_end?></td>
                                            <td><?=$s_start. $row['mobile'] . '<br>'. $row['residence_phone'].$s_end?></td>
                                            <td><?=$s_start. $address.$s_end?></td>
                                            <td><?=$s_start. ucwords($row['driver_name']).$s_end?></td>
                                            <td><?=$s_start. $row['cash_collected_delivery'].$s_end?></td>
                                            <td><?=$s_start. number_format($row['cash_collected_delivery'], 2).$s_end?></td>
                                            <td><?=$s_start. $row['voucher_cash_delivery'].$s_end?></td>
                                            <td><?=$s_start. $row['comments'].$s_end?></td>
                                    </tr>
                            <?php
                                    }
                                    else
                                    {
                                        $raw_total_amount_collection = number_format($row['nett_total'] - $row['cash_collected_delivery'], 2);
                                        
                                        $total_orders_amount += $raw_total_amount_collection;
                                        
                                        $total = '<b>'.$raw_total_amount_collection.'</b>';
                                        $total .=  $row['discount'] == 0 ? "<br>({$row['grand_total']} - <br> <span style='color:green'>{$row['cash_collected_delivery']}</span>)" : "<br>({$row['grand_total']} - <span style='color:red'>{$row['discount']}</span> - <br> <span style='color:green'>{$row['cash_collected_delivery']}</span>)";
                                    
                                        $cash_collected_delivery = $row['cash_collected_delivery'];
                                        
                                        $disc_amount = number_format($row['nett_total'] - ($row['cash_collected_collection'] + $row['voucher_cash_collection'] + $row['cash_collected_delivery'] + $row['voucher_cash_delivery']), 2);
                                        
                                        $total_discrepant_amount += $disc_amount;
                            ?>
                                    <tr>
                                            <td><?=$s_start. $sno++.$s_end?></td>
                                            <td><?=$s_start. date('d/m/Y', strtotime($row['order_date'])).$s_end?></td>
                                            <td><?=$s_start. ucwords($row['order_number']).$s_end?></td>
                                            <td><?=$s_start. $row['boxes'].$s_end?></td>
                                            <td><?=$s_start. $row['quantities'].$s_end?></td>
                                            <td><?=$s_start. $row['locations'].$s_end?></td>
                                            <td><?=$s_start. ucwords($row['customer_name']).$s_end?></td>
                                            <td><?=$s_start. $row['mobile'] . '<br>'. $row['residence_phone'].$s_end?></td>
                                            <td><?=$s_start. $address.$s_end?></td>
                                            <td><?=$s_start. ucwords($row['driver_name']).$s_end?></td>
                                            <td><?=$s_start. $total.$s_end?></td>
                                            <td><?=$s_start. $row['cash_collected_collection'].$s_end?></td>
                                            <td><?=$s_start. $row['voucher_cash_collection'].$s_end?></td>
                                            <td><?=$s_start. $disc_amount.$s_end?></td>
                                            <td><?=$s_start. number_format($row['cash_collected_collection'] + $cash_collected_delivery, 2).$s_end?></td>
                                            <td><?=$s_start. number_format($row['voucher_cash_collection'] + $row['voucher_cash_delivery'], 2).$s_end?></td>
                                            <td><?=$s_start. $row['comments'].$s_end?></td>
                                    </tr>
                            <?php
                                    }
                                }
                                echo "</tbody>"
                                . "</table>";
                                
                                ?>
                                <div>
                                    <h3>
                                        <i class="fa fa-table"></i>
                                        Summary
                                    </h3>
                                </div>
                                
                                <?php
                                
                                $total_dep_to_be_collected = $boxes_count * 10;
                                if (strtolower($type) == 'delivery')
                                {
                                    $grand_total += $deposit_collected;
                                ?>
                                <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always">
                                    <tr>
                                        <th colspan="3" style='width:80%'>Total Deposit Collected</th>
                                        <td style='width:20%'>$ <b><?=number_format($deposit_collected, 2)?></b></td>
                                    </tr>
                                    <tr>
                                        <td style='width:30%'><b>Total Orders</b></th>
                                        <th style='width:20%'><?=($index+1);?></td>
                                        <td style='width:30%'><b>Total Boxes</b></th>
                                        <th style='width:20%'><?=$boxes_count;?></td>
                                    </tr>
                                </table>
                                <?php
                                }
                                else
                                {
                                    $grand_total += $deposit_collected;
                                ?>
                                <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always">
                                    <tr>
                                        <th style='width:20%'>Total Amount to Collect</th>
                                        <td style='width:5%'>$ <b><?=number_format($total_orders_amount, 2)?></b></td>
                                        <th style='width:20%'>Total Cash Collected</th>
                                        <td style='width:5%'>$ <b><?=number_format($deposit_collected, 2)?></b></td>
                                        <th style='width:20%'>Total Voucher Collected</th>
                                        <td style='width:5%'>$ <b><?=number_format($voucher_cash_collected, 2)?></b></td>
                                        <th style='width:20%'>Total Discrepancy</th>
                                        <td style='width:5%'>$ <b><?=number_format($total_discrepant_amount, 2)?></b></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="2" style='width:30%'><b>Total Orders</b></th>
                                        <th colspan="2" style='width:20%'><?=($index+1);?></td>
                                        <td colspan="2" style='width:30%'><b>Total Boxes</b></th>
                                        <th colspan="2" style='width:20%'><?=$boxes_count;?></td>
                                    </tr>
                                </table>
                                <?php
                                }
                            }
                            ?>
                            <br>
                    </div>
                    <!--</div>-->
                    <?php
                            }
                    ?>
                                
                            <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y "  style="width:98%;page-break-after: always">
                                <tr>
                                    <th style='width:80%'>Grand Total Amount Collected</th>
                                    <td style='width:20%'>$ <b><?=number_format($grand_total, 2)?></b></td>
                                </tr>
                            </table>
                    <?php
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
<script type="text/javascript">
$(document).ready(function () {
<?php
if (!empty($records))
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