<?php
if (!defined('BASEPATH')) die;

class Product_Dict_Vendor extends Main {

    function Product_Dict_Vendor($_ci = '') {
        parent::Controller();
        //
        $this->ci = $_ci;
        // load models
        $this->load->model('main_model');
        $this->load->model('wh/product_dict_vendor_model');
    }

    // display
    function display() {
        $this->ci->smarty->display('wh_admin/product_dict_vendor.html');
    }
    function display_add() {
        $this->ci->smarty->display('wh_admin/product_dict_vendor_add.html');
    }

    // load
    function load_all() {
        echo '{"total":'.json_encode($this->product_dict_vendor_model->load_all_count()).', "data":'.json_encode($this->product_dict_vendor_model->load_all()).'}';
    }
    function load($id = null) {
        echo '{"success": 1, "data":'.json_encode($this->product_dict_model_model->load($id)).'}';
    }

    // add
    function add() {
        $result = $this->product_dict_vendor_model->add();
        echo '{"success": '. $result .'}';
    }

    // delete
    function delete($id = null) {
        $result = $this->product_dict_vendor_model->delete($id);
        echo 'grid';
    }

}