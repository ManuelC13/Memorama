/**
 * Created by Andre on 15/04/2016.
 */

var connection;
var opened = false;

//MULTIPLAYER DATA
var isMyTurn = false;
var playerNumber;

//DEFINES MULTIPLAYER EVENTS
var HELLO = 0;
var FLIPP = 1;
var RESET_FLIPPED_CARDS = 2;
var ANSWER_SELECTION = 3;
var GREETING = 4;

//DEFINES SERVER SERVICES EVENTS
var REQUEST_PLAYER_NUMBER = 10;

$(function(){
    requestServerSocket();
    connection = new WebSocket('ws://localhost:4040');
    
     connection.onopen = function () {
        opened = true;
         
        setPlayerNumber();
        handleSendMessage(GREETING, "Ya llegué bitches");
     };

     connection.onerror = function (error) {
          $('div').html('WebSocket Error ' + error);
     };

     connection.onmessage = function (e) {
         handleEvent(e.data);
     };
});

function requestServerSocket(){
    
//    $.ajax({
//        url: '../core/php/MultiplayerWebSocket/Sockets/WebSocketStarter.php',
//        method: 'get',
//        beforeSend: function(){},
//        success: function(response){}, 
//        error: function(e){}
//    });
}

function notify(event, selection){
    if(isMyTurn === true){
        handleSendMessage(event, selection);
    }
}

function handleSendMessage(event, param1){
     switch (event) {
        case FLIPP:
            var data = {
                event: event,
                selection: param1 
            }
            data = JSON.stringify(data);
            connection.send(data);
        break;
        
        case ANSWER_SELECTION:
            var data = {
                event: event,
                selection: param1 
            }
            data = JSON.stringify(data);
            connection.send(data);
        break;
        
        case RESET_FLIPPED_CARDS:
            var data = {
                event: event 
            }
            data = JSON.stringify(data);
            connection.send(data);
        break;
        
        case GREETING:
            var data = {
                event: event, 
                msg: param1,
                playerPanel: $("#tablero_puntaje").html()
            }
            data = JSON.stringify(data);
            connection.send(data);
        break;
        
        default:
            //NOTHING IN THIS MOMMENT
            break;
    }
}
function handleEvent(data){
    var datos = $.parseJSON(data);
    var event = datos.event;
    
    if(event == FLIPP){
        $("#"+datos.selection).click();
    }
    
    if(event == ANSWER_SELECTION){
        alert(datos.selection);
    }
    
    if(event == RESET_FLIPPED_CARDS){
         alert(datos.event);
    }
    
    if(event == HELLO){
        alert(datos.playerName + " dice: "+datos.msg);
    }
    
    if(event == REQUEST_PLAYER_NUMBER){
        playerNumber = datos.playerName;
        if(playerNumber === 1) {isMyTurn=true;}
        $('.panel div h2').html("Player " + playerNumber);
    }
    
    if (event === GREETING) {
        $(".panel").append(datos.playerPanel);
        alert(datos.msg);
    }
}

function setPlayerNumber(){
    var data = {
            event: REQUEST_PLAYER_NUMBER
        }
        data = JSON.stringify(data);
        connection.send(data);
}








