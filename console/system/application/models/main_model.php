<?php
class Main_Model extends Model {
		
	function Main_Model() {
		// Call the Model constructor
		parent::Model();
		//
		if (isset($this->ci)) { $this->db = $this->ci->db; }
	}

    //
    // active set
    //
    function active_set($id, $state) {
        $this->db->where('id', $id);
        $this->db->set('active', $state);
        $this->db->update($this->config_db_table_user);
        return '{"success": true}';
    }

    //
    // suspend set
    //
    function suspend_set($id, $state) {
        $this->db->where('id', $id);
        $this->db->set('suspend', $state);
        $this->db->update($this->config_db_table_user);
        return '{"success": true}';
    }

}
?>