/**
 * Funciones auxiliares de javascripts 

 */


function confirmarBorrar(nombre,id){
	
  if (confirm("¿Quieres eliminar el usuario:  "+nombre+"?"))
  {
   document.location.href="?orden=Borrar&id="+id;
  }
}

function confirmarBorrarFile(file){
	
	  if (confirm("¿Quieres eliminar el archivo:  "+file+"?"))
	  {
	   document.location.href="?orden=BorrarFichero&id="+file;
	  }
	}

function confirmarRenombrarFile(file){
	var nuevoNombre = prompt("Introduce el nuevo nombre:");
	if(nuevoNombre!=null || nuevoNombre!=""){
		document.location.href="?orden=Renombrar&id="+file+"&nombre="+nuevoNombre;
	}

	
}

$(document).ready(function(){
    // Cuando el raton se pone encima de un elemento .detalles
    $(".eliminar").mouseenter(function(event){
        // Ponemos en el div flotante el contenido del attributo contentpara añadir contenido al div
        // donde se encuentra el raton(this)
        $("#flotante").html($(this).attr("content"));
        // Posicionamos el div flotante y mo lostramos
        $("#flotante").css({left:event.pageX+5, top:event.pageY+5, display:"block"});
    });
 
    // Cuando el raton sale del elemento con el class=text
    $(".eliminar").mouseleave(function(event){
        // Escondemos el div flotante
        $("#flotante").hide();
    });
});

$(document).ready(function(){
    // Cuando el raton se pone encima de un elemento .detalles
    $(".modificar").mouseenter(function(event){
        // Ponemos en el div flotante el contenido del attributo contentpara añadir contenido al div
        // donde se encuentra el raton (this)
        $("#flotanteM").html($(this).attr("content"));
        // Posicionamos el div flotante y mo lostramos
        $("#flotanteM").css({left:event.pageX+5, top:event.pageY+5, display:"block"});
    });
 
    // Cuando el raton sale del elemento con el class=text
    $(".modificar").mouseleave(function(event){
        // Escondemos el div flotante
        $("#flotanteM").hide();
    });
});

$(document).ready(function(){
    // Cuando el raton se pone encima de un elemento .detalles
    $(".detalles").mouseenter(function(event){
        // Ponemos en el div flotante el contenido del attributo content del div
        // donde se encuentra el raton(this)
        $("#flotanteD").html($(this).attr("content"));
        // Posicionamos el div flotante y mo lostramos
        $("#flotanteD").css({left:event.pageX+5, top:event.pageY+5, display:"block"});
    });
 
    // Cuando el raton sale del elemento con el class=text
    $(".detalles").mouseleave(function(event){
        // Escondemos el div flotante
        $("#flotanteD").hide();
    });
	$(".aviso:contains(El)").css("background-color", "red");
});




