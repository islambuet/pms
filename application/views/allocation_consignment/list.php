<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($consignments);
//print_r($bookings);
//echo '</PRE>';
?>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CUSTOMER_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="booking_id" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
            <?php
            foreach($bookings as $booking)
            {?>
                <option value="<?php echo $booking['booking_id']?>"><?php echo $booking['customer_name'];?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>

<div style="" class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $CI->lang->line('LABEL_CONSIGNMENT_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-sm-4 col-xs-8">
        <select id="consignment_id" class="form-control" tabindex="-1">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
            <?php
            foreach($consignments as $consignment)
            {?>
                <option value="<?php echo $consignment['consignment_id']?>"><?php echo $consignment['consignment_name'];?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<div id="edit_container">

</div>
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-12" style="overflow-x: auto">
            <table class="table table-hover table-bordered" >
                <thead>
                    <tr>
                        <th></th>
                        <?php
                        foreach($consignments as $consignment)
                        {
                        ?>
                            <th><?php echo $consignment['consignment_name']; ?></th>
                        <?php
                        }
                        ?>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <?php
                        foreach($consignments as $consignment)
                        {
                            $text='';
                            foreach($consignment['varieties'] as $variety)
                            {
                                $text.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                            }
                            ?>
                            <th><?php echo $text; ?></th>
                        <?php
                        }
                        ?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($bookings as $booking)
                {
                    $customer_info=$booking['customer_name'].'<br>';
                    foreach($booking['varieties'] as $variety)
                    {
                        $customer_info.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                    }
                    ?>
                    <tr>
                        <td><?php echo $customer_info;?></td>
                        <?php
                            for($i=0;$i<sizeof($consignments);$i++)
                            {
                                $text='';
                                foreach($booking['varieties'] as $variety)
                                {
                                    $text.=$variety['variety_name'].'-0'."<br>";
                                }
                                ?>
                                <td><?php echo $text; ?></td>
                                <?php
                            }
                        $text='';
                        foreach($booking['varieties'] as $variety)
                        {
                            $text.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                        }
                        ?>
                        <td><?php echo $text; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
