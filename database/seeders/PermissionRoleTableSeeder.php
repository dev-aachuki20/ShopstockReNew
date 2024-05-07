<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // role_create ,role_edit,role_show,  role_access profile_access profile_edit user_change_password dashboard_widget_access staff_access staff_create staff_edit staff_delete staff_print staff_export staff_rejoin customer_management_access customer_access customer_create customer_edit customer_delete customer_print customer_export master_access category_access category_create category_edit category_delete category_print category_export group_access group_create group_edit group_print group_export group_delete group_undo area_access area_create area_edit area_print area_export area_delete  product_access product_create product_edit product_delete product_undo product_print product_export  ip_access ip_create ip_edit ip_delete unit_access unit_create unit_edit unit_delete split_access split_create log_access log_view report_access report_customer_access setting_access setting_edit transaction_management_access transaction_access transaction_create transaction_edit transaction_delete estimate_management_access estimate_access  estimate_create estimate_show estimate_cancelled_show estimate_edit estimate_history estimate_delete estimate_print estimate_ledger_print estimate_statement_print estimate_date_filter_access

        $roles = Role::all();
        $superadminpermissionid = Permission::all();

        $adminpermissionid= Permission::whereIn('name',['profile_access', 'profile_edit', 'user_change_password' , 'customer_management_access', 'customer_access', 'transaction_management_access','estimate_access','estimate_show','estimate_ledger_print','estimate_statement_print','estimate_print'])->pluck('id')->toArray();

        $staffpermissionid= Permission::whereIn('name',['profile_access', 'profile_edit' , 'user_change_password' , 'product_access', 'product_create', 'product_edit', 'product_delete', 'product_undo', 'product_print', 'product_export', 'estimate_management_access' ,'estimate_access' , 'estimate_create' ,'estimate_show' , 'estimate_cancelled_show', 'estimate_history', 'estimate_delete','estimate_print','transaction_management_access', 'transaction_access', 'transaction_create','transaction_delete'])->pluck('id')->toArray();

        foreach ($roles as $role) {
            switch ($role->id) {
                case 1:
                    $role->givePermissionTo($superadminpermissionid);
                    break;
                case 2:
                    $role->givePermissionTo($adminpermissionid);
                    break;
                case 3:
                    $role->givePermissionTo($staffpermissionid);
                    break;
                default:
                    break;
            }
        }
    }
}
