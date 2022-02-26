<body class="theme-lightred" data-theme="theme-lightred">
	<div id="navigation">
		<div class="container-fluid">
			<a href="<?=base_url()?>admin/users/index" id="brand" style="width:10px">Postki</a>
			<ul class='main-nav'>
 <?php        
//                foreach ($topMenu as $idx => $row) {
//                    $active_controller = explode("/", $row['path']);
//
//                    if (in_array($controller, $active_controller)) {
//                        $class = "active";
//                    } else {
//                        $class = "";
//                    }
//                    ?>
                    <!--<li class="//<?php echo $class; ?>" >-->    
                        <?php // if (!empty($row['subMenu']))
//                        {?>
<!--                        <a href="//<?php echo base_url() . $row['path']; ?>" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>//<?php echo $row['menuName']; ?></span>      
                            <span class="caret"></span>
                        </a> -->
                        <?php // }
//                        else
//                        {
//                            ?>
<!--                            <a href="//<?php // echo base_url() . $row['path']; ?>" >                            
                            <i class="icon-edit"></i>
                            <span>//<?php // echo $row['menuName']; ?></span>
                        </a> -->
                     <?php //   }
//                        ?>
                        
                        <?php
//                        if (!empty($row['subMenu'])) {
//                            ?>
                            <!--<ul class="dropdown-menu">-->     
                                <?php
//                                foreach ($row['subMenu'] as $id => $rec) {
//
//                                    $path_arr = explode("/", $rec['path']);
//                                    if (in_array($action, $path_arr)) {
//                                        $class = "active";
//                                    } else {
//                                        $class = "";
//                                    }
//                                    ?>
<!--                                    <li class="//<?php // echo $class; ?>">
                                        <a href="//<?php // echo base_url() . $rec['path']; ?>">
                                            //<?php // echo $rec['subMenuName']; ?></a>-->
                                    <!--</li>-->
                                <?php // }
//                                ?>
                            <!--</ul>-->
                            <?php
//                        }
//                        ?>

                    <!--</li>-->
                <?php
