<div class="container-fluid">


    <div class="page-header">


        <div>
            <h3>
                <i class="fa fa-table"></i>
                Shipment Costing Entry Form
            </h3>
        </div>
        <div class="row"> 
            <?php
//            p($costing_data);
            ?>
            <form action="" method="post">
                <div class="row">

                    <table class="table table-bordered" style="margin: 20px auto; width: 50%; text-align: center;">
                        <tr>
                            
                                <?php
                        if (!empty($costing_data))
                        {
                            foreach ($costing_data as $section_name => $section_data)
                            {
                                
                                ?>
                                <td><?php
                                if (array_key_exists("has_overseas_counter", $section_data))
                                    {
                                        echo'hello test';
                                    }
                                ?>
                                    <table class="table table-bordered">
                                       <tr>
                                    <th class="" colspan="2"><?= $section_name; ?>
                                        <input type="hidden" name="currency" value="<?= $section_data['currency']; ?>">
                                        <input type="hidden" name="geographical" value="<?= $section_data['geographical']; ?>">
                                        <input type="hidden" name="section" value="<?= $section_data['section']; ?>">
                                        <input type="hidden" name="scheme" value="<?= $section_data['scheme']; ?>">
                                        <?php
                                        if (array_key_exists("container_type", $section_data))
                                        {
                                            ?>
                                            <input type="hidden" name="container_type" value="<?= $section_data['container_type']; ?>">
                                            <?php
                                        }
                                        ?>

                                    </th>
                                </tr>
                                        
                                    </table>
                                    
                                </td>
                                <?php
                        }
                        
                            }
                                ?>
                           
                        </tr>
                        
                    </table>
                </div>
            </form>
        </div>


    </div>
</div>