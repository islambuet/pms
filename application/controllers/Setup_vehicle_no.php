<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_vehicle_no extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_vehicle_no');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='setup_vehicle_no';
        //$this->load->model("sys_user_role_model");
    }

    public function index($action="list",$id=0)
    {
        if($action=="list")
        {
            $this->system_list($id);
        }
        elseif($action=="add")
        {
            $this->system_add();
        }
        elseif($action=="edit")
        {
            $this->system_edit();
        }
        elseif($action=="save")
        {
            $this->system_save();
        }
        else
        {
            $this->system_list($id);
        }
    }

    public function system_list()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="No of vehicles list";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_vehicle_no/list",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

    public function system_add()
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {
            $data['title']="Add/Edit Vehicle no";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_vehicle_no/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/add');
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_edit()
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $year=$this->input->post('year');
            $consignment_id=$this->input->post('consignment_id');


            $data['year']=$year;
            $data['consignment_id']=$consignment_id;
            $data['no_of_vehicles']=0;
            $vhinfo=Query_helper::get_info($this->config->item('table_setup_vehicle_no'),'*',array('consignment_id ='.$consignment_id,'revision =1'),1);
            if($vhinfo)
            {
                $data['no_of_vehicles']=$vhinfo['no_of_vehicles'];
            }
            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("setup_vehicle_no/edit",$data,true));

            $ajax['status']=false;
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
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
            $year=$this->input->post('year');
            $consignment_id=$this->input->post('consignment_id');
            $no_of_vehicles=$this->input->post('no_of_vehicles');
            $time=time();

            $data['consignment_id']=$consignment_id;
            $data['no_of_vehicles']=$no_of_vehicles;
            $data['revision']=1;
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;

            $this->db->trans_start();  //DB Transaction Handle START

            $this->db->where('consignment_id',$consignment_id);
            $this->db->set('revision', 'revision+1', FALSE);
            $this->db->update($this->config->item('table_setup_vehicle_no'));
            Query_helper::add($this->config->item('table_setup_vehicle_no'),$data);
            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                $this->system_list();
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                $this->jsonReturn($ajax);
            }
        }
    }
    private function check_validation()
    {
        return true;
    }
    public function get_items()
    {
        $this->db->from($this->config->item('table_setup_vehicle_no').' vn');
        $this->db->select('vn.no_of_vehicles');
        $this->db->select('c.consignment_name,c.year');
        $this->db->join($this->config->item('table_consignment').' c','c.id = vn.consignment_id','INNER');
        $this->db->where('vn.revision',1);
        $results=$this->db->get()->result_array();
        $this->jsonReturn($results);

    }

}
