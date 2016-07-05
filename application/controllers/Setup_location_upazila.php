<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_location_upazila extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_location_upazila');
        $this->controller_url='setup_location_upazila';
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

    public function system_list()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Upazila List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_upazila/list",$data,true));
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
    public function get_items()
    {
        //$this->db->from($this->config->item('table_districts').' districts');
        $this->db->from($this->config->item('table_location_upazilas').' upazilas');
        $this->db->select('upazilas.id id,upazilas.name upazila_name');
        $this->db->select('upazilas.remarks remarks,upazilas.status status,upazilas.ordering ordering');
        $this->db->select('zones.name zone_name');
        $this->db->select('territories.name territory_name');
        $this->db->select('districts.name district_name');
        $this->db->join($this->config->item('table_location_districts').' districts','districts.id = upazilas.district_id','INNER');
        $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
        $this->db->join($this->config->item('table_location_zones').' zones','zones.id = territories.zone_id','INNER');
        $this->db->where('upazilas.status !=',$this->config->item('system_status_delete'));
        $this->db->order_by('zones.ordering');
        $this->db->order_by('territories.ordering');
        $this->db->order_by('districts.ordering');
        $this->db->order_by('upazilas.ordering');
        $districts=$this->db->get()->result_array();
        $this->jsonReturn($districts);

    }

    public function system_add()
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {
            $data['title']="Create New Upazila";
            $data['upazila']['id']=0;
            $data['upazila']['zone_id']=0;
            $data['upazila']['territory_id']=0;
            $data['upazila']['district_id']=0;
            $data['upazila']['name']='';
            $data['upazila']['remarks']='';
            $data['upazila']['ordering']=999;
            $data['upazila']['status']=$this->config->item('system_status_active');
            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=array();
            $data['districts']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_upazila/add_edit",$data,true));
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
                $upazila_id=$this->input->post('id');
            }
            else
            {
                $upazila_id=$id;
            }

            $this->db->from($this->config->item('table_location_upazilas').' upazilas');
            $this->db->select('upazilas.*');

            $this->db->select('territories.zone_id zone_id');
            $this->db->select('districts.territory_id territory_id');
            $this->db->join($this->config->item('table_location_districts').' districts','districts.id = upazilas.district_id','INNER');
            $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');


            $this->db->where('upazilas.id',$upazila_id);
            $data['upazila']=$this->db->get()->row_array();


            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=Query_helper::get_info($this->config->item('table_location_territories'),array('id value','name text'),array('zone_id ='.$data['upazila']['zone_id']));
            $data['districts']=Query_helper::get_info($this->config->item('table_location_districts'),array('id value','name text'),array('territory_id ='.$data['upazila']['territory_id']));
            $data['title']="Edit Upazila (".$data['upazila']['name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_location_upazila/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$upazila_id);
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
            $data = $this->input->post('upazila');

            $this->db->trans_start();  //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update($this->config->item('table_location_upazilas'),$data,array("id = ".$id));

            }
            else
            {
                $data['user_created'] = $user->user_id;
                $data['date_created'] = $time;
                Query_helper::add($this->config->item('table_location_upazilas'),$data);
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
        $this->form_validation->set_rules('upazila[name]',$this->lang->line('LABEL_UPAZILA_NAME'),'required');
        $this->form_validation->set_rules('upazila[district_id]',$this->lang->line('LABEL_DISTRICT_NAME'),'required');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }


}
