<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AdminModel');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Manila');
		if($this->session->role != 'client')
		{
			redirect('Login','refresh');
		}
	}

	public function index()
	{
		redirect('Client/home');
	}

	public function logout()
	{
		$this->AdminModel->logout();
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('role');
		$this->session->unset_userdata('id');
		$this->session->set_flashdata('loginStatus', 'Successfully Logout !');
		redirect('Login');
	}

	public function home()
	{
		if($this->session->role == 'client')
		{
			redirect('Client/orders');
		}

		$this->load->view('includes/header');
		$this->load->view('admin/homepage');
		$this->load->view('includes/footer');
	}

	public function orders()
	{
		$this->session->unset_userdata('validation_error');

		$data['orders'] = $this->AdminModel->getOrders($this->uri->segment(3));
		$data['inventory'] = $this->AdminModel->getAllInventory();

		//PAGINATION
        $config['base_url'] = base_url() . 'Client/orders';
        if($this->session->role == 'admin')
        {
            $config['total_rows'] = $this->AdminModel->countOrders();
        }
        else
        {
            $config['total_rows'] = $this->AdminModel->countClientOrders($this->session->id);
        }
        $config['per_page'] = 8;

        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close']= '</ul></nav>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';

        $config['first_tag_close'] = '</li>';
        $config['first_url']='';

        $config['last_link']='Last';
        $config['last_tag_open']='<li>';
        
        $config['last_tag_close']='</li>';
        $config['next_link']='&raquo;';
        
        $config['next_tag_open']='<li>';
        $config['next_tag_close']='</li>';
        
        $config['prev_link'] ='&laquo;';
        $config['prev_tag_open']='<li>';
        
        $config['prev_tag_close']='</li>';
        $config['cur_tag_open']='<li class="active"><a href="#">';
        
        $config['cur_tag_close']='</a></li>';
        $config['num_tag_open']='<li>';
        
        $config['num_tag_close']='</li>';

        $this->pagination->initialize($config);
		
		$this->load->view('includes/header');
		$this->load->view('admin/orders', $data);
		$this->load->view('includes/footer');
	}

	public function doAddOrder()
	{
		$this->load->helper(array('form', 'url'));

		$this->form_validation->set_rules('name', 'names', 'required');
		$this->form_validation->set_rules('qty', 'quantity', 'required|is_natural_no_zero|less_than_equal_to[999999]');
		$this->form_validation->set_rules('buyer', 'buyers', 'required');
		$this->form_validation->set_rules('contact', 'contact', 'required|numeric');

		if ($this->form_validation->run() == FALSE)
	    {
	    	$data['orders'] = $this->AdminModel->getOrders($this->uri->segment(3));
	    	$data['inventory'] = $this->AdminModel->getAllInventory();
	    	$data['name'] = $this->AdminModel->getName($this->input->post('name')); 

			//PAGINATION
	        $config['base_url'] = base_url() . 'Client/orders';
	        $config['total_rows'] = $this->AdminModel->countOrders();
	        $config['per_page'] = 8;

	        $config['full_tag_open'] = '<nav><ul class="pagination">';
	        $config['full_tag_close']= '</ul></nav>';

	        $config['first_link'] = 'First';
	        $config['first_tag_open'] = '<li>';

	        $config['first_tag_close'] = '</li>';
	        $config['first_url']='';

	        $config['last_link']='Last';
	        $config['last_tag_open']='<li>';
	        
	        $config['last_tag_close']='</li>';
	        $config['next_link']='&raquo;';
	        
	        $config['next_tag_open']='<li>';
	        $config['next_tag_close']='</li>';
	        
	        $config['prev_link'] ='&laquo;';
	        $config['prev_tag_open']='<li>';
	        
	        $config['prev_tag_close']='</li>';
	        $config['cur_tag_open']='<li class="active"><a href="#">';
	        
	        $config['cur_tag_close']='</a></li>';
	        $config['num_tag_open']='<li>';
	        
	        $config['num_tag_close']='</li>';

	        $this->pagination->initialize($config);

	    	$this->session->set_flashdata('validation_error', "Order Error !!!");
	    	
	    	$this->load->view('includes/header');
	    	$this->load->view('admin/orders', $data);
	    	$this->load->view('includes/footer');
	    }
	    else
	    {
	    	$id = $this->input->post('name');
			$data = $this->AdminModel->getItemDetails($id);

	    	if($this->session->role != 'client')
	    	{
				$data = array(
		    		'prod_id' => $id,
		    		'prod_name' => $data[0]->name,
		    		'prod_price' => $data[0]->price,
		    		'qty' => $this->input->post('qty'),
		    		'dawae' => 'OUT',
		    		'buyer_name' => $this->input->post('buyer'),
		    		'contact' => $this->input->post('contact'),
		    		'status' => 'Unpaid',
		    		'date_added' => date('Y-m-d H:i:s'),
	    		'date_updated' => date('Y-m-d H:i:s')
		    	);
	    	}
	    	else
	    	{
				$data = array(
		    		'prod_id' => $id,
		    		'prod_name' => $data[0]->name,
		    		'prod_price' => $data[0]->price,
		    		'qty' => $this->input->post('qty'),
		    		'dawae' => 'OUT',
		    		'buyer_name' => $this->input->post('buyer'),
		    		'contact' => $this->input->post('contact'),
		    		'client_id' => $this->session->id,
		    		'date_added' => date('Y-m-d H:i:s'),
	    		'date_updated' => date('Y-m-d H:i:s')
		    	);	
	    	}
	    	
	        if($this->AdminModel->doAddOrder($data) == 1)
	        {
	        	$this->AdminModel->updateStocks($id, $this->input->post('qty'), 'out');
	        	$this->session->set_flashdata('orderAddSuccess', 'Order Added Successfully !');
	        }
	        else
	        {
	        	$this->session->set_flashdata('orderAddFail', 'Order Add Failed !');
	        }
	        redirect('Client/orders','refresh');
	    }
	}

	public function statusChange()
	{
		$id = $this->input->post('id');

		echo $this->AdminModel->confirmOrder($id);
	}

	public function statusComplete()
	{
		$id = $this->input->post('id');

		echo $this->AdminModel->completeOrder($id);
	}

	public function getItemPrice()
	{
		$price = $this->AdminModel->getItemPrice($this->input->post('id'));
		echo json_encode($price);
	}

	public function searchOrder()
	{
		$name = $this->input->post('search');

		if($name == "")
		{
			redirect('Client/orders','refresh');
		}
		else
		{
			$data['inventory'] = $this->AdminModel->getAllInventory();
			$data['orders'] = $this->AdminModel->searchOrder($name);

			$this->load->view('includes/header');
			$this->load->view('admin/orders', $data);
			$this->load->view('includes/footer');
		}
	}

	public function transaction_status($id)
	{
		$data['transaction'] = $this->AdminModel->getTransactionStatus($id);
		$data['id'] = $id;

		$this->load->view('includes/header');
		$this->load->view('admin/transaction_status', $data);
		$this->load->view('includes/footer');
	}

	public function getBalance()
	{
		$order = $this->AdminModel->getBalance($this->input->post('id'));
		$totalBalance = $order['prod_price'] * $order['qty'];

		$transactions = $this->AdminModel->getPayments($this->input->post('id'));

		echo round($totalBalance - $transactions['amount_paid'], 2);
	}

	public function statusDecline()
	{
		$id = $this->input->post('id');
		$reason = $this->input->post('reason');
		$status = $this->input->post('status');
		if($status == 'decline')
		{
			$status = 'Declined';
		}
		elseif($status == 'cancel')
		{
			$status == 'Cancelled';
		}
		else
		{
			$status = 'On Hold';
		}

		echo $this->AdminModel->declineOrder($id, $reason, $status);
	}
}