<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$CI = & get_instance();
?>
<html lang="en">
<head>
    <title>PMS</title>
    <link rel="shortcut icon"  type="image/x-icon" href="<?php echo base_url(); ?>images/favicon.ico">
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
<!--    <link rel="stylesheet" href="--><?php //echo base_url(); ?><!--css/validationEngine.jquery.css">-->
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui/jquery-ui.theme.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>/css/jqx/jqx.base.css">

</head>
<body>
    <script src="<?php echo base_url(); ?>js/jquery-2.1.1.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap-filestyle.min.js"></script>
    <script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>

    <!--    for jqx grid finish-->
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxcore.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxscrollbar.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.edit.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.sort.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxbuttons.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxcheckbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxlistbox.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxmenu.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxdata.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jqx/jqxdatatable.js"></script>
    <!--    for jqx grid end-->

<!--    <script src="--><?php //echo base_url() ?><!--js/validator_js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>-->
<!--    <script src="--><?php //echo base_url() ?><!--js/validator_js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>-->
    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>";
        var display_date_format = "dd-M-yy";
        var SELCET_ONE_ITEM = "<?php echo $CI->lang->line('SELECT_ONE_ITEM'); ?>";
    </script>
    <header class="hidden-print">

                <img alt="Logo" height="40" class="site_logo pull-left" src="<?php echo base_url(); ?>images/logo.png">
                <div class="site_title pull-left">A.R MALIK & Co. (PVT) LTD.</div>
                <div class="user_info pull-right" id="user_info"></div>

    </header>
    <div class="container-fluid">
        <div class="row dashboard-wrapper">
            <div class="col-sm-12" id="system_content">
            <?php
                //$this->load->view("login");
            ?>
            </div>
<!--            <div class="col-sm-3 hidden-sm hidden-xs" id="right_side">-->
<!---->
<!--            </div>-->

        </div>

    </div>
    <footer>
        <div>
            &copy; 2014 - All Rights Reserved <a class="external" href="http://amaderit.com" target="blank">Amader IT</a>
        </div>
        <div class="clearfix"></div>
    </footer>
    <div id="system_loading"><img src="<?php echo base_url(); ?>images/spinner.gif"></div>
    <div id="system_message"></div>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/system_common.js"></script>


</body>
</html>