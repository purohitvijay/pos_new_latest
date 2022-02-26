<div class="page-content">
    <div class="row">
        <div class="col-md-12">                
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-reorder"></i><?php echo $form_caption; ?></div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form action="<?php echo base_url(); ?>admin/permission/addPermission" class="form-horizontal" method='post' id="menuForm" name='permissionForm'>
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
                        <div class="form-body">
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Permission Name<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <input type="hidden" name="permissionId" value="<?php echo set_value('permissionId', empty($permissionId) ? "" : $permissionId); ?>" />
                                    <input type="text" class="form-control" placeholder="Enter Permission Name" name="permissionName" value="<?php echo set_value('permissionName', empty($permissionName) ? "" : $permissionName); ?>" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Alias Name<span class="required">*</span></label>
                                <div class="col-md-4">                                    
                                    <input type="text" class="form-control" placeholder="Enter Permission Alais Name" name="aliasName" value="<?php echo set_value('aliasName', empty($aliasName) ? "" : $aliasName); ?>" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Path<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Enter Path" name="path" value="<?php echo set_value('path', empty($path) ? "" : $path); ?>" required/>
                                </div>
                            </div>  
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Always Allow<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('alwaysAllow', $content = array('' => '--Select--','Yes' => 'Yes','No' => 'No'), empty($alwaysAllow) ? NULL : $alwaysAllow, 'id = "alwaysAllow" class="form-control" required'); ?>
                                </div>
                            </div> 
                                                     

                            <div class="form-actions fluid">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                    <a href="<?php echo base_url(); ?>admin/permission" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>                              
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- END FORM--> 
                </div>
            </div>
        </div>
    </div>
</div>