<table class="table table-hover table-nomargin table-bordered" id="menuTable">
    <tr>
        <th>Order No.</th>
        <th>Customer Name</th>
        <th>Address</th>
    </tr>
    <?php
        if (!empty($receiving_batch_pending_orders)) {
            foreach ($receiving_batch_pending_orders as $index => $order_details) {
                ?>
                <tr> 
                    <td><?= $order_details['order_number']; ?></td>
                    <td><?= $order_details['customer_name']; ?></td>
                    <td><?= $order_details['block'].",". $order_details['unit']."<br>,". $order_details['street']. $order_details['pin']; ?></td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="3">Oops! No Records found.</td>
            </tr>
        <?php }
    ?>
</table>