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

    // active
    function active_set($id, $state) {
        $this->db->where('id', $id);
        $this->db->set('active', $state);
        $this->db->update($this->table_name);
        return '{"success": true}';
    }

    // limit check
    /*

    // load
    function load_all_count() {
        $this->db->from('kreomaniak_client');
        return $this->db->count_all_results();
    }
    function load_all() {
        $this->limit_check();
        if (isset($_REQUEST['query']) && $_REQUEST['query'] != '') {
            $this->db->like('nick', $_REQUEST['query']);
            $this->db->or_like('user', $_REQUEST['query']);
            $this->db->or_like('name', $_REQUEST['query']);
            $this->db->or_like('surname', $_REQUEST['query']);
        }
        $query = $this->db->get($this->config_db_table_client);
        $record = $query->result_array();
        return $record;
    }
    function load($id) {

    }
    function load_ui($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->config_db_table_client);
        $record = $query->row_array();
        return $record;
    }
    function load_detail_ui($id) {
        //$this->db->where('id', $id);
        //$query = $this->db->get($this->config_db_table_client);
        //$record = $query->row_array();
        return $record;
    }
    function load_last_registered_ui() {
        $this->db->limit(2);
        $this->db->order_by('date_activated desc');
        $query = $this->db->get($this->config_db_table_client);
        $record = $query->result_array();
        return $record;
    }
    function load_field($field, $value) {
        $this->db->where($field, $value);
        $query = $this->db->get($this->config_db_table_client);
        if ($this->db->affected_rows() > 0) {
            $record = $query->row_array();
            return $record;
        }
    }

    // add
    function add() {
        $this->db->insert($this->config_db_table_client, $_POST);
        return 1;
    }
    function add_ui() {
        // check for missed user
        if (!isset($_POST['user'])) {
            $result = array();
            $result['success'] = 0;
            $result['code'] = 'user_missing';
            return $result;
        }
        // remove second password from post
        unset($_POST['password2']);
        unset($_POST['checkbox_agree']);
        // check does the user exists in database (email)
        $this->db->where('user', $_POST['user']);
        $query = $this->db->get($this->config_db_table_client);
        $user = $query->row_array();
        if ($this->db->affected_rows() > 0) {
            // user (email) exists - registration impossible
            $result = array();
            $result['success'] = 0;
            $result['code'] = 'user_exist';
        } else {
            // define element for tree table ( user is related to directories to organise content )
            $_REQUEST['title'] = $_POST['user'];
            // create directories in a tree structure
            $this->tree_model->add_save('media_image', MEDIA_IMAGE_CLIENT_ID);
            $mi_id = $this->tree_model->db->insert_id();
            $this->tree_model->add_save('media_video', MEDIA_VIDEO_CLIENT_ID);
            $mv_id = $this->tree_model->db->insert_id();
            $this->tree_model->add_save('media_file', MEDIA_FILE_CLIENT_ID);
            $mf_id = $this->tree_model->db->insert_id();
            $_POST['id_media_image'] = $mi_id;
            $_POST['id_media_video'] = $mv_id;
            $_POST['id_media_file'] = $mf_id;
            // insert user
            // generate password
            $_POST['password'] = isset($_POST['password']) ? sha1($_POST['password']) : sha1('default');
            $_POST['password_hash'] = md5(date("Y-m-d H:i:s").$_POST['user']);
            // generate date related stuff
            $_POST['date_added'] = date("Y-m-d H:i:s");
            $_POST['date_last_modified'] = date("Y-m-d H:i:s");
            $_POST['point'] = 15;
            // insert new user
            $this->db->insert($this->config_db_table_client, $_POST);
            // result
            $result = array();
            $result['success'] = 1;
            $result['code'] = 'ok';
        }
        return $result;
    }
    function add_facebook_ui_check() {
        // check does the user exists in database (email)
        $this->db->where('user', $_POST['user']);
        $query = $this->db->get($this->config_db_table_client);
        $user = $query->row_array();
        if ($this->db->affected_rows() > 0) {
            // user (email) exists - lets go to update
            $result = array();
            $result['success'] = 1;
            $result['code'] = 'user_exist';
        } else {
            // user (email) does not exists - lets add
            $result = array();
            $result['success'] = 1;
            $result['code'] = 'user_does_not_exist';
        }
        return $result;
    }
    function add_facebook_ui_add() {
        // define element for tree table ( user is related to directories to organise content )
        $_REQUEST['title'] = $_POST['user'];
        // create directories in a tree structure
        $this->tree_model->add_save('media_image', MEDIA_IMAGE_CLIENT_ID);
        $mi_id = $this->tree_model->db->insert_id();
        $this->tree_model->add_save('media_video', MEDIA_VIDEO_CLIENT_ID);
        $mv_id = $this->tree_model->db->insert_id();
        $this->tree_model->add_save('media_file', MEDIA_FILE_CLIENT_ID);
        $mf_id = $this->tree_model->db->insert_id();
        $_POST['id_media_image'] = $mi_id;
        $_POST['id_media_video'] = $mv_id;
        $_POST['id_media_file'] = $mf_id;
        //
        $_POST['date_added'] = date("Y-m-d H:i:s");
        $_POST['date_last_modified'] = date("Y-m-d H:i:s");
        $_POST['point'] = 15;
        // insert new user
        $this->db->insert($this->config_db_table_client, $_POST);
        $result = array();
        $result['success'] = 1;
        $result['code'] = 'facebook_add';
        return $result;
    }
    function add_facebook_ui_update($user) {
        $this->db->where('user', $user);
        $this->db->update($this->config_db_table_client, $_POST);
        $result = array();
        $result['success'] = 1;
        $result['code'] = 'facebook_update';
        return $result;
    }

    // edit
    function edit($id = null) {

    }
    function edit_ui($id = null) {
        // password check
        if (isset($_POST['checkbox_password'])) {
            $_POST['password'] = isset($_POST['password']) ? sha1($_POST['password']) : sha1('default');
            $_POST['password_hash'] = md5(date("Y-m-d H:i:s").$_POST['user']);
            unset($_POST['checkbox_password']);
            unset($_POST['password2']);
        } else {
            unset($_POST['password']);
            unset($_POST['password2']);
        }
        // unset
        unset($_POST['user_id_media_image']);
        unset($_POST['userfile_name']);
        unset($_POST['userfile_type']);
        //
        if (!isset($_POST['checkbox_newsletter'])) { $_POST['checkbox_newsletter'] = 0; }
        if (!isset($_POST['checkbox_marketing'])) { $_POST['checkbox_marketing'] = 0; }
        // we need to check and compare nick of others
        if (isset($_POST['nick'])) {
            $this->db->where('nick', $_POST['nick']);
            $query = $this->db->get('kreomaniak_client');
            if ($this->db->affected_rows() == 0) {
                // update
                $record = $_POST;
                $record['date_last_modified'] = date("Y-m-d H:i:s");
                $this->db->where('id', $id);
                $this->db->update('kreomaniak_client', $record);
                //
                $result = array();
                $result['success'] = 1;
                $result['code'] = 'ok';
            } else {
                $result = array();
                $result['success'] = 0;
                $result['code'] = 'nick_exists';
            }

        } else {
            // update
            $record = $_POST;
            $record['date_last_modified'] = date("Y-m-d H:i:s");
            $this->db->where('id', $id);
            $this->db->update('kreomaniak_client', $record);
            //
            $result = array();
            $result['success'] = 1;
            $result['code'] = 'ok';
        }
        return $result;
    }
    function edit_point_ui() {
        // values
        $amount = $_POST['amount'];
        $id = $_POST['id'];
        $id_trans = $_POST['id_trans'];
        $pay_type = $_POST['pay_type'];
        // identify client id
        $this->db->where('id', $id);
        $this->db->where('converted', 0);
        $query = $this->db->get($this->config_db_table_payment);
        $order = $query->row_array();
        if ($this->db->affected_rows() > 0) {
            // get client
            $this->db->where('id', $order['id_user']);
            $query = $this->db->get($this->config_db_table_client);
            $client = $query->row_array();
            if ($this->db->affected_rows() > 0) {
                // add point to account
                $point = $this->point_convert($amount);
                $record = array();
                $record['point'] = (int)$client['point'] + $point;
                $record['date_last_modified'] = date("Y-m-d H:i:s");
                $this->db->where('id', $client['id']);
                $this->db->update($this->config_db_table_client, $record);
                // add point to operation history
                $history = array();
                $history['id_user'] = $client['id'];
                $history['id_payment'] = $order['id'];
                $history['operation'] = 'payment '.$pay_type;
                $history['point'] = $point;
                $history['date_added'] = date('Y-m-d H:i:s');
                $this->db->insert($this->config_db_table_point, $history);
                // convert payment to "used"
                $record = array();
                $record['converted'] = 1;
                $this->db->where('id', $id);
                $query = $this->db->update($this->config_db_table_payment, $record);
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    // delete
    function delete($id = null) {
        $this->db->where('id', $id);
        $query = $this->db->delete('kreomaniak_client');
        return '{"success": true}';
    }
    function delete_ui($id = null) {

    }

    // support
    function password_generate() {
        $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ1234567890";
        $validCharNumber = strlen($validCharacters);
        $password = '';
        for ($i = 0; $i < 5; $i++) {
            $index = mt_rand(0, $validCharNumber-1);
            $password .= $validCharacters[$index];
        }
        return $password;
    }
    function activate($password_hash) {
        $this->db->where('password_hash', $password_hash);
        $query = $this->db->get($this->config_db_table_client);
        $user = $query->row_array();
        if ($this->db->affected_rows() > 0) {
            $record = array();
            $record['active'] = 1;
            $record['date_activated'] = date("Y-m-d H:i:s");
            $this->db->where('password_hash', $password_hash);
            $this->db->update($this->config_db_table_client, $record);
            $result = array();
            $result['success'] = 1;
            $result['code'] = 'ok';
        } else {
            $result = array();
            $result['success'] = 0;
            $result['code'] = 'hash_not_found';
        }
        return $result;
    }
    function login() {
        $result = array();
        if (!isset($_POST['user']) || !isset($_POST['password'])) {
            $result['success'] = 0; $result['code'] = 'no_data'; return $result;
        }
        $this->db->where('user', $_POST['user']);
        $this->db->where('password', sha1($_POST['password']));
        $query = $this->db->get($this->config_db_table_client);
        $record = $query->row_array();
        if ($this->db->affected_rows() > 0) {
            if ($record['suspend'] == 1) { $result['success'] = 0; $result['code'] = 'suspended'; }
            else if ($record['active'] == 0) { $result['success'] = 0; $result['code'] = 'unactive'; }
            else { $result['success'] = 1; $result['client'] = $record; $result['code'] = 'ok'; }
        } else {
            $result['success'] = 0; $result['code'] = 'not_found'; return $result;
        }
        return $result;
    }
    function user_get($field, $value) {
        $this->db->where($field, $value);
        $query = $this->db->get($this->config_db_table_client);
        $user = $query->row_array();
        if ($this->db->affected_rows() > 0) {
            $result = array();
            $result['success'] = 1;
            $result['code'] = 'ok';
            $result['user'] = $user;
        } else {
            $result = array();
            $result['success'] = 0;
            $result['code'] = 'user_not_found';
        }
        return $result;
    }

    // point
    function point_load_all_user_ui($id_user) {
        $this->db->where('id_user', $id_user);
        $this->db->order_by('date_added desc');
        $query = $this->db->get($this->config_db_table_point);
        $record = $query->result_array();
        return $record;
    }
    function point_get_ui($id) {
        $this->db->where('id', $id);
        $query = $this->db->get($this->config_db_table_client);
        $record = $query->row_array();
        return $record['point'];
    }
    function point_reduce_ui($id, $point, $operation) {
        if (!isset($id) || !isset($point) || !isset($operation)) { die; }
        // add point to operation history
        $history = array();
        $history['id_user'] = $id;
        $history['id_payment'] = 0;
        $history['operation'] = $operation;
        $history['point'] = -$point;
        $history['date_added'] = date('Y-m-d H:i:s');
        $this->db->insert($this->config_db_table_point, $history);
        // reduce point from account
        $sql = "update kreomaniak_client set point = point - ".$point." where id = ". $id;
        $this->db->query($sql);
        echo $sql;
    }
    function point_convert($amount = 0) {
        $amount = $amount / 100;
        if ($amount == 18) { return 10; }
        if ($amount == 34) { return 20; }
        if ($amount == 80) { return 50; }
        if ($amount == 145) { return 100; }
        if ($amount == 700) { return 500; }
        if ($amount == 1300) { return 1000; }
        if ($amount == 5500) { return 5000; }
        if ($amount == 10000) { return 10000; }
    }

    // password reset
    function password_reset_ui() {
        $record = array();
        $record['password'] = sha1($_POST['password']);
        $record['password_hash'] = md5(date("Y-m-d H:i:s").$_POST['user']);
        // generate date related stuff
        $_POST['date_last_modified'] = date("Y-m-d H:i:s");
        // update
        $this->db->where('user', $_POST['user']);
        $this->db->update('kreomaniak_client', $record);
        // result
        $result = array();
        $result['success'] = 1;
        $result['code'] = 'ok';
        return $result;
    }
    function password_clear($password_hash) {
        $record = array();
        $record['password'] = null;
        $this->db->where('password_hash', $password_hash);
        $this->db->update('kreomaniak_client', $record);
        // result
        $result = array();
        $result['success'] = 1;
        $result['code'] = 'ok';
        return $result;
    }
    function password_update($password_hash, $password) {
        $record = array();
        $record['password'] = $password;
        $this->db->where('password_hash', $password_hash);
        $this->db->update('kreomaniak_client', $record);
        // result
        $result = array();
        $result['success'] = 1;
        $result['code'] = 'ok';
        return $result;
    }*/
}
?>