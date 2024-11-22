(function ( $ ) {
	"use strict";

	$(function () {


		$('#listeo-fafe-fields-editor').sortable({
			items: '.form_item',
			handle: '.handle',
			cursor: 'move',
			containment: 'parent',
			placeholder: 'my-placeholder',
		    start: function(event, ui) {
		        // Set the initial width of the placeholder to match the helper
	        ui.placeholder.width(ui.item.outerWidth() - 2);
		    },
			/*stop: function(event, ui) {
		        $(".form_item").each(function(i, el){
		        	
		            $(this).find('input').attr('name').replace(/\d+/, $(el).index())
		             
		        });
		    }*/


		});	

		$('.field-options-custom tbody').sortable();


		
		$(".listeo-forms-builder").on('click', '.listeo-fafe-section-move-down', function(event){
			event.preventDefault();
			var section = $(this).parents('.listeo-fafe-row-section');
			var next = $(this).parents('.listeo-fafe-row-section').next();
			section.insertAfter(next);			
		});

		$(".listeo-forms-builder").on('click', '.listeo-fafe-section-move-up', function(event){
			event.preventDefault();
			var section = $(this).parents('.listeo-fafe-row-section');
			var prev = $(this).parents('.listeo-fafe-row-section').prev();
			section.insertBefore(prev);			
		});

		$('#listeo-fafe-forms-editor,#listeo-fafe-forms-editor-adv').sortable({
			items: '.form_item',
			handle: '.handle',
			cursor: 'move',
			containment: 'parent',
			placeholder: 'my-placeholder',
			connectWith: '#listeo-fafe-forms-editor,#listeo-fafe-forms-editor-adv',
			stop: function(event, ui) {
		        $(".form_item").each(function(i, el){
		            $(this).find('.priority_field').val ($(el).index() );
		        });
		    },
		    receive: function (e, ui) {
		        ui.sender.data('copied', true);
		        console.log(ui);
		    }
		});

		function randomIntFromInterval(min,max){
		    return Math.floor(Math.random()*(max-min+1)+min);
		}

		$( ".form-editor-available-elements-container" ).sortable({
			items: '.form_item',
			handle: '.handle',
			connectWith: '.form-editor-container',
			helper: function (e, li) {
				
				if(li.hasClass('form_item_header')) {
					var copy = li.clone();
					var formRowCount = $('#listeo-fafe-forms-editor .form_item').length+25;
					$('.name-container input',copy).val('header'+randomIntFromInterval(20,990));
					$('input',copy).attr('name').replace(/^(\[)\d+(\].+)$/, '$1' + formRowCount + '$2');
					copy.find('input,select').each(function() {
				        var $this = $(this);
				        
				        $this.attr('name', $this.attr('name').replace(/\[(\d+)\]/, '[' + formRowCount + ']'));
				        
				    });
				    formRowCount++;
					this.copyHelper = copy.insertAfter(li);
					 $(this).data('copied', false);
			        return li.clone();
				} else {
					return li.data('copied', true);
				}
		    },
			stop: function(event, ui) {
	
				var copied = $(this).data('copied');
				
		        if (!copied) {
		            this.copyHelper.remove();
		        }

		        this.copyHelper = null;
				$(".form_item").each(function(i, el){
					var i = $(el).index();
					if($(el).parent().hasClass('adv')){
						if($(el).parent().hasClass('panel')){
							$(this).find('.place_hidden').val('panel')
						} else {
							$(this).find('.place_hidden').val('adv')	
						}
						
					} else {
						$(this).find('.place_hidden').val('main')
					}
					if( $(this).find('.priority_field').lenght > 0 ) {
						$(this).find('.priority_field').attr('name').replace(/(\[\d\])/, '[' + $(el).index() + ']'); 	
					}
		        
		    	});
		    }
		});

		$(".listeo-forms-builder,.listeo-forms-builder-right").on('click', '#listeo-show-names', function(){
			$('.name-container').show();
		});

		$('.form-editor-container').on( 'click', '.element_title', function() {

			$(this).next().slideToggle();
		});

	 	$(".listeo-forms-builder,.listeo-form-editor").on('click', '.remove_item', function(event){

 			event.preventDefault();
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().fadeOut(300, function() { $(this).remove(); });
	 		}
 		});
 		
 		$(".field-options-custom").on('click', '.remove_row', function(event){
 			event.preventDefault();
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().fadeOut(300, function() { $(this).remove(); });
	 		}
 		});

	    
		/*fields editor*/
		$('#listeo-fafe-fields-editor, #listeo-fafe-forms-editor,#listeo-fafe-forms-editor-adv')
		.on( 'init', function() {
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			$(this).find( '.field-type-selector' ).change();
			$(this).find( '.field-type select' ).change();
			$(this).find( '.field-edit-class-select' ).change();
			$(this).find( '.field-options-data-source-choose' ).change();
		})
		.on( 'change', '.field-type select', function() {
			$(this).parent().parent().find('.field-options').hide();

			if ( 'repeatable' === $(this).val() ||  'select' === $(this).val() ||  'select' === $(this).val() || 'select_multiple' === $(this).val() || 'checkbox' === $(this).val() || 'multicheck_split' === $(this).val() || 'radio' === $(this).val()) {
				$(this).parent().parent().find('.field-options').show();
			} 

		})
		.on( 'change', '.field-options-data-source-choose', function() {
			if ( 'predefined' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').show();
				$(this).parent().find('.field-options-custom').hide();
			}
			if ( 'custom' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').hide().val("");
				$(this).parent().find('.field-options-custom').show();
			}
			if ( '' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').hide().val("");
				$(this).parent().find('.field-options-custom').hide();
			}
		})
		.on( 'change', '.field-edit-class-select', function() {
			if ( 'col-md-12' === $(this).val() ) {
				$(this).parent().parent().find('.open_row-container').hide().find('input').prop('checked', true);
				$(this).parent().parent().find('.close_row-container').hide().find('input').prop('checked', true);
				
			} else {
				$(this).parent().parent().find('.open_row-container').show();
				$(this).parent().parent().find('.close_row-container').show();
				
			}
			if ( '' === $(this).val() ) {
				$(this).parent().parent().find('.open_row-container').hide().find('input').prop('checked', false);
				$(this).parent().parent().find('.close_row-container').hide().find('input').prop('checked', false);
			}
			if ( 'custom' === $(this).val() ) {
				$(this).parent().find('.field-options-predefined').hide().val("");
				$(this).parent().find('.field-options-custom').show();
			}
		})
		.on( 'click', '.remove-row', function(e){
			e.preventDefault();
			
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().parent().fadeOut(300, function() { $(this).remove(); });
	 		}
		})
		.on( 'click', '.add-new-option-table', function(e){
			e.preventDefault();
			var $tbody = $(this).closest('table').find('tbody');
			var row    = $tbody.data( 'field' );
			row = row.replace( /\[-1\]/g, "[" + $tbody.find('tr').size() + "]");
			$tbody.append( row );
		})
		.on('change', '.step-container', function() {
			var form = $(this).parent().parent();
			var max = form.find('.max-container input').val();
			var min = form.find('.min-container input').val();
			var step = $(this).find('input').val();
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			if(step > (max-min)){
				form.find('.step-error-exceed').show();
			}
			var offset = 0;
			var len = (Math.abs(max - min)  + ((offset || 0) * 2)) / (step || 1) + 1;
			
			if(len > 30){
				form.find('.step-error-too-many').show();
			}
		})
		.on('change', '.min-container', function() {
			var form = $(this).parent().parent();
			var max = form.find('.max-container input').val();
			var min = form.find('.min-container input').val();
			var step = $(this).find('input').val();
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			if(step > (max-min)){
				form.find('.step-error-exceed').show();
			}
			var offset = 0;
			var len = (Math.abs(max - min)  + ((offset || 0) * 2)) / (step || 1) + 1;
			console.log(len);
			if(len > 30){
				form.find('.step-error-too-many').show();
			}
		})
		.on('change', '.max-container', function() {
			var form = $(this).parent().parent();
			var max = form.find('.max-container input').val();
			var min = form.find('.min-container input').val();
			var step = $(this).find('input').val();
			$('.step-error-too-many').hide();
			$('.step-error-exceed').hide();
			if(step > (max-min)){
				form.find('.step-error-exceed').show();
			}
			var offset = 0;
			var len = (Math.abs(max - min)  + ((offset || 0) * 2)) / (step || 1) + 1;
			console.log(len);
			if(len > 30){
				form.find('.step-error-too-many').show();
			}
		})
		.on('change', '.field-type-selector', function() {
		  var form = $(this).parent().parent();
		  var type = $(this).val();
		
		  switch (type) { 
				case 'select': 
				case 'radio': 
				case 'multicheck_split': 
				case 'multi-select': 
					form.find('.options-container').show();
					form.find('.multi-container').show();
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					form.find('.taxonomy-container').hide();
					break;
				case 'select-taxonomy': 
				case 'term-select': 
					form.find('.multi-container').show();
					form.find('.taxonomy-container').show();
					form.find('.options-container').hide();
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					break;
				case 'input-select': 
				case 'slider': 
				case 'double-input': 
					form.find('.options-container').hide();
					form.find('.multi-container').hide();
					form.find('.max-container').show();
					form.find('.min-container').show();
					form.find('.step-container').show();
					form.find('.unit-container').show();
					break;		
				case 'multi-checkbox': 
				case 'multi-checkbox-row': 
					form.find('.options-container').show();
					form.find('.taxonomy-container').show();
					form.find('.multi-container').hide();
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					break;
				case 'header': 
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.multi-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					form.find('.options-container').hide();
					form.find('.taxonomy-container').hide();
					break;
				case 'radius': 
					form.find('.max-container').show();
					form.find('.min-container').show();
					
					form.find('.multi-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').hide();
					form.find('.options-container').hide();
					form.find('.taxonomy-container').hide();
					break;
				default:
					form.find('.max-container').hide();
					form.find('.min-container').hide();
					form.find('.multi-container').hide();
					form.find('.step-container').hide();
					form.find('.unit-container').show();
					form.find('.options-container').hide();
					form.find('.taxonomy-container').hide();
			}

		  // Does some stuff and logs the event to the console
		});
		
		$('#listeo-fafe-fields-editor').trigger( 'init' );
		$('#listeo-fafe-forms-editor').trigger( 'init' );
		$('#listeo-fafe-forms-editor-adv').trigger( 'init' );


		$('.listeo-forms-builder-top').on('click', '.add_new_item', function(e) {
			e.preventDefault();
			var name;
		    do {
		        name=prompt("Please enter field name");
		    }
			while(name.length < 2);
			var clone    = $('#listeo-fafe-fields-editor').data( 'clone' );
			var id = string_to_slug(name);
			var index = $('.form_item').size()+1; 
			clone = clone.replace( /\[-2\]/g, "[" + index + "]").replace( /clone/g, name);
			$('#listeo-fafe-fields-editor').append(clone);
			$('#listeo-fafe-fields-editor .form_item:last-child .edit-form-field').toggle().find('.field-id input').val('_'+id);
			$('#listeo-fafe-fields-editor .form_item:last-child .edit-form-field .field-options').hide();
		});


		$('.listeo-form-editor table')
		.on( 'click', '.add-new-main-option', function(e){
			e.preventDefault();
			var $tbody = $(this).closest('table').find('tbody');
			var row    = $tbody.data( 'field' );
			
			row = row.replace( /\[-1\]/g, "[" + $tbody.find('tr').size() + "]");
			
			$tbody.append( row );
		})
		.on( 'click', '.remove-row', function(e){
			e.preventDefault();
			
 			if (window.confirm("Are you sure?")) {
	 			$(this).parent().parent().fadeOut(300, function() { $(this).remove(); });
	 		}
		})
		
		function string_to_slug (str) {
		    str = str.replace(/^\s+|\s+$/g, ''); // trim
		    str = str.toLowerCase();
		  
		    // remove accents, swap ñ for n, etc
		    var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
		    var to   = "aaaaeeeeiiiioooouuuunc------";
		    for (var i=0, l=from.length ; i<l ; i++) {
		        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		    }

		    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		        .replace(/\s+/g, '_') // collapse whitespace and replace by -
		        .replace(/-+/g, '_'); // collapse dashes

		    return str;
		}


		// Submit Form Editor

		
		$('.row-container').sortable({
			items: '.editor-block',
			// handle: '.handle',
			cursor: 'move',
			connectWith: '.row-container',
			placeholder: 'my-placeholder',
			/*stop: function(event, ui) {
		        $(".form_item").each(function(i, el){
		            $(this).find('input').attr('name').replace(/\d+/, $(el).index())
		        });
		    }*/
		    update: function(event, ui){
                if(ui.sender){
                    var section_old = ui.sender.data('section');
                    var section_new = $(this).data("section")
                    
                    $(ui.item).find('input,select').each(function(){
    				
    					var newname = this.name.replace(section_old,section_new);
    					this.name = newname;
    					//$(this).attr('name',newname);
  					});      
                }
            },
		    start: function(event, ui) {
		        // Set the initial width of the placeholder to match the helper
	        ui.placeholder.width(ui.item.outerWidth() - 2);
		    },
		    // stop: function(event, ui) {
		       

		    //     // $(".form_item").each(function(i, el){
		    //     //     $(this).find('.priority_field').val( $(el).index() );
		    //     // });
		    // },
		    // receive: function (e, ui) {
		    //     ui.sender.data('copied', true);
		    // }
		}).disableSelection();	

		var widths = ["block-width-3", "block-width-4", "block-width-6","block-width-12"];
		var widths_nr = ["3", "4", "6","12"];
		$('.form-editor-container').on( 'click', '.block-wider a', function(e){
			e.preventDefault();
			var className = $(this).parents().eq(3).attr('class').match(/block-width-\d+/);
			if (className) {
				var cur_width_index = widths.indexOf(className[0]);
				console.log(cur_width_index);
				if(cur_width_index < 3) {
					console.log($(this).parents('.editor-block'));
					$(this).parents('.editor-block').removeClass(widths[cur_width_index]).addClass(widths[cur_width_index+1]);	
					$(this).parents('.editor-block').find('.block-width-input').val(widths_nr[cur_width_index+1]);
				}
			}
		})
		$('.form-editor-container').on( 'click', '.block-narrower a', function(e){
			e.preventDefault();
			var className = $(this).parents().eq(3).attr('class').match(/block-width-\d+/);
			if (className) {
				var cur_width_index = widths.indexOf(className[0]);
				console.log(cur_width_index);
				if(cur_width_index > 0) {
					console.log($(this).parents('.editor-block'));
					$(this).parents('.editor-block').removeClass(widths[cur_width_index]).addClass(widths[cur_width_index-1]);	
					$(this).parents('.editor-block').find('.block-width-input').val(widths_nr[cur_width_index-1]);
				}
			}
		})

		$('.form-editor-container').on( 'click', '.block-edit a', function(e){
			var form_fields;
			e.preventDefault();
				$('.listeo-editor-modal-title').html('Edit Field');
			$('.listeo-editor-modal-footer .button-primary').html('Save Field');
			form_fields = $(this).parents('.editor-block').find('.editor-block-form-fields').addClass('edited-now').html();
			$('.listeo-modal-form').html(form_fields);

			$('.edited-now').find('select').each(function(i) {
				var value = $(this).val();
				console.log(value);
     			$('.listeo-modal-form').find('select').eq(i).val(value);
			});
			$('.edited-now').find('input[type="checkbox"]').each(function(i) {
				
				if($(this).is(":checked")) {
					$('.listeo-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',true);	
				} else {
					$('.listeo-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',false);	
				}
     			
			});
			
			$('.listeo-editor-modal').show();
		
		});

		$('.form-editor-container').on( 'click', '.block-delete a', function(e){
			$(this).parents('.editor-block').remove();
			e.preventDefault();
		});

		$('.form-editor-container').on( 'click', '.block-add-new a', function(e){
			e.preventDefault();
			$('.listeo-editor-modal-title').html('Add New Field');
			$('.listeo-editor-modal-footer .button-primary').html('Add Field');
			var section = $(this).data('section');
			var ajax_data = {
				'action'	: 'listeo_editor_get_items', 
				'section'	: section
				//'nonce': nonce		
			};

			$.ajax({
	            type: 'POST', dataType: 'json',
				url: ajaxurl,
				data: ajax_data,
				
	            success: function(data){
	            	console.log(data);
					$('.listeo-modal-form').html(data.data.items);
					$('.listeo-editor-modal').show();
	            }
	        });
		});
		
		$('.listeo-modal-close, .listeo-cancel').on( 'click', function(e){
			e.preventDefault();
			$('.listeo-editor-modal').hide();
			$('.listeo-modal-form').html('');
			$('.editor-block-form-fields').removeClass('edited-now')
		});

		
		$('#listeo-save-field').on( 'click', function(e){
			
			e.preventDefault();           

			$('.listeo-modal-form input').each(function(){
			    $(this).attr('value',$(this).val());
			
			});

			var new_fields = $('.listeo-modal-form').html();
			$('.edited-now').html(new_fields);
			
			$('.listeo-modal-form').find('input[type="checkbox"]').each(function(i) {
				
				if($(this).is(":checked")) {
					$('.edited-now').find('input[type="checkbox"]').eq(i).prop('checked',true);	
				} else {
					$('.edited-now').find('input[type="checkbox"]').eq(i).prop('checked',false);	
				}
     			
			});

			$('.listeo-modal-form').find('select').each(function(i) {
				var value = $(this).val();
     			$('.edited-now').find('select').eq(i).val(value);
			});


			$('.listeo-editor-modal').hide();
			//$('.listeo-modal-form').html('');
			$('.editor-block-form-fields').removeClass('edited-now');
			$('.section_options').removeClass('edited-now');
		
		});

		$('.listeo-modal-form').on( 'click', '.insert-field', function(e){
			e.preventDefault();
			var section = $(this).data('section');
			var field = $(this).parent().find('.editor-block').clone();
			
			field.show();
			
			// console.log(section);
			// console.log($("div").find("[data-section='" + section + "']"));
			//$(".row-"+section).append(field).show();
			// $(field).appendTo($(".row-"+section)).show();
			// $("TEEST").appendTo($(".row-"+section));s
			
			if($(".row-"+section+" .editor-block").length > 0){
				$(".row-"+section+" .editor-block:last").after(field);		
			} else {
				$(".row-"+section+"").append(field);	
			}
			
		
			$('.row-container').sortable('refresh');
			$('.listeo-editor-modal').hide();
		});

		$('.form-editor-container').on('click','.listeo-fafe-section-edit', function(e){
			e.preventDefault();
			var form_fields;
				$('.listeo-editor-modal-title').html('Edit Section');
			$('.listeo-editor-modal-footer .button-primary').html('Save Changes');
			form_fields = $(this).parent().parent().find('.section_options').addClass('edited-now').html();
		
			$('.listeo-modal-form').html(form_fields);

			$('.edited-now').find('select').each(function(i) {
				var value = $(this).val();
     			$('.listeo-modal-form').find('select').eq(i).val(value);
			});

			$('.edited-now').find('input[type="checkbox"]').each(function(i) {
				
				if($(this).is(":checked")) {
					$('.listeo-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',true);	
				} else {
					$('.listeo-modal-form').find('input[type="checkbox"]').eq(i).prop('checked',false);	
				}
     			
			});
			
			$('.listeo-editor-modal').show();
		});

		$('.listeo-fafe-new-section').on('click',function(e){
			e.preventDefault();
			var name;
		    do {
		        name=prompt("Please enter section name");
		    }
			while(name.length < 2);
			var clone    = $('.form-editor-container').data('section');
			var id = string_to_slug(name);
			
			clone = clone.replace( '{section_org}', name).replace(/{section}/g, id);
			
			$('.form-editor-container').append(clone);
			$('.row-container').sortable();
			//$('#listeo-fafe-fields-editor .form_item:last-child .edit-form-field').toggle().find('.field-id input').val('_'+id);
		});

		$('.form-editor-container').on('click','.listeo-fafe-section-remove-section', function(e){
			e.preventDefault();
			$(this).parents('.listeo-fafe-section').next().remove();
			$(this).parents('.listeo-fafe-section').remove();
		});

		$('.editor-block').each(function(i, el){
        	
            var css_class = $(this).find('select#field_for_type').val();
            $(this).addClass('type-'+css_class);
            
        });
		

		$('.show-fields-type-service').on('click',function(e){
			e.preventDefault();
			$('.listeo-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').hide();
			$('.type-rental').hide();
			$('.type-service').show();
			$('.type-classifieds').hide();
		})

		$('.show-fields-type-rentals').on('click',function(e){
			e.preventDefault();
			$('.listeo-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').hide();
			$('.type-rental').show();
			$('.type-service').hide();
			$('.type-classifieds').hide();
		})

		$('.show-fields-type-events').on('click',function(e){
			e.preventDefault();
			$('.listeo-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').show();
			$('.type-rental').hide();
			$('.type-service').hide();
			$('.type-classifieds').hide();
		})
		
		$('.show-fields-type-classifieds').on('click',function(e){
			e.preventDefault();
			$('.listeo-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-classifieds').show();
			$('.type-event').hide();
			$('.type-rental').hide();
			$('.type-service').hide();
		})

		$('.show-fields-type-all').on('click',function(e){
			e.preventDefault();
			$('.listeo-editor-listing-types a').removeClass('active');
			$(this).addClass('active');
			$('.type-event').show();
			$('.type-rental').show();
			$('.type-service').show();
			$('.type-classifieds').show();
		})
		
 $("#listeo-new-search-form-dialog").dialog({
   title: "Add New Seach Form",
   dialogClass: "wp-dialog",
   autoOpen: false,
   draggable: false,
   width: "auto",
   modal: true,
   resizable: false,
   closeOnEscape: true,
   position: {
     my: "center",
     at: "center",
     of: window,
   },
   open: function () {
     // close dialog by clicking the overlay behind it
     $(".ui-widget-overlay").bind("click", function () {
       $("#listeo-new-search-form-dialog").dialog("close");
     });
   },
   create: function () {
     // style fix for WordPress admin
     $(".ui-dialog-titlebar-close").addClass("ui-button");
   },
 });

 // bind a button or a link to open the dialog
		$("a#add-new-listeo-search-form").click(function (e) {
			e.preventDefault();
			$("#listeo-new-search-form-dialog").dialog("open");
		});
		//  Search form new handle

		$(document).on("submit", "#listeo-new-search-form", function (e) {
			const defaultforms = [
				"sidebar_search",
				"search_on_half_map",
				"search_on_home_page",
				"search_on_homebox_page",
			];
			$("#listeo-new-search-form .spinner").addClass("is-active");
			e.preventDefault();
			var name = $("#listeo-new-search-form-name").val();
			var newname = string_to_slug(name);

			if(defaultforms.includes(newname)){
				alert("use different name");
			} else {
				
				var type = $("#listeo-new-search-form-type").val();
				var ajax_data = {
				action: "listeo_form_builder_addnewform",
				name: name,
				type: type,
				//'nonce': nonce
				};

				$.ajax({
				type: "POST",
				dataType: "json",
				url: ajaxurl,
				data: ajax_data,

					success: function (data) {
						
						if(data.success){
						location.reload();	
						} else {
							alert("Please use different name");
						}
						$("#listeo-new-search-form .spinner").removeClass("is-active");
						
						
					},
					error: function (data) {
						console.log(data);
						alert("Please use different name");
						$("#listeo-new-search-form .spinner").removeClass("is-active");
					}
				});
			}
		
    });
	
	// 	$(".form-builder").on("click", "#add-new-listeo-search-form", function (e) {
	// 	e.preventDefault();
	// 	var name;
	// 	do {
	// 		name = prompt("Please enter new search form name");
	// 	} while (name.length < 2);
	// 	const defaultforms = ["sidebar_search", "search_on_half_map", "search_on_home_page", "search_on_homebox_page"];
	// 	var newname = string_to_slug(name);
	// 	if(defaultforms.includes(newname)){
	// 		alert("use different name");
	// 	} else {
	// 			var ajax_data = {
	// 			action: "listeo_form_builder_addnewform",
	// 			name: name,
	// 			//'nonce': nonce
	// 			};

	// 			$.ajax({
	// 			type: "POST",
	// 			dataType: "json",
	// 			url: ajaxurl,
	// 			data: ajax_data,

	// 			success: function (data) {
				
	// 				  location.reload();
	// 			},
	// 			});
	// 	}
		
    // });
	/*eof*/

	});

}(jQuery));
