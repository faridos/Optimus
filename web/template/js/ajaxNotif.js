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
    $('#loader_ajouter_relation').show();
    $('#replace-enattente').empty();
    var id = $('.userWidget-1').attr('id');
    console.log(id);
    $.ajax({
        url: Routing.generate('add_relation', {'id': id}),
        success: function() {
            $('#replace-enattente').append("<a class=\"btn btn-green btn-round\"><span class=\"state\" style=\"color:#fff\">En attente</span></a>");
        $('#loader_ajouter_relation').hide();
        }
        
    });
});
$('#add_relation_ami').click(function() {
    
    $('#loader_ajouter_ami').show();
    $('#replace-bt-amis').empty();
    var id = $('.userWidget-1').attr('id');
    console.log(id);
    $.ajax({
        url: Routing.generate('add_relation', {'id': id}),
        success: function() {
            $('#loader_ajouter_ami').hide();
            $('#enattente-ami').show();
        
        }
        
    });
});

//envoyer message
$('#envoyerMessage').click(function()
{
    var id = $('.idReciever').attr('id');
    var txt = $('#txt-message').val();
    $('#envoyerMessage').hide();
$('#loader_envoie_message').show();

    $.ajax({
        type: "POST",
        url: Routing.generate('message_send'),
        data: {id:id, content:txt},
//        error: function() {
//            $('#messageEch').show();
//            $('#messageEnvoyer').hide();
//        },
        success: function() {
            $('#messageEch').hide();
            $('#messageEnvoyer').show().delay(3000).fadeOut();
            $('textarea').val('');
            $('#loader_envoie_message').hide();
            $('#envoyerMessage').show();
        }
    });

});
$('#btn-fermer-message').click(function() {
    $('#messageEch').hide();
    $('#messageEnvoyer').hide();
    $('textarea').val('');
});
$('#request_club').click(function() {
    $('#loaderRejoindreClub').show();
    var id = $('.requestClub').attr('id');

    $.ajax({
        url: Routing.generate('request_club', {'id': id}),
        success: function() {
            $('.requestClub').replaceWith('Enattente');
            $('#loaderRejoindreClub').hide();
        }
    });
});
$('#exit_club').click(function() {
    $('#exit_club').hide();
    $('#loaderRejoindreClub').show();
    var id = $('.exitClub').attr('id');

    $.ajax({
        url: Routing.generate('exit_club', {'id': id}),
        success: function(data) {
             $('#loaderRejoindreClub').hide();
            $('#activer_member_club').show();
         
        }
    });
});
$('#activer_member_club').click(function() {
    $('#activer_member_club').hide();
    $('#loaderRejoindreClub').show();
    var id = $('.activerclub').attr('id');

    $.ajax({
        url: Routing.generate('activer_compte', {'id': id}),
        success: function(data) {
        $('#loaderRejoindreClub').hide();
        $('#AttentrejoindreClub').show();
        }
    });
});
$('#accept_relation_profil').click(function() {
    $('#accept_relation_profil').hide();
    $('#loader_accept_relation').show();

    var id = $('.userWidget-1').attr('id');
    console.log(id);
    $.ajax({
        url: Routing.generate('accept_invitation_profil', {'id': id}),
        success: function(data) {
            var nb = parseInt($('#nbInvitation').text());
            var nouveauNb = nb - 1;

            if (nouveauNb > 0)
            {
                $('#nbInvitation').replaceWith(nouveauNb);
            }
            else
            {
                $('#nombre_invitation').hide();
            }
            $('.invitation' + data).empty();
            $('#loader_accept_relation').hide();
            $('#replace-accepte-profil').empty().append(
                    '<button type="button" class="btn btn-green btn-round dropdown-toggle" data-toggle="dropdown">' +
                    'Amis' +
                    '<span class="caret"></span>' +
                    ' </button>'

                    );
            
        }
    });
});