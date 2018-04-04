$(document).ready( function() {
	(function($) {
	  var oldHTML = $.fn.html;

	  $.fn.formhtml = function() {
	    if (arguments.length) return oldHTML.apply(this,arguments);
	    $("input,button", this).each(function() {
	      this.setAttribute('value',this.value);
	    });
	    $("textarea", this).each(function() {
	      // updated - thanks Raja & Dr. Fred!
	      $(this).text(this.value);
	    });
	    $("input:radio,input:checkbox", this).each(function() {
	      // im not really even sure you need to do this for "checked"
	      // but what the heck, better safe than sorry
	      if (this.checked) this.setAttribute('checked', 'checked');
	      else this.removeAttribute('checked');
	    });
	    $("option", this).each(function() {
	      // also not sure, but, better safe...
	      if (this.selected) {
	    	  this.setAttribute('selected', 'selected');
	      } else {
	    	  this.removeAttribute('selected');
	      }
	    });
	    return oldHTML.apply(this);
	  };

	  //optional to override real .html() if you want
	  // $.fn.html = $.fn.formhtml;
	})(jQuery);
});