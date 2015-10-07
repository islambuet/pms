<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
?>
<div class="widget-header">
    <div class="title">
        <?php echo $title; ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="row show-grid">
    <div class="col-xs-2">
    </div>
    <div class="col-xs-8">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center"><?php echo $CI->lang->line('LABEL_CONTAINER_NAME');?></th>
                <th class="text-center"><?php echo $CI->lang->line('LABEL_REMARKS');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($containers as $container)
            {
                ?>
                <tr>
                    <?php
                    if(isset($CI->permissions['edit'])&&($CI->permissions['edit']==1))
                    {
                        ?>
                        <td><a href="<?php echo site_url($CI->controller_url.'/index/edit/'.$container['id']);?>"><?php echo $container['container_name']; ?></td></a>
                        <?php
                    }
                    else
                    {
                        ?>
                        <td><?php echo $container['container_name']; ?></td>
                        <?php
                    }
                    ?>
                    <td class="text-right"><?php echo $container['remarks']; ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>

                <td class="text-center" colspan="2"><a href="<?php echo site_url($CI->controller_url.'/index/add/'.$consignment_id);?>" class="btn btn-primary"><?php echo $CI->lang->line('LABEL_MORE_CONTAINER'); ?></a> </td>

            </tr>
            </tbody>
        </table>
    </div>
</div>