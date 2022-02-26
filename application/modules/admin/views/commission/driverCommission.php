<div class="container-fluid">
    
    
    <div class="page-header">

        
        <div>
            <h3>
                <i class="fa fa-table"></i>
                Commission Report
            </h3>
        </div>

        <div class="row">
            <form action="<?=base_url()?>admin/commission/driverCommission" method="post">
                <div class="pull-left form-group">
                        <label for="date_from" class="control-label pull-left">
                            From Date
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="date_from" id="date_from" class="form-control big datepick" required value='<?=$date_from?>'>
                            </div>    
                        </div>
                        <label for="date_to" class="control-label pull-left">
                            &nbsp;&nbsp;To Date
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="date_to" id="date_to" class="form-control big datepick" required value='<?=$date_to?>'>
                            </div>    
                        </div>
                        <?php
                        if (!empty($drivers))
                        {  
                        ?>
                        <label for="drivers" class="control-label pull-left" style="padding-left:10px">
                            Drivers
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <select id="drivers" name="driver_id" class="form-control">
                                    <?php  
                                    $driver_names = array();
                                    $driver_name = '';
                                    foreach ($drivers as $index => $row)
                                    {
                                        $selected = $row['id'] == $driver_id ? 'Selected' : '';
                                        $driver_name = $row['id'] == $driver_id ? $row['name'] : $driver_name;
                                    ?>
                                        <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>    
                        </div>
                        
                        <?php
                        }
                        ?>
                        <label for="payment_reference" class="control-label pull-left">
                            &nbsp;&nbsp;Payment Reference
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control big" required value='<?=$payment_reference?>'>
                            </div>    
                        </div>
                    
                        <div class="pull-left" style="padding-left:10px">
                            <button type="submit" class="btn btn-primary" >Report</button>
                            <button type="button" class="btn btn-primary fake-back-class">Back</button>
                        </div>
                </div>

                <div class="pull-right">
                    <?php
                    if (!empty($data['header']))
                    {
                    ?>
                        <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-print"></i>Print
                        </button>
                    <?php             
                    }
                    ?>
                </div>
            </form>
        </div>
        
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <?php
//                p($data);
                if (empty($data))
                {
                ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?=$message?>
                    </div>
                <?php
                }
                else
                {
                ?>
                <div class="box box-color box-bordered">
                        <br><br>
                        
                        <table style="page-break-after: always;width:90%" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                            <tr>
                                <td colspan="3">
                                    <div class="pull-right">
                                        <button id="btnAdd" class="btn btn-primary">
                                            <i class="fa fa-plus"></i>Add Line Item
                                        </button>
                                    </div>
                                </th>
                            </tr>
                        </table>
<form action="<?=base_url()?>admin/commission/driverCommission" method="post" id="commissionForm">

                        <input type="hidden" name="employee_id" value="<?=$driver_id?>">
                        <input type="hidden" name="date_from" value="<?=$formatted_date_from?>">
                        <input type="hidden" name="date_to" value="<?=$formatted_date_to?>">
                        <input type="hidden" name="payment_reference" value="<?=$payment_reference?>">
                        <input type="hidden" name="data" value="<?=base64_encode(serialize($data))?>">
                        
                         <table style="page-break-after: always;width:90%" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                            <tr>
                                <td style="text-align: center" colspan="3">
                                    Driver Name : <b><?=$driver_name?> |</b> Date Range : <b><?=$date_from?></b> to <b><?=$date_to?> |</b> Payment Reference : <b><?=$payment_reference?> </b>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:55%">Operation</th>
                                <th style="width:15%">Base Amount</th>
                                <th style="width:15%">Box Count</th>
                                <th style="width:15%">Commission Amount</th>
                            </tr>
                        <!--<div class="box-content nopadding">-->

                                <?php    
                                $orders = array();

                                $sno= 1;
                                                    
                                $grand_total_commission_amount = $grand_total_boxes = 0;
                                                    
                                $line_item_index =   0;
                                $show_re = false;
                                foreach ($data as $box_type => $operation_wise_records)
                                {    
                                    list($box_type,$collection_commission_base_amount, $delivery_commission_base_amount) = explode('@@##@@', $box_type);
                                    $header_displayed = false;
                                    $amt =0;  
                                     foreach ($operation_wise_records as $operation => $records)
                                    {  
                                       if($operation == "redelivery") {
                                         foreach($records as $idx => $values)
                                          {
                                              if(!empty($values['commission_amount'])) 
                                                  {
                                                       $Total_comm_amt = $amt += $values['commission_amount'];
                                                  }
                                          }        
                                       foreach($records as $idx => $res)
                                       {
                                           $order_no = $res['order_number'];
                                           if(!empty($res['commission_amount'])) 
                                               {
                                                $rets = $res['commission_amount'] + $res['commission_amount'];  
                                               }
                                       }
                                       }
                                        if (!empty($records))
                                        {
                                            $total_boxes = 0;
                                            $already_accommodated_boxes =  0;
                                            foreach ($records as $row)
                                            {  
                                                echo "<input type='hidden' name='line_item_orders_mapping[{$row['order_number']}##$operation##{$row['box_ids']}]' value='$line_item_index'>";
                                                
                                                switch ($operation)
                                                {
                                                    case 'delivery':
                                                        $orders[] = $row['order_number'];
                                                        $total_boxes += $row['quantity'];
                                                        break;
                                                    
                                                    case 'collection':
                                                        $orders[] = $row['order_number'];
                                                        $total_boxes += $row['quantity'];
                                                        break;
                                                    case 'redelivery':
                                                        $total_boxes += $row['quantity'];
                                                        break;
                                                    default:
                                                        if (in_array($row['order_number'], $orders))
                                                        {
                                                            $already_accommodated_boxes +=  $row['original_quantity'];
                                                        }
                                                        else
                                                        {
                                                            $total_boxes += $row['quantity'];
                                                        }
                                                        break;
                                                }
                                            }
                                            
                                            switch ($operation)
                                            {
                                                case 'delivery':
                                                    $commission_amount = $delivery_commission_base_amount * $total_boxes;
                                                    $commission_base_amount = number_format($delivery_commission_base_amount, 2);
                                                    break;
                                                
                                                case 'collection':
                                                    $commission_amount = $collection_commission_base_amount * $total_boxes;
                                                    $commission_base_amount = number_format($collection_commission_base_amount, 2);
                                                break;
                                                case 'redelivery':
                                                      $commission_amount = $Total_comm_amt;
                                                break;
                                                default:
//                                                    if ($already_accommodated_boxes > 0 && $total_boxes > 0)
//                                                    {
//                                                        $commission_amount = $redelivery_amount * ($total_boxes-$already_accommodated_boxes);
//                                                    }
//                                                    else if ($already_accommodated_boxes > 0)
//                                                    {
//                                                        $commission_amount = $redelivery_amount * ($already_accommodated_boxes);
//                                                    }
//                                                    else
//                                                    {
                                                        $commission_amount = $redelivery_amount * ($total_boxes);
//                                                    }
                                                
                                                    
                                                    $commission_base_amount = $redelivery_amount;
                                                    break;
                                            }
                                           $grand_total_commission_amount += $commission_amount;
                                                
                                          if ($total_boxes > 0)
                                            { 
                                               
                                              if($operation=='redelivery')
                                                {
                                                   $commission_base_amount = "";
                                                   $total_boxes = "";
                                                   $box_type= $order_no;
                                                    if(!empty($Total_comm_amt))
                                                   {
                                                     $commission_amount = $Total_comm_amt; 
                                                   }
                                                   (empty($commission_amount) ? '--' : '$ '. number_format($commission_amount, 2));
                                                }
                                                elseif($header_displayed === false)
                                                {
                                                    echo "<tr><th colspan='4'>$box_type</th></tr>";
                                                    $header_displayed = true;
                                                }
                                                $grand_total_boxes += $total_boxes;
                                          ?>
                                            <input type="hidden" name="base_line_item[]" value="<?=$box_type?>">
                                            <input type="hidden" name="base_line_item_operation[]" value="<?=$operation?>">
                                            <input type="hidden" name="base_line_item_id[]" value="<?=11?>">
                                            <input type="hidden" name="base_line_base_commission[]" value="<?=$commission_base_amount?>">
                                            <input type="hidden" name="base_line_item_commission[]" value="<?=$commission_amount?>">
                                            <tr> 
                                               <?php  
                                               if($operation=='redelivery') {
                                              ?>
                                                <?php if($show_re == false) { ?>
                                                <tr>
                                                    <th colspan="4">Redelivery</th>
                                                </tr>
                                                <?php
                                                $show_re = true;
                                                }?>
                                                    <td><?php echo $row['order_number'];?></td>
                                                <?php   } else { ?>
                                                     <td><?=ucwords($operation)?></th>
                                               <?php }   ?>
                                                 <td><?=empty($commission_base_amount) ? '--' : '$ '. number_format($commission_base_amount, 2)?></th>
                                                <td>
                                                 <?php 
                                                    if ($already_accommodated_boxes > 0)
                                                    {
                                                        echo "$total_boxes (<del>$already_accommodated_boxes</del>)";
                                                        echo "<input type='hidden' name='base_line_item_operation_count[]' value='$total_boxes'>";
                                                    }
                                                    else
                                                    {
                                                        echo "<input type='hidden' name='base_line_item_operation_count[]' value='$total_boxes'>";
                                                        echo (empty($total_boxes) ? '--' :  number_format($total_boxes, 2));
                                                    }   
                                                    ?>
                                                </th>
                                                <td><b>$</b> <?=number_format($commission_amount, 2)?></th>
                                            </tr>
                                        <?php
                                            }
                                         }
                                         ?>

                        <!--</div>-->
                        <?php           
                                    $line_item_index++;
                                }
                               }
                             ?>
                                   <input type="hidden" name="total_boxes" value="<?=$grand_total_boxes?>">
                                    <tr id="grandTotalRow">
                                        <td colspan="2"><b>Grand Total Commission Amount</b></th>
                                        <td><b><?=$grand_total_boxes?></b></th>
                                        <td>
                                            <span class='pull-left'><b>$</b>&nbsp;</span>
                                            <input id="to_be_posted_amount" type="hidden" name="grand_total_commission_amount" value="<?=$grand_total_commission_amount?>">
                                            <input id="original_amount" type="hidden" value="<?=number_format($grand_total_commission_amount,2)?>">
                                            <span id="grand_total_holder" class='pull-left'><?=number_format($grand_total_commission_amount,2)?></span>
                                        </td>
                                    </tr>
                                </table>
                        
                                <div class="form-actions" style="margin:auto;text-align: center">
                                    <input type="reset" class="btn fake-back-class" value="Reset" id="back">
                                    <input type="submit" class="btn btn-primary" value="Submit" id="submitBtn">
                                </div>
                        <?php
                    }
                   ?>
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
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2,
    });

    function update_grand_total()
    {
//        grand_total_holder original_amount to_be_posted_amount
        $('.fake-line-item-class').each(function(index, val){
            tmpVal = parseFloat($('#original_amount').val()) + parseFloat($(this).val());
        
            formatterVal = formatter.format(tmpVal);
            formatterVal = formatterVal.split('$')
            formatterVal = formatterVal[1];
            $('#to_be_posted_amount').val(tmpVal)
            $('#grand_total_holder').html(formatterVal)
        })
    }
    
    $('body').on('change', '.fake-line-item-class', function (){
//        grand_total_holder
        tmpVal = parseFloat($('#to_be_posted_amount').val()) + parseFloat($(this).val());
        formatterVal = formatter.format(tmpVal);
        formatterVal = formatterVal.split('$')
        formatterVal = formatterVal[1];
        $('#to_be_posted_amount').val(tmpVal)
        $('#grand_total_holder').html(formatterVal)
    })
    
    $('#btnAdd').click(function(){
        html = "<tr>\n\
                    <td colspan='3'><button style='margin-right:10px' class='pull-left btn btn-primary fake-class-remove'><i class='fa fa-minus'></i></button><input style='width:400px;'  class='pull-left form-control' type='text' required name='line_item[]' placeholder='Enter Line Item Name'></td>\n\
                    <td><span class='pull-left'><b>$</b>&nbsp;</span> <input style='width:100px'  pattern='[+-][0-9.,]+([0-9]+)?' type='number' class='form-control fake-line-item-class' name='line_item_amount[]' placeholder='Amount' required></td>\n\
                </tr>";
        $(html).insertBefore( "#grandTotalRow" );
        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
    })
    
    $('#commissionForm').submit(function (event){
        event.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#loadingDiv_bakgrnd').show();
        data = $('#commissionForm').serializeObject();
 
        $.ajax({
            data:data,
            url: "<?=base_url()?>admin/commission/saveCommission",
            type:'POST',
        })
        .done(function( response ) {
            alert('Commission record saved successfully.');
            window.location.href = "<?=base_url()?>admin/commission/driverCommission";
            $('#loadingDiv_bakgrnd').hide();
        });
    })
    
    $('.fake-back-class').click(function(){
        window.location.href = "<?=base_url()?>admin/commission/paymentReferenceList";
    })
    
    $('body').on('click', '.fake-class-remove', function (){
        
        if (confirm('Are you sure you want to delete this line item?') == true)
        {
            $(this).closest('tr').remove();
            update_grand_total();
        }
    })
    
    $('.datepick').datepicker({
        format: "dd/mm/yyyy"
    })
})
</script>