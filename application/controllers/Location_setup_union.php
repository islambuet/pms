<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location_setup_union extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Location_setup_union');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='location_setup_union';
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

    public function system_list()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Union List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("location_setup_union/list",$data,true));
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
            $data['title']="Create New Union";
            $data['union']['id']=0;
            $data['union']['zone_id']=0;
            $data['union']['territory_id']=0;
            $data['union']['district_id']=0;
            $data['union']['upazila_id']=0;
            $data['union']['union_name']='';
            $data['union']['remarks']='';
            $data['union']['ordering']=99;
            $data['union']['status']=$this->config->item('system_status_active');
            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=array();
            $data['districts']=array();
            $data['upazilas']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("location_setup_union/add_edit",$data,true));
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
    public function system_edit($id)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            if(($this->input->post('id')))
            {
                $union_id=$this->input->post('id');
            }
            else
            {
                $union_id=$id;
            }

            $data['union']=Query_helper::get_info($this->config->item('table_unions'),'*',array('id ='.$union_id),1);
            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=Query_helper::get_info($this->config->item('table_territories'),array('id','territory_name'),array('zone_id ='.$data['union']['zone_id']));
            $data['districts']=Query_helper::get_info($this->config->item('table_districts'),array('id','district_name'),array('territory_id ='.$data['union']['territory_id']));
            $data['upazilas']=Query_helper::get_info($this->config->item('table_upazilas'),array('id','upazila_name'),array('district_id ='.$data['union']['district_id']));
            $data['title']="Edit Union (".$data['union']['union_name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("location_setup_union/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$union_id);
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
        $id = $this->input->post("id");
        if($id>0)
        {
            if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();
            }
        }
        else
        {
            if(!(isset($this->permissions['add'])&&($this->permissions['add']==1)))
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
            $data = $this->input->post('union');
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_unions'),$data,array("id = ".$id));
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
            else
            {
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::add($this->config->item('table_unions'),$data);
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
    }
    private function check_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('union[zone_id]',$this->lang->line('LABEL_ZONE_NAME'),'required');
        $this->form_validation->set_rules('union[territory_id]',$this->lang->line('LABEL_TERRITORY_NAME'),'required');
        $this->form_validation->set_rules('union[district_id]',$this->lang->line('LABEL_DISTRICT_NAME'),'required');
        $this->form_validation->set_rules('union[upazila_id]',$this->lang->line('LABEL_UPAZILA_NAME'),'required');
        $this->form_validation->set_rules('union[union_name]',$this->lang->line('LABEL_UNION_NAME'),'required');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }
    public function get_crops()
    {
        //$this->db->from($this->config->item('table_upazilas').' upazilas');
        $this->db->from($this->config->item('table_unions').' unions');
        $this->db->select('unions.id id,unions.union_name union_name');
        $this->db->select('unions.remarks remarks,unions.status status,unions.ordering ordering');
        $this->db->select('zones.zone_name zone_name');
        $this->db->select('territories.territory_name territory_name');
        $this->db->select('districts.district_name district_name');
        $this->db->select('upazilas.upazila_name upazila_name');
        $this->db->join($this->config->item('table_zones').' zones','zones.id = unions.zone_id','INNER');
        $this->db->join($this->config->item('table_territories').' territories','territories.id = unions.territory_id','INNER');
        $this->db->join($this->config->item('table_districts').' districts','districts.id = unions.district_id','INNER');
        $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->where('unions.status !=',$this->config->item('system_status_delete'));
        $districts=$this->db->get()->result_array();
        $this->jsonReturn($districts);

    }

}
