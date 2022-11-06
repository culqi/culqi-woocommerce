(function ($) {

	const FullCulqi_Charges = {

		/**
		 * Start the engine.
		 *
		 * @since 2.0.0
		 */
		init: function () {

			// Document ready
			$(document).ready(FullCulqi_Charges.ready);

			// Page load
			$(window).on('load', FullCulqi_Charges.load);
		},
		/**
		 * Document ready.
		 *
		 * @since 2.0.0
		 */
		ready: function () {
			// Execute
			FullCulqi_Charges.executeUIActions();
		},
		/**
		 * Page load.
		 *
		 * @since 2.0.0
		 */
		load: function () {
			// Bind all actions.
			FullCulqi_Charges.bindUIActions();
		},

		/**
		 * Execute when the page is loaded
		 * @return mixed
		 */
		executeUIActions: function() {

		},

		/**
		 * Element bindings.
		 *
		 * @since 2.0.0
		 */
		bindUIActions: function () {
			
			$( '#culqi_refunds' ).on( 'click', function(e) {
				e.preventDefault();

				if( ! confirm( fullculqi_charges_vars.refund_confirm ) )
					return;

				const post_id = $(this).data('post');

				FullCulqi_Charges.applyRefund( post_id );
			} );
		},
		/**
		 * Sync Start
		 * @return mixed
		 */
		applyRefund: function( post_id = 0 ) {
			
			// Loading
			$( '#culqi_refunds_notify' ).html( fullculqi_charges_vars.img_loading + ' ' + fullculqi_charges_vars.refund_loading );

			$.ajax({
				url 		: fullculqi_charges_vars.url_ajax,
				type 		: 'POST',
				dataType	: 'json',
				data 		: {
					action: 'create_culqi_refund',
					post_id: post_id,
					wpnonce : fullculqi_charges_vars.nonce
				},
				success: function( response ) {

					$( document.body ).trigger( 'fullculqi.refunds.success', [ fullculqi_charges_vars, response] );
					
					if( response.success ) {

						$( '#culqi_refunds_notify' ).html( fullculqi_charges_vars.img_success + ' ' + fullculqi_charges_vars.refund_success );
						location.reload();
					
					} else {
						
						$('#culqi_refunds_notify' ).html( fullculqi_charges_vars.img_failure + ' ' + response.data );
					}			
				},
				error: function(jqXHR, textStatus, errorThrown) {
					
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
					
					$('#culqi_refunds_notify' ).html( fullculqi_charges_vars.img_failure + ' ' + fullculqi_charges_vars.refund_failure );

					$( document.body ).trigger('fullculqi.refunds.error', [ fullculqi_charges_vars, jqXHR, textStatus, errorThrown ] );
				}
			});
		}
	};
	FullCulqi_Charges.init();
	// Add to global scope.
	window.fullculqi_charges = FullCulqi_Charges;
})(jQuery);
