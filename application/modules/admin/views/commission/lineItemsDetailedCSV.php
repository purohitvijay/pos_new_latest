Payment Reference <?=$data['payment_reference']?> for <?=$data['driver']?> for date range (<?=$data['date_from']?> - <?=$data['date_to']?>)
<?php
echo "Order No\t";
echo "Box\t";
echo "Base Commission Amount\t";
echo "Quantity\t";
echo "Commission\t";
echo "\n";

if (!empty($line_items_order_data))
{
    $previous_type = '';
    foreach ($line_items_order_data as $index => $row)
    {
        if ($previous_type <> $row['type'])
        {
            if ($index > 0)
            {
                echo "\t";
                echo ucwords($previous_type)." Summary\t\t";
                echo "$type_wise_box_count\t";
                echo "$type_wise_amount\t";
                echo "\n";
            }

            echo ucwords($row['type'])."\t\n";
            $type_wise_box_count = $type_wise_amount = 0;
        }

        echo $row['order_number']."\t";
        echo $row['box']."\t";
        echo $row['base_commission']."\t";
        echo $row['quantity']."\t";
        echo $row['amount']. "\t";
        echo "\n";
        $type_wise_box_count += $row['quantity'];
        $type_wise_amount += $row['amount'];

        $previous_type = $row['type'];
    }
}


if ($type_wise_box_count > 0)
{
    echo "\t";
    echo ucwords($row['type'])." Summary \t\t";
    echo "$type_wise_box_count\t";
    echo "$type_wise_amount\t";
    echo "\n";
}


foreach ($line_items_data as $row)
{
    if ($row['type'] == 'custom')
    {
        echo "\t";
        echo "{$row['line_item']}\t\t";
        echo "\t";
        echo "{$row['amount']}\t";
        echo "\n";
    }
}


echo "\t";
echo "Grand Total Commission Amount\t\t";
echo "{$data['total_boxes']}\t";
echo "{$data['grand_total']}\t";
echo "\n";