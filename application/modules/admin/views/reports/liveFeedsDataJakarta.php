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
            <td><?=$s_start. $row['order_number']. "<br/><b>{$row['total_boxes']}</b> boxes".$s_end?></s></td>
            <td><?=$s_start. $row['customer_name'].$s_end?></td>
            <td><?=$s_start. $row['mobile']. ' / '. $row['residence_phone'].$s_end?></td>
            <?php if($row['status']=="delivered_at_jkt_picture_taken") { ?>
            <td style="background-color:#87CEFA;"><?=$s_start. $row['display_text'].$s_end?></td>
            <?php } else { ?>
            <td><?=$s_start. $row['display_text'].$s_end?></td>
            <?php } ?>
            <td><?=$s_start. ucwords($row['driver']).$s_end?></td>
            <td><?=$s_start. $row['address'].$s_end?></td>
            <td><?=$s_start. $row['updated_at'].$updatedby.$s_end?></td>
            
            
        </tr>
<?php
    }
}
?>