jQuery(document).ready(function(){'use strict';

	function data_export_form(e){
			var postdata = jQuery(this).serializeArray();
			var formURL = jQuery(this).attr("action");
			jQuery.ajax(
			{
				url : formURL,
				type: "POST",
				data : postdata,
				success:function(data, textStatus, jqXHR) {
					//jQuery('#profile-msg').modal();
				},
				error: function(jqXHR, textStatus, errorThrown){
					jQuery("#simple-msg-err").html('<span style="color:red">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</span>');
				}
			});
			e.preventDefault();	//STOP default action
			e.unbind(); //Remove a previously-attached event handler
		}
	jQuery("#data-export-form").submit(data_export_form); //SUBMIT FORM

	console.log("hello lol");

	});