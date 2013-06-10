$form = $( "#proForm" );

function completar (e){
    e.preventDefault();
    $a = $( this );
    /* Send the data using post */
    var posting = $.ajax({
        type: "POST", 
        url: '/dashboard/'+$a.attr("data-proid")+'/t/'+$a.attr("data-id"), 
        data: {'completado': $a.attr("data-completado")}
    });

    /* Put the results in a div */
    posting.done(function( data ) {
        data = $.parseJSON(data);
        console.log(data[0].completado);
        if(data[0].completado === 1) {
            $a.attr('data-completado', data[0].completado);
            $a.text('completado');
            $a.attr('class', 'rgt task btn btn-danger btn-small');
        } else {
            $a.attr('data-completado', data[0].completado);
            $a.text('Marcar completado');
            $a.attr('class', 'rgt task btn btn-success btn-small');
        }
    });
}

$(".task").bind('click', completar);
