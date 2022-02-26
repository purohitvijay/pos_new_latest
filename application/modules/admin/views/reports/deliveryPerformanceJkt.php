<div class="container-fluid">


    <div class="page-header">

        <div class="row" >
            <form action="<?= base_url() ?>admin/report/deliveryPerformanceJakarta" method="post">
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
                            Delivery Performance Jakarta DCDR / DSDR
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
                                    <th rowspan="3" align="center">S.b.</th>
                                    <?php if(!empty($records['location_header']))
                                    {
                                        foreach($records['location_header'] as $idx => $rec)
                                        {
                                            echo "<th colspan='4' align='center'>".$rec."</th>";
                                        }                                        
                                    }
                                    
                                    ?>
                                </tr>
                                <tr>
                                 <?php if(!empty($records['location_header']))
                                    {
                                     
                                        foreach($records['location_header'] as $idx => $rec)
                                        {
                                            echo "<td>DCDR</td>";
                                            echo "<td>DCDR</td>";
                                            echo "<td>DSDR</td>";
                                            echo "<td>DSDR</td>";
                                        }                                        
                                    }
                                    
                                    ?>
                                </tr>
                                <tr>
                                 <?php if(!empty($records['location_header']))
                                    {
                                     
                                        foreach($records['location_header'] as $idx => $rec)
                                        {
                                            echo "<td>Min</td>";
                                            echo "<td>Max</td>";
                                            echo "<td>Min</td>";
                                            echo "<td>Max</td>";
                                        }                                        
                                    }
                                    
                                    ?>
                                </tr>
                            <tbody>
                                <?php
                                if(!empty($records['shipment_data']))
                                {
                                    foreach($records['shipment_data'] as $index => $shipment_record)
                                    {
                                        echo "<tr>";
                                        echo "<th>".$shipment_record['shipment_batch_name']."</th>";
                                        foreach($records['location_header'] as $idx => $rec)
                                        {
                                            if(isset($shipment_record[$idx]))
                                            {
                                            echo "<td>".$shipment_record[$idx]['dcdr_min']."</td>";
                                            echo "<td>".$shipment_record[$idx]['dcdr_max']."</td>";
                                            echo "<td>".$shipment_record[$idx]['dsdr_min']."</td>";
                                            echo "<td>".$shipment_record[$idx]['dsdr_max']."</td>";                                            
                                            }
                                            else
                                            {
                                                echo "<td>&nbsp;</td>";
                                                echo "<td>&nbsp;</td>";
                                                echo "<td>&nbsp;</td>";
                                                echo "<td>&nbsp;</td>";   
                                            }
                                        }
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </tbody>
                            </thead>

                            <tbody>

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