Container Type : <?= $shipment_batch['container_type'] ?> | Shipment Batch : <?=$shipment_batch['batch_name']?> | Exchange Rate : <?=$shipment_batch['exchange_rate']?> | Shipment Reference : <?=$shipment_batch['shipment_reference']?> 

<?php
echo "\nITEM\t";
echo "QUANTITY\t";
echo "LOCAL ($)\t";
echo "\n";

echo "BOX QUANTITY :\t";
echo "\n";
echo "POSTKI :\t";
echo ($shipment_batch["total_records_count"]) ? $shipment_batch["total_records_count"] - $shipment_batch["Saftri_records_count"]  : "0";
echo "\t";
echo ($shipment_batch["total_seles"]) ? $shipment_batch["total_seles"] - $shipment_batch["Saftri_total_seles"] : "0"."\t";
echo "\n";

echo "SAFTRI :\t";
echo ($shipment_batch["Saftri_records_count"]) ? $shipment_batch["Saftri_records_count"] : "0";
echo "\t";
echo ($shipment_batch["Saftri_total_seles"]) ? $shipment_batch["Saftri_total_seles"] : "0"."\t";
echo "\n";

echo "Total :\t";
echo ($shipment_batch["total_records_count"]) ? $shipment_batch["total_records_count"] : "0"."\t";
echo "\t";
echo ($shipment_batch["total_seles"]) ? $shipment_batch["total_seles"] : "0"."\t";
echo "\n\n";


echo " Trade Discount :\t";
echo "\t";
echo ($shipment_batch["discount"]) ? $shipment_batch["discount"] : "0"."\t";
echo "\n\n";

//echo "BOX QUANTITY\t";       
$subtotal = 0;
$previous_section = '';


if (!empty($data))
{       
    $grand_total_in_local_currency = ($shipment_batch["total_seles"]) ? $shipment_batch["total_seles"] : "0";
    $grand_total_in_local_currency -= ($shipment_batch["discount"]) ? $shipment_batch["discount"] : "0";                            
    $subtotal_in_local_currency = 0;

    foreach ($data as $index => $record)
    {
        if ($previous_section != $record['section'])
        {
            if ($index > 0)
            {
                echo "SUBTOTAL\t";
                echo "\t";
                echo "$subtotal_in_local_currency\t";
                echo "\n\n";

                $subtotal_in_local_currency = 0;
            }

            echo "{$record['section']}\t\n";
        }
        
        echo "{$record['line_item']}\t";
        echo "\t";
        echo "{$record['local_currency_amount']}\t";
        echo "\n";

        $subtotal_in_local_currency += $record['local_currency_amount'];

        $grand_total_in_local_currency -= $record['local_currency_amount'];

        $previous_section = $record['section'];
    }
    
    echo "SUBTOTAL\t";
    echo "\t";
    echo "$subtotal_in_local_currency\t";
    echo "\n\n";
    
    echo "TOTAL\t";
    echo "\t";
    echo "$grand_total_in_local_currency\t";
    echo "\n";
}