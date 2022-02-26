<table class="table table-hover table-nomargin table-bordered" id="menuTable">
    <tr>
        <th>Order No.</th>
        <th>Customer Name</th>
        <th>Address</th>
    </tr>
    <?php
        if (!empty($records)) {
            foreach ($records as $index => $record) {
                ?>
                <tr> 
                    <td><?= $record['order_number']; ?></td>
                    <td><?= $record['customer_name']; ?></td>
                    <td><?= $record['address']; ?></td>
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