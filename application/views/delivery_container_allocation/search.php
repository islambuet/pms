<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
$action_data=array();
$action_data["action_save"]='#save_form';
$CI->load->view("action_buttons",$action_data);
?>

<div class="row widget">
    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_YEAR');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-xs-4">
            <select id="year" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                $current_year=date("Y",time());
                for($i=$this->config->item("start_year");$i<=($current_year+1);$i++)
                {?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_CONSIGNMENT_NAME');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <select id="consignment_id" class="form-control">
                <option value=""><?php echo $this->lang->line('SELECT');?></option>
                <?php
                foreach($consignments as $consignment)
                {?>
                    <option value="<?php echo $consignment['id']?>"><?php echo $consignment['consignment_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
</div>
<div class="row widget" id="detail_container">

</div>
<div class="clearfix"></div>

<script type="text/javascript">

jQuery(document).ready(function()
{
    turn_off_triggers();
    $(document).on("change","#year",function()
    {
        $("#detail_container").html("");
        var year=$(this).val();
        if(year>0)
        {
            $.ajax({
                url: base_url+"common_controller/get_dropdown_consignments_by_year/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
    });
    $(document).on("change","#consignment_id",function()
    {
        $("#detail_container").html("");
        var year=$('#year').val();
        var consignment_id=$('#consignment_id').val();
        if(consignment_id>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/list/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year,consignment_id:consignment_id},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
    });
    $(document).on("change","#container_id",function()
    {
        $("#edit_container").html("");
        $("#select_container").html("");
        var year=$('#year').val();
        var consignment_id=$('#consignment_id').val();
        var container_id=$('#container_id').val();
        if(container_id>0)
        {
            $.ajax({
                url: base_url+"<?php echo $CI->controller_url;?>/index/select_list/",
                type: 'POST',
                datatype: "JSON",
                data:{year:year,consignment_id:consignment_id,container_id:container_id},
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
    });
    $(document).on("change","#select_all",function()
    {
        if($(this).is(':checked'))
        {
            $('.select_bookings').prop('checked', true);
        }
        else
        {
            $('.select_bookings').prop('checked', false);
        }

    });
    $(document).on("click", "#load_allocation", function(event)
    {
        $("#edit_container").html("");
        $("#select_form").submit();

    });
    $(document).on("change", ".quantity", function(event)
    {
        var booking_id=$(this).attr('data-booking-id');
        var variety_id=$(this).attr('data-variety-id');
        var quantity=$(this).val();
        var cs_quantity=parseFloat($('#cs_quantity_'+booking_id+'_'+variety_id).val().replace(/,/g,''));
        var other_quantity=parseFloat($('#other_quantity_'+booking_id+'_'+variety_id).val().replace(/,/g,''));
        var remain_quantity=cs_quantity-other_quantity-quantity;
        $("#remain_quantity_"+booking_id+'_'+variety_id).val(parseFloat(remain_quantity).toLocaleString());
        calculate_total(variety_id);
    })
});
function calculate_total(variety_id)
{
    var remain=parseFloat($('#total_quantity_'+variety_id).val().replace(/,/g,''));;
    $( ".quantity" ).each( function( index, element ){
        if(($(this).attr('data-variety-id'))==variety_id)
        {
            remain=remain-parseFloat($(this).val().replace(/,/g,''));
        }

    });
    $("#total_remain_"+variety_id).val(parseFloat(remain).toLocaleString());
}
</script>
