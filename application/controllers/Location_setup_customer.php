<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Location_setup_customer extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Location_setup_customer');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='location_setup_customer';
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
            $data['title']="Customer List";
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("location_setup_customer/list",$data,true));
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
            $data['title']="Create New Customer";
            $data['customer']['id']=0;
            $data['customer']['zone_id']=0;
            $data['customer']['territory_id']=0;
            $data['customer']['district_id']=0;
            $data['customer']['upazila_id']=0;
            $data['customer']['union_id']=0;
            $data['customer']['customer_name']='';
            $data['customer']['owner_name']='';
            $data['customer']['father_name']='';
            $data['customer']['address']='';
            $data['customer']['contact_no']='';
            $data['customer']['email']='';
            $data['customer']['remarks']='';
            $data['customer']['ordering']=99;
            $data['customer']['status']=$this->config->item('system_status_active');
            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=array();
            $data['districts']=array();
            $data['upazilas']=array();
            $data['unions']=array();
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("location_setup_customer/add_edit",$data,true));
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
                $customer_id=$this->input->post('id');
            }
            else
            {
                $customer_id=$id;
            }

            //$data['customer']=Query_helper::get_info($this->config->item('table_customers'),'*',array('id ='.$customer_id),1);
            $this->db->from($this->config->item('table_customers').' customers');
            $this->db->select('customers.*');
            $this->db->select('territories.zone_id zone_id');
            $this->db->select('districts.territory_id territory_id');
            $this->db->select('upazilas.district_id district_id');
            $this->db->select('unions.upazila_id upazila_id');
            $this->db->join($this->config->item('table_unions').' unions','unions.id = customers.union_id','INNER');
            $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
            $this->db->join($this->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
            $this->db->join($this->config->item('table_territories').' territories','territories.id = districts.territory_id','INNER');
            $this->db->where('customers.id',$customer_id);
            $data['customer']=$this->db->get()->row_array();

            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $data['territories']=Query_helper::get_info($this->config->item('table_territories'),array('id','territory_name'),array('zone_id ='.$data['customer']['zone_id']));
            $data['districts']=Query_helper::get_info($this->config->item('table_districts'),array('id','district_name'),array('territory_id ='.$data['customer']['territory_id']));
            $data['upazilas']=Query_helper::get_info($this->config->item('table_upazilas'),array('id','upazila_name'),array('district_id ='.$data['customer']['district_id']));
            $data['unions']=Query_helper::get_info($this->config->item('table_unions'),array('id','union_name'),array('upazila_id ='.$data['customer']['upazila_id']));
            $data['title']="Edit Customer (".$data['customer']['customer_name'].')';
            $ajax['status']=true;
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("location_setup_customer/add_edit",$data,true));
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
            $data = $this->input->post('customer');
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_customers'),$data,array("id = ".$id));
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
                Query_helper::add($this->config->item('table_customers'),$data);
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
        $this->form_validation->set_rules('customer[union_id]',$this->lang->line('LABEL_UNION_NAME'),'required');
        $this->form_validation->set_rules('customer[customer_name]',$this->lang->line('LABEL_NAME'),'required');
        $this->form_validation->set_rules('customer[contact_no]',$this->lang->line('LABEL_CONTACT_NO'),'required');
        $this->form_validation->set_rules('customer[email]',$this->lang->line('LABEL_EMAIL'),'valid_email');

        if($this->form_validation->run() == FALSE)
        {
            $this->message=validation_errors();
            return false;
        }
        return true;
    }
    public function get_items()
    {
        //$this->db->from($this->config->item('table_unions').' unions');
        $this->db->from($this->config->item('table_customers').' customers');
        $this->db->select('customers.id id,customers.customer_name customer_name,customers.email,customers.contact_no');
        $this->db->select('customers.remarks remarks,customers.status status,customers.ordering ordering');
        $this->db->select('zones.zone_name zone_name');
        $this->db->select('territories.territory_name territory_name');
        $this->db->select('districts.district_name district_name');
        $this->db->select('upazilas.upazila_name upazila_name');
        $this->db->select('unions.union_name union_name');
        $this->db->join($this->config->item('table_unions').' unions','unions.id = customers.union_id','INNER');
        $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->join($this->config->item('table_districts').' districts','districts.id = upazilas.district_id','INNER');
        $this->db->join($this->config->item('table_territories').' territories','territories.id = districts.territory_id','INNER');
        $this->db->join($this->config->item('table_zones').' zones','zones.id = territories.zone_id','INNER');
        $this->db->where('customers.status !=',$this->config->item('system_status_delete'));
        $districts=$this->db->get()->result_array();
        $this->jsonReturn($districts);

    }

}
