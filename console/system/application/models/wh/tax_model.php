<?php

class Tax_Model extends Main_Model {

    function Tax_Model() {
        // Call the Model constructor
        parent::Model();
        //
        if (isset($this->ci)) {
            $this->db = $this->ci->db;
        }
        //
        $this->table_name = 'wh_tax';
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
    
    function load($value) {
        $this->db->where('value', $value);
        $query = $this->db->get($this->table_name);
        $record = $query->row_array();
        return $record;
    }
    

}

?>