<?php
class Main_Model extends Model {
		
	function Main_Model() {
		// Call the Model constructor
		parent::Model();
		//
		if (isset($this->ci)) { $this->db = $this->ci->db; }
	}

}
?>