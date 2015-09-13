<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $data["link_back"]=base_url()."sys_module_task";
    $this->load->view("action_buttons_edit",$data);

//echo '<pre>';
//print_r($cropInfo);
//echo '</pre>';
?>
<form class="form_valid" id="save_form" action="<?php echo base_url();?>sys_module_task/index/save" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $module_task['id']; ?>" />
    <div class="row widget">
        <div class="widget-header">
            <div class="title">
                <?php echo $title; ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="task[name]" id="name" class="form-control" value="<?php echo $module_task['name']; ?>"/>
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_TYPE');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="type" name="task[type]" class="form-control" tabindex="-1">
                    <!--<option value=""></option>-->
                    <option value="MODULE"
                        <?php
                        if ($module_task['type'] == "MODULE") {
                            echo "selected='selected'";
                        }
                        ?> >Module
                    </option>
                    <option value="TASK"
                        <?php
                        if ($module_task['type'] == "TASK") {
                            echo "selected='selected'";
                        }
                        ?> >Task</option>
                </select>
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_PARENT');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="parent" name="task[parent]" data-placeholder="Select" class="form-control" tabindex="-1">
                    <option value="0"><?php echo $this->lang->line("SELECT"); ?></option>
                    <?php
                    echo "<pre>";
                    print_r($modules);
                    echo "</pre>";
                    foreach ($modules as $module)
                    {
                        ?>
                        <option value='<?php echo $module['module_task']['id']?>' <?php if($module['module_task']['id']==$module_task['parent']){ echo "selected='selected'";} ?>><?php echo $module['prefix'].$module['module_task']['name']; ?></option>
                    <?php

                    }
                    ?>
                </select>
            </div>
        </div>

        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTROLLER_NAME');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="task[controller]" id="controller" class="form-control" value="<?php echo $module_task['controller'] ?>" >
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_ORDER');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <input type="text" name="task[ordering]" id="ordering" class="form-control" value="<?php echo $module_task['ordering'] ?>" >
            </div>
        </div>
        <div style="" class="row show-grid">
            <div class="col-xs-4">
                <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_TYPE');?><span style="color:#FF0000">*</span></label>
            </div>
            <div class="col-sm-4 col-xs-8">
                <select id="status" name="task[status]" class="form-control" tabindex="-1">
                    <!--<option value=""></option>-->
                    <option value="1"
                        <?php
                        if ($module_task['status'] == 1) {
                            echo "selected='selected'";
                        }
                        ?> >Active
                    </option>
                    <option value="0"
                        <?php
                        if ($module_task['status'] == 0) {
                            echo "selected='selected'";
                        }
                        ?> >In-Active</option>
                    <option value="99"
                        <?php
                        if ($module_task['status'] == 99) {
                            echo "selected='selected'";
                        }
                        ?> >Delete</option>
                </select>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</form>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        turn_off_triggers();

    });
</script>
