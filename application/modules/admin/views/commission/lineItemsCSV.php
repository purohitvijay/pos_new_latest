Payment Reference <?=$data['payment_reference']?> for <?=$data['driver']?> for date range (<?=$data['date_from']?> - <?=$data['date_to']?>)
<?php
echo "Box\t";
echo "Base Amount\t";
echo "Box Count\t";
echo "Commission Amount\t";
echo "\n";
$previous_item_id = '';
$total_boxes = $grand_total_boxes = 0;

$custom_line_items = array();

foreach ($line_items_sum_data as $sum_row)
{
    $total_boxes = 0;
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
//                echo ucwords($row['line_item']). "\t";
                echo ucfirst($row['operation']). "\t";
                echo "$ ". $row['base_commission']. "\t";
                echo $row['count']. "\t";
                echo "$ ". $row['amount'];
                echo "\n";
            }
        }
    }
    $grand_total_boxes += $total_boxes;
    echo ucwords($sum_row['line_item']). "\t\t";
    echo $total_boxes. "\t";
    echo "$ ".$sum_row['total_amount']. "\t";
    echo "\n\n";
}

if (!empty($custom_line_items))
{
    foreach ($custom_line_items as $row)
    {
        echo $row['line_item']. "\t"."\t\t";
        echo "$ ".$row['amount']. "\t";
        echo "\n";
    }
}

echo 'Grand Total Commission Amount'. "\t". "\t";
echo $grand_total_boxes. "\t";
echo "$ ".$data['grand_total']. "\t";
echo "\n";
?>