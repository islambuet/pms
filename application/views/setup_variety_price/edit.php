<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $CI = & get_instance();

?>


    <div class="widget-header">
        <div class="title">
            <?php echo $title; ?>
        </div>
        <div class="clearfix"></div>
    </div>
<form class="form_valid" id="save_form" action="<?php echo site_url($CI->controller_url.'/index/save');?>" method="post">
    <input type="hidden" name="variety_id" value="<?php echo $variety_price['variety_id']; ?>" />
    <input type="hidden" name="year" value="<?php echo $variety_price['year']; ?>" />
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_UNIT_PRICE');?><span style="color:#FF0000">*</span></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="variety_price[unit_price]" id="unit_price" class="form-control" value="<?php echo $variety_price['unit_price'];?>"/>
        </div>
    </div>
    <div class="row show-grid">
        <div class="col-xs-4">
            <label class="control-label pull-right"><?php echo $this->lang->line('LABEL_REMARKS');?></label>
        </div>
        <div class="col-sm-4 col-xs-8">
            <input type="text" name="variety_price[remarks]" id="remarks" class="form-control" value="<?php echo $variety_price['remarks'];?>"/>
        </div>
    </div>
</form>