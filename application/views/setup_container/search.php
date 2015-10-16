<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();
    $action_data=array();
    $CI->load->view("action_buttons",$action_data);
//echo "<pre>";
//print_r($consignments);
//echo "</pre>";
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
            <select id="year" name="consignment[year]" class="form-control">
                <?php
                $current_year=date("Y",time());
                for($i=$this->config->item("start_year");$i<=($current_year+1);$i++)
                {?>
                    <option value="<?php echo $i;?>" <?php if($i==$year){ echo "selected";}?>><?php echo $i;?></option>
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
                    <option value="<?php echo $consignment['id']?>" <?php if($consignment['id']==$consignment_id){ echo "selected";}?>><?php echo $consignment['consignment_name'];?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
</div>
    <div class="row widget" id="detail_container">
       <?php
       if($consignment_id>0)
       {
           $data['containers']=Query_helper::get_info($CI->config->item('table_container'),'*',array('consignment_id ='.$consignment_id));
           $data['consignment_id']=$consignment_id;
           $CI->load->view('setup_container/list',$data);
       }
       ?>
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

            var consignment_id=$("#consignment_id").val();
            if(consignment_id>0)
            {
                $.ajax({
                    url: base_url+"<?php echo $CI->controller_url;?>/index/list/",
                    type: 'POST',
                    datatype: "JSON",
                    data:{consignment_id:consignment_id},
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
    });
</script>