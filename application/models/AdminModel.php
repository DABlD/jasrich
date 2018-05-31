<?php

class AdminModel extends CI_Model {
	public function getInventory($offset)
	{
		// return $this->db->where('deleted_at', null)->order_by('date_added', 'DESC')->get('inventory', '8', $offset)->result();
		return $this->db->where('deleted_at', null)->order_by('date_added', 'DESC')->get('inventory')->result();
	}
	
	public function getDeletedItems($offset)
	{
		return $this->db->where('deleted_at !=', null)->order_by('date_added', 'DESC')->get('inventory', '8', $offset)->result();
	}

	public function getAllInventory()
	{
		return $this->db->where('deleted_at', null)->order_by('date_added', 'DESC')->get('inventory')->result();
	}

	public function getSuppliers()
	{
		return $this->db->order_by('date_added', 'DESC')->get('suppliers')->result();
	}

	public function getOrders($offset)
	{
		if($this->session->role != 'client')
		{
			return $this->db->where('dawae', 'OUT')->order_by('date_added', 'DESC')->get('orders', '8', $offset)->result();
		}
		else
		{
			return $this->db->where('dawae', 'OUT')->order_by('date_added', 'DESC')->where('client_id', $this->session->id)->get('orders', '8', $offset)->result();
		}
	}

	public function getOutOrders($offset)
	{
		if($this->session->role != 'client')
		{
			// return $this->db->where('dawae', 'OUT')->order_by('date_added', 'DESC')->get('orders', '8', $offset)->result();
			return $this->db->where('dawae', 'OUT')->order_by('date_added', 'DESC')->get('orders')->result();
		}
		else
		{
			// return $this->db->where('dawae', 'OUT')->order_by('date_added', 'DESC')->where('client_id', $this->session->id)->get('orders', '8', $offset)->result();
			return $this->db->where('dawae', 'OUT')->order_by('date_added', 'DESC')->where('client_id', $this->session->id)->get('orders')->result();
		}
	}

	function getSupplierContact($id)
	{
		return $this->db->where('id', $id)->get('suppliers')->row()->contact;
	}

	function getSupplierName($id)
	{
		return $this->db->where('id', $id)->get('suppliers')->row()->name;
	}

	public function getSuppliersSelect()
	{
		return $this->db->select('id, name as text')->where('deleted_at',  null)->get('suppliers')->result();
	}

	public function getNewOrders($offset)
	{
		return $this->db->where('dawae', 'OUT')->where('status', 'For Confirmation')->order_by('date_added', 'DESC')->get('orders', '8', $offset)->result();
	}

	public function getAllUnpaidOrders()
	{
		return $this->db->where('status', 'Unpaid')->order_by('date_added', 'DESC')->get('orders')->result();
	}
	public function doAddItem($data)
	{
		$data2 = array(
		        'username' => $this->session->username,
		        'action' => 'Added an Item',
		);

		$this->db->insert('audit_trail', $data2);

		return $this->db->insert('inventory', $data);
	}

	public function confirmOrder($id)
	{
		$data = array(
		        'username' => $this->session->username,
		        'action' => 'Confirmed Order #' . $id,
		);

		$this->db->insert('audit_trail', $data);
		return $this->db->where('id', $id)->set('status', 'Unpaid')->update('orders');
	}

	public function declineOrder($id, $reason, $status)
	{
		$data = array(
		        'username' => $this->session->username,
		        'action' => $status . ' Order #' . $id,
		);

		$this->db->insert('audit_trail', $data);
		return $this->db->where('id', $id)->set('status', $status)->set('reason', $reason)->update('orders');
	}

	public function completeOrder($id)
	{
		$data = array(
		        'username' => $this->session->username,
		        'action' => 'Completed Order #' . $id,
		);

		$this->db->insert('audit_trail', $data);
		return $this->db->where('id', $id)->set('status', 'Completed')->update('orders');
	}
	
	public function restore($id)
	{
	    return $this->db->where('id', $id)->set('deleted_at', null)->update('inventory');
	}

	public function count()
	{
		return $this->db->where('deleted_at', null)->count_all_results('inventory');
	}

	public function countDeletedItems()
	{
		return $this->db->where('deleted_at != ', null)->count_all_results('inventory');
	}

	public function countOrders()
	{
		return $this->db->where('dawae', 'OUT')->count_all_results('orders');
	}

	public function countClientOrders($id)
	{
		return $this->db->where('dawae', 'OUT')->where('client_id', $id)->count_all_results('orders');
	}

	public function countNewOrders()
	{
		return $this->db->where('dawae', 'OUT')->where('status', 'For Confirmation')->count_all_results('orders');
	}

	public function countUsers()
	{
// 		return $this->db->where('deleted_at', null)->where('role !=', 'admin')->count_all_results('users');

		return $this->db->where('role !=', 'admin')->count_all_results('users');
	}

