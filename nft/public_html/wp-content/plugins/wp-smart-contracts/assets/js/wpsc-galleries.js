jQuery( document ).ready(function($) {

    if (!jQuery("#wpsc-input-galleries").val()) {
        jQuery("#wpsc-term-in-empty").fadeIn();
    }

    // Get out
    jQuery(document).on('click', '.wpsc-term-in', function() {
        
        // remove the element from the input
        const id = jQuery(this).attr('data-id');
        const name = jQuery(this).attr('data-name');
        const confirm = jQuery(this).attr('data-confirm');
        if (!confirm || window.confirm("Are you sure you want to delete this Gallery?") == true) {

            const str = jQuery("#wpsc-input-galleries").val();
            var arr = str.split(',');
            if (Array.isArray(arr)) {
                var index = arr.indexOf(id);
                if (index !== -1) {
                    arr.splice(index, 1);
                }
                jQuery("#wpsc-input-galleries").val(arr.join(","));
            }

            if (!jQuery("#wpsc-input-galleries").val()) {
                jQuery("#wpsc-term-in-empty").fadeIn();
            }
            
            // remove from the table
            jQuery("#wpsc-term-in-" + id).remove();
    
            // add the element to the out table
            jQuery('#wpsc-table-out').append('<p id="wpsc-term-out-' + id + '" class="ui label"><span class="wpsc-term-out" data-id="' + id + '" data-name="' + name + '">' + name  + ' <span class="wpsc-id">(' + id + ')</span></span></p>');
        
        }
    });

    // Get in
    jQuery(document).on('click', '.wpsc-term-out', function() {

        jQuery("#wpsc-term-in-empty").hide();

        // add the element to the input
        const id = jQuery(this).attr('data-id');
        const name = jQuery(this).attr('data-name');
        const str = jQuery("#wpsc-input-galleries").val();
        var arr = str.split(',');
        if (Array.isArray(arr)) {
            arr.push(id);
            jQuery("#wpsc-input-galleries").val(arr.join(","));
        }

        // remove from the table
        jQuery("#wpsc-term-out-" + id).remove();

        // add the element to the in table
        jQuery('#wpsc-table-in').append('<p id="wpsc-term-in-' + id + '" class="ui label"><span class="wpsc-term-in" data-id="' + id + '" data-name="' + name + '"></span>' + name + ' <span class="wpsc-id">(' + id + ')</span></p>');

    });

});
