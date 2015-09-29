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
                <th class="text-center"><?php echo $CI->lang->line('LABEL_PAYMENT_FOR');?></th>
                <th class="text-center"><?php echo $CI->lang->line('LABEL_DATE');?></th>
                <th class="text-center"><?php echo $CI->lang->line('LABEL_TOTAL');?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total=0;
            foreach($payments as $payment)
            {
                ?>
                <tr>
                    <?php
                    if(isset($CI->permissions['edit'])&&($CI->permissions['edit']==1))
                    {
                        ?>
                        <td><a href="<?php echo site_url($CI->controller_url.'/index/edit/'.$payment['id']);?>"><?php echo $payment['booking_status']; ?></td></a>
                        <?php
                    }
                    else
                    {
                        ?>
                        <td><?php echo $payment['booking_status']; ?></td>
                        <?php
                    }
                    ?>
                    <td class="text-right"><?php echo System_helper::display_date($payment['payment_date']); ?></td>
                    <td class="text-right"><?php echo $payment['amount']; ?></td>
                </tr>
                <?php
                $total+=$payment['amount'];
            }
            ?>
            <tr>
                <td colspan="2" class="text-right"><?php echo $CI->lang->line('LABEL_TOTAL'); ?></td>
                <td class="text-right"><?php echo $total; ?></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-center"><a href="<?php echo site_url($CI->controller_url.'/index/add/'.$booking_id);?>" class="btn btn-primary"><?php echo $CI->lang->line('LABEL_MORE_PAYMENT'); ?></a> </td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>