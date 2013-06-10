$form = $( "#proForm" );
$form.hide();

function reset() {
    $form.find('input[type= "text"]').val("");
}

function editar(e){
    e.preventDefault();
    $("#legend-pro").text('Actualizar');
    reset();
    $li = $( this );
    $form.show();
    $form.find('input[type="submit"]').attr("value", "actualizar");
    $form.find( 'input[name = "nombre"]' ).val($li.attr("data-nombre"));
    $form.find( 'input[name = "proid"]' ).val($li.attr("data-id"));
    $form.find( 'input[name = "pro-parent"]' ).val($li.attr("data-proyecto"));
    $("#pro-parent").select2("data", {id: $li.attr("data-pid"), text:  $li.attr("data-proyectoNombre")})

    $('select#urgencia option[value='+$li.attr("data-urgencia")+']').attr("selected", "selected");

    $form.find( 'input[name = "descripcion"]' ).val($li.attr("data-descripcion"));

    $form.submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();
        /* get some values from elements on the page: */
        nombre      = $form.find( 'input[name = "nombre"]' ).val(),
        descripcion = $form.find( 'input[name = "descripcion"]' ).val(),
        urlForm     = './' + $li.attr("data-id");

        /* Send the data using post */
        var posting = $.ajax({
            type: "PUT", 
            url: urlForm, 
            data: $form.serialize()
        });

        /* Put the results in a div */
        posting.done(function( data ) {
            data = $.parseJSON(data);
            console.log(data);
            console.log($li);
            $li.attr("data-id", data[0].id);
            $li.attr("data-nombre", data[0].nombre);
            $li.attr("data-descripcion", data[0].descripcion);
            $li.attr("data-proyecto", data[0].proyecto_id);
            $li.attr("data-urgencia", data[0].urgencia);
            $li.attr("class", "btnEdit");
            $li.text(data[0].nombre);

            $button = $('<button>');
            $button.attr("data-id", data[0].id);
            $button.attr("class", 'close');
            $button.html('&times;');

            $li.append($button);

            $form.hide();
        });
    });
};


function borrar (e){
    e.preventDefault();
    reset();
    $li = $( this );
    /* Send the data using post */
    var posting = $.ajax({
        type: "DELETE", 
        url: './d/'+$li.attr("data-id"), 
        data: $li.attr("data-id")
    });

    /* Put the results in a div */
    posting.done(function( data ) {
        console.log(data);
        $form.hide();
        $li.remove();
    });
}



$("#nPro").bind('click', function(e) {
    reset();
    $("#legend-pro").text('Insertar');
    $form.show();

    $form.submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();
        /* get some values from elements on the page: */
        var $form       = $( this ),
        nombre      = $form.find( 'input[name = "nombre"]' ).val(),
        descripcion = $form.find( 'input[name = "descripcion"]' ).val(),
        urlForm     = '.';
        $form.find('input[type="submit"]').attr("value", "insertar");

        /* Send the data using post */
        var posting = $.ajax({
            type: "POST", 
            url: urlForm, 
            data: $form.serialize()
        });

        /* Put the results in a div */
        posting.done(function( data ) {
            data = $.parseJSON(data);
            console.log(data[0]);
            $form.hide();

            var $li = $("<li>");
            var $a = $("<a>");
            $a.attr("data-id", data[0].id);
            $a.attr("data-nombre", data[0].nombre);
            $a.attr("data-descripcion", data[0].descripcion);
            $a.attr("data-proyecto", data[0].proyecto_id);
            $a.attr("data-urgencia", data[0].urgencia);
            $a.attr("class", "btnEdit");
            $a.text(data[0].nombre);

            $button = $('<button>');
            $button.attr("data-id", data[0].id);
            $button.attr("class", 'close');
            $button.html('&times;');

            $a.append($button);
            $li.append($a);

            $("#pro-list").append($li);
            $(".btnEdit").bind('click', editar);
            $(".close").bind('click', borrar);

        });
    });
});


$(".btnEdit").bind('click', editar);
$(".close").bind('click', borrar);
reset();



$('#pro-parent').select2({
    placeholder: "Seleccione Proyecto",
    ajax: {
        url: "/api/proyecto",
        dataType: "json",
        data: function (term, page) {
            return {
                q: term,
                page: page,
            };
        },
        results: function(data, page) {
            console.log(data);
            return { results: data };
        }
    }
});
