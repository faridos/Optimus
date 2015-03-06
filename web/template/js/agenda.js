(function($){
  $('.agendx').click(function(){
  	$('#agendaa').show();
  	$('.affiche_agendaa').html($(this).text());
 
  });
  })(jQuery);
  
  (function($){
  $('#fermer').click(function(){
    $('#agendaa').hide(0);
   
    
  });
  })(jQuery);