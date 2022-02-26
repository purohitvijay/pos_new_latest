<?php
if(!empty($box_arr))
{
    ?>
<div class="form-group">
       <label for="password" class="control-label col-sm-2">
       <select name="box_id[]" class="box_selected box_id" > 
           <option value="0">--Select--</option>
           <?php
       foreach($box_arr as $idx => $box_record)
       {
           ?>
           <option value="<?php echo $box_record['id'];?>"><?php echo $box_record['name'];?></option>
     <?php  
     
       } ?>
       </select>
       
       </label>
        <div class="col-sm-10">
        
        <input type="text" class="form-control" placeholder="Quantity" name="box_quantity[]" value="" style="width:8%" onkeypress="return isNumber(event)"/>
        </div>
    </div>
<?php 
}
?>
