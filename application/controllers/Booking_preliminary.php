<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_preliminary extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Booking_preliminary');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='booking_preliminary';
        $this->load->model("booking_preliminary_model");
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
            $data['title']="Preliminary Booking";
            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("booking_preliminary/search",$data,true));
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
    public function system_edit($id=0)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            if(($this->input->post('customer_id')))
            {
                $customer_id=$this->input->post('customer_id');
                $year=$this->input->post('year');
                $data_booking_info=Query_helper::get_info($this->config->item('table_bookings'),'*',array('customer_id ='.$customer_id,'year ='.$year),1);
                if(isset($data_booking_info['id']))
                {
                    $id=$data_booking_info['id'];
                }

            }
            /*$data['variety']=Query_helper::get_info($this->config->item('table_varieties'),'*',array('id ='.$variety_id),1);

            $data['title']="Change Variety price(".$data['variety']['variety_name'].')';*/
            if($id>0)
            {
                $data['title']='Edit Booking('.$id.')';
                $data['booking']=Query_helper::get_info($this->config->item('table_bookings'),'*',array('id ='.$id),1);
                $data['booked_varieties']=array();
            }
            else
            {
                $data['title']='New Booking';
                $data['booking']['id']=0;
                $data['booking']['customer_id']=$this->input->post('customer_id');
                $data['booking']['year']=$this->input->post('year');
                $data['booking']['remarks']='';
                $data['booking']['status']=$this->config->item('system_status_active');

                $data['booked_varieties']=array();

            }
            $ajax['status']=true;


            $data['varieties']=$this->booking_preliminary_model->get_all_varieties();

            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("booking_preliminary/edit",$data,true));

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
            /*echo '<PRE>';
            print_r($this->input->post('booking'));
            print_r($this->input->post('booked_varieties'));
            echo '</PRE>';*/
            $data = $this->input->post('booking');
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_bookings'),$data,array("id = ".$id));
                $this->db->trans_complete();   //DB Transaction Handle END

                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_edit($id);
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
                $booking_id=Query_helper::add($this->config->item('table_bookings'),$data);
                $this->db->trans_complete();   //DB Transaction Handle END
                if ($this->db->trans_status() === TRUE)
                {
                    $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                    $this->system_edit($booking_id);
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
        $booked_varieties=$this->input->post('booked_varieties');
        if(sizeof($booked_varieties)>0)
        {
            $variety_ids=array();
            foreach($booked_varieties as $variety)
            {
                if(!is_numeric($variety['quantity']))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_QUANTITY_MISSING");
                    return false;
                }
                if(!(($variety['quantity'])>0))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_QUANTITY_INVALID");
                    return false;
                }
                if(!(($variety['id'])>0))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_VARIETY_MISSING");
                    return false;
                }
                $variety_ids[]=$variety['id'];
                if(sizeof($variety_ids)!= sizeof(array_unique($variety_ids)))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_DUPLICATE_VARIETY");
                    return false;
                }
            }
        }
        else
        {
            $this->message=$this->lang->line("MSG_REQUIRED_BOOKING");
            return false;

        }
        return true;
    }
}
