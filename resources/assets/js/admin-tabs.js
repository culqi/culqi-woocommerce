(function ($) {
	jQuery(document).ready(function(){
		var textTitle = jQuery.trim(jQuery('.wp-heading-inline').html());
		console.log(textTitle);
		if(textTitle=='Culqi Customers'){
			var html = '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">\n' +
				'        <a href="admin.php?page=fullculqi_settings" class="nav-tab">Settings</a>\n' +
				'        <a href="edit.php?post_type=culqi_charges" class="nav-tab">Charges</a>\n' +
				'        <a href="edit.php?post_type=culqi_orders" class="nav-tab">Orders</a>\n' +
				'        <a style="display: none" href="edit.php?post_type=culqi_customers" class="nav-tab nav-tab-active">Customers</a>\n' +
				'        <a href="admin.php?page=fullculqi_webhooks" class="nav-tab">Webhooks</a>\n' +
				'    </nav>';
		}
		if(textTitle=='Culqi Orders'){
			var html = '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">\n' +
				'        <a href="admin.php?page=fullculqi_settings" class="nav-tab">Settings</a>\n' +
				'        <a href="edit.php?post_type=culqi_charges" class="nav-tab">Charges</a>\n' +
				'        <a href="edit.php?post_type=culqi_orders" class="nav-tab nav-tab-active">Orders</a>\n' +
				'        <a style="display: none" href="edit.php?post_type=culqi_customers" class="nav-tab">Customers</a>\n' +
				'        <a href="admin.php?page=fullculqi_webhooks" class="nav-tab">Webhooks</a>\n' +
				'    </nav>';
		}
		if(textTitle=='Culqi Charges'){
			var html = '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">\n' +
				'        <a href="admin.php?page=fullculqi_settings" class="nav-tab">Settings</a>\n' +
				'        <a href="edit.php?post_type=culqi_charges" class="nav-tab nav-tab-active">Charges</a>\n' +
				'        <a href="edit.php?post_type=culqi_orders" class="nav-tab">Orders</a>\n' +
				'        <a style="display: none" href="edit.php?post_type=culqi_customers" class="nav-tab">Customers</a>\n' +
				'        <a href="admin.php?page=fullculqi_webhooks" class="nav-tab">Webhooks</a>\n' +
				'    </nav>';
		}
		jQuery('hr.wp-header-end').before(html);

	})


})(jQuery);