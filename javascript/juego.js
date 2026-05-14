$(document).ready(function() {

    //Ver nombre del jugador con sesion iniciada
    $.get("../core/php/VerifyUserName.php")
        .done(function(data) {
            var usuario = $.parseJSON(data);
            $("#nombre-jugador").text(usuario[0].nombre);
            idUsuario = usuario[0].id;
        })
        /*
         * MR 2026.4 | Preventivo | Manuel Cupul
         * Se agregó manejo del caso de fallo al cargar el nombre del usuario,
         * mostrando un aviso en lugar de fallar silenciosamente.
         */
        .fail(function() {
            Toast.warning("No se pudo cargar tu información de usuario. Algunos datos pueden no mostrarse.");
        });

    $("#pregunta-correctos").hide();
    $("#puntaje").val("0");
    asignarListenersPregunta();

    var tiempo = 150;
    var url = window.location.toString();
    var parametros = url.substring(url.indexOf("dif=") + 4).split('&');

    if (parametros.length < 2) {
        salirJuego();
    }

    dificultad = parametros[0];
    materia = parametros[1].substring(4);

    switch (dificultad) {
        case "facil":
            tiempo = 300;
            break;
        case "medio":
            tiempo = 250;
            break;
        case "dificil":
            tiempo = 5;
            break;
    }

    pedirDatos(materia);
    iniciarContador(tiempo);

    /*
     * MR 2026.3 | Perfectivo | Manuel Cupul
     * Se agregaron los listeners del botón de pausa y las opciones del modal
     * (Reanudar, Reiniciar, Salir) para permitir controlar el flujo de la partida.
     */
    $("#btn-pausa").click(function() {
        pausarJuego();
    });

    $("#pausa-reanudar").click(function() {
        reanudarJuego();
    });

    $("#pausa-reiniciar").click(function() {
        $("#modal-pausa").modal("hide");
        reiniciarJuego();
    });

    $("#pausa-salir").click(function() {
        salirJuego();
    });

});

var dificultad;
var idUsuario;
var materia;
var cartas = [];

// MR 2026.3 | Perfectivo | Manuel Cupul — variables de estado para pausa
var enPausa = false;
tiempoActual = 0;

/*
 * MR 2026.3 | Perfectivo | Manuel Cupul
 * Funciones de pausa, reanudación y reinicio de partida.
 * pausarJuego: detiene el contador, bloquea cartas y muestra el modal.
 * reanudarJuego: reanuda el contador desde el tiempo guardado y desbloquea cartas.
 * reiniciarJuego: recarga la página para empezar una nueva partida.
 */
function pausarJuego() {
    if (enPausa) return;
    enPausa = true;
    clearInterval(intervaloContador);
    bloquearCartas();
    $("#btn-pausa").prop("disabled", true);
    $("#modal-pausa").modal({
        backdrop: "static",
        keyboard: false
    });
    $("#modal-pausa").modal("show");
}

function reanudarJuego() {
    $("#modal-pausa").modal("hide");
    enPausa = false;
    $("#btn-pausa").prop("disabled", false);
    // Retomar el contador con el tiempo que quedaba
    tiempoActual = parseInt($("#timer").text());
    iniciarContador(tiempoActual);
    // Solo desbloquear si no hay pregunta pendiente
    if ($("#pregunta-correctos").is(":hidden")) {
        desbloquearCartas();
    }
}

function reiniciarJuego() {
    location.reload();
}

// Lógica original del juego

function pedirDatos(materia) {
    $.get("../core/php/ParejasJuegoDispatcher.php", {idmateria: materia})
        .done(function(data) {
            if (!data) {
                // MR 2026.4 | Preventivo | Manuel Cupul — sin datos para la materia
                Toast.error("No hay pares de cartas disponibles para esta materia. Regresa al menú y elige otra.");
                setTimeout(function() { salirJuego(); }, 3000);
                return;
            }

            var datos;
            try {
                datos = $.parseJSON(data);
            } catch(e) {
                // MR 2026.4 | Preventivo | Manuel Cupul — respuesta no parseable
                Toast.error("Error al leer los datos del juego. Por favor, intenta de nuevo.");
                setTimeout(function() { salirJuego(); }, 3000);
                return;
            }

            if (datos.length < 9) {
                // MR 2026.4 | Preventivo | Manuel Cupul — pares insuficientes
                Toast.warning("Esta materia no tiene suficientes pares de cartas para iniciar el juego.");
                setTimeout(function() { salirJuego(); }, 3000);
                return;
            }

            datos = revolver(datos);
            procesarDatos(datos);
        })
        .fail(function() {
            // MR 2026.4 | Preventivo | Manuel Cupul — error de conexión al cargar cartas
            Toast.error("No se pudieron cargar las cartas. Verifica tu conexión e intenta de nuevo.");
            setTimeout(function() { salirJuego(); }, 3000);
        });
}


function procesarDatos(datos) {
    for (var i = 0; i < 9; i++) {
        crearCartas(datos[i].concepto, datos[i].descripcion, i);
    }
}

