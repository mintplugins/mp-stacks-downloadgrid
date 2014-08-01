jQuery(document).ready(function($){
	
	//Ajax load more posts
	$( document ).on( 'click', '.mp-stacks-downloadgrid-load-more-button', function(event){
		
		event.preventDefault();
		
		//Change the message on the Load More button to say "Loading..."
		$(this).html(mp_stacks_downloadgrid_vars.loading_text);
		
		// Use ajax to load more posts
		var postData = {
			action: 'mp_stacks_downloadgrid_load_more',
			mp_stacks_downloadgrid_post_id: $(this).attr( 'mp_post_id' ),
			mp_stacks_downloadgrid_offset: $(this).attr( 'mp_brick_offset' ),
			mp_stacks_downloadgrid_counter: $(this).attr( 'mp_stacks_downloadgrid_counter' ),
		}
		
		var the_downloadgrid_container = $(this).parent();
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