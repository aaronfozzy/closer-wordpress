

// init Masonry
var $grid = jQuery('.grid').masonry({
  itemSelector: '.grid-item',
  gutter: 40
});
// layout Masonry after each image loads
$grid.imagesLoaded().progress( function() {
  $grid.masonry('layout');
});

// Main menu

jQuery( document ).ready( function( $ ) {
  navMain = $( '#main-menu' );

  navMain.on( 'click', '.nav-parent > a', function( e ) {
    e.preventDefault();
    var ef = $( this );
    var ah = ef.parent( '.nav-parent' );
    if ( ! ah.hasClass( 'show' ) ) {
      $('.nav-parent').removeClass('show');
      ah.addClass('show');
    }else{
      ah.removeClass('show');
    }
  });

  document.querySelectorAll('.mob-init').forEach(function(el){
    el.addEventListener('click', function() {
      this.classList.toggle('active');
      navMain.classList.toggle('active');
    });
  });
});

// Close dropdown menu if clicked outside of the nav
jQuery(document).on("click", function(event){
  var $trigger = jQuery(".nav-parent");
  if($trigger !== event.target && !$trigger.has(event.target).length){
    $trigger.removeClass('show');
  }            
});