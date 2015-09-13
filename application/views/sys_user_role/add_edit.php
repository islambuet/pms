<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $action_data["action_back"]=base_url($CI->controller_url);
    $action_data["action_save"]='#save_form';
    $CI->load->view("action_buttons",$action_data);

    $modules=array();
    foreach($access_tasks as $tasks)
    {
        $modules[$tasks['parent']]['name']=$tasks['module_name'];
        $modules[$tasks['parent']]['tasks'][]=$tasks;
    }
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th><?php echo "Module"; ?></th>
                <th><?php echo "Task"; ?></th>
                <th><?php echo "View"; ?></th>
                <th><?php echo "Add"; ?></th>
                <th><?php echo "Edit"; ?></th>
                <th><?php echo "Delete"; ?></th>
                <th><?php echo "Report"; ?></th>
                <th><?php echo "Print"; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
                foreach($modules as $module_id=>$module)
                {
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" data-id='<?php echo $module_id; ?>' class="module_name"><?php echo $module['name'];?>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <?php
                    foreach($module['tasks'] as $task)
                    {
                        ?>
                        <tr>
                            <td>
                                <input type="hidden" name="tasks[<?php echo $task['id'];?>][ugr_id]" value="<?php echo isset($role_status['ugr_id'][$task['id']])?$role_status['ugr_id'][$task['id']]:0;?>">
                                <input type="hidden" name="tasks[<?php echo $task['id'];?>][task_id]" value="<?php echo $task['id'];?>">
                            </td>
                            <td><input type="checkbox" data-id='<?php echo $task['id'];?>' class="task_name module_action_<?php echo $module_id;?>"><?php echo $task['name'];?></td>
                            <td>
                                <?php
                                if($task['view'])
                                {
                                    ?>
                                    <input title="view" class="task_action_<?php echo $task['id'];?> module_action_<?php echo $module_id;?>" type="checkbox" <?php if(in_array($task['id'],$role_status['view'])){echo 'checked';}?> value="1" name='tasks[<?php echo $task['id'];?>][view]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($task['add'])
                                {
                                    ?>
                                    <input title="add" class="task_action_<?php echo $task['id'];?> module_action_<?php echo $module_id;?>" type="checkbox" <?php if(in_array($task['id'],$role_status['add'])){echo 'checked';}?> value="1" name='tasks[<?php echo $task['id'];?>][add]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($task['edit'])
                                {
                                    ?>
                                    <input title="edit" class="task_action_<?php echo $task['id'];?> module_action_<?php echo $module_id;?>" type="checkbox" <?php if(in_array($task['id'],$role_status['edit'])){echo 'checked';}?> value="1" name='tasks[<?php echo $task['id'];?>][edit]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($task['delete'])
                                {
                                    ?>
                                    <input title="delete" class="task_action_<?php echo $task['id'];?> module_action_<?php echo $module_id;?>" type="checkbox" <?php if(in_array($task['id'],$role_status['delete'])){echo 'checked';}?> value="1" name='tasks[<?php echo $task['id'];?>][delete]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($task['report'])
                                {
                                    ?>
                                    <input title="report" class="task_action_<?php echo $task['id'];?> module_action_<?php echo $module_id;?>" type="checkbox" <?php if(in_array($task['id'],$role_status['report'])){echo 'checked';}?> value="1" name='tasks[<?php echo $task['id'];?>][report]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($task['print'])
                                {
                                    ?>
                                    <input title="print" class="task_action_<?php echo $task['id'];?> module_action_<?php echo $module_id;?>" type="checkbox" <?php if(in_array($task['id'],$role_status['print'])){echo 'checked';}?> value="1" name='tasks[<?php echo $task['id'];?>][print]'>
                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
            ?>
            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $(document).on("click",'.task_name',function()
        {
            //console.log('task_action clicked');
            if($(this).is(':checked'))
            {
                $('.task_action_'+$(this).attr('data-id')).prop('checked', true);

            }
            else
            {
                $('.task_action_'+$(this).attr('data-id')).prop('checked', false);
            }

        });
        $(document).on("click",'.module_name',function()
        {
            //console.log('module_action clicked');
            if($(this).is(':checked'))
            {
                $('.module_action_'+$(this).attr('data-id')).prop('checked', true);

            }
            else
            {
                $('.module_action_'+$(this).attr('data-id')).prop('checked', false);
            }

        });

    });

</script>
