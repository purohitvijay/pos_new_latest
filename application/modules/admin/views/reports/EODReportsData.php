<?php
if (!empty($records))
{
    foreach ($records as $index => $row)
    {
?>
        <tr style="background-color: <?=$row['color']?>;color:<?=$row['font_color']?>">
            <td><?=$row['order_number']?></td>
            <td><?=$row['customer_name']?></td>
            <td><?=$row['mobile']. ' / '. $row['residence_phone']?></td>
            <td><?=$row['display_text']?></td>
            <td><?=$row['driver']?></td>
            <td><?=$row['updated_at']?></td>
            <td>
                
                <?php
                if($row['status'] !== 'collected_at_warehouse' && $row['status'] !== 'order_booked')
                {
                ?>
                <button class="btn btn-inverse fake-reassign-driver-class" rel="<?=$row['id'].'@@##@@'.$row['employee_id'].'@@##@@'.$row['order_number'].'@@##@@'.$row['driver']?>" title="Reassign Driver">
                        <i class="fa fa-random"></i>
                </button>
                <?php
                }
                else
                {
                    echo '--';
                }
                ?>
            </td>
        </tr>
<?php
    }
}
?>