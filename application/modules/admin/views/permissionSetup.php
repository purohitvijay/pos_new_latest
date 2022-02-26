<div class="page-content">
     <div class="row">
        <div class="col-md-12">
            <?php if (!empty($message)) {
                ?>
                <div class="alert alert-success active">
                    <button class="close " data-dismiss="alert"></button>
                    <span><?php echo $message; ?></span>
                </div>
            <?php }
            ?>
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-cogs"></i>Permission Setup List</div>                    
                </div>
                <div class="portlet-body">
                    <div class="table-responsive">
                         <form method="post" action="<?php echo base_url();?>admin/permissionSetup/savePermissionAsRole">
                        <table class="table table-striped table-bordered table-hover" id="permissionTable">
                            <thead>
                            <th>Permission</th>
                            <?php 
                            foreach($roleArr as $id => $row)
                            {
                                ?>
                                <th><?php echo $row['RoleName'];?></th>
                            <?php } ?>
                            </thead>
                            <tbody>
                               
                                    <?php 
                                    foreach($permissionArr as $idx => $row)
                                    {
                                        ?>
                                    <tr>
                                        <td>
                                            <?php echo $row['permissionName'];?>
                                        </td>
                                        <?php 
                                        foreach($roleArr as $id => $rec)
                                        {
                                            $checked = "";
                                            if(!empty($permissionRoleTrans[$row['id']][$rec['id']]))
                                            {
                                                $checked = "class='checked'";
                                            }
                                            ?>
                                        <td align="center">
                                            <input type="checkbox" name="permission[<?php echo $row['id'];?>][<?php echo $rec['id'];?>]" <?php echo $checked;?>/> </td>
                                       <?php } ?>
                                    </tr>
                                   <?php } ?>
                                   
                                      
                            </tbody>
                        </table>
                              <div class="form-actions fluid">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn red"><?php echo mlLang('lblSubmitBtn'); ?></button>                                   
                                </div>
                            </div> 
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $('document').ready(function(){
        $('.checked').closest('span').addClass('checked');
        $('.checked').attr('checked',true);
    })
</script>