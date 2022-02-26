<div class="page-content">
    <div class="row">
        <div class="col-md-12">                
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-reorder"></i><?php echo $form_caption; ?></div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form action="<?php echo base_url(); ?>admin/submenu/addSubMenu" class="form-horizontal" method='post' id="subMenuForm" name='subMenuForm'>
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
                                <label  class="col-md-3 control-label">Sub Menu Name<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <input type="hidden" name="subMenuId" value="<?php echo set_value('subMenuId', empty($subMenuId) ? "" : $subMenuId); ?>" />
                                    <input type="text" class="form-control" placeholder="Enter Sub Menu Name" name="subMenuName" value="<?php echo set_value('subMenuName', empty($subMenuName) ? "" : $subMenuName); ?>" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Menu<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('menuId', $menuArr, empty($menuId) ? NULL : $menuId, 'id = "menuId" class="form-control" required'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Permission<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('permissionId', $permissionArr, empty($permissionId) ? NULL : $permissionId, 'id = "permissionId" class="form-control" required'); ?>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label  class="col-md-3 control-label">Order Id<span class="required">*</span></label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Enter Order Id" name="orderId" value="<?php echo set_value('orderId', empty($orderId) ? "" : $orderId); ?>" required />
                                </div>
                            </div>                           

                            <div class="form-actions fluid">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                    <a href="<?php echo base_url(); ?>admin/submenu" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>                              
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