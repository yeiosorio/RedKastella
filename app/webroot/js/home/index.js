
// definicion de variables globales para el index

$('#wizardPopRequest').smartWizard({
    keyNavigation: "false"
});

$('#modalLogin').on('shown.bs.modal', function () {
    $('#username').focus();
})

// Se redirecciona al home con el termino de busqueda del campo de busqueda global
function globalSearchContracts(){

    var searchInput = $("#searchInput").val()
    
    window.location.href = baseUrl + 'Home/searchContracts/'+searchInput;

}

$(".login-form").submit(function(e){
    $('.loading').show();
    $.ajax({
        url: baseUrl + 'Users/loginRest',
        type: 'post',
        dataType: 'json',
       data: new FormData(this),
       processData: false,
       contentType: false,
       success: function (data) {
        console.log(data)

        if(data.success == false){
            $('.alertLogin').html('<div style="font-size: 14px;" class="alert alert-danger alert-dismissable in"> <a href="#" style="margin-top: -12px;" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.message+'</div>')
            setTimeout(() => {
                $('.alert-dismissable').hide();
            }, 4000);

            $('.loading').hide();
        }else if(data.success == true){
            $('.loading').hide();
            window.location.href = baseUrl + "Home/searchContracts";
        }
       }
    });

    return false;
   	e.preventDefault();

});

$('.addUserForm').submit(function(e){
    var form = $(this);

    $.ajax({
        url: baseUrl + 'Users/registerRest',
        type: 'post',
        data: new FormData(this),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {

            if(response.success == true){

                $.ajax({
                        url: baseUrl + 'Users/loginRest',
                        type: 'post',
                        data: new FormData(form[0]),
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response)
                            if(response.success == false){

                                console.log(response.errors.password[0]);
                            }else if(response.success == true){
                                window.location.href = baseUrl + "Home/searchContracts/welcome";
                            }

                        }
                    });
                }else{    						

                }

            }
    });
    e.preventDefault();

});

