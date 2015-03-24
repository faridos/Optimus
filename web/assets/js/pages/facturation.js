jQuery(function($) {
    $(".chosen-select").chosen();
});
function loadagencydata(id) {
    if (id != "") {
        $.ajax({
            type: "POST",
            url: '{{ path('car_ajx') }}',
            data: 'id=' + id,
            success: function(data) {
                console.log(data);
                $("#carmarque").val(data.marque);
                $("#carmodel").val(data.model);
                $("#carkm").val(data.km);
                $("#carkmmoy").val(data.kmmoy);
                $("#carprixl").val(data.prixl);
                dater = data.dater;
                dates = data.dates;
                $("#inf").remove();

                if ((dater != "") && (dates != "")) {
                    var inf = '<div id="inf" class="alert alert-info"><button type="button" class="close" data-dismiss="alert"> <i class="icon-remove"></i></button> <strong>Occupée ! </strong>à partir de ' + dates + ' jusqu\'à ' + dater + '  . <br></div>'
                    $("#carinf").append(inf);
                }
                else {
                    var inf = '<div id="inf" class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"> <i class="icon-remove"></i></button> <strong><i class="icon-ok"></i>Disponible ! </strong> <br></div>'
                    $("#carinf").append(inf);
                }


                //alert(dates);
                //alert(dater);

            }
        });
    }
}