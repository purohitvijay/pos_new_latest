    <section id="trackinglive">
        <div class="track-wrapper1">
            <div>
                <h3><?php echo lang('Tracking Order Number');?></h3>
            </div>
            <div class="order-txt"> <?php echo $order_number;?> </div>
            <div class="stat-row">
            </div>
            
            <!-- Status Collected-->
            <div class="track-diag">
                <?php
                if (empty($previous_status) && empty($current_status))
                {
                     echo "Order Booked";
                }
				
                if (!empty($previous_status))
                {
                    foreach ($previous_status as $idx => $rec)
                    {
                    $previous_date = isset($rec['date']) ? $rec['date'] : false;
                    ?>
                    <div class="rnd fill">
                        <div class="stat-label">
                       
                           <?php
                                switch ($rec['status'])
                                {
                                    case "box_collected":
                                        $status = "Collected";
                                    break;
                                    case "collected_at_warehouse":
                                        $status = "Singapore Warehouse";                                                   
                                    break;
                                    case "ready_for_receiving_at_jakarta":
                                        $status = "Singapore Warehouse";                                                   
                                    break;
                                    case "Shipped":
                                        $status = "Ship on board";
                                    break;
                                    case "received_at_jakarta_warehouse":
                                        $status = "Jakarta Warehouse";
                                    break;
                                    case "delivered_at_jkt_picture_not_taken":
                                        $status = "Received";
                                    break;
                                }
                                echo lang($status)."<br>";

                                if($status == "Received")
                                {
                                
                                    echo lang("By:"). $receiver." " .$receiver_date;
                                }
                                else if ($previous_date == false)
                                {
                                    echo $previous_date;
                                }
                                else
                                {
                                    echo date('d/m/y', strtotime($previous_date));
                                }
                           ?>
                        </div>
                    
                    </div>
    
                    <div class="line-1">
                    </div>
                    <?php
                    
                    }
            }?>
                <?php if(!empty($current_status))
                {
                    foreach($current_status as $idx => $statusRec)
                    {
                        ?>
                    <div class="rnd fill">
                    <div class="stat-label"><?php
                            $previous_date = isset($statusRec['date']) ? $statusRec['date'] : false;
                            echo lang($statusRec['status'])."<br>";
                            
                            if($statusRec['status'] == "Received")
                            {       
                                echo lang("By:"). $receiver." " .$receiver_date;
                            }
                            else if ($previous_date == false)
                            {
                                echo $previous_date;
                            }
                            else
                            {
                                echo date('d/m/y', strtotime($previous_date));
                            }
                            ?>
                        </div> 
                    </div>
                    <?php
                    if(isset($statusRec['next_status']))
                    {
                         
                        if($statusRec['next_status'] != "")
                        {  ?>
                            <div class="line-1 lifi">                    
                                <div class="stat">
                                    <div class='pulse1 adj-pos mobile-view'></div>
                                    <?php 
                                        if($location == "Luar Jawa" && $current_status[0]["status"] == "Jakarta Warehouse" && $reference_no == 0)
                                        { ?> 
                                        </div>
                                    </div>
                                    <?php continue; }
                                    ?>
                                    <div class="box arrow-bottom">
                                        <?php echo date('d/m/y');?>
                                        <?php echo lang('Status');?> :
                                        <?php
                                            if($current_status[0]["status"] == "Collected")
                                            {
                                                echo lang('Warehouse, pending shipping');
                                            }
                                            else if($current_status[0]["status"] == "Singapore Warehouse")
                                            {
                                                echo lang('Booked for shipping');
                                            }
                                            else if($current_status[0]["status"] == "Ship on Board")
                                            {
                                                echo lang('Transit Jakarta port');
                                            }
                                            else if($current_status[0]["status"] == "Jakarta Warehouse")
                                            {
                                                if($location == "Luar Jawa")
                                                {
                                                    if($reference_no)
                                                    {
                                                        $reference_no_arra = explode("/", $reference_no);
                                                        $print_lable = $reference_no_arra[0];
                                                        if(isset($reference_no_arra[1]))
                                                        {
                                                            $retrieved = $reference_no_arra[1];
                                                            $date = '';
                                                            if($retrieved)
                                                                $date = DateTime::createFromFormat('dmY', $retrieved);
                                                            
                                                            if($date)
                                                            	$print_lable .= " Handover date : ".$date->format('d-m-Y');  
                                                        }
                                                        
                                                        echo $print_lable;
                                                    }
                                                }
                                                else    
                                                    echo lang('Delivery in Progress');
                                            }
                                            else if($current_status[0]["status"] == "Received")
                                            {
                                                echo lang('Image download in progress');
                                            }
                                            ?>
                                    </div>
                                    <div class='pulse1 desktop-view'></div>
                                
                                </div>
                            </div>
                            <?php 
                            }
                            else if($statusRec['next_status'] == "Received")
                            {                          
                              $currentDate = date('Y-m-d');
                              $received_date = date('Y-m-d', strtotime($previous_date));
                              $datediff = strtotime($currentDate) - strtotime($received_date);
                              $days = round($datediff / (60 * 60 * 24));

                              $class = "marker";
                                  $pulseMarker = "pulseMarker";
                              if($days <= 7)
                              {
                                  $class = "marker1";
                                  $pulseMarker = "pulseMarker1";
                              }
                              else if($days >= 8)
                              {
                                  $class = "marker2";
                                  $pulseMarker = "pluseMarker2";
                              }
                              else if($days <= 14)
                              {
                                  $class = "marker2";
                                  $pulseMarker = "pluseMarker2";
                              }

                              if($days >= 15) 
                              {
                                  $class = "marker3";
                                  $pulseMarker = "pulseMarker3";
                              }
                        
                                ?>
                                <div class="line-1 lifi">                    
                                  <div class="stat">
                                      <div class='pulse1 adj-pos mobile-view <?php echo "mobile-".$pulseMarker;?>'></div>
                                      <div class="box arrow-bottom mobile-<?php echo $class;?> mobile-view">
                                          <?php echo date('d/m/y');?>
                                          <?php echo lang('Status');?> :
                                          <?php echo lang('In Transit');?> : 
                                          <?php echo lang($next_status[0]);?>
                                      </div>
                                      <div class="box arrow-bottom desktop-view <?php echo $class;?>">
                                          <?php echo date('d/m/y');?>
                                          <?php echo lang('Status');?> :
                                          <?php echo lang('In Transit');?> : 
                                          <?php echo lang($next_status[0]);?>
                                      </div>
                                          <div class='pulse1 desktop-view <?php echo $pulseMarker;?>'></div>
                                      </div>                        
                                  </div>
                            <?php
                            }
                        }
                     }
                }
                ?>
                

                <?php
                if(!empty($next_status))
                {
                    $i = 0;
                    foreach($next_status as $idx => $statusRec)
                    {
                        $i++;
                        if($current_status[0]["status"] != "Received" && ($i > 1 || count($next_status) == 1))
                        {
                        ?>
                        <div class="line-1 lifi">
                    
                        </div>
                        <?php } ?>
                        <div class="rnd rndli rnd-padding">
                            <div class="stat-label">                        
                               <?php 
                               echo lang($statusRec);?>

                            </div>
                </div><?php
                    }
                }
                if(!empty($image_id))
                {
                    foreach ($image_id as $row)
                    {?>
    
                        <a alt="download" style="margin: 5px;font-size: 22px;color: red;"href="<?php echo base_url(); ?>public/index/downloadJakartaImage/<?= $row['id'] ?>"><i class="fa fa-download"></i></a>
                     <?php
                    }
                }?>                
                
            </div>
        </div> 
    
    
    </section>
    <footer>

    </footer>

</body>

</html>