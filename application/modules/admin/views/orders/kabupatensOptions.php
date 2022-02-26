<?php
if (!empty($results))
{
?>
    <option>--Select--</option>
<?php
    foreach ($results as $index => $row)
    {
?>
        <option value="<?=$row['id']?>"><?=$row['name']?></option>
<?php
    }
}
?>
