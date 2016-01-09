<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
//echo "<pre>";
//print_r($containers);
//echo "</pre>";
//
//echo "<pre>";
//print_r($allocated_varieties);
//echo "</pre>";
//
//echo "<pre>";
//print_r($bookings);
//echo "</pre>";

?>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_VARIETY_TYPE');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="container_variety_type" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
            <?php
            foreach($varieties as $variety)
            {?>
                <option value="<?php echo $variety['id']?>"><?php echo $variety['text'];?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NO');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="container_no" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
        </select>
    </div>
</div>
<div id="select_container">

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
        <div id="data_div" class="col-xs-12" style="overflow: hidden">
            <table class="table table-hover table-bordered" >
                <thead>
                <tr>
                    <th><div class="header_div">Customer</div></th>
                    <th><div class="header_div">Variety</div></th>
                    <th><div class="header_div">EQ</div></th>

                    <?php
                    foreach($containers as $container)
                    {
                        foreach($container as $container_no=>$c)
                        {
                            //.$c['quantity']
                        ?>
                            <th class="text-center"><div class="header_div"><?php echo $c['variety_name'].'<br>'.$container_no.'<br>'; ?></div></th>
                        <?php
                        }
                    }
                    ?>
                    <th><div class="header_div">RV</div></th>
                    <th><div class="header_div">RQ</div></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($bookings as $booking_id=>$booking)
                {
                    /*echo '<PRE>';
                    print_r($booking);
                    echo '</PRE>';*/
                    $customer_info=$booking['customer_name'];
                    $text_variety='';
                    $text_quantity='';
                    foreach($bookings[$booking_id]['varieties'] as $variety)
                    {
                        //$customer_info.=$variety['variety_name'].'-'.number_format($variety['quantity'])."<br>";
                        $text_variety.=$variety['variety_name']."<br>";
                        $text_quantity.=number_format($variety['quantity'])."<br>";
                    }
                    ?>
                    <tr>
                        <td><?php echo $customer_info;?></td>
                        <td><?php echo $text_variety;?></td>
                        <td><?php echo $text_quantity;?></td>
                        <?php
                        foreach($containers as $variety_id=>$container)
                        {
                            foreach($container as $container_no=>$c)
                            {
                                $text_quantity='0';
                                if(isset($allocated_varieties[$booking['booking_id']][$variety_id][$container_no]['quantity']))
                                {
                                    $quantity=$allocated_varieties[$booking['booking_id']][$variety_id][$container_no]['quantity'];
                                    $text_quantity=number_format($quantity);
                                    $bookings[$booking_id]['varieties'][$variety_id]['copy_quantity']-=$quantity;
                                    $containers[$variety_id][$container_no]['total_quantity']+=$quantity;
                                }
                                ?>
                                <td class="text-center"><?php echo $text_quantity; ?></td>
                            <?php
                            }
                        }
                        $text_variety='';
                        $text_quantity='';
                        foreach($bookings[$booking_id]['varieties'] as $variety)
                        {
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['copy_quantity'])."<br>";
                        }
                        ?>
                        <td><?php echo $text_variety; ?></td>
                        <td><?php echo $text_quantity; ?></td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<div id="scroll_div" class="col-xs-12" style="overflow-x: auto;position: fixed;bottom: 10px;background-color: #0daed3" >
    <table class="table table-hover table-bordered" style="margin-bottom: 0;" >
        <thead>
        <tr>
            <th><div class="footer_div"> &nbsp;</div></th>
            <th><div class="footer_div"> &nbsp;</div></th>
            <th><div class="footer_div"> &nbsp;</div></th>

            <?php
            foreach($containers as $container)
            {
                foreach($container as $container_no=>$c)
                {
                    //.$c['quantity']
                    ?>
                    <th class="text-center"><div class="footer_div"><?php echo $c['variety_name'].'<br>'.$container_no.'<br>'; ?></div></th>
                <?php
                }
            }
            ?>
            <th>RV</th>
            <th>RQ</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3"></td>
                <?php
                foreach($containers as $variety_id=>$container)
                {
                    foreach($container as $container_no=>$c)
                    {
                        $text_quantity=number_format($c['total_quantity']);
                        ?>
                        <td class="text-center"><?php echo $text_quantity; ?></td>
                    <?php
                    }
                }
                ?>
                <th><div class="footer_div"> &nbsp;</div></th>
                <th><div class="footer_div"> &nbsp;</div></th>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">

    jQuery(document).ready(function()
    {
        $("#scroll_div").width($("#data_div").width());
        var footer_divs=$(".footer_div");
        $(".header_div").each( function( index)
        {
            var width=$(this).width();
            $(footer_divs[index]).width(width)
        });
        $("#scroll_div").scroll(function()
        {
            console.log('hi1');
            $("#data_div").scrollLeft($("#scroll_div").scrollLeft());
        });
        /*$("#data_div").scroll(function()
        {
            console.log('hi2');
            $("#scroll_div").scrollLeft($("#data_div").scrollLeft());
        });*/

    });
</script>