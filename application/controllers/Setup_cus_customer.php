<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setup_cus_customer extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Setup_cus_customer');
        $this->controller_url='setup_cus_customer';
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
            $data['title']="Customer List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cus_customer/list",$data,true));
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
        //$this->db->from($this->config->item('table_unions').' unions');
        $this->db->from($this->config->item('table_cus_customer').' customers');
        $this->db->select('customers.id id,customers.customer_custom_id,customers.name customer_name,customers.email,customers.contact_no');
        $this->db->select('customers.remarks remarks,customers.status status,customers.ordering ordering');
        $this->db->select('zones.name zone_name');
        $this->db->select('territories.name territory_name');
        $this->db->select('districts.name district_name');
        $this->db->select('upazilas.name upazila_name');
        $this->db->select('unions.name union_name');
        $this->db->join($this->config->item('table_location_unions').' unions','unions.id = customers.union_id','INNER');
        $this->db->join($this->config->item('table_location_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->join($this->config->item('table_location_districts').' districts','districts.id = upazilas.district_id','INNER');
        $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
        $this->db->join($this->config->item('table_location_zones').' zones','zones.id = territories.zone_id','INNER');
        $this->db->where('customers.status !=',$this->config->item('system_status_delete'));
        $this->db->order_by('zones.ordering');
        $this->db->order_by('territories.ordering');
        $this->db->order_by('districts.ordering');
        $this->db->order_by('upazilas.ordering');
        $this->db->order_by('unions.ordering');
        $this->db->order_by('customers.ordering');
        $items=$this->db->get()->result_array();
        $this->jsonReturn($items);

    }
    private function system_add()
    {
        if(isset($this->permissions['add'])&&($this->permissions['add']==1))
        {
            $data['title']="Create New Customer";
            $data['customer']['id']=0;
            $data['customer']['zone_id']=0;
            $data['customer']['territory_id']=0;
            $data['customer']['district_id']=0;
            $data['customer']['upazila_id']=0;
            $data['customer']['union_id']=0;
            $data['customer']['name']='';
            $data['customer']['customer_custom_id']='';
            $data['customer']['owner_name']='';
            $data['customer']['father_name']='';
            $data['customer']['address']='';
            $data['customer']['contact_no']='';
            $data['customer']['email']='';
            $data['customer']['remarks']='';
            $data['customer']['ordering']=99;
            $data['customer']['status']=$this->config->item('system_status_active');
            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['territories']=array();
            $data['districts']=array();
            $data['upazilas']=array();
            $data['unions']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cus_customer/add_edit",$data,true));
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
                $customer_id=$this->input->post('id');
            }
            else
            {
                $customer_id=$id;
            }
            $this->db->from($this->config->item('table_cus_customer').' customers');
            $this->db->select('customers.*');
            $this->db->select('territories.zone_id zone_id');
            $this->db->select('districts.territory_id territory_id');
            $this->db->select('upazilas.district_id district_id');
            $this->db->select('unions.upazila_id upazila_id');
            $this->db->join($this->config->item('table_location_unions').' unions','unions.id = customers.union_id','INNER');
            $this->db->join($this->config->item('table_location_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
            $this->db->join($this->config->item('table_location_districts').' districts','districts.id = upazilas.district_id','INNER');
            $this->db->join($this->config->item('table_location_territories').' territories','territories.id = districts.territory_id','INNER');
            $this->db->where('customers.id',$customer_id);
            $data['customer']=$this->db->get()->row_array();

            $data['zones']=Query_helper::get_info($this->config->item('table_location_zones'),array('id value','name text'),array('status ="'.$this->config->item('system_status_active').'"'),0,0,array('ordering ASC'));
            $data['territories']=Query_helper::get_info($this->config->item('table_location_territories'),array('id value','name text'),array('zone_id ='.$data['customer']['zone_id']),0,0,array('ordering ASC'));
            $data['districts']=Query_helper::get_info($this->config->item('table_location_districts'),array('id value','name text'),array('territory_id ='.$data['customer']['territory_id']),0,0,array('ordering ASC'));
            $data['upazilas']=Query_helper::get_info($this->config->item('table_location_upazilas'),array('id value','name text'),array('district_id ='.$data['customer']['district_id']),0,0,array('ordering ASC'));
            $data['unions']=Query_helper::get_info($this->config->item('table_location_unions'),array('id value','name text'),array('upazila_id ='.$data['customer']['upazila_id']),0,0,array('ordering ASC'));
            $data['title']="Edit Customer (".$data['customer']['name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("setup_cus_customer/add_edit",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/edit/'.$customer_id);
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
            $data = $this->input->post('customer');
            $this->db->trans_start();  //DB Transaction Handle START
            if($id>0)
            {
                $data['user_updated']=$user->user_id;
                $data['date_updated']=$time;
                Query_helper::update($this->config->item('table_cus_customer'),$data,array("id = ".$id));

            }
            else
            {
                $data['user_created'] = $user->user_id;
                $data['date_created'] = $time;
                Query_helper::add($this->config->item('table_cus_customer'),$data);
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
        $this->form_validation->set_rules('customer[union_id]',$this->lang->line('LABEL_UNION_NAME'),'required');
        $this->form_validation->set_rules('customer[name]',$this->lang->line('LABEL_NAME'),'required');
        $this->form_validation->set_rules('customer[contact_no]',$this->lang->line('LABEL_CONTACT_NO'),'required');
        $this->form_validation->set_rules('customer[email]',$this->lang->line('LABEL_EMAIL'),'valid_email');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }


}
