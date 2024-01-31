<?php

return [

    'alert-type' => [
        'success'  => 'success',
        'info'     => 'info',
        'warning'  => 'warning',
        'error'    => 'error',
    ],

    'dashboard' => [
        'title' => 'Dashboard',
        'add_product' 		=> 'Add Item',
        'add_customer' 		=> 'Add Party',
        'add_invoice' 		=> 'Add Invoice',
		'reset' 			=> 'Reset',
		'view_detail'		=> 'View Details',
		'total_customer' 	=> 'Total Parties!',
		'total_product' 	=> 'Total Items!',
        'todaySaleAmount'   =>  "Today's Sale",
        'last7DaysSaleAmount'   =>  "Last 7 Days",
        'last30DaysSaleAmount'   =>  "Last 30 Days",
        'allSaleAmount'   =>  "All Time Sale",
        'todayTotalOrder'   =>  "Today's Order",
        'totalProductInStock'   =>  "Total Item",
        'totalCategory'   =>  "Total Category",
        'totalCustomer'   =>  "Total Party",
        'order' => 'Order',
        'today' => 'Today',
        '7days' => '7 Days',
        '30days' => '30 Days',
        'customer' => 'Party',
        'amount' => 'Amount',
        'products' => 'Items',
	],

	'user-management' => [
		'title' => 'Staff management',
		'fields' => [
            'title' => 'Title',
            'add' 	=> 'Add New',
            'list-title'=>'Staff List',
            'list'=> [
                'name'=>'Name',
            ],
            'add-user'=>[
                'title'=> 'Add User',
            ]
		],
	],

    'users' => [
		'title' => 'Staffs',
        'profile'=>'My Profile',
        'users'=>'Staff',
		'fields' => [
			'name' => 'Name',
			'email' => 'Email',
            'usernameid'=>'Username',
			'password' => 'Password',
			'role' => 'Role',
            'phone'=>'Phone Number',
            'address'=>'City',
            'created_at'=>'Created At',
            'created_by'=>'Created By',
			'remember-token' => 'Remember token',
            'add' 	=> 'Add New',
            'edit' => 'Edit Staff',
		]
	],

    'profile'=>[
        'title'=> 'My Profile',
        'edit_profile'=>'Edit Profile',
        'change'=>'Change',
        'profile'=>'Profile',
        'fields'=>[
            'personal_detail'=>'Personal Details',
            'name'=>'Name',
            'email' => 'Email',
            'usernameid'=>'Username',
			'password' => 'Password',
			'role' => 'Role',
            'phone'=>'Phone Number',
            'address'=>'City',
            'status'=>'Status',
        ]
    ],

	'roles' => [
		'title' => 'Roles Management',
        'role'=>'Role',
		'fields' => [
			'title' => 'Title',
            'add' 	=> 'Add New',
            'list-title'=>'Roles List',
            'role_detail'=>'Role Detail',
            'list'=> [
                'name'=>'Name'
            ],
            'add-role'=>[
                'title'=> 'Add Role',
                'edit_role'=>'Edit Role',
                'givepermit'=>'Select Permissions'
            ]
		],
	],

    'permissions' => [
		'title' => 'Permission Management',
        'allow_permissions'=>'Allow Permissions',
		'fields' => [
			'title' => 'Title',
            'add' 	=> 'Add New',
            'list-title'=>'Permission List',
            'list'=> [
                'name'=>'Name',
            ]
		],
	],

    'customer-management' => [
		'title' 	=> 'Party Management',
		'fields' 	=> [
			'add' 	=> 'Add New',
			'list' 	=> 'Party List',
			'alter_list' 	=> 'Alter List',
		],
	],

    'phone-book' => [
		'title' 	=> 'Phone Book',
		'fields' 	=> [
			'name' 			=> 'Name',
            'guardian_name'  => 'Husband/Father Name',
            'ph_num'         =>'Phone No.',
            'address'       =>'City',
			'list' 	=> 'Phone Book',
			'alter_list' 	=> 'Alter List',
		],
	],

	'customers'  => [
		'title'  => 'Parties',
        'customer' => 'Party',
		'customer_details'  	  		=> 'Party Details',
		'customer_order_details'  	    => 'Party Order Details',
		'select_address'  => 'Select City',
		'fields' => [
			'name' 			=> 'Name',
			'email' 		=> 'Email',
            'ph_num'         =>'Phone No.',
            'phone'         =>'Phone No. 1',
            'phone2'         =>'Phone No. 2',
            'address'       =>'City',
            'guardian_name'  => 'Husband/Father Name',
			'action' 		=> 'Action',
            'created_at'    =>'Created At',
			'created_by' 	=> 'Created By',
			'is_type'		=> 'Type',
			'credit_limit'  => 'Credit Limit',
			'phone_number' 	=> 'Phone Number',
			'opening_blance'=> 'Opening Blance',
            'add' 	=> 'Add Party',
            'edit' => 'Edit Party',
            'select_address' => 'Select City',

		],
	],

    'device-management' => [
		'title' 	=> 'Device Management',
		'fields' 	=> [
			'add' 	=> 'Add New',
			'list' 	=> 'Device List',
			'alter_list' 	=> 'Alter List',
		],
	],

	'device'  => [
		'title'  => 'Devices',
        'device' => 'Device',
		'device_details' => 'Device Details',
        'select_staff'  => 'Select Staff',
		'fields' => [
			'name' 			=> 'Name',
			'staff_name' 		=> 'Assigned Staff Name',
            'device_id'         =>'Device ID',
            'device_ip'       =>'Device IP',
            'pin'  => 'PIN Number',
			'action' 		=> 'Action',
            'created_at'    =>'Created At',
			'created_by' 	=> 'Created By',
            'add' 	=> 'Add New',
            'edit' => 'Edit Device',
		],
	],

    'master-management' => [
		'title' => 'Master Management',
		'fields' => [
		],
	],

    'address' => [
		'title' => 'City Management',
        'address'=>'City',
		'fields' => [
			'title' => 'Title',
            'add' 	=> 'Add New',
            'edit' => 'Edit City',
            'list-title'=>'City List',
            'list'=> [
                'address'=>'City',
                'no_of_customer' =>'No. of Party',
                'created_at'=>'Created At',
            ]
		],
	],

    'category' => [
		'title' => 'Category Management',
        'category'=>'Category',
        'list-title'=>'Category List',
        'add' 	=> 'Add New',
		'fields' => [
			'name' => 'Name',
            'total_product' => 'Total Item',
            'created_at'=>'Created At',
            'add' 	=> 'Add New Category',
            'edit' => 'Edit Category',
		],
	],

    'product-management' => [
		'title' => 'Item Management',
		'fields' => [
			'add' => 'Add New',
			'list' => 'Item List',
		],
	],

	'product' => [
		'title' => 'Items',
        'product' => 'Item',
        'select_item'  => 'Select Item',
        'select_category'  => 'Select Category',
		'fields' => [
			'p_name' 				=> 'Product Name',
			'name' 				=> 'Item Name',
			'price' 			=> 'Purchase Price',
			'descripation' 		=> 'Descripation',
			'image' 			=> 'Image',
			'choose_file' 		=> 'Choose product image...',
			'images' 			=> 'Item Images',
			'product-category' 	=> 'Item Category',
            'category_name' 	=> 'Category Name',
            'order_count' 	=> 'No. Of Invoice',
            'from_product' 	=> 'From Item',
            'to_product' 	=> 'To Item',
            'created_at'=>'Created At',
            'add' 	=> 'Add New Item',
            'merge' 	=> 'Merge Item',
            'edit' => 'Edit Category',
            'list' 	=> 'Item List',
		],
	],

	'product2' => [
		'title' => 'Products',
		'fields' => [
			'name' 				=> 'Product Name',
			'print_name' 		=> 'Print Name',
			'product_type' 		=> 'Product Type',
			'group_type' 		=> 'Group Type',
			'unit_type'			=> 'Unit Type',
			'extra_option' 		=> 'Extra Option',	
			'measurement_type'	=> 'Measurement Type',			
			'height_h'			=> 'H',
			'length_l'			=> 'L',
			'width_w'			=> 'W',
			'height'			=> 'Height',
			'length'			=> 'Length',
			'width'				=> 'Width',			
			'is_sub_product'	=> 'Is Sub Product',			
			'price' 			=> 'Purchase Price',
			'sale_price' 		=> 'Estimate Price',
			'min_sale_price'	=> 'Min. Sale Price',
			'wholesaler_price' 	=> 'Wholesaler price',
			'retailer_price' 	=> 'Retailer price',
			'descripation' 		=> 'Descripation',
			'image' 			=> 'Image',
			'choose_file' 		=> 'Choose product image...',
			'images' 			=> 'Product Images',
			'product-category' 	=> 'Product Category',
		],
	],

    'order-management' => [
		'title' 	=> 'Invoice management',
		'fields' 	=> [
			'add' 	=> 'New Invoice',
			'list' 	=> 'Invoice List',
		],
	],

	'order'  => [
		'title'  => 'Orders',
		'order'  => 'Order',
        'invoice'  => 'Invoice',
        'recycle'  => 'Recycle',
        'create_new_order' => 'Create New Order',
        'new_order'  => 'New Order',
        'edit_order' => 'Edit Order',
        'share_invoice'  => 'Share Invoice',
        'list'=>'Order List',
		'fields' => [
            'select_customer'=>'Select Party',
            'select_product'=>'Select Item',
			'placeholder_search' => 'Search by product name ....',
			'order_id' 		=> 'Order Id',
			'customer' 		=> 'Party',
			'customer_name'	=> 'Party Name',
			'product' 		=> 'Item',
			'total_products'=> 'No of products',
			'product_name' 	=> 'Item Name',
            'product_id' 	=> 'Item Name',
			'products' 		=> 'Items',
			'quantity' 		=> 'Quantity',
			'price' 		=> 'Price',
            'amount' 		=> 'Amount',
			'sub_total'		=> 'Amount',
            'sub_total_amount'	=> 'Sub Total',
			'entry_date'	=> 'Entry Date',
			'total'			=> 'Total',
			'total_amount'	=> 'Total Amount',
			'order_type'	=> 'Order Type',
			'total_price'	=> 'Total Price',
            'thaila'	    => 'Thaila',
            'round_off'	    => 'Round Off',
			'grand_total'	=> 'GRAND TOTAL',
			'invoice_date'  => 'Invoice Date',
			'invoice_number'=> 'Invoice Number',
			'address'		=> 'City',
			'phone_number'	=> 'Phone Number',
			'email_address' => 'Email Address',
			'order_note'  	=> 'Notes',
			'date' => 'Date',
            'duration' => 'Duration',
			'sno'=>'SNo.',
			'number_of_item'=>'Number of item',
            'created_at'=>'Created At',
            'deleted_at'=>'Deleted At',
            'deleted_by'=>'Deleted By',
            'add' 	=> 'Add New',
            'edit' => 'Edit Invoice',
            'from_date' => 'From Date',
			'to_date' => 'To Date',
		],
	],

	'reports' => [
		'title' => 'Report',
        'order' => 'Order',
        'order_sell_record' => 'Invoice Sell Record',
        'no_of_order_sold' => 'No of Invoice Sold',
        'yearly' => 'Yearly',
        'monthly' => 'Monthly',
        'weekly' => 'Weekly',
        'daily' => 'Daily',
        'total_order' => 'Total Order',
        'total_product' => 'Total Item',
        'total_quantity'=> 'Total Quantity',
        'total_customer' => 'Total Party',
        'no_of_devices' => 'No. of Devices',
        'today' => 'Today',
        '7days' => '7 Days',
        '30days' => '30 Days',
        'customer' => 'Party',
        'total_amount'	=> 'Total Amount',
        'total_order_amount'	=> 'Total Sell Amount',
        'sale_amount'	=> 'Sale Amount',
        'sale_percent'	=> 'Sale Percentage',
        'products' => 'Items',
        'title' => 'Report',
	],

	'report-management' => [
		'title' 	=> 'Report Management',
		'fields' 	=> [
			'customer_report' => 'Party Report',
            'category_report' => 'Category Report',
            'product_report' => 'Item Report',
			'list' 			  => 'List All Reports',
		],
	],

    'modified-management' => [
		'title' 	=> 'Modified Management',
		'fields' 	=> [
            'customer_modified' => 'Modified Party',
            'product_modified' => 'Modified Item',
		],
	],

	'master-management' => [
		'title' 	=> 'Master Management',
	],

    'settings' => [
		'title' 	=> 'Settings',
        'manage_settings'=>'Manage Settings'
	],

    'backup' => [
		'title' 	=> 'Backup',
        'backup-management' => 'Database Backup Management',
	],


	'brand_master'  => [
		'title'  => 'Brand Master',
		'fields' 	=> [
			'name' => 'Name',
		],
	],

	'group_master'  => [
		'title'  => 'Group Master',
		'add'  => 'Add Group',
		'fields' 	=> [
			'name' => 'Name',
		],
	],
	'area_master'  => [
		'title'  => 'Area Master',
		'add'  => 'Add Area',
		'fields' 	=> [
			'name' => 'Area Name',
			'address' => 'Address',
		],
	],
	'category_master'  => [
		'title'  => 'Categories',
		'fields' 	=> [
			'name' => 'Name',
		],
	],
	'logActivities'  => [
		'title'  => 'Log Activities',
		'fields' 	=> [
			'name' => 'Staff Name',
			'subject' => 'Subject',
			'url' => 'Url',
			'method' => 'Method',
			'agent' => 'User Browser',
		],
	],


	'report'  => [
		'title'  => 'Reports',
		'report_for_customer'  => 'Report for customer',
		'customer_wise_report'  => 'Party wise report',
		'filter_customer_list'  => 'Filter customer list',
		'filter_area_list'      => 'Filter area list',
	],



    'qa_company_name' 	=> 'Kanak Bangles',
	'qa_save_invoice' 	=> 'Save Invoice',
    'qa_temp_save_invoice' 	=> 'Save Temporary Invoice',
    'qa_disconnected' => 'Disconnected',
    'qa_connected' => 'Connected',
    'qa_print_invoice' 	=> 'Print Invoice',
	'qa_sr_no' 	=> 'Sr.No.',
	'qa_create' => 'Create',
	'qa_save' => 'Save',
    'qa_copy' => 'Copy',
	'qa_edit' => 'Edit',
	'qa_restore' => 'Restore',
	'qa_permadel' => 'Delete Permanently',
	'qa_all' => 'All',
	'qa_trash' => 'Trash',
	'qa_view' => 'View',
    'qa_download' => 'Download',
	'qa_update' => 'Update',
	'qa_list' => 'List',
	'qa_cancel' => 'Cancel',
	'qa_no_entries_in_table' => 'No entries in table',
	'qa_custom_controller_index' => 'Custom controller index.',
	'qa_logout' => 'Logout',
	'qa_add_new' => 'Add new',
	'qa_are_you_sure' => 'Are you sure?',
	'qa_dashboard' => 'Dashboard',
	'qa_delete' => 'Delete',
    'qa_approve' => 'Approve',
	'qa_delete_selected' => 'Delete selected',
	'qa_category' => 'Category',
	'qa_categories' => 'Categories',
	'qa_administrator_can_create_other_users' => 'Administrator (can create other users)',
	'qa_simple_user' => 'Simple user',
	'qa_title' => 'Title',
	'qa_roles' => 'Roles',
	'qa_role' => 'Role',
	'qa_user_management' => 'User management',
	'qa_users' => 'Users',
	'qa_user' => 'User',
	'qa_name' => 'Name',
	'qa_email' => 'Email',
    'qa_username' => 'Username',
	'qa_password' => 'Password',
	'qa_remember_token' => 'Remember token',
	'qa_permissions' => 'Permissions',
	'qa_user_actions' => 'User actions',
	'qa_action' => 'Action',
	'qa_action_model' => 'Action model',
	'qa_action_id' => 'Action id',
	'qa_time' => 'Time',
	'qa_reports' => 'Reports',
	'qa_entry_date' => 'Entry date',
	'qa_amount' => 'Amount',
	'qa_income_categories' => 'Income categories',
	'qa_monthly_report' => 'Monthly report',
	'qa_companies' => 'Companies',
	'qa_address' => 'City',
	'qa_website' => 'Website',
	'qa_company' => 'Company',
	'qa_first_name' => 'First name',
	'qa_last_name' => 'Last name',
	'qa_phone' => 'Phone',
	'qa_phone1' => 'Phone 1',
	'qa_phone2' => 'Phone 2',
	'qa_skype' => 'Skype',
	'qa_photo' => 'Photo (max 8mb)',
	'qa_category_name' => 'Category name',
	'qa_product_management' => 'Item management',
	'qa_products' => 'Items',
	'qa_product_name' => 'Item name',
	'qa_price' => 'Price',
	'qa_status' => 'Status',
	'qa_attachment' => 'Attachment',
	'qa_serial_number' => 'Serial number',
	'qa_created_at' => 'Created at',
	'qa_updated_at' => 'Updated at',
	'qa_deleted_at' => 'Deleted at',
	'qa_notifications' => 'Notifications',
	'qa_notify_user' => 'Notify User',
	'qa_when_crud' => 'When CRUD',
	'qa_create_new_notification' => 'Create new Notification',
	'qa_messages' => 'Messages',
	'qa_you_have_no_messages' => 'You have no messages.',
	'qa_all_messages' => 'All Messages',
	'qa_new_message' => 'New message',
	'qa_outbox' => 'Outbox',
	'qa_inbox' => 'Inbox',
	'qa_recipient' => 'Recipient',
	'qa_subject' => 'Subject',
	'qa_message' => 'Message',
	'qa_send' => 'Send',
	'qa_reply' => 'Reply',
	'qa_client_management' => 'Party management',
	'qa_client_management_settings' => 'Party management settings',
	'qa_country' => 'Country',
	'qa_client_status' => 'Party status',
	'qa_clients' => 'Parties',
	'qa_file' => 'File',
	'qa_client' => 'Party',
	'qa_start_date' => 'Start date',
	'qa_current_password' => 'Current password',
	'qa_new_password' => 'New password',
    'qa_confirm_password' => 'Confirm password',
	'qa_password_confirm' => 'New password confirmation',
	'qa_dashboard_text' => 'You are logged in!',
	'qa_forgot_password' => 'Forgot Password?',
    'qa_submit' => 'Submit',
    'qa_reset' => 'Reset',
	'qa_remember_me' => 'Remember me',
	'qa_login' => 'Login',
	'qa_change_password' => 'Change password',
	'qa_csv' => 'CSV',
	'qa_print' => 'Print',
    'qa_share' => 'Share',
	'qa_excel' => 'Excel',
	'qa_copy' => 'Copy',
    'qa_merge' => 'Merge',
	'qa_colvis' => 'Column visibility',
	'qa_pdf' => 'PDF',
	'qa_reset_password' => 'Reset password',
    'qa_otp_line' => 'Please Enter Your Registered Email',
    'qa_reset_password_subject'=>'Reset Password Notification',
	'qa_reset_password_woops' => 'Whoops! Something went wrong.',
    'qa_email_verify_first'=> 'You need to verify your email address first.',
    'qa_invalid_username'=> 'Please Enter Valid Username !',
    'qa_invalid_email'=> 'Invalid Email!',
	'qa_email_line1' => 'You are receiving this email because we received a password reset request for your account.',
	'qa_email_line2' => 'If you did not request a password reset, no further action is required.',
	'qa_email_greet' => 'Hello',
	'qa_email_regards' => 'Regards',
    'qa_login_success'     => 'You have logged in successfully!',
	'qa_confirm_password' => 'Confirm password',
	'qa_if_you_are_having_trouble' => 'If you’re having trouble clicking the',
	'qa_copy_paste_url_bellow' => 'button, copy and paste the URL below into your web browser:',
	'qa_please_select_products' => 'Please search/select products ....',
	'qa_please_select' => 'Please select',
	'qa_please_select_customer' => 'Please select customer',
	'qa_register' => 'Register',
	'qa_registration' => 'Registration',
	'qa_not_approved_title' => 'You are not approved',
	'qa_not_approved_p' => 'Your account is still not approved by administrator. Please, be patient and try again later.',
	'qa_there_were_problems_with_input' => 'There were problems with input',
	'qa_whoops' => 'Whoops!',
	'qa_file_contains_header_row' => 'File contains header row?',
	'qa_csvImport' => 'CSV Import',
	'qa_csv_file_to_import' => 'CSV file to import',
	'qa_parse_csv' => 'Parse CSV',
	'qa_import_data' => 'Import data',
	'qa_imported_rows_to_table' => 'Imported :rows rows to :table table',
	'qa_subscription-billing' => 'Subscriptions',
	'qa_subscription-payments' => 'Payments',
	'qa_basic_crm' => 'Basic CRM',
	'qa_customers' => 'Parties',
	'qa_customer' => 'Party',
	'qa_select_all' => 'Select all',
	'qa_deselect_all' => 'Deselect all',
	'qa_team-management' => 'Teams',
	'qa_team-management-singular' => 'Team',
	'quickadmin_title' => 'Dashboard Widgets',
	'qa_filter' => 'Filter',
	'qa_debit' => 'Debit',
	'qa_credit' => 'Credit',
	'estimate_number'=> 'Estimate Number',
	'estimate'	=> 'Estimate',
	'bill_to'	=> 'Bill To',
	'type'	=> 'Type',
	'date'	=> 'Date',
    'qa_action' => 'Actions',
    'qa_sn'=>'Sn.',
    'qa_no_record'=>'No Record Found!',
    'qa_record_found'=>'Record Found!',
    'thaila' => 'थैला',


];
