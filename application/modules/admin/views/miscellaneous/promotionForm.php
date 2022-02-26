<div class="page-content main_container_padding">

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3>
                        <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                        
                </div>
                
                <div class="box-content nopadding">
                    <form id="myForm" action="<?php echo base_url(); ?>admin/miscellaneous/savePromotion" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                        
                        <?php  
                            if (!empty($message)) 
                            {
                                ?>
                                <div class="alert alert-danger active">
                                    <button class="close" data-dismiss="alert"></button>
                                    <span><?php echo $message; ?></span>
                                </div>
                                <?php
                            }
                        ?> 
                        
                        <input type='hidden' name='id' value='<?= isset($promotionData['id']) ? $promotionData['id'] : ""?>'>
                        <input type='hidden' name='promotion_trans_id' value='<?= isset($promotionData['promotion_trans_id']) ? $promotionData['promotion_trans_id'] : ""?>'>
                        
                        <input type='hidden' name='usage_left' value='<?= isset($promotionData['usage_left']) ? $promotionData['usage_left'] : ""?>'>
                        
                        <input type='hidden' name='old_max_capping' value='<?= isset($promotionData['quantity_count']) ? $promotionData['quantity_count'] : ""?>'>

                        <div class="form-group">
                            <label for="promo_name" class="control-label col-sm-2">Promo Name<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input id="promo_name" type="text" style="width:317px" class="form-control" placeholder="Promo Name" name="promo_name" value='<?= isset($promotionData['name']) ? $promotionData['name'] : ""?>' required/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date_from" class="control-label col-sm-2">Date From<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" id="date_from" class="form-control" placeholder="Date From" id="date_from" name="date_from" style="width:11%;position: relative; z-index: 1000;" value='<?= isset($promotionData['date_from']) ? $promotionData['date_from'] : ""?>'required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date_to" class="control-label col-sm-2">Date To<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" id="date_to" class="form-control" placeholder="Date To" id="date_to" name="date_to" style="width:11%;position: relative; z-index: 1000; " value='<?= isset($promotionData['date_to']) ? $promotionData['date_to'] : ""?>' required/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="box_type" class="control-label col-sm-2">Box Type<span class="required">*</span></label>
                            <div class="col-sm-10">
                                
                            <div class='input-group date'>
                                <select id="box_type" name="box_ids[]" multiple class="form-control" >
                                    <?php
//                                    $promotionData = array();
                                    foreach ($boxes as $index => $row)
                                    {
                                        $selected = !empty($promotionData['boxes_name']) && in_array($row['id'], $promotionData['boxes_name']) ? 'Selected' : '';
                                        
//                                        $selected = "half";
                                        if (!empty($promotionData['boxes_name']) && in_array($row['id'], $promotionData['boxes_name']))
                                        {
                                            $promotionData['boxes_name'][] = "<b>{$row['name']}</b>";
                                        }
                                        
                                    ?>
                                        <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                    }
                                    ?>
                                </select> 
                        </div> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="amount" class="control-label col-sm-2">Amount<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input id="amount" type="number" style="width:11%" class="form-control" placeholder="Amount" name="amount" value='<?= isset($promotionData['amount']) ? $promotionData['amount'] : ""?>' required />
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label for="multiple_usage" class="control-label col-sm-2">Multiple Usage<span class="required">*</span></label>
                            <div class="col-sm-10">
                            <div class='input-group date'> 
                               
                                <select class="form-control multiple_usage" name="multiple_usage" required>
                                    <option value="">--Select--</option>
                                    <option value="yes">yes</option>
                                    <option value="no">no</option>
                                    
                                </select>  
                            </div>
                        </div>
                        </div>
 
                        <div class="form-group">
                            <label for="max_capping" class="control-label col-sm-2">Max Capping<span class="required">*</span></label>
                            <div class="col-sm-10"> 
                                <input id="max_capping" type="number" name="max_capping" style="width:11%" class="form-control" placeholder="Max Capping" value='<?= isset($promotionData['quantity_count']) ? $promotionData['quantity_count'] : ""?>' required />
                               
                            </div>
                        </div>
                        
                        <div class="form-actions col-sm-offset-2 col-sm-4">
                            <button id="submitBtn" type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                            <a href="<?php echo base_url(); ?>admin/miscellaneous/promotion" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                        </div>
                    
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
 

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div> 
<script type="text/javascript">
$(document).ready(function (){
    
     <?php
    if(isset($promotionData['boxes_ids'])) {
    ?> 
         var boxes_ids = "<?=$promotionData['boxes_ids']?>";
         var boxes_id_arr = boxes_ids.split(","); 
         $("select[name='box_ids[]']").val(boxes_id_arr);
    <?php } ?>
    
    
    
    <?php
    if(isset($promotionData['multiple_usage'])) {
    ?>
      var value = "<?= $promotionData['multiple_usage']?>";
   
       $('.multiple_usage option').each(function (index, obj)
       {
           if($(this).val() == value)
           {
            console.log($(this).attr('selected','selected'));   
           }
       });
    
   <?php } ?>
            
    
    $('#date_from').datepicker({
        dateFormat: "dd/mm/yy"
    })
    $('#date_to').datepicker({
        dateFormat: "dd/mm/yy"
    })
 
    $('#myForm').submit(function(event) {
          
        var fromDate = $('#date_from').val();
        var EndDate = $('#date_to').val();
        
        fromDate = fromDate.split('/')
        fromDate = fromDate[2] + '-' + fromDate[1] + '-' + fromDate[0];
        
        EndDate = EndDate.split('/')
        EndDate = EndDate[2] + '-' + EndDate[1] + '-' + EndDate[0];
        
        fromDate = new Date(fromDate);
        EndDate = new Date(EndDate);
        
        if (fromDate > EndDate)
        {
            event.preventDefault()
            alert("Date To should be bigger than Date From.")
            return false;
        } 
    });
    
     $("#box_type").multiselect();
 
})
</script>