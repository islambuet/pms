<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="row widget">
    <div class="widget-header">
        <div class="title">
            Dashboard Menu
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="sub-menu-container">
        <?php
        foreach($tasks as $task)
        {
            ?>
            <div class="menu-item col-sm-2" data-menu-link="<?php echo base_url().$task['controller'];?>" title="<?php echo $task['name']; ?>">
                <div class="menu_left pull-left">
                    <div class="menu_image">
                        <img alt="menu" src="<?php echo base_url().'images/'.$task['icon'];?>">

                    </div>
                    <div class="menu_title">
                        <?php echo $task['name']; ?>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>

    </div>

</div>
<div class="clearfix"></div>
