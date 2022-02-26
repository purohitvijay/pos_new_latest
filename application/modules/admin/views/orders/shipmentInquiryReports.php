
<div class="container-fluid maindiv">


    <div class="page-header">
 
        <div class="row" >
        <form action="<?= base_url() ?>admin/order/shipmentInQuiry" method="post">
                <div class="pull-left form-group"> 
                    <label for="order_number" class="control-label pull-left">
                        &nbsp;&nbsp;Order Number<br>&nbsp;&nbsp;
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group'>
                            <input type="text" name="order_number"   class="form-control big" required value='<?= $order_number ?>'>
                        </div>    
                    </div>
                    <div class="pull-left" style="padding-left:10px">
                        <button type="submit" class="btn btn-primary" >Report</button>
                    </div>
                </div>
         </form>
            
            <form action="<?= base_url() ?>admin/order/shipmentInQuiry" method="post">
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
                 
        </div>

        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div>
                        <h3 class="reportText">
                            <i class="fa fa-table"></i>
                            Shipment Inquiry  <?= empty($order_number) ? "": "For Order Number ". "<b>".  $order_number."</b>"?><?= empty($batches_names) ? '' : "For Shipment Batch ".  implode(', ', $batches_names) ?>
                        </h3> 
                    </div>
                    <?php 
                    if (!empty($records))
                    {
                            ?>
                    <div>
                            <!--<div class="box-content nopadding">-->
                            <table  id='table_data' class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                                <thead>
                                    <tr>
                                        <th>Shipment Batch</th> 
                                        <th>Loading Date</th>  
                                        <th>Ship on Board Date (SOB)</th>
                                        <th>ETA Jkt</th>
                                        <th>Order Status</th>
                                        <th>Order Status Date</th>
                                        <th>ETA JKT to Now</th>  
                                        <th>Date Collected to Now</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $current_date = new DateTime(date('Y-m-d'));
                                    
                                    foreach($records as $val)
                                    {  
                                        if(isset($val['eta_jakarta']))
                                        {  
                                            $eta_jkt_date = new DateTime(date('Y-m-d', strtotime($val['eta_jakarta'])));                                             
                                            $interval = $current_date->diff($eta_jkt_date);
                                            $eta_jakarta_to_now = $interval->format('%a days');
                                        } 
                                      
                                         
                                        if(isset($val['collection_date']))
                                        {  
                                            $collection_date = new DateTime(date('Y-m-d', strtotime($val['collection_date']))); 
                                            $interval = $current_date->diff($collection_date);
                                            $val['collection_date'] = $interval->format('%a days');
                                        } 
                                        
                                        $val['batch_name'] = isset($val['batch_name']) ? $val['batch_name'] : '--';
                                        $val['load_date'] = isset($val['load_date']) ? date('d/m/Y', strtotime($val['load_date'])) : '--';
                                        $val['ship_onboard'] = isset($val['ship_onboard']) ? date('d/m/Y', strtotime($val['ship_onboard'])) : '--';
                                        $eta_jakarta_to_now = isset($eta_jakarta_to_now) ? $eta_jakarta_to_now : '--'; 
                                        $val['eta_jakarta'] = isset($val['eta_jakarta']) ? date('d/m/Y', strtotime($val['eta_jakarta'])) : '--';
                                        $val['status_display_text'] = isset($val['status']) ? $statutes[$val['status']]['display_text'] : '--'; 
                                        $val['status'] = isset($val['status']) ? $val['status'] : '--';
                                       
                                        if($val['status'] == "delivered_at_jkt_picture_not_taken" || $val['status'] == "delivered_at_jkt_picture_taken")
                                        {
                                            $val['order_stauts_date'] = isset($val['order_stauts_date']) ? date('d/m/Y h:i:s', strtotime($val['order_stauts_date'])) : "--";
                                        }
                                        else
                                        {
                                            $val['order_stauts_date'] = "--";
                                        }
                                        $val['collection_date'] = isset($val['collection_date']) ? $val['collection_date'] : '--';
                                        
                                        echo"<tr>";
                                        echo"<td>".$val['batch_name']."</td>";
                                        echo"<td>".$val['load_date']."</td>";
                                        echo"<td>".$val['ship_onboard']."</td>";
                                        echo"<td>".$val['eta_jakarta']."</td>";
                                        echo"<td>".$val['status_display_text']."</td>";
                                        echo"<td>".$val['order_stauts_date']."</td>";
                                        echo"<td>".$eta_jakarta_to_now."</td>";
                                        echo"<td>".$val['collection_date']."</td>";
                                        echo"</tr>";
                                    }
                                    ?>
                                </tbody>    
                            </table>
                            </div>
                             <?php
                        
                        echo "</br>";
                        echo "</br>";
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
            $("#shipment_batch").multiselect();
        })
</script>