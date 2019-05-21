


/**
 * Aplicación
 */
var pagingMessages = angular.module('pagingMessages', ["bw.paging"]);

/**
 * Controlador de Medicamentos
 */
pagingMessages.controller('pagingMessagesController', function ($scope,$http) {


    /**
     * pagina actual
     * @type {Number}
     */
    $scope.currentPageReceived = 0;
    
    /**
     * Tamaño de la pagina
     * @type {Number}
     */
    $scope.pageSizeReceived = 10;

    /**
     * Total de items
     * @type {Number}
     */
    $scope.totalReceived = 1000;



    $scope.receivedInfo = {

    };


    /**
     * Función que asigna los elementos a mostrar en la paginación actual
     * @param  {Int} page Numero de la pagina
     */
    $scope.pageResultsReceived = function(page){


            var offset = 0;

            if (page > 1) {

                offset = (page - 1) * 10
            }


                /**
                 * Request al servidor, mandamos el nombre del principio activo
                 * @type AJAX
                 */
                var res = $http.post(baseUrl+'Messages/pageMessagesReceived/',{ offset: offset});

                /**
                 * Cuando llegan los datos se asignan y se muestra la paginacion inicial
                 */
                res.success(function(response) {

                    /**
                     * Número total de items
                     * @type {Int}
                     */
                    $scope.totalReceived = response.total;


                    $scope.receivedInfo = response.info;    


               }); 
       
    };

     $scope.pageResultsReceived(0);



    /**
     * pagina actual
     * @type {Number}
     */
    $scope.currentPageSent = 0;
    
    /**
     * Tamaño de la pagina
     * @type {Number}
     */
    $scope.pageSizeSent = 10;

    /**
     * Total de items
     * @type {Number}
     */
    $scope.totalSent = 1000;



    $scope.sentInfo = {

    };


    /**
     * Función que asigna los elementos a mostrar en la paginación actual
     * @param {Int} page Numero de la pagina
     */
    $scope.pageResultsSent = function(page){




            var offset = 0;

            if (page > 1) {

                offset = (page - 1) * 10
            }


                /**
                 * Request al servidor, mandamos el nombre del principio activo
                 * @type AJAX
                 */
                var res = $http.post(baseUrl+'Messages/pageSentMessages/',{offset: offset});

                /**
                 * Cuando llegan los datos se asignan y se muestra la paginacion inicial
                 */
                res.success(function(response) {	

                	console.log(response);

                    /**
                     * Número total de items
                     * @type {Int}
                     */
                    $scope.totalSent = response.total;


                    $scope.sentInfo = response.info;    

            

               }); 
       
    };


    $scope.pageResultsSent(0);


	$scope.message = "";

	$scope.subject = "";

    $scope.selectedType = 1;


    $scope.organizationId = 'none';


    $scope.messageContent = "";

    $scope.recipients = undefined;

    $scope.pSearchInput ="";



    $scope.getMessageContent = function(data){

        $('#modalMessageInfo').modal('show');

        console.log(data);

        $scope.messageContent = data;

    }


    $scope.messageReceivedContent = "";

    $scope.getMessageReceivedContent = function(data){

        $('#modalMessageReceivedInfo').modal('show');


         console.log(data);

        $scope.messageReceivedContent = data;

    }


    /**
     * identificador de mensaje para la eliminacion 
     * @type {[type]}
     */
    $scope.messageId = undefined;

    /**
     * Variable para identificar si el mensaje es enviado o recibido, si es 1 es recibido, si es 2 enviado
     * @type {Number}
     */
    $scope.ownOr = 1;


    $scope.dropReceivedMessage = function(id){
    

        $('#modalConfirmDrop').modal('show');
        
        $scope.messageId = id;

        $scope.ownOr = 1;

    }


    $scope.dropSentMessage = function(id){

        $('#modalConfirmDrop').modal('show');
        
        $scope.messageId = id;
        
        $scope.ownOr = 2;
    }


    $scope.confirmDrop = function(){
    
            var postData = {messageId : $scope.messageId, ownOr: $scope.ownOr};        

            var res = $http.post(baseUrl+'Messages/dropMessage/', postData);

                /**
                 * Cuando llegan los datos se asignan y se muestra la paginacion inicial
                 */
                res.success(function(response) {

                        if (response.success == true) {


                                            
                            if($scope.ownOr == 1){

                                $scope.pageResultsReceived(0);

                            }else{

                                $scope.pageResultsSent(0);
                            }   
                            
                        }
               }); 

     

    }

    $scope.validateEmails = function(){

        var usersIds = selectedEmails.getValue(); 
            
        if(usersIds.length != 0){  
        
            $scope.recipients =  usersIds;  

            return true;
        
        }else{

            $scope.recipients = undefined;

            return false;
        }   

    }


    $scope.doSendMessage = function(isValid){

        if(isValid){

            var usersIds = selectedEmails.getValue(); 

            var postData = { message: $scope.message,  subject: $scope.subject, usersIds: usersIds, selectedType : $scope.selectedType, organizationId: $scope.organizationId };
                    
            var res = $http.post(baseUrl+'Messages/send/', postData);

                    /**
                     * Cuando llegan los datos se asignan y se muestra la paginacion inicial
                     */
                    res.success(function(response) {

                         if (response.success == true) {

                             $scope.message = "";

                             $scope.subject = "";

                             $scope.messageSentInfo = "El mensaje se ha enviado con exito!";

                         }else{

                            $scope.messageSentInfo ="Hubo un problema enviando el mensaje";


                        }


                        


                    }); 

            // carga de resultado de enviados cuando se envia un mensaje 
            $scope.pageResultsSent(0);
                    
            //clear emails 
            selectedEmails.clear();

            $scope.pSearchInput ="";

        }

    }


    $scope.messageSentInfo = "";


	$scope.sendMessage = function(isValid){


        // console.log(isValid);
    

        // si es usuarios ingresados
        if($scope.selectedType == 1){
                
            if($scope.validateEmails()){

                $scope.doSendMessage(isValid);

            }


        }else{

            // Si se ha seleccionado un grupo
             $scope.doSendMessage(isValid);


        }

    }





});



/**
 * run emogify for icons
 */
emojify.run();






