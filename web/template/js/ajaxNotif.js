//notification IsSeen
$('#show_notification').click(function() {
// $('.loader').show();
    var id = $('.notification').attr('id');
    $.ajax({
        url: Routing.generate('save_notification', {'id': id}),
        success: function() {
        }
    });
});
// envoyer invitation
$('#add_relation').click(function() {
// $('.loader').show();
    var id = $('.userWidget-1').attr('id');
    console.log(id);
    $.ajax({
        url: Routing.generate('add_relation', {'id': id}),
        success: function() {
            $('#replace-enattente').empty().append("<a class=\"btn btn-green btn-round\"><span class=\"state\" style=\"color:#fff\">En attente</span></a>");
        }
    });
});
//envoyer message
$('#envoyerMessage').click(function()
{
    
    var id = $('.idReciever').attr('id');
    console.log(id);
    var txt = $('#txt-message').val();

    $.ajax({
        url: Routing.generate('message_send', {'id': id, 'content': txt}),
        error: function() {
            $('#messageEch').show();
            $('#messageEnvoyer').hide();
        },
        success: function() {
            $('#messageEch').hide();

            $('#messageEnvoyer').show().delay(3000).fadeOut();
            ;
            $('textarea').val('');
        }
    });

});
$('#btn-fermer-message').click(function() {
    $('#messageEch').hide();
    $('#messageEnvoyer').hide();
    $('textarea').val('');
});
$('#request_club').click(function() {
    $('.loader').show();
    var id = $('.requestClub').attr('id');
   
    $.ajax({
        url: Routing.generate('request_club', {'id': id}),
        success: function() {
            $('.requestClub').replaceWith('Enattente');
             $('.loader').hide();
        }
    });
});
$('#exit_club').click(function() {
 $('.loader').show();
    var id = $('.exitClub').attr('id');
   
    $.ajax({
        url: Routing.generate('exit_club', {'id': id}),
        success: function() {
            $('.exitClub').replaceWith('Rejoindre');
            $('.loader').hide();
        }
    });
});