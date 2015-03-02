$('#accept_invitation').click(function() {
// $('.loader').show();
    var id = $('.invitation').attr('id');
    $.ajax({
        url: Routing.generate('accept_relation', {'id': id}),
        success: function(data) {
            if (data.length > 0)
            {
                for (var i in data)
                {
                    var invitation = data[i];
                    $('#nb_invitation').empty().append("<span class=\"notifyIcon icon-users\"></span><span class=\"counter\">" + data.length + "</span>");
                    $('#id_invitation').empty().append("<li class=\"invitation\"> <div class=\"userWidget-2\" style=\"margin-bottom:1px\"> <div class=\"avatar\"></div> <div class=\"info\"><div class=\"notifyName\">" + invitation.username + "</div></div>");
                }
            }
            else
            {
                $('#nombre_invitation').hide();
                $('#id_invitation').empty();
            }
        }
    });
});

$('#show_notification').click(function() {
// $('.loader').show();
    var id = $('.notification').attr('id');
    $.ajax({
        url: Routing.generate('save_notification', {'id': id}),
        success: function() {
           
        }
    });
});