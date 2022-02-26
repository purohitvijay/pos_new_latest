<div class="page-content main_container_padding">
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/addKabupaten/<?=$location_id?>" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            <input type="hidden" name="location_id" value="<?=$location_id?>">
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
                                                    <label for="textfield" class="control-label col-sm-2">Location<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <select name="location_id">
                                                            <?php
                                                                if (!empty($locations))
                                                                {
                                                                    foreach ($locations as $index => $row)
                                                                    {
                                                                        $selected = $location_id == $row['id'] ? 'Selected="selected"' : '';
                                                            ?>
                                                                        <option <?=$selected?>  value="<?=$row['id']?>"><?=$row['name']?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                            </div>
                                    
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                                        <input type="text" class="form-control" placeholder="Enter Name" name="name" value="<?php echo set_value('name', empty($name) ? "" : $name); ?>" style="width:50%" required/>
                                                    </div>
                                            </div>
                                                        
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                                    <a href="<?php echo base_url(); ?>admin/masters/kabupatenList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>