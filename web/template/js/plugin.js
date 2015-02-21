$(function(){
  $('.open').click(function(){
  	$('#descu').show();
  	$('.affiche_personne').html($(this).text());
  	
    
   
    
  });
  });
  $(function(){
  $('#close').click(function(){
    $('#descu').hide(0);
   
    
  });
  });