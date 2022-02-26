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
                                <th style="width:55%">Operation</th>
                                <th style="width:15%">Base Amount</th>
                                <th style="width:15%">Box Count</th>
                                <th style="width:15%">Commission Amount</th>
                            </tr>
                         <!--<div class="box-content nopadding">-->

                            <?php
                            $previous_item_id = '';
                            $total_boxes = $grand_total_boxes = 0;
                         
                            $custom_line_items = array();
                            $print_res = false;
                              
                            foreach ($line_items_sum_data as $sum_row)
                            {       
                                $total_boxes = 0;
                                $count = 1;
                                foreach ($line_items_data as $row)
                                {   
                                     if ($row['type'] == 'custom')
                                    {
                                        $custom_line_items[$row['id']] = array(
                                            'line_item' => $row['line_item'],
                                            'amount' => number_format($row['amount']),
                                        );
                                    }
                                    else
                                    {
                                         if ($row['line_item'] == $sum_row['line_item'])
                                        {
                                              
                                           $total_boxes += $row['count'];
                                             
                                           if($row['operation']=='redelivery')
                                            {
                                              if($print_res == false) {
                                               ?>
                                                <tr>
                                                 <th colspan="4">Redelivery</th>
                                                </tr>
                                             <?php   
                                             }   $print_res = true;
//                                           ?>
                                             <tr>
                                                <td><?=$row['line_item']?></th>
                                                <td><?="--"?></th>
                                                <td><?="--"?></th>
                                                <td><b>$</b> <?=number_format($row['amount'], 2)?></th>
                                            </tr>
                                            <?php } else { ?>
                                            <tr>
                                                <td><?=ucwords($row['operation'])?></th>
                                                <td><?=$row['base_commission']?></th>
                                                <td><?=$row['count']?></th>
                                                <td><b>$</b> <?=number_format($row['amount'], 2)?></th>
                                            </tr>
                                           <?php 
                                           }
                                        }
                                    }
                                    $count++;
                                }
                                
                                $grand_total_boxes += $total_boxes;
                                if(is_numeric($sum_row['line_item']))
                                {
                                }
                                else
                                {
                            ?>
                                   <tr>
                                    <th colspan="2"><?=ucwords($sum_row['line_item'])?></th>
                                    <th><?=$total_boxes?></th>
                                    <th>$ <?=$sum_row['total_amount']?></th>
                                  </tr>            
                                     <?php           
                                }
                            }
                           if (!empty($custom_line_items))
                            {  
                                foreach ($custom_line_items as $row)
                                {  
                            ?>
                                    <tr>
                                        <td colspan="3"><?=ucwords($row['line_item'])?></th>
                                        <td><b>$</b> <?=number_format($row['amount'], 2)?></th>
                                    </tr>
                            <?php
                                }
                            }
                         ?>
                                 <tr id="grandTotalRow">
                                    <td colspan="2"><b>Grand Total Commission Amount</b></th>
                                    <td><b><?=$grand_total_boxes?></b></th>
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