	public function countTransactions()
	{
		return $this->db->count_all_results('transactions');
	}

	public function doEditItem($data, $id)
	{
		$data2 = array(
		        'username' => $this->session->username,
		        'action' => 'Edited Item #' . $id,
		);

		$this->db->insert('audit_trail', $data2);

		$this->db->set('name', $data['name']);
		// $this->db->set('stock', $data['stock']);
		$this->db->set('price', $data['price']);
		// $this->db->set('stock_type', $data['stock_type']);
		$this->db->set('expiry_date', $data['expiry_date']);
		$this->db->set('date_updated', $data['date_updated']);
		$this->db->where('id', $id);

		return $this->db->update('inventory');
	}

	public function searchItem($name)
	{
		$this->db->select('*');
		$this->db->where('deleted_at', null);
		$this->db->like('name',$name);
		$this->db->or_like('category',$name);
// 		$this->db->or_like(array('name' => $name, 'category' => $name));
		return $this->db->get("inventory")->result();

		// print_r($this->db->select('*')->like('name', 'ultra')->get()->result());
	}

	public function searchOrder($name)
	{
		$this->db->select('*');
		$this->db->where('dawae', 'OUT');
        $this->db->like('prod_name', $name, 'both');
		$this->db->or_where('dawae', 'OUT');
        $this->db->like('date_added', $name, 'both');
		$this->db->or_where('dawae', 'OUT');
        $this->db->like('buyer_name', $name, 'both');
// 		$this->db->where('dawae', 'OUT');
// 		$this->db->or_like(array('prod_name' => $name, 'status' => $name, 'date_added' => $name, 'buyer_name' => $name));
// 		$this->db->like('prod_name',$name);
// 		$this->db->like('status',$name);
		return $this->db->get("orders")->result();
	}

	public function doDeleteItem($id)
	{
		$data = array(
		        'username' => $this->session->username,
		        'action' => 'Deleted Item #' . $id,
		);

		$this->db->insert('audit_trail', $data);
		return $this->db->where('id', $id)->set('deleted_at', date('Y-m-d H:i:S'))->update('inventory');
	}

	public function getItemDetails($id)
	{
		return $this->db->select('name,price')->from('inventory')->where('id', $id)->get()->result();
	}

	public function getName($id)
	{
		return $this->db->select('name')->from('inventory')->where('id', $id)->get()->row_array();
	}

	public function doAddOrder($data)
	{
		$data2 = array(
		        'username' => $this->session->username,
		        'action' => 'Added an Order',
		);

		$this->db->insert('audit_trail', $data2);
		return $this->db->insert('orders', $data);
	}

	public function getItemPrice($id)
	{
		return $this->db->select('price, stock')->from('inventory')->where('id', $id)->get()->row_array();
	}

	public function getTransactions($offset)
	{
		return $this->db->select('*')
				->select('transactions.id as t_id')
				// ->from('transactions')
				->join('orders as o', 'o.id = transactions.order_id')
				->order_by('date', 'DESC')
				->get('transactions','8', $offset)->result();
	}
	
	public function userRestore($id)
	{
	    return $this->db->where('id', $id)->set('deleted_at', null)->update('users');
	}
	
	public function userDisable($id)
	{
	    return $this->db->where('id', $id)->set('deleted_at', date('Y-m-d H:i:s'))->update('users');
	}
	
	public function supplierRestore($id)
	{
	    return $this->db->where('id', $id)->set('deleted_at', null)->update('suppliers');
	}
	
	public function supplierDisable($id)
	{
	    return $this->db->where('id', $id)->set('deleted_at', date('Y-m-d H:i:s'))->update('suppliers');
	}

	public function getUsers($offset)
	{
// 		return $this->db->where('deleted_at', null)->where('role !=', 'admin')->get('users')->result();

		return $this->db->where('role !=', 'admin')->get('users')->result();
	}

	public function doAddTransaction($data)
	{
		$data2 = array(
		        'username' => $this->session->username,
		        'action' => 'Added a Transaction',
		);

		$this->db->insert('audit_trail', $data2);
		return $this->db->insert('transactions', $data);
	}

	public function addUser($data)
	{
		$data2 = array(
		        'username' => $this->session->username,
		        'action' => 'Added a user',
		);

		$this->db->insert('audit_trail', $data2);
		return $this->db->insert('users', $data);
	}

	public function addSupplier($data)
	{
		$data2 = array(
		        'username' => $this->session->username,
		        'action' => 'Added a supplier',
		);

		$this->db->insert('audit_trail', $data2);
		return $this->db->insert('suppliers', $data);
	}

	public function searchTransaction($name)
	{
		return $this->db->select('*')
				->select('transactions.id as t_id')
				->or_like(array('o.prod_name' => $name, 'o.buyer_name' => $name, 'date' => $name))
				->join('orders as o', 'o.id = transactions.order_id')
				->get('transactions')->result();
	}

