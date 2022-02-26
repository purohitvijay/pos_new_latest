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
            <form action="<?=base_url()?>admin/shipmentcost/SaveShipmentCostingMaster" method="post">
                <div class="row">
                   
                    <table class="table table-bordered" style="margin: 20px auto; width: 70%; text-align: center;">
                        <tr>   
                        <?php
                        if (!empty($costing_data))
                        {
                            $previous_record = null;
                            foreach ($costing_data as $index => $section_data)
                            {
                                $section_name = $section_data['section_text'];
                                $has_counter_overseas = $section_data['has_counter_overseas'];
                                
                                if (empty($has_counter_overseas) && empty($previous_record['has_counter_overseas']))
                                {
                                    echo "</tr>";
                                }
                            ?>
                        
                        <td width="50%">
                            <table class="table table-bordered">
                               <tr>
                                    <th class="" colspan="2"><?= $section_name; ?></th>
                                </tr>
                                
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><b><?= $section_data['currency']; ?></b></td>
                                </tr>
                                
                                 <?php
                                foreach ($section_data['data'] as $key => $data)
                                {
                                    ?>
                                    <tr>
                                        <td width="75%"><?= $data['text'] ?>
                                            <input type="hidden" name="text[]" value="<?= $data['text']; ?>">
                                        </td>
                                        
                                        <input type="hidden" name="currency[]" value="<?= $section_data['currency']; ?>">
                                        <input type="hidden" name="geographical_type[]" value="<?= $section_data['geographical']; ?>">
                                        <input type="hidden" name="section[]" value="<?= $section_data['section']; ?>">
                                        <input type="hidden" name="scheme[]" value="<?= $section_data['scheme']; ?>">
                                        <input type="hidden" name="container_type[]" value="<?= empty($section_data['container_type']) ? '':$section_data['container_type']; ?>">
                                        <input type="hidden" name="item[]" value="<?= $data['name']; ?>">

                                        <?php
                                        $temp_section = $section_data['section'];
                                        $temp_text = $data['text'];
                                        
                                        if (isset($masters_data[$temp_section][$temp_text]))
                                        {
                                            $value = $masters_data[$temp_section][$temp_text];
                                        }
                                        else
                                        {
                                            $value = isset($data['default_value']) ? $data['default_value'] : '';
                                        }

                                        if ($data['type'] == 'system')
                                        {
                                            ?> 
                                            <td>
                                                <input type="hidden" name="type[]" value="<?= $data['type']; ?>">
                                                <input type="text" class="form-control" name="costing[]" value="<?= $value; ?>">
                                            </td>
                                            <?php
                                        }
                                        else
                                        {
                                            ?> 
                                            <td>
                                                <input type="hidden" name="type[]" value="<?= $data['type']; ?>">
                                                <input type="text" class="form-control" name="costing[]" value="<?=$value?>">
                                            </td>
                                            <?php
                                        }
                                        ?>   
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                        
                                    </table>
                                    
                                </td>
                                
                                <?php
                                    
                                        $previous_record = $section_data;
                                        
                                        
                                        
                                        if (empty($has_counter_overseas))
                                        {
                                           
                                            echo "<td>&nbsp;</td>";
                                            echo'</tr>';
                                        }
                                        if (empty($previous_record['has_counter_overseas']))
                                        {
                                            echo "</tr>";
                                        }
                                    } //for loop ends here

                                } // if ends here
                                ?>
                           
                        </tr>
                    </table>
                    
                    <table class="table-bordered" style="margin: 0px auto">
                        <tr>
                            <td>
                  <input type="submit" class="btn btn-default" name="save" value="SUBMIT">   
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>


    </div>
</div>




