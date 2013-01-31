<?php
class Product_Dict_Vendor_Model extends Main_Model {
	
	function Product_Dict_Vendor_Model() {
		// Call the Model constructor
		parent::Model();
		//
		if (isset($this->ci)) { $this->db = $this->ci->db; }
        //
        $this->table_name = 'wh_product_dict_vendor';
	}

    // load
    function load_all_count() {
        $this->db->from($this->table_name);
        return $this->db->count_all_results();
    }
    function load_all() {
        $this->limit_check();
        $query = $this->db->get($this->table_name);
        $record = $query->result_array();
        return $record;
    }
    function load($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table_name);
        $record = $query->row_array();
        return $record;
    }

    // add
    function add() {
        $this->db->insert($this->table_name, $_POST);
        return 1;
    }

    // delete
    function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->table_name);
        return 1;
    }

}
?>