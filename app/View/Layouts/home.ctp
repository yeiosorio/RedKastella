<?php 

$loggedUser = AuthComponent::user();  

$baseUrl = Router::url('/', true);

print '<script type="text/javascript">;
    var listContracts = '.json_encode($listContracts).'
    var loggedUser = '.json_encode($loggedUser).'
</script>'; 
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Redkastella</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic">
    <link rel="stylesheet" href="fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="fonts/font-awesome.min.css">
    <link rel="stylesheet" href="fonts/simple-line-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Actor">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amiko">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Antic">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.min.css">
    <link rel="stylesheet" href="<?=$baseUrl?>css/Bold-BS4-Pricing-Table-Style-50-1.css">
	  <link rel="stylesheet" href="<?=$baseUrl?>css/Bold-BS4-Pricing-Table-Style-50.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/css/bootstrap-slider.min.css">
</head>

<body>

    <nav class="navbar navbar-light navbar-expand-lg fixed-top" id="mainNav">
        <div class="container"><a class="navbar-brand" href="index.html" style="margin: 10px;"></a><button class="navbar-toggler" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><i class="fa fa-bars"></i></button>
            <div
                class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item border rounded" role="presentation" style="background-color: rgb(255,102,0);border: none !important;">
                        <a data-toggle="modal" data-target="#modalLogin" class="nav-link" href="#" style="color: white;">INGRESAR</a>
                    </li>
                </ul>
        </div>
        </div>
    </nav>
    <header data-bs-parallax-bg="true" class="masthead" style="background-image: url(&quot;img/background-1.jpg&quot;);margin-bottom: 0px;">
        <div class="overlay" style="background-color: transparent;"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-lg-8 mx-auto">
                    <div class="site-heading" style="padding: 250px 0px;padding-bottom: 180px;">
                        <h1 style="font-size: 40px;filter: blur(0px);font-family: Actor, sans-serif;font-weight: normal;">Encuentra Oportunidades de Negocio para tu Empresa</h1><span class="subheading" style="font-size: 18px;">Todos los dias más de 13.000 empresas publicas buscan lo que tu empresa ofrece<br></span>
                        <div class="row" style="padding: 5px;">
                            <div class="col-8 mt-4">
                              <input id="searchInput" class="border rounded" type="text" placeholder="Barra de busqúeda" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                            </div>
                            <div class="col-4 mt-4" style="padding: 0px;">
                              <button onclick="globalSearchContracts();" class="btn btn-primary border rounded boton-buscar" type="button" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Encontrar contratos</button>
                            </div>
                            <div class="col mt-4" style="padding: 0px;"><span class="subheading" style="font-size: 18px;font-weight: normal;margin: 20px 0px 0px;">Pasa menos tiempo buscando </span><span class="subheading" style="font-size: 30px;font-family: Antic, sans-serif;font-weight: normal;margin: 4px;">y más tiempo produciendo </span></div>
                        </div>
                    </div>
                    <div class="d-flex d-xl-flex justify-content-center justify-content-xl-center col-12 hidden-md-down" style="margin: 30px 0px;"><img class="d-flex d-lg-flex d-xl-flex justify-content-center justify-content-lg-center justify-content-xl-center" src="img/Animacion-Scroll.gif" style="width: 46px;height: 62px;"></div>
                </div>
            </div>
        </div>
    </header>
    <div data-bs-parallax-bg="true" style="background-image: url(&quot;img/more-business.jpg&quot;);">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center" style="color: rgb(255,255,255);padding: 15px;filter: blur(0px);font-size: 28px;font-family: Actor, sans-serif;font-weight: normal;">Últimos Contratos Agregados</h3>
                </div>
            </div>
        </div>
    </div>

    <div style="background-color: #dae1e7">
        <div class="container-fluid contenido-cards">
            <div class="row contenido" style="padding: 40px 0 80px;">
         
            </div>
        </div>
    </div>
    
    <div class="container-fluid" style="padding: 0px;margin: 0px;">
        <div class="row frame-carousel" style="margin: 0px;"><iframe src="carousel/carousel.html" width="100%" scrolling="no" style="overflow:hidden;border:none;" frameborder="0"></iframe></div>
    </div>
    <footer style="background-color: #3D4D65;margin-top: 0px;">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 footer-navigation">
                    <h3><img src="assets/img/logo_p_w.png"></h3>
                    <p class="links"><a href="#">Home</a><strong> · </strong><a href="#">Blog</a><strong> · </strong><a href="#">Pricing</a><strong> · </strong><a href="#">About</a><strong> · </strong><a href="#">Faq</a><strong> · </strong><a href="contact.html">Contáctanos</a></p>
                    <p
                        class="company-name">Red Kastella © 2018</p>
                </div>
                <div class="col-sm-6 col-md-4 footer-contacts">
                    <div><span class="fa fa-map-marker footer-contacts-icon"> </span>
                        <p><span class="new-line-span">21 Revolution Street</span> Paris, France</p>
                    </div>
                    <div><i class="fa fa-phone footer-contacts-icon"></i>
                        <p class="footer-center-info email text-left"> +1 555 123456</p>
                    </div>
                    <div><i class="fa fa-envelope footer-contacts-icon"></i>
                        <p> <a href="#" target="_blank">support@company.com</a></p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 footer-about">
                    <h4>About the company</h4>
                    <p> Lorem ipsum dolor sit amet, consectateur adispicing elit. Fusce euismod convallis velit, eu auctor lacus vehicula sit amet. </p>
                    <div class="social-links social-icons"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-linkedin"></i></a><a href="#"><i class="fa fa-github"></i></a></div>
                </div>
                <div class="col">
                    <hr><span class="d-xl-flex justify-content-xl-end" style="font-size: 12px;font-family: Antic, sans-serif;font-weight: normal;"><strong>© Augusta consultores. Todos los derechos reservados</strong></span></div>
            </div>
        </div>
    </footer>

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
                                <label>Correo electrónico</label>
                                <input id="email" name="data[User][username]" class="border rounded" type="text" placeholder="Correo electrónico" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                            </div>
                            <div style="">
                                <label>Contraseña</label>
                                <input id="password" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">

                            </div>
                        <div class="row" style="margin-top: 16px; text-align: center; margin-right: 0px; margin-left: 0px;">
                            <button class="btn btn-primary border rounded boton-buscar" type="submit" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesión</button>
                        </div>

                        <div class="alertLogin" style="margin-top: 16px"></div>
                        <div style="display:none; text-align: center; margin-top: 16px" class="loading">
                            <div class="spinner-grow text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        </form>
                        
                        <div class="row" style="margin-top: 16px; text-align: center">
                            <!-- <button class="btn btn-primary border rounded" type="button" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesión con facebook</button> -->
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
                            <input id="emailreg" name="data[User][username]" class="border rounded" type="email" placeholder="Correo electrónico" style="margin-bottom: 8px; width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                        </div>
                        <div style="">
                            <label>Contraseña</label>
                            <input id="passwordreg" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="margin-bottom: 8px; width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
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
            <div class="modal-dialog" style="max-width: 100%; width: 70%;" role="document">            
                <div class="modal-content" style="padding: 34px; background-color: #efefef;">
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
                                        </ul><a onclick="chosePlan(1);" href="#" class="pricingTable-signup">Quiero mi prueba</a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="pricingTable" style="width: 84%; margin: auto; box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); background: #e65c00; height: 733px; border-radius: 8px 8px 8px 8px;">
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
                                        </ul><a onclick="chosePlan(2);" href="#" class="pricingTable-signup" style="">Comprar Plan</a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4" style="margin-top: 51px;">
                                    <div class="pricingTable" style="width: 84%; float: left;box-shadow: 0 12px 50px 0 rgba(0, 0, 0, .05), 0 5px 16px 0 rgba(0, 0, 0, .08), 0 0px 0px -2px rgba(0, 0, 0, .2); border-radius: 8px 8px 8px 8px;">
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
                                        </ul><a onclick="chosePlan(3);" href="#" class="pricingTable-signup">Comprar Plan</a>
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
                                            <input id="email" name="data[User][username]" class="border rounded" type="text" placeholder="Correo electrónico" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">
                                        </div>
                                        <div style="">
                                            <label>Contraseña</label>
                                            <input id="password" name="data[User][password]" class="border rounded" type="password" placeholder="Ingresa tu contraseña" style="width: 100%;height: 40px;padding: 5px 10px;font-family: Actor, sans-serif;font-size: 15px;">

                                        </div>
                                        <div class="row" style="margin-top: 16px; text-align: center">
                                            <button class="btn btn-primary border rounded boton-buscar" type="submit" style="margin: auto; width: 90%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesión</button>
                                        </div>
                                    </form>
                                    
                                    <div class="row" style="margin-top: 16px; text-align: center">
                                        <button class="btn btn-primary border rounded" type="button" style="margin: auto; width: 90%;height: 40px;padding: 5px 10px;font-family: Antic, sans-serif;">Iniciar Sesión con facebook</button>
                                    </div>
                                    <p style="text-align: center"><a href="#">¿Olvidaste tu contraseña?</a></p>
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
                                    <input name="merchantId"    type="hidden"  value="508029"   >
                                    <input name="accountId"     type="hidden"  value="512321" >
                                    <input name="description"   type="hidden"  value="Test PAYU"  >
                                    <input name="referenceCode" type="hidden"  value="TestPayU" >
                                    <input name="amount"        type="hidden"  value="30000">
                                    <input name="tax"           type="hidden"  value="3193"  >
                                    <input name="taxReturnBase" type="hidden"  value="16806" >
                                    <input name="currency"      type="hidden"  value="COP" >
                                    <input name="signature"     type="hidden"  value="3BDC3BE3061AB06E556C4A714DC6580A"  >
                                    <input name="test"          type="hidden"  value="1" >
                                    <input name="buyerEmail"    type="hidden"  value="yei_osorio@hotmail.com" >
                                    <input name="responseUrl"    type="hidden"  value="http://www.test.com/response" >
                                    <input name="confirmationUrl"    type="hidden"  value="https://prueba.redkastella.com/Home/confirmPayment" >
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
      <script>
        var baseUrl = "<?= Router::url('/', true); ?>";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
    <script src="<?=$baseUrl?>js/jquery.min.js"></script>
    <script src="<?=$baseUrl?>js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.0.2/bootstrap-slider.min.js"></script>
    <script src="<?=$baseUrl?>js/moment/moment-with-locales.js"></script>
    <script src="<?=$baseUrl?>js/jquery.smartWizard.js"></script>

    <script src="<?=$baseUrl?>js/home/index.js"></script>
    <script src="<?=$baseUrl?>js/script.min.js"></script>
    
</body>

</html>