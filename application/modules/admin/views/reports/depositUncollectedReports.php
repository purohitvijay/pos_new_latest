<div class="container-fluid">
    
    
    <div class="page-header">
        
        <div class="row" >
            <form action="<?=base_url()?>admin/report/depositsUncollectedReports" method="post">
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
                            Deposit Uncollected Reports As Of <?=$collection_date_from?> -  <?=$collection_date_to?> <?=empty($driver_names) ? '' : 'for '.implode(', ', $driver_names)?>
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
                                            <th>Date</th>
                                            <th>Order Id</th>                                            
                                            <th>Driver</th>                                            
                                            <th>Box Qty.</th>                                            
                                            <th>Address</th>
                                            <th>Remarks</th>                                            
                                            <th>Total</th>
                                            <th>Uncollected Amount</th>                                            
                                    </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                                $net_total = 0;
                                $uncollected_amount_total = 0;
                                
                                foreach ($records as $index => $row)
                                {
                                    echo "<tr>";
                                    echo "<td>".$row['collection_date']."</td>";
                                    echo "<td>".$row['order_number']."</td>";
                                    echo "<td>".$row['username']."</td>";
                                    echo "<td>".$row['count']."</td>";
                                    echo "<td>".$row['address']."</td>";
                                    echo "<td>".$row['remarks']."</td>";
                                    echo "<td>".$row['nett_total']."</td>";
                                    echo "<td>".$row['uncollected_amount']."</td>";
                                    
                                    $count = count($records);
                                    $net_total += $row['nett_total'];
                                    $uncollected_amount_total += $row['uncollected_amount'];
                                    echo "</tr>";
                                }
                                
                                echo "<tr><td colspan=6>&nbsp;</td>";
                                echo "<td>".$net_total."</td>";
                                echo "<td>".$uncollected_amount_total."</td></tr>";
                                ?>
                            </table>
                            <div>
                                <h3>
                                    <i class="fa fa-table"></i>
                                    Summary
                                </h3>
                            </div>
                    <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always">
                        <tr>
                            <th colspan="3" style='width:30%'>Total Order</th>
                            <td style='width:20%'> <b><?= $count?></b></td>
                            <th colspan="3" style='width:30%'>Uncollected Amount Total</th>
                            <td style='width:20%'>$ <b><?= $uncollected_amount_total?></b></td>
                        </tr>
                    </table>
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