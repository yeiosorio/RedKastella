
if (favorites.length > 0) {

    console.log('favorites +++++++++++++++++')
    console.log(favorites)
        $('.hasChange').html("");
        $('.contenedor-contratos').html("");
    
        // iteracion seccion de cards de contratos generada dinamicamente con datos de la API de colombia compra -->
        $.each(favorites, function(key, value) {
            
                    // [created] => 2019-01-21 04:22:53
                    // [users_id] => 568
                    // [author] => 
                    // [category] => 
                    // [ciudad] => 
                    // [contenido] => asdfas
                    // [departamento] => 
                    // [nombre] => Material Vivo Vegetal y Animal, Accesorios y Suministros
                    // [title] => 
                    // [valor] => 100000000.0000
                    // [num_constancia] => 18-1-178910
                    // [fecha_cargue] => 2018-05-31
                    var pubDate = value.InterestContract.fecha_cargue;
                    var uid = value.InterestContract.id;
                    var hasChange = value.InterestContract.hasChange;

                $('.contenedor-contratos').append(`
                    <div class="col-md-6 col-lg-6 col-xl-4"><div class="father">
                        <div class="front">
                            <header>
                                <div class="hasChange_`+key+`"></div>
                                <div class="bkg"></div>
                                <img src="`+baseUrl+`img/uso-final.png" alt="Picture">
                                <p style="cursor: pointer;" onclick="getContractsDetails('`+value.InterestContract.num_constancia+`');">`+value.InterestContract.nombre+`</p>
                            </header>
                            <div class="experience">
                            <h3 style="cursor: pointer;" onclick="getContractsDetails('`+value.InterestContract.num_constancia+`');">`+value.InterestContract.contenido+`</h3>
                                <p>Pueblo Bello - César</p>
                                <div class="valor">
                                    <p><b>Valor estimado: </b>$`+value.InterestContract.valor+`</p>
                                    <svg xmlns="http:/www.w3.org/2000/svg" xmlns:xlink="http:/www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="314.065px" height="314.065px" viewBox="0 0 314.065 314.065" style="enable-background:new 0 0 314.065 314.065;" xml:space="preserve"><g><g id="_x34_96._Down"><g><path d="M190.333,149.972l-17.602,17.6v-65.497c0-8.666-7.036-15.701-15.701-15.701c-8.668,0-15.701,7.035-15.701,15.701v65.497     l-17.607-17.6c-6.133-6.129-16.072-6.129-22.201,0c-6.133,6.129-6.133,16.078,0,22.202l44.408,44.41h-0.008     c3.07,3.069,7.083,4.6,11.108,4.6c2.008,0,4.022-0.384,5.903-1.149c1.892-0.766,3.663-1.907,5.198-3.442l44.402-44.41     c6.127-6.128,6.127-16.072,0-22.201C206.411,143.834,196.46,143.834,190.333,149.972z M235.533,21.061     C160.438-22.295,64.414,3.436,21.063,78.531c-43.356,75.089-17.633,171.117,57.464,214.478     c75.087,43.348,171.119,17.62,214.476-57.467C336.364,160.443,310.62,64.419,235.533,21.061z M265.801,219.841     c-34.688,60.075-111.503,80.653-171.574,45.961C34.158,231.118,13.565,154.308,48.25,94.232     c34.683-60.078,111.499-80.662,171.578-45.971C279.899,82.936,300.485,159.762,265.801,219.841z" style="fill: rgb(255, 255, 255);"></path></g></g></g>
                                    </svg>
                                    <div class="bkg2">
                                        <p><b>Fecha de la publicación:</b> `+pubDate+`</p>
                                        <div class="favoriteAlert_`+key+`"></div>
                                        <div class="final star_`+key+` active" id="star">
                                            <section onclick="removeFavorites(`+key+`, `+uid+`, '`+value.InterestContract.num_constancia+`', '`+value.InterestContract.nombre+`','`+value.InterestContract.contenido+`', `+value.InterestContract.valor+`, '`+pubDate+`');" class="Button star_`+key+` active" ></section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            
                // Si ha sufrido cambios el contrato se muestra label
                if (hasChange) {
                    $('.hasChange_'+key).append(`
                        <div style="cursor: pointer; margin-left: 15px; font-size: 13px; position: absolute; margin-top: 17px; width: 8px; height: 8px; background-color: #33bf7a; border-radius: 50px;">
                        </div>
                        <span onclick="getHistoryChange('`+value.InterestContract.num_constancia+`');" style="font-weight: 600; cursor: pointer; margin-left: -118px; font-size: 12px; color: #808080; position: absolute; margin-top: 11px; ">Nuevos cambios</span>
                    `);
                }else{
                    $('.hasChange').html("");
                }

                
        });
    
}

function goToFavorites(){
    
    if (loggedUser) {
        window.location.href = baseUrl + 'Favorites/listFavorites';

        return;
    }
    $('#modalLogin').modal('show');
}

function getHistoryChange(num_constancia) {
    $('.spinner-cube').show();
    $('.gridDetailInfo').html("")
    $('#modalhistoryChange').modal('show')
    
    // Se pregunta por el plan que registra el cliente
    // De no registrar aun se lanza la modal de planes
    $.ajax({
        type:'POST',
        dataType: "json",
        data: {
            num_constancia: num_constancia
        },
        url: baseUrl+"Favorites/getHistoryChange",
        success: function (data) {
            $('.spinner-cube').hide();

            $.each(data, function(key, value) {

                var fecha_ultima_modificacion = moment(value.cul_fecha).format('MM/DD/YYYY, h:mm a');
                var typeFile = '';

                $('.gridDetailInfo').append(`
                    <tr>
                        <td>`+value.cul_cla_oid+`</td>
                        <td>`+value.cul_valoranterior+`</td>
                        <td>`+value.cul_valornuevo+`</td>
                        <td>`+fecha_ultima_modificacion+` kb</td>
                        <td>`+value.cul_justificacion+`</td>
                    </tr>
                `)
    
            })
            
        }
    });
  
   
}