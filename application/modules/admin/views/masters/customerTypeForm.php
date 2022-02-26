<div class="page-content main_container_padding">
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/add_customer_type" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            
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
                                                    <label for="textfield" class="control-label col-sm-2" >Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="customer_type_id" value="<?php echo set_value('customer_type_id', empty($customer_type_id) ? "" : $customer_type_id); ?>" />
                                                        <input type="text" class="form-control" placeholder="Enter Name" name="customer_type" value="<?php echo set_value('customer_type', empty($customer_type) ? "" : $customer_type); ?>" style="width:50%" required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">Select Pass Type</label>
                                                    <div class="col-sm-10">
                                                        <select id="box" name="pass_type_id" required class="form-control" style="width:20%">
                                                             <option value="">--Select--</option>
                                                            <?php  foreach ($pass_types as $key => $pass_type) { ?>
                                                            <option value="<?= $pass_type["pass_type_id"] ?>" <?= (isset($pass_type_id) && $pass_type_id == $pass_type["pass_type_id"]) ? "selected='selected'" : ""; ?> ><?= $pass_type["pass_type"] ?> </option>
                                                            <?php   } ?>                                                                
                                                        </select>
                                                    </div>
                                            </div>
                                             
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                                    <a href="<?php echo base_url(); ?>admin/masters/customer_typeList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>