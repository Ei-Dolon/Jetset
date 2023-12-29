function displayMedia(selection) {

    var str_html = '<form id="form_wizard_step_3" action='+arr.php+' method="POST" class="ui form">';

    str_html += '<input type="hidden" name="step" value="4">';

    str_html += '<input type="hidden" name="wpsc-choose-collection-value" value="'+jQuery("#collection_id").val()+'">';
    console.log("Hello", jQuery("#wpsc-owner-address").val());
    str_html += '<input type="hidden" name="wpsc-owner-address" value="'+jQuery("#wpsc-owner-address").val()+'">';

    str_html += '<p style="text-align: center;"><input type="submit" class="ui primary button" value="Save"></p>';
    
    str_html += '<div class="ui raised compact secondary segment"><h4 class="ui dividing header">Mint to address</h4><p>This account will be the owner of all new minted NFT Items.<div class="inline field"><label>Owner Address</label><input type="text" name="wpsc-owner" placeholder="0x..."></div><div id="wpsc_step3_warning" class="ui warning message" style="display: none;">Please enter a valid address to continue</div></div>';
    
    str_html += '<table id="wpsc-bulk-table" class="ui striped celled table"><thead><tr><th>Media</th><th>Quantity</th><th>Title</th><th>Description</th><th>Taxonomy</th><th>Attributes</th></tr></thead><tbody>';
    
    var taxonomies = '<div class="grouped fields"><div class="field">';
    
    arr.taxonomies.forEach(element => {
        taxonomies += '<div class="ui checkbox"><input name="wpsc-tax-{id}-'+element.term_id+'" type="checkbox"><label>'+element.name+'</label></div><br/>';
    });
    
    taxonomies += '</div></div>';

    jQuery("#step2-menu-item").html(selection.length + ' items selected<br>[<a href="#" class="select-media-files">Reset</a>]');
    jQuery("#step2-menu").removeClass("active");
    jQuery("#step3-menu").addClass("active");
    jQuery(".step3-hide").hide();
    jQuery(".step4-show").fadeIn(500);

    var there_is_a_3d_model = false;

    var class_column = '';

    str_html += '<tr class="active wpsc-small"><td>Check the box to override the entire column with the value of the first row</td>';
    str_html += '<td>';
    str_html += '<div class="field"><div class="ui checkbox"><input id="wpsc-all-qty" name="wpsc-all-qty" type="checkbox"><label class="wpsc-small">Override with the value of the first row</label></div><div class="ui mini info message wpsc-bm-qty-help" style="display: none"><p>The quantity defined in the first row is used for all minted items.</p></div></div>';
    str_html += '</td>';
    str_html += '<td>';
    str_html += '<div class="field"><div class="ui checkbox"><input id="wpsc-all-title" name="wpsc-all-title" type="checkbox"><label class="wpsc-small">Override with the value of the first row</label></div><div class="ui mini info message wpsc-bm-title-help" style="display: none"><p>The title defined in the first row is used for all minted items.</p><p>You can use the tag {id} to be replaced by the NFT ID once minted, for example, the title:</p><p><i>My Amazing Item #{id}</i></p><p>will be replaced with</p><p><i>My Amazing Item #1</p><p>My Amazing Item #2</i></p>etc...</div></div>';
    str_html += '</td>';
    str_html += '<td class="ui form">';
    str_html += '<div class="field"><div class="ui checkbox"><input id="wpsc-all-desc" name="wpsc-all-desc" type="checkbox"><label class="wpsc-small">Override with the value of the first row</label></div><div class="ui mini info message wpsc-bm-desc-help" style="display: none"><p>The description defined in the first row is used for all minted items</p><p>You can use the tag {id} to be replaced by the NFT ID once minted, for example, the description:</p><p><i>This is an Amazing Item #{id}</i></p><p>will be replaced with</p><p><i>This is an Amazing Item #1</p><p>This is an Amazing Item #2</i></p><p>etc...</p></div></div>';
    str_html += '</td>';
    str_html += '<td>';
    str_html += '<div class="field"><div class="ui checkbox"><input id="wpsc-all-tax" name="wpsc-all-tax" type="checkbox"><label class="wpsc-small">Override with the value of the first row</label></div><div class="ui mini info message wpsc-bm-tax-help" style="display: none"><p>The taxonomies defined in the first row is used for all minted items</p></div></div>';
    str_html += '</td>';
    str_html += '<td>';
    str_html += '<div class="field"><div class="ui checkbox"><input id="wpsc-all-atts" name="wpsc-all-atts" type="checkbox"><label class="wpsc-small">Override with the value of the first row</label></div><div class="ui mini info message wpsc-bm-atts-help" style="display: none"><p>The attributes defined in the first row is used for all minted items</p></div></div>';
    str_html += '</td>';
    str_html += '</tr>';

    var first = true;

    selection.map( function( attachment ) {

        str_html += '<tr class="top aligned"><td>';

        if (first) {
          str_html += '<input type="hidden" name="wpsc-first-id" value="'+attachment.id+'">';
          first = false;
        }

        str_html += '<input type="hidden" name="wpsc-mime-'+attachment.id+'" value="'+attachment.mime+'">';
        str_html += '<input type="hidden" name="wpsc-url-'+attachment.id+'" value="'+attachment.url+'">';

        str_html += '</td>';
        str_html += '<td>';
            str_html += '<div class="ui fluid input '+class_column+'qty">';
                str_html += '<input type="number" min="1" onkeypress="return event.charCode >= 48" name="wpsc-qty-'+attachment.id+'" value="1">';
            str_html += '</div>';
        str_html += '</td>';
        str_html += '<td>';
            str_html += '<div class="ui fluid input '+class_column+'title">';
                str_html += '<input type="text" name="wpsc-title-'+attachment.id+'" value="'+attachment.name+'">';
            str_html += '</div>';
        str_html += '</td>';
        str_html += '<td class="ui form">';
            str_html += '<div class="field '+class_column+'desc">';
                str_html += '<textarea rows="3" name="wpsc-desc-'+attachment.id+'"></textarea>';
            str_html += '</div>';
        str_html += '</td>';
        str_html += '<td>';
          str_html += '<div class="ui mini form '+class_column+'tax">';
          str_html += taxonomies.replace(/{id}/gi, attachment.id);
          str_html += '<input type="text" name="wpsc-new-tax-'+attachment.id+'" placeholder="Add new categories, comma separated">';
          str_html += '</div>';
        str_html += '</td>';
        str_html += '<td>';
            str_html += '<div class="ui fluid mini input '+class_column+'atts">';
                str_html += '<input type="text" name="wpsc-atts-'+attachment.id+'" placeholder="Comma separated attributes">';
            str_html += '</div>';
        str_html += '</td>';
        str_html += '</tr>';

        class_column = 'wpsc-bm-';

   });

   str_html += '</tbody></table>';

   str_html += '<p style="text-align: center;"><input type="submit" class="ui primary button" value="Save"></p>';
   
   str_html += '</form>';

   if (there_is_a_3d_model) {
      str_html = '<script type="module" src="'+arr.model_viewer+'"></script><div class="image">' + str_html + '</div>';
   } else {
      str_html = '<div class="image">' + str_html + '</div>';
   }

   jQuery("#wpsc-nft-media-preview-media-real").html(str_html);

}