//                    }
                    
                    
                    if ($geo_type == 'singapore' || $geo_type == 'all')
                    {
                ?>
                    
                    
                    <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Masters</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">     
                            <li class="">
                                <a href="<?=base_url()?>admin/masters/boxList?haveSideBar=0">
                                    Boxes
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/masters/locationList?haveSideBar=0">
                                    Locations
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/masters/agentList?haveSideBar=0">
                                    Agents
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/masters/codeList?haveSideBar=0">
                                    Codes
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/masters/locationBoxMapping?haveSideBar=0">
                                    Location Box Mapping
                                </a>
                            </li>
                            <?php
                            if($this->session->userdata['id'] == '2')
                            {
                            ?>
                             <li class="">
                                <a href="<?=base_url()?>admin/masters/userList?haveSideBar=0">
                                    Users
                                </a>
                            </li>
                            <?php }?>
                             <li class="">
                                <a href="<?=base_url()?>admin/masters/pass_typeList?haveSideBar=0">
                                    Pass Type
                                </a>
                            </li>
                             <li class="">
                                <a href="<?=base_url()?>admin/masters/customer_typeList?haveSideBar=0">
                                    Customer Type
                                </a>
                            </li>
                             <li class="">
                                <a href="<?=base_url()?>admin/masters/categoriesList?haveSideBar=0">
                                    Media Categories
                                </a>
                            </li>
                             <li class="">
                                <a href="<?=base_url()?>admin/masters/media_typeList?haveSideBar=0">
                                    Media Type
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Reports</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="<?=base_url()?>admin/report/boxCollectionDateReport?haveSideBar=0">
                                    Box Collection Date Report
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/deliveryRunSheet?haveSideBar=0">
                                    Box Delivery
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/collectionRunSheet?haveSideBar=0">
                                    Box Collection
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/getCollectionCall?haveSideBar=0">
                                    Collection Call
                                </a>
                            </li>
                            <li class="">
                                <!--
                                <a href="<?=base_url()?>admin/report/getReport?haveSideBar=0">
                                    Labels & Forms
                                </a>
                                -->
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/getLiveFeeds?haveSideBar=0">
                                    Live Feeds
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/EODReports?haveSideBar=0">
                                    EOD
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/agentCommission?haveSideBar=0">
                                    Agent Commission
                                </a>
                            </li> 
                            <li class="">
                                <a href="<?=base_url()?>admin/report/destBoxesReports?haveSideBar=0">
                                    Dest / Boxes Breakdown
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/depositsUncollectedReports?haveSideBar=0">
                                    Deposits Uncollected
                                </a>
                            </li>
                            
                            <li class="">
                                <a href="<?=base_url()?>admin/report/driverCollectionSheet?haveSideBar=0">
                                    Driver Collection Sheet
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/driverDeliverySheet?haveSideBar=0">
                                    Driver Delivery Sheet
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/deliveryPerformanceJakarta?haveSideBar=0">
                                    Delivery Performance Jakarta
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/shipmentWeightListing?haveSideBar=0">
                                    Luar Jawa Summary
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/deliveredBoxesReports?haveSideBar=0">
                                    Delivered Boxes Report
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/deliveredReports?haveSideBar=0">
                                    Delivery Status Jakarta
                                </a>
                            </li>
                            <li class="">
                                    <a href="<?=base_url()?>admin/report/promoReport?haveSideBar=0">
                                    Promo Report
                                </a>
                            </li>
                            <li class="">
                                    <a href="<?=base_url()?>admin/report/CustomerLoyaltyReport?haveSideBar=0">
                                    Customer Loyalty
                                </a>
                            </li>
                            <li class="">
                                    <a href="<?=base_url()?>admin/report/CustomerProfilingReport?haveSideBar=0">
                                    Customer Profiling
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                     <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Orders</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">     
                            <li class="">
                                <a href="<?=base_url()?>admin/order/index?haveSideBar=0">
                                    Listing
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/order/orderBookingForm?haveSideBar=0">
                                    Entry Form
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/order/customerList?haveSideBar=0">
                                    Customers' Details
                                </a>
                            </li>
                             <li class="">
                                <a href="<?=base_url()?>admin/order/pictureDateInput?haveSideBar=0">
                                    Picture Date Input
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/order/shipmentInQuiry?haveSideBar=0">
                                    Shipment Inquiry
                                </a>
                            </li>
                        </ul>
                    </li>
                        
                     <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Print Module</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">     
                            <li class="">
                                <a href="<?=base_url()?>admin/order/batchPrint?haveSideBar=0">
                                    Batch Printing
                                </a>
                            </li>
                        </ul>
                    </li>
                       <?php
                       $user_id = $this->session->userdata['id'];
                      $canPerform = canPerformAction('luckydraw',$user_id);
                      if($canPerform === TRUE)
                      {
                       ?>
                       
                     <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Lucky Draw</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">     
                            <li class="">
                                <a href="<?=base_url()?>admin/luckyDraw/luckyDrawList?haveSideBar=0">
                                    Lucky Draw
                                </a>
                            </li>
                            <li class="">
                                <a href="https://luckydraw.postkicrm.com/get-details.php" target="blank">
                                    Front End
                                </a>
                            </li>
                            <li class="">
                                <a href="https://luckydraw.postkicrm.com/index.php" target="blank">
                                    Front End (Auto)
                                </a>
                            </li>

                        </ul>
                    </li>
                    <?php }   ?>
                    
                    <?php
                    $user_id = $this->session->userdata['id'];
                    $canPerform = canPerformAction('miscellaneous',$user_id);
                    if($canPerform === TRUE)
                    {   ?>
                    <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                                    <i class="icon-edit"></i>
                                    <span>Misc</span>      
                                    <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">     
                            <li class="">
                                <a href="<?= base_url() ?>admin/miscellaneous/postalCodeList?haveSideBar=0">
                                    Postal Codes
                                </a>
                            </li>
                            <li class="">
                                <a href="<?= base_url() ?>admin/miscellaneous/customerCleanup">
                                    Housekeeping-Customer Clean Up
                                </a>
                            </li>
                            <li class="">
                                <a href="<?= base_url() ?>admin/miscellaneous/cleanImage">
                                    Housekeeping-Clean Image
                                </a>
                            </li>
                            <li class="">
                                <a href="<?= base_url() ?>admin/miscellaneous/cleanData">
                                    Housekeeping-Clean Data
                                </a>
                            </li>
                            <li class="">
                                <a href="<?= base_url() ?>admin/miscellaneous/promotion">
                                    Promotion
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                    } ?> 
                    
                    <?php
                    $user_id = $this->session->userdata['id'];
                    $canPerform = canPerformAction('commission_module',$user_id);
                    if($canPerform === TRUE)
                    {
                       ?>
                    <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Costing</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="<?=base_url()?>admin/commission/paymentReferenceList?haveSideBar=0">
                                    Payment References
                                </a>
                            </li>
                     
<!--                            <li class="">
                                <a href="<?=base_url()?>admin/shipmentcost/shipmentReferenceList?haveSideBar=0">
                                    Shipment
                                </a>
                            </li>-->
                            <li class="">
                                <a href="<?=base_url()?>admin/shipmentcost/shipmentCostingEntry?haveSideBar=0">
                                    Shipment Costing Master
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/shipmentcost/shipmentPaymentProcessingList?haveSideBar=0">
                                    Shipment Payment Processing
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/shipmentcost/shipmentCostingReportList?haveSideBar=0">
                                    Shipment Costing Report
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/order/outStandingOrderPayment?haveSideBar=0">
                                    Outstanding Order Payment
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php
                      }
                    }
                    
                    
                    
                    if ($geo_type == 'jakarta' || $geo_type == 'all' || $geo_type == 'singapore')
                    {
                    ?> 
                        <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Warehouse</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">     
                            <li class="">
                                <a href="<?=base_url()?>admin/masters/shipmentBatchList?haveSideBar=0">
                                    Shipping
                                </a>
                            </li>
                            <?php
                            if ($geo_type !== 'jakarta')
                            {
                            ?>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/weeklyCollectionReports?haveSideBar=0">
                                    Weekly Collection Forecasts
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                    }
                    if ($geo_type == 'jakarta' || $geo_type == 'all')
                    {
                    ?>
                    
                    <li>    
                        <a href="<?php echo base_url() . 'admin/receiving_batch?haveSideBar=0'; ?>" >                            
                            <i class="icon-edit"></i>
                            <span>Receiving Batch</span>
                        </a> 
                    </li>
                    
                    <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Update Orders</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">
                            <li class="">
                                <a href="<?php echo base_url() . 'admin/receiving_batch/getOrdersListingAtJakarta?haveSideBar=0'; ?>" >                            
                                    <i class="icon-edit"></i>
                                    <span>Edit Orders</span>
                                </a>
                            </li>
                            <li class="">
                                <a href="<?php echo base_url() . 'admin/order/imageUploadJktForm?haveSideBar=0'; ?>" >                            
                                    <i class="icon-edit"></i>
                                    <span>Order Images</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                   
                    <li class="" >    
                        <a href="#" data-toggle="dropdown" class='dropdown-toggle'>                            
                            <i class="icon-edit"></i>
                            <span>Reports Jkt</span>      
                            <span class="caret"></span>
                        </a> 
                                                
                        <ul class="dropdown-menu">
                             <li class="">
                                <a href="<?=base_url()?>admin/report/photoReports?haveSideBar=0">
                                    Photo
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/report/getLiveFeedsJkt?haveSideBar=0">
                                    Live Feeds
                                </a>
                            </li>
                            <li class="">
                                <a href="<?=base_url()?>admin/receiving_batch/getOrderWeightListingJkt?haveSideBar=0">
                                   Listings
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                <?php   
                }
                ?>

			</ul>
                        
                        
			<div class="user">
				<div class="dropdown">
					<a href="#" class='dropdown-toggle' data-toggle="dropdown"><?=$template_name?></a>
					<ul class="dropdown-menu pull-right">
						<li>
							<a href="<?php echo base_url();?>admin/users/logout">Sign out</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>    
    
    
    
