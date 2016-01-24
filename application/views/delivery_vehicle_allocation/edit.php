<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo '<PRE>';
//print_r($bookings);
//echo '</PRE>';
//echo '<PRE>';
//print_r($allocated_vehicles);
//echo '</PRE>';
//return;
?>



<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" name="year" value="<?php echo $year; ?>" />
    <input type="hidden" name="consignment_id" value="<?php echo $consignment_id; ?>" />
    <input type="hidden" name="container_variety_type" value="<?php echo $container_variety_type; ?>" />
    <input type="hidden" name="container_no" value="<?php echo $container_no; ?>" />

    <div class="row show-grid">
        <div class="col-xs-12">
            <table class="table table-bordered">
                <thead>
                    <th>Customer</th>
                    <th>Allocated Quantity</th>
                    <th>Vehicle No</th>
                    <th>Vehicle Quantity</th>
                </thead>
                <tbody>
                <?php
                if(sizeof($bookings)>0)
                {
                    foreach($bookings as $booking)
                    {
                        $selected_no='';
                        $selected_quantity='';
                        if(isset($allocated_vehicles[$booking['booking_id']]))
                        {
                            $selected_no=$allocated_vehicles[$booking['booking_id']]['vehicle_no'];
                            $selected_quantity=$allocated_vehicles[$booking['booking_id']]['quantity'];
                        }
                        ?>
                        <tr>
                            <td><?php echo $booking['customer_name']; ?></td>
                            <td><?php echo $booking['quantity']; ?></td>
                            <td>
                                <select name="allocated_vehicles[<?php echo $booking['booking_id']; ?>][vehicle_no]" class="form-control" tabindex="-1">
                                    <option value=""><?php echo $this->lang->line('SELECT');?></option>
                                    <?php
                                    for($i=1;$i<=$no_of_vehicles;$i++)
                                    {
                                        ?>
                                        <option value="<?php echo $i;?>" <?php if($selected_no==$i){ echo "selected";}?>><?php echo $i;?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td><input type="text" name="allocated_vehicles[<?php echo $booking['booking_id'] ?>][quantity]" value="<?php echo ($selected_quantity); ?>"></td>
                        </tr>
                        <?php
                    }
                }
                else
                {
                    ?>
                    <tr>
                        <td colspan="20" class="text-center alert-danger">
                            <?php echo $this->lang->line("NO_DATA_FOUND"); ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


</form>

<script type="text/javascript">

    jQuery(document).ready(function()
    {
        //$(".datepicker").datepicker({dateFormat : display_date_format});
    });
</script>