function crearCartas(concepto, descipcion, indice) {
    //cargamos la carta y le inyectamos los el concepto
    $.get("../sections/carta.html", function(data) {
        var htmlCarta = $.parseHTML(data);
//        $(htmlCarta).attr("id", "C" + indice + concepto);
        $(htmlCarta).attr("id", indice + concepto);
        $(htmlCarta).attr("tipo", "definicion");
        $(htmlCarta).find("#texto").append(concepto);
        $(htmlCarta).find("#img-correcta").hide();
        $(htmlCarta).find("#imagen-carta").attr("src", "../img/carta_uady.png");
        asignarListeners(htmlCarta);
        colocarCartas(htmlCarta);
    });

    //Luego creamos otra carta con la descripcion
    $.get("../sections/carta.html", function(data) {
        var htmlCarta = $.parseHTML(data);
//        $(htmlCarta).attr("id", "D" + indice + concepto);
        $(htmlCarta).attr("id", indice + concepto);
        $(htmlCarta).attr("tipo", "concepto");
        $(htmlCarta).find("#texto").append(descipcion);
        $(htmlCarta).find("#img-correcta").hide();
        $(htmlCarta).find("#imagen-carta").attr("src", "../img/carta_uady_c.png");
        asignarListeners(htmlCarta);
        colocarCartas(htmlCarta);
    });
}

var parejaSeleccionada = [];

function asignarListeners(carta) {
    $(carta).click(function() {
        console.log("click");

        //notify(1, this.id);

//        $(carta).toggleClass("flipped");
//        //Le quitamos el evento de click
//        $(carta).unbind("click");
        //Comprueba que no se haga click a la misma carta varias veces
//        for (var index in parejaSeleccionada) {
//
//            if (parejaSeleccionada[index] === carta) {
//                parejaSeleccionada.splice(index, 1);
//                return;
//            }
//        }


        if ($(carta).attr("tipo") !== $(parejaSeleccionada[0]).attr("tipo")) {
            $(carta).toggleClass("flipped");
            //Le quitamos el evento de click
            $(carta).unbind("click");
            parejaSeleccionada.push(carta);
        } else {
            //alert("Son iguales");
        }

        //Si es la segunda carta seleccionada
        if (parejaSeleccionada.length > 1) {
            bloquearCartas();
            $("#pregunta-correctos").show(500);
        }



    });
}

function confirmarRespuesta(respuesta, caso) {
    var divResultado = $("#resultado");
    divResultado.removeClass();


    $("#pregunta-correctos").hide(500);

    if (respuesta) {
        console.log("Correcto");
        divResultado.text("Correcto");
        divResultado.show(1000);
        divResultado.toggleClass("alert alert-success", true);

    } else {
        console.log("Incorrecto");
        divResultado.text("Incorrecto");
        divResultado.show(1000);
        divResultado.toggleClass("alert alert-danger", true);
    }


    /*
     * Casos:
     * 1-> Respuesta y conceptos iguales: Acertado
     * 2-> Respuesta y conceptos iguales: Fallo
     * 3-> Respuesta y conceptos NO iguales: Fallo
     * 4-> Respuesta y conceptos NO iguales: Acertado
     */

    switch (caso) {
        case 1:
            sumarPuntos(3);
            sacarCartas(parejaSeleccionada);
            break;
        case 2:
            sumarPuntos(-3);
            ocultarSeleccionados();
            break;
        case 3:
            sumarPuntos(-3);
            ocultarSeleccionados();
            break;
        case 4:
            sumarPuntos(1);
            ocultarSeleccionados();
            break;
    }

    desbloquearCartas();
    confirmarGane();
    //divResultado.hide(500);
    parejaSeleccionada = [];
}

function ocultarSeleccionados() {
    for (i = 0; i < parejaSeleccionada.length; i++) {
        $(parejaSeleccionada[i]).toggleClass("flipped");
    }
}

function sumarPuntos(puntos) {
    var puntaje = parseInt($("#puntaje").val());
    puntaje = puntaje + puntos;
    $("#puntaje").val(puntaje);
}

function sacarCartas(parejasSeleccionada) {
    for (var i = 0, max = parejasSeleccionada.length; i < max; i++) {
        for (var j = 0; j < cartas.length; j++) {
            var idSeleccionado = $(parejaSeleccionada[i]).attr("id");
            var idCarta = $(cartas[j]).attr("id");
            if (idSeleccionado === idCarta) {
                $(cartas[j]).find("#img-correcta").show();
                cartas.splice(j, 1);
                break;
            }
        }
    }

}

function confirmarGane() {
    if (cartas.length === 0) {
        clearInterval(intervaloContador);
        mostrarGanaste();
    }
}


