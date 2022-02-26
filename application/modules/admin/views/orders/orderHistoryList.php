<?php
if (!empty($results))
{
?>
<div class="row">
        <div class="col-sm-12">
                <div class="box box-color box-bordered">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-table"></i>
                                        Order History
                                </h3>
                        </div>
                        <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin">
                                        <thead>
                                                <tr>
                                                        <th>Order Number</th>
                                                        <th>Order Date</th>
                                                        <th>Delivery Date</th>
                                                        <th>Collection Date</th>
                                                        <th>Grand Total</th>
                                                        <th>Discount</th>
                                                        <th>Nett Total</th>
                                                        <th>Operations</th>
                                                </tr>
                                        </thead>
                                        <tbody>
<?php
        foreach ($results as $index => $row)
        {

?>
                                                <tr>
                                                        <td><?=$row['order_number'];?></td>
                                                        <td><?=$row['order_date'];?></td>
                                                        <td><?=$row['delivery_date'];?></td>
                                                        <td><?=$row['collection_date'];?></td>
                                                        <td><?=$row['grand_total'];?></td>
                                                        <td><?=$row['discount'];?></td>
                                                        <td><?=$row['nett_total'];?></td>
                                                        <td>
                                                            <a href="#" title="Status Track"><i class="glyphicon-history"></i></a>
                                                            &nbsp;
                                                            <a href="#" title="Repeat"><i class="glyphicon-repeat"></i></a>
                                                            &nbsp;
                                                            <a href="#" rel="<?=$row['id'];?>" class="detailsRowFakeClass" title="Timeline"><i class="glyphicon-truck"></i></a>
                                                            &nbsp;
                                                            <a target="_new" href="<?=base_url()?>admin/order/printNow/<?=$row['id'];?>" title="Print"><i class="glyphicon-print"></i></a>
                                                        </td>
                                                </tr>
                                                
                                                <?php
                                                if (!empty($row['employee_order_status']))
                                                {
                                                ?>
                                                <tr class="hide" id="row_<?=$row['id'];?>">
                                                    <td colspan="8">
                                                        
                                                        
                                                        
                                                        
                                                        <div class="box-content nopadding">
								<ul class="timeline">
                                                                        <?php
                                                                        $statuses = explode('@@##@@', $row['employee_order_status']);
                                                                        $comments = explode('@@##@@', $row['comments']);
                                                                        $users = explode('@@##@@', $row['users']);
                                                                        $status_update_time = explode('@@##@@', $row['status_update_time']);
                                                                        $cash_collected = explode('@@##@@', $row['cash_collected']);
                                                                        $voucher_cash = explode('@@##@@', $row['voucher_cash']);
                                                                        $reassigned_stage = explode('@@##@@', $row['reassigned_stage']);
                                                                        $reassigned_from = explode('@@##@@', $row['reassigned_from']);
                                                                        
                                                                        foreach ($statuses as $inner_index => $status)
                                                                        {
                                                                        ?>
									<li>
										<div class="timeline-content">
											<div class="left">
												<div class="icon">
													<i class="fa <?=$global_status[$status]['glyphicon']?>"></i>
												</div>
												<div class="date"><?=date('d-m-Y H:i:s', strtotime($status_update_time[$inner_index]))?></div>
											</div>
											<div class="activity">
												<div class="user">
													<a href="#"><?=$users[$inner_index]?></a>
                                                                                                        <span><?=ucwords(str_replace('_', ' ', $status))?></span>
                                                                                                        <?php
                                                                                                        if ($cash_collected[$inner_index] !== '' && $cash_collected[$inner_index] != 0)
                                                                                                        {
                                                                                                        ?>
                                                                                                            <br/><span class="fa fa-usd">&nbsp;<?=$cash_collected[$inner_index]?> Cash Collected</span>
                                                                                                        <?php
                                                                                                        }
                                                                                                        
                                                                                                        if ($comments[$inner_index] !== '' && $comments[$inner_index] !== null)
                                                                                                        {
                                                                                                        ?>
                                                                                                            &nbsp;&nbsp;<span class="fa fa-comments">&nbsp;<?=$comments[$inner_index]?></span>
                                                                                                        <?php
                                                                                                        }
                                                                                                        
                                                                                                        if ($voucher_cash[$inner_index] !== '0.00' && $voucher_cash[$inner_index] !== '' && $voucher_cash[$inner_index] !== null)
                                                                                                        {
                                                                                                        ?>
                                                                                                            &nbsp;&nbsp;<span class="fa fa-gift">&nbsp;<?=$voucher_cash[$inner_index]?> Voucher Cash Used</span>
                                                                                                        <?php
                                                                                                        }
                                                                                                        
                                                                                                        if ($reassigned_stage[$inner_index] === 'yes')
                                                                                                        {
                                                                                                        ?>
                                                                                                            &nbsp;&nbsp;<span class="fa fa-random">Reassigned from <b><?=$reassigned_from[$inner_index]?></b></span>
                                                                                                        <?php
                                                                                                        }
                                                                                                        ?>    
												</div>
												
											</div>
										</div>
										<div class="line"></div>
									</li>
                                                                        <?php
                                                                        }
                                                                        
                                                                        if ($row['status'] == 'cancelled')
                                                                        {
                                                                        ?>
                                                                            <li>
										<div class="timeline-content">
											<div class="left">
												<div class="icon">
													<i class="fa glyphicon-ban"></i>
												</div>
												<div class="date"><?=date('d-m-Y H:i:s', strtotime($row['updated_at']))?></div>
											</div>
											<div class="activity">
												<div class="user">
													<a href="#"><?=$row['cancelled_by']?></a>
                                                                                                        <span>Order Cancelled</span>
												</div>
												
											</div>
										</div>
										<div class="line"></div>
                                                                            </li>
                                                                        <?php
                                                                        }
                                                                        ?>
								</ul>
							</div>
                                                        
                                                        
                                                        
                                                        
                                                        
                                                    </td>
                                                </tr>
                                                
<?php
                                                }
        }
?>
                                                
                                        </tbody>
                                </table>
                            </div>
            </div>
        </div>
    </div>
<?php
}
else
{
?>
<div class="alert alert-warning alert-dismissable">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <strong>Oops! </strong>No records Found.
</div>
<?php
}
?>

<script type="text/javascript">
$(document).ready(function (){
    $('body').on('click', '.detailsRowFakeClass', function (){
        id = $(this).attr('rel')
        $('#row_'+id).toggleClass('hide');
    })
})
</script>