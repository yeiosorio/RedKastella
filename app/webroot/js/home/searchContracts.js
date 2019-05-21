/**
 * @author Yeison osorio <yei_osorio@hotmail.com>
 * @desc Logica para filtrado y renderizado de contratos en la vista de buscador global
 * @date 22 dic 2018
 */

var listSubcategory = new Array();
var slideValues = new Array();
var category = 0
var activePlan;
listContracts = JSON.parse(listContracts)

// Evento del buscador para filtrar y mostrar los contratos buscados por palabra clave
$(".searchContract").keypress(function(e) {

    $('.contenedor-contratos').html("");

    var keySearch = $('#searchContract').val();
    var selectTypeContract = $('#searchContract').val();

    if(e.which == 13) {
       
        $.ajax({
            type:'POST',
            dataType: "json",
            url: baseUrl+"Home/getBySearch",
            success: function (data) {
                console.log(data);
                // Objeto con contratos
                var dataArray = JSON.parse(data);

                if (dataArray.status == "OK") {
                    // Si se selecciono un filtro  de tipo de contrato
                    var listContracts = dataArray.listContracts;
                    var filterByType = (selectTypeContract != "") ? selectTypeContract : "" ;

                    console.log(listContracts)
                    console.log(searchInput)
                
                    var arrayStr;
                    var nom_grupo;
                    var strFound;
                    var strFound2; 
                
                    // iteracion seccion de cards de contratos generada dinamicamente con datos de la API de colombia compra -->
                    $.each(listContracts, function(key, value) {
                       
                        strSearch = searchInput.charAt(0).toUpperCase() + searchInput.slice(1);
                
                        if (value.nom_grupo != undefined) {
                             arrayStr = value.nom_grupo.split(' ');
                             nom_grupo = arrayStr[1];
                
                             // Se busca la palabra del buscador dentro de la informacion de los contratos que se obtuvieron de la API
                             // y se filtran para ser mostrados en la vista
                             strFound = nom_grupo.indexOf(strSearch);
                             strFound2 = value.detalle_objeto_proceso.indexOf(strSearch);
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
                     
                        if (strFound != -1 || strFound2 != -1) {
                           
                            $('.contenedor-contratos').append(`
                                <div class="col-md-6 col-lg-6 col-xl-4"><div class="father">
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
                        }
                    });
                    
                }else{
                    // No se encontraron contratos
                }

            }
        });
    }

});

$('#wizardPopRequest').smartWizard({
    keyNavigation: "false"
});

// Cuando se realiza busqueda
if (searchInput != "welcome" && searchInput != false) {
   
    $('.contenedor-contratos').html("");
   
    console.log(listContracts)
    console.log(searchInput)

    var arrayStr;
    var nom_grupo;
    var strFound;
    var strFound2; 

    // iteracion seccion de cards de contratos generada dinamicamente con datos de la API de colombia compra -->
    $.each(listContracts, function(key, value) {
       
        strSearch = searchInput.charAt(0).toUpperCase() + searchInput.slice(1);

        if (value.nom_grupo != undefined) {
            nom_grupo = value.nom_grupo.substring(3, value.nom_grupo.length -1);

             // Se busca la palabra del buscador dentro de la informacion de los contratos que se obtuvieron de la API
             // y se filtran para ser mostrados en la vista
             strFound = value.nom_grupo.indexOf(strSearch);
             strFound2 = value.detalle_objeto_proceso.indexOf(strSearch);
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
     
        if (strFound != -1 || strFound2 != -1) {
           
            $('.contenedor-contratos').append(`
                <div class="col-md-6 col-lg-6 col-xl-4"><div class="father">
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
        }
    });
}


// Seccion para cuando se busca por preferencias
// Se renderizan la informacion de la api y se filtran de acuerdo a las preferencias
if (dataSearchPreference == 1) {
    console.log(searchPreference)

    // Array con lista de categorias seleccionadas para la busqueda
    var searchPreference = JSON.parse(localStorage.searchPreference);

    var deptPreference = localStorage.deptPreference
    var rango_cuantia_min = localStorage.rango_cuantia_min
    var rango_cuantia_max = localStorage.rango_cuantia_max
    category = localStorage.category

    setCurrencyValues(rango_cuantia_min, rango_cuantia_max);
   
    $('.deptPreference').val(deptPreference);
    getContractSubCategories(category);

    $('.contenedor-contratos').html("");
   
    console.log(listContracts)

    // iteracion seccion de cards de contratos generada dinamicamente con datos de la API de colombia compra -->
    $.each(listContracts, function(key, value) {
       
        var arrayStr = value.nom_grupo.split(' ');
        var nom_grupo = arrayStr[1];

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
     
     
       
        // Se iteran las categorias seleccionadas para filtrarse la informacion de contratos
        $.each(searchPreference, function(key2, category) {

            console.log('depto');
            console.log(deptPreference);
            console.log(value.departamento_entidad);
            console.log('categoria');
            console.log(category);
            console.log(value.nom_segmento);

            // Se busca coincidencias en los nombres de los contratos
            // y se filtran para ser mostrados en la vista
            var strCategory = value.nom_segmento.indexOf(category);
            var strDepto = value.departamento_entidad.indexOf(deptPreference);
            console.log('---------------')
            console.log(strCategory)
            console.log(strDepto)

            // Si coincide la categoria de busqueda mostramos el contrato en la vista
            if (strCategory != -1 && strDepto != -1) {
                $('.contenedor-contratos').append(`
                <div class="col-md-6 col-lg-6 col-xl-4"><div class="father">
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
            }

        });
       
    });


}

// Al registrarse
if (searchInput == "welcome") {
     
    $('.contenedor-contratos').append(`
        <div class="col-md-12 col-lg-12 col-xl-12" style="padding: 101px;">
            <div style="text-align:center; ">
                <img src="`+baseUrl+`img/person.png" style="margin-right:2%; width: 20%; margin: 27px;">
            </div>
            <div style="text-align:center; ">
                <h2>Bienvenido</h2>
                <label style="font-size: 28px">Kastella busca entre los mas de 100.000 contratos Publicados en SECOP 1, SECOP 2 y entidades privadas</label>
                <br>
                <label style="margin-top: 16px;">Configura tus </label><b> preferencias de busqueda en el panel izquierdo y click en buscar</b>
                <br>
                <label>Kastella siempre recordará tus preferencias para que no pierdas ninguna oportunidad.</label>
            </div>
        </div>
    `);

}

function getContractSubCategories(idCategory){
    $('.checksSubcategory').html("");

    /**
     * Obtencion de las categorias
     */
    $.ajax({
        data: {
            id: idCategory
        },
        type:'POST',
        dataType: "json",
        url: baseUrl+"ContractPreferences/getContractSubCategories/",
        success: function (data) {

            console.log(data);
            $.each(data.contractTypes, function(key, value) {
                // Se renderiza lista de categorias de contratos de acuerdo al tipo elejido
                $('.checksSubcategory').append(`
                    <div class="custom-control custom-checkbox">
                        <input name="checkSubcategory" type="checkbox" class="custom-control-input" id="customCheck_`+value.ContractSubcategory.id+`" value="`+value.ContractSubcategory.name+`">
                        <label class="custom-control-label" for="customCheck_`+value.ContractSubcategory.id+`" style="font-size: 14px;">`+value.ContractSubcategory.name+`</label>
                    </div>
                `)
           
            })

        }
    });
}

// Funcion encargada de buscar de acuerdo a las preferencias establecidas
function searchPreferenceFilter(){

    var deptPreference = $('.deptPreference').val()
    var rango_cuantia_min = $('.currencyField1').val();
    var rango_cuantia_max = $('.currencyField2').val();

    // Se recojen las subcategorias checkeadas
    $("input[name=checkSubcategory]").each(function (index) {
        if($(this).is(':checked')){
            listSubcategory.push($(this).val())
        }
     });

     if (listSubcategory.length > 0) {
       
        localStorage.searchPreference = JSON.stringify(listSubcategory)
        localStorage.deptPreference = deptPreference
        localStorage.rango_cuantia_min = rango_cuantia_min;
        localStorage.rango_cuantia_max = rango_cuantia_max;
        localStorage.category = category;


        // Guardado de la busqueda
        $.ajax({
            type:'POST',
            dataType: "json",
            data: {
                listSubcategory: localStorage.searchPreference,
                deptPreference: deptPreference,
                rango_cuantia_min: rango_cuantia_min,
                rango_cuantia_max: rango_cuantia_max,
                category: category,
            },
            url: baseUrl + "Home/saveLastSearch/",
            success: function(response) {
               
            }
        });
        setTimeout(() => {
            window.location.href = baseUrl + 'Home/searchContracts/searchPreference';
           
        }, 100);

       

         
     }else{
        //  Mensaje flash alertando que se debe elejir al menos una categoria y tipo de contrato

     }

}

/**
* Función usada para obtener los departamentos y agregarlos a un dropdown especificado
*
*/ 
function getDepartments(){

    $.ajax({
        type:'GET',
        dataType: "json",
        url: baseUrl + "Departments/getAllDepartments/",
        success: function(response) {
           $.each(response, function(key, value) {
                $('.selectDept').append(`
                    <option value="`+value.Departments.name+`">`+value.Departments.name+`</option>
                `)
            })
        },
        error: function(response) {
        }
    });

}

//llamamos la función para listar departamentos
getDepartments();
 

$(".slider-values").slider({
    min: 0,
    max: 5000000000,
    step: 5000000,
    orientation: "horizontal",  
    selection: "after",
    tooltip: "show",    
    value: [0,100000000]
});

setCurrencyValues(0, 500000000);

/**
 * Inicialización del objeto slider en el tag de clase .slider
 * Detectamos ademas el evento de slide (cuando se mueve) para obtener los valores actuales siempre
 */
$('.slider-values').slider().on('slide', function(ev){
   
    //asignamos los valores
    setCurrencyValues(ev.value[0],ev.value[1]);
});

/**
 * Función que ingresa y da formato a los valores seleccionados por el usuario
 * @param {int} valor mínimo
 * @param {int} valor máximo
 */
function setCurrencyValues(min,max){
   
    //almacenamos los valores en slideValues
    slideValues[0] = min;
    slideValues[1] = max;

    /**
     * asignamos los valores a unos campos escondidos
     */
    $('.currencyField1').val(min);
    $('.currencyField2').val(max);

    /**
     * Utilizamos un plugin para formatear los valores
     * primero formateamos los valores en los campos escondidos usando la funcion formatCurrency
     */
    $('.currencyField1').formatCurrency();
    $('.currencyField2').formatCurrency();

    /**
     * Luego los mostramos en unos spans aplicando la misma función formatCurrency
     */
    $('.currencyField1').formatCurrency('.currencyFieldLabel1');
    $('.currencyField2').formatCurrency('.currencyFieldLabel2');
}


$('#modalLogin').on('shown.bs.modal', function () {
    $('#username').focus();
})

// Se redirecciona al home con el termino de busqueda del campo de busqueda global
function globalSearchContracts(){

    var searchInput = $("#searchInput").val()
   
    window.location.href = baseUrl + 'Home/searchContracts/'+searchInput;

}

$(".login-form").submit(function(e){
    
    e.preventDefault();
    $('.loading').show();
    
    $.ajax({
        url: baseUrl + 'Users/loginRest',
        type: 'post',
        dataType: 'json',
       data: new FormData(this),
       processData: false,
       contentType: false,
       success: function (data) {
        $('.loading').hide();

        if(data.success == false){
            $('.alertLogin').html('<div style="font-size: 14px;" class="alert alert-danger alert-dismissable in"> <a href="#" style="margin-top: -12px;" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.message+'</div>')
            setTimeout(() => {
                $('.alert-dismissable').hide();
            }, 4000);
            
        }else if(data.success == true){
            
            location.reload();
        }
       }
    });

});




$(".loginStepPlans").submit(function(e){
    $('.loading').show();
    $.ajax({
        url: baseUrl + 'Users/loginRest',
        type: 'post',
        dataType: 'json',
       data: new FormData(this),
       processData: false,
       contentType: false,
       success: function (data) {
           
        $('.loading').hide();
        if(data.success == false){
            $('.alertLogin').html('<div style="font-size: 14px;" class="alert alert-danger alert-dismissable in"> <a href="#" style="margin-top: -12px;" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.message+'</div>')
            setTimeout(() => {
                $('.alert-dismissable').hide();
            }, 4000);
        }else if(data.success == true){
            if (activePlan == 1) {
                registerPlan();
            }else{
                loadNextStep();
            }
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
                                
                            }else if(response.success == true){
                                window.location.href = baseUrl + "Home/searchContracts/welcome";
                            }

                        }
                    });
                }else{                          
                    $('.alertLogin').html('<div style="font-size: 14px;" class="alert alert-danger alert-dismissable in"> <a href="#" style="margin-top: -12px;" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+data.message+'</div>')
                    setTimeout(() => {
                        $('.alert-dismissable').hide();
                    }, 4000);
                }

            }
    });
    e.preventDefault();

});


function getContractsDetails(index){
    $('.detailColumns').html("");
    $('.gridDetailInfo').html("");

    var dataContract = listContracts[index]

    if (loggedUser) {

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
    }else{
        showModalPlans();
    }

}

function printPdfContract(base64Pdf){
    console.log(base64Pdf);

    var printWindow = window.open("data:application/octet-stream;base64,"+ base64Pdf);
    
}

    // Funcion para navegar entre modal de login y registro
    function openRegisterModal(){

        $('#modalLogin').modal('hide');
        setTimeout(() => {
            $('#modalRegister').modal('show')
        }, 200);

    }

        // Funcion para navegar entre modal de login y registro
    function showModalPlans(){
       
        $('#modalStepPlans').modal('show');

        if (loggedUser && loggedUser.plan_id == "1") {
            $('.getFreeAccount').attr("disabled", true)
        }else{
            $('.getFreeAccount').attr("disabled", false)
        }

    }

    // Funcion para volver a la modal del login
    function backToLogin(){

        $('#modalRegister').modal('hide')
        setTimeout(() => {
            $('#modalLogin').modal('show');
        }, 200);

    }

  //
    function goPayUPayment(){

        let today = moment().format('YYYY-MM-DD');
        let codePay = today + loggedUser.id

        // Se hace un llamado a php para encriptar firma que se envia a PayU en MD5.
        $.post(baseUrl+'Home/generateSignatureHash', {
            ApiKey: '4Vj8eK4rloUd272L48hsrarnUA',
            merchantId: '508029',
            referenceCode: 'TestPayU',
            activePlan: activePlan,
            currency: 'COP'
        }, 
        function(response) {

            var data = JSON.parse(response);
            if (data.success) {

                console.log(data.signatureHash)
                
                $("input[name=signature]").val(data.signatureHash);
                $("input[name=amount]").val(data.planValue);
                $("input[name=extra1]").val(loggedUser.id);
                $("input[name=extra2]").val(loggedUser.name);

                // Primero se valida que el username sea un email valido y se adjunta al formulario de PayU
                if (valid_email_address(loggedUser.username) ) {
                    $("input[name=buyerEmail]").val(loggedUser.username);
                }else{
                    $("input[name=buyerEmail]").val(loggedUser.email);
                }
                
                $("#formPayU").submit();
                setTimeout(() => {
                    // window.location.href = baseUrl + 'Home/searchContracts';
                }, 100);
                
            }
        })
       
    }

    // Validacion de email
    function valid_email_address(email) {
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}

 
    function chosePlan(planId) {
        // Se establece el plan seleccionado para luego decidir si enviar al boton de pago o registrar el plan
        activePlan = planId
        loadNextStep();
       
    }
    

    function registerPlan(){
        $.ajax({
            type:'POST',
            data: {
                planId:activePlan
            },
            dataType: "json",
            url: baseUrl+"Home/addPlan",
            success: function (data) {
                if (data) {
                    window.location.href = baseUrl + 'Home/searchContracts';
                }
                
            }
        });
    }

    function loadNextStep() {
        $('#wizardPopRequest').smartWizard("goForward");
    }

    function switchRegister() {
        $('.wrapRegister').css('display', 'block')
        $('.wrapLogin').css('display', 'none')
    }

    function backSession() {
        $('.wrapRegister').css('display', 'none')
        $('.wrapLogin').css('display', 'block')
    }


    function verifiedFavorites(index, numero_constancia, nom_grupo, detalle_objeto_proceso, cuantia, pubDate) {

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
                        ValidateInfoPlan(index, numero_constancia, nom_grupo, detalle_objeto_proceso, cuantia, pubDate);
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
    function ValidateInfoPlan(index, numero_constancia, nom_grupo, detalle_objeto_proceso, cuantia, pubDate){
        $.ajax({
            type:'POST',
            dataType: "json",
            url: baseUrl+"Home/ValidateInfoPlan/",
            success: function (data) {
                // No se ha superado la cuota de favoritos respecto al plan registrado
                if (data) {
                    // Se agrega la informacion del contrato como favorito
                    addFavorites(index, numero_constancia, nom_grupo, detalle_objeto_proceso, cuantia, pubDate);
                }else{
                    showModalPlans();

                    setTimeout(() => {
                        $('.star_'+index).removeClass("active");
                   
                    }, 100);
                }
            }
        });
    }

    function addFavorites(index, numero_constancia, nom_grupo, detalle_objeto_proceso, cuantia, pubDate){
        $('.favoriteAlert_'+index).html("");
        var data = {
            created: moment().format('YYYY-MM-DD h:mm:ss'),
            author: '',
            category: '',
            ciudad: '',
            contenido: detalle_objeto_proceso,
            departamento: '',
            link: '',
            nombre: nom_grupo,
            title: '',
            valor: cuantia,
            num_constancia: numero_constancia,
            fecha_cargue :  moment(pubDate).format('YYYY-MM-DD h:mm:ss'),
        }

        $.ajax({
            type:'POST',
            data: {interest: data},
            dataType: "json",
            url: baseUrl+"InterestContracts/saveInterestContract",
            success: function (data) {
            },
            complete: function (data) {
                $('.favoriteAlert_'+index).append('<p style="color: green!important; margin-top: 26px; position: relative;">Ahora sigues este contrato</p>');
            }
        });
    }

    function getfavoritesByUser(){
        $.ajax({
            type:'POST',
            dataType: "json",
            url: baseUrl+"Home/getfavoritesByUser/",
            success: function (data) {
   
                console.log(data);
                $.each(data.contractTypes, function(key, value) {
                    // Se renderiza lista de categorias de contratos de acuerdo al tipo elejido
                    $('.checksSubcategory').append(`
                        <div class="custom-control custom-checkbox">
                            <input name="checkSubcategory" type="checkbox" class="custom-control-input" id="customCheck_`+value.ContractSubcategory.id+`" value="`+value.ContractSubcategory.name+`">
                            <label class="custom-control-label" for="customCheck_`+value.ContractSubcategory.id+`" style="font-size: 14px;">`+value.ContractSubcategory.name+`</label>
                        </div>
                    `)
               
                })
   
            }
        });
    }
