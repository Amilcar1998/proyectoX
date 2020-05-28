$(document).ready(()=>{
    $("#botones").hide();
    $("#enviar").on("click",()=>{
        var cajitas=$("#cantidad").val();
        for(var i=1; i<=cajitas;i++)
        {
            $("#inputs").append(
                "<div class='p-3 mt-4'>"+
                    "<h3>#"+i+"</h3>"+
                    "<label>Nombres:</label>"+
                    "<input type='text' name='nombre[]' class='form-control'>"+
                    "<label>Apellidos:</label>"+
                    "<input type='text' name='apellidos[]' class='form-control'>"+
                    "<label>Edad:</label>"+
                    "<input type='number' name='edad[]' class='form-control'>"+
                    
                    "<label>Nacionalidad:</label>"+
                    "<select placeholder='seleccione su pais' class='form-control'>"+
                    "<option value='1'>EL SALVADOR</option>"+
                    "<option value='2'>GUATEMALA</option>"+
                    "<option value='3'>HONDURAS</option>"+
                    "<option value='4'>NICARAGUA</option>"+
                    "<option value='5'>COSTA RICA</option>"+
                    "</select>"+

                    "<label>Pasatiempos:</label>"+
                    "<label class='btn btn-success active'>"+
                    "<input type='checkbox' autocomplete='off' checked>"+
                    "<span class='glyphicon glyphicon-ok'></span>Jugar futboll"+
                    "</label>"+
                    "<label class='btn btn-warning active'>"+
                    "<input type='checkbox' autocomplete='off' checked>"+
                    "<span class='glyphicon glyphicon-ok'></span>Leer libros"+
                    "</label>"+
                    "<label class='btn btn-primary active'>"+
                    "<input type='checkbox' autocomplete='off' checked>"+
                    "<span class='glyphicon glyphicon-ok'></span>Natacion"+
                    "</label>"+
                    "<label class='btn btn-Danger active'>"+
                    "<input type='checkbox' autocomplete='off' checked>"+
                    "<span class='glyphicon glyphicon-ok'></span>Juegos de mesa"+
                    "</label>"+

                    "<label>Ingrese su direccion de domicilio:</label><br>"+
                    "<textarea class='form-control' rows='4'></textarea>"+

                    "<label>Cargo:</label><br>"+
                    "<select placeholder='pais' class='form-control'>"+
                    "<option value='6'>PROGRAMADOR</option>"+
                    "<option value='7'>DESARROLLADOR</option>"+
                    "<option value='8'>ANALISTA</option>"+
                    "<option value='9'>DISEÑADOR</option>"+
                    "<option value='10'>TESTER</option>"+
                    "</select>"+

                "</div>"
                );
        }
        $("#numero").hide();
        $("#botones").show();
    });
    $("#btnReiniciar").on("click",()=>{
        $("#inputs").empty();
        $("#numero").show();
        $("#botones").hide();
        $("#resultados").empty();
    });
    $("#btnEnviar").on("click",()=>{
        $.ajax({
            type:"POST",
            url:"php/formularios.php",
            data:$("#frm").serialize(),
        }).done((data)=>{
            $("#resultados").html("<h3 class='text-success'>Datos enviados exitisamente!:</h3>"+data);
        }).fail(()=>{
            alert("server side error!");
        })
    });
});