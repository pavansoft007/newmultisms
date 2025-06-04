<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function getRoleList()
    {
        $this->db->select('*');
        $this->db->where_not_in('id', array(1,6,7));
        $r = $this->db->get('roles')->result_array();
        return $r;  
    }

    function getModulesList()
    {
        $this->db->order_by('sorted', 'ASC');
        return $this->db->get('permission_modules')->result_array(); 
    }

    // role save and update function
    public function save_roles($data)
    {
        $insertData = array(
            'name' => $data['role'],
            'prefix' => strtolower(str_replace(' ', '', $data['role'])),
        );

        if (!isset($data['id']) && empty($data['id'])) {
            $insertData['is_system'] = 0;
            $this->db->insert('roles', $insertData);
        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('roles', $insertData);
        }
    }

    // check permissions function
    public function check_permissions($module_id = '', $role_id = '')
    {
        $sql = "SELECT permission.*, staff_privileges.id as staff_privileges_id,staff_privileges.is_add,staff_privileges.is_edit,staff_privileges.is_view,staff_privileges.is_delete FROM permission LEFT JOIN staff_privileges ON staff_privileges.permission_id = permission.id and staff_privileges.role_id = " . $this->db->escape($role_id) . " WHERE permission.module_id = " . $this->db->escape($module_id) . " ORDER BY permission.id ASC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    // Role Groups CRUD
    public function getRoleGroups() {
        return $this->db->get('role_groups')->result_array();
    }
    public function getRoleGroup($id) {
        return $this->db->get_where('role_groups', ['id' => $id])->row_array();
    }
    public function saveRoleGroup($data) {
        if (isset($data['id']) && $data['id']) {
            $this->db->where('id', $data['id']);
            $this->db->update('role_groups', $data);
            return $data['id'];
        } else {
            $this->db->insert('role_groups', $data);
            return $this->db->insert_id();
        }
    }
    public function deleteRoleGroup($id) {
        $this->db->where('id', $id);
        $this->db->delete('role_groups');
    }
    // Role-Group Mapping
    public function getRolesByGroup($role_group_id) {
        $this->db->select('roles.*');
        $this->db->from('role_group_roles');
        $this->db->join('roles', 'roles.id = role_group_roles.role_id');
        $this->db->where('role_group_roles.role_group_id', $role_group_id);
        return $this->db->get()->result_array();
    }
    public function setRolesForGroup($role_group_id, $role_ids) {
        $this->db->where('role_group_id', $role_group_id);
        $this->db->delete('role_group_roles');
        foreach ($role_ids as $role_id) {
            $this->db->insert('role_group_roles', [
                'role_group_id' => $role_group_id,
                'role_id' => $role_id
            ]);
        }
    }
}
