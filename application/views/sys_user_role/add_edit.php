<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$action_data=array();
$action_data["action_back"]=base_url($CI->controller_url);
$action_data["action_save"]='#save_form';
$action_data["action_clear"]='#save_form';
$CI->load->view("action_buttons",$action_data);
?>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $group_id; ?>" />
    <input type="hidden" id="system_save_new_status" name="system_save_new_status" value="0" />
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
                    <th><?php echo $CI->lang->line("NAME");?></th>
                    <th><input type="checkbox" data-type='task_action_view' class="task_header_all"><?php echo $CI->lang->line('LABEL_VIEW'); ?></th>
                    <th><input type="checkbox" data-type='task_action_add' class="task_header_all"><?php echo $CI->lang->line('LABEL_ADD'); ?></th>
                    <th><input type="checkbox" data-type='task_action_edit' class="task_header_all"><?php echo $CI->lang->line('LABEL_EDIT'); ?></th>
                    <th><input type="checkbox" data-type='task_action_delete' class="task_header_all"><?php echo $CI->lang->line('LABEL_DELETE'); ?></th>
                    <th><input type="checkbox" data-type='task_action_print' class="task_header_all"><?php echo $CI->lang->line('LABEL_PRINT'); ?></th>
                    <th><input type="checkbox" data-type='task_action_download' class="task_header_all"><?php echo $CI->lang->line('LABEL_DOWNLOAD'); ?></th>
                    <th><input type="checkbox" data-type='task_action_column_headers' class="task_header_all"><?php echo $CI->lang->line('LABEL_COLUMN_HEADERS'); ?></th>
                    <th><input type="checkbox" data-type='task_action_sp1' class="task_header_all"><?php echo $CI->lang->line('LABEL_SP1'); ?></th>
                    <th><input type="checkbox" data-type='task_action_sp2' class="task_header_all"><?php echo $CI->lang->line('LABEL_SP2'); ?></th>
                    <th><input type="checkbox" data-type='task_action_sp3' class="task_header_all"><?php echo $CI->lang->line('LABEL_SP3'); ?></th>
                    <th><input type="checkbox" data-type='task_action_sp4' class="task_header_all"><?php echo $CI->lang->line('LABEL_SP4'); ?></th>
                    <th><input type="checkbox" data-type='task_action_sp5' class="task_header_all"><?php echo $CI->lang->line('LABEL_SP5'); ?></th>


                </tr>
                </thead>

                <tbody>
                <?php
                if(sizeof($modules_tasks)>0)
                {
                    foreach($modules_tasks as $module_task)
                    {
                        ?>
                        <tr>
                            <td>
                                <?php echo $module_task['prefix'];
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" data-id='<?php echo $module_task['module_task']['id'];?>' class="task_action_all">
                                    <?php
                                }
                                ?>
                                <?php echo $module_task['module_task']['name'];?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_VIEW'); ?>" class="task_action_view task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['view'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][view]'>
                                    <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_ADD'); ?>" class="task_action_add task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['add'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][add]'>
                                    <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_EDIT'); ?>" class="task_action_edit task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['edit'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][edit]'>
                                    <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_DELETE'); ?>" class="task_action_delete task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['delete'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][delete]'>
                                    <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_PRINT'); ?>" class="task_action_print task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['print'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][print]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_DOWNLOAD'); ?>" class="task_action_download task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['download'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][download]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_COLUMN_HEADERS'); ?>" class="task_action_column_headers task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['column_headers'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][column_headers]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_SP1'); ?>" class="task_action_sp1 task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['sp1'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][sp1]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_SP2'); ?>" class="task_action_sp2 task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['sp2'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][sp2]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_SP3'); ?>" class="task_action_sp3 task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['sp3'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][sp3]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_SP4'); ?>" class="task_action_sp4 task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['sp4'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][sp4]'>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if($module_task['module_task']['type']=='TASK')
                                {
                                    ?>
                                    <input type="checkbox" title="<?php echo $CI->lang->line('LABEL_SP5'); ?>" class="task_action_sp5 task_action_<?php echo $module_task['module_task']['id'];?>"  <?php if(in_array($module_task['module_task']['id'],$role_status['sp5'])){echo 'checked';}?> value="1" name='tasks[<?php echo $module_task['module_task']['id'];?>][sp5]'>
                                <?php
                                }
                                ?>
                            </td>

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
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();
        $(document).on("click",'.task_action_all',function()
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
        $(document).on("click",'.task_header_all',function()
        {
            //console.log('task_action clicked');
            if($(this).is(':checked'))
            {
                $('.'+$(this).attr('data-type')).prop('checked', true);

            }
            else
            {
                $('.'+$(this).attr('data-type')).prop('checked', false);
            }

        });
    });

</script>
