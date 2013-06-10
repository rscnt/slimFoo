$form = $( "#proForm" );
$form.hide();

function reset() {
    $form.find('input[type= "text"]').val("");
}

function editar(e){
    e.preventDefault();
    $("#legend-pro").text('Actualizar');
    $li = $( this );
    $form.show();
    $form.find('input[type="submit"]').attr("value", "actualizar");
    $form.find( 'input[name = "nombre"]' ).val($li.attr("data-nombre"));
    $form.find( 'input[name = "proid"]' ).val($li.attr("data-id"));
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
            $li.attr("data-id", data[0].id);
            $li.attr("data-nombre", data[0].nombre);
            $li.attr("data-descripcion", data[0].descripcion);
            $li.text(data[0].nombre);

            reset();
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

            var li = $("<li><a class='btnEdit' data-id="+ data[0].id +">"+ data[0].nombre +"<button data-id="+data[0].id+" class='close'>&times;</button></a></li>");

            $("#pro-list").append(li);
            $(".btnEdit").bind('click', editar);
            $(".close").bind('click', borrar);
        });
    });
});


$(".btnEdit").bind('click', editar);
$(".close").bind('click', borrar);
reset();