jQuery(document).ready(function() {

   var custom_file_frame;
   jQuery(document).on('click', '.select-media-files', function(event) {

    event.preventDefault();

    var multiple = true;
    var type_browse = ['video', 'image', 'audio', 'application', 'model/gltf-binary'];

    $this = jQuery(this);
    if (typeof(custom_file_frame)!=="undefined") {
        custom_file_frame.close();
    }
    custom_file_frame = wp.media.frames.customHeader = wp.media({
        title: "Select Media",
        button: {
            text: "Use Media"
        },
        multiple: multiple,
        library: {
            type: type_browse
        }
    });

    custom_file_frame.on('select', function() {
        var selection = custom_file_frame.state().get('selection').toJSON();
        var the_json = [];
        for (let index = 0; index < selection.length; index++) {
            the_json.push({
                id: selection[index].id,
                url: selection[index].url,
                size: selection[index].filesizeHumanReadable,
                name: selection[index].title,
                mime: selection[index].mime
            });
        }
        displayMedia(the_json);
    });
    custom_file_frame.open();
   
   });

   jQuery(document).on('click', '#wpsc-all-qty', function(event) {
     if (jQuery("#wpsc-all-qty:checked").val()=="on") {
      jQuery(".wpsc-bm-qty").fadeOut(500);
      jQuery(".wpsc-bm-qty-help").fadeIn(500);
     } else {
      jQuery(".wpsc-bm-qty").fadeIn(500);
      jQuery(".wpsc-bm-qty-help").fadeOut(500);
     }
   });
   
   jQuery(document).on('click', '#wpsc-all-title', function(event) {
     if (jQuery("#wpsc-all-title:checked").val()=="on") {
      jQuery(".wpsc-bm-title").fadeOut(500);
      jQuery(".wpsc-bm-title-help").fadeIn(500);
     } else {
      jQuery(".wpsc-bm-title").fadeIn(500);
      jQuery(".wpsc-bm-title-help").fadeOut(500);
     }
   });
   
   jQuery(document).on('click', '#wpsc-all-desc', function(event) {
     if (jQuery("#wpsc-all-desc:checked").val()=="on") {
      jQuery(".wpsc-bm-desc").fadeOut(500);
      jQuery(".wpsc-bm-desc-help").fadeIn(500);
     } else {
      jQuery(".wpsc-bm-desc").fadeIn(500);
      jQuery(".wpsc-bm-desc-help").fadeOut(500);
     }
   });

   jQuery(document).on('click', '#wpsc-all-tax', function(event) {
     if (jQuery("#wpsc-all-tax:checked").val()=="on") {
      jQuery(".wpsc-bm-tax").fadeOut(500);
      jQuery(".wpsc-bm-tax-help").fadeIn(500);
     } else {
      jQuery(".wpsc-bm-tax").fadeIn(500);
      jQuery(".wpsc-bm-tax-help").fadeOut(500);
     }
   });
   
   jQuery(document).on('click', '#wpsc-all-atts', function(event) {
    if (jQuery("#wpsc-all-atts:checked").val()=="on") {
      jQuery(".wpsc-bm-atts").fadeOut(500);
      jQuery(".wpsc-bm-atts-help").fadeIn(500);
    } else {
     jQuery(".wpsc-bm-atts").fadeIn(500);
     jQuery(".wpsc-bm-atts-help").fadeOut(500);
    }
  });

});
