<?php
if (!defined('BASEPATH')) die;

class Client extends Main {

    function Client($_ci = '') {
        parent::Controller();
        //
        $this->ci = $_ci;
        // load models
        $this->load->model('main_model');
        $this->load->model('_system/tree_model');
        $this->load->model('wh/client_model');
    }

    // display
    function display() {
        $this->ci->smarty->display('wh_admin/client.html');
    }
    function display_add() {
        $this->ci->smarty->display('wh_admin/client_add.html');
    }
    function display_edit($id = null) {
        $this->ci->smarty->assign('id', $id);
        $this->ci->smarty->display('wh_admin/client_edit.html');
    }











    /*function display() {
        $this->ci->smarty->display('kreomaniak/client.html');
    }
    function display_add() {
        $this->ci->smarty->display('kreomaniak/client_add.html');
    }
    function display_edit($id = null) {
        $this->ci->smarty->assign('id', $id);
        $this->ci->smarty->display('kreomaniak/client_edit.html');
    }

    // load
    function load_all() {
        echo '{"total":'.json_encode($this->client_model->load_all_count()).', "data":'.json_encode($this->client_model->load_all()).'}';
    }
    function load($id) {

    }
    function load_ui($id) {
        echo '{"success": 1, "data":'.json_encode($this->client_model->load_ui($id)).'}';
    }
    function load_detail_ui($id) {
        echo '{"success": 1, "data":'.json_encode($this->client_model->load_detail_ui($id)).'}';
    }
    function load_last_registered_ui() {
        echo '{"success": 1, "data":'.json_encode($this->client_model->load_last_registered_ui()).'}';
    }

    // add
    function add() {
        $result = $this->client_model->add();
        echo '{"success": '. $result .'}';
    }
    function add_ui() {
        $result = $this->client_model->add_ui();
        // if success send optin email
        if ($result['success'] == 1) {
            $this->add_opt_in($_POST);
        }
        echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
    }
    function add_facebook_ui_check() {
        if (!isset($_POST['user'])) { die(); }
        // first we need to verify does user exist in db already
        $result = $this->client_model->add_facebook_ui_check();
        echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
    }
    function add_facebook_ui_add() {
        $result = $this->client_model->add_facebook_ui_add();
        echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
    }
    function add_facebook_ui_update($user = null) {
        if (!isset($user)) { die(); }
        $result = $this->client_model->add_facebook_ui_update($user);
        echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
    }

    // edit
    function edit($id = null) {
        $result = $this->client_model->edit($id);
        echo '{"success": '. $result .'}';
    }
    function edit_ui($id = null) {
        $upload_data = array();
        if (isset($_POST['userfile_name']) && $_POST['userfile_name'] != '') {
            $result = $this->media_image_model->add_ui($_POST['user_id_media_image']);
            if ($result['success'] == 1) {
                $_POST['image'] = $result['upload_data']['file_name'];
                $upload_data = $result['upload_data'];
            }
        }
        $result = $this->client_model->edit_ui($id);
        echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'", "upload_data": '. json_encode($upload_data) .'}';
    }
    function edit_point_ui() {
        $result = $this->client_model->edit_point_ui();
        echo '{"success": '. $result .'}';
    }

    // delete
    function delete($id = null) {
        $result = $this->client_model->delete($id);
        echo 'grid';
    }
    function delete_ui($id = null) {

    }

    // opt
    function add_opt_in($record) {
        // lets define data
        $this->ci->smarty->assign('path_template', SITE_URL.'/templates/email/'.CONFIGURATION);
        $this->ci->smarty->assign('path_media', SITE_URL.'/templates/email/'.CONFIGURATION);
        $this->ci->smarty->assign('path_site', SITE_URL);
        $this->ci->smarty->assign('nick', $_POST['nick']);
        $this->ci->smarty->assign('password_hash', $record['password_hash']);
        $message = $this->ci->smarty->fetch('../../../../templates/email/'.CONFIGURATION.'/client_optin.html');
        //
        $config['protocol'] = EMAIL_PROTOCOL;
        $config['mailtype'] = EMAIL_MODE;
        $config['charset'] = EMAIL_ENCODING;
        //
        $this->ci->email->initialize($config);
        $this->ci->email->subject('Kreomaniak - Rejestracja');
        $this->ci->email->from($this->config_email_from1, $this->config_email_from2);
        $this->ci->email->to($_POST['user']);
        $this->ci->email->message($message);
        $this->ci->email->send();
    }
    function add_opt_in_confirm($password_hash = null) {
        if (!isset($password_hash)) { die(); }
        // select user base on hash value
        $this->ci->db->where('password_hash', $password_hash);
        $query = $this->ci->db->get($this->config_db_table_client);
        $record = $query->row_array();
        // lets define data
        $this->ci->smarty->assign('path_template', SITE_URL.'/templates/email/'.CONFIGURATION);
        $this->ci->smarty->assign('path_media', SITE_URL.'/templates/email/'.CONFIGURATION);
        $this->ci->smarty->assign('path_site', SITE_URL);
        $this->ci->smarty->assign('nick', $record['nick']);
        $message = $this->ci->smarty->fetch('../../../../templates/email/'.CONFIGURATION.'/client_optin_confirm.html');
        //
        $config['protocol'] = EMAIL_PROTOCOL;
        $config['mailtype'] = EMAIL_MODE;
        $config['charset'] = EMAIL_ENCODING;
        //
        $this->ci->email->initialize($config);
        $this->ci->email->subject('Kreomaniak - Rejestracja - Potwierdzenie');
        $this->ci->email->from($this->config_email_from1, $this->config_email_from2);
        $this->ci->email->to($record['user']);
        $this->ci->email->message($message);
        $this->ci->email->send();
    }

    //
    function activate($password_hash = null) {
        $result = $this->client_model->activate($password_hash);
        // if hash fits send confirmation email
        if ($result['success'] == 1) {
            $this->add_opt_in_confirm($password_hash);
        }
        echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
    }
    function login() {
        $result = $this->client_model->login();
        if ($result['success'] == 1) {
            echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'", "client": '. json_encode($result['client']) .'}';
        } else {
            echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
        }
    }
    function user_get($field = null, $value = null) {
        $result = $this->client_model->user_get($field, $value);
        if ($result['success'] == 1) {
            echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'", "user": '. json_encode($result['user']) .'}';
        } else {
            echo '{"success": '. $result['success'] .', "code": "'. $result['code'] .'"}';
        }
    }

    // point
    function point_load_all_user_ui($id_user = null) {
        echo '{"success": 1, "data":'.json_encode($this->client_model->point_load_all_user_ui($id_user)).'}';
    }
    function point_get_ui($id = null) {
        $result = $this->client_model->point_get_ui($id);
        echo '{"point": '. $result .'}';
    }
    function point_reduce_ui($id = null, $point = null, $operation = null) {
        $result = $this->client_model->point_reduce_ui($id, $point, $operation);
        echo '{"success": 1}';
    }

    // password reset
    function password_reset_ui($user = null) {
        if (!isset($user)) { echo '{"success": false}'; die; }
        // first we need user data
        $record = $this->client_model->load_field('user', $user);
        //
        if (isset($record) && isset($record['password'])) {
            // clear password field
            //$this->user_model->password_clear($record['password_hash']);
            // template is on the start
            $this->ci->smarty->assign('path_template', SITE_URL.'/templates/email/'.CONFIGURATION);
            $this->ci->smarty->assign('path_media', SITE_URL.'/templates/email/'.CONFIGURATION);
            $this->ci->smarty->assign('path_site', SITE_URL);
            $this->ci->smarty->assign('user', $record['user']);
            $this->ci->smarty->assign('name', $record['name']);
            $this->ci->smarty->assign('surname', $record['surname']);
            $this->ci->smarty->assign('password_hash', $record['password_hash']);
            $message = $this->ci->smarty->fetch('../../../../templates/email/'.CONFIGURATION.'/client_password_reset.html');
            // now, email config
            $config['protocol'] = EMAIL_PROTOCOL;
            $config['mailtype'] = EMAIL_MODE;
            $config['charset'] = EMAIL_ENCODING;
            // hit it! send it!
            $this->ci->email->initialize($config);
            $this->ci->email->subject('Kreomaniak - Reset Hasła');
            $this->ci->email->from($this->config_email_from1, $this->config_email_from2);
            $this->ci->email->to($record['user']);
            $this->ci->email->message($message);
            $this->ci->email->send();
            //
            echo '{"success": 1, "code": "password_reset"}';
        } else {
            echo '{"success": 0, "code": "user_missing"}';
        }
    }
    function password_reset_confirm_ui($password_hash = null) {
        if (!isset($password_hash)) { echo '{"success": false}'; die; }
        // first we need user data
        $record = $this->client_model->load_field('password_hash', $password_hash);
        //
        if (isset($record) && isset($record['password'])) {
            // lets generate new password
            $password = $this->password_generate();
            // cipher new password with sha1
            $password_sha1 = sha1($password);
            // lets update user record
            $this->client_model->password_update($password_hash, $password_sha1);
            // template prepare
            $this->ci->smarty->assign('path_template', SITE_URL.'/templates/email/'.CONFIGURATION);
            $this->ci->smarty->assign('path_media', SITE_URL.'/templates/email/'.CONFIGURATION);
            $this->ci->smarty->assign('path_site', SITE_URL);
            $this->ci->smarty->assign('user', $record['user']);
            $this->ci->smarty->assign('name', $record['name']);
            $this->ci->smarty->assign('surname', $record['surname']);
            $this->ci->smarty->assign('password', $password);
            $message = $this->ci->smarty->fetch('../../../../templates/email/'.CONFIGURATION.'/client_password_reset_confirm.html');
            // now, email config
            $config['protocol'] = EMAIL_PROTOCOL;
            $config['mailtype'] = EMAIL_MODE;
            $config['charset'] = EMAIL_ENCODING;
            // hit it! send it!
            $this->ci->email->initialize($config);
            $this->ci->email->subject('Kreomaniak - Reset Hasła');
            $this->ci->email->from($this->config_email_from1, $this->config_email_from2);
            $this->ci->email->to($record['user']);
            $this->ci->email->message($message);
            $this->ci->email->send();
            //
            echo '{"success": 1, "code": "password_changed"}';
        } else {
            echo '{"success": 0, "code": "user_missing"}';
        }
    }
    function password_generate() {
        $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ1234567890";
        $validCharNumber = strlen($validCharacters);
        $password = '';
        for ($i = 0; $i < 5; $i++) {
            $index = mt_rand(0, $validCharNumber-1);
            $password .= $validCharacters[$index];
        }
        return $password;
    }*/

}