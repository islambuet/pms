<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Allocation_consignment extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Allocation_consignment');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='allocation_consignment';
        $this->load->model("allocation_consignment_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="list")
        {
            $this->system_list();
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
            $this->system_search();
        }
    }

    public function system_search()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Assign Quantity";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("allocation_consignment/search",$data,true));
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
    public function system_list()
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $year=$this->input->post('year');
            $data['title']='Allocation list';
            $data['bookings']=$this->allocation_consignment_model->get_bookings($year);
            if(!(sizeof($data['bookings'])>0))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_NO_PERMANENT_BOOKING_FOUND");
                $this->jsonReturn($ajax);
            }
            $data['consignments']=$this->allocation_consignment_model->get_consignments($year);
            $data['allocated_varieties']=$this->allocation_consignment_model->get_all_allocated_varieties($year);
            if(!(sizeof($data['consignments'])>0))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_NO_CONSIGNMENT_FOUND");
                $this->jsonReturn($ajax);
            }
            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("allocation_consignment/list",$data,true));

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
    public function system_edit()
    {
        $year=$this->input->post('year');
        $booking_id=$this->input->post('booking_id');
        $consignment_id=$this->input->post('consignment_id');
        $data['consignment_info']=$this->allocation_consignment_model->get_consignment_info($consignment_id);
        $data['booking_info']=$this->allocation_consignment_model->get_booking_info($booking_id);
        $data['allocated_varieties']=$this->allocation_consignment_model->get_allocated_varieties($year,$booking_id,$consignment_id);
        $data['year']=$year;
        $data['consignment_id']=$consignment_id;
        $data['booking_id']=$booking_id;
        $ajax['system_content'][]=array("id"=>"#edit_container","html"=>$this->load->view("allocation_consignment/edit",$data,true));

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
            $year=$this->input->post('year');
            $booking_id=$this->input->post('booking_id');
            $consignment_id=$this->input->post('consignment_id');
            $allocated_varieties=$this->input->post('allocated_varieties');

            $time=time();
            $this->db->trans_start();  //DB Transaction Handle START

            $this->db->where('year',$year);
            $this->db->where('booking_id',$booking_id);
            $this->db->where('consignment_id',$consignment_id);
            $this->db->set('revision', 'revision+1', FALSE);
            $this->db->update($this->config->item('table_allocation_varieties'));
            foreach($allocated_varieties as $data)
            {
                $data['date']=System_helper::get_time($data['date']);
                $data['revision']=1;
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                $data['year'] = $year;
                $data['booking_id'] = $booking_id;
                $data['consignment_id'] = $consignment_id;
                Query_helper::add($this->config->item('table_allocation_varieties'),$data);
            }

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
        $allocated_varieties=$this->input->post('allocated_varieties');

        if(!(sizeof($allocated_varieties)>0))
        {
            $this->message="invalid input";
            return false;
        }


        return true;
    }

}
