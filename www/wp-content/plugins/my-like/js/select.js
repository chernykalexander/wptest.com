/**
 * Adds a button to select all / de-select all hierarchy terms under taxonomy hierarchy metabox
 */
 

(function( $ ) {
	
	$(document).ready(function() {
		
		$( '#cat_selectall' ).click( function() {
			console.log( 'Hello, world!' );
		});


		// $( '#cat_selectall' ).toggle(function(){
		//     $( '[name^=my_like_options[cat_]' ).attr( 'checked', 'checked' );
		//     $( this ).val( 'uncheck all' );
		// },function(){
		//     $('input:checkbox').removeAttr('checked');
		//     $(this).val('check all');        
		// })



		// //add buttons
		// $(".categorydiv").each( function() {
			
		// 	var tax_id = $(this).attr('id');
		// 	var tax_name = tax_id.replace("taxonomy-", "");
		// 	$('#'+ tax_name +'-adder')
		// 		.prepend('<p><input type="button" data-tsl-tax="'+ tax_name +'" class="button tsl-select" value="'+ labels.select +'"><input type="button" data-tsl-tax="'+ tax_name +'" class="button tsl-deselect" value="'+ labels.deselect +'"></p>');
			
		// });
		
		// //add trigger to Select all
		// $(".tsl-select").click( function() {
		// 	var tax = $(this).attr('data-tsl-tax');
		// 	$('#' + tax + 'checklist').find("input[type='checkbox']").prop('checked', true);
			
		// });
		
		// //add trigger to deSelect all
		// $(".tsl-deselect").click( function() {
		// 	var tax = $(this).attr('data-tsl-tax');
		// 	$('#' + tax + 'checklist').find("input[type='checkbox']").prop('checked', false);
			
		// });
		
	
	}); // document ready end
 
}(jQuery));
