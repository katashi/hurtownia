<?php
if (!defined('BASEPATH')) die;

class Product extends Main {

    function Product($_ci = '') {
        parent::Controller();
        //
        $this->ci = $_ci;
        // load models
        $this->load->model('main_model');
        $this->load->model('wh/product_model');
    }

    // display
    function display($id_tree = null) {
        $this->ci->smarty->assign('id', $id_tree);
        $this->ci->smarty->display('wh_admin/product.html');
    }
    function display_add($id_tree = 0) {
        $this->ci->smarty->assign('id', $id_tree);
        $this->ci->smarty->display('wh_admin/product_add.html');
    }
    function display_edit($id = null) {
        $this->ci->smarty->assign('id', $id);
        $this->ci->smarty->display('wh_admin/product_edit.html');
    }

    // load
    function load_all($id_tree = null) {
        echo '{"total":'.json_encode($this->product_model->load_all_count($id_tree)).', "data":'.json_encode($this->product_model->load_all($id_tree)).'}';
    }
    function load($id = null) {
        echo '{"success": 1, "data":'.json_encode($this->product_model->load($id)).'}';
    }

    // add
    function add() {
        $result = $this->product_model->add();
        echo '{"success": '. $result .'}';
    }

    // edit
    function edit($id = null) {
        $result = $this->product_model->edit($id);
        echo '{"success": '. $result .'}';
    }

    // delete
    function delete($id = null) {
        $result = $this->product_model->delete($id);
        echo 'grid';
    }

    // active set
    function active_set($id = null, $state = false) {
        $result = $this->product_model->active_set($id, $state);
        echo 'grid';
    }

}