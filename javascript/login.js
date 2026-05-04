/**
 * Created by Andre on 12/03/2016.
 */


$(function(){
    $("#login").click(function(){

        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        if(username == "" || password== ""){
            verifyUser(null);
            return;
        }

        var data = {
            username: username,
            password: password
        };
        $.ajax({
            url: 'core/php/Login.php',
            data:data,
            type: 'post',
            beforeSend: function () {
                $(loginResponse).html("Procesando, espere por favor...");
            },
            success: function (response) {
                verifyUser(response);
            },
            error:function(){
                alert("Error en el servidor");
            }
        });
    })
});

function verifyUser(response){
    try {
        var userResponse = JSON.parse(response);
        var locationToInsert =  $("#loginResponse");


        if (userResponse !== null) {
            var student = 0;
            var teacher = 1;

            if (userResponse[0].type == student) {
                location.href = "sections/MenuStudent.html";
            } else if (userResponse[0].type == teacher) {
                location.href = "sections/MenuTeacher.html";
            }


        } else {

            insertContentToPage("resources/responseLogin.html", locationToInsert);

        }
    } catch (e){
        console.log(e);
    }
}

