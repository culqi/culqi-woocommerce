(function ($) {

	const FullCulqi = {

		CPTs: null,

		/**
		 * Start the engine.
		 *
		 * @since 2.0.0
		 */
		init: function () {
			//console.log('init');
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

			FullCulqi.CPTs = fullculqi_vars.delete_cpts;
		},

		/**
		 * Element bindings.
		 *
		 * @since 2.0.0
		 */
		bindUIActions: function () {
			
			$('#fullculqi_delete_all').on('click', function(e) {
				e.preventDefault();

				if( ! confirm( fullculqi_vars.text_confirm ) )
					return;

				FullCulqi.deleteCPTs();
			});
			
		},
		/**
		 * Delete Posts in CPTs
		 * @return mixed
		 */
		deleteCPTs: function() {

			// Loading
			const cpt_name = FullCulqi.setCPTLoading();

			// Delete from the first element
			FullCulqi.deletePosts( cpt_name );

		},
		deletePosts: function( cpt_name ) {
			//console.log('set');
			$.ajax({
				url 		: fullculqi_vars.url_ajax,
				type 		: 'POST',
				dataType	: 'json',
				data 		: {
					action: 'delete_' + cpt_name,
					wpnonce : fullculqi_vars.nonce
				},
				success: function( response ) {

					$( document.body ).trigger( 'fullculqi.settings.success', [ cpt_name, response] );
					
					if( response.success ) {

						const cpt_success = fullculqi_vars.delete_success.replace( '%s', cpt_name );

						$('#fullculqi_delete_all_notify').find( 'div.' + cpt_name ).html( fullculqi_vars.img_success + ' ' + cpt_success );
					} else
						$('#fullculqi_delete_all_notify').find( 'div.' + cpt_name ).html( fullculqi_vars.img_failure + ' ' + fullculqi_vars.delete_error );


					if( FullCulqi.CPTs.length > 0 )
						FullCulqi.deleteCPTs();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					
					console.log(jqXHR);
					console.log(textStatus);
					console.log(errorThrown);
					
					$( '#fullculqi_delete_all_notify' ).find( 'div.' + cpt_name ).html( fullculqi_vars.img_failure + ' ' + fullculqi_vars.delete_error );

					$( document.body ).trigger('fullculqi.settings.error', [ cpt_name, jqXHR, textStatus, errorThrown ] );
				}
			});

		},
		setCPTLoading: function() {
			
			const cpt_name = FullCulqi.CPTs.shift(),
				cpt_loading = fullculqi_vars.delete_loading.replace( '%s', cpt_name );

			// Loading
			$('#fullculqi_delete_all_notify').append( '<div class="' + cpt_name + '">' + fullculqi_vars.img_loading + ' ' + cpt_loading + '</div>' );

			return cpt_name;
		}
	};

	FullCulqi.init();
	// Add to global scope.
	window.fullculqi = FullCulqi;
})(jQuery);