<form class="form-horizontal" role="form" id="shipmentBatchesUpdateForm">
    <input type="hidden" name="receiving_batch_id" class="fake-order-id" value="<?php echo $receiving_batch_id; ?>">
    <div class="form-group">
        <div class="col-md-12">
            <div class="form-group row">

                <label for="textfield" class="control-label col-sm-3"><b>Name</b><span class="required">*</span></label>

                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="name" id="receiving_batch_name_update" class="form-control" required="required" value="<?php echo $name; ?>">
                    </div>
                </div> 
            </div>
            <div class="form-group row">
                <label for="textfield" class="control-label col-sm-3"><b>Select Shipment Batches</b><span class="required">*</span></label>

                <div class="col-md-4">
                    <div class="input-group">

                        <?php
                        if (!empty($selected_shipment_batch_id))
                        {
                            ?>
                            <select id="shipment_batches_update" name="shipment_batches_selected[]" multiple class="multiselect" required="required">
                                <?php
                                $str = "";
                                foreach ($selected_shipment_batch_id as $index => $row)
                                {
                                    $str .= "<option disabled='disabled' value='{$row}' selected>{$selected_shipment_batch_name[$index]}</option>";
                                }
                                
                                if (!empty($shipmentBatchesArr))
                                {
                                    ?>

                                    <?php
                                    foreach ($shipmentBatchesArr as $index => $row)
                                    {

                                        $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                    <?php
                                }
                                echo $str;
                                echo "</select>";
                            }
                            else
                            {
                                echo "No shipment batches found.";
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>                  
    </div>
</form>
<script>
    jQuery(document).ready(function () {
        $(".multiselect").multiselect();
    });
</script>