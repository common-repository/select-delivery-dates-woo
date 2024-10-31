jQuery(document).ready(function(){
    jQuery('.ocwdd-inner-block ul.tabs li').click(function(){
        var tab_id = jQuery(this).attr('data-tab');
        jQuery('.ocwdd-inner-block ul.tabs li').removeClass('current');
        jQuery('.ocwdd-inner-block .tab-content').removeClass('current');
        jQuery(this).addClass('current');
        jQuery("#"+tab_id).addClass('current');
    })

    jQuery( "#ocwdd_starting_date" ).datepicker({ minDate: 0,dateFormat : "yy/mm/dd" });
    jQuery( "#ocwdd_ending_date" ).datepicker({ minDate: 0,dateFormat : "yy/mm/dd" });
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = jQuery(".ocwdd_input_fields_wrap"); //Fields wrapper
    var add_button      = jQuery(".add_field_button"); //Add button ID
        
    var x = 1; //initlal text box count
    jQuery(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            jQuery(wrapper).append('<div><input type="text" name="delivery_time_option[]" class="regular-text"/><a href="#" class="remove_field"><img src="'+ocwddDATA.ocwdd_array_img+'/assets/images/remove.png"></a></div>'); //add input box
        }
    });
    jQuery(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        jQuery(this).parent('div').remove(); x--;
    });

    var max_fields = 5;
    var x = 1; //initlal text box count
    jQuery(".add_date_button").click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields && jQuery(".ocwdd_select_date").length != max_fields){ //max input box allowed
            x++; //text box increment
            jQuery('.ocwdd_select_multiple').append('<div><input type="text" name="ocwdd_multiple_date[]" class="regular-text ocwdd_select_date" readonly><a href="#" class="remove_date"><img src="'+ocwddDATA.ocwdd_array_img+'/assets/images/remove.png"></a></div>'); //add input box
        }
    });
    jQuery('.ocwdd_select_multiple').on("click",".remove_date", function(e){ //user click on remove text
        e.preventDefault(); 
        jQuery(this).parent('div').remove(); x--;
    });

    jQuery('body').on('focus', '.ocwdd_select_date', function(){
        jQuery(this).datepicker({
            minDate: 0,
            dateFormat: "yy/mm/dd",
        });
    });
    // ocwdd_select_date

    jQuery('#ocwdd_select_user_role').select2({
        ajax: {
                url: ajaxurl,
                dataType: 'json',
                delay: true,
                data: function (params) {
                    return {
                        q: params.term,
                        action: 'ocwdd_roles_ajax'
                    };
                },
                processResults: function( data ) {
                var options = [];
                if ( data ) {
 
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[0], text: text[1]} );
                    });
 
                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 0
    });

    if(jQuery(".ocwdd_display_for_u_role").is(":checked")){ 
        jQuery(".ocwdd_display_select_user").removeClass('hide_sec');
    }else{
        jQuery(".ocwdd_display_select_user").addClass('hide_sec');
    }
    jQuery(".ocwdd_display_for_u_role").click(function() {
        if(jQuery(this).is(":checked")) {
            jQuery(".ocwdd_display_select_user").removeClass('hide_sec');
        } else {
            jQuery(".ocwdd_display_select_user").addClass('hide_sec');
        }
    });

    if(jQuery(".ocwdd_ed_select_offday").is(":checked")){ 
        jQuery(".ocwdd_display_select_offday").removeClass('hide_sec');
    }else{
        jQuery(".ocwdd_display_select_offday").addClass('hide_sec');
    }
    jQuery(".ocwdd_ed_select_offday").click(function() {
        if(jQuery(this).is(":checked")) {
            jQuery(".ocwdd_display_select_offday").removeClass('hide_sec');
        } else {
            jQuery(".ocwdd_display_select_offday").addClass('hide_sec');
        }
    });

})