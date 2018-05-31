<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProductModel extends CI_Model {
	function getCategories()
	{
		return $this->db->select('category')->distinct()->get('inventory')->result();
	}
}

/* End of file ProductModel.php */
/* Location: ./application/models/ProductModel.php */