<?php
if (!empty($codes_arr))
{
    $total_price = 0;
    foreach ($codes_arr as $index => $results_arr)
    {
        $code_id = $results_arr['code_id'];
        $kabupatens = $results_arr['kabupatens'];
        
        $results = $results_arr['results'];    
        foreach ($results as $index => $box_row)
        {
?>
            <?php
            if ($index == 0)
            {
            ?>
            <?php
                if(!empty($Disable_Order)){
                    $disable = 'disabled';
                }
           else {
               $disable = '';
                }
            ?>
            <div class="form-group fakeCodeDetailsClass deleteHelperClass_<?=$code_id?>" style="border:1px dashed #368ee0;padding:4px">
 
                <button type="button" class="col-sm-2 btn deleteHelperButton <?= $disable ?>" rel="<?=$code_id?>">
                    <?=$box_row['code']?>
                    <span class="glyphicon glyphicon-remove" style="vertical-align:middle"></span>
                </button>
                <input type="hidden" name="codes[]" value="<?=$code_id?>">

                <label class="control-label col-sm-2"><b>Location</b></label>

                <input type="hidden" name="code_items_count[]" value="<?php echo count($results)?>">

                <div class="col-sm-2">
                        <div class="input-group">
                                <input type="hidden" name="locations_selected[]" value="<?=$box_row['location_id']?>_#_<?=$box_row['location_name']?>">
                                <label for="textfield" class="control-label col-sm-2"><?=$box_row['location_name']?></label>
                                <input type="hidden" name="capture_weight[]" value="<?=$box_row['capture_weight']?>" />
                        </div>
                </div>

                <label class="control-label col-sm-2"><b>Kabupaten</b></label>

                <div class="col-sm-2">
                        <div class="input-group">
                            <?php
                            if (!empty($kabupatens))
                            {
                            ?>
                                <input type="text" value="<?=$box_row['kabupaten']?>" name="kabupatens_name_selected[]" class="fake-kabupaten-autosuggest-class form-control" id="kabupaten_<?=$code_id?>"/>
                                <input type="hidden" value="<?=$box_row['kabupaten_id']?>" name="kabupatens_selected[]" id="kabupaten_id_<?=$code_id?>"/>
                            <?php
                            }
                            else
                            {
                                echo "No kabupatens found.";
                            }
                            ?>
                        </div>
                </div>

            </div>
            <?php
            }
            
            $price = $box_row['price'] * $box_row['quantity'];
            $total_price += $price;
            ?>

            <div class="form-group fakeCodeDetailsClass deleteHelperClass_<?=$code_id?>">
                <?php if(isset($box_row['promocode_id']) && $box_row['promocode_id'] > 0 ) 
                {
                    echo "<input type='hidden' id='box_procomode_id' value=".$box_row['promocode_id'].">";
                    $promocode_applied = "<span style='background-color:green;margin-left:20px;color:white'>Promocode Applied</span>";
                }
                else
                {
                    $promocode_applied = "";
                }
            ?> 
                    <label class="control-label box-label col-sm-2">Box <?=$promocode_applied;?></label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="hidden" name="boxes[]" select-box-id="<?=$box_row['box_id']?>" data-box-id="<?=$box_row['box_id']?>" value="<?=$box_row['box_id'].'_#_'.$box_row['box_name']?>">
                            <label class="control-label"><?php echo $box_row['box_name']?></label>
                        </div>
                    </div>

                    <label for="textfield" class="control-label col-sm-1">Quantity</label>
                    <div class="col-sm-1">
                        <?php 
                        if(!empty($Disable_Order)){ ?>
                           
                        <div class="input-group">
                            <label class="form-control"><input type="hidden" value="<?= $box_row['quantity'] ?>" class="quantityTextBoxClass" name="quantity[]"><?= $box_row['quantity'] ?></label>
                        </div> 
                      <?php  }
                      else  {
                        ?>   
                        <div class="input-group">
                                <select name="quantity[]" readonly class="quantityTextBoxClass form-control" >
                                <?php
                                    $str = '';
                                    for ($i=1; $i<=20; $i++)
                                    {
                                        $selected =  $box_row['quantity'] == $i ? "Selected='Selected'" : '';
                                        $str .= "<option $selected value='$i'>$i</option>";
                                    }
                                    echo $str;
                                ?>
                                </select>
                            </div>
                      <?php } ?>
                    </div>

                    <label class="control-label col-sm-2">Price</label>
                    <div class="col-sm-1">
                        <div class="input-group">
                            <input type="hidden" name="prices[]" class="priceHiddenClass" value="<?=$box_row['price']?>" >
                            <label class="control-label">$ <b class="priceTextBoxClass"><?php echo $box_row['price']?></b></label>
                        </div>
                    </div>


                    <label class="control-label col-sm-1 pull-right">$ <b class="individualPriceFakeClass"><?=$price?></b></label>
            </div>
            <input type="hidden" name="redel_orig_box_qty[<?=$box_row['box_id']?>]" data-redelivery-box-id="<?=$box_row['box_id']?>" data-quantity="<?=$box_row['quantity']?>" value="<?=$box_row['quantity']. '@@##@@'. $code_id. '@@##@@'. $box_row['location_id']. '@@##@@'. $box_row['kabupaten_id']. '@@##@@'. $box_row['price']. '@@##@@'. $price?>">
<?php
        }
    }
}
?>

