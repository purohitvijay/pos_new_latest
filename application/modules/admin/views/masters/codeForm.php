<div class="page-content main_container_padding">
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/addCode" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            
                                            <?php
                                            $errors = validation_errors();
                                            if (!empty($errors)) {
                                                ?>
                                                <div class="alert alert-danger active">
                                                    <button class="close" data-dismiss="alert"></button>
                                                    <span><?php echo $errors; ?></span>
                                                </div>
                                                <?php
                                            }
                                            ?> 
                                            
                                            <div class="form-group">
                                                    <label for="code" class="control-label col-sm-2">Code<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                                        <input type="text" class="form-control" placeholder="Enter Code" name="code" value="<?php echo set_value('code', empty($code) ? "" : $code); ?>" id="code" style="width:50%" required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="location_id" class="control-label col-sm-2">Location<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        if (!empty($locations))
                                                        {
                                                        ?>
                                                            <select name="location_id" class="form-control" style="width:20%">
                                                                <option value="">--Select--</option>
                                                                <?php
                                                                foreach ($locations as $index => $row)
                                                                {
                                                                    $selected = !empty($location_id) &&  $location_id == $row['id'] ? 'Selected' : '';
                                                                ?>
                                                                    <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="description" class="control-label col-sm-2">Description</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="Enter Description" id="description" name="description" value="<?php echo set_value('description', empty($description) ? "" : $description); ?>"/>
                                                    </div>
                                            </div>
					
                                            <div class="form-group">
                                                    <label for="description" class="control-label col-sm-2">Boxes</label>
                                                    <div class="col-sm-10">
												
                                            <?php
                                            if (!empty($boxes))
                                            {
                                                foreach ($boxes as $index => $row)
                                                {
                                                    $checked = !empty($box_ids) && in_array($row['id'], $box_ids) ? 'Checked' : '';
                                            ?>
                                                    <div class="check-line">
                                                            <input <?=$checked?> type="checkbox" name="box_ids[]" value="<?=$row['id']?>" class='icheck-me' id="c<?=$row['id']?>" data-skin="minimal">
                                                            <label class='inline' for="c<?=$row['id']?>"><?=$row['name']?></label>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            else
                                            {
                                                echo "No boxes found";
                                            }
                                            ?>

                                                </div>
                                            </div>
                                                                                                
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                                    <a href="<?php echo base_url(); ?>admin/masters/codeList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>

<script type="text/javascript">
$(document).ready(function (){
  $('.spinner .btn:first-of-type').on('click', function() {
      inputObj = $(this).parent().parent().find('input')
      inputObj.val( parseInt(inputObj.val(), 10) + 1);
  });
  $('.spinner .btn:last-of-type').on('click', function() {
    
      inputObj = $(this).parent().parent().find('input')
      inputObj.val( parseInt(inputObj.val(), 10) - 1);
  });
  
})
</script>