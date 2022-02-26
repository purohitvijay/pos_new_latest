
<div class="container-fluid maindiv">


    <div class="page-header">
 
        <div class="row" >
            <form action="<?= base_url() ?>admin/report/deliveredBoxesReports" method="post">
                <div class="pull-left form-group">
                    <label for="date_from" class="control-label pull-left">
                        Date<br> (From)
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group date'>
                            <input type="text" name="date_from" id="date_from" class="form-control big datepick2" required value='<?= $date_from ?>'>
                        </div>    
                    </div>
                    <label for="date_to" class="control-label pull-left">
                        &nbsp;&nbsp;Date<br>&nbsp;&nbsp; (To)
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group date'>
                            <input type="text" name="date_to" id="date_to" class="form-control big datepick2" required value='<?= $date_to ?>'>
                        </div>    
                    </div>
                    <div class="pull-left" style="padding-left:10px">
                        <button type="submit" class="btn btn-primary" >Report</button>
                    </div>
                </div>
            </form>
            
            <form action="<?= base_url() ?>admin/report/deliveredBoxesReports" method="post">
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
                    
                        <button id="downloadXls" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-download"></i>Download as Xls
                        </button>
                    
                        <button id="downloadPdf" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-file"></i>Download as Pdf
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
                        <h3 class="reportText">
                            <i class="fa fa-table"></i>
                            Delivered Boxes <?= empty($date_from) ? "": "As Of ".$date_from."-".$date_to ?><?= empty($batches_names) ? '' : 'For Shipment Batch ' . implode(', ', $batches_names) ?>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records['box_data']))
                    {
                         
                            ?>
                    <div id='div_table_data'>
                        <!--<div class="box-content nopadding">-->
                                    <?php 
                                    $location_qty_arr = array();
                                    $previous_location_name = "";
                                    foreach($records['box_data'] as $idx => $val)
                                    {
                                      $location_names = explode('@@##@@', $val['location_name']);
                                      $quantity = explode('@@##@@', $val['quantity']);   
                                      
                                      foreach($location_names as $idx => $location_names)
                                      {
                                        if (empty($location_qty_arr[$location_names]))
                                        {
                                             $location_qty_arr[$location_names] = $quantity[$idx];
                                        }
                                        else
                                        {
                                             $location_qty_arr[$location_names] += $quantity[$idx];
                                        }
                                      }
                                      if(empty($previous_location_name) || $previous_location_name != $val['location_name'])
                                      { ?>
                                        
                                        <table class="table_data table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y" style="page-break-after: always" id="menuTable">
                                            <thead>
                                              <tr>
                                                  <th>Order#</th>  
                                                  <th>Destination</th>
                                                  <th>Kabupaten</th>  
                                                  <th>Shipment Batch</th>
                                                  <th>Driver Name</th>
                                                  <th>Qty</th>
                                              </tr>
                                          </thead>
                                          <tbody>    
                                    <?php echo "<b class='b1'><h3 class='h3'>" .$val['location_name']. "</h3></b>";
                                        }  
                                        echo"<tr>";
                                        echo"<td>".$val['order_number']."</td>";
                                        echo"<td>".$val['location_name']."</td>";
                                        echo"<td>".$val['kabupatens_name']."</td>";
                                        echo"<td>".$val['batch_name']."</td>";
                                        echo"<td>".$val['driver_name']."</td>";
                                        echo"<td>".$val['quantity']."</td>";
                                        echo"</tr>"; 
                                        $previous_location_name = $val['location_name'];
                                    }
                                    ?>
                                </tbody>
                                </table>
                           
                                <b><h3>
                                    <table>
                                        <tr><th>
                                 <i class="fa fa-table"></i>
                                    Summary
                                        </th></tr>
                                    </table>
                                </h3></b>
                            
  
                            <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable">
                                <?php
                                $total_quantity = 0;
                                $i = 0;
                                foreach ($location_qty_arr as $location => $count)
                                { 
                                    $total_quantity += $count;
                                    
                                    if ($i++ % 2 == 1)
                                    {
                                       echo "<th style='width:40%'>$location</th><td style='width:10%'>$count</td></tr><tr>";
                                    }
                                    else
                                    { 
                                       echo "<tr><th style='width:40%'>$location</th><td style='width:10%'>$count</td>";
                                    }
                                }
                                echo '</tr>';
                                echo "<tr>"
                                . " <th>Collection Total</th>"
                                        . "<td><b>".$total_quantity." </b></td>"
                                . "</tr>";
                                ?>
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
                 
                $("#downloadPdf").click(function () {
                    var doc = new jsPDF('p', 'pt'); 

                    var headerText = $(".reportText").text();
                    var setHeaderText = doc.splitTextToSize(headerText, 500);

                    doc.autoTableSetDefaults({
                        addPageContent: function() {
                        doc.text(setHeaderText,1,1);        
                          },   
                    });
                       
                    var headers = $('#div_table_data .b1 .h3');
                     
                    $('#div_table_data table').each(function(index,val){ 
                     var json = doc.autoTableHtmlToJson(val); 
                     doc.text($(headers[index]).text(), 100, doc.autoTableEndPosY() + 80);
                    
                     doc.autoTable(json.columns, json.data, {
                        startY:  doc.autoTableEndPosY() + 90,
                        theme: 'grid',
                        styles: {
                          overflow: 'linebreak',
                          fontSize: 11,
                          rowHeight: 12,
                          columnWidth: 'wrap',
                          border : 2,
                        },
                        margin: {top: 80,left: 100},
                        headerStyles: {
                        cellPadding: 2,
                        lineWidth: 0,
                        valign:'top',
                        fontStyle: 'bold',
                        halign: 'left',    //'center' or 'right'
                        fillColor: [211, 211, 211],
                        textColor: [78, 53, 73], //Black     
                        rowHeight:0
                        }
                         
                    });
                    });
 
                    var a = window.document.createElement("a");
                    a.href = doc.output('datauristring');
                    
                    var today = new Date();
                    var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
                    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                    var dateTime = date+'_'+time;
                    
                    a.download = "DeliveredBoxesReport_"+dateTime+".pdf";
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);                

    });
                
                $("#downloadXls").click(function () {
                    var date_from = $("#date_from").val();
                    var date_to = $("#date_to").val();
                    var shipment_batch_ids = $("#shipment_batch").val();

                    var today = new Date();
                    var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
                    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                    var dateTime = date+'_'+time;
                    
                    if(shipment_batch_ids == null)
                    {
                       shipment_batch_ids = ""; 
                    }
                    
                    window.location.href = "<?= base_url();?>admin/report/downloadDeliveredBoxesXlsReport?date_from=" + date_from+ "&date_to=" + date_to + "&shipment_batch_ids=" +shipment_batch_ids + "&current_datetime=" +dateTime;
                });
          
<?php } ?>
        })
</script>