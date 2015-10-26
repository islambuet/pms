<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//echo "<pre>";
//print_r($containers);
//echo "</pre>";

?>
<div class="row show-grid">
    <div class="col-xs-4">
        <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONTAINER_NAME');?><span style="color:#FF0000">*</span></label>
    </div>
    <div class="col-xs-4">
        <select id="container_id" class="form-control">
            <option value=""><?php echo $this->lang->line('SELECT');?></option>
            <?php
            foreach($containers as $container)
            {?>
                <option value="<?php echo $container['container_id']?>"><?php echo $container['container_name'];?></option>
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
                        <th><?php echo $this->lang->line('LABEL_CONTAINER_NAME');?></th>
                        <th>Variety</th>
                        <th>EQ</th>
                        <th>Allocation</th>
                        <th id="header_rv">RV</th>
                        <th id="header_rq">RQ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($containers as $container)
                {
                    ?>
                    <tr>
                        <td><?php echo $container['container_name']; ?></td>
                        <?php
                        $text_variety='';
                        $text_quantity='';
                        foreach($container['varieties'] as $variety)
                        {
                            $text_variety.=$variety['variety_name']."<br>";
                            $text_quantity.=number_format($variety['quantity'])."<br>";
                        }
                        ?>
                        <td><?php echo $text_variety; ?></td>
                        <td><?php echo $text_quantity; ?></td>
                        <td>
                            <b>Customer x</b><br>
                            v1-10<br>
                            v2-10<br>
                            <b>Customer y</b><br>
                            v1-10<br>
                            v2-10<br>

                        </td>
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
<script type="text/javascript">

    jQuery(document).ready(function()
    {

        $( ".bottom" ).each(function( index )
        {
            var consignment_id=$(this).attr('data-consignment-id');
            var header=$('#header_'+consignment_id);
            var width=header.width();
            $(this).outerWidth(width);

        });
    });
</script>