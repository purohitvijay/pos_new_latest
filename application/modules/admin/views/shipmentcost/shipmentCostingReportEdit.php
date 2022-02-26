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
                Edit Shipment Batch Costing Report
            </h3>
        </div>
        <?php
        if (empty($shipment_batches)) {
            echo "No active shipment batches found.";
        } else { ?>
        <div class="row">
                <form action="<?= base_url() ?>admin/shipmentcost/shipmentCostingReport" method="post">
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
                        
                        <label for="shipment_reference" class="control-label pull-left">
                            &nbsp;&nbsp;Exchange Rate
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group'>
                                <input step="0.01" type="number" name="exchange_rate" id="exchange_rate" class="form-control big" required value='<?=$exchange_rate?>'>
                            </div>    
                        </div>
                    
                        <label for="shipment_reference" class="control-label pull-left">
                            &nbsp;&nbsp;Shipment Reference
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="shipment_reference" id="shipment_reference" class="form-control big" disabled required value='<?=$shipment_reference?>'>
                            </div>    
                        </div>
                    
                        <div class="pull-left" style="padding-left:10px">
                            <button type="submit" class="btn btn-primary" >Report</button>
                            <button type="button" class="btn btn-primary fake-back-class">Back</button>
                        </div>
                        <?php
                        if (!empty($masters_data)) {
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
                        if (!empty($data['header'])) {
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
                if (empty($masters_data)) {
                    ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?= $message ?>
                    </div>
                <?php
                 } else { ?>
                <div class="box box-color box-bordered">
                    <br>
                    <form action="<?=base_url()?>admin/shipmentcost/saveShipmentBatchCostingReport" method="post" id="shipmentCostingForm">        
                        <input type="hidden" name="shipment_batch_id" value="<?=$shipment_batch_id?>">
                        <input type="hidden" name="shipment_cost_report_master_id" value="<?=$shipment_cost_report_master_id?>">
                        <input type="hidden" name="date" value="<?=$date?>">
                        <input type="hidden" name="exchange_rate" value="<?=$exchange_rate?>" id="exchange_rate_save_form">
                        <input type="hidden" name="shipment_reference" value="<?=$shipment_reference ?>">
                        <input type="hidden" name="total_seles" value="<?= ($masters_data["total_seles"]) ? $masters_data["total_seles"] : "0" ?>">
                        <input type="hidden" name="discount" value="<?= ($masters_data["discount"]) ? $masters_data["discount"] : "0" ?>">
                        <input type="hidden" name="records_count" value="<?= ($masters_data["records_count"]) ? $masters_data["records_count"] : "0" ?>">
                                <input type="hidden" name="Saftri_total_seles" value="<?= ($masters_data["Saftri_total_seles"]) ? $masters_data["Saftri_total_seles"] : "0" ?>">
                        <input type="hidden" name="Saftri_records_count" value="<?= ($masters_data["Saftri_records_count"]) ? $masters_data["Saftri_records_count"] : "0" ?>">
                        <input type="hidden" name="total_records_count" value="<?= ($masters_data["total_records_count"]) ? $masters_data["total_records_count"] : "0" ?> ">
                                <input type="hidden" name="data" value="<?= base64_encode(serialize($masters_data)) ?>">
                            <table style="page-break-after: always;" class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                                <tr>
                                    <td style="text-align: center" colspan="5">
                                            Container Type : <b><?= $shipment_record['container_type'] ?></b> |  Shipment Batch : <b><?= $shipment_record['batch_name'] ?></b> | Date : <b><?= $date ?> |</b> Exchange Rate : <b id="exchange_rate_text"><?= $exchange_rate ?></b> |</b> Shipment Reference : <b><?= $shipment_reference ?> </b> 
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
                                        <td style="width:15%"><?= ($masters_data["records_count"]) ? $masters_data["records_count"] : "0" ?></td>
                                        <td style="width:15%"><?= set_locale_money_format(($masters_data["total_seles"]) ? $masters_data["total_seles"] - $masters_data["Saftri_total_seles"] : "0") ?></td>
                                    </tr>
                                    <tr>
                                        <td style="width:15%">SAFTRI</td>
                                        <td style="width:15%"><?= ($masters_data["Saftri_records_count"]) ? $masters_data["Saftri_records_count"] : "0" ?></td>
                                        <td style="width:15%"><?= set_locale_money_format(($masters_data["Saftri_total_seles"]) ? $masters_data["Saftri_total_seles"] : "0") ?></td>
                                    </tr>
                                    <tr>
                                        <th><b>TOTAL</b></th> 
                                        <th><b><?= ($masters_data["total_records_count"]) ? $masters_data["total_records_count"] : "0" ?></b></th>
                                        <th><b><?= set_locale_money_format(($masters_data["total_seles"]) ? $masters_data["total_seles"] : "0") ?></b></th>
                                    </tr>
                                     <tr>
                                        <td style="width:15%"><b>Trade Discount:</b></td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"><b><?= set_locale_money_format(($masters_data["discount"]) ? $masters_data["discount"] : "0") ?></b></td>
                                    </tr>  
                                <?php if($masters_data["agents_name"]){ ?>
                                <tr>
                                    <td style="width:15%"><b>Agents:</b></td>
                                </tr>                                    
                                <?php foreach ($masters_data["agents_name"] as $key => $value) 
                                    { ?>                                        
                                    <tr>
                                        <td style="width:15%"><?= $key ?></td>
                                        <td style="width:15%"></td>
                                        <td style="width:15%"><?= set_locale_money_format($value) ?></td>
                                    </tr>                                         
                                <?php } } ?>
                                <?php
                                $dist_subtotal_in_local_currency = 0;
                                $grand_total_in_local_currency = $masters_data["total_seles"];
                                $type = "custom";
                                if (!empty($masters_data['materials'])) 
                                {
                                    $section = 'MATERIALS:';
                                    echo "<tr><td colspan='5'><b>$section</td></tr>";
                                    $subtotal = 0;
                                    $dist_subtotal_in_local_currency = 0;
                                        $total_quantity = 0;
                                    if($masters_data['records'])
                                    {
                                        foreach ($masters_data['records'] as $index => $materials) 
                                        {
                                            $materials_master_data_reference = 0;
//                                            if(array_key_exists($materials["name"], $masters_data['materials']))
//                                                $cost_in_local_currency  = $materials['grand_total'] * $masters_data['materials'][$materials["name"]];
//                                            else
//                                                $cost_in_local_currency  = $materials['grand_total'];
                                            
                                                if(array_key_exists($materials["name"], $masters_data['materials']))
                                                    $cost_in_local_currency  = $materials['quantity'] * $masters_data['materials'][$materials["name"]];
                                                else
                                                    $cost_in_local_currency  = $materials['quantity'];

                                            $cost_in_local_currency_exchange_rate = set_locale_money_format($cost_in_local_currency);
                                                $total_quantity += $materials["quantity"];
                                            echo ""
                                                . "<td>{$materials["name"]} "
                                            ."<input type='hidden' name='line_item[]' value='{$materials["name"]}'>"
                                                ."<input type='hidden' name='quantity[]' value='{$materials["quantity"]}'>" .
                                            "<input type='hidden' name='count[]' value='{$cost_in_local_currency_exchange_rate}'>" .
                                            "<input type='hidden' name='master_data_reference[]' class='materials_master_data_reference' value='{$cost_in_local_currency}'>".
                                            "<input type='hidden' name='local_currency_amount[]' class='materials_currency_fake' value='$cost_in_local_currency'>" .
                                            "<input type='hidden' name='section[]' value='$section'>" .
                                            "<input type='hidden' name='type[]' value='$type'></td>"
                                                . "<td> {$materials["quantity"]}</td>"
                                            . "<td class='materials_currency_fake_class'>" . $cost_in_local_currency_exchange_rate. "</td>";
                                                    $cost_in_local_currency_exchange_rate = str_replace(",", "", $cost_in_local_currency_exchange_rate);
                                                $dist_subtotal_in_local_currency += $cost_in_local_currency_exchange_rate;
                                            echo "</tr>";
                                        }
                                    }

                                    echo "<tr>"
                                    . "<th><b>SUBTOTAL</b></th>"
                                    . " <th>$total_quantity</th>"
                                    . "<th class='local_sub_total_fake_class' id='materials_subtotal'>" . set_locale_money_format($dist_subtotal_in_local_currency) . "</th>"
                                    . "</tr>";
                                        $dist_subtotal_in_local_currency = str_replace(",", "", $dist_subtotal_in_local_currency);
                                    $grand_total_in_local_currency -= $dist_subtotal_in_local_currency;

                                }

                                if (!empty($masters_data['distribution_overseas'])) 
                                {
                                    $section = 'DISTRIBUTION: (local)';
                                    echo "<tr><td colspan='5'><b>$section</td></tr>";
                                    $subtotal = 0;
                                    $dist_subtotal_in_local_currency = 0;

                                    foreach ($masters_data['distribution_overseas'] as $index => $distribution_overseas) 
                                    {
                                            $cost_in_local_currency = 0;
                                            $commission_orders = round(($masters_data['commission_orders'] - $masters_data['commission_Special_orders']),2 );
                                            $cost_in_local_currency = round($distribution_overseas,2);
                                            
                                            
                                        if ($index == 'COMPENSATION')
                                        {
                                            $htmlVal = "<input type='text' class='distribution_overseas_currency_fake_class_input' value='$cost_in_local_currency' exchange_type='$type'>";
                                        }
                                        else if($index == "DELIVERY & COLLECTION")
                                        {
                                            $cost_in_local_currency += $commission_orders;
                                            $distribution_overseas += $commission_orders;
                                            $htmlVal = set_locale_money_format($cost_in_local_currency);
                                        }
                                        else if($index == "COLLECTION SP")
                                        {
                                            $cost_in_local_currency += $masters_data['commission_Special_orders'];
                                            $distribution_overseas += $masters_data['commission_Special_orders'];
                                            $htmlVal = set_locale_money_format($cost_in_local_currency);
                                        }
                                        
                                        echo ""
                                        . "<td>{$index}"
                                        ."<input type='hidden' name='line_item[]' value='{$index}'>" .
                                        "<input type='hidden' name='count[]' value='{$cost_in_local_currency}'>" .
                                        "<input type='hidden' name='master_data_reference[]' class='distribution_overseas_master_data_reference' value='$distribution_overseas'>".
                                        "<input type='hidden' name='local_currency_amount[]' class='distribution_overseas_currency_fake' value='$cost_in_local_currency'>" .
                                        "<input type='hidden' name='section[]' value='$section'>" .
                                        "<input type='hidden' name='type[]' value='$type'></td>" .  
                                        "<td></td>".                                          
                                        "<td class='distribution_overseas_currency_fake_class'>" . $htmlVal ."</td>";
                                            $cost_in_local_currency = str_replace(",", "", $cost_in_local_currency);
                                        $dist_subtotal_in_local_currency += $cost_in_local_currency;
                                        echo "</tr>";
                                    }

                                    echo "<tr>"
                                        . "<th><b>SUBTOTAL</b></th> "
                                        . "<th> </th>"
                                    . "<th class='local_sub_total_fake_class' id='distribution_overseas_subtotal'>" .  set_locale_money_format($dist_subtotal_in_local_currency) . "</th>"
                                    . "</tr>";
                                        $dist_subtotal_in_local_currency = str_replace(",", "", $dist_subtotal_in_local_currency);
                                    $grand_total_in_local_currency -= $dist_subtotal_in_local_currency;

                                }

                                if (!empty($masters_data['distribution_local'])) 
                                {
                                    $section = 'DISTRIBUTION: (overseas)';
                                    echo "<tr><td colspan='5'><b>$section</td></tr>";
                                    $subtotal = 0;
                                    $dist_subtotal_in_local_currency = 0;

                                    foreach ($masters_data['distribution_local'] as $index => $distribution_local) 
                                    {
                                            $cost_in_local_currency = 0;
                                            $cost_in_local_currency = round($distribution_local / $exchange_rate,2);
                                            $cost_in_local_currency_td = $cost_in_local_currency;
                                            if($cost_in_local_currency)
                                                $cost_in_local_currency = set_locale_money_format($cost_in_local_currency);
                                            
                                        echo ""
                                        . "<td>{$index}"
                                        ."<input type='hidden' name='line_item[]' value='{$index}'>" .
                                        "<input type='hidden' name='count[]' value='{$cost_in_local_currency}'>" .
                                        "<input type='hidden' name='master_data_reference[]' class='distribution_local_master_data_reference' value='$distribution_local'>".
                                        "<input type='hidden' name='local_currency_amount[]' class='distribution_local_currency_fake local_exchange_fake_class' value='$cost_in_local_currency_td'>" .
                                        "<input type='hidden' name='section[]' value='$section'>" .
                                        "<input type='hidden' name='type[]' value='$type'></td>" . 
                                        "<td></td>".          
                                       "<td class='distribution_local_currency_fake_class'>" . $cost_in_local_currency."</td>";
                                            $cost_in_local_currency = str_replace(",", "", $cost_in_local_currency);
                                        $dist_subtotal_in_local_currency += $cost_in_local_currency;
                                        echo "</tr>";
                                    }

                                    echo "<tr>"
                                        . "<th><b>SUBTOTAL</b></th> <th> </th>"
                                    . "<th class='local_sub_total_fake_class' id='distribution_local_subtotal'>" . set_locale_money_format($dist_subtotal_in_local_currency) . "</th>"
                                    . "</tr>";
                                        $dist_subtotal_in_local_currency = str_replace(",", "", $dist_subtotal_in_local_currency);
                                    $grand_total_in_local_currency -= $dist_subtotal_in_local_currency;

                                }

                                if (!empty($masters_data['freight_local'])) 
                                {
                                    $section = 'FREIGHT: (local)';
                                    echo "<tr><td colspan='5'><b>$section</td></tr>";
                                    $subtotal = 0;
                                    $dist_subtotal_in_local_currency = 0;

                                    foreach ($masters_data['freight_local'] as $index => $freight_local) 
                                    {
                                            $cost_in_local_currency = 0;
                                            $cost_in_local_currency = round($freight_local,2);
                                            if($cost_in_local_currency)
                                                $cost_in_local_currency = set_locale_money_format($cost_in_local_currency);                                            
                                        
                                        if ($index == 'Mainpower' || $index == "Courier" || $index == "Staff")
                                            $htmlVal = "<input type='text' class='freight_local_currency_fake_class_input' value='$cost_in_local_currency' exchange_type='$type'>";
                                        else
                                            $htmlVal = $cost_in_local_currency;
                                        
                                        echo ""
                                        . "<td>{$index}"
                                        ."<input type='hidden' name='line_item[]' value='{$index}'>" .
                                        "<input type='hidden' name='count[]' value='{$cost_in_local_currency}'>" .
                                        "<input type='hidden' name='master_data_reference[]' class='freight_local_master_data_reference' value='$freight_local'>".
                                        "<input type='hidden' name='local_currency_amount[]' class='freight_local_currency_fake local_exchange_fake_class' value='$cost_in_local_currency'>" .
                                        "<input type='hidden' name='section[]' value='$section'>" .
                                        "<input type='hidden' name='type[]' value='$type'></td>" . 
                                        "<td></td>".          
                                        "<td class='freight_local_currency_fake_class'>" . $htmlVal. "</td>";
                                            $cost_in_local_currency = str_replace(",", "", $cost_in_local_currency);
                                        $dist_subtotal_in_local_currency += $cost_in_local_currency;

                                        echo "</tr>";
                                    }

                                    echo "<tr>"
                                        . "<th><b>SUBTOTAL</b></th> <th> </th>"
                                    . "<th class='local_sub_total_fake_class' id='freight_local_subtotal'>" . set_locale_money_format($dist_subtotal_in_local_currency). "</th>"
                                    . "</tr>";
                                        $dist_subtotal_in_local_currency = str_replace(",", "", $dist_subtotal_in_local_currency);
                                    $grand_total_in_local_currency -= $dist_subtotal_in_local_currency;

                                }
                                if (!empty($masters_data['freight_overseas'])) 
                                {
                                    $section = 'FREIGHT: (overseas)';
                                    echo "<tr><td colspan='5'><b>$section</td></tr>";
                                    $subtotal = 0;
                                    $dist_subtotal_in_local_currency = 0;

                                    foreach ($masters_data['freight_overseas'] as $index => $freight_overseas) 
                                    {
                                            $cost_in_local_currency = 0;
                                            if($index == "Handling Luar Jawa")
                                            {
                                                $freight_overseas = ($masters_data["Luar_Jawa"] * $freight_overseas); 
                                            }
                                            
                                            $cost_in_local_currency = round($freight_overseas / $exchange_rate,2);                                            
                                            
                                            if($cost_in_local_currency)
                                                $cost_in_local_currency_td = set_locale_money_format($cost_in_local_currency);
                                        echo ""
                                        . "<td>{$index}</td>"
                                        ."<input type='hidden' name='line_item[]' value='{$index}'>" .
                                        "<input type='hidden' name='count[]' value='{$cost_in_local_currency}'>" .
                                        "<input type='hidden' name='master_data_reference[]' class='freight_overseas_master_data_reference' value='$freight_overseas'>".
                                        "<input type='hidden' name='local_currency_amount[]' class='freight_overseas_currency_fake local_exchange_fake_class' value='$cost_in_local_currency'>" .
                                        "<input type='hidden' name='section[]' value='$section'>" .
                                        "<input type='hidden' name='type[]' value='$type'></td>" . 
                                        "<td></td>".          
                                            "<td class='freight_overseas_currency_fake_class'>" . $cost_in_local_currency_td. "</td>";
                                            $cost_in_local_currency = str_replace(",", "", $cost_in_local_currency);
                                        $dist_subtotal_in_local_currency += $cost_in_local_currency;

                                        echo "</tr>";
                                    }

                                    echo "<tr>"
                                        . "<th><b>SUBTOTAL</b></th> <th> </th>"
                                    . "<th class='local_sub_total_fake_class' id='freight_overseas_subtotal'>" . set_locale_money_format($dist_subtotal_in_local_currency) . "</th>"
                                    . "</tr>";
                                        $dist_subtotal_in_local_currency = str_replace(",", "", $dist_subtotal_in_local_currency);
                                    $grand_total_in_local_currency -= $dist_subtotal_in_local_currency;                                        
                                }

                                $grand_total_in_local_currency -= $masters_data["discount"];

                                echo "<tr>"
                                    . "<td><b>TOTAL</b></td> <td> </td>"
                                . "<td id='local_grand_total_fake_class'>" . set_locale_money_format($grand_total_in_local_currency) . "</td>"
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
var exchange_rate = parseFloat("<?= $exchange_rate ?>");
var discount = parseFloat("<?= $masters_data["discount"] ?>");
var total_seles = parseFloat("<?= $masters_data["total_seles"] ?>");
$(document).ready(function () 
{
    var formatter = new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2,
    });
    
    $('#shipmentCostingForm').submit(function (event) 
    {
        event.preventDefault();
        
        $('#submitBtn').prop('disabled', true);
        $('#loadingDiv_bakgrnd').show();
        data = $('#shipmentCostingForm').serializeObject();
        
        $.ajax({
                        data: data,
                        url: "<?= base_url() ?>admin/shipmentcost/saveShipmentBatchCostingReport",
                        type: 'POST',
        })
                    .done(function (response) {
                        alert('Shipment cost Report record saved successfully.');
                        window.location.href = "<?= base_url() ?>admin/shipmentcost/shipmentCostingReport";
            $('#loadingDiv_bakgrnd').hide();
        });
    })
    
                $('.fake-back-class').click(function () {
                    window.location.href = "<?= base_url() ?>admin/commission/shipmentReferenceList";
    })

    function update_sub_total(type)
    {
        elemClass = '.' + type + '_subtotal';
        sub_total = 0;

        elementClass = '.' + type + '_currency_fake_class';
        sub_total_currency = 0;
        $(elementClass).each(function (index, elem) 
        {
            if ($(this).closest("tr").find("input."+type+"_currency_fake_class_input").length > 0)
            {
                tmpVal = $(this).closest("tr").find("input."+type+"_currency_fake_class_input").val();
            }
            else
            {
                tmpVal = $(this).html();
            }
            tmpVal = tmpVal.toString();
            tmpVal  = +(tmpVal.replace(",", ""));
            console.log(tmpVal);
            sub_total_currency += tmpVal;
        })
                    sub_total = sub_total_currency.toLocaleString();
        subTotalElement = '#' + type + '_subtotal';
        $(subTotalElement).html(sub_total);
    }

    function update_grand_total()
    {
                    var tmpVal = 0;
         sub_total = total_seles;
         sub_total -= discount;
        $('.local_sub_total_fake_class').each(function (index, elem) {
            tmpVal = $(elem).html();
            tmpVal = tmpVal.toString();
            tmpVal  = +(tmpVal.replace(",", ""));
            sub_total -=  tmpVal;
        })        
        sub_total_format = sub_total.toLocaleString();
        grandTotalLocalElem = '#local_grand_total_fake_class';
        $(grandTotalLocalElem).html(sub_total_format);
    }

    $('.text_foreign_currency_fake_class').blur(function () {
        val = $(this).val();

        tmpId = $(this).attr('id');
        tmpNumericId = tmpId.split('_')
        tmpId = tmpNumericId[tmpNumericId.length - 1];

        destId = '#foreign_currency_hidden_' + tmpId;
        $(destId).val(val);
    })

    function update_local_exchange(obj,type)
    {
        val = parseFloat($(obj).val());
        if(type == "freight_local" || type == "materials" || type == "distribution_overseas")
            local_value = val;
        else
            local_value = val / exchange_rate;
        local_val = local_value.toLocaleString();
      
        is_custom_local_val = $(obj).closest("tr").find("input."+type+"_currency_fake_class_input").length;
        if (is_custom_local_val > 0)
        {
            local_val = $(obj).closest("tr").find("input."+type+"_currency_fake_class_input").val()/exchange_rate;   
            local_val = local_val.toLocaleString(); 
            $(obj).closest("tr").find("input."+type+"_currency_fake_class_input").val(local_val);
        }
        else
        {          
            local_val = local_value.toLocaleString(); 
            $(obj).closest("tr").find("."+type+"_currency_fake_class").html(local_val);
            tmpObj = $(obj).closest("tr").find("."+type+"_currency_fake");
            $(tmpObj).val(local_val);     
        }
    }

    $('#update_total_button').click(function () 
    {
        $('#loadingDiv_bakgrnd').show();
        exchange_rate = parseFloat($('#exchange_rate').val());
        $('.materials_master_data_reference').each(function (index, value)
        {
            update_local_exchange($(this),"materials");
        })
        update_sub_total('materials');

        $('.distribution_local_master_data_reference').each(function (index, value) {
            update_local_exchange($(this),"distribution_local");
        })
        update_sub_total('distribution_local');

        $('.distribution_overseas_master_data_reference').each(function (index, value) {
            update_local_exchange($(this),"distribution_overseas");
        })
        update_sub_total('distribution_overseas');

        $('.freight_local_master_data_reference').each(function (index, value) {
            update_local_exchange($(this),"freight_local");
        })
        update_sub_total('freight_local');

        $('.freight_overseas_master_data_reference').each(function (index, value) {
            update_local_exchange($(this),"freight_overseas");
        })
        update_sub_total('freight_overseas');

        update_grand_total();
        $('#exchange_rate_save_form').val(exchange_rate);
        $('#exchange_rate_text').html(exchange_rate);
        $('#loadingDiv_bakgrnd').hide();
    })

    $('#shipment_batches_change').on('change', function () {
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