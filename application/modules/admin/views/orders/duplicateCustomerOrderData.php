<style type="text/css">
    div.modal-footer
    {
        display:none;
    }
</style>
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            Alert
                        </h3>
                    </div>
                    <?php
                    echo "<div class='orders_text' style='font-size:16px;'><b>Seems like this order is already booked,click the order number to view the order details<br></b>";
                    if(!empty($duplicatCustomerOrdersData))
                    { 
                        foreach($duplicatCustomerOrdersData as $idx => $val)
                        { ?>
                            <a class='order_numbers' href=<?php echo base_url()."admin/order/orderBookingForm/".$val['order_id']?>><?=$val['order_number'].", "?></a>
                   <?php }  
                    } ?>       
                    <br><b>If you want to forcefully save duplicate order press save button.</b>
                    </div>
                    
                    <br/><button class="btn btn-primary forcefully_save_orders" type="button" data-bb-handler="save">Save</button>
                    <button class="btn btn-primary cancel_save_orders" type="button">Cancel</button>
                </div>
            </div>
        </div>
    </div>