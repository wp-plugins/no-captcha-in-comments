
/*
 plugin
No spam in comments. No captcha.
*/

(function($) {

	function ncc_init() {
		$('.ncc_group').hide(); // hide inputs from users

		var answer = $('.ncc_group .ncc_control-a').val(); // get answer
		$('.ncc_group-q .ncc_control-q').val(answer); // set answer into other input instead of user
		$('.ncc_group-e .ncc_control-e').val(''); // clear value of the empty input because some themes are adding some value for all inputs

		var current_date = new Date();
		var current_year = current_date.getFullYear();
		var ncc_dynamic_control = '<input type="hidden" name="ncc_spm-q" class="ncc_control ncc_control-q" value="'+current_year+'" />';

		$.each($('#comments form'), function(index, commentForm) { // add input for every comment form if there are more than 1 form
			if ($(commentForm).find('.ncc_control-q').length == 0) {
				$(commentForm).append(ncc_dynamic_control);
			}
		});

		$.each($('#respond form'), function(index, commentForm) { // add input for every comment form if there are more than 1 form
			if ($(commentForm).find('.ncc_control-q').length == 0) {
				$(commentForm).append(ncc_dynamic_control);
			}
		});

		$.each($('form#commentform'), function(index, commentForm) { // add input for every comment form if there are more than 1 form
			if ($(commentForm).find('.ncc_control-q').length == 0) {
				$(commentForm).append(ncc_dynamic_control);
			}
		});
	}
function ncc_counter_func(){
    var ncc_count=$('#ncc_count').val();
    var ncc_counter=setInterval(ncc_timer,1000);
    function ncc_timer(){
        ncc_count=ncc_count-1;
        if(ncc_count==0)
        {

           $('#ncc_count').val("");     
            clearInterval(ncc_counter);
            
            return;
        }
    } 
}
	$(document).ready(function() {
		ncc_init();
                ncc_counter_func();
	});

	

})(jQuery);
