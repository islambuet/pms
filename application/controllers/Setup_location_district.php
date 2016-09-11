<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_location_district extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_location_district');
        $this->controller_url='setup_location_district';
        //$this->load->model("sys_user_role_model");
    }

    public function index($action="list",$id=0)
    {
        if($action=="list")
        {
            $this->system_list($id);
        }
        elseif($action=="get_items")
        {
            $this->get_items();
        }
        elseif($action=="add")
        {
            $this->system_add();
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
            $this->system_list($id);
        }
    }

    private function system_list()
    {

        if(isset($this->permissions['action0'])&&($this->permissions['action0']==1))
        {
            $data['title']="District List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_district/list",$data,true));
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
    private function get_items()
    {
        $this->db->from($this->config->item('table_location_districts').' districts');
        $this->db->select('districts.id id,districts.name district_name');
        $this->db->select('districts.remarks remarks,districts.status status,districts.ordering ordering');
        $this->db->select('zones.name zone_name');
        $this->db->select('territories.name territory_name');
        $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
        $this->db->join($this->config->item('table_location_zones').' zones','zones.id = territories.zone_id','INNER');

        $this->db->where('districts.status !=',$this->config->item('system_status_delete'));
        $districts=$this->db->get()->result_array();
        $this->jsonReturn($districts);

    }

    private function system_add()
    {
        if(isset($this->permissions['action1'])&&($this->permissions['action1']==1))
        {
            $data['title']="Create New District";
            $data['district']['id']=0;
            $data['district']['zone_id']=0;
            $data['district']['territory_id']=0;
            $data['district']['name']='';
            $data['district']['remarks']='';
            $data['district']['ordering']=99;
            $data['district']['status']=$this->config->item('system_status_active');
            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_district/add_edit",$data,true));
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
    private function system_edit($id)
    {
        if(isset($this->permissions['action2'])&&($this->permissions['action2']==1))
        {
            if(($this->input->post('id')))
            {
                $district_id=$this->input->post('id');
            }
            else
            {
                $district_id=$id;
            }

            //$data['district']=Query_helper::get_info($this->config->item('table_districts'),'*',array('id ='.$district_id),1);

            $this->db->from($this->config->item('table_location_districts').' districts');
            $this->db->select('districts.*');
            $this->db->select('territories.zone_id zone_id');
            $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
            $this->db->where('districts.id',$district_id);
            $data['district']=$this->db->get()->row_array();


            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=Query_helper::get_info($this->config->item('table_location_territories'),array('id value','name text'),array('zone_id ='.$data['district']['zone_id']));
            $data['title']="Edit District (".$data['district']['name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_district/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$district_id);
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

    private function system_save()
    {
        $user=User_helper::get_user();
        $time=time();
        $id = $this->input->post("id");
        if($id>0)
        {
            if(!(isset($this->permissions['action2'])&&($this->permissions['action2']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();
            }
        }
        else
        {
            if(!(isset($this->permissions['action1'])&&($this->permissions['action1']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();

            }
        }
        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $data = $this->input->post('district');
            $this->db->trans_start();  //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update($this->config->item('table_location_districts'),$data,array("id = ".$id));

            }
            else
            {
                $data['user_created'] = $user->user_id;
                $data['date_created'] = $time;
                Query_helper::add($this->config->item('table_location_districts'),$data);
            }
            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {
                $save_and_new=$this->input->post('system_save_new_status');
                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                if($save_and_new==1)
                {
                    $this->system_add();
                }
                else
                {
                    $this->system_list();
                }
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules('district[name]',$this->lang->line('LABEL_DISTRICT_NAME'),'required');
        $this->form_validation->set_rules('district[territory_id]',$this->lang->line('LABEL_TERRITORY_NAME'),'required');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }


}
