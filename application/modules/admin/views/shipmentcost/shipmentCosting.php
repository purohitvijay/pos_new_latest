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
                Shipment Batch Costing
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
                                <?php
                                if (!empty($data)) {
                                    ?>
                                    <select id="shipment_batches_change" name="shipment_batch_id" class="form-control">
                                        <?php
                                        foreach ($shipment_batches as $index => $row) {
                                            $selected = $row['id'] == $shipment_batch_id ? 'Selected' : '';
                                            ?>
                                            <option <?= $selected ?> value="<?= $row['id'] ?>"><?= $row['shipment_batch'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                        <?php
                                    } else {
                                        ?>
                                    <select id="shipment_batches" name="shipment_batch_id" class="form-control">
                                    <?php
                                    foreach ($shipment_batches as $index => $row) {
                                        $selected = $row['id'] == $shipment_batch_id ? 'Selected' : '';
                                        ?>
                                            <option <?= $selected ?> value="<?= $row['id'] ?>"><?= $row['shipment_batch'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                        <?php }
                                    ?>
                                </div>    
                        </div>
                    
                        <label for="date" class="control-label pull-left">
                            Date
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="date" id="date" class="form-control big datepick" required value='<?=$date?>'>
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
                                <input type="text" name="payment_reference" id="payment_reference" class="form-control big" required value='<?=$payment_reference?>'>
                            </div>    
                        </div>
                    
                        <div class="pull-left" style="padding-left:10px">
                            <button type="submit" class="btn btn-primary" id="Submit_Report" >Report</button>
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
                        <input type="hidden" name="date" value="<?=$date?>">
                        <input type="hidden" name="exchange_rate" value="<?=$exchange_rate?>" id="exchange_rate_save_form">
                        <input type="hidden" name="payment_reference" value="<?=$payment_reference ?>">
                        <input type="hidden" name="data" value="<?=base64_encode(serialize($data))?>">
                        
                        <table style="page-break-after: always;" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                            <tr>
                                <td style="text-align: center" colspan="5">
                                    Shipment Batch : <b><?=$shipment_record['batch_name']?></b> | Date : <b><?=$date?> |</b> Exchange Rate : <b id="exchange_rate_text"><?=$exchange_rate?></b> |</b> Payment Reference : <b><?=$payment_reference?> </b>
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
                                
                                echo "<tr><th colspan='5'>$section</td></tr>";

                                $subtotal = 0;
                                
                                foreach ($data['location_box_count'] as $index => $location_record)
                                {
                                    echo '<tr>';
                                    $box_wise_string = '';
                                    
                                    $quantity = 0;
                                    
                                    if (!empty($location_record['boxes']))
                                    {
                                        foreach ($location_record['boxes'] as $box_row)
                                        {
                                            $quantity += $box_row['quantity'];
                                            $box_wise_string .= "{$box_row['name']} ({$box_row['quantity']})<br>";
                                        }
                                    }
                                    $foreign_currency_base_value = empty($masters_data['location'][$location_record['location']]) ? 1 :
                                                            $masters_data['location'][$location_record['location']];
                                    
                                    $cost_in_foreign_currency = $quantity * $foreign_currency_base_value;
                                    if ($location_record['location'] == 'Luar Jawa')
                                    {
                                    $cost_in_local_currency = round($cost_in_foreign_currency / $custom_field_exchange_rate, 2);
                                    }
                                    else {
                                    $cost_in_local_currency = round($cost_in_foreign_currency / $exchange_rate, 2);
                                    }
                                    if ($location_record['location'] == 'Luar Jawa')
                                    {
                                        $has_luar_jawa = true;
                                        $type = 'custom';
                                    }
                                    else
                                    {
                                        $type = 'system';
                                    }
                                    
                                    if ($type == 'custom')
                                    {
                                        $htmlVal = "<input type='text' class='location_fake_class' id='fake_location_text_$index' value='$cost_in_foreign_currency' exchange_type='$type'>";
                                    }
                                    else
                                    {
                                        $htmlVal = $cost_in_foreign_currency."<input type='hidden' id='fake_location_text_$index' class='location_fake_class' value='$cost_in_foreign_currency'  exchange_type='$type'>";
                                    }
                                    
                                    $subtotal += $quantity;
                                    
                                    echo ""
                                    . "<td>{$location_record['location']}</td>"
                                    . "<td>$box_wise_string</td>"
                                    . "<td>$quantity</td>"
                                    . "<td class='location_currency_fake_class'>".$cost_in_local_currency."</td>"
                                    . "<td>".$htmlVal.
                                            "<input type='hidden' name='line_item[]' value='{$location_record['location']}'>".
                                            "<input type='hidden' name='description[]' value='$box_wise_string'>".
                                            "<input type='hidden' name='count[]' value='$quantity'>".
                                            "<input type='hidden' name='local_currency_amount[]' class='local_currency_fake_class local_exchange_fake_class' value='$cost_in_local_currency'>".
                                            "<input type='hidden' name='master_data_reference[]' value='$foreign_currency_base_value'>".
                                            "<input type='hidden' id='fake_location_hidden_$index' name='foreign_currency_amount[]' value='$cost_in_foreign_currency'>".
                                            "<input type='hidden' name='section[]' value='$section'>".
                                            "<input type='hidden' name='type[]' value='$type'>".
                                            
                                     "</td>";
                                
                                    $dist_subtotal_in_foreign_currency += $cost_in_foreign_currency;
                                    $dist_subtotal_in_local_currency += $cost_in_local_currency;
                                
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
                                        
                                if (!empty($data['special_boxes']))
                                {
                                    $section = 'SPECIAL BOXES';
                                    
                                    echo "<tr><th colspan='5'>$section</td></tr>";
                                    
                                    $special_pack_cost = empty($masters_data['special_pack']['Special Pack']) ?
                                                        0.0 : $masters_data['special_pack']['Special Pack'];

                                    $spcl_subtotal_in_foreign_currency = 0;
                                    $spcl_subtotal_in_local_currency = 0;
                                
                                    foreach ($data['special_boxes'] as $index => $row)
                                    {
                                        $local_cost = round($special_pack_cost / $exchange_rate, 2);
                                                
                                        $spcl_subtotal_in_foreign_currency += $special_pack_cost;
                                        $spcl_subtotal_in_local_currency += $local_cost;
                                    
                                        
                                        echo "<tr>";

                                        echo "<td>{$row['order_number']}</td>"
                                        . "<td>{$row['box_name']}</td>"
                                        . "<td>{$row['quantity']}</td>"
                                        . "<td class='special_pack_currency_fake_class'>"
                                            . "<input type='text' class='local_exchange_fake_class user_defined_local_exchange_fake_class' name='local_currency_amount[]' value='$local_cost'>"
                                        . "</td>"
                                        . "<td>"
                                                . "<input class='special_pack_fake_class text_foreign_currency_fake_class' id='foreign_currency_text_$index' type='text' value='".$special_pack_cost."'>"
                                                ."<input type='hidden' name='line_item[]' value='{$row['order_number']}'>"
                                                ."<input type='hidden' name='description[]' value='{$row['box_name']}'>"
                                                ."<input type='hidden' name='count[]' value='{$row['quantity']}'>"
                                                ."<input type='hidden' name='master_data_reference[]' value='$special_pack_cost'>"
                                                ."<input type='hidden' id='foreign_currency_hidden_$index' name='foreign_currency_amount[]' value='".$special_pack_cost."'>"
                                                ."<input type='hidden' name='section[]' value='$section'>".
                                                "<input type='hidden' name='type[]' value='custom'>"
                                        . "</td>"
                                        . "";
                                        
                                        $subtotal += $row['quantity'];         
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

                                     $freight_subtotal_in_foreign_currency = 0;
                                     $freight_subtotal_in_local_currency = 0;
                                    
                                     foreach ($masters_data['freight'] as $head => $cost)
                                    {
                                        $uniqid = uniqid();
                                                
                                        if ($head == 'Handling Luar Jawa') //Did so because Imran feed backed it would follow normal currency
                                        {
                                            $local_cost = round($cost / $custom_field_exchange_rate, 2);
                                            $local_host_html =  "<input type='text' class='local_exchange_fake_class user_defined_local_exchange_fake_class ' name='local_currency_amount[]' value='$local_cost'>";
                                        }
                                        else
                                        {
                                            $local_cost = round($cost / $exchange_rate, 2);
                                            $local_host_html =  "$local_cost<input type='hidden' class='local_exchange_fake_class' name='local_currency_amount[]' value='$local_cost'>";
                                        }
                                        
                                        $freight_subtotal_in_foreign_currency += $cost;
                                        $freight_subtotal_in_local_currency += $local_cost;
                                        
                                        if ($head == 'Handling Luar Jawa')
                                        {
                                            $type = 'custom';
                                            $htmlhandling = "<input type='text' class='freight_fake_class' name='foreign_currency_amount' value='$cost' exchange_type='$type'>";
                                        }
                                        else
                                        {
                                            $type = 'system';
                                            $htmlhandling = $cost . "<input type='hidden' class='freight_fake_class' name='foreign_currency_amount' value='$cost' exchange_type='$type'>";
                                        }
                                        
                                        echo "<tr>";
                                        echo ""
                                        . "<td>$head</td>"
                                        . "<td>&nbsp;</td>"
                                        . "<td>&nbsp;</td>"
                                        . "<td class='freight_currency_fake_class'>$local_host_html</td>"
                                        . "<td>".$htmlhandling.
                                                
                                        "<input type='hidden' name='line_item[]' value='$head'>".
                                        "<input type='hidden' name='description[]' value=''>".
                                        "<input type='hidden' name='count[]' value=''>".
//                                        "<input class='freight_fake_class'  type='hidden' id='foreign_currency_hidden_$uniqid' name='foreign_currency_amount[]' value='$cost'>".
                                        "<input type='hidden' name='master_data_reference[]' value='$cost'>".
                                            "<input type='hidden' name='type[]' value='$type'>".
                                        "<input type='hidden' name='section[]' value='$section'>";
                                        
                                        echo "</td>"
                                        . "<tr>";
                                    
                                        unset($local_cost, $cost);  
                                    }
                                    
                                    echo "<tr>"
                                    . "<td>&nbsp;</td>"
                                    . "<td><b>SUBTOTAL</b></td>"
                                    . "<td>&nbsp;</td>"
                                    . "<td id='freight_local_subtotal' class='local_sub_total_fake_class'>".$freight_subtotal_in_local_currency."</td>"
                                    . "<td id='freight_foreign_subtotal'class='foreign_sub_total_fake_class'>".$freight_subtotal_in_foreign_currency."</td>"
                                    . "</tr>";
                                    
                                    $grand_total_in_foreign_currency += $freight_subtotal_in_foreign_currency;
                                    $grand_total_in_local_currency += $freight_subtotal_in_local_currency;
                                }
                                
                                echo "<tr>"
                                    . "<td>&nbsp;</td>"
                                    . "<td><b>TOTAL</b></td>"
                                    . "<td>&nbsp;</td>"
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
    
    $('.user_defined_local_exchange_fake_class').blur(function(){
        update_sub_total('special_pack');
        update_sub_total('freight');
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

    $('#shipment_batches_change').on('change', function() {
        var con = confirm('This action would reload the form. Do you want to continue?');
        if (con == true) {
          $("#Submit_Report").click();
        }
    })
});
</script>

<?php
            
        } //else empty data ends here
    } // else shipment batches ends here
?>
