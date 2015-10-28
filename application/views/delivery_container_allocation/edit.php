<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($bookings);
//echo '</PRE>';
//return;
//echo '<PRE>';
//print_r(($booking_info));
//echo '</PRE>';
//echo '<PRE>';
//print_r($allocated_varieties);
//echo '</PRE>';
//return;
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">

    <input type="hidden" name="consignment_id" value="<?php echo $consignment_id; ?>" />
    <input type="hidden" name="container_id" value="<?php echo $container_id; ?>" />
    <?php
    foreach($bookings as $booking)
    {
        ?>
        <div class="widget-header">
            <div class="title">
                <?php echo $booking['customer_name']; ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Variety</th>
                <th>Customer Quantity</th>
                <th>Total Allocated Quantity</th>
                <th>Allocated Quantity</th>
                <th>Remaining</th>
                <th>Date</th>
            </tr>
            <tbody>
            <?php
            foreach($booking['varieties'] as $variety)
            {
                ?>
                <tr>
                    <td>
                        <?php echo $variety['variety_name']; ?>
                        <input type="hidden" name="allocated_varieties[<?php echo $booking['booking_id'];?>][<?php echo $variety['id'];?>][variety_id]" value="<?php echo $variety['id']; ?>">
                    </td>
                    <td><?php echo $variety['quantity']; ?></td>
                    <td><?php echo '0'; ?></td>
                    <td><input type="text" name="allocated_varieties[<?php echo $booking['booking_id'];?>][<?php echo $variety['id'];?>][quantity]" class="form-control" value=""/></td>
                    <td><?php echo '0'; ?></td>
                    <td><input type="text" name="allocated_varieties[<?php echo $booking['booking_id'];?>][<?php echo $variety['id'];?>][date]" class="form-control datepicker" value="<?php {echo System_helper::display_date(time());} ?>"/></td>

                </tr>
                <?php
                /*if(array_key_exists($variety['id'],$booking_info['varieties']))
                {
                    ?>

                    <tr>
                        <td>
                            <?php echo $variety['variety_name'] ?>
                            <input type="hidden" name="allocated_varieties[<?php echo $consignment['consignment_id'];?>][<?php echo $variety['id'];?>][variety_id]" value="<?php echo $variety['id']; ?>">
                            <input type="hidden" name="allocated_varieties[<?php echo $consignment['consignment_id'];?>][<?php echo $variety['id'];?>][consignment_id]" value="<?php echo $consignment['consignment_id']; ?>">
                        </td>
                        <td><?php echo number_format($variety['quantity']); ?></td>
                        <td><?php echo number_format($booking_info['varieties'][$variety['id']]['quantity']); ?></td>
                        <td><input type="text" name="allocated_varieties[<?php echo $consignment['consignment_id'];?>][<?php echo $variety['id'];?>][quantity]" class="form-control" value="<?php if(isset($allocated_varieties[$consignment['consignment_id']][$variety['id']]['quantity'])){echo $allocated_varieties[$consignment['consignment_id']][$variety['id']]['quantity'];}else{echo '0';} ?>"/></td>
                        <td><input type="text" name="allocated_varieties[<?php echo $consignment['consignment_id'];?>][<?php echo $variety['id'];?>][date]" class="form-control datepicker" value="<?php if(isset($allocated_varieties[$consignment['consignment_id']][$variety['id']]['date'])){echo System_helper::display_date($allocated_varieties[$consignment['consignment_id']][$variety['id']]['date']);}else{echo System_helper::display_date(time());} ?>"/></td>
                    </tr>
                <?php
                }*/
            }
            ?>
            </tbody>
            </thead>

        </table>
        <?php
    }

    ?>

</form>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $(".datepicker").datepicker({dateFormat : display_date_format});
    });
</script>
