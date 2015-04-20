/**this code is developed by essid haithem for optimus team **/

$('div.info').hide().fadeIn(1000).delay(3500).fadeOut(800);

$('div.set').click(function() {
  $('div.contentimus').addClass('show');
  $('div').removeClass('pagevis');
  $('div').removeClass('final1');
  
  if ($(this).is('#set1')) {
    $('div#page1').addClass('pagevis').siblings('div').removeClass('pageVis');
  }
  else if ($(this).is('#set2')) {
    $('div#page2').addClass('pagevis').removeClass('final1');
  }
  else if ($(this).is('#set3')) {
    $('div#page3').addClass('pagevis');
  }
  else {
    $('div#page4').addClass('pagevis');
  }
});
$('button.okus').click(function() {
$('div.contentimus2').addClass('show');

  $('div').removeClass('final1');
  
  if ($(this).is('#okus1')) {
    $('div#final1').addClass('pagevis').siblings('div').removeClass('pageVis');
  }
  else if ($(this).is('#okus2')) {
    $('div#final2').addClass('pagevis');
  }
  else if ($(this).is('#okus3')) {
    $('div#final3').addClass('pagevis');
  }
  else {
    $('div#final4').addClass('pagevis');
  }
});

 (function($){
  $('#avant').click(function(){
  
   $('div#final1').removeClass('pagevis');
    
  });
  })(jQuery);
  
 (function($){
  $('#avant').click(function(){
  
   $('div#final1').removeClass('pagevis');
    
  });
  })(jQuery);

  
  (function($){
  $('#avant1').click(function(){
  
   $('div#final2').removeClass('pagevis');
    
  });
  })(jQuery);
  
  
