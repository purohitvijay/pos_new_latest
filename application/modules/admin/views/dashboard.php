<style type="text/css">
.label-info {
    background-color: #eee;
    border: 1px solid dimgray;
    color: dimgray;
}
.label {
    font-size: 28px;
}
</style>
<div class="container-fluid">
    <br><br>    
    
    
    <div class="box">
        <div class="box-title">
                <h3>
                        <i class="fa fa-bars"></i>
                        Status as of <b><?=date('d/m/Y H:i:s')?></b>
                </h3>
        </div>
        <div class="box-content">
                <?php
                if (!empty($statuses))
                {
                ?>
                    <ul class="tiles tiles-center nomargin">
                <?php
                    foreach ($statuses as $status => $row)
                    {
                ?>
                        <li style="background-color:<?=$row['label_color']?>">
                                <span class="label label-info"><?=empty($status_count[$status]) ? 0 : $status_count[$status]?></span>
                                <a href="#">
                                        <span>
                                                <i style="color:<?=$row['font_color']?>;padding-top: 20px;" class="fa <?=$row['glyphicon']?>"></i>
                                        </span>
                                        <span class="name" style="padding-right:0px;padding-left:0px;white-space: normal;display: inline-block;color:<?=$row['font_color']?>"><?=$row['display_text']?></span>
                                </a>
                        </li>
                <?php
                    }
                ?>
                    </ul>    
                <?php
                }
                ?>
        </div>
</div>


    
    
    
</div>