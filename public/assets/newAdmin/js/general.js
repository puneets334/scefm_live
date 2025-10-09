/* Left Menu Toggle JS Start */
jQuery(document).ready(function () {
    $("#topnav-hamburger-icon").click(function () {
        $('.hamburger-icon').toggleClass("hide");
        $('.sidePanel').toggleClass("hide");
        $('.mainPanel').toggleClass("hide");

        if ($('.hamburger-icon').hasClass('hide')) {
            $('.hamburger-icon').closest('.mngmntHeader').addClass('side-menu-show');
        } else {
            $('.hamburger-icon').closest('.mngmntHeader').removeClass('side-menu-show');
        }
    });
  $(".main-menu-close").click(function () {
        $('.sidePanel').addClass("hide");
        $('.hamburger-icon').removeClass("hide");

        $('.hamburger-icon').closest('.mngmntHeader').removeClass('side-menu-show');
    });
});
/* Left Menu Toggle JS End */

/* Main Menu Item Click Active This Tab JS Start */ 
jQuery(function($) {
	var path = window.location.href;
	$('.dashboardLeftNav  li  a').each(function() {
	  if (this.href === path) {
		$(this).addClass('active');
	  }
	});
  });
  $(function($) {
    let url = window.location.href;
    $('.dashboardLeftNav  li  a').each(function() {
      if (this.href === url) {
        $(this).closest('.dashboardLeftNav .health').addClass('actve');
        $(this).closest('.dashboardLeftNav .submenu').addClass('show');
		
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
          $("iframe").contents().find("body").addClass("blue-theme");
          $("iframe").contents().find("body").removeClass("black-theme");
      } else if (storedTheme === 'black') {
          jQuery('body').addClass('black-theme');
          $("iframe").contents().find("body").addClass("black-theme");
          $("iframe").contents().find("body").removeClass("blue-theme");
      }
  }

// Apply stored theme when the DOM is ready
  jQuery(document).ready(function() {
      applyStoredTheme();
  });

// Event handler for the blue theme
  jQuery('.whitebg').on('click', function() {
      jQuery('body').addClass('blue-theme');
      $("iframe").contents().find("body").addClass("blue-theme");
      jQuery('body').removeClass('black-theme');
      $("iframe").contents().find("body").removeClass("black-theme");
      setTheme('blue');
  });

// Event handler for the black theme
  jQuery('.blackbg').on('click', function() {
      jQuery('body').addClass('black-theme');
      $("iframe").contents().find("body").addClass("black-theme");
      jQuery('body').removeClass('blue-theme');
      $("iframe").contents().find("body").removeClass("blue-theme");
      setTheme('black');
  });
// Theme JS End
// Font Size Increse & Decrease  Start 
// $(document).ready(function() {
//   var $affectedElements = $("p, h1, h2, h3, h4, h5, h6, blockquote, td, th, a, span, label, Input, select, button, li");
//   $affectedElements.each( function(){
//     var $this = $(this);
//     $this.data("orig-size", $this.css("font-size") );
//   });
//   $("#text_resize_increase").click(function(){
//     changeFontSize(1);
//   })
//   $("#text_resize_decrease").click(function(){
//     changeFontSize(-1);
//   })
//   $("#text_resize_reset").click(function(){
//     $affectedElements.each( function(){
//           var $this = $(this);
//           $this.css( "font-size" , $this.data("orig-size") );
//      });
//   })
//   function changeFontSize(direction){
//       $affectedElements.each( function(){
//           var $this = $(this);
//           $this.css( "font-size" , parseInt($this.css("font-size"))+direction );
//       });
//   }
// });
// ----------------------
// $(document).ready(function() {
//   // Cache elements in the main document that need font size adjustments
//   var $affectedElements = $("p, h1, h2, h3, h4, h5, h6, blockquote, td, th, a, span, label, input, textarea, select, button, li");
//   $affectedElements.each(function() {
//     var $this = $(this);
//     $this.data("orig-size", $this.css("font-size"));
//     $this.data("orig-line", $this.css("line-height"));
//   });

//   // Cache iframe and define variables for iframe content elements
//   var $iframe = $(".iframe-scroll-bar");
//   var $iframeContent, $iframeSelect2Span;

//   // Function to set up iframe content elements when iframe is loaded
//   $iframe.on('load', function() {
//     $iframeContent = $iframe.contents().find("p, h1, h2, h3, h4, h5, h6, blockquote, td, th, a, span, label, input, textarea, select, button, li");
//     $iframeSelect2Span = $iframe.contents().find('body .select2, body , .select2-container--default');
    
//     // Save original font sizes for iframe content
//     $iframeContent.each(function() {
//       var $this = $(this);
//       $this.data("orig-size", $this.css("font-size"));
//       $this.data("orig-line", $this.css("line-height"));
//     });

//     // Save original font sizes for .select2 elements in the iframe
//     $iframeSelect2Span.each(function() {
//       var $this = $(this);
//       $this.data("orig-size", $this.css("font-size"));
//       $this.data("orig-line", $this.css("line-height"));
//     });
//   });

//   // Increase font size button click handler
//   $("#text_resize_increase").click(function() {
//     changeFontSize(1);
//   });

//   // Decrease font size button click handler
//   $("#text_resize_decrease").click(function() {
//     changeFontSize(-1);
//   });

//   // Reset font size button click handler
//   $("#text_resize_reset").click(function() {
//     resetFontSize($affectedElements);
//     resetFontSize($iframeContent);
//     resetFontSize($iframeSelect2Span);
//   });

//   // Function to change font size
//   function changeFontSize(direction) {
//     // Change font size for main document elements
//     $affectedElements.each(function() {
//       var $this = $(this);
//       var newSize = parseInt($this.css("font-size")) + direction;
//       $this.css("font-size", newSize + "px");
//       $this.css("line-height", newSize + "px");
//     });

//     // Change font size for iframe content elements
//     if ($iframeContent) {
//       $iframeContent.each(function() {
//         var $this = $(this);
//         var newSize = parseInt($this.css("font-size")) + direction;
//         $this.css("font-size", newSize + "px");
//         $this.css("line-height", newSize + "px");
//       });
//     }

//     // Change font size for .select2 elements inside the iframe
//     // if ($iframeSelect2Span) {
//       $iframeSelect2Span.each(function() {
//         var $this = $(this);
//         var currentSize = parseInt($this.css("font-size")) + direction;
//         if (currentSize >= 12 && currentSize <= 28) {
//           $this.css('font-size', currentSize + 'px');
//         }
//       });
//     // }
//   }

//   // Function to reset font size to original values
//   function resetFontSize($elements) {
//     if ($elements) {
//       $elements.each(function() {
//         var $this = $(this);
//         $this.css("font-size", $this.data("orig-size"));
//         $this.css("line-height", $this.data("orig-line"));
//       });
//     }
//   }
// });
// ----- 3-12-24-
$(document).ready(function() {
  // Cache elements in the main document that need font size adjustments
  var $affectedElements = $("p, h1, h2, h3, h4, h5, h6, blockquote, td, th, a, span, label, input, textarea, select, button, li");
  $affectedElements.each(function() {
    var $this = $(this);
    $this.data("orig-size", $this.css("font-size"));
    // $this.data("orig-line", $this.css("line-height"));
  });

  // Cache iframe and define variables for iframe content elements
  var $iframe = $(".iframe-scroll-bar");
  var $iframeContent, $iframeSelect2Span;

  // Variables to track the number of clicks
  var increaseCounter = 0;
  var decreaseCounter = 0;

  // Function to set up iframe content elements when iframe is loaded
  $iframe.on('load', function() {
    $iframeContent = $iframe.contents().find("p, h1, h2, h3, h4, h5, h6, blockquote, td, th, a, span, label, input, textarea, select, button, li");
    $iframeSelect2Span = $iframe.contents().find('body .select2, body , .select2-container--default');
    
    // Save original font sizes for iframe content
    $iframeContent.each(function() {
      var $this = $(this);
      $this.data("orig-size", $this.css("font-size"));
      // $this.data("orig-line", $this.css("line-height"));
    });

    // Save original font sizes for .select2 elements in the iframe
    $iframeSelect2Span.each(function() {
      var $this = $(this);
      $this.data("orig-size", $this.css("font-size"));
      // $this.data("orig-line", $this.css("line-height"));
    });
  });

  // Increase font size button click handler
  $("#text_resize_increase").click(function() {
    if (increaseCounter < 4) {  // Allow only two clicks
      changeFontSize(1);  // Increase by 2px
      increaseCounter++;  // Increment the increase counter
    }
  });

  // Decrease font size button click handler
  $("#text_resize_decrease").click(function() {
    if (decreaseCounter < 4) {  // Allow only two clicks
      changeFontSize(-1);  // Decrease by 2px
      decreaseCounter++;  // Increment the decrease counter
    }
  });

  // Reset font size button click handler
  $("#text_resize_reset").click(function() {
    resetFontSize($affectedElements);
    resetFontSize($iframeContent);
    resetFontSize($iframeSelect2Span);
    
    // Reset counters
    increaseCounter = 0;
    decreaseCounter = 0;
  });

  // Function to change font size
  function changeFontSize(direction) {
    // Change font size for main document elements
    $affectedElements.each(function() {
      var $this = $(this);
      var currentSize = parseInt($this.css("font-size"));
      var newSize = currentSize + direction;
      
      // Ensure the new font size is within the range (for example, 12px to 28px)
      if (newSize >= 12 && newSize <= 28) {
        $this.css("font-size", newSize + "px");
        // $this.css("line-height", newSize + "px");
      }
    });

    // Change font size for iframe content elements
    if ($iframeContent) {
      $iframeContent.each(function() {
        var $this = $(this);
        var currentSize = parseInt($this.css("font-size"));
        var newSize = currentSize + direction;
        
        // Ensure the new font size is within the range (for example, 12px to 28px)
        if (newSize >= 12 && newSize <= 28) {
          $this.css("font-size", newSize + "px");
          // $this.css("line-height", newSize + "px");
        }
      });
    }

    // Change font size for .select2 elements inside the iframe
    if ($iframeSelect2Span) {
      $iframeSelect2Span.each(function() {
        var $this = $(this);
        var currentSize = parseInt($this.css("font-size"));
        var newSize = currentSize + direction;
        
        // Ensure the new font size is within the range (for example, 12px to 28px)
        if (newSize >= 12 && newSize <= 28) {
          $this.css('font-size', newSize + 'px');
        }
      });
    }
  }

  // Function to reset font size to original values
  function resetFontSize($elements) {
    if ($elements) {
      $elements.each(function() {
        var $this = $(this);
        $this.css("font-size", $this.data("orig-size"));
        // $this.css("line-height", $this.data("orig-line"));
      });
    }
  }
});
// Font Size Increse & Decrease  End 



// Table TD height JS  Start

// document.querySelectorAll('.custom-table td').forEach(td => {
//   const contentHeight = td.scrollHeight; 
//   const minHeight = 40; 
//   td.style.minHeight = `${Math.max(contentHeight, minHeight)}px`; 
// });
function adjustDataKey(tableElement) {
  if (!tableElement) {
    return;
  }

  const tds = tableElement.querySelectorAll("td");
  if (window.innerWidth <= 768) {
    tds.forEach(td => {
      const dataKey = td.getAttribute("data-key");
      if (!dataKey) {
        return;
      }

      const baseHeight = 34;
      const lengthFactor = 2;
      const calculatedMinHeight = baseHeight + dataKey.length * lengthFactor;
      td.style.minHeight = `${calculatedMinHeight}px`;
    });
  }
}

function handleResponsiveTables() {
  const tableElements = document.querySelectorAll(".custom-table");
  tableElements.forEach(adjustDataKey);

  window.addEventListener("resize", () => {
    tableElements.forEach(adjustDataKey);
  });
}
window.onload = handleResponsiveTables;

// Table TD height JS  End

// ------JS For Iframe StarT ------------

// function adjustIframeHeight(iframe) {
//   const newHeight = iframe.contentWindow.document.body.scrollHeight + 'px';
//   iframe.style.setProperty('height', newHeight, 'important');
// }

// document.querySelectorAll('.iframe-scroll-bar').forEach(iframe => {
//   iframe.addEventListener('load', () => {
//     adjustIframeHeight(iframe);
//     iframe.contentWindow.addEventListener('resize', () => {
//       adjustIframeHeight(iframe);
//     });

//     const observer = new MutationObserver(() => {
//       adjustIframeHeight(iframe);
//     });

//     observer.observe(iframe.contentWindow.document.body, { childList: true, subtree: true,attributes: true,
//       characterData: true });
//   });
// });

// function adjustIframeHeight(iframe) {
//   try {
//     const newHeight = iframe.contentWindow.document.body.scrollHeight + 'px';
//     iframe.style.setProperty('height', newHeight, 'important');
//   } catch (error) {
//     console.error('Error adjusting iframe height:', error);
//   }
// }

// ------------
function adjustIframeHeight(iframe) {
  try {
    const iframeDocument = iframe.contentWindow.document;

    // Filter out hidden elements (display: none)
    const visibleContentHeight = Array.from(iframeDocument.body.children)
      // .filter(element => window.getComputedStyle(element).display !== 'none') // Exclude hidden elements
      .filter(element => {
        return window.getComputedStyle(element).display !== 'none' &&
               !element.classList.contains('sweet-overlay') &&
               !(element.id === 'loader-wrapper');
    }) 
      .reduce((height, element) => height + element.offsetHeight, 0);

    // Set the iframe height to match the visible content
    const newHeight = visibleContentHeight + 'px';
    iframe.style.setProperty('height', newHeight, 'important');
  } catch (error) {
    console.error('Error adjusting iframe height:', error);
  }
}

document.querySelectorAll('.iframe-scroll-bar').forEach(iframe => {
  iframe.addEventListener('load', () => {
    // Initial height adjustment after iframe content loads
    adjustIframeHeight(iframe);

    // Monitor DOM changes inside the iframe for dynamic content
    const observer = new MutationObserver(() => {
      adjustIframeHeight(iframe);
    });

    observer.observe(iframe.contentWindow.document.body, {
      childList: true,    // Detect when child nodes are added/removed
      subtree: true,      // Observe all descendants of the body
      attributes: true,   // Detect attribute changes (e.g., for display toggling)
      characterData: true // Detect text changes
    });

    // Adjust height if iframe content resizes
    iframe.contentWindow.addEventListener('resize', () => {
      adjustIframeHeight(iframe);
    });
  });
});
// ------JS For Iframe End ------------

