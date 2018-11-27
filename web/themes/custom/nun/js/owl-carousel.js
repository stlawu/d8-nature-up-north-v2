(function ($, Drupal) {
  $(document).ready(function() {

    var owl = $("#owl-demo");
    var owl_th = $("#owl-demo-thumb");
    var owl_captions = $("#owl-demo-captions");

    var total_items = $(".owl-main-outer-wrapper .owl-carousel .item").length;
    total_items--;
    var current_slide = 0;

    owl.owlCarousel({
      navigation : false,
      pagination: false,
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem : true,
      rewindNav: true,
      rewindSpeed: 100
    });

    owl_th.owlCarousel({
      items: 5,
      itemsDesktop: [1000, 5],
      itemsDesktopSmall: [900, 5],
      itemsTablet: [600, 4],
      itemsMobile: false
    });

    owl_captions.owlCarousel({
      navigation : false,
      pagination: false,
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem : true,
      rewindNav: true,
      rewindSpeed: 100,
      mouseDrag: false,
      touchDrag: false
    });

    $(".nav-left").click(function() {
      owl.trigger("owl.prev");
      owl_captions.trigger("owl.prev");
    });
    $(".nav-right").click(function() {
      owl.trigger("owl.next");
      owl_captions.trigger("owl.next");
    });

    $(".owl-thumb-outer-wrapper .owl-carousel .item").each(function(index) {
      $(this).click(function() {
        owl.trigger("owl.goTo", index);
        owl_captions.trigger("owl.goTo", index);
      })
    });
  });
})(jQuery, Drupal);
