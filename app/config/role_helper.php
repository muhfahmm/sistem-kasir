<?php
/**
 * Helper Functions untuk Role Management
 * Support Multiple Roles (admin, kasir, atau admin,kasir)
 */

/**
 * Cek apakah user memiliki role tertentu
 * @param string $role_to_check - Role yang ingin dicek ('admin' atau 'kasir')
 * @return bool
 */
function hasRole($role_to_check) {
    if (!isset($_SESSION['all_roles'])) {
        return false;
    }
    
    $all_roles = $_SESSION['all_roles'];
    $roles_array = explode(',', $all_roles);
    
    return in_array($role_to_check, $roles_array);
}

/**
 * Cek apakah user adalah admin
 * @return bool
 */
function isAdmin() {
    return hasRole('admin');
}

/**
 * Cek apakah user adalah kasir
 * @return bool
 */
function isKasir() {
    return hasRole('kasir');
}

/**
 * Cek apakah user memiliki kedua role (admin dan kasir)
 * @return bool
 */
function hasBothRoles() {
    return hasRole('admin') && hasRole('kasir');
}

/**
 * Get role yang sedang aktif (yang dipilih saat login)
 * @return string
 */
function getActiveRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : '';
}

/**
 * Get semua role yang dimiliki user
 * @return array
 */
function getAllRoles() {
    if (!isset($_SESSION['all_roles'])) {
        return [];
    }
    
    return explode(',', $_SESSION['all_roles']);
}

/**
 * Cek apakah user sedang login sebagai admin (role aktif = admin)
 * @return bool
 */
function isActiveAdmin() {
    return getActiveRole() === 'admin';
}

/**
 * Cek apakah user sedang login sebagai kasir (role aktif = kasir)
 * @return bool
 */
function isActiveKasir() {
    return getActiveRole() === 'kasir';
}

/**
 * Get display name untuk role
 * @param string $role
 * @return string
 */
function getRoleDisplayName($role) {
    $role_names = [
        'admin' => 'Administrator',
        'kasir' => 'Kasir'
    ];
    
    return isset($role_names[$role]) ? $role_names[$role] : ucfirst($role);
}

/**
 * Get all roles display name
 * @return string - Contoh: "Admin, Kasir"
 */
function getAllRolesDisplay() {
    $roles = getAllRoles();
    $display_names = array_map('getRoleDisplayName', $roles);
    return implode(', ', $display_names);
}
?>
