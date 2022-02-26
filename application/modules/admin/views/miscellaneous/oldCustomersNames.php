 
      <div>
          <div class="old_customer_name">
        <?php
        if($customerName)
        {
            foreach($customerName as $idx => $val)
            {
                isset($val['name']) ? $val['name'] : '';
                echo "Delete Duplicate Customer: " ."<b>".$val['name']."</b><br/>";
            }
        }
        ?>
              <br/>
              <button class="btn btn-primary ok" type="button">OK</button>
          </div>
      </div>
  