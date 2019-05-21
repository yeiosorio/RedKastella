<?php 

$loggedUser = AuthComponent::user();  

$baseUrl = Router::url('/', true);

?>
    </span>
  </div>
</nav></div>
    <div style="width: 100%;background-color: #3D4D65;height: 6px;"></div>
    <div style="margin-top: 30px;margin-bottom: 50px;">
        <div class="container-fluid contenido-cards2">
            <p class="text-center">Kastella Busca entre los mas de <strong>100.000</strong> contratos Publicados en<br><strong>SECOP 1</strong> y <strong>SECOP 2</strong> y Entidades Privadas</p>
            <div class="row" style="margin: 0px 0px 30px;">
                <div class="col">
                    <div class="row padMar" style="margin: 0 5%;">
                        <div class="col padMar">
                            <div class="input-group">
                                <div class="input-group-prepend"></div>
                                <div class="form-group">
                                    <select class="form-control selectTypeContract" id="exampleFormControlSelect1" style="height: 44px;">
                                        <option value="">Tipos de Contrato</option>
                                        <option value="Material Vivo Animal y Vegetal">Material Vivo Animal y Vegetal</option>
                                        <option value="Materias Primas">Materias Primas</option>
                                        <option value="Maquinaria, Herramientas, Equipo Industrial y Vehículos">Maquinaria, Herramientas, Equipo Ind...</option>
                                        <option value="Componentes y Suministros">Componentes y Suministros</option>
                                        <option value="Productos de Uso Final">Productos de Uso Final</option>
                                        <option value="Servicios">Servicios</option>
                                        <option value="Terrenos, Edificios, Estructuras y Vías">Terrenos, Edificios, Estructuras y Vías</option>
                                    </select>
                                </div>
                                <input class="form-control autocomplete searchContract" type="text" placeholder="Barra de búsqueda" style="padding: 21px;">
                                <div class="input-group-append"><button class="btn btn-primary btn-kastella" type="button" style="padding: 13.1px 15px; 15px;border:none;"><i class="fa fa-search"></i></button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-3">
                    <div class="row">
                        <div class="col">
                            <div class="preferencias" style="background-color: #418EB5;padding: 5px 0px;padding-left: 10PX;"><span style="color: rgb(255,255,255);font-size: 18px;">Preferencias de Búsqueda</span></div>
                            <div style="background-color: #f6f8f9;padding: 2% 0px;padding-bottom: 5%;">
                                <div class="col">
                                    <p style="margin: 0px;margin-left: 5px;margin-bottom: 5px;font-family: Actor, sans-serif;font-weight: bold;">Tipos de Contrato</p>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(1);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/Material-vivo.png" width="30px" style="margin-right:2%;"></span>Material Vivo Animal y Vegetal
                        </div>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(11);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/Materias-primas.png" width="30px" style="margin-right:2%;"></span>Materias Primas
                        </div>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(21);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/Maquinaria.png" width="30px" style="margin-right:2%;"></span>Maquinaria, Herramientas, Equipo Industrial y Vehículos
                        </div>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(31);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/Componentes.png" width="30px" style="margin-right:2%;"></span>Componentes y Suministros
                        </div>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(41);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/uso-final.png" width="30px" style="margin-right:2%;"></span>Productos de Uso Final
                        </div>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(51);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/Servicios.png" width="30px" style="margin-right:2%;"></span>Servicios
                        </div>
                        <div style="font-size: 16px; margin-bottom: 4px; cursor: pointer;" onclick="getContractSubCategories(61);" class="contrato">
                            <span><img src="<?=$baseUrl?>img/Terrenos.png" width="30px" style="margin-right:2%;"></span>Terrenos, Edificios, Estructuras y Vías
                        </div>
                        </div>
                        <hr>
                        <div class="col">
                            <p style="margin: 0px;margin-left: 5px;font-family: Actor, sans-serif;font-weight: bold;margin-top: 15px;">Categorías</p>
                            <div>
                                <fieldset class="checksSubcategory">
                                </fieldset>
                            </div>
                        </div>
                    <hr>
                    <div class="col" style="padding-bottom: 10px;">
                        <p style="margin: 0px;margin-left: 5px;font-family: Actor, sans-serif;font-weight: bold;margin-top: 15px;">Departamento</p>
                        <select class="deptPreference" style="width: 100%;">
                            <optgroup class="selectDept" label="Selecione un Departamento">
                                <option value="0" selected="">Seleccione Departamento</option>
                            </optgroup>
                        </select>
                    </div>
                    <hr>
                    <div class="col">
                        <p style="margin: 0px;margin-left: 5px;font-family: Actor, sans-serif;font-weight: bold;margin-top: 15px;">Rango de Valor para la Categoría:</p>
                        <div>
                        <input type="hidden" class="currencyField1" value="">
                        <input type="hidden" class="currencyField2" value="">
                            <span style="font-size: 14px;">
                                <strong class="currencyFieldLabel1"></strong>
                            </span>
                            <input type="text" id="ex2" class="span2 slider-values" value="" data-slider-min="10" data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]" style="width: 100%;">
                            <span style="font-size: 14px;">
                                <strong class="currencyFieldLabel2"></strong>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="col">
                        <p style="margin: 0px;margin-left: 5px;font-family: Actor, sans-serif;font-weight: bold;margin-top: 15px;">Etiquetas de Búsqueda:</p>
                        <hr><button onclick="searchPreferenceFilter();" class="btn btn-primary border rounded" type="button" style="margin-top: 15px;width: 100%;height: 30px;padding: 5px;background-color: rgb(255,102,0);font-family: Actor, sans-serif;">BUSCAR</button></div>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class="col-md-12 col-lg-9">
                        <!-- Seccion de cards de contratos generada dinamicamente con datos de la API colombia compra -->
                        <div class="row contenedor-contratos" style="padding: 0px 0 80px;background-color: #dee4e6;"></div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    
                    <div class="footer-2" style="background-color: #3D4D65;">
                        <div class="container" style="width: 75%;">
                            <div class="row">
                                <div class="col-8 col-sm-6 col-md-6">
                                    <p class="text-left" style="margin-top:5%;margin-bottom:3%;"><strong>RedKastella</strong> © Copyright 2018</p>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6">
                                    <p class="text-right" style="margin-top:5%;margin-bottom:8%%;font-size:1em;">Politica de Privacidad</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Modal de login ********************************-->
        <div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLoginLabel" aria-hidden="true">
            <div class="modal-dialog" style="margin-top: 124px" role="document">
                <div class="modal-content" style="padding: 34px; background-color: #efefef;">
                    <div class="modal-header" style="text-align:center">
                    <h3 class="" style="margin-bottom: -5px; color: rgb(255,102,0); font-size: 28px;font-family: Actor, sans-serif;font-weight: normal;">Inicio de Sesión</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px; font-size: 47px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <form class="navbar-form navbar-left login-form">
                            <div style="">
                                <label>Correo electronico</label>
                                <input id="email" name="data[User][username]" class="border rounded" type="text" placeholder="Correo electronico" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                            </div>
                            <div style="">
                                <label>Contraseña</label>
                                <input id="password" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">

                            </div>
                            <div class="row" style="margin-top: 16px; text-align: center; margin-right: 0px; margin-left: 0px;">
                                <button class="btn btn-primary border rounded" type="submit" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesion</button>
                            </div>
                            <div class="alertLogin" style="margin-top: 16px"></div>
                            <div style="display:none; text-align: center; margin-top: 16px" class="loading">
                                <div class="spinner-grow text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                        </form>
                        
                        <div class="row" style="margin-top: 16px; text-align: center">
                            <!-- <button class="btn btn-primary border rounded" type="button" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesion con facebook</button> -->
                        </div>
                        <p style="text-align: center"><a href="#">¿Olvidaste tu contraseña?</a></p>
                        <div style="text-align: center">
                            <label style="color: rgb(255,102,0);">Si aún no tienes cuenta </label>
                            <b onclick="openRegisterModal();" style="color: rgb(255,102,0); text-decoration: underline; cursor: pointer; margin-left: 8px;"> Regístrate aquí</b>
                        </div>
                    </div>
                    
                </div>
            </div>
      </div>

        <!-- Modal de Registro ************************************-->
        <div class="modal fade" id="modalRegister" tabindex="-1" role="dialog" aria-labelledby="modalRegisterLabel" aria-hidden="true">
            <div class="modal-dialog" style="margin-top: 124px" role="document">
                <div class="modal-content" style="padding: 34px; background-color: #efefef;">
                    <div class="modal-header" style="text-align:center">
                    <span style="font-size: 30px; font-size: 30px; margin-right: 117px;" class="fa fa-arrow-left" onclick="backToLogin();"></span><h3 class="" style="margin-bottom: -5px; color: rgb(255,102,0); font-size: 28px;font-family: Actor, sans-serif;font-weight: normal;">Registro</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px; font-size: 47px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <form action="" class="addUserForm form-style " method="post">    
                            
                            <div style="">
                                <label>Correo electrónico</label>
                                <input id="emailreg" name="data[User][email]" class="border rounded" type="email" placeholder="Correo electronico" style="margin-bottom: 8px; width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                            </div>
                            <div style="">
                                <label>Contraseña</label>
                                <input id="passwordreg" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="margin-bottom: 8px; width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                            </div>

                            <div class="alertLogin" style="margin-top: 16px"></div>

                            <div style="display: none; text-align: center; margin-top: 16px" class="loading">
                                <div class="spinner-grow text-secondary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 16px; text-align: center">
                                <button class="btn btn-primary border rounded boton-buscar" type="submit" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Registrarse</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
      </div>

      <!-- Modal detalles de contratos *****************************-->
        <div class="modal fade" id="modalContractsDetails" tabindex="-1" role="dialog" aria-labelledby="modalContractsDetailsLabel" aria-hidden="true">
            <div class="modal-dialog" style="margin-top: 34px; max-width: 60%;" role="document">
                <div class="modal-content" style="padding: 34px; background-color: #efefef;">
                    <div class="modal-header" style="text-align:center; border-bottom: 2px solid #1d919e7d;">
                    <h3 class="titleDetail" style="margin-bottom: -5px; color: rgb(255,102,0); font-size: 28px;font-family: Actor, sans-serif;font-weight: normal;"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -40px; font-size: 47px; ">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="titulos"><h4 style="font-family: Actor, sans-serif;font-weight: normal;">Información General del Proceso</h4></div>
                                <table style="width:100%;" id="space" class="tabla1">
                                    <colgroup><col span="1"><col span="1"></colgroup>
                                    <tbody class="detailColumns">
                                        <div class="sk-cube-grid spinner-cube">
                                            <div class="sk-cube sk-cube1"></div>
                                            <div class="sk-cube sk-cube2"></div>
                                            <div class="sk-cube sk-cube3"></div>
                                            <div class="sk-cube sk-cube4"></div>
                                            <div class="sk-cube sk-cube5"></div>
                                            <div class="sk-cube sk-cube6"></div>
                                            <div class="sk-cube sk-cube7"></div>
                                            <div class="sk-cube sk-cube8"></div>
                                            <div class="sk-cube sk-cube9"></div>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="titulos"><h4 style="font-family: Actor, sans-serif;font-weight: normal;">Documentos del Proceso</h4></div>
                                <table style="width:100%;" id="space" class="tabla2">
                                    <colgroup><col span="1"><col span="1"></colgroup>
                                    <thead class="cabecera">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Fecha de ultima modificación</th>
                                            <th>Tipo</th>
                                            <th>Tamaño</th>
                                            <th>Versión</th>
                                        </tr>
                                    </thead>
                                    <tbody class="gridDetailInfo">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div >
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


        <!-- paso a paso para eleccion de planes y pago PayU ************************************-->
        <div class="modal fade" id="modalStepPlans" tabindex="-1" role="dialog" aria-labelledby="modalStepPlans" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 100%; width: 950px;" role="document">            
                <div class="modal-content" style="padding: 0px; background-color: #efefef;">
                    <div class="modal-header" style="">
                        <h3 class="" style="margin: auto; margin-bottom: -5px; color: rgb(255,102,0); font-size: 28px;font-family: Actor, sans-serif;font-weight: normal;">Elije tu plan hoy</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 0rem 0rem 0rem; margin-top: -40px; font-size: 47px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                         <!-- Smart Wizard -->
                        <div id="wizardPopRequest" class="form_wizard wizard_horizontal"> 
                            <ul class="wizard_steps">
                                <li>
                                    <a href="#step-1">
                                        <span class="step_no">1</span>
                                        <span class="step_descr">Elije tu plan<br />
                                            <small></small>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                <?php if(!isset($loggedUser)){ ?>
                                    <a href="#step-2" id="second-step">
                                        <span class="step_no">2</span>
                                        <span class="step_descr">Inicio de sesion<br />
                                            <small></small>
                                        </span>
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                    <a href="#step-3">
                                        <span class="step_no">3</span>
                                        <span class="step_descr">Confirmación<br />
                                            <small></small>
                                        </span>
                                    </a>
                                </li>
                            </ul>

                            <!--cuerpo del contenido del asistente-->
                            <div id="step-1">
                                <div class="row">
                                <div class="col-md-6 col-lg-6" style="margin-top: 26px;">
                                    <div class="pricingTable" style="min-height: 645px; width: 300px; box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); border-radius: 8px 8px 8px 8px; float: right;">
                                        <h1 class="title" style="color: #5bc6d0;margin: 0px;">Plan Básico</h1>
                                        <p style="margin: 0px 0px 25px; color: #b5b5b5;">Acceso a caracteristicas limitadas</p>
                                        <div class="price-value" style="margin-bottom: 18px;">
                                            <div style="width: 172px;height: 172px;border-radius: 200px;background: #5bc6d0;position: relative;display: flex;justify-content: center;align-items: center;flex-direction: column;">
                                                GRATIS
                                            </div>
                                        </div>
                                        <ul class="list-unstyled pricing-content">
                                            <li>1GB Disk Space</li>
                                            <li>50 Email Accounts</li>
                                            <li>Unlimited Bandwidth</li>
                                            <li>10 Subdomains</li>
                                        </ul><button onclick="chosePlan(1);" href="#" class="pricingTable-signup getFreeAccount">Quiero mi prueba</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6" style="margin-top: 26px;">
                                    <div class="pricingTable" style="min-height: 645px; width: 300px; box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); background: #e65c00; border-radius: 8px 8px 8px 8px;">
                                        <h1 class="title" style="color: #5bc6d0;margin: 0px; color: #e4e4e4;">Plan Empresarial</h1>
                                        <p style="margin: 0px 0px 25px; color: #ffffff;">Anual</p>
                                        <div class="price-value" style="background: #e85c00; margin-bottom: 8px;">
                                            <div style="background: #ffffff!important; width: 180px;height: 180px;border-radius: 200px;background: #5bc6d0;position: relative;display: flex;justify-content: center;align-items: center;flex-direction: column;">
                                                <span class="month" style="color: #e85c00; position: relative;display: block;">Un solo pago</span>
                                                <span class="amount" style="color: #e85c00; position: relative;display: block;">100<span class="currency">$</span><span class="value">99</span></span>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled pricing-content">
                                            <li style="color: #e4e4e4;">1GB Disk Space</li>
                                            <li style="color: #e4e4e4;">50 Email Accounts</li>
                                            <li style="color: #e4e4e4;">Unlimited Bandwidth</li>
                                            <li style="color: #e4e4e4;">10 Subdomains</li>
                                        </ul><a onclick="chosePlan(2);" href="#" class="pricingTable-signup" style="">Comprar Plan</a>
                                    </div>
                                </div>
                               
                            </div>
                            </div>
                            <?php if(!isset($loggedUser)){ ?>
                            <div id="step-2">
                                <!-- Login ****************************************** -->
                                <div class="wrapLogin" style="width: 28%; margin: auto;">
                                    <form class="navbar-form navbar-left loginStepPlans">
                                        <div style="">
                                            <label>Correo electronico</label>
                                            <input id="email" name="data[User][username]" class="border rounded" type="text" placeholder="Correo electronico" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                                        </div>
                                        <div style="">
                                            <label>Contraseña</label>
                                            <input id="password" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">

                                        </div>

                                        <div class="row" style="margin-top: 16px; text-align: center; margin-right: 0px; margin-left: 0px;">
                                            <button class="btn btn-primary border rounded boton-buscar" type="submit" style="margin: auto; width: 90%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesion</button>
                                        </div>

                                        <div class="alertLogin" style="margin-top: 16px"></div>
                                        <div style="display:none; text-align: center; margin-top: 16px" class="loading">
                                            <div class="spinner-grow text-secondary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </form>
                                    
                                    <div class="row" style="margin-top: 16px; text-align: center">
                                        <!-- <button class="btn btn-primary border rounded" type="button" style="margin: auto; width: 90%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesion con facebook</button> -->
                                    </div>
                                    <p style="text-align: center"><a href="#">¿Olvidastes tu contraseña?</a></p>
                                    <div style="text-align: center">
                                        <label style="color: rgb(255,102,0);">Si aún no tienes cuenta </label>
                                        <b onclick="switchRegister();" style="color: rgb(255,102,0); text-decoration: underline; cursor: pointer; margin-left: 8px;"> Regístrate aquí</b>
                                    </div>
                                </div>

                                <!-- Registro ****************************************** -->
                                <div class="wrapRegister" style="display:none; width: 28%; margin: auto;">
                                    <form action="" class="addUserForm form-style " method="post">    
                                        <div style="">
                                            <label>Correo electronico</label>
                                            <input id="emailreg" name="data[User][username]" class="border rounded" type="email" placeholder="Correo electronico" style="margin-bottom: 8px; width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                                        </div>
                                        <div style="">
                                            <label>Contraseña</label>
                                            <input id="passwordreg" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="margin-bottom: 8px; width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                                        </div>
                                        <div class="row" style="margin-top: 16px; text-align: center">
                                            <button class="btn btn-primary border rounded boton-buscar" type="submit" style="margin: auto; width: 90%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Registrarse</button>
                                        </div>
                                        <p onclick="backSession();" style="text-align: center"><a href="#">Regresar al Inicio de sesión</a></p>
                                    </form>
                                </div>
                                
                            </div>
                            <?php } ?>
                            <div id="step-3">
                                <div class="pricingTable" >
                                    <a onclick="goPayUPayment();" href="#" class="pricingTable-signup" style="">Realizar pago</a>
                                </div>
                                <form target="_blank" id="formPayU" method="post" action="https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/" style="display:none">
                                    <!-- <input name="merchantId" type="hidden" value="780264">
                                    <input name="accountId"     type="hidden"  value="787070"> -->
                                    <input name="merchantId"    type="hidden"  value="508029">
                                    <input name="accountId"     type="hidden"  value="512321">
                                    <input name="description"   type="hidden"  value="Membresia premium redkastella">
                                    <input name="referenceCode" type="hidden"  value="TestPayU">
                                    <input name="amount"        type="hidden"  value="">
                                    <input name="tax"           type="hidden"  value="0">
                                    <input name="taxReturnBase" type="hidden"  value="0">
                                    <input name="currency"      type="hidden"  value="COP">
                                    <input name="signature"     type="hidden"  value="">
                                    <input name="test"          type="hidden"  value="1">
                                    <input name="buyerEmail"    type="hidden"  value="">
                                    <input name="extra1"        type="hidden"  value="">
                                    <input name="extra2"        type="hidden"  value="">
                                    <input name="responseUrl"   type="hidden"  value="">
                                    <input name="confirmationUrl" type="hidden"  value="https://prueba.redkastella.com/Home/confirmPayment">
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Planes de pago ************************************-->
        <div class="modal fade" id="modalPlans" tabindex="-1" role="dialog" aria-labelledby="modalPlansLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 100%; width: 992px;" role="document">            
                <div class="modal-content" style="background-color: #efefef;">
                    <div class="modal-header" style="">
                        <h3 class="" style="margin: auto; margin-bottom: -5px; color: rgb(255,102,0); font-size: 28px;font-family: Actor, sans-serif;font-weight: normal;">Elije tu plan hoy</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: 0rem 0rem 0rem; margin-top: -40px; font-size: 47px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <div class="row">
                            <div class="col-md-6 col-lg-4" style="margin-top: 51px;">
                                <div class="pricingTable" style="box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); border-radius: 8px 8px 8px 8px; width: 84%;float: right;">
                                    <h1 class="title" style="color: #5bc6d0;margin: 0px;">Plan Básico</h1>
                                    <p style="margin: 0px 0px 25px; color: #b5b5b5;">Acceso a caracteristicas limitadas</p>
                                    <div class="price-value">
                                        <div style="width: 172px;height: 172px;border-radius: 200px;background: #5bc6d0;position: relative;display: flex;justify-content: center;align-items: center;flex-direction: column;">
                                            GRATIS
                                        </div>
                                    </div>
                                    <ul class="list-unstyled pricing-content">
                                        <li>1GB Disk Space</li>
                                        <li>50 Email Accounts</li>
                                        <li>Unlimited Bandwidth</li>
                                        <li>10 Subdomains</li>
                                        <li>15 Domains</li>
                                        <li>Free cPanel</li>
                                    </ul><a onclick="goPayUPayment();" href="#" class="pricingTable-signup">Quiero mi prueba</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="pricingTable" style="width: 332px; box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); background: #e65c00; height: 733px; border-radius: 8px 8px 8px 8px;">
                                    <h1 class="title" style="color: #5bc6d0;margin: 0px; color: #e4e4e4;">Plan Empresarial</h1>
                                    <p style="margin: 0px 0px 25px; color: #ffffff;">Anual</p>
                                    <div class="price-value" style="background: #e85c00;">
                                        <div style="background: #ffffff!important; width: 180px;height: 180px;border-radius: 200px;background: #5bc6d0;position: relative;display: flex;justify-content: center;align-items: center;flex-direction: column;">
                                            <span class="month" style="color: #e85c00; position: relative;display: block;">Un solo pago</span>
                                            <span class="amount" style="color: #e85c00; position: relative;display: block;">100<span class="currency">$</span><span class="value">99</span></span>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled pricing-content">
                                        <li style="color: #e4e4e4;">1GB Disk Space</li>
                                        <li style="color: #e4e4e4;">50 Email Accounts</li>
                                        <li style="color: #e4e4e4;">Unlimited Bandwidth</li>
                                        <li style="color: #e4e4e4;">10 Subdomains</li>
                                        <li style="color: #e4e4e4;">15 Domains</li>
                                        <li style="color: #e4e4e4;">Free cPanel</li>
                                    </ul><a onclick="goPayUPayment();" href="#" class="pricingTable-signup" style="">Comprar Plan</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4" style="margin-top: 51px;">
                                <div class="pricingTable" style="width: 332; float: left;box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); border-radius: 8px 8px 8px 8px;">
                                    <h1 class="title" style="color: #5bc6d0;margin: 0px;">Plan Pro</h1>
                                    <p style="margin: 0px 0px 25px; color: #b5b5b5;">Pagas mes a mes</p>
                                    <div class="price-value">
                                        <div style="width: 172px;height: 172px;border-radius: 200px;background: #5bc6d0;position: relative;display: flex;justify-content: center;align-items: center;flex-direction: column;">
                                        <span class="month" style="position: relative;display: block;"></span>
                                        <span class="amount" style="position: relative;display: block;">50<span class="currency">$</span><span class="value">Mil</span></span>
                                        </div>
                                    </div>
                                    <ul class="list-unstyled pricing-content">
                                        <li>1GB Disk Space</li>
                                        <li>50 Email Accounts</li>
                                        <li>Unlimited Bandwidth</li>
                                        <li>10 Subdomains</li>
                                        <li>15 Domains</li>
                                        <li>Free cPanel</li>
                                    </ul><a onclick="goPayUPayment();" href="#" class="pricingTable-signup">Comprar Plan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
