(function ($) {


	const FullCulqi = {

		/**
		 * Global variables
		 * @type Integer
		 */
		record: 50,

		/**
		 * Start the engine.
		 *
		 * @since 2.0.0
		 */
		init: function () {

			// Document ready
			$(document).ready(FullCulqi.ready);

			// Page load
			$(window).on('load', FullCulqi.load);
		},
		/**
		 * Document ready.
		 *
		 * @since 2.0.0
		 */
		ready: function () {
			// Execute
			FullCulqi.executeUIActions();
		},
		/**
		 * Page load.
		 *
		 * @since 2.0.0
		 */
		load: function () {
			// Bind all actions.
			FullCulqi.bindUIActions();
		},

		/**
		 * Execute when the page is loaded
		 * @return mixed
		 */
		executeUIActions: function() {

			let $title_action = $('.edit-php .wrap .page-title-action:last');

			if( $title_action.length == 0 )
				$title_action = $('.edit-php .wrap .wp-heading-inline');

			$title_action.after(
				'<a style="display: none" href="" id ="' +
				fullculqi_vars.sync_id +
				'" class="page-title-action" data-record="50">' +
				'<span class="dashicons dashicons-update-alt" style="vertical-align:middle"></span> ' +
				fullculqi_vars.sync_text +
				'</a>' +
				'<span id="' +
				fullculqi_vars.sync_notify +
				'"></span>'
			);
		},

		/**
		 * Element bindings.
		 *
		 * @since 2.0.0
		 */
		bindUIActions: function () {
			
			$('#' + fullculqi_vars.sync_id).on( 'click', function(e) {
				e.preventDefault();

				if( ! confirm( fullculqi_vars.sync_confirm ) )
					return;

				if( typeof $(this).data('record') !== 'undefined' )
					FullCulqi.record = $(this).data('record');

				FullCulqi.syncEntities();
			} );
		},
		/**
		 * Sync Start
		 * @return mixed
		 */
		syncEntities: function( after_id = '' ) {
			
			// Loading
			$('#' + fullculqi_vars.sync_notify).html( fullculqi_vars.img_loading + ' ' + fullculqi_vars.sync_loading );

			$.ajax({
				url 		: fullculqi_vars.url_ajax,
				type 		: 'POST',
				dataType	: 'json',
				data 		: {
					action : 'sync_' + fullculqi_vars.sync_id,
					record : FullCulqi.record,
					after_id : after_id,
					wpnonce : fullculqi_vars.nonce
				},
				success: function( response ) {

					$( document.body ).trigger( 'fullculqi.metaboxes.success', [ fullculqi_vars.sync_id, response] );
					
					if( response.success ) {

						if( response.data.after_id == null ) {
							$('#' + fullculqi_vars.sync_notify).html( fullculqi_vars.img_success + ' ' + fullculqi_vars.sync_success );
							location.reload();
						} else {
							$('#' + fullculqi_vars.sync_notify).html( fullculqi_vars.img_success + ' ' + fullculqi_vars.sync_continue );
							setTimeout(function(){ FullCulqi.syncEntities( response.data.after_id ); }, 700);
						}
					
					} else {
						
						$('#' + fullculqi_vars.sync_notify).html( fullculqi_vars.img_failure + ' ' + response.data );
					}			
				},
				error: function(jqXHR, textStatus, errorThrown) {
					
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
					
					$('#' + fullculqi_vars.sync_notify).html( fullculqi_vars.img_failure + ' ' + fullculqi_vars.sync_failure );

					$( document.body ).trigger('fullculqi.metaboxes..error', [ fullculqi_vars.sync_id, jqXHR, textStatus, errorThrown ] );
				}
			});
		}
	};

	FullCulqi.init();
	// Add to global scope.
	window.fullculqi = FullCulqi;
})(jQuery);