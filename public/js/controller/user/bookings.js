// (function() {

//     $(function() {
//         const pastBookingsContent = $("#past-bookings-content");
//         const nextBookingsContent = $("#next-bookings-content");

//         $("#past-bookings-button").click(function() {
//             if (pastBookingsContent.hasClass("hidden")) {
//                 pastBookingsContent.removeClass("hidden");
//                 nextBookingsContent.addClass("hidden");
//                 $("#past-bookings-icon").addClass("rotate-90");
//                 $("#next-bookings-icon").removeClass("rotate-90");
//             } else {
//                 pastBookingsContent.addClass("hidden");
//                 nextBookingsContent.removeClass("hidden");
//                 $("#past-bookings-icon").removeClass("rotate-90");
//                 $("#next-bookings-icon").addClass("rotate-90");
//             }
//         })

//         $("#next-bookings-button").click(function() {
//             if (nextBookingsContent.hasClass("hidden")) {
//                 nextBookingsContent.removeClass("hidden");
//                 pastBookingsContent.addClass("hidden");
//                 $("#next-bookings-icon").addClass("rotate-90");
//                 $("#past-bookings-icon").removeClass("rotate-90");
//             } else {
//                 nextBookingsContent.addClass("hidden");
//                 pastBookingsContent.removeClass("hidden");
//                 $("#next-bookings-icon").removeClass("rotate-90");
//                 $("#past-bookings-icon").addClass("rotate-90");
//             }
//         })        
//     })

// })();    

var accordion = (function(){
  
    var $accordion = $('.js-accordion');
    var $accordion_header = $accordion.find('.js-accordion-header');
    var $accordion_item = $('.js-accordion-item');
   
    // default settings 
    var settings = {
      // animation speed
      speed: 400,
      
      // close all other accordion items if true
      oneOpen: false
    };
      
    return {
      // pass configurable object literal
      init: function($settings) {
        $accordion_header.on('click', function() {
          accordion.toggle($(this));
        });
        
        $.extend(settings, $settings); 
        
        // ensure only one accordion is active if oneOpen is true
        if(settings.oneOpen && $('.js-accordion-item.active').length > 1) {
          $('.js-accordion-item.active:not(:first)').removeClass('active');
        }
        
        // reveal the active accordion bodies
        $('.js-accordion-item.active').find('> .js-accordion-body').show();
      },
      toggle: function($this) {
              
        if(settings.oneOpen && $this[0] != $this.closest('.js-accordion').find('> .js-accordion-item.active > .js-accordion-header')[0]) {
          $this.closest('.js-accordion')
                 .find('> .js-accordion-item') 
                 .removeClass('active')
                 .find('.js-accordion-body')
                 .slideUp()
        }
        
        // show/hide the clicked accordion item
        $this.closest('.js-accordion-item').toggleClass('active');
        $this.next().stop().slideToggle(settings.speed);
      }
    }
  })();
  
  $(function(){
    accordion.init({ speed: 300, oneOpen: true });
  });
