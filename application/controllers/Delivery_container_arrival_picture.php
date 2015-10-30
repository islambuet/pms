<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_container_arrival_picture extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Delivery_container_arrival_picture');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='delivery_container_arrival_picture';
        //$this->load->model("allocation_consignment_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="edit")
        {
            $this->system_edit($id);
        }
        elseif($action=="save")
        {
            $this->system_save();
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
            $data['title']="Container Arrival Picture";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("delivery_container_arrival_picture/search",$data,true));
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
    public function system_edit($id)
    {
        if(($this->input->post('container_id')))
        {
            $container_id=$this->input->post('container_id');
        }
        else
        {
            $container_id=$id;
        }
        $data['container_id']=$container_id;
        $this->db->from($this->config->item('table_setup_container_arrival'));
        $this->db->order_by('ordering ASC');
        $data['pictures']=$this->db->get()->result_array();
        $data['container_info']=Query_helper::get_info($this->config->item('table_data_container_arrival'),'*',array('container_id ='.$container_id));
        $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("delivery_container_arrival_picture/edit",$data,true));

        $ajax['status']=false;
        if($this->message)
        {
            $ajax['system_message']=$this->message;
        }
        $this->jsonReturn($ajax);
    }

    public function system_save()
    {
        $user=User_helper::get_user();


        if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
            die();
        }

        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $uploaded_images = System_helper::upload_file('images/delivery');
            echo "<pre>";
            print_r($uploaded_images);
            echo "</pre>";

        }
    }
    private function check_validation()
    {

        return true;
    }

}
