<?php
if (!defined('BASEPATH')) die;

class Tax extends Main {

    function Tax($_ci = '') {
        parent::Controller();
        //
        $this->ci = $_ci;
        // load models
        $this->load->model('main_model');
        $this->load->model('wh/tax_model');
    }

    // load
    function load_all($id_tree = null) {
        echo '{"total":'.json_encode($this->tax_model->load_all_count()).', "data":'.json_encode($this->tax_model->load_all()).'}';
    }
   

}