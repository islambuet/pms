<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<option value=""><?php echo $this->lang->line('SELECT');?></option>
<?php



    foreach($items as $item)
    {
    ?>
        <option value="<?php echo $item['value'];?>"><?php echo $item['text'];?></option>
    <?php

}
?>

