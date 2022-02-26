<?php
if (!empty($results))
{
?>
<div class="row">
        <div class="col-sm-12">
                <div class="box box-color box-bordered">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-table"></i>
                                        Follow Up Call History
                                </h3>
                        </div>
                        
                        <div class="box-content nopadding">
                            <ul class="timeline">
                            <?php
                            foreach ($results as $index => $row)
                            {
                            ?>
                                <li>
                                        <div class="timeline-content">
                                                <div class="left">
                                                        <div class="icon">
                                                                <i class="fa fa-phone"></i>
                                                        </div>
                                                        <div class="date"><?=date('d-m-Y H:i:s', strtotime($row['followup_datetime']))?></div>
                                                </div>
                                                <div class="activity">
                                                        <div class="user">
                                                                <a href="#"><?=$row['name']?></a>
                                                                <span><?=$row['comments']?></span>
                                                        </div>

                                                </div>
                                        </div>
                                        <div class="line"></div>
                                </li>
                        <?php
                            }
                        ?>
                                            
                                    </ul>
                            </div>
            </div>
        </div>
    </div>
<?php
}
?>

<script type="text/javascript">
$(document).ready(function (){
    $('body').on('click', '.detailsRowFakeClass', function (){
        id = $(this).attr('rel')
        $('#row_'+id).toggleClass('hide');
    })
})
</script>