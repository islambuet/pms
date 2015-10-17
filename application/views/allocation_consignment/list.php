<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
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
                foreach($bookings as &$booking)
                {
                    /*echo '<PRE>';
                    print_r($booking);
                    echo '</PRE>';*/
                    $customer_info=$booking['customer_name'].'<br>';
                    foreach($booking['varieties'] as $variety)
                    {
                        $customer_info.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                    }
                    ?>
                    <tr>
                        <td><?php echo $customer_info;?></td>
                        <?php
                            foreach($consignments as &$con)
                            {
                                $text='';

                                foreach($con['varieties'] as $variety)
                                {
                                    if(isset($allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity']))
                                    {
                                        $text.=$variety['variety_name'].'-'.number_format($allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity'])."<br>";
                                        $booking['varieties'][$variety['id']]['copy_quantity']-=$allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity'];
                                        $con['varieties'][$variety['id']]['copy_quantity']-=$allocated_varieties[$booking['booking_id']][$con['consignment_id']][$variety['id']]['quantity'];
                                    }
                                    else
                                    {
                                        $text.=$variety['variety_name'].'-0'."<br>";
                                    }
                                    //$con['varieties'][$variety['id']]['copy_quantity']=0;
                                }
                                ?>
                                <td><?php echo $text; ?></td>
                                <?php
                            }
                        $text='';
                        foreach($booking['varieties'] as $variety)
                        {
                            $text.=$variety['variety_name'].'-'.number_format($variety['copy_quantity'])."<br>";
                        }
                        ?>
                        <td><?php echo $text; ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td></td>
                    <?php
                    foreach($consignments as $consignment)
                    {
                        $text='';
                        //$text=$consignment;
                        foreach($consignment['varieties'] as $variety)
                        {
                            $text.=$variety['variety_name'].'-'.number_format($variety['copy_quantity'])."<br>";
                        }
                        ?>
                        <td><?php echo($text); ?></td>
                    <?php
                    }
                    ?>
                    <th></th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
