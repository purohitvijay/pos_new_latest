<?php
if (!empty($results))
{
    $total_price = 0;
    foreach ($results as $index => $box_row)
    {
?>
        <?php
        if ($index == 0)
        {
        ?>
        <div class="form-group fakeCodeDetailsClass deleteHelperClass_<?=$code_id?>" style="border:1px dashed #368ee0;padding:4px">
            
            <button type="button" class="col-sm-2 btn deleteHelperButton" rel="<?=$code_id?>">
                <?=$box_row['code']?>
                <span class="glyphicon glyphicon-remove" style="vertical-align:middle"></span>
            </button>
            <input type="hidden" name="codes[]" value="<?=$code_id?>">
            
            <label class="control-label col-sm-2"><b>Location</b></label>
            
            <input type="hidden" name="code_items_count[]" value="<?php echo count($results)?>">
            
            <div class="col-sm-2">
                    <div class="input-group">
                            <input type="hidden" class="selectedLocation" location_id="<?=$box_row['location_id']?>" name="locations_selected[]" value="<?=$box_row['location_id']?>_#_<?=$box_row['location_name']?>">
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
                        <input type="text" name="kabupatens_name_selected[]" class="fake-kabupaten-autosuggest-class form-control" id="kabupaten_<?=$code_id?>"/>
                        <input type="hidden" name="kabupatens_selected[]" id="kabupaten_id_<?=$code_id?>"/>
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
        ?>

        <div class="form-group fakeCodeDetailsClass deleteHelperClass_<?=$code_id?>">
            <label class="control-label col-sm-2 box-label">Box</label>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="hidden" class="selectedBoxes" select-box-id="<?=$box_row['box_id']?>" box_id="<?=$box_row['box_id']?>" name="boxes[]" value="<?=$box_row['box_id'].'_#_'.$box_row['box_name']?>">
                        <label class="control-label"><?php echo $box_row['box_name']?></label>
                    </div>
                </div>
            
                <label for="textfield" class="control-label col-sm-1">Quantity</label>
                <div class="col-sm-1">
                        <div class="input-group">
                            <select name="quantity[]" class="selectedBoxQuantity quantityTextBoxClass form-control" >
                            <?php
                                $str = '';
                                for ($i=1; $i<=20; $i++)
                                {
                                    $str .= "<option value='$i'>$i</option>";
                                }
                                echo $str;
                            ?>
                            </select>
                        </div>
                </div>
                
                <label class="control-label col-sm-2">Price</label>
                <div class="col-sm-1">
                    <div class="input-group">
                        <input type="hidden" name="prices[]" class="priceHiddenClass" value="<?=$box_row['price']?>" >
                        <label class="control-label">$ <b class="priceTextBoxClass"><?php echo $box_row['price']?></b></label>
                    </div>
                </div>
                
                
                <?php
                $price = $box_row['price'];
                $total_price += $price;
                ?>
                <label class="control-label col-sm-1 pull-right">$ <b class="individualPriceFakeClass"><?=$price?></b></label>
        </div>
<?php   
    }
}
?>