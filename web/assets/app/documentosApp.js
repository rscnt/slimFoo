$form = $( "#proForm" );
$form.hide();
$('#enlaceFile').hide();

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


    $divLabel = $('<div>');
    $divLabel.attr('class', 'control-label');

    $label = $('<label>');
    $label.text('url:');

    $divControls = $('<div>');
    $divControls.attr('class', 'controls');
    $aFoo = $('<a>');
    $aFoo.attr('href', $li.attr("data-directorio"));
    $aFoo.text('Archivo');

    $divLabel.append($label);
    $divControls.append($aFoo);
    $('#enlaceFile').find(".controls").remove();
    $('#enlaceFile').find(".control-label").remove();
    $('#enlaceFile').append($divLabel);
    $('#enlaceFile').append($divControls);

    $('#enlaceFile').show();
    $('#fileContainer').hide();


    // <div class="control-group">
    //     <div class="control-label">
    //         <label for="nombre">
    //             Archivo
    //         </label>
    //     </div>
    //     <div class="controls">
    //         <input type="file" name="file" id="file" />
    //    </div>
    // </div>


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
    $('#enlaceFile').hide();
    $('#fileContainer').show();
    $('#file').show();

    $form.submit(function(event) {
            $form.hide();
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