var countContracts = 0
function lastContractsPub(){
    $('.contenido').html("");
    listContracts = JSON.parse(listContracts)
    console.log(listContracts)

    var arrayStr;
    var nom_grupo;
    var strFound;
    var strFound2; 

    // iteracion seccion de cards de contratos generada dinamicamente con datos de la API de colombia compra -->
    $.each(listContracts, function(key, value) {
       
        if (value.nom_grupo != undefined) {
             arrayStr = value.nom_grupo.split(' ');
             nom_grupo = arrayStr[1];

        }

        var fecha_carga = moment(value.fecha_carga);
        var now = moment();
        var pubDate = ""

        var diffTime = now.diff(fecha_carga, 'hours');
        if (diffTime == 1) {
             pubDate = "Hace "+ diffTime +" Hora"
        }else{
             pubDate = "Hace "+ diffTime +" Horas"
        }

        if (diffTime > 24) {
            diffTime = now.diff(fecha_carga, 'days');
            if (diffTime == 1) {
                pubDate = "Hace "+ diffTime +" dia"
            }else{
                pubDate = "Hace "+ diffTime +" dias"
            }
            if (diffTime > 15) {
                diffTime = 0
                moment.locale("es");
                moment().format("ll");
                pubDate =  moment(value.fecha_carga).format('MMMM DD YYYY');
            }
        }
     
           
            $('.contenido').append(`
                <div class="col-md-4 col-lg-4 col-xl-3">
                <div class="father">
                    <div class="front">
                        <header>
                            <div class="bkg"></div>
                            <img src="`+baseUrl+`img/uso-final.png" alt="Picture">
                            <p style="cursor: pointer;" onclick="getContractsDetails('`+key+`');">`+nom_grupo+`</p>
                        </header>
                        <div class="experience">
                        <h3 style="cursor: pointer;" onclick="getContractsDetails('`+key+`');">`+value.detalle_objeto_proceso+`</h3>
                            <p>${value.municipio_entidad} - ${value.departamento_entidad} </p>
                            <div class="valor">
                                <p><b>Valor estimado: </b>$`+value.cuantia_contratar+`</p>
                                <svg xmlns="http:/www.w3.org/2000/svg" xmlns:xlink="http:/www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="314.065px" height="314.065px" viewBox="0 0 314.065 314.065" style="enable-background:new 0 0 314.065 314.065;" xml:space="preserve"><g><g id="_x34_96._Down"><g><path d="M190.333,149.972l-17.602,17.6v-65.497c0-8.666-7.036-15.701-15.701-15.701c-8.668,0-15.701,7.035-15.701,15.701v65.497     l-17.607-17.6c-6.133-6.129-16.072-6.129-22.201,0c-6.133,6.129-6.133,16.078,0,22.202l44.408,44.41h-0.008     c3.07,3.069,7.083,4.6,11.108,4.6c2.008,0,4.022-0.384,5.903-1.149c1.892-0.766,3.663-1.907,5.198-3.442l44.402-44.41     c6.127-6.128,6.127-16.072,0-22.201C206.411,143.834,196.46,143.834,190.333,149.972z M235.533,21.061     C160.438-22.295,64.414,3.436,21.063,78.531c-43.356,75.089-17.633,171.117,57.464,214.478     c75.087,43.348,171.119,17.62,214.476-57.467C336.364,160.443,310.62,64.419,235.533,21.061z M265.801,219.841     c-34.688,60.075-111.503,80.653-171.574,45.961C34.158,231.118,13.565,154.308,48.25,94.232     c34.683-60.078,111.499-80.662,171.578-45.971C279.899,82.936,300.485,159.762,265.801,219.841z" style="fill: rgb(255, 255, 255);"></path></g></g></g>
                                </svg>
                                <div class="bkg2">
                                    <p><b>Fecha de la publicación:</b> `+pubDate+`</p>
                                    <div class="final star_`+key+`" id="star">
                                        <section onclick="verifiedFavorites(`+key+`, '`+value.numero_constancia+`', '`+nom_grupo+`','`+value.detalle_objeto_proceso+`', `+value.cuantia_contratar+`, '`+pubDate+`');" class="Button star_`+key+`"></section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        
        countContracts++
        if (countContracts == 12) {
            return false;
        }
       
    });
}

lastContractsPub();

function getContractsDetails(index){

    var dataContract = listContracts[index]
    
    $('.spinner-cube').show();
    $('.gridDetailInfo').html("")
    $('.detailColumns').html("")
    $('#modalContractsDetails').modal('show')

    // Llamado a la api de colombiacompra para complementar los detalles del contrato
    $.ajax({
        type:'POST',
        data:{
            num_constancia: dataContract.numero_constancia
        },
        dataType: "json",
        url: baseUrl + "Home/getContractsDetails/",
        success: function(response) {
            $('.spinner-cube').hide();

                var contractDocuments = JSON.parse(response.contractDocuments);
                console.log(contractDocuments)

                var title = dataContract.numero_proceso
                var contractType = dataContract.tipo_contrato
                var nom_clase = dataContract.nom_clase
                var detailDesc = dataContract.detalle_objeto_proceso
                var entidad = dataContract.nomb_entidad
                var contratista = dataContract.nom_encargado_usuario
                var amount = dataContract.cuantia_def_contratar
                var municipios_ejecucion = dataContract.municipios_ejecucion
                var dir_contratista = dataContract.dir_contratista
                var estado_proceso = dataContract.estado_proceso
                var correo_encargado_usuario = dataContract.correo_encargado_usuario
                var stateColor = (estado_proceso == "Celebrado") ? "color: #f15d00;" : "color: grey";

                $('.titleDetail').text('Detalles del proceso numero: '+title);

                // Detalles del contrato
                $('.detailColumns').append(`
                        <tr>
                            <td>Tipo de Contrato</td>
                            <td>`+contractType+`</td>
                        </tr>
                        <tr>
                            <td>Clase</td>
                            <td>`+nom_clase+`</td>
                        </tr>
                        <tr>
                            <td>detalle del objeto</td>
                            <td>`+detailDesc+`</td>
                        </tr>
                        <tr>
                            <td>Unidad/Subunidad ejecutora (SIIF)</td>
                            <td>`+entidad+`</td>
                        </tr>
                        <tr>
                            <td>Nombre del Contratista</td>
                            <td>`+contratista+`</td>
                        </tr>
                        <tr>
                            <td>Cuantía</td>
                            <td>`+amount+`</td>
                        </tr>
                        <tr>
                            <td>Lugar Ejecución</td>
                            <td>`+municipios_ejecucion+`</td>
                        </tr>
                        <tr>
                            <td>Dirección Contratista</td>
                            <td>`+dir_contratista+`</td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td style="${stateColor}r">`+estado_proceso+`</td>
                        </tr>
                        <tr>
                            <td>Correo Encargado</td>
                            <td>`+correo_encargado_usuario+`</td>
                        </tr>
                    `)

            
            // Iteracion de documentos asociados al contrato
            $.each(contractDocuments, function(key, value) {

                var fecha_ultima_modificacion = moment(value.fecha_ultima_modificacion).format('MM/DD/YYYY, h:mm a');
                var typeFile = '';

                $('.gridDetailInfo').append(`
                    <tr>
                        <td>`+value.nombre+`</td>
                        <td>`+value.descripcion+`</td>
                        <td>`+fecha_ultima_modificacion+`</td>
                        <td><img onclick="printPdfContract('`+value.b64+`');" style="width: 41px; cursor:pointer;" src="`+baseUrl+`img/icon-pdf.png" alt="PDF"></td>
                        <td>`+value.tamano+` kb</td>
                        <td>`+value.version+`</td>
                    </tr>
                `)
        
            })

        }

    });

}

    function printPdfContract(base64Pdf){

        var printWindow = window.open("data:application/octet-stream;base64,"+ base64Pdf);
        
    }

    // Funcion para navegar entre modal de login y registro
  function openRegisterModal(){

    $('#modalLogin').modal('hide');
    setTimeout(() => {
        $('#modalRegister').modal('show')
    }, 200);

  }
  // Funcion para volver a la modal del login
  function backToLogin(){

    $('#modalRegister').modal('hide')
    setTimeout(() => {
        $('#modalLogin').modal('show')
    }, 200);

  }


  function verifiedFavorites(index, num_constancia, objeto_contrato, detalle_proceso, cuantia, pubDate) {

    // Si hay sesion
    if (loggedUser) {
        // Se pregunta por el plan que registra el cliente
        // De no registrar aun se lanza la modal de planes
        $.ajax({
            type:'POST',
            dataType: "json",
            url: baseUrl+"Home/getPlanType",
            success: function (data) {

                // No se ha superado la cuota de favoritos respecto al plan registrado
                if (!data) {
                    showModalPlans();

                    setTimeout(() => {
                        $('.star_'+index).removeClass("active");
                   
                    }, 50);
                }else{
                    ValidateInfoPlan(index, num_constancia, objeto_contrato, detalle_proceso, cuantia, pubDate);
                }
               
            }
        });
       
    }else{
        showModalPlans();

        setTimeout(() => {
            $('.star_'+index).removeClass("active");
           
        }, 100);
    }
   
}

  // Se agrega la informacion del contrato como favorito
function ValidateInfoPlan(index, num_constancia, objeto_contrato, detalle_proceso, cuantia, pubDate){
    $.ajax({
        type:'POST',
        dataType: "json",
        url: baseUrl+"Home/ValidateInfoPlan/",
        success: function (data) {
            // No se ha superado la cuota de favoritos respecto al plan registrado
            if (data) {
                // Se agrega la informacion del contrato como favorito
                addFavorites(num_constancia, objeto_contrato, detalle_proceso, cuantia, pubDate);
            }else{
                showModalPlans();

                setTimeout(() => {
                    $('.star_'+index).removeClass("active");
               
                }, 100);
            }
        }
    });
}

function addFavorites(num_constancia, objeto_contrato, detalle_proceso, cuantia, pubDate){

    var data = {
        created: moment().format('YYYY-MM-DD h:mm:ss'),
        author: '',
        category: '',
        ciudad: '',
        contenido: detalle_proceso,
        departamento: '',
        link: '',
        nombre: objeto_contrato,
        title: '',
        valor: cuantia,
        num_constancia: num_constancia,
        fecha_cargue :  moment(pubDate).format('YYYY-MM-DD h:mm:ss'),
    }

    $.ajax({
        type:'POST',
        data: {interest: data},
        dataType: "json",
        url: baseUrl+"InterestContracts/saveInterestContract",
        success: function (data) {

        }
    });
}

    // Funcion para navegar entre modal de login y registro
    function showModalPlans(){
       
        console.log(loggedUser)
        $('#modalStepPlans').modal('show');
        // $('#modalLogin').modal('hide');
        // setTimeout(() => {
        //     $('#modalRegister').modal('show')
        // }, 200);

    }
  