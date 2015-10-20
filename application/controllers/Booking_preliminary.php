<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_preliminary extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    private $selected_variety_ids=array();
    //this variable is set before save with selected variety ids
    //used to get price
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
            $time=time();
            if($id>0)
            {
                $data['title']='Edit Preliminary Booking( Booking id= '.$id.')';
                $data['booking']=Query_helper::get_info($this->config->item('table_bookings'),'*',array('id ='.$id),1);
                if($data['booking']['booking_status']!=$this->config->item('booking_status_preliminary'))
                {
                    $ajax['status']=false;
                    $ajax['system_message']=$this->lang->line("MSG_EDIT_PRIMARY");
                    $this->jsonReturn($ajax);
                }
                $data['booked_varieties']=Query_helper::get_info($this->config->item('table_preliminary_varieties'),array('date','variety_id','quantity'),array('booking_id ='.$id,'revision =1'));
                $data['payment']=Query_helper::get_info($this->config->item('table_payments'),'*',array('booking_id ='.$id,'booking_status ="'.$this->config->item('booking_status_preliminary').'"'),1);
            }
            else
            {

                $data['title']='New Preliminary Booking';
                $data['booking']['id']=0;
                $data['booking']['customer_id']=$this->input->post('customer_id');
                $data['booking']['year']=$this->input->post('year');
                $data['booking']['preliminary_remarks']='';
                $data['booking']['status']=$this->config->item('system_status_active');
                $data['booking']['preliminary_booking_date']=$time;

                $data['payment']['amount']='';
                $data['payment']['payment_method']='';
                $data['payment']['payment_number']='';
                $data['payment']['bank_name']='';
                $data['payment']['branch_name']='';
                $data['payment']['payment_date']=$time;
                $data['payment']['remarks']='';

                $data['booked_varieties']=array(array('date'=>$time,'variety_id'=>'','quantity'=>''));

            }
            $ajax['status']=true;


            $data['varieties']=System_helper::get_all_varieties_for_dropdown();

            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("booking_preliminary/edit",$data,true));

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
            $data['preliminary_booking_date']=System_helper::get_time($data['preliminary_booking_date']);
            $time=time();
            if($id>0)
            {
                $data['modified_by']=$user->user_id;
                $data['modification_date']=$time;
                $this->db->trans_start();  //DB Transaction Handle START
                Query_helper::update($this->config->item('table_bookings'),$data,array("id = ".$id));

                $payment_info=$this->input->post('payment');
                $payment_info['modified_by']=$user->user_id;
                $payment_info['modification_date']=$time;
                $payment_info['payment_date'] = System_helper::get_time($payment_info['payment_date']);
                Query_helper::update($this->config->item('table_payments'),$payment_info,array("booking_id = ".$id,'booking_status ="'.$this->config->item('booking_status_preliminary').'"'));

                $this->db->where('booking_id',$id);
                $this->db->set('revision', 'revision+1', FALSE);
                $this->db->update($this->config->item('table_preliminary_varieties'));
                $this->insert_booking_varieties($id);
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
                $data['booking_status']=$this->config->item('booking_status_preliminary');
                $data['created_by'] = $user->user_id;
                $data['creation_date'] = $time;
                $this->db->trans_start();  //DB Transaction Handle START
                $booking_id=Query_helper::add($this->config->item('table_bookings'),$data);
                $this->insert_booking_varieties($booking_id);
                $payment_info=$this->input->post('payment');
                $payment_info['created_by'] = $user->user_id;
                $payment_info['creation_date'] = $time;
                $payment_info['booking_id'] = $booking_id;
                $payment_info['payment_date'] = System_helper::get_time($payment_info['payment_date']);
                $payment_info['booking_status'] = $this->config->item('booking_status_preliminary');

                Query_helper::add($this->config->item('table_payments'),$payment_info);
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
    private function insert_booking_varieties($booking_id)
    {
        $booked_varieties=$this->input->post('booked_varieties');
        $time=time();
        $user=User_helper::get_user();
        foreach($booked_varieties as $variety)
        {
            $data=array();
            $data['booking_id']=$booking_id;
            $data['variety_id']=$variety['id'];
            $data['quantity']=$variety['quantity'];
            $data['date']=System_helper::get_time($variety['date']);
            $data['revision']=1;
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;
            Query_helper::add($this->config->item('table_preliminary_varieties'),$data);
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

            }
            if(sizeof($variety_ids)!= sizeof(array_unique($variety_ids)))
            {
                $this->message=$this->lang->line("MSG_BOOKING_DUPLICATE_VARIETY");
                return false;
            }
            $this->selected_variety_ids=$variety_ids;
        }
        else
        {
            $this->message=$this->lang->line("MSG_REQUIRED_BOOKING");
            return false;

        }
        $payment=$this->input->post('payment');
        if(!is_numeric($payment['amount']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_MISSING");
            return false;
        }
        /*if(!(($payment['amount'])>0))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_INVALID");
            return false;
        }*/
        if(!($payment['payment_method']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_METHOD_INVALID");
            return false;
        }
        /*if(!($payment['payment_number']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_NUMBER_INVALID");
            return false;
        }*/
        /*if(!($payment['bank_name']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_BANK_NAME_INVALID");
            return false;
        }*/

        return true;
    }
}