function asignarListenersPregunta() {
    $("#respuesta-si").unbind("click");
    $("#respuesta-no").unbind("click");

    $("#respuesta-si").click(function() {
        var id1 = $(parejaSeleccionada[0]).attr("id");
        var id2 = $(parejaSeleccionada[1]).attr("id");


//        var idF1 = id1.substring(1);
//        var idF2 = id2.substring(1);

        if (id1 === id2) {
            confirmarRespuesta(true, 1);
        } else {
            confirmarRespuesta(false, 2);
        }




    });

    $("#respuesta-no").click(function() {
        var id1 = $(parejaSeleccionada[0]).attr("id");
        var id2 = $(parejaSeleccionada[1]).attr("id");

//        var idF1 = id1.substring(1);
//        var idF2 = id2.substring(1);

        if (id1 === id2) {
            confirmarRespuesta(false, 3);
        } else {
            confirmarRespuesta(true, 4);
        }
    });
}

function colocarCartas(carta) {
    //revolvemos las cartas
    cartas.push(carta);
    cartas = revolver(cartas);
    //Las colocamos en en tablero
    for (i = 0; i < cartas.length; i++) {
        $("#tablero").append(cartas[i]);
    }
}

function bloquearCartas() {
    for (i = 0; i < cartas.length; i++) {
        $(cartas[i]).unbind("click");
    }
}

function desbloquearCartas() {
    for (i = 0; i < cartas.length; i++) {
        asignarListeners(cartas[i]);
    }
}

var intervaloContador;

function iniciarContador(tiempo) {
    $("#timer").text(tiempo);
    intervaloContador = setInterval(function() {
        var tiempoDecr = parseInt($("#timer").text());

        tiempoDecr = tiempoDecr - 1;
        if (tiempoDecr === 100) {
            $("#timer").removeClass("buen-tiempo").addClass("poco-tiempo");
        }
        if (tiempoDecr === 0) {
            console.log("Se acabo el tiempo");
            clearInterval(intervaloContador);
            mostrarTiempoTerminado();
        }

        $("#timer").text(tiempoDecr);

    }, 1000);
}

function mostrarTiempoTerminado() {
    $("#titulo-modal").text("¡Se terminó el tiempo!");
    mostrarModal();
}

function mostrarGanaste() {
    $("#titulo-modal").text("¡Has Ganado!");
    mostrarModal();
}

function mostrarModal() {
    //Hacemos que el modal no se pueda cerrar
    $("#modal-jugar").click(function() {
        location.reload();
    });

    $("#modal-regresar").click(function() {
        window.location.href = "../sections/MenuStudent.html";
    });

    var texto = "Tu puntaje en el juego ha sido de " + $("#puntaje").val() + " puntos";
    $("#contenido-modal").text(texto);

    $("#modal-mensaje").modal({
        backdrop: 'static',
        keyboard: false
    });

    /**
     * Mantenimiento realizado por: Raúl Nahuat
     * Descripción: Se agregó la función para enviar el puntaje al servidor y cargar el ranking de puntajes antes de mostrar el modal.
     * Se conserva el guardado original y, después, se consulta el ranking ya existente para mapear en la tabla.
     * Tipo de mantenimiento: Correctivo
     */
    enviarDatosPuntaje().always(function() {
        cargarRankingPuntajes().always(function() {
            $("#modal-mensaje").modal('show');
        });
    });
}


function enviarDatosPuntaje() {
    //var usuario = $("#nombre-jugador").text();
    var puntaje = $("#puntaje").val();
    return $.get("../core/php/IngresarPuntaje.php", {
        idUsuario: idUsuario,
        idMateria: materia,
        dificultad: dificultad,
        puntaje: puntaje,
        parejasEncontradas: 9 - cartas.length/2
    }).done(function(data) {

    })
    .fail(function() {
        // MR 2026.4 | Preventivo | Manuel Cupul — el puntaje no se guardó en servidor
        Toast.warning("Tu puntaje no pudo guardarse en el servidor. El juego continuará normalmente.");
    });
}

/**
 * Mantenimiento realizado por: Raúl Nahuat
 * Descripción: Se agregó la función para cargar el ranking de puntajes desde el servidor y mostrarlo en la tabla del modal. 
 * Se reutilizó el dispatcher existente de puntajes por materia para obtener nombre y score, y se mapea en la tabla del modal.
 * Tipo de mantenimiento: Correctivo
 */
function cargarRankingPuntajes() {
    var $tabla = $("#mejores-puntajes tbody");
    $tabla.empty();

    //Se reutilizó el dispatcher existente de puntajes por materia para obtener nombre y score.
    return $.get("../core/php/PuntajeDispatcher.php", {
        tipo: 2,
        idMateria: materia
    }).done(function(data) {
        var puntajes = typeof data === "string" ? $.parseJSON(data) : data;

        if (!puntajes || !puntajes.length) {
            return;
        }

        for (var i = 0; i < puntajes.length; i++) {
            $tabla.append("<tr><td>" + puntajes[i].nombre + "</td><td>" + puntajes[i].puntaje + "</td></tr>");
        }
    });
}

function salirJuego() {
    location.href = "MenuStudent.html";
}

function revolver(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;
    while (0 !== currentIndex) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }
    return array;
}


