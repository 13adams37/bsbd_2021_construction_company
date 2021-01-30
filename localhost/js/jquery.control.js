var preload = new Image();preload.src = '/images/load.gif';
(function($){'use strict';$.ckupdatePD = function(){if(typeof CKEDITOR !== 'undefined'){for(var instance in CKEDITOR.instances){CKEDITOR.instances[instance].updateElement();}}}})(jQuery);
(function($){'use strict';$.controlPD = function(obj){for(var name in obj){if($['_'+name+'PD']) $['_'+name+'PD'](obj[name]);}}})(jQuery);
(function($){'use strict';$.hashgetPD = function(){var query = location.hash.replace(/^#/, '');$.controlPD({'get': query});}})(jQuery);
(function($){'use strict';$.placholderPD = function(elm){$(elm).find('[placeholder]').each(function(){if($(this).val() == $(this).attr('placeholder')) $(this).val('');});}})(jQuery);
(function($){'use strict';$.loaderPD = function(method){var methods = {show:function(){$('<div/>',{'class':'loader-pd'}).appendTo('body');},hide:function(){$('.loader-pd').remove();}};if(methods[method]){return methods[method].apply(this, Array.prototype.slice.call(arguments,1));}}})(jQuery);
(function($){'use strict';$._submitPD = function(obj){var options = $.extend({cache:false,datatype:'json',placholder:true,async:true,load:true}, obj || {});options.placholder && $.placholderPD(options.form);$(options.form).ajaxSubmit({cache: options.cache,dataType: options.datatype,async:options.async,beforeSubmit: function(){options.load && $.loaderPD('show');},error:function(){alert('Error')},success: function(data){$.controlPD(data)},complete: function(){options.load && $.loaderPD('hide');}});}})(jQuery);
(function($){'use strict';$._getPD = function(obj){var options = $.extend({cache:false, datatype:'json',async:true,load:true}, obj || {});$.ajax({url:options.url, cache:options.cache, dataType:options.datatype, beforeSend: function(){options.load && $.loaderPD('show')}, async:options.async}).done(function(data){$.controlPD(data)}).fail(function(){alert('Error')}).always(function(){options.load && $.loaderPD('hide')});}})(jQuery);
(function($){'use strict';$._noticePD = function(obj){var settings = $.extend({css:'info', html:''}, obj || {});var close = $('<a/>', {'class': 'notice-pd-button'});var html = $('<div/>', {'class': 'notice-pd-html'}).append(settings.html);var wrap = $('<div/>', {'class': 'notice-pd-item notice-pd-'+settings.css}).append(close).append(html);if(!$('.notice-pd').length) $('<div/>', {'class': 'notice-pd'}).appendTo('body');wrap.fadeIn(150).appendTo('.notice-pd').delay(4000).fadeOut(250, function(){this.remove();});$(wrap).hover(function(){wrap.stop(true,true);},function(){wrap.delay(1000).fadeOut(250,function(){this.remove();});});$(close).on('click', function(){wrap.fadeOut(100, function(){this.remove();});});return wrap;}})(jQuery);
(function($){'use strict';$._messagePD = function(obj){var settings = $.extend({css:'info',insert:'html',target:'.message',html:''}, obj || {});var close = $('<a/>', {'class': 'msg-pd-button'});var html = $('<div/>', {'class': 'msg-pd-html', 'html':settings.html});var wrap = $('<div/>', {'class': 'msg-pd msg-pd-'+ settings.css}).append(close).append(html);switch(settings.insert){case 'append': wrap.appendTo(settings.target); break;case 'prepend': wrap.prependTo(settings.target); break;case 'before': wrap.insertBefore(settings.target); break;case 'after': wrap.insertAfter(settings.target); break;case 'html': $(settings.target).html(wrap); break;}$(close).on('click', function(){wrap.fadeOut(100, function(){this.remove();});});return wrap;}})(jQuery);
(function($){'use strict';$._dialogPD = function(obj){var settings = $.extend({width:'auto', height:'auto',html:''}, obj || {});var close = $('<a/>', {'class': 'dialog-pd-button dialog-pd-close'});var html = $('<div/>', {'class': 'dialog-pd-html'}).append(close).append(settings.html);var win = $('<div/>', {'class': 'dialog-pd-win', 'style': 'width: '+settings.width+'; height: '+settings.height+';'}).append(html);var wrap = $('<div/>', {'class': 'dialog-pd dialog-pd-close'}).append(win);$('body, html').css('overflow', 'hidden');$('.dialog-pd').remove();wrap.appendTo('body');$(wrap).on('click', function(e){if($(e.target).hasClass('dialog-pd-close')){$('body, html').css('overflow', 'auto');wrap.remove();}}); return wrap;}})(jQuery);
(function($){'use strict';$._htmlPD = function(obj){for(var key in obj) $(obj[key]['sel']).html(obj[key]['html']);}})(jQuery);
(function($){'use strict';$._textPD = function(obj){for(var key in obj) $(obj[key]['sel']).text(obj[key]['text']);}})(jQuery);
(function($){'use strict';$._appendPD = function(obj){for(var key in obj) $(obj[key]['sel']).append(obj[key]['html']);}})(jQuery);
(function($){'use strict';$._valPD = function(obj){for(var key in obj) $(obj[key]['sel']).val(obj[key]['value']);}})(jQuery);
(function($){'use strict';$._togclassPD = function(obj){for(var key in obj) $(obj[key]['sel']).toggleClass(obj[key]['class']);}})(jQuery);
(function($){'use strict';$._redirPD = function(obj){document.location = obj;}})(jQuery);
(function($){'use strict';$._backPD = function(obj){history.back();}})(jQuery);
(function($){'use strict';$._hashsetPD = function(obj){location.hash = obj;}})(jQuery);
(function($){'use strict';$._reloadPD = function(obj){location.reload(obj);}})(jQuery);
(function($){'use strict';$._removePD = function(obj){$(obj).remove();}})(jQuery);
(function($){'use strict';$._alertPD = function(obj){alert(obj);}})(jQuery); 
$(function(){
    $('body').on('click', '.all', function(){
        if($(this).prev('.area').hasClass('short')){
            $(this).text('Скрыть').prev('.area').removeClass('short').addClass('full');
        }else if($(this).prev('.area').hasClass('full')){
            $(this).text('Показать все').prev('.area').removeClass('full').addClass('short');
            $('html,body').animate({scrollTop: $(this).parents('.photo').offset().top - 0}, 500);
        }
    });
});
$(function(){$('a[rel^="photo"]').prettyPhoto({social_tools: false});});
$(function(){
    $('a[href*=#]:not([href=#])').click(function() {
    if(location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
    || location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top - 0
        }, 500);
        return false;
      }
    }
  });     
});