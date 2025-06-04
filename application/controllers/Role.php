<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom Diagnostic Management System
 * @version : 5.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Role.php
 */

class Role extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model');
        if (!is_superadmin_loggedin()) {
            access_denied();
        }
    }

    // new role add
    public function index()
    {
        if (isset($_POST['save'])) {
            $rules = array(
                array(
                    'field' => 'role',
                    'label' => 'Role Name',
                    'rules' => 'required|callback_unique_name',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['validation_error'] = true;
            } else {
                // update information in the database
                $data = $this->input->post();
                $this->role_model->save_roles($data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('role'));
            }
        }
        $this->data['roles'] = $this->role_model->getRoleList();
        $this->data['title'] = translate('roles');
        $this->data['sub_page'] = 'role/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // role edit
    public function edit($id)
    {
        if (isset($_POST['save'])) {
            $rules = array(
                array(
                    'field' => 'role',
                    'label' => 'Role Name',
                    'rules' => 'required|callback_unique_name',
                ),
            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['validation_error'] = true;
            } else {
                // SAVE ROLE INFORMATION IN THE DATABASE
                $data = $this->input->post();
                $this->role_model->save_roles($data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('role'));
            }
        }
        $this->data['roles'] = $this->role_model->get('roles', array('id' => $id), true);
        $this->data['title'] = translate('roles');
        $this->data['sub_page'] = 'role/edit';
        $this->data['main_menu'] = 'test';
        $this->load->view('layout/index', $this->data);
    }

    // check unique name
    public function unique_name($name)
    {
        $id = $this->input->post('id');
        if (isset($id)) {
            $where = array('name' => $name, 'id != ' => $id);
        } else {
            $where = array('name' => $name);
        }
        $q = $this->db->get_where('roles', $where);
        if ($q->num_rows() > 0) {
            $this->form_validation->set_message("unique_name", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }

    // role delete in DB
    public function delete($role_id)
    {
        $systemRole = array(1, 2, 3, 4, 5, 6, 7);
        if (!in_array($role_id, $systemRole)) {
            $this->db->where('id', $role_id);
            $this->db->delete('roles');
        }
    }

    public function permission($role_id)
    {
        $roleList = $this->role_model->getRoleList();
        $allowRole = array_column($roleList, 'id');
        if (!in_array($role_id, $allowRole)) {
            access_denied();
        }
        if (isset($_POST['save'])) {
            $role_id = $this->input->post('role_id');
            $privileges = $this->input->post('privileges');
            foreach ($privileges as $key => $value) {
                $is_add = (isset($value['add']) ? 1 : 0);
                $is_edit = (isset($value['edit']) ? 1 : 0);
                $is_view = (isset($value['view']) ? 1 : 0);
                $is_delete = (isset($value['delete']) ? 1 : 0);
                $arrayData = array(
                    'role_id' => $role_id,
                    'permission_id' => $key,
                    'is_add' => $is_add,
                    'is_edit' => $is_edit,
                    'is_view' => $is_view,
                    'is_delete' => $is_delete,
                );
                $exist_privileges = $this->db->select('id')->limit(1)->where(array('role_id' => $role_id, 'permission_id' => $key))->get('staff_privileges')->num_rows();
                if ($exist_privileges > 0) {
                    $this->db->update('staff_privileges', $arrayData, array('role_id' => $role_id, 'permission_id' => $key));
                } else {
                    $this->db->insert('staff_privileges', $arrayData);
                }
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('role/permission/' . $role_id));
        }
        $this->data['role_id'] = $role_id;
        $this->data['modules'] = $this->role_model->getModulesList();
        $this->data['title'] = translate('roles');
        $this->data['sub_page'] = 'role/permission';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    // Role Groups Management
    public function groups()
    {
        $this->data['role_groups'] = $this->role_model->getRoleGroups();
        $this->data['roles'] = $this->role_model->getRoleList();
        $this->data['title'] = 'Role Groups';
        $this->data['sub_page'] = 'role/groups';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function group_add()
    {
        if ($this->input->post()) {
            $data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
            ];
            $group_id = $this->role_model->saveRoleGroup($data);
            $role_ids = $this->input->post('role_ids') ?: [];
            $this->role_model->setRolesForGroup($group_id, $role_ids);
            set_alert('success', 'Role Group saved successfully.');
            redirect(base_url('role/groups'));
        }
        $this->data['roles'] = $this->role_model->getRoleList();
        $this->data['title'] = 'Add Role Group';
        $this->data['sub_page'] = 'role/group_add';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function group_edit($id)
    {
        $group = $this->role_model->getRoleGroup($id);
        if (!$group) redirect(base_url('role/groups'));
        if ($this->input->post()) {
            $data = [
                'id' => $id,
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
            ];
            $this->role_model->saveRoleGroup($data);
            $role_ids = $this->input->post('role_ids') ?: [];
            $this->role_model->setRolesForGroup($id, $role_ids);
            set_alert('success', 'Role Group updated successfully.');
            redirect(base_url('role/groups'));
        }
        $this->data['group'] = $group;
        $this->data['roles'] = $this->role_model->getRoleList();
        $this->data['assigned_roles'] = array_column($this->role_model->getRolesByGroup($id), 'id');
        $this->data['title'] = 'Edit Role Group';
        $this->data['sub_page'] = 'role/group_edit';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function group_delete($id)
    {
        $this->role_model->deleteRoleGroup($id);
        set_alert('success', 'Role Group deleted successfully.');
        redirect(base_url('role/groups'));
    }
}
