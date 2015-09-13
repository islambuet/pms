<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row widget">
    <div class="widget-header">
        <div class="title">
            Dashboard Menu
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="main-menu-container">
        <?php
            foreach($modules as $module)
            {
                ?>
                <div class="menu-item col-sm-2" data-menu-id="<?php echo $module['id'];?>">
                    <div class="menu_left pull-left">
                        <div class="menu_image">
                            <img alt="menu" src="<?php echo base_url().'images/'.$module['icon'];?>">

                        </div>
                        <div class="menu_title">
                            <?php echo $module['name']; ?>
                        </div>
                    </div>
                    <div class="menu_right pull-right">
                        <div class="menu_sub_count"><?php echo $module['subcount']; ?></div>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>

</div>
<div class="clearfix"></div>
<div class="" id="sub-menu">

</div>
<div class="clearfix"></div>
