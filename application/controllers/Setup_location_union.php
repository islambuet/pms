<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_location_union extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_location_union');
        $this->controller_url='setup_location_union';
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

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Union List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_union/list",$data,true));
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
        //$this->db->from($this->config->item('table_upazilas').' upazilas');
        $this->db->from($this->config->item('table_location_unions').' unions');
        $this->db->select('unions.id id,unions.name union_name');
        $this->db->select('unions.remarks remarks,unions.status status,unions.ordering ordering');
        $this->db->select('zones.name zone_name');
        $this->db->select('territories.name territory_name');
        $this->db->select('districts.name district_name');
        $this->db->select('upazilas.name upazila_name');
        $this->db->join($this->config->item('table_location_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->join($this->config->item('table_location_districts').' districts','districts.id = upazilas.district_id','INNER');
        $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
        $this->db->join($this->config->item('table_location_zones').' zones','zones.id = territories.zone_id','INNER');

        $this->db->order_by('zones.ordering');
        $this->db->order_by('territories.ordering');
        $this->db->order_by('districts.ordering');
        $this->db->order_by('upazilas.ordering');
        $this->db->order_by('unions.ordering');

        $this->db->where('unions.status !=',$this->config->item('system_status_delete'));
        $districts=$this->db->get()->result_array();
        $this->jsonReturn($districts);

    }

    private function system_add()
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {
            $data['title']="Create New Union";
            $data['union']['id']=0;
            $data['union']['zone_id']=0;
            $data['union']['territory_id']=0;
            $data['union']['district_id']=0;
            $data['union']['upazila_id']=0;
            $data['union']['name']='';
            $data['union']['remarks']='';
            $data['union']['ordering']=999;
            $data['union']['status']=$this->config->item('system_status_active');
            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=array();
            $data['districts']=array();
            $data['upazilas']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_union/add_edit",$data,true));
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

            //$data['union']=Query_helper::get_info($this->config->item('table_unions'),'*',array('id ='.$union_id),1);
            $this->db->from($this->config->item('table_location_unions').' unions');
            $this->db->select('unions.*');
            $this->db->select('territories.zone_id zone_id');
            $this->db->select('districts.territory_id territory_id');
            $this->db->select('upazilas.district_id district_id');
            $this->db->join($this->config->item('table_location_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
            $this->db->join($this->config->item('table_location_districts').' districts','districts.id = upazilas.district_id','INNER');
            $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
            $this->db->where('unions.id',$union_id);
            $data['union']=$this->db->get()->row_array();

            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=Query_helper::get_info($this->config->item('table_location_territories'),array('id value','name text'),array('zone_id ='.$data['union']['zone_id']));
            $data['districts']=Query_helper::get_info($this->config->item('table_location_districts'),array('id value','name text'),array('territory_id ='.$data['union']['territory_id']));
            $data['upazilas']=Query_helper::get_info($this->config->item('table_location_upazilas'),array('id value','name text'),array('district_id ='.$data['union']['district_id']));
            $data['title']="Edit Union (".$data['union']['name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_union/add_edit",$data,true));
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

    private function system_save()
    {
        $user=User_helper::get_user();
        $time=time();
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

            $this->db->trans_start();  //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update($this->config->item('table_location_unions'),$data,array("id = ".$id));

            }
            else
            {
                $data['user_created'] = $user->user_id;
                $data['date_created'] = $time;
                Query_helper::add($this->config->item('table_location_unions'),$data);
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
        $this->form_validation->set_rules('union[upazila_id]',$this->lang->line('LABEL_UPAZILA_NAME'),'required');
        $this->form_validation->set_rules('union[name]',$this->lang->line('LABEL_UNION_NAME'),'required');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }


}
