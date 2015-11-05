<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Receiving_product_receiving_picture extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Receiving_product_receiving_picture');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='receiving_product_receiving_picture';
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
            $data['title']="Vehicle Loading Picture";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("receiving_product_receiving_picture/search",$data,true));
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
        if($this->input->post('vehicle_loading_id'))
        {
            $vehicle_loading_id=$this->input->post('vehicle_loading_id');
        }
        else
        {
            $vehicle_loading_id=$id;
        }
        $this->db->from($this->config->item('table_setup_product_receiving'));
        $this->db->order_by('ordering ASC');
        $data['pictures']=$this->db->get()->result_array();
        $data['vehicle_loading_id']=$vehicle_loading_id;
        $data['vehicle_info']=Query_helper::get_info($this->config->item('table_data_product_receiving'),'*',array('vehicle_loading_id ='.$vehicle_loading_id),1);

        $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("receiving_product_receiving_picture/edit",$data,true));

        $ajax['status']=true;
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
            $vehicle_loading_id=$this->input->post('vehicle_loading_id');
            $uploaded_images = System_helper::upload_file('images/delivery');

            $this->db->from($this->config->item('table_setup_product_receiving'));
            $this->db->order_by('ordering ASC');
            $pictures=$this->db->get()->result_array();
            $info=array();
            foreach($pictures as $picture)
            {
                if(array_key_exists('image_'.$picture['id'],$uploaded_images))
                {
                    $info[$picture['id']]=$uploaded_images['image_'.$picture['id']]['info']['file_name'];
                }
                else
                {
                    $info[$picture['id']]=$this->input->post('previous_image_'.$picture['id']);
                }
            }
            $vehicle_info=Query_helper::get_info($this->config->item('table_data_product_receiving'),'*',array('vehicle_loading_id ='.$vehicle_loading_id),1);
            $time=time();
            $data=$this->input->post('vehicle');
            $data['images']=json_encode($info);

            $this->db->trans_start();  //DB Transaction Handle START
            if($vehicle_info)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                Query_helper::update($this->config->item('table_data_product_receiving'),$data,array("id = ".$vehicle_info['id']));
            }
            else
            {
                $data['vehicle_loading_id']=$vehicle_loading_id;
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                Query_helper::add($this->config->item('table_data_product_receiving'),$data);
            }
            $this->db->trans_complete();   //DB Transaction Handle END
            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                $this->system_edit($vehicle_loading_id);
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                $this->jsonReturn($ajax);
            }
        }
    }



    public function get_driver_nos()
    {
        $container_id=$this->input->post('container_id');
        $booking_id=$this->input->post('booking_id');

        $this->db->from($this->config->item('table_data_vehicle_loading').' dvl');
        $this->db->where('dvl.container_id',$container_id);
        $this->db->where('dvl.booking_id',$booking_id);
        $this->db->select('id value,vehicle_number text');
        $data['items']=$this->db->get()->result_array();

        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#vehicle_no","html"=>$this->load->view("dropdown_with_select",$data,true));
        $this->jsonReturn($ajax);
    }
    public function get_bookings()
    {
        $container_id=$this->input->post('container_id');

        $this->db->from($this->config->item('table_data_vehicle_loading').' dvl');
        $this->db->select('DISTINCT(booking_id) value',false);
        $this->db->select('CONCAT(customer.customer_name,"(",upazilas.upazila_name,")") text',false);
        $this->db->join($this->config->item('table_bookings').' bookings','bookings.id = dvl.booking_id','INNER');
        $this->db->join($this->config->item('table_customers').' customer','customer.id = bookings.customer_id','INNER');
        $this->db->join($this->config->item('table_unions').' unions','unions.id = customer.union_id','INNER');
        $this->db->join($this->config->item('table_upazilas').' upazilas','upazilas.id = unions.upazila_id','INNER');
        $this->db->where('dvl.container_id',$container_id);
        $data['items']=$this->db->get()->result_array();
        $ajax['status']=true;
        $ajax['system_content'][]=array("id"=>"#booking_id","html"=>$this->load->view("dropdown_with_select",$data,true));
        $this->jsonReturn($ajax);
    }
    private function check_validation()
    {

        return true;
    }

}
