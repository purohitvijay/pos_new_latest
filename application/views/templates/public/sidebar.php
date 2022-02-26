<div class="page-container">
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->        
        <ul class="page-sidebar-menu">
            <li>
                <div class="sidebar-toggler hidden-phone"></div>
            </li>
            <?php
            foreach ($menu as $caption => $path) {
                $class = strtolower($caption) == strtolower($controller) ? "class='active'" : '';
                if ($caption == "Dashboard") {
                    $class = strtolower("users") == strtolower($controller) ? "class='active'" : '';
                }
                ?>
                <li <?php echo $class; ?>>
                    <a href="<?php echo base_url() . $path; ?>">
                        <i class="icon-cogs"></i>
                        <span class="title"><?php echo $caption; ?></span>
                        <span class="arrow open"></span>
                    </a>
    <?php
    if (!empty($submenu[$caption])) {
        ?>

                        <ul class="sub-menu">     
                        <?php
                        foreach ($submenu[$caption] as $cap => $submen_path) {
                            $submenu_selected = "";
                            if (!empty($submen_path)) {
                                $submenu_method = explode("/", $submen_path);
                                $last_key = key(array_slice($submenu_method, -1, 1, TRUE));
                                $submenu_selected = $submenu_method[$last_key];
                            }
                            $class = strtolower($submenu_selected) == strtolower($action) ? "class='active'" : '';
                            ?>
                                <li <?php echo $class; ?>>
                                    <a href="<?php echo base_url() . $submen_path; ?>">
                                <?php echo $cap; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
    <?php } ?>
                </li> 
                            <?php } ?>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
