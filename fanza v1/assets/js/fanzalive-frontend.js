jQuery.fn.qts_change = function(id, url) {
	img = jQuery('<img />'), place = jQuery(this);
	img.load(function() {
		if (this.src && this.complete) {
			place.attr('src',this.src);
		}
	});

	img.error(function() {
		place.attr('src',place.attr('alt'));
	});

	if (url != '') {
		img.attr('src', url);
	} else {
		place.attr('src', place.attr('rel'));
	}
};

jQuery(document).ready(function ($) {
    var custom_uploader, timer;

	// Apply custom empty rules to input wrappers
	$('.acf-style input[type=text], .acf-style textarea').each(function() {
		if (!$(this).val().trim().length) {
			$(this).parent().addClass('acf-empty');
		}
	});

	$('.acf-style input[type=text], .acf-style textarea').focus(function() {
		$(this).parent().removeClass('acf-empty');
		performCheck(this);
	});

	$('.acf-style input[type=text], .acf-style textarea').on('input',function() {
		performCheck(this);
	});
	$('.acf-style input[type=text], .acf-style textarea').blur(function() {
		if (!$(this).val().trim().length) $(this).parent().addClass('acf-empty');
	});
	$('.acf-style input[type=text], .acf-style textarea').change(function() {
		$(this).parent().removeClass('acf-empty');
		if (!$(this).val().trim().length) $(this).parent().addClass('acf-empty');
	});

    // Adds the lightbox to images.
	$('.lightbox_trigger').click(function(e) {
        e.preventDefault();
        var image_href = $(this).attr("href");
        var image_alt = $(this).find("img").attr("alt");
        if (image_alt) image_alt = '<p><span>' + image_alt + '</span>';
        if ($('#lightbox').length > 0) {
            $('#lightbox #content').html('<div id="boxything"><img src="' + image_href + '" />' + image_alt+ '<div id="boxyclose"></div></div>');
            $('#lightbox').fadeIn(500);
        } else {
            var lightbox = $('<div id="lightbox"><div id="content"><div id="boxything"><img src="' + image_href +'" />' + image_alt + '<div id="boxyclose"></div></div></div></div>');
            $('body').append(lightbox);

			$('#lightbox').click(
				function() {
					$('#lightbox').hide();
				}
			);

            lightbox.hide().fadeIn(500);
        }
		// Relocate the boxyclose
		console.log($('#lightbox img')[0].getBoundingClientRect());
    });

    // Toggle Text.
    $(".toggle").not('h3,h2,h1,h4').hide();

	$(".fanzalive-toggle").click(function(e){
        $(this).next(".toggle").slideToggle();
		e.preventDefault();
    });

	// Create a clone of the menu, right next to original.
	// $('nav').addClass('original').clone().insertAfter('nav').removeClass('original').addClass('cloned');

    // Toggles dropdown menu (if selected in settings).
    $('.dropdown-toggle').click(function(event){
		$('.menu-main-container').find('> ul').slideToggle();

		event.preventDefault();
		return false;
    });
	$(window).scroll(function() {

		var original = $('.original'), adminbar = $('#wpadminbar');

		if (original.length) {
            // get oTop
            oTop = original.offset().top;
            if (typeof original !== 'undefined') {
				if ($(window).scrollTop() >= oTop) {
					// Show the cloned menu
					if (!$('.cloned').is(':visible')) {
						$('.cloned').show();
						$('.original').css('opacity',0);
					}
				} else {
					// Hide the cloned menu
					if ($('.cloned').is(':visible')) {
						$('.cloned').hide();
						$('.original').css('opacity',1);
					}
				}
				// Account for admin bar
				if (adminbar.length) {
					if (adminbar.css('position') == 'fixed')
						$('.cloned').css('top',adminbar.height());
					else
						$('.cloned').css('top',0);
				}
			}
		}
	});

	// Catch window resize and refer the browser to .scroll()
	sem_window_wide = ((window.innerWidth > 500)? true:false);
	$(window).resize(function() {

		// Fix menu hiding because of the toggles
		if (window.innerWidth > 500) {
			$('.menu-main-container ul').show();
			sem_window_wide = true;
		} else {
			/*
				Handle going from big to small
			*/
			if (sem_window_wide == true) {
				if (fanzalive_global_options.menu_dropdown == true) $('.menu-main-container ul').hide();
				sem_window_wide = false;
			}
		}
		$(window).scroll();
	});


	$(document.body).on('change', '.tabs-dropdown', function(){
		$that = $(this),
		$contents = $($that.data('target'));

		$contents.find($that.val()).addClass('active').siblings().removeClass('active');
		return false;
	});
});

