<div class="container-fluid">
    
    
    <div class="page-header">
        
        <div class="row">
            <form action="<?=base_url()?>admin/report/driverCollectionSheet" method="post">
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
                        <div class="pull-left" style="padding-left:10px">
                            <button type="submit" class="btn btn-primary" >Report</button>
                        </div>
                </div>

                <div class="pull-right">
                    <?php
                    if (!empty($records['header']))
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
                            Collection Reports As Of <?=$collection_date_from?> -  <?=$collection_date_to?> <?=empty($driver_names) ? '' : 'for '.implode(', ', $driver_names)?>
                        </h3>
                    </div>
                    <?php
//                    p($records,0);
                    if (!empty($records['header']))
                    {
                    ?>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable" >
                            <thead>
                                    <tr>
                                        <th></th>
                      <?php
                      
                        foreach($records['header'] as $index => $value)
                                        {   
                                          echo "<th>".$value['name'].'</th>'; 
                                        }
                                        
                      
                      ?>
                                        <th>Totals</th>   
                                    </tr>
                            </thead>
                            
                            <tbody>
                         
                            <?php
                                    $date_data = $records['dateData'];
                                    $header = $records['header'];
                                    $totalArr = array();
                                    foreach($date_data as $id => $date_record)
                                    {
                                        $total =0;
                                        echo "<tr><td>".$id.'</td>';
                                        foreach($header as $index => $value)
                                        {
                                         $count = 0;   

                                           if(isset($date_record[$index]))
                                            {
                                                foreach ($date_record[$index]['box'] as $key => $box)
                                                {
                                                 $count +=  $box;  
                                                }
                                               echo "<td>".$count."</td>";
                                               $total += $count;
                                               if(isset($totalArr[$index]['boxe_count']))
                                               $totalArr[$index]['boxe_count'] += $count;    
                                               else
                                               $totalArr[$index]['boxe_count'] = $count;
                                            }
                                            else
                                            {
                                                echo "<td></td>";
                                            }
                                         }
                                            echo "<td>".$total."</td>";
                                            echo "</tr>";
                                            }
                                            
                                      echo '<tr><td>STotal</td>';
                                      $finalTotal = 0;
                                      foreach ($header as $key => $va)
                                      {
                                          if(isset($totalArr[$key]['boxe_count']))
                                          {
                                              echo "<td>".$totalArr[$key]['boxe_count']."</td>";    
                                              $finalTotal+= $totalArr[$key]['boxe_count'];
                                        
                                          }   else
                                              echo "<td></td>";
                                      }
                                      echo "<td>".$finalTotal."</td></tr>";
                                      ?>
                                
                                
                                
                                
                                
                            </tbody>
                    </table>
                   
                    
                    
                    <br><br>
                    <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable" >
                            <thead>
                                    <tr>
                                        <th></th>
                      <?php
                      
                        foreach($records['header'] as $index => $value)
                                        {   
                                          echo "<th>".$value['name'].'</th>'; 
                                        }

                                         echo "<th>Totals</th>";
                      ?>
                                    </tr>
                            </thead>
                            
                            <tbody>
                         
                            <?php
                                    $box_data = $records['boxData'];
                                    $header = $records['header'];
                                    $totalArr1 = array();
                                    foreach($box_data as $id => $box_record)
                                    {
                                        $total =0;
                                        echo "<tr><td>".$id.'</td>';
                                        foreach($header as $index => $value)
                                        {
                                         $count = 0;   
//
                                           if(isset($box_record[$index]))
                                            {
                                               echo "<td>".$box_record[$index]."</td>";
                                               $total += $box_record[$index];
                                               if(isset($totalArr1[$index]['boxe_count']))
                                               $totalArr1[$index]['boxe_count'] += $box_record[$index];    
                                               else
                                               $totalArr1[$index]['boxe_count'] = $box_record[$index];
                                               
                                            }
                                            else
                                            {
                                                echo "<td></td>";
                                            }
                                            
                                         }
                                         echo "<td>".$total."</td>";
                                            
                                            echo "</tr>";
                                            }
                                      
                                      echo '<tr><td>STotal</td>';
                                      $finalTotal =0;
                                      foreach ($header as $key => $va)
                                      {
                                          if(isset($totalArr1[$key]['boxe_count']))
                                          {
                                              echo "<td>".$totalArr1[$key]['boxe_count']."</td>";    
                                              $finalTotal+= $totalArr1[$key]['boxe_count'];
                                          }
                                          else
                                              echo "<td></td>";
                                      }
                                      echo "<td>".$finalTotal."</td></tr>"; 
//                                      
//                                      ?>
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
        $('.datepick2').datepicker({
            format: "dd/mm/yyyy"
        })
        
        $("#drivers").multiselect();
                    
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