<div class="form-group" id="grandTotalRow">
    <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalPriceContainer"><?=$grand_total?></b></label>
    <input type="hidden" name="total_price" value="<?=$grand_total?>">
    <div class="col-sm-2 pull-right"><b>Total</b></div>
</div>

<?php
if ($discount_type == 'repeated_customer')
{
?>
    <div class="form-group" id="discountRow">
        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer"><?=$discount?></b></label>
        <input type="hidden" name="total_discount" value="<?=$discount?>">
        <div class="col-sm-2 pull-right"><b>Discount (Repeated Customer)</b></div>
        <input type="hidden" name="discount_type" value="repeated_customer">
    </div>
<?php
}
else if ($discount_type == 'agent')
{
?>
    <div class="form-group" id="discountRow">
        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer"><?=$discount?></b></label>
        <input type="hidden" name="total_discount" value="<?=$discount?>">
        <div class="col-sm-2 pull-right"><b>Discount (Agent Booking)</b></div>
        <input type="hidden" name="discount_type" value="agent">
    </div>
<?php
}
else if ($discount_type == 'migration')
{
?>
    <div class="form-group" id="discountRow">
        <label for="textfield" class="control-label col-sm-2 pull-right">$ <b id="totalDiscountContainer"><?=$discount?></b></label>
        <input type="hidden" name="total_discount" value="<?=$discount?>">
        <div class="col-sm-2 pull-right"><b>Discount (Migration)</b></div>
        <input type="hidden" name="discount_type" value="migration">
    </div>
<?php
}
else if ($discount_type == 'promocode_discount')
{   
    if(isset($promocode_data))
    {
        $promocode_name = $promocode_data['name'];
        $promocode_amount = $promocode_data['amount'];
        $promocode_id = $promocode_data['id'];
    }
    else
    {
        $promocode_name = "";
        $promocode_amount = "";
        $promocode_id = "";
    }
?>
    <div class="form-group" id="discountRow">
        <label for="textfield" class="control-label col-sm-2 pull-right">$ - <b id="totalDiscountContainer"><?=$discount?></b></label>
        <input type="hidden" name="total_discount" value="<?=$discount?>">
      
        <div class="col-sm-2 pull-right"><b>Discount <br/><span class="green_text">(Promocode: <?=$promocode_name.", $".$promocode_amount?>)</span></b></div>
        <input type="hidden" name="discount_type" value="promocode_discount">
        <input type="hidden" name="promocode_id" value="<?=$promocode_id?>">
    </div>
<?php
}

if ($can_update_total == 'yes')
{
?>
    <div class="form-group" id="nettTotalRow">
        <label class="control-label col-sm-2 pull-right">$ 
        <input type="text" id="nettTotalContainer" name="nett_total" value="<?=$nett_total?>"></label>
        <div class="col-sm-2 pull-right"><b>Nett Total</b></div>
    </div>
<?php
}
else
{
?>
    <div class="form-group" id="nettTotalRow">
        <label class="control-label col-sm-2 pull-right">$ <b id="nettTotalContainer"><?=$nett_total?></b></label>
        <input type="hidden" name="nett_total" value="<?=$nett_total?>">
        <div class="col-sm-2 pull-right"><b>Nett Total</b></div>
    </div>
<?php
}
?>
<style>
    .green_text
    {
        color:#008000; 
    }
</style>                