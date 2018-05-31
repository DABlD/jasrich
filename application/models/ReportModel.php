<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportModel extends CI_Model {

	public function getSalesReport($day, $way)
	{
		$this->db->select('SUM(`prod_price` * `qty`) as sale');
		$this->db->where('dawae', $way);
		$this->db->like('date_added', $day);
		return $this->db->get('orders')->row()->sale;
	}

	public function getCategorySale($category, $day)
	{
		$this->db->select('SUM(`qty`) as qt');
		$this->db->join('inventory', 'inventory.id = orders.prod_id');
		$this->db->like('orders.date_added', $day);
		$this->db->where('inventory.category', $category);
		return $this->db->get('orders')->row()->qt;
	}

	public function getExpiredItems($date)
	{
		return $this->db->where('deleted_at', null)->select('name, expiry_date')->where('expiry_date <=', $date)->get('inventory')->result();
	}

	public function getLowStocks()
	{
		return $this->db->select('name, stock')->where('stock <', 100)->where('deleted_at', null)->get('inventory')->result();
	}

	public function getItems()
	{
		return $this->db->select('id')->where('deleted_at', null)->get('inventory')->result();
	}

	public function getLastBought($id)
	{
		return $this->db->select('prod_name as name, date_added as last')->order_by('date_added', 'desc')->where('prod_id', $id)->get('orders')->row();
	}

	public function getItemName($id)
	{
		return $this->db->where('id', $id)->get('inventory')->row()->name;
	}
}

/* End of file ReportModel.php */
/* Location: ./application/models/ReportModel.php */