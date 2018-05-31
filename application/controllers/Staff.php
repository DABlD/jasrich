<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends CI_Controller 
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
		if($this->session->role != 'staff')
		{
			redirect('Login','refresh');
		}
	}

	public function index()
	{
		redirect('Staff/home');
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
			redirect('Admin/orders');
		}
		
		$this->session->unset_userdata('validation_error');

		$data['orders'] = $this->AdminModel->getNewOrders($this->uri->segment(3));
		$data['inventory'] = $this->AdminModel->getAllInventory();

		//PAGINATION
        $config['base_url'] = base_url() . 'Staff/orders';
        $config['total_rows'] = $this->AdminModel->countNewOrders();
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
		$this->load->view('staff/homepage', $data);
		$this->load->view('includes/footer');
	}
	
	public function inventory()
	{
		$this->session->unset_userdata('errors');
		$data['inventory'] = $this->AdminModel->getInventory($this->uri->segment(3));

		//PAGINATION
        
        $config['base_url'] = base_url() . 'Staff/inventory';
        $config['total_rows'] = $this->AdminModel->count();
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
		$this->load->view('admin/inventory', $data);
		$this->load->view('includes/footer');
	}

	public function additem()
	{
		$this->load->view('includes/header');
		$this->load->view('admin/add-item');
		$this->load->view('includes/footer');	
	}

	public function doAddItem()
	{
		$this->load->helper(array('form', 'url'));

		$this->form_validation->set_rules('name', 'name', 'required|is_unique[inventory.name]');
		$this->form_validation->set_rules('category', 'category', 'required');
		// $this->form_validation->set_rules('stock', 'stock', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('price', 'price', 'required|greater_than[0]');

		if ($this->form_validation->run() == FALSE)
	    {
	    	$this->session->set_flashdata('itemAddFail', '');

			$data['inventory'] = $this->AdminModel->getInventory($this->uri->segment(3));

			//PAGINATION
	        
	        $config['base_url'] = base_url() . 'Staff/inventory';
	        $config['total_rows'] = $this->AdminModel->count();
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
	        $this->session->set_flashdata('itemAddFail', validation_errors());

			$this->load->view('includes/header');
			$this->load->view('admin/products', $data);
			$this->load->view('includes/footer');
	    }
	    else
	    {
	    	$name = $this->input->post('name');
	    	// $stock = $this->input->post('stock');
	    	// if(!$this->input->post('stype'))
	    	// {
	    	// 	$stype = "PCS";
	    	// }
	    	// else
	    	// {
	    	// 	$stype = "BOX";
	    	// }
	    	$price = sprintf("%.2f", $this->input->post('price'));

	    	if(!$this->input->post('expiry'))
	    	{
	    		$expiry = "NO EXPIRY";
	    	}
	    	else
	    	{
	    		$expiry = $this->input->post('expiry') . ' 00:00:00';
	    	}

	    	$data = array(
	    		'name' => $name,
	    		'stock' => 0,
	    		'price' => $price,
	    		'stock_type' => 'PCS',
	    		'expiry_date' => $expiry,
		    		'date_added' => date('Y-m-d H:i:s'),
	    		'date_updated' => date('Y-m-d H:i:s')
	    	);

	        if($this->AdminModel->doAddItem($data) == 1)
	        {
	        	$this->session->set_flashdata('itemAddSuccess', 'Item Added Successfully !');
	        }
	        else
	        {
	        	$this->session->set_flashdata('itemAddFail', 'Item Add Failed !');
	        }
	        redirect('Products/','refresh');
	    }
	}

	public function doEditItem($id)
	{
		$this->load->helper(array('form', 'url'));

		$this->form_validation->set_rules('name', 'name', 'required');
		// $this->form_validation->set_rules('stock', 'stock', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('price', 'price', 'required|greater_than[0]');

		if ($this->form_validation->run() == FALSE)
	    {
	    	$this->session->set_flashdata('editerrors', $id);

			$data['inventory'] = $this->AdminModel->getInventory($this->uri->segment(4));

			//PAGINATION
	        
	        $config['base_url'] = base_url() . 'Staff/inventory';
	        $config['total_rows'] = $this->AdminModel->count();
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
	    	$this->load->view('admin/inventory', $data);
	    	$this->load->view('includes/footer');
	    }
	    else
	    {
	    	$name = $this->input->post('name');
	    	// $stock = $this->input->post('stock');
	    	$price = sprintf("%.2f", $this->input->post('price'));
	    	
	    	if(!$this->input->post('expiry'))
	    	{
	    		$expiry = "NO EXPIRY";
	    	}
	    	else
	    	{
	    		$expiry = $this->input->post('expiry');
	    	}
	    	// if(!$this->input->post('stype'))
	    	// {
	    	// 	$stype = "PCS";
	    	// }
	    	// else
	    	// {
	    	// 	$stype = "BOX";
	    	// }

	    	$data = array(
	    		'name' => $name,
	    		// 'stock' => $stock,
	    		'price' => $price,
	    		// 'stock_type' => $stype,
	    		'expiry_date' => $expiry,
	    		'date_updated' => date('Y-m-d H:i:s'),
	    	);

	        if($this->AdminModel->doEditItem($data, $id) == 1)
	        {
	        	$this->session->set_flashdata('itemEditSuccess', 'Item Edited Successfully !');
	        }
	        else
	        {
	        	$this->session->set_flashdata('itemEditFail', 'Item Edit Failed !');
	        }
	        redirect('Staff/inventory','refresh');
	    }	
	}

	public function searchItem()
	{
		$name = $this->input->post('search');

		if($name == "")
		{
			redirect('Staff/inventory','refresh');
		}
		else
		{
			$data['inventory'] = $this->AdminModel->searchItem($name);

			$this->load->view('includes/header');
			$this->load->view('admin/inventory', $data);
			$this->load->view('includes/footer');
		}
	}

	public function doDeleteItem($id)
	{
		if($this->AdminModel->doDeleteItem($id))
		{
			$this->session->set_flashdata('itemDeleteSuccess', 'Item Deleted Successfully !');
		}
		else
		{
			$this->session->set_flashdata('itemDeleteFail', 'Item Delete Failed !');
		}
		redirect('Staff/inventory','refresh');
	}

	public function orders()
	{
		$this->session->unset_userdata('validation_error');

		$data['orders'] = $this->AdminModel->getOrders($this->uri->segment(3));
		$data['inventory'] = $this->AdminModel->getAllInventory();

		//PAGINATION
        $config['base_url'] = base_url() . 'Staff/orders';
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
	        $config['base_url'] = base_url() . 'Staff/orders';
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
	        redirect('Staff/orders','refresh');
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

	public function auditTrail()
	{
		$this->load->view('includes/header');
		$this->load->view('admin/auditTrail');
		$this->load->view('includes/footer');
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
			redirect('Staff/orders','refresh');
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

	public function searchTransaction()
	{
		$name = $this->input->post('search');

		if($name == "")
		{
			redirect('Staff/transaction','refresh');
		}
		else
		{
			$data['orders'] = $this->AdminModel->getAllUnpaidOrders();
			$data['transactions'] = $this->AdminModel->searchTransaction($name);

			// print_r($data['transactions']);
			$this->load->view('includes/header');
			$this->load->view('admin/transactions', $data);
			$this->load->view('includes/footer');
		}
	}

	public function transaction()
	{
		$data['transactions'] = $this->AdminModel->getTransactions($this->uri->segment(3));
		$data['orders'] = $this->AdminModel->getAllUnpaidOrders();

		$config['base_url'] = base_url() . 'Staff/transaction';
        $config['total_rows'] = $this->AdminModel->countTransactions();
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
		$this->load->view('admin/transactions', $data);
		$this->load->view('includes/footer');
	}

	public function doAddTransaction()
	{
		$this->load->helper(array('form', 'url'));

		$this->form_validation->set_rules('order', 'Order', 'required');
		$this->form_validation->set_rules('amount', 'amount', 'required|greater_than[0]|less_than_equal_to[999999]');

		$config['base_url'] = base_url() . 'Staff/transaction';
        $config['total_rows'] = $this->AdminModel->countTransactions();
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

		if ($this->form_validation->run() == FALSE)
	    {
	    	$this->session->set_flashdata('validation_error', "Transaction Error !");
		}
		else
		{
			$data2 = array(
				'order_id' => $this->input->post('order'),
				'amount_paid' => $this->input->post('amount'),
		    		'date' => date('Y-m-d H:i:s'),
			);

	        if($this->AdminModel->doAddTransaction($data2) == 1)
	        {
	    		$this->session->set_flashdata('transactionAddSuccess', "Transaction Record Success !");
	    	}
	    	else
	    	{
	    		$this->session->set_flashdata('transactionAddFail', "Transaction Record Failed !");
	    	}

	    	redirect('Staff/transaction','refresh');
		}

		$data['transactions'] = $this->AdminModel->getTransactions($this->uri->segment(3));
		$data['orders'] = $this->AdminModel->getAllUnpaidOrders();

		$this->load->view('includes/header');
		$this->load->view('admin/transactions', $data);
		$this->load->view('includes/footer');
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

	public function for_delivery()
	{
		$id = $this->input->post('id');
		echo $this->AdminModel->deliverOrder($id);
	}



	public function getCategories()
	{
		$result = array();
		foreach ($this->AdminModel->getCategories() as $res) {
			array_push($result, $res->category);
		}
		echo json_encode($result);
	}

	public function getOrderPerCategory()
	{
		$months = json_decode($this->input->post('months'));
		$category = $this->input->post('category');

		$result = array();
		foreach ($months as $month) {
			$temp = $this->AdminModel->getOrderPerCategory($category, date('Y-m', strtotime(date('Y-') . $month)));
			// array_push($result, json_encode($temp));
			array_push($result, $temp);
		}

		print_r(json_encode($result));
		// print_r($result);
	}

	public function loadYears()
	{
		echo json_encode($this->AdminModel->loadYears());
	}

	public function getSalesThisWeek()
	{
		$start = $this->input->post('start');
		$end = $this->input->post('end');

		$ajaxReturn = array();
		$result = $this->AdminModel->getSalesThisWeek($start, $end);
		foreach ($result as $res) 
		{
			array_push($ajaxReturn, $res['date']);
		}

		echo json_encode($ajaxReturn);
	}

	public function getSalesPerDay()
	{
		$days = $this->input->post('days');

		$orders = array();
		foreach ($days as $day) 
		{
			array_push($orders, $this->AdminModel->getSalesPerDay($day));
		}

		echo json_encode($orders);
	}
}