<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $CI->load->view("action_buttons",$action_data);
?>

<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="col-xs-12" style="overflow-x: auto;">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th><?php echo $CI->lang->line("ID");?></th>
                    <th><?php echo $CI->lang->line("NAME");?></th>
                    <th><?php echo $CI->lang->line("TOTAL_TASK");?></th>
                </tr>
            </thead>

            <tbody>
            <?php
                if(sizeof($user_groups_info)>0)
                {
                    foreach($user_groups_info as $user_group_info)
                    {
                        ?>
                        <tr>
                            <td><?php echo $user_group_info['id']; ?></td>
                            <td><a href="<?php echo site_url('sys_user_role/index/edit/'.$user_group_info['id']);?>"><?php echo $user_group_info['name']; ?></a></td>
                            <td><?php echo $user_group_info['total_task']; ?></td>
                        </tr>
                    <?php
                    }
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="20" class="text-center alert-danger">
                            <?php echo $CI->lang->line("NO_DATA_FOUND"); ?>
                        </td>
                    </tr>
                    <?php
                }
            ?>

            </tbody>
        </table>
    </div>


</div>
<div class="clearfix"></div>