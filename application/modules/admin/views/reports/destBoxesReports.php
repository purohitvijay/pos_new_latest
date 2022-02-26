<div class="container-fluid">


    <div class="page-header">

        <div class="row" >
            <form action="<?= base_url() ?>admin/report/destBoxesReports" method="post">
                <div class="pull-left form-group">
                    <label for="shipment_date_from" class="control-label pull-left">
                        Date<br> (From)
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group date'>
                            <input type="text" name="shipment_date_from" id="shipment_date_from" class="form-control big datepick2" required value='<?= $shipment_date_from ?>'>
                        </div>    
                    </div>
                    <label for="shipment_date_to" class="control-label pull-left">
                        &nbsp;&nbsp;Date<br>&nbsp;&nbsp; (To)
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group date'>
                            <input type="text" name="shipment_date_to" id="shipment_date_to" class="form-control big datepick2" required value='<?= $shipment_date_to ?>'>
                        </div>    
                    </div>
                    <div class="pull-left" style="padding-left:10px">
                        <button type="submit" class="btn btn-primary" >Report</button>
                    </div>
                </div>
         </form>
            
            <form action="<?= base_url() ?>admin/report/destBoxesReports" method="post">
                <div class="pull-left form-group">
                    <?php
                    if (!empty($shipment_batches))
                    {
                        ?>
                        <label for="shipment_batch" class="control-label pull-left" style="padding-left:10px">
                            Shipment Batch
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <select id="shipment_batch" name="shipment_batch_ids[]" multiple class="form-control">
                                    <?php
                                    $batches_names = array();

                                    foreach ($shipment_batches as $index => $row)
                                    {
                                        $selected = !empty($shipment_batch_selected) && in_array($row['id'], $shipment_batch_selected) ? 'Selected' : '';

                                        if (!empty($shipment_batch_selected) && in_array($row['id'], $shipment_batch_selected))
                                        {
                                            $batches_names[] = "<b>{$row['name']}</b>";
                                        }
                                        ?>
                                        <option <?= $selected ?> value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>    
                        </div>
                        <?php
                    }
                    ?>

                    <div class="pull-left" style="padding-left:10px">
                        <button type="submit" class="btn btn-primary" >Report</button>
                    </div>
                </div>

            
            </form>
                <div class="pull-right">
                    <?php
                    if (!empty($records['box_data']))
                    {
                        ?>
                        <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-print"></i>Print
                        </button>
                        <?php
                    }
                    ?>
                </div>
        </div>

        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div>
                        <h3>
                            <i class="fa fa-table"></i>
                            Destination/Boxes Breakdown  <?= empty($shipment_date_from) ? "": "As Of ".$shipment_date_from."-".$shipment_date_to ?><?= empty($batches_names) ? '' : 'for ' . implode(', ', $batches_names) ?>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records['box_data']))
                    {
                        if (!empty($records['location_data']))
                        {
                            ?>
                            <!--<div class="box-content nopadding">-->
                            <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                                <thead>
                                    <tr>
                                        <th>FCL</th>  
                                        <th>SH No.</th>  
                                        <?php
                                        $location_header = $records['location_data']['location_header'];
                                        foreach ($location_header as $index => $value)
                                        {
                                            echo "<th>" . $value . "</th>";
                                        }
                                        ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $location_data = $records['location_data'];
                                    $shipment_location_data = $records['location_data']['shipment_location_data'];
                                    $location_total = array();
                                    $location_total['total'] = 0;
                                    $shipment_count = 0;
                                    foreach ($shipment_location_data as $id => $shipment_record)
                                    {
                                        echo "<tr><td>" . $shipment_record['container_type'] . '</td>';
                                        echo "<td>" . $shipment_record['batch_name'] . '</td>';
                                        $count = 0;
                                        $shipment_count++;
                                        foreach ($location_header as $index => $value)
                                        {
                                            if (isset($shipment_record[$index]['boxes_count']))
                                            {
                                                echo "<td>" . $shipment_record[$index]['boxes_count'] . "</td>";
                                                $count += $shipment_record[$index]['boxes_count'];
                                                if (!isset($location_total[$index]))
                                                {
                                                    $location_total[$index] = $shipment_record[$index]['boxes_count'];
                                                }
                                                else
                                                {
                                                    $location_total[$index] += $shipment_record[$index]['boxes_count'];
                                                }
                                                
                                            }
                                            else
                                            {
                                                echo "<td></td>";
                                            }
                                        }
                                        echo "<td>" . $count . "</td>";
                                        echo "</tr>";

                                        $location_total['total'] += $count;
                                    }
                                    //total row
                                    echo "<tr>";
                                    echo "<td></td><td>S/Total</td>";
                                    //total count row

                                    foreach ($location_total as $idx => $location_total_row)
                                    {
                                        if ($idx != 'total')
                                        {
                                            echo "<td>" . $location_total_row . "</td>";
                                        }
                                    }
                                    echo "<td>" . $location_total['total'] . "</td>";
                                    echo "<tr>";

//average row
                                    echo "<tr>";
                                    echo "<td></td><td>Average </td>";
                                    //total count row

                                    foreach ($location_total as $idx => $location_total_row)
                                    {
                                        if ($idx != 'total')
                                        {
                                            $average = $location_total_row / $shipment_count;
                                            echo "<td>" . number_format((float) $average, 2, '.', '') . "  </td>";
                                        }
                                    }
                                    $total_average = $location_total['total'] / $shipment_count;
                                    echo "<td> ".number_format((float) $total_average, 2, '.', '')." </td>";
                                    echo "<tr>";
                                    
                                    
                                    //percent of total 
                                    echo "<tr>";
                                    echo "<td></td><td>% of Totals</td>";
                                    //total count row

                                    foreach ($location_total as $idx => $location_total_row)
                                    {
                                        if ($idx != 'total')
                                        {
                                            $calculate_percentage = $location_total_row / $location_total['total'] * 100;
                                            echo "<td>" . number_format((float) $calculate_percentage, 2, '.', '') . " % </td>";
                                        }
                                    }
                                    echo "<td> 100%</td>";
                                    echo "<tr>";
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        }
                        echo "</br>";
                        echo "</br>";
                        if (!empty($records['box_data']))
                        {
                            ?>
                            <!--<div class="box-content nopadding">-->
                            <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                                <thead>
                                    <tr>
                                        <th>FCL</th>  
                                        <th>SH No.</th>  
                                        <?php
                                        $box_header = $records['box_data']['box_header'];
                                        foreach ($box_header as $index => $value)
                                        {
                                            echo "<th>" . $value . "</th>";
                                        }
                                        ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $box_data = $records['box_data'];
                                    $shipment_box_data = $records['box_data']['shipment_data_box'];

                                    $box_total = array();
                                    $box_total['total'] = 0;
                                    $shipment_count = 0;
                                    foreach ($shipment_box_data as $id => $shipment_record)
                                    {
                                        echo "<tr><td>" . $shipment_record['container_type'] . '</td>';
                                        echo "<td>" . $shipment_record['batch_name'] . '</td>';
                                        $count = 0;
                                        $shipment_count++;
                                        foreach ($box_header as $index => $value)
                                        {
                                            if (isset($shipment_record[$index]['boxes_count']))
                                            {
                                                echo "<td>" . $shipment_record[$index]['boxes_count'] . "</td>";
                                                $count += $shipment_record[$index]['boxes_count'];
                                                if (!isset($box_total[$index]))
                                                {
                                                    $box_total[$index] = $shipment_record[$index]['boxes_count'];
                                                }
                                                else
                                                {
                                                    $box_total[$index] += $shipment_record[$index]['boxes_count'];
                                                }
                                            }
                                            else
                                            {
                                                echo "<td></td>";
                                            }
                                        }
                                        echo "<td>" . $count . "</td>";
                                        echo "</tr>";
                                        $box_total['total'] += $count;
                                    }
                                    //total row
                                    echo "<tr>";
                                    echo "<td></td><td>S/Total</td>";
                                    //total count row

                                    foreach ($box_total as $idx => $box_total_row)
                                    {
                                        if ($idx != 'total')
                                        {
                                            echo "<td>" . $box_total_row . "</td>";
                                        }
                                    }
                                    echo "<td>" . $box_total['total'] . "</td>";
                                    echo "<tr>";
                                    
                                    
                                    
                                    //average row
                                    echo "<tr>";
                                    echo "<td></td><td>Average </td>";
                                    //total count row

                                    foreach ($box_total as $idx => $box_total_row)
                                    {
                                        if ($idx != 'total')
                                        {
                                            $average = $box_total_row / $shipment_count;
                                            echo "<td>" . number_format((float) $average, 2, '.', '') . "</td>";
                                        }
                                    }
                                     $total_average = $box_total['total'] / $shipment_count;
                                    echo "<td> ".number_format((float) $total_average, 2, '.', '')." </td>";
                                    echo "<tr>";
                                    
                                    
                                    //percent of total 
                                    echo "<tr>";
                                    echo "<td></td><td>% of Totals</td>";
                                    //total count row

                                    foreach ($box_total as $idx => $box_total_row)
                                    {
                                        if ($idx != 'total')
                                        {
                                            $calculate_percentage = $box_total_row / $box_total['total'] * 100;
                                            echo "<td>" . number_format((float) $calculate_percentage, 2, '.', '') . " % </td>";
                                        }
                                    }
                                    echo "<td> 100%</td>";
                                    echo "<tr>";
                                    ?>
                                </tbody>
                            </table>

                            <?php
                        }
                        
                    if (!empty($records['box_data']))
                    {
                        if (!empty($records['location_data']))
                        {
                            ?>
                            <br><br>
                            <!--<div class="box-content nopadding">-->
                            <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <?php
                                        $box_header = $records['box_data']['box_header'];
                                        foreach ($box_header as $index => $value)
                                        {
                                            echo "<th>" . $value . "</th>";
                                        }
                                        ?>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <?php   
                                    $box_location_data = $records['box_location_data'];
                                    $location_box_wise_totals = array();
                                    
                                    $location_header = $records['location_data']['location_header'];
                                    foreach ($location_header as $location_id => $value)
                                    {
                                        $location_box_sub_total = 0;
                                        echo "<tr>";
                                        echo "<td>$value</td>";
                                        foreach ($box_header as $box_id => $box_name_tmp)
                                        {
                                            $box_count = empty($box_location_data[$location_id][$box_id]) ? 0 : $box_location_data[$location_id][$box_id];
                                            echo "<td>" . $box_count . "</td>";
                                            
                                            if (empty($location_box_wise_totals[$box_id]))
                                            {
                                                $location_box_wise_totals[$box_id] = $box_count;
                                            }
                                            else
                                            {
                                                $location_box_wise_totals[$box_id] += $box_count;
                                            }
                                            
                                            $location_box_sub_total += $box_count;
                                        }
                                        
                                        echo "<td>$location_box_sub_total</td>";
                                        echo "</tr>";
                                    }
                                ?>
                                    
                                    <tr>
                                        <th>Total</th>
                                        <?php
                                        $tmp_grand_total = 0;
                                        foreach ($box_header as $index => $value)
                                        {
                                            $tmp_grand_total += $location_box_wise_totals[$index];
                                            echo "<th>" . $location_box_wise_totals[$index] . "</th>";
                                        }
                                        ?>
                                        <th><?=$tmp_grand_total?></th>
                                    </tr>
                                    
                                </tbody>
                            </table>

                            <?php
                        }
                        echo "</br>";
                        echo "</br>";
                    }
                    }
                    else
                    {
                        ?>
                        <div class="alert alert-warning alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>Oops! </strong>No records Found.
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
        $(document).ready(function () {
            $('.datepick2').datepicker({
                format: "dd/mm/yyyy"
            })
            $("#shipment_batch").multiselect();

<?php
if (!empty($records))
{
    ?>
                $("#btnPrint").click(function () {
                    printElement(document.getElementById("reportContainer"));
                    window.print();
                });
    <?php
}
?>
        })
</script>