function doDuration(e) {
	var distance = Math.abs(doMath(e) - $('body').scrollTop());
	return (distance / 500) * 500;
}

function scrollIt(e) {
	var $ = jQuery;

	if (e.length) {
		$('html, body').stop().animate({
			scrollTop: doMath(e)
		},{
			progress:function(a,p,r) {
				/* if (a.props.scrollTop != doMath(e)) {
					scrollIt(e);
				} */
			}
		}, doDuration(e));
	}
}
function doMath(e) {
	$ = jQuery, adminbar = $('#wpadminbar'), oTop = $('.cloned')[0].getBoundingClientRect().top;
	return e.offset().top - parseInt(e.css('margin-top'),10) - $(".cloned").outerHeight(true) - oTop;
}

function clickclear(thisfield, defaulttext) {if (thisfield.value == defaulttext) {thisfield.value = "";}}
function clickrecall(thisfield, defaulttext) {if (thisfield.value == "") {thisfield.value = defaulttext;}}

(function($){
    $(document).ready(function() {
        // Default dropdown action to show/hide dropdown content
        $('.js-dropp-action').click(function(e) {
            e.preventDefault();
            $(this).toggleClass('js-open');
            $(this).parent().next('.dropp-body').toggleClass('js-open');
        });

        $('.custom_radio_label:first').before('<label class="custom_headine custom_radio_label select_blank" for="type_blank_away">Select<input data-value="Select" class="custom_radio_button" id="select_blank" type="hidden" name="type" value=""></label>');
        // Using as fake input select dropdown
        $('.custom_radio_label').click(function() {
        $(this).addClass('js-open').siblings().removeClass('js-open');
        $('.dropp-body,.js-dropp-action').removeClass('js-open');
        });
        // get the value of checked input radio and display as dropp title
        $('input[name="type"]').change(function() {
            var value = $("input[name='type']:checked").attr('data-value');
            $('.js-value').text(value);
        });

        $('.select_blank').click(function() {
            $('.js-value').text('Select');
            $("input[name='type']").prop('checked', false);
        });

        $('.commentary_image').change(function(){
            readImgUrlAndPreview(this);
            function readImgUrlAndPreview(input){
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('.imagePreview').show();
                        $(input).closest('.form-field').find('.imagePreview').attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    });
})(jQuery);


/* Sub menu */
(function($){
    $(document).ready(function(){
        $('.menu .menu-item-has-children > a').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();

            // hide all menus
            $('.menu .child-menu-open').not($(this).parent('li')).removeClass('child-menu-open');
            $('.menu .menu-open').not($(this).next('.sub-menu')).removeClass('menu-open');

            $(this).parent('li').toggleClass('child-menu-open');
            $(this).next('.sub-menu').toggleClass('menu-open');

            return false;
        });

        $("body").click(function() {
            $('.menu .child-menu-open').removeClass('child-menu-open');
            $('.menu .menu-open').removeClass('menu-open');
        });
    });
})(jQuery);

/* Tabs menu */
(function($){
    var
    setActiveTab = function($target) {
        $menu = $target.closest('.tabs-menu'),
        $contents = $($menu.data('target'));
        $target.parent('li').addClass('active').siblings('li').removeClass('active');
        $contents.find($target.attr('href')).addClass('active').siblings().removeClass('active');
    },
    hashOnChange = function () {
        if (window.location.hash) {
            if ($('.tabs-menu li a[href="'+ window.location.hash +'"]').length > 0) {
                setActiveTab($('.tabs-menu li a[href="'+ window.location.hash +'"]'));
            }
        }
    };
    $(document).ready(function(){
        // listen to browser back button click to check if we've put any history
        $(window).on('popstate', function(){
            if (window.history && window.history.pushState) {
                hashOnChange();
            }
        });

        // autoselect tab based on browser hash
        $(window).on('load', hashOnChange);

    	$(document.body).on('click', '.tabs-menu a', function(e){
            e.preventDefault();

    		$that = $(this),
            setActiveTab($that);

            if ($menu.hasClass('tabs-keep-history')) {
                window.history.pushState("", "", window.location.href.split('#')[0] + $that.attr('href'));
            }

            return false;
    	});
    });
})(jQuery);


/* Match Calendar + Table */
(function($){
    var timer = null,
    delay = 30000, // 30 seconds
    scheduleAutoUpdate = function($wrap) {
        timer = setTimeout(function(){
            updateMatchesTemplate($wrap);
        }, delay);
    },
    clearAutoUpdate = function() {
        clearTimeout(timer);
        timer = null;
    },
    updateMatchesTemplate = function($wrap) {
        $.post(fanzalive.ajaxurl, {action: 'fanzalive_get_matches_template', date: $wrap.data('date')})
        .done(function(r){
            if (r.success) {
                $wrap.find('table.matches').replaceWith(r.message);
            }
        })
        .complete(function(){
            if ($wrap.find('.row-match.status-live').length > 0 || $wrap.find('.row-match.status-fixture').length > 0) {
                scheduleAutoUpdate($wrap);
            } else {
                clearAutoUpdate();
            }
        });
    },
    initCalendar = function($wrap) {
        return $wrap.find(".owl-carousel").owlCarousel({
            center: false,
            nav: true,
            margin: 2,
            dots: false,
            items: 7,
            startPosition: 12,
            responsiveClass: true,
            responsive : {
                0 : {
                    items: 3,
                    startPosition: 14,
                },
                480 : {
                    items: 5,
                    startPosition: 13,
                },
                768 : {
                    items: 7,
                    startPosition: 12,
                }
            }
        });
    },
    carouselFocus = function($wrap, currentIndex) {
        var carousel = $wrap.find(".owl-carousel");
        var breakpoint = carousel.data('owl.carousel')._breakpoint;
        var responsive = carousel.data('owl.carousel').options.responsive;
        var total = carousel.data('owl.carousel')._items.length;

        var items = responsive[breakpoint]['items'];
        var focusOn = Math.max(Math.min(currentIndex - ((items-1) / 2), total - items), 0);
        carousel.trigger('to.owl.carousel', [focusOn, 500, true]);
    },
    block = function($wrap){
        $wrap.find('.matches').block({
            message: $wrap.data('loading'),
            css: {
                border: 'none',
                padding: '10px',
                backgroundColor: 'transparent',
                color: '#333'
            },
            overlayCSS: {
                backgroundColor: '#999',
                opacity: '0.9'
            }
        });
    };

    $(document).ready(function(){
        // loop through each matches component & intialize
        $('.fanzalive-matches').each(function(){
            var $wrap = $(this), carousel;

            initCalendar($wrap);
            scheduleAutoUpdate($wrap);

            $wrap.on('click', '.owl-item', function(){

                // disable multiple clicks
                if ($wrap.hasClass('loading-matches')) {
                    return false;
                }

                carouselFocus($wrap, $(this).index());

                // clear auto update timer
                clearAutoUpdate();

                // block matches
                block($wrap);

                // add a class to maintain loading state
                $wrap.addClass('loading-matches');

                var $date = $(this).find('.calendar-date'), date = $date.data('date');
                $wrap.find(".date-active").removeClass('date-active');
                $date.addClass('date-active');
                $wrap.data('date', date);

                $.post(fanzalive.ajaxurl, {action: 'fanzalive_get_matches_template', date: date})
                .done(function(r){
                    if (r.success) {
                        $wrap.find('table.matches').replaceWith(r.message);
                    }
                })
                .complete(function(){
                    $wrap.removeClass('loading-matches');
                    $wrap.find('.matches').unblock();

                    scheduleAutoUpdate($wrap);
                });
            });
        });
        
        setInterval(function(){
            var preId = $(".event-commentaries .event-commentary:first-child").attr('id');
            //console.log('Pre:' + preId);
            $.ajax({
                type : "post",
                url : fanzalive.ajaxurl,
                data : {action: "fanzalive_get_comments"},
                success: function(response) {
                   // console.log(response);
                    response = JSON.parse(response);
                    //console.log(response);
                    //var latestId = $(".event-commentaries .event-commentary:first-child").attr('id');
                     //$(response).filter('#result');
                    var latestId = $(response).find(".event-commentaries .event-commentary:first-child").attr('id');
                    //console.log('Latest:' + latestId);
                    if(preId != latestId){
                        $('#latest-news').html(response);
                    }
                }
            });  
        }, 3000);
    });
})(jQuery);

jQuery(document).ready(function($){
    $('.team-tabs li:first-child').addClass('active');
    $('.team-tabs .tab-content:first-child').addClass('active');
    /*$('.team-tabs .tab-content').hide();
    $('.team-tabs .tab-content:first').show();*/

    // Click function
    $('.team-tabs li').click(function(){
        $('.team-tabs li').removeClass('active');
        $('.team-tabs .tab-content').removeClass('active');
        $(this).addClass('active');
        $('.team-tabs .tab-content').hide();
      
        var activeTabs = $(this).find('a').attr('href');
        $(activeTabs).fadeIn();
        $(activeTabs).addClass('active');

    });

    $('.team-tabs #results .sp-section-content-tables').remove();
    $('.team-tabs #results .sp-widget-align-left').remove();
    $('.team-tabs #results .sp-widget-align-right').addClass('sp-widgets');
    $('.team-tabs #results .sp-widget-align-right').removeClass('sp-widget-align-right');
    $('.team-tabs #results .sp-section-content-details').remove();
    $('.team-tabs #results .sp-section-content-content').remove();

    $('.team-tabs #fixture .sp-section-content-tables').remove();
    $('.team-tabs #fixture .sp-widget-align-right').remove();
    $('.team-tabs #fixture .sp-widget-align-left').addClass('sp-widgets');
    $('.team-tabs #fixture .sp-widget-align-left').removeClass('sp-widget-align-left');
    $('.team-tabs #fixture .sp-section-content-details').remove();
    $('.team-tabs #fixture .sp-section-content-content').remove();
});


  tinymce.init({
    selector: '#commentaryComment',
      plugins: 'media image paste searchreplace save code visualblocks visualchars codesample hr pagebreak anchor advlist lists wordcount  facebookembed twitterembeded instagramembeded',
      menubar: 'edit view insert format tools',
      toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | insertfile link anchor codesample | ltr rtl | image | media | facebookembed | twitterembeded | instagramembeded',
      images_upload_url: fanzalive.ajaxurl+ '?action=upload_image',
      file_picker_types: 'file image media',
      media_live_embeds: true,
      toolbar_sticky: true,
      autosave_ask_before_unload: true,
      autosave_interval: '30s',
      autosave_restore_when_empty: false,
      autosave_retention: '2m',
      importcss_append: true,
      height: 200,
      relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
      quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  });

  tinymce.init({
    selector: '.usercommentaryComment',
    menubar:false,
    statusbar: false,
      toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | insertfile link anchor codesample | ltr rtl',
      autosave_ask_before_unload: true,
      autosave_interval: '30s',
      autosave_restore_when_empty: false,
      autosave_retention: '2m',
      height: 200,
  });


    tinymce.init({
      selector: 'textarea#post_description',
      plugins: 'image paste searchreplace save code visualblocks visualchars codesample hr pagebreak anchor advlist lists wordcount',
      menubar: 'edit view insert format tools',
      toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | insertfile link anchor codesample | ltr rtl | image',
      images_upload_url: fanzalive.ajaxurl+ '?action=upload_image',
      file_picker_types: 'file image media',
      toolbar_sticky: true,
      autosave_ask_before_unload: true,
      autosave_interval: '30s',
      autosave_restore_when_empty: false,
      autosave_retention: '2m',
      importcss_append: true,
      height: 200,
      relative_urls : false,
    remove_script_host : false,
    convert_urls : true,
      quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
});


    tinymce.init({
      selector: 'textarea#user_message',
      plugins: 'paste searchreplace save code visualblocks visualchars codesample hr pagebreak anchor advlist lists wordcount',
      menubar: 'edit view insert format tools',
      toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | insertfile link anchor codesample | ltr rtl',
      toolbar_sticky: true,
      autosave_ask_before_unload: true,
      autosave_interval: '30s',
      autosave_restore_when_empty: false,
      autosave_retention: '2m',
      importcss_append: true,
      height: 200,
      quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
});



const headerMenuItems = (commentaryComment) => [{
  text: 'Header 1',
  icon: false,
  classes: 'h1',
  onclick: () => commentaryComment.execCommand('FormatBlock', false, 'h1')
}, {
  text: 'Header 2',
  icon: false,
  classes: 'h2',
  onclick: () => commentaryComment.execCommand('FormatBlock', false, 'h2')
}, {
  text: 'Header 3',
  icon: false,
  classes: 'h3',
  onclick: () => commentaryComment.execCommand('FormatBlock', false, 'h3')
}]

const headerSocialItems = (commentaryComment) => [{
  text: 'Facebook',
  icon: false,
  classes: 'social-links',
  onclick: () => commentaryComment.insertContent('<a href="#">Facebook</a>')
}, {
  text: 'Instagram',
  icon: false,
  classes: 'social-links',
  onclick: () => commentaryComment.insertContent('<a href="#">Instagram</a>')
}, {
  text: 'Twitter',
  icon: false,
  classes: 'social-links',
  onclick: () => commentaryComment.insertContent('<a href="#">Twitter</a>')
}, {
  text: 'Linkedin',
  icon: false,
  classes: 'social-links',
  onclick: () => commentaryComment.insertContent('<a href="#">Linkedin</a>')
}]
/*********************************/

/***********************************/


const textAlignMenuItems = (commentaryComment) => [{
  icon: 'alignleft',
  onclick: () => commentaryComment.execCommand('JustifyLeft')
}, {
  icon: 'aligncenter',
  onclick: () => commentaryComment.execCommand('JustifyCenter')
}, {
  icon: 'alignright',
  onclick: () => commentaryComment.execCommand('JustifyRight')
}, {
  icon: 'alignjustify',
  onclick: () => commentaryComment.execCommand('JustifyFull')
}]

const placeholderMenuItems = (commentaryComment) => [{
  text: 'Recipient',
  menu: [{
    text: 'Full Name',
    onclick: () => commentaryComment.insertContent('{{to.name}}')
  }, {
    text: 'First Name',
    onclick: () => commentaryComment.insertContent('{{to.first_name}}')
  }, {
    text: 'Last Name',
    onclick: () => commentaryComment.insertContent('{{to.last_name}}')
  }]
}, {
  text: 'Sender',
  menu: [{
    text: 'Full Name',
    onclick: () => commentaryComment.insertContent('{{from.name}}')
  }, {
    text: 'First Name',
    onclick: () => commentaryComment.insertContent('{{from.first_name}}')
  }, {
    text: 'Last Name',
    onclick: () => commentaryComment.insertContent('{{from.last_name}}')
  }, {
    text: 'Signature',
    onclick: () => commentaryComment.insertContent('{{from.signature}}')
  }]
}, {
  text: 'Organization',
  menu: [{
    text: 'Name',
    onclick: () => commentaryComment.insertContent('{{organization.name}}')
  }]
}]

function setup(commentaryComment) {
   commentaryComment.addButton('headings', {
    type: 'menubutton',
    text: 'Headings',
    tooltip: 'Headings',
    menu: headerMenuItems(commentaryComment)
  })
  commentaryComment.addButton('social', {
    type: 'menubutton',
    text: 'Social',
    tooltip: 'Social',
    menu: headerSocialItems(commentaryComment)
  })
  
  commentaryComment.addButton('textalign', {
    type: 'menubutton',
    icon: 'alignleft',
    tooltip: 'Alignment',
    menu: textAlignMenuItems(commentaryComment),
    onclick: (e) => {
      $('.mce-i-aligncenter').closest('.mce-menu').width(48)
    }
  })
  
  commentaryComment.addButton('placeholders', {
    type: 'menubutton',
    text: 'Placeholders',
    tooltip: 'Placeholders',
    menu: placeholderMenuItems(commentaryComment)
  })
}

jQuery(document).ready(function() {
  const minus = jQuery('.quantity__minus1');
  const plus = jQuery('.quantity__plus1');
  const input = jQuery('[name="goals-team1"]');
  const minus1 = jQuery('.quantity__minus2');
  const plus1 = jQuery('.quantity__plus2');
  const input1 = jQuery('[name="goals-team2"]');
  minus.click(function(e) {
    e.preventDefault();
    var value = input.val();
    if (value > 0) {
      value--;
    }
    input.val(value);
  });
  
  plus.click(function(e) {
    e.preventDefault();
    var value = input.val();
    value++;
    input.val(value);
  })
  minus1.click(function(e) {
    e.preventDefault();
    var value = input1.val();
    if (value > 0) {
      value--;
    }
    input1.val(value);
  });
  
  plus1.click(function(e) {
    e.preventDefault();
    var value = input1.val();
    value++;
    input1.val(value);
  })
});

jQuery(document).on('click', '#follow_btn', function($){
    jQuery("#follow-popup").show();
    jQuery(".popup-main").slideDown();
});

jQuery(document).on('click', '.follow-close', function($){
    jQuery("#follow-popup").hide();
    jQuery(".popup-main").slideUp();
});

jQuery(window).on('load', function($){
    jQuery( ":nth-child(1)" ).nextAll( ".tbal-ads" ).remove();
});

jQuery(document).ready(function($){
    
    if($('#standings .match-table .sportspress .sp-template-league-table').children('h4.sp-table-caption').length > 0) {
        $('#standings .col-md-3').addClass('mar78');
    }else {
        $('#standings .col-md-3').removeClass('mar78');
    }

    jQuery(document).on('click', '#delete_news', function() {
         $.ajax({
            type: 'post',
            url: fanzalive.ajaxurl,
            data: {
                action: 'his_delete_post',
                id: $(this).attr('data-id'),
            },
            success: function( result ) {
                if( result == 'success' ) {
                    /*$('.news-action').after('<span style="color: green">Post deleted successfully!</span>');*/
                   window.location.reload();
                }
            }
        })
    });

    var fb_embed_post_template = '<div class="fb-post" data-href="{embed_post_url}" data-width="{embed_post_width}"></div>';
    var embedPost = false;
    $.each($('.FacebookEmbedPost'), function(index, value) {
        embedPost = fb_embed_post_template.replace("{embed_post_url}", $(value).data('post')).replace("{embed_post_width}", $(value).data('width'));
        $(this).replaceWith(embedPost);
    });

    

})
