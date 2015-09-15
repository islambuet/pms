<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_controller extends Root_Controller
{
    private  $message;
    public function __construct()
    {
        parent::__construct();
        $this->message="";

    }
    //get crop types of a crop
    public function get_dropdown_classifications_by_cropid()
    {
        $crop_id = $this->input->post('crop_id');
        $data['items']=Query_helper::get_info($this->config->item('table_classifications'),array('id value','classification_name text'),array('crop_id ='.$crop_id));
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#classification_id","html"=>$this->load->view("dropdown_with_select",$data,true));

        $this->jsonReturn($ajax);
    }
}
