<?php
class Product_Model extends Main_Model {
	
	function Product_Model() {
		// Call the Model constructor
		parent::Model();
		//
		if (isset($this->ci)) { $this->db = $this->ci->db; }
        //
        $this->table_name = 'wh_product';
	}

    // load
    function load_all_count($id_tree) {
        if (isset($id_tree)) {
            // id_tree exists and we need return only counted number of dependent nodes
            $this->db->where('id_tree', $id_tree);
            $this->db->from($this->table_name.'_relations');
        } else {
            // count all products
            $this->db->from($this->table_name);
        }
        return $this->db->count_all_results();
    }
    function load_all($id_tree) {
        $this->limit_check();
        if (isset($id_tree)) {
            // id_tree exists and we need return only dependent nodes
            $this->db->where('id_tree', $id_tree);
            $this->db->order_by('position');
            $query = $this->db->get($this->table_name.'_relations');
            $result = $query->result();
            $record = array();
            foreach ($result as $item) {
                $values['tree'] = 'wh_product';
                $values['relations_id'] = $item->id;
                $values['relations_id_element'] = $item->id_element;
                $query2 = $this->db->query('select * from '.$this->table_name.' where id='.$item->id_element);
                foreach ($query2->result() as $item2) {
                    $values['id'] = $item2->id;
                    $values['title'] = $item2->title;
                    $values['header'] = $item2->header;
                    $values['date_added'] = $item2->date_added;
                    $values['active'] = $item2->active;
                }
                $record[] = $values;
            }
        } else {
            // load all products
            $query = $this->db->get($this->table_name);
            $record = $query->result_array();
        }
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

    // edit
    function edit($id) {
        $record = $_POST;
        $record['date_last_modified'] = date("Y-m-d H:i:s");
        $this->db->where('id', $id);
        $this->db->update($this->table_name, $record);
        return 1;

    }

    // delete
    function delete($id) {
        $this->db->where('id', $id);
        $query = $this->db->delete($this->table_name);
        return 1;
    }

    // active
    function active_set($id, $state) {
        $this->db->where('id', $id);
        $this->db->set('active', $state);
        $this->db->update($this->table_name);
        return '{"success": true}';
    }

}
?>