/**
 * Created by Andre on 15/04/2016.
 */
$(function(){

    $('#close').click(function(){

        $.ajax({
            url: '../core/php/CloseSession.php',
            type: 'get',
            success: function (response) {
                verifySession(response)

            },
            error:function(){
                alert("Error en el servidor");
            }
        });
    });
    $(document).ready(function(){

        $.ajax({
            url: '../core/php/VerifySession.php',
            type: 'get',
            success: function (response) {

                verifySession(response);
            },
            error:function(){
                alert("Error en el servidor");
            }
        });
    });
});





function verifySession(response){
    if(response==""){

        location.href = "../Login.html";
    }
}





