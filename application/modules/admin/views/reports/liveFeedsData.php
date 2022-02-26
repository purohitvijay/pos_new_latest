<?php
if (!empty($records))
{
    foreach ($records as $index => $row)
    {
        $s_start = $row['order_status'] == 'cancelled' ? '<s>' : '';
        $s_end = $row['order_status'] == 'cancelled' ? '</s>' : '';
        $updatedby = empty($row['updatedby']) ? '' : '<br><br><b>'.$row['updatedby'].'</b>';
?>
        <tr style="background-color: <?=$row['color']?>;color:<?=$row['font_color']?>">
            <td><?=$s_start. $row['order_number'].$s_end?></s></td>
            <td><?=$s_start. $row['customer_name'].$s_end?></td>
            <td><?=$s_start. $row['mobile']. ' / '. $row['residence_phone'].$s_end?></td>
            <td><?=$s_start. $row['display_text'].$s_end?></td>
            <td><?=$s_start. ucwords($row['driver']).$s_end?></td>
            <td><?=$s_start. $row['address'].$s_end?></td>
            <td><?=$s_start. $row['updated_at'].$updatedby.$s_end?></td>
            <td>
                
                <?php
                if($row['order_status'] !== 'cancelled' && $row['status'] !== 'collected_at_warehouse' && $row['status'] !== 'box_collected' && $row['status'] !== 'order_booked')
                {
                ?>
                <button class="btn btn-inverse fake-reassign-driver-class" rel="<?=$row['order_id'].'@@##@@'.$row['employee_id'].'@@##@@'.$row['order_number'].'@@##@@'.$row['driver'].'@@##@@'.$row['id']?>" title="Reassign Driver">
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
            
            <?php
            if (!empty($show_lat_long))
            {
                echo "<td>DB <br> {$row['lattitude']} <br>{$row['longitude']}<br> Google <br> {$row['google_lat']} <br>{$row['google_lon']}<br>{$row['order_id']}</td>";
            }
            ?>
        </tr>
<?php
    }
}
?>