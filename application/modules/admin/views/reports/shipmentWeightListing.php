<div class="container-fluid">
    
    
    <div class="page-header">
        
        <div class="row">
            <form action="<?=base_url()?>admin/report/shipmentWeightListing" method="post">
                <div class="pull-left form-group">
                        <?php
                        if (!empty($shipmentBatchesArr))
                        {  
                        ?>
                        <label for="drivers" class="control-label pull-left" style="padding-left:10px">
                            Shipment  Batch
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <select id="shipment" name="shipment_batch_ids[]" multiple class="form-control">
                                    <?php
                                    $shipment_names = array();
                                    foreach ($shipmentBatchesArr as $index => $row)
                                    {
                                        $selected = !empty($shipment_selected) && in_array($row['id'], $shipment_selected) ? 'Selected' : '';
                                        
                                        if (!empty($shipment_selected) && in_array($row['id'], $shipment_selected))
                                        {
                                            $shipment_names[] = "<b>{$row['name']}</b>";
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
                        <div class="pull-left" style="padding-left:10px">
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
                            Luar Jawa Summary Reports  <?=empty($shipment_names) ? '' : 'for '.implode(', ', $shipment_names)?>
                        </h3>
                    </div>
                    <?php
//                    p($records,0);
                    if (!empty($records))
                    {
                    ?>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable" >
                            <thead>
                                    <tr>
                                        <th>Sh No</th>
                                        <th>Id</th>
                                        <th>Qty</th>
                                        <th>Sender</th>
                                        <th>Dest</th>
                                        <th>Kab</th>
                                        <th>Wt_SG</th>
                                        <th>Wt_ID</th>
                                        <th>Discrepany</th>
                                        <th>Date Received</th>
                                        <th>Penerima</th>
                                    </tr>
                            </thead>
                            
                            <tbody>
                         
                            <?php
                                    $totalQty = 0;
                                    $totalWt =0;
                                    $totalWtJkt =0;
                                    $discrepany =0;
                                    foreach($records as $id => $date_record)
                                    {
                                            $totalQty +=$date_record['quantities'];
                                            $totalWt +=$date_record['weight'];
                                            $totalWtJkt +=$date_record['jkt_weight'];
                                            $discrepany +=$date_record['discrepany'];
                                            echo "<tr>";
                                            echo "<td>".$date_record['batch_name']."</td>";
                                            echo "<td>".$date_record['order_number']."</td>";
                                            echo "<td>".$date_record['quantities']."</td>";
                                            echo "<td>".$date_record['customer_name']."</td>";
                                            echo "<td>".$date_record['locations']."</td>";
                                            echo "<td>".$date_record['kabupatens']."</td>";
                                            echo "<td>".$date_record['weight']."</td>";
                                            echo "<td>".$date_record['jkt_weight']."</td>";
                                            echo "<td>".$date_record['discrepany']."</td>";
                                            echo "<td>".$date_record['received_date']."</td>";
                                            echo "<td>".$date_record['jkt_receiver']."</td>";
                                            echo "</tr>";
                                     }
                                     
                                     echo "<tr>"
                                     . "<td>Totals</td>";
                                     echo "<td>&nbsp;</td>";
                                     echo "<td>".$totalQty."</td>";
                                     echo "<td>&nbsp;</td>";
                                     echo "<td>&nbsp;</td>";
                                     echo "<td>&nbsp;</td>";
                                     echo "<td>".$totalWt."</td>";
                                     echo "<td>".$totalWtJkt."</td>";
                                     echo "<td>".$discrepany."</td>";
                                     echo "<td>&nbsp;</td>";
                                     echo "<td>&nbsp;</td>";
                                     echo "</tr>";
                                      ?>
                             </tbody>
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
        $("#shipment").multiselect();
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

