<?php
if (!empty($promotion_boxes)) {
    ?>
    <div class="row promoCodeList">
        <div class="col-sm-12">
            <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3>
                        <i class="fa fa-table"></i>
                        Select a PromoCode
                    </h3>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin promoTable">
                        <thead>
                            <tr>
                                <th>Name</th> 
                                <th>Amount</th>
                                <th class='hidden-1024'></th>
                            </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" name='selectedPromoIdDetails' id="promoIdHidden">
                        <?php
                        foreach ($promotion_boxes as $index => $row) {
                            ?>
                            <tr>
                                <td id="name_<?= $row['id']; ?>"><?= $row['name']; ?></td>


                                <td id="block_<?= $row['id']; ?>"><?= $row['amount']; ?></td>


                                <!--selectPromoRadio-->
                                <td><input onclick="$('#promoIdHidden').val(this.value)" type="radio" name="selectPromoCode" class="selectPromoRadio" value="<?= $row['id'] . '@#' . $row['amount'] . '@#' . $row['box_id'] . "@#" . $row['name']; ?>"></td>


                            <input type="hidden" id="amount" value="<?= $row['amount']; ?>">


                            </tr>

        <?php
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
?>