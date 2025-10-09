$(function () {
    new WOW().init();
});


/* Main Menu Item Click Active This Tab JS Start */ 
jQuery(function($) {
  var path = window.location.href;
  $('.custom-nav li a').each(function() {
	if (this.href === path) {
	  $(this).addClass('active');
	}
  });
});
/* Main Menu Item Click Active This Tab JS Start */ 

// Theme JS Start

function setTheme(theme) {
  sessionStorage.setItem('theme', theme);
}

function getTheme() {
  return sessionStorage.getItem('theme');
}

  function applyStoredTheme() {
      var storedTheme = getTheme();
      if (storedTheme === 'blue') {
          jQuery('body').addClass('blue-theme');
      } else if (storedTheme === 'black') {
          jQuery('body').addClass('black-theme');
      }
  }

// Apply stored theme when the DOM is ready
  jQuery(document).ready(function() {
      applyStoredTheme();
  });

// Event handler for the blue theme
  jQuery('.whitebg').on('click', function() {
      jQuery('body').addClass('blue-theme');
      jQuery('body').removeClass('black-theme');
      setTheme('blue');
  });

// Event handler for the black theme
  jQuery('.blackbg').on('click', function() {
      jQuery('body').addClass('black-theme');
      jQuery('body').removeClass('blue-theme');
      setTheme('black');
  });
// Theme JS End
// Font Size Increse & Decrease 
$(document).ready(function() {
  var $affectedElements = $("p, h1, h2, h3, h4, h5, h6, blockquote, td, th, a, span, label, Input, select, button, li");
  $affectedElements.each( function(){
    var $this = $(this);
    $this.data("orig-size", $this.css("font-size") );
  });
  $("#text_resize_increase").click(function(){
    changeFontSize(1);
  })
  $("#text_resize_decrease").click(function(){
    changeFontSize(-1);
  })
  $("#text_resize_reset").click(function(){
    $affectedElements.each( function(){
          var $this = $(this);
          $this.css( "font-size" , $this.data("orig-size") );
     });
  })
  function changeFontSize(direction){
      $affectedElements.each( function(){
          var $this = $(this);
          $this.css( "font-size" , parseInt($this.css("font-size"))+direction );
      });
  }
});