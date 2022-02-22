jQuery(document).ready(function(){
	
	jQuery('li.product .button').each(function () {
		
		jQuery(this).closest('li.product').find('.phoeniixx_short_btn').append(this);

	});
		
	jQuery('.phoen_gridlist_toggle #phoen_grid').click(function(){
		jQuery( '.product .phoeniixx_short_desc' ).hide();
		jQuery( 'ul.products' ).removeClass('phoen_list');							
		jQuery( 'ul.products' ).addClass('phoen_grid');
		jQuery.cookie('phoen_gridcookie','phoen_grid', { path: '/' });
		jQuery( '.phoen_gridlist_toggle a').removeClass('active'); 
		jQuery(this).addClass( 'active' );
		
	});
	
	jQuery('.phoen_gridlist_toggle #phoen_list').click(function(){
		jQuery( '.product .phoeniixx_short_desc' ).show();
		jQuery( 'ul.products' ).removeClass('phoen_grid');
		jQuery( 'ul.products' ).addClass('phoen_list');
		jQuery.cookie('phoen_gridcookie','phoen_list', { path: '/' });
		jQuery( '.phoen_gridlist_toggle a').removeClass('active'); 
		jQuery(this).addClass( 'active' );
		
	});
	
	if (jQuery.cookie('phoen_gridcookie')) {
		
        jQuery('ul.products, .phoen_gridlist_toggle').addClass(jQuery.cookie('phoen_gridcookie'));
   
	}
	
	if (jQuery.cookie('phoen_gridcookie')) {
		
        jQuery('ul.products, #').addClass(jQuery.cookie('gridcookie'));
    
	}
	
	// add active class to grid if display type is grid  
    if (jQuery.cookie('phoen_gridcookie') == 'phoen_grid') {
        //jQuery('.phoeniixx_short_desc' ).hide();
		jQuery('.phoen_gridlist_toggle #phoen_grid').addClass('active');
        jQuery('.phoen_gridlist_toggle #phoen_list').removeClass('active');
    }
	
	// add active class to list if display type is list  
    if (jQuery.cookie('phoen_gridcookie') == 'phoen_list') {
		//jQuery('.phoeniixx_short_desc' ).show();
        jQuery('.phoen_gridlist_toggle #phoen_list').addClass('active');
        jQuery('.phoen_gridlist_toggle #phoen_grid').removeClass('active');
    }

});