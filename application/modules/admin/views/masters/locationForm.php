<div class="page-content main_container_padding">
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/addLocation" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            
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
                                                    <label for="textfield" class="control-label col-sm-2">Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                                        <input type="text" class="form-control" placeholder="Enter Name" name="name" value="<?php echo set_value('name', empty($name) ? "" : $name); ?>" style="width:50%" required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="password" class="control-label col-sm-2">Order  Id<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="Enter Order Id" name="order_id" value="<?php echo set_value('order_id', empty($order_id) ? "" : $order_id); ?>" required style="width:15%" />
                                                    </div>
                                            </div>
                                                        
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                                    <a href="<?php echo base_url(); ?>admin/masters/locationList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>