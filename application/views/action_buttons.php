<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
    $CI = & get_instance();
    $dashboard_link='home/index';
    if(isset($CI->parent_module_id))
    {
        $dashboard_link=$dashboard_link.'/'.$CI->parent_module_id;
    }

?>
<div class="row widget hidden-print" style="padding-bottom: 0px;">
    <div class="action_button">
        <a class="btn" href="<?php echo site_url($dashboard_link)?>"><?php echo $this->lang->line("ACTION_DASHBOARD"); ?></a>
    </div>
    <?php
        if(isset($action_new))
        {
            ?>
            <div class="action_button"">
                <a class="btn" href="<?php echo $action_new; ?>"><?php echo $this->lang->line("ACTION_NEW"); ?></a>
            </div>
            <?php
            }
    ?>
    <div class="action_button">
        <a class="btn" href="<?php echo site_url('home/logout')?>"><?php echo $this->lang->line("ACTION_LOGOUT"); ?></a>
    </div>


</div>
<div class="clearfix"></div>