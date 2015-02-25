(function($){
  $('.definedname').click(function(){
  	$('#descu').show();
  	$('.affiche_personne').html($(this).text());
 
  });
  })(jQuery);
  
  (function($){
  $('#close').click(function(){
    $('#descu').hide(0);
   
    
  });
  })(jQuery);