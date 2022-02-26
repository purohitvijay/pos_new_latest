<div class="container-fluid">
    
    
    <div class="page-header">


                
        <div class="pull-right" style="margin-right:70px">
            <div style="margin-left:15px" class="right-btn-add pull-right"><button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">Print</button></div>
            <div class="right-btn-add pull-right"> <button type="button" class="btn btn-primary fake-back-class">Back</button></div>
        </div>
        
        <div class="row" id="reportContainer">
        <div style="margin-left:70px">
            <h3>
                <i class="fa fa-table"></i>
                Payment Reference <b><?=$data['payment_reference']?></b> for <b><?=$data['driver']?></b> for date range (<b><?=$data['date_from']?></b> - <b><?=$data['date_to']?></b>)
            </h3>
        </div>
        
           <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    
                        <table style="page-break-after: always;width:90%" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                           
                            <tr>
                                <th style="width:20%">Order No</th>
                                <th style="width:20%">Box</th>
                                <th style="width:20%">Base Commission Amount</th>
                                <th style="width:20%">Quantity</th>
                                <th style="width:20%">Commission</th>
                            </tr>

                            <?php    
                            $custom_line_items = array();
                              
                            foreach ($line_items_data as $row)
                            {        
                                if ($row['type'] == 'custom')
                                {
                                    $custom_line_items[] = array(
                                        'line_item' => $row['line_item'],
                                        'amount' => number_format($row['amount']),
                                    );
                                }
                            }
                          
                            if (!empty($line_items_order_data))
                            {     
                               $previous_type = '';
                               foreach ($line_items_order_data as $index => $row)
                                { 
                                   if ($previous_type <> $row['type'])
                                    {
                                        if ($index > 0)
                                        {
                            ?>            
                                            <tr>
                                                <td colspan="3"><b><?=ucwords($previous_type)?> Summary</b></th>
                                                <td><b><?=$type_wise_box_count?></b></th>
                                                <td><span class='pull-left'><b>$</b>&nbsp;<?=number_format($type_wise_amount,2)?></td>
                                            </tr>
                            <?php
                                        }
                                            
                                        echo "<tr><th colspan='5'>".ucwords($row['type'])."</th></tr>";
                                        $type_wise_box_count = $type_wise_amount = 0;
                                    }  
                                    if($row['type']=='redelivery') {    
                             ?>
                                      <tr>
                                        <td><?=$row['order_number']?></td>
                                        <td><?="--"?></td>
                                        <td><?="--"?></td>
                                        <td><?="--"?></td>
                                        <td><b>$</b>&nbsp;<?=$row['amount']?></td>
                                    </tr>
                                    <?php 
                                    $type_wise_box_count += $row['quantity'];
                                    $type_wise_amount += $row['amount'];
                                    
                                    $previous_type = $row['type'];
                                    ?>
                                    <?php } else { ?>
                                    <tr>
                                        <td><?=$row['order_number']?></td>
                                        <td><?=$row['box']?></td>
                                        <td><?=$row['base_commission']?></td>
                                        <td><?=$row['quantity']?></td>
                                        <td><b>$</b>&nbsp;<?=$row['amount']?></td>
                                    </tr>
                            <?php
                                    $type_wise_box_count += $row['quantity'];
                                    $type_wise_amount += $row['amount'];
                                    
                                    $previous_type = $row['type'];
                                    }
                                }  
                            }
                           
                            
                            if ($type_wise_box_count > 0)   
                            { ?>
                            <?php if($row['type']=="redelivery") { ?>
                                <tr>
                                    <td colspan="3"><b><?=ucwords($row['type'])?> Summary</b></th>
                                    <td><b><?="--"?></b></th>
                                    <td><span class='pull-left'><b>$</b>&nbsp;<?=number_format($type_wise_amount,2)?></td>
                                </tr>
                            <?php } else {?>
                                <tr>
                                    <td colspan="3"><b><?=ucwords($row['type'])?> Summary</b></th>
                                    <td><b><?=$type_wise_box_count?></b></th>
                                    <td><span class='pull-left'><b>$</b>&nbsp;<?=number_format($type_wise_amount,2)?></td>
                                </tr>
                           <?php }
                            }
                           ?>
                             <?php
                             if (!empty($custom_line_items))
                            {
                                foreach ($custom_line_items as $row)
                                {
                            ?>
                                    <tr>
                                        <td colspan="4"><b><?=ucwords($row['line_item'])?></b></th>
                                        <td><b>$</b> <?=number_format($row['amount'], 2)?></th>
                                    </tr>
                            <?php
                                }
                            }
 
                            ?>

                            <tr>
                                <td colspan="3"><b>Grand Total Commission Amount</b></th>
                                <td><b><?=$data['total_boxes']?></b></th>
                                <td><span class='pull-left'><b>$</b>&nbsp;<?=number_format($data['grand_total'],2)?></td>
                            </tr>
                            
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {

    $('.fake-back-class').click(function(){
        window.location.href = "<?=base_url()?>admin/commission/paymentReferenceList";
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
