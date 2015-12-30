<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_booking extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Report_booking');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='report_booking';
        $this->load->model("report_booking_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="report")
        {
            $this->system_report();
        }
        else
        {
            $this->system_search();
        }
    }

    public function system_search()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Booking Report";
            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['crops']=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("report_booking/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search');
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_report()
    {
        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            /*$year=$this->input->post('year');
            echo '<PRE>';
            print_r($this->input->post());
            echo '</PRE>';*/
            if($this->input->post('booking_type')==$this->config->item('booking_status_preliminary'))
            {
                $data['title']='Preliminary Booking Report';
                $data['bookings']=$this->report_booking_model->get_preliminary_booking_report_data();
                $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("report_booking/report_preliminary",$data,true));
            }
            else
            {
                $data['title']='Permanent Booking Report';
                $data['bookings']=$this->report_booking_model->get_permanent_booking_report_data();
                $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("report_booking/report_permanent",$data,true));
            }




            /*$data['bookings']=$this->delivery_container_allocation_model->get_bookings($consignment_id);
            $data['containers']=$this->delivery_container_allocation_model->get_containers($consignment_id);
            $data['allocated_varieties']=$this->delivery_container_allocation_model->get_all_allocated_varieties($consignment_id);*/




            $ajax['status']=false;
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

}
