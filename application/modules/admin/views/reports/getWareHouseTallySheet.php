<div class="container-fluid">

    <?php
    if (!empty($message))
    {
    ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><?=$message?></div>
    <?php
    }
    else
    {
    ?>
    
    <div class="page-header">
        
        <div class="pull-right">
            <?php
            if (!empty($records))
            {
            ?>
                <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                    <i class="fa fa-print"></i>Print
                </button>
            <?php             
            }
            ?>
        </div>
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div>
                        <h3>
                            <i class="fa fa-table"></i>
                            Warehouse Tally Sheet as of <b><?=$date?></b>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records))
                    {
                    ?>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable">
                            <thead>
                                    <tr>
                                            <th>S.No.</th>
                                            <th>Date</th>
                                            <th>Order Number</th>
                                            <th>Driver</th>
                                            <th>Location(s)</th>
                                            <th>Kabupaten(s)</th>
                                            <th>Box Quantity</th>
                                            <th>Box Size</th>
                                            <th>Volume / Box</th>
                                            <th>Total Volume</th>
                                            <th>Weight</th>
                                            <th>Comments</th>
                                    </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                                $sno= 1;
                                
                                $delivery = $collection = 0;
                                
                                $boxes_count = array();
                                
                                foreach ($records as $type => $type_records)
                                {
                                    if ($type == 'collection')
                                    {
                            ?>
                                    <!--
                                    <tr>
                                            <th colspan="11"><?=ucwords($type)?></th>
                                    </tr>
                                    -->
                            <?php
                                    foreach ($type_records as $index => $row)
                                    {
                                        $boxes = explode('@@##@@', $row['box_name_ws']);
                                        $individual_quantity = explode('@@##@@', $row['individual_quantity']);
                                        if (!empty($boxes))
                                        {
                                            foreach ($boxes as $box_index => $box_name)
                                            {
                                                if (empty($boxes_count[$box_name]))
                                                {
                                                    $boxes_count[$box_name] = $individual_quantity[$box_index];
                                                }
                                                else
                                                {
                                                    $boxes_count[$box_name] += $individual_quantity[$box_index];
                                                }
                                                
                                                $$type += $individual_quantity[$box_index];
                                            }
                                        }
                            ?>
                                    <tr>
                                            <td><?=$sno++;?></td>
                                            <td><?=date('d/m/Y', strtotime($row['order_date']))?></td>
                                            <td><?=ucwords($row['order_number'])?></td>
                                            <td><?=ucwords($row['driver_name'])?></td>
                                            <td><?=ucwords($row['locations'])?></td>
                                            <td><?=ucwords($row['kabupatens'])?></td>
                                            <td><?=$row['quantity']?></td>
                                            <td><?=ucwords($row['box_name'])?></td>
                                            <td><?=$row['volume']?></td>
                                            <td><?=$row['total_volume']?></td>
                                            <td><?=$row['weight']?></td>
                                            <td><?=$row['comments']?></td>
                                    </tr>
                            <?php
                                    }
                            ?>
                            </tbody>
                    </table>
                    
                    
                    <div>
                        <h3>
                            <i class="fa fa-table"></i>
                            Summary
                        </h3>
                    </div>
                        
                        <?php
                        if (!empty($boxes_count))
                        {
                        ?>
                            <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y ">
                                <?php
                                $i = 0;
                                foreach ($boxes_count as $box => $count)
                                {
                                    if ($i++ % 2 == 1)
                                    {
                                        //echo $i . 'in top';
                                        echo "<th style='width:40%'>$box</th><td style='width:10%'>$count</td></tr><tr>";
                                    }
                                    else
                                    {
//                                        /echo $i . 'in bottoms';
                                        echo "<tr><th style='width:40%'>$box</th><td style='width:10%'>$count</td>";
                                    }
                                }
                                
                                echo '</tr>';
//                                echo "<tr>"
//                                . " <th>Delivery Total</th>"
//                                        . "<td><b>".$delivery." </b></td>"
//                                . " <th>Collection Total</th>"
//                                        . "<td><b>".$collection." </b></td>"
//                                . "</tr>";
                                echo "<tr>"
                                . " <th>Collection Total</th>"
                                        . "<td><b>".$collection." </b></td>"
                                . "</tr>";
                                ?>
                            </table>
                    <!--</div>-->
                    <?php
                                }
                                
                                }
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
        
    <?php
    }
    ?>
</div>
<script type="text/javascript">
$(document).ready(function () {           
    <?php
    if (!empty($records))
    {
    ?>            
        document.getElementById("btnPrint").onclick = function() {
            printElement(document.getElementById("reportContainer"));
            window.print();
        }
    <?php
    }
    ?>
})
</script>