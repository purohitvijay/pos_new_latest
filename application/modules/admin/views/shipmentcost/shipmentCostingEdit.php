<script type="text/javascript">  
$(document).ready(function(){
    $('.datepick').datepicker({
        format: "dd/mm/yyyy"
    })
})
</script>

<div class="container-fluid">
    
    <div class="page-header">
        
        <div>
            <h3>
                <i class="fa fa-table"></i>
                Edit Shipment Batch Costing
            </h3>
        </div>
        <?php
        if (empty($shipment_batches))
        {
            echo "No active shipment batches found.";
        }
        else
        {
        ?>

        <div class="row">
            <form action="<?=base_url()?>admin/shipmentcost/shipmentPaymentCost" method="post">
                <div class="pull-left form-group">
                        <label for="shipment_batches" class="control-label pull-left" style="padding-left:10px">
                            Shipment Batches
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group'>
                                <b><?=$shipment_record['batch_name']?></b>&nbsp;
                            </div>    
                        </div>
                    
                        <label for="date" class="control-label pull-left">
                            Date
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="date" id="date" class="form-control big datepick" disabled required value='<?=$formatted_date?>'>
                            </div>    
                        </div>
                        
                        <label for="payment_reference" class="control-label pull-left">
                            &nbsp;&nbsp;Exchange Rate
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group'>
                                <input step="0.01" type="number" name="exchange_rate" id="exchange_rate" class="form-control big" required value='<?=$exchange_rate?>'>
                            </div>    
                        </div>
                    
                        <label for="payment_reference" class="control-label pull-left">
                            &nbsp;&nbsp;Payment Reference
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control big" disabled required value='<?=$payment_reference?>'>
                            </div>    
                        </div>
                    
                        <div class="pull-left" style="padding-left:10px">
                            <button type="submit" class="btn btn-primary" >Report</button>
                            <button type="button" class="btn btn-primary fake-back-class">Back</button>
                        </div>
                        <?php
                        if (!empty($data))
                        {
                        ?>
                        <div class="pull-right" style="padding-left:10px">
                            <button type="button" id="update_total_button" class="btn btn-primary">Update Totals</button>
                        </div>
                        <?php
                        }
                        ?>
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
                        <br>
                        <form action="<?=base_url()?>admin/shipmentcost/saveShipmentBatchCost" method="post" id="shipmentCostingForm">
        
                        <input type="hidden" name="shipment_batch_id" value="<?=$shipment_batch_id?>">
                        <input type="hidden" name="shipment_cost_master_id" value="<?=$shipment_cost_master_id?>">
                        <input type="hidden" name="date" value="<?=$date?>">
                        <input type="hidden" name="exchange_rate" value="<?=$exchange_rate?>" id="exchange_rate_save_form">
                        <input type="hidden" name="payment_reference" value="<?=$payment_reference ?>">
                        <input type="hidden" name="data" value="<?=base64_encode(serialize($data))?>">
                        
                        <table style="page-break-after: always;" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                            <tr>
                                <td style="text-align: center" colspan="5">
                                    Shipment Batch : <b><?=$shipment_record['batch_name']?></b> | Date : <b><?=$formatted_date?> |</b> Exchange Rate : <b id="exchange_rate_text"><?=$exchange_rate?></b> |</b> Payment Reference : <b><?=$payment_reference?> </b>
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
                                $has_luar_jawa = false;
                                
                                $dist_subtotal_in_foreign_currency = $dist_subtotal_in_local_currency = 
                                        $grand_total_in_foreign_currency = $grand_total_in_local_currency = 0;
                                
                                
                                $section = 'DISTRIBUTION:(overseas)';

                                $subtotal = 0;

                                echo "<tr><th colspan='5'>$section</td></tr>";
                                foreach ($data as $index => $location_record)
                                {
                                    if ($location_record['section'] <> $section) continue;
                                    echo '<tr>';
                                    $box_wise_string = '';
                                    
                                    $quantity = 0;
                                    
                                   $id = $location_record['id'];
                                   $section = $location_record['section'];
                                   $line_item = $location_record['line_item'];
                                   $description = $location_record['description'];
                                   $count = $location_record['count'];
                                   $local_currency_ammount = $location_record['local_currency_amount'];
                                   $foreign_currency_amount = $location_record['foreign_currency_amount'];
                                   $master_data_reference = $location_record['master_data_reference'];
                                   $shipment_cost_master_id = $location_record['shipment_cost_master_id'];
                                   $type = $location_record['type'];
                                    
                                    if ($type == 'custom')
                                    {
                                        $htmlVal = "<input type='text' class='location_fake_class' id='fake_location_text_$index' value='$foreign_currency_amount' exchange_type='$type'>";
                                    }
                                    else
                                    {
                                        $htmlVal = $foreign_currency_amount."<input type='hidden' id='fake_location_text_$index' class='location_fake_class' value='$foreign_currency_amount' exchange_type='$type'>";
                                    }
                                    
                                    echo ""
                                    . "<td>$line_item</td>"
                                    . "<td>$description</td>"
                                    . "<td>$count</td>"
                                    . "<td class='location_currency_fake_class'>".$local_currency_ammount."</td>"
                                    . "<td>".$htmlVal.
                                            "<input type='hidden' name='line_item[]' value='$line_item'>".
                                            "<input type='hidden' name='description[]' value='$description'>".
                                            "<input type='hidden' name='count[]' value='$count'>".
                                            "<input type='hidden' name='local_currency_amount[]' class='local_currency_fake_class local_exchange_fake_class' value='$local_currency_ammount'>".
                                            "<input type='hidden' name='master_data_reference[]' value='$master_data_reference'>".
                                            "<input type='hidden' id='fake_location_hidden_$index' name='foreign_currency_amount[]' value='$foreign_currency_amount'>".
                                            "<input type='hidden' name='section[]' value='$section'>".
                                            "<input type='hidden' name='id' value='$id'>".
                                            "<input type='hidden' name='type' value='$type'>".
                                            
                                     "</td>";
                                    $subtotal += $count;
                                    $dist_subtotal_in_foreign_currency += $foreign_currency_amount;
                                    $dist_subtotal_in_local_currency += $local_currency_ammount;
                                
                                    echo "</tr>";
                                }
                                
                                echo "<tr>"
                                . "<td>&nbsp;</td>"
                                . "<td><b>SUBTOTAL</b></td>"
                                . "<td>$subtotal</td>"
                                . "<td class='local_sub_total_fake_class' id='location_local_subtotal'>".$dist_subtotal_in_local_currency."</td>"
                                . "<td class='foreign_sub_total_fake_class' id='location_foreign_subtotal'>".$dist_subtotal_in_foreign_currency."</td>"
                                . "</tr>";

                                $grand_total_in_foreign_currency += $dist_subtotal_in_foreign_currency;
                                $grand_total_in_local_currency += $dist_subtotal_in_local_currency;
                                
                                $subtotal = 0;
                                
                                if (!empty($location_record['section']))
                                {
                                    $section = 'SPECIAL BOXES';
                                    
                                    echo "<tr><th colspan='5'>$section</td></tr>";
                                    
                                    $special_pack_cost = empty($masters_data['special_pack']['Special Pack']) ?
                                                        0.0 : $masters_data['special_pack']['Special Pack'];

                                    $spcl_subtotal_in_foreign_currency = 0;
                                    $spcl_subtotal_in_local_currency = 0;

                                    foreach ($data as $index => $row)
                                    {
                                        
                                        if ($row['section'] <> $section) continue;
                                    
                                        $special_pack_cost = $row['foreign_currency_amount'];
                                        $local_cost = $row['local_currency_amount'];
                                        $id = $row['id'];
                                        $type = $row['type'];
                                        $spcl_subtotal_in_foreign_currency += $special_pack_cost;
                                        $spcl_subtotal_in_local_currency += $local_cost;
                                                
                                        echo "<tr>";

                                        echo "<td>{$row['line_item']}</td>"
                                        . "<td>{$row['description']}</td>"
                                        . "<td>{$row['count']}</td>"
                                        . "<td class='special_pack_currency_fake_class'>"
                                                . "<input type='text' class='local_exchange_fake_class user_defined_local_exchange_fake_class' name='local_currency_amount[]' value='{$row['local_currency_amount']}'></td>"
                                        . "<td>"
                                                . "<input class='special_pack_fake_class text_foreign_currency_fake_class' id='foreign_currency_text_$index' type='text' value='".$special_pack_cost."'>"
                                                ."<input type='hidden' name='line_item[]' value='{$row['line_item']}'>"
                                                ."<input type='hidden' name='description[]' value='{$row['description']}'>"
                                                ."<input type='hidden' name='count[]' value='{$row['count']}'>"
                                                ."<input type='hidden' name='master_data_reference[]' value='$special_pack_cost'>"
                                                ."<input type='hidden' id='foreign_currency_hidden_$index' name='foreign_currency_amount[]' value='".$special_pack_cost."'>"
                                                ."<input type='hidden' name='section[]' value='$section'>"
                                                ." <input type='hidden' name='id' value='$id'>"
                                                ." <input type='hidden' name='type' value='$type'>"
                                        . "</td>"
                                        . "";
                                                
                                        $subtotal += $row['count'];
                                    }
                                    
                                    echo "<tr>"
                                    . "<td>&nbsp;</td>"
                                    . "<td><b>SUBTOTAL</b></td>"
                                    . "<td>$subtotal</td>"
                                    . "<td class='local_sub_total_fake_class' id='special_pack_local_subtotal'>".$spcl_subtotal_in_local_currency."</td>"
                                    . "<td class='foreign_sub_total_fake_class' id='special_pack_foreign_subtotal'>".$spcl_subtotal_in_foreign_currency."</td>"
                                    . "</tr>";
                                    
                                    $grand_total_in_foreign_currency += $spcl_subtotal_in_foreign_currency;
                                    $grand_total_in_local_currency += $spcl_subtotal_in_local_currency;
                                }
                                
                                if (!empty($masters_data['freight']))
                                {
                                    $freight_subtotal_in_foreign_currency = 0;
                                    $freight_subtotal_in_local_currency = 0;

                                    $index = 0;
                                    
                                    $section = 'FREIGHT:(overseas)';
                                    
                                    echo "<tr><th colspan='5'>$section</td></tr>";
                                    
                                    foreach ($data as $index => $row)
                                    {
                                        if ($row['section'] <> $section) continue;
                                        
                                        $uniqid = uniqid();
                                                
                                        $cost = $row['foreign_currency_amount'];
                                        $local_cost = $row['local_currency_amount'];
                                        $id = $row['id'];
                                        
                                        $freight_subtotal_in_foreign_currency += $cost;
                                        $freight_subtotal_in_local_currency += $local_cost;
                                        
                                        $type = $row['type'];
                                        
                                        if ($row['line_item'] == 'Handling Luar Jawa') //Did so because Imran feed backed it would follow normal currency
                                        {
                                            $local_host_html =  "<input type='text' class='local_exchange_fake_class user_defined_local_exchange_fake_class ' name='local_currency_amount[]' value='$local_cost'>";
                                        }
                                        else
                                        {
                                            $local_host_html =  "$local_cost<input type='hidden' class='local_exchange_fake_class' name='local_currency_amount[]' value='$local_cost'>";
                                        }
                                            
                                        if ($row['type'] == 'custom')
                                        {
                                            $htmlhandling = "<input type='text' class='freight_fake_class' name='foreign_currency_amount' value='$cost' exchange_type='$type'>";
                                        }
                                        else
                                        {
                                            $htmlhandling = $cost . "<input type='hidden'class='freight_fake_class' name='foreign_currency_amount' value='$cost' exchange_type='$type'>";
                                        }
                                        
                                        echo "<tr>";
                                        echo ""
                                        . "<td>{$row['line_item']}</td>"
                                        . "<td>&nbsp;</td>"
                                        . "<td>&nbsp;</td>"
                                        . "<td class='freight_currency_fake_class'>$local_host_html</td>"
                                        . "<td>".$htmlhandling.
                                                
                                       
                                        "<input type='hidden' name='line_item[]' value='{$row['line_item']}'>".
                                        "<input type='hidden' name='description[]' value=''>".
                                        "<input type='hidden' name='count[]' value=''>".
//                                        "<input type='hidden' id='foreign_currency_hidden_$uniqid' name='foreign_currency_amount[]' value='$cost'>".
                                        "<input type='hidden' name='master_data_reference[]' value='$cost'>".
                                        "<input type='hidden' name='id' value='$id'>".
                                        "<input type='hidden' name='type' value='$type'>".
                                        "<input type='hidden' name='section[]' value='$section'>";
                                        
                                        echo "</td>"
                                        . "<tr>";
                                    }
                                    
                                    echo "<tr>"
                                    . "<td>&nbsp;</td>"
                                    . "<td>&nbsp;</td>"
                                    . "<td><b>SUBTOTAL</b></td>"
                                    . "<td id='freight_local_subtotal' class='local_sub_total_fake_class'>".$freight_subtotal_in_local_currency."</td>"
                                    . "<td id='freight_foreign_subtotal'class='foreign_sub_total_fake_class'>".$freight_subtotal_in_foreign_currency."</td>"
                                    . "</tr>";
                                    
                                    $grand_total_in_foreign_currency += $freight_subtotal_in_foreign_currency;
                                    $grand_total_in_local_currency += $freight_subtotal_in_local_currency;
                                }
                                
                                echo "<tr>"
                                    . "<td>&nbsp;</td>"
                                    . "<td>&nbsp;</td>"
                                    . "<td><b>TOTAL</b></td>"
                                    . "<td id='local_grand_total_fake_class'>".$grand_total_in_local_currency."</td>"
                                    . "<td id='foreign_grand_total_fake_class'>".$grand_total_in_foreign_currency."</td>"
                                    . "</tr>";
                                
                        ?>
                            <tr>
                                <td colspan="5">
                                    <div class="form-actions" style="margin:auto;text-align: center">
                                        <input type="reset" class="btn fake-back-class" value="Reset" id="back">
                                        <input type="submit" class="btn btn-primary" value="Submit" id="submitBtn">
                                    </div>
                                </td>
                            </tr>
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
    
var exchange_rate = parseFloat("<?=$exchange_rate?>");
var custom_field_exchange_rate = parseFloat("<?=$custom_field_exchange_rate?>");
        
$(document).ready(function () {
    
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2,
    });
    
    $('#shipmentCostingForm').submit(function (event){
        event.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#loadingDiv_bakgrnd').show();
        data = $('#shipmentCostingForm').serializeObject();
        
        $.ajax({
            data:data,
            url: "<?=base_url()?>admin/shipmentcost/saveShipmentBatchCost",
            type:'POST',
        })
        .done(function( response ) {
            alert('Shipment cost record saved successfully.');
            window.location.href = "<?=base_url()?>admin/shipmentcost/shipmentPaymentCost";
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
    
    $('.user_defined_local_exchange_fake_class').blur(function(){
        update_sub_total('special_pack');
        update_sub_total('freight');
    })
    
    function update_sub_total(type)
    {
        elemClass = '.' + type + '_fake_class';
        
        sub_total = 0;
        $(elemClass).each(function (index, elem){
            sub_total += parseFloat($(elem).val());
        })
        
        subTotalElem = '#' + type + '_foreign_subtotal';
        $(subTotalElem).html(sub_total);
        
        
        elementClass = '.' + type + '_currency_fake_class';
        sub_total_currency = 0;
        $(elementClass).each(function (index, elem){
            if ($(this).find('input').length > 0)
            {
                tmpVal = parseFloat($(this).find('input').val());
            }
            else
            {
                tmpVal = parseFloat($(this).html());
            }
            sub_total_currency += tmpVal;
        })
        local_sub_total = sub_total_currency.toFixed(2);
        subTotalElement = '#' + type + '_local_subtotal';
        $(subTotalElement).html(local_sub_total);
        
        update_grand_total();
    }
    
    function update_grand_total()
    {
        sub_total = 0;
        $('.local_sub_total_fake_class').each(function (index, elem){
            sub_total += parseFloat($(elem).html());
        })
        sub_total_format = sub_total.toFixed(2);
        grandTotalLocalElem = '#local_grand_total_fake_class';
        $(grandTotalLocalElem).html(sub_total_format);
        
        sub_total = 0;
        $('.foreign_sub_total_fake_class').each(function (index, elem){
            sub_total += parseFloat($(elem).html());
        })
        sub_total_format = sub_total.toFixed(2);
        grandTotalForeignElem = '#foreign_grand_total_fake_class';
        $(grandTotalForeignElem).html(sub_total_format);
    }
    
    $('.special_pack_fake_class').blur(function(){
        update_local_exchange($(this));
        update_sub_total('special_pack');
    })
    
    $('.freight_fake_class').blur(function(){
        update_custom_field_exchange($(this));
        update_sub_total('freight');
    })
    
    function update_location_class(obj)
    {
        val = $(obj).val();
        
        tmpId = $(obj).attr('id');
        tmpNumericId = tmpId.split('_')
        tmpId = tmpNumericId[tmpNumericId.length - 1];
        
        destId = '#fake_location_hidden_' + tmpId;
        $(destId).val(val);
    }
    
    $('.location_fake_class').blur(function(){
        update_location_class($(this))
        
        update_custom_field_exchange($(this));
        update_sub_total('location');
        
        
    })
    
    $('.text_foreign_currency_fake_class').blur(function(){
        val = $(this).val();
        
        tmpId = $(this).attr('id');
        tmpNumericId = tmpId.split('_')
        tmpId = tmpNumericId[tmpNumericId.length - 1];
        
        destId = '#foreign_currency_hidden_' + tmpId;
        $(destId).val(val);
        //' id='foreign_currency_text_$index'
    })
    
    function update_local_exchange(obj)
    {   
        foreign_val = parseFloat($(obj).val());
        local_value =  foreign_val / exchange_rate;
        local_val = local_value.toFixed(2);
        
        is_custom_local_val = $(obj).parent().prev().find('input').length;
        
        if (is_custom_local_val > 0)
        {
            $(obj).parent().prev().find('input').val(local_val);
        }
        else
        {
            $(obj).parent().prev().html(local_val);

            tmpObj = $(obj).parent().find(".local_exchange_fake_class");
            $(tmpObj).val(local_val);
        }
    }
    
    function update_custom_field_exchange(obj)
    {
        foreign_val = parseFloat($(obj).val());
        local_value =  foreign_val / custom_field_exchange_rate;
        local_val = local_value.toFixed(2);
        
        is_custom_local_val = $(obj).parent().prev().find('input').length;
        
        if (is_custom_local_val > 0)
        {
            $(obj).parent().prev().find('input').val(local_val);
        }
        else
        {
            $(obj).parent().prev().html(local_val);

            tmpObj = $(obj).parent().find(".local_exchange_fake_class");
            $(tmpObj).val(local_val);
        }
    }
  
    
    $('#update_total_button').click(function(){
        $('#loadingDiv_bakgrnd').show();
        exchange_rate = parseFloat($('#exchange_rate').val());
        
        $('.special_pack_fake_class').each(function (index, value){
            update_local_exchange($(this));
        })
        update_sub_total('special_pack');
        
        $('.location_fake_class').each(function (index, value){
            type = $(this).attr("exchange_type");
            update_location_class($(this));
         if(type == 'custom'){   
            update_custom_field_exchange($(this));
            }
        else{
            update_local_exchange($(this));
            }
        })
        update_sub_total('location');
        
        $('.freight_fake_class').each(function (index, value){
         type = $(this).attr("exchange_type");
         if(type == 'custom'){   
            update_custom_field_exchange($(this));
            }
        else{
            update_local_exchange($(this));
            }
        })
        update_sub_total('freight');
        
        $('#exchange_rate_save_form').val(exchange_rate);
        $('#exchange_rate_text').html(exchange_rate);
        $('#loadingDiv_bakgrnd').hide();
    })
})
</script>

<?php
            
        } //else empty data ends here
    } // else shipment batches ends here
?>