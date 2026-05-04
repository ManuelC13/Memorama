$(function(){
    $.get("../core/php/MateriaDispatcher.php").done(function(data){
        var LOL = $.parseJSON(data);
        //materias.forEach(function(value, index){
        //    var selector = "<option value=" + value[index].id + ">" + value[index].name + "</option>";
        //    $("#materia").html(selector);
        //});

        for(var i = 0; i < LOL[0].length; i++){
            var selector = "<option value=" + LOL[0][i].id + ">" + LOL[0][i].name + "</option>";
            $("#materia").append(selector);
        }
    });
});


