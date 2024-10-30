jQuery(function($){

	$('.load_more_video').click(function(){
		var this_var = $(this);
		$this_var = $(this);

		this_var.text(translations.loading);

		if (!current_page)
			var current_page = $(this).data("current-page");



		var max_pages = $(this).data("max-pages");

		var data = {
			'action': 'loadmore',
			'query': $(this).data("query"),
			'page' : current_page
		};

		
		$.ajax({
			url : ajaxurl,
			data : data,
			type : 'POST',
			success : function(data){
				if( data ) {
					this_var.text(translations.load_more).before(data);
					
					current_page++;
					$this_var.data('current-page', current_page);

					if (current_page == max_pages) this_var.remove();
					
				} else {
					this_var.remove(); // If last page - hide button
				}
				lis_lvg_inline_link();
			}
		});

	});

});