	public function getTransactionStatus($id)
	{
		return $this->db->select('*')
				->select('transactions.id as t_id')
				->where('order_id', $id)
				->join('orders as o', 'o.id = transactions.order_id')
				->get('transactions')->result();
	}

	public function getBalance($id)
	{
		return $this->db->select('prod_price, qty')
				->where('id', $id)
				->get('orders')
				->row_array();
	}

	public function getPayments($id)
	{
		return $this->db->select_sum('amount_paid')
				->where('order_id', $id)
				->get('transactions')->row_array();
	}

	public function deliverOrder($id)
	{
		$data = array(
		        'username' => 'System',
		        'action' => 'Set the order status of #' . $id . ' to "For Delivery"',
		);

		$this->db->insert('audit_trail', $data);
		return $this->db->set("status", "For Delivery")->where("id", $id)->update("orders");
	}

	public function updateStocks($id, $qty, $dawae)
	{
		$data = array(
		        'username' => $this->session->username,
		        'action' => 'Updated the stocks of item #' . $id,
		);

		$this->db->insert('audit_trail', $data);
		if($dawae == 'out')
		{
			return $this->db->set("stock", "stock - '$qty'", FALSE)->where('id', $id)->update("inventory");
		}
		else
		{
			return $this->db->set("stock", "stock + '$qty'", FALSE)->where('id', $id)->update("inventory");
		}
	}

	public function getCategories()
	{
		return $this->db->select('category')->distinct()->get('inventory')->result();
	}

	public function getCategorySales($start, $end, $category)
	{
		return $this->db->join('inventory', 'inventory.id = orders.prod_id')
						->where('orders.date_added >=', $start)
						->where('orders.date_added <=', $end)
						->where('orders.dawae', 'OUT')
						->where('inventory.category', $category)
						->where('inventory.deleted_at', null)
						->select_max('orders.qty')
						->select('orders.prod_name') 
						// ->select('inventory.category')
						->get('orders')->result();
	}

	public function getQ()
	{
		return $this->db->select('q')->from('data')->get()->result();  
	}

	public function getW()
	{
		return $this->db->select('w')->from('data')->get()->result();  
	}

	public function getOrderPerCategory($category, $date)
	{
		// return $this->db->like('date_added', $date)->count_all_results('inventory');

		return $this->db->join('orders', 'orders.prod_id = inventory.id')
						->where('inventory.category', $category)
						->like('orders.date_added', $date)
						->count_all_results('inventory');
	}

	public function seedChart()
	{
		for ($i=0; $i < 10; $i++) 
		{ 
			$id = rand(25, 92);
			$item = $this->db->select('name, price')->where('id', $id)->get('inventory')->result();

            $position = array('Omron', 'Zenith', 'Hess');
            // $position = array('Belo', 'Makati Medical', "Saint Luke's");
            
			$data = array(
				'prod_id' => $id,
				'client_id' => 3,
				'prod_name' => $item[0]->name,
				'prod_price' => $item[0]->price,
				'qty' => rand(5,10),
				// 'dawae' => rand(0, 1) ? 'IN' : 'OUT',
				'dawae' => 'IN',
				'buyer_name' => $position[rand(0,2)],
				'contact' => 'test@email.com',
				'status' => 'For Confirmation',
				'date_added' => '2018-' . '01' . '-' . rand(3,11) . ' ' . rand(0,23) . ':' . rand(0,59) . ':' . rand(0,59)
			);

			echo $this->db->insert('orders', $data);
		}
	}

	public function loadYears()
	{
		return $this->db->select('year(date_added) as year')->distinct()->get('orders')->result();	
	}

	public function getSalesThisWeek($start, $end)
	{
		return $this->db->select('date(date_added) as date')
						->distinct()
						->where('date_added >=', $start)
						->where('date_added <=', $end)
						->get('orders')->result_array();
	}

	public function getSalesPerDay($day)
	{
		return $this->db->select('prod_price, qty, date(date_added) as date')->like('date_added', $day)->get('orders')->result();
	}

	public function logout()
	{
		$data = array(
		        'username' => $this->session->username,
		        'action' => 'Logged Out',
		);

		$this->db->insert('audit_trail', $data);
	}

	function getSalesAnalysisReport($start, $end)
	{
		return $this->db->where('dawae', 'OUT')
						->where('date_added >=', $start)
						->where('date_added <=', $end)
						->select('prod_price, qty')
						->get('orders')
						->result();
	}

	function getExpenseAnalysisReport($start, $end)
	{
		return $this->db->where('dawae', 'IN')
						->where('date_added >=', $start)
						->where('date_added <=', $end)
						->select('prod_price, qty')
						->get('orders')
						->result();
	}
}