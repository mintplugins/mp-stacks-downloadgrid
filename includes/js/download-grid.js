jQuery(document).ready(function($){
	
	//$('.mp-stacks-download-grid-item').css('opacity', 0);
	//$('.mp-stacks-download-grid-item').velocity( 'transition.flipXIn', { stagger: 150 } );
	
	//Ajax load more downloads
	$( document ).on( 'click', '.mp-stacks-download-grid-load-more-button', function(event){
		
		event.preventDefault();
		
		// Use ajax to load more posts
		var postData = {
			action: 'mp_stacks_download_grid_load_more',
			mp_stacks_download_grid_post_id: $(this).attr( 'mp_post_id' ),
			mp_stacks_download_grid_offset: $(this).attr( 'mp_brick_offset' ),
			mp_stacks_download_grid_counter: $(this).attr( 'mp_stacks_download_grid_counter' ),
		}
		
		var the_download_grid_container = $(this).parent();
		var the_button = $(this);
		
		//Ajax load more posts
		$.ajax({
			type: "POST",
			data: postData,
			url: mp_stacks_frontend_vars.ajaxurl,
			success: function (response) {
				
				the_button.replaceWith(response);
			
			}
		}).fail(function (data) {
			console.log(data);
		});	
		
	});
	
}); 