Shipment Batch : <?=$shipment_batch['batch_name']?> | Exchange Rate : <?=$shipment_batch['exchange_rate']?> | Payment Reference : <?=$shipment_batch['payment_reference']?>
<?php
echo "ITEM\t";
echo "DESCRIPTION\t";
echo "QUANTITY\t";
echo "LOCAL ($)\t";
echo "FOREIGN (IDR)\t";
echo "\n";             
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
                echo "\tSUBTOTAL\t";
                echo "$subtotal\t";
                echo "$subtotal_in_local_currency\t";
                echo "$subtotal_in_foreign_currency\t";
                echo "\n";

                $subtotal_in_local_currency = 0;
                $subtotal_in_foreign_currency = 0;
                $subtotal = 0;
            }

            echo "{$record['section']}\t\n";
        }

        if ($record['section'] === 'DISTRIBUTION:(overseas)' || $record['section'] === 'SPECIAL BOXES')
        {
            $count = $record['count'];
            $subtotal += $count;
        }
        else
        {
            $count = '';                                            
        }
        
        $record['description'] = str_replace("<br>", ";", $record['description']);
        $record['description'] = trim($record['description'], ';');
        echo "{$record['line_item']}\t";
        echo "{$record['description']}\t";
        echo "$count\t";
        echo "{$record['local_currency_amount']}\t";
        echo "{$record['foreign_currency_amount']}\t";
        echo "\n";

        $subtotal_in_local_currency += $record['local_currency_amount'];
        $subtotal_in_foreign_currency += $record['foreign_currency_amount'];

        $grand_total_in_local_currency += $record['local_currency_amount'];
        $grand_total_in_foreign_currency += $record['foreign_currency_amount'];

        $previous_section = $record['section'];
    }
    
    echo "\t\t";
    echo "SUBTOTAL\t";
    echo "$subtotal_in_local_currency\t";
    echo "$subtotal_in_foreign_currency\t";
    echo "\n";
    
    echo "\t\t";
    echo "TOTAL\t";
    echo "$grand_total_in_local_currency\t";
    echo "$grand_total_in_foreign_currency\t";
    echo "\n";
}