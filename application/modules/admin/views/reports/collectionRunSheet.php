<div class="container-fluid">
    
    
    <div class="page-header">
        
        <div class="row" >
            <form action="<?=base_url()?>admin/report/collectionRunSheet" method="post">
                <div class="pull-left form-group">
                        <label for="collection_date_from" class="control-label pull-left">
                            Collection Date<br> (From)
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="collection_date_from" id="collection_date_from" class="form-control big datepick2" required value='<?=$collection_date_from?>'>
                            </div>    
                        </div>
                        <label for="collection_date_to" class="control-label pull-left">
                            &nbsp;&nbsp;Collection Date<br>&nbsp;&nbsp; (To)
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="collection_date_to" id="collection_date_to" class="form-control big datepick2" required value='<?=$collection_date_to?>'>
                            </div>    
                        </div>
                        <?php
                        if (!empty($drivers))
                        {  
                        ?>
                        <label for="drivers" class="control-label pull-left" style="padding-left:10px">
                            Drivers
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <select id="drivers" name="driver_ids[]" multiple class="form-control">
                                    <?php
                                    $driver_names = array();
                                    
                                    foreach ($drivers as $index => $row)
                                    {
                                        $selected = !empty($drivers_selected) && in_array($row['id'], $drivers_selected) ? 'Selected' : '';
                                        
                                        if (!empty($drivers_selected) && in_array($row['id'], $drivers_selected))
                                        {
                                            $driver_names[] = "<b>{$row['name']}</b>";
                                        }
                                    ?>
                                        <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>    
                        </div>
                        <?php
                        }
                        ?>
                    
                        <div class="pull-left">
                            <button type="submit" class="btn btn-primary" >Report</button>
                        </div>
                </div>

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
            </form>
        </div>
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div>
                        <h3>
                            <i class="fa fa-table"></i>
                            Collection Run Sheet As Of <?=$collection_date_from?> -  <?=$collection_date_to?> <?=empty($driver_names) ? '' : 'for '.implode(', ', $driver_names)?>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records))
                    {
                    ?>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                            <thead>
                                    <tr>
                                            <th>S.No.</th>
                                            <th>By</th>
                                            <th>Box</th>
                                            <th>Sender</th>
                                            <th>Address</th>
                                            <th>Contact</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                            <th>$</th>
                                            <th>Destination</th>
                                            <th>Comments</th>
                                    </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                                $sno= 1;
                                
                                $boxes_count = array();
                                
                                foreach ($records as $index => $row)
                                {
                                    $count = $row['count'];
                                    $boxes = explode('@@##@@', $row['box']);
                                    $locations = explode('@@##@@', $row['location']);
                                    $quantity = explode('@@##@@', $row['quantity']);
                                    $price = explode('@@##@@', $row['total_price']);
                                    
                                    for ($i = 0; $i < $count; $i++)
                                    {
                                        if (empty($boxes_count[$boxes[$i]]))
                                        {
                                            $boxes_count[$boxes[$i]] = $quantity[$i];
                                        }
                                        else
                                        {
                                            $boxes_count[$boxes[$i]] += $quantity[$i];
                                        }
                            ?>
                                    <tr>
                                            <td><?=$sno++;?></td>
                                            <td><?=ucwords($row['username'])?></td>
                                            <td><?=$row['order_number']?></td>
                                            <td><?=ucwords($row['customer'])?></td>
                                            <td><?=$row['address']?></td>
                                            <td><?=ucwords($row['contacts'])?></td>
                                            <td><?=ucwords($boxes[$i])?></td>
                                            <td><?=$quantity[$i]?></td>
                                            <td><?=$price[$i]?></td>
                                            <td><?=ucwords($locations[$i])?></td>
                                            <td><?=ucwords($row['collection_notes'])?></td>
                                    </tr>
                            <?php
                                    }
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
                                $box_count = 0;
                                
                                foreach ($boxes_count as $box => $count)
                                {
                                    $box_count += $count;
                                    
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
                                echo "<tr>"
                                . " <th>Total Jobs</th>"
                                        . "<td><b>".count($records)." </b></td>"
                                . " <th>Total Boxes</th>"
                                        . "<td><b>".$box_count." </b></td>"
                                . "</tr>"
                                ?>
                            </table>
                            
                        <?php
                        }
                        ?>
                    </div>
                
                    <!--</div>-->
                    <?php
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
        $("#drivers").multiselect();      
               
        <?php
        if (!empty($records))
        {
        ?>              
        $("#btnPrint").click (function() {
            printElement(document.getElementById("reportContainer"));
            window.print();
        });
        <?php
        }
        ?>
})
</script>