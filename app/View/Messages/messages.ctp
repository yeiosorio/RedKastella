<div class="st-content">


            <div ng-app="pagingMessages">

                <div ng-controller="pagingMessagesController">


             <!-- Tabbable Widget -->
                  <div class="tabbable">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#received" data-toggle="tab"><i class="fa fa-fw fa-arrow-circle-down"></i> Correo Recibido</a></li>
                      <li><a href="#sent" data-toggle="tab"><i class="fa fa-fw fa-arrow-circle-up"></i> Correo Enviado</a></li>
                      <li><a href="#send" data-toggle="tab"><i class="fa fa-fw fa-envelope"></i> Redactar</a></li>
                    </ul>
                    <!-- // END Tabs -->

                    <!-- Panes -->
                    <div class="tab-content">

                        <!-- Tab de recividos -->
                      <div id="received" class="tab-pane active">

                                <button type="button" class="btn btn-primary" style="float:right;" ng-click="pageResultsReceived(0)"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                <br /><br />
            
                                  <div class="table-responsive gl-responsive">  
                                         <table class="table" >
                                          <thead>
  

                                            <th>De</th>
                                            
                                            <th>Asunto</th>

                                            <th>Fecha</th>

                                            <th>Ver</th>

                                            <th>Eliminar</th>  

                                          </thead>


                                          <tbody ng-repeat="info in receivedInfo">

                                            <tr>  


                                              <td>
                                                
                                                {{info.Message.User.email}}
                                              </td>

                                              <td>

                                                {{info.Message.subject}}  

                                              </td>

                                              <td>

                                              {{info.Message.created}}

                                              </td>



                                              <td>
                                                <i class="fa fa-envelope" style="font-size: 20px; cursor: pointer;" ng-click="getMessageReceivedContent(info)"></i>
                                              </td>

                                              <td>
                                                
                                                <i class="fa fa-trash" aria-hidden="true" style="font-size: 20px; cursor: pointer;" ng-click="dropReceivedMessage(info.MessagesUser.id)"></i>

                                              </td>

                                            </tr>
                                      </tbody>
                                    </table>
                     
                                      <div 
                                        paging
                                        page="currentPageReceived" 
                                        page-size="pageSizeReceived" 
                                        total="totalReceived"
                                        adjacent="true"
                                        show-prev-next="true"
                                        paging-action="pageResultsReceived(page)"
                                      >
                                      </div>  

                                    </div>        



                      </div>
                      <!-- Tab de Enviados -->
                      <div id="sent" class="tab-pane">
                
                                  <div class="table-responsive gl-responsive">  
                
                                   <button type="button" class="btn btn-primary" style="float:right;" ng-click="pageResultsSent(0)"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                    <br /><br />

                                         <table class="table" >
                                          <thead>
                                            
                                            <th>Para</th>

                                            <th>Asunto</th>

                                            <th>Fecha</th>
                                            
                                            <th>Ver</th>

                                            <th>Eliminar</th>  
                                            
                                          </thead>


                                          <tbody ng-repeat="info in sentInfo">

                                            <tr>

                                              <td>
                                                <!-- <label>Fecha</label> -->
                                                
                                                <div ng-repeat="mu in info.MessagesUsers | limitTo:5">
                                                    
                                                    <span>{{mu.User.email}}</span>

                                                </div>
                                              </td>

                                              <td>
                                                <!-- <label>Leucositos</label> -->
                                                {{info.Message.subject}}
                                              </td>

                                              <td>
                                                <!-- <label>Fecha</label> -->
                                                {{info.Message.created}}
                                              </td>

                                              <td>
                                                <i class="fa fa-envelope" style="font-size: 20px; cursor: pointer;" ng-click="getMessageContent(info)"></i>
                                              </td>
                                              
                                              <td>
                                                <i class="fa fa-trash" aria-hidden="true" style="font-size: 20px; cursor: pointer;" ng-click="dropSentMessage(info.Message.id)"></i>
                                              </td>
                          
                                            </tr>
                                      </tbody>
                                    </table>
                     
                                    <div 
                                      paging
                                      page="currentPageSent" 
                                      page-size="pageSizeSent" 
                                      total="totalSent"
                                      adjacent="true"
                                      show-prev-next="true"
                                      paging-action="pageResultsSent(page)"
                                    >
                                    </div>  

                                    </div>         

                      </div>

                      <!-- Tab de Envio -->
                      <div id="send" class="tab-pane">


                       <div class="messages form">
                        <?php echo $this->Form->create('Message',array('novalidate','name'=>'messageForm')); ?>

                            <fieldset>



                                <div ng-show="messageSentInfo">
                                  <div class="alert alert-info" role="alert" >{{messageSentInfo}}</div>
                                </div>   

                                <br />

                                       
                                <label>Para:  </label>  
                                  &nbsp;&nbsp;&nbsp;
                                  <div class="radio radio-info radio-inline">
                                    <input type="radio" id="inlineRadio1" value="1" ng-model="selectedType" name="radioInline" >
                                    <label for="inlineRadio1">Amigos y personas</label>
                                  </div>
                                  <div class="radio radio-info radio-inline">
                                    <input type="radio" id="inlineRadio2" value="2" ng-model="selectedType" name="radioInline">
                                    <label for="inlineRadio2">A Grupo</label>
                                  </div>
                              
                                <br />

                                <br />


                              <div ng-show="selectedType != 1 ">  
                                
                                  
                                <?php if(count($organizationsList)): ?>   

                                  <label>Grupo</label>

                                  <select name="group" ng-model="organizationId" class="form-control">

                                  <option value="none">Seleccione un grupo</option>
                                
                                  <?php foreach ($organizationsList as $key => $value): ?>
                              
                                    <option value="<?php echo $key ?>"> <?php echo $value; ?></option>

                                  <?php endforeach; ?>

                                </select>

                              <?php else: ?>  

                                <div class="alert alert-info" role="alert">Aun no perteneces a ningun grupo :sob: </div>
                                  
                              <?php endif; ?>

                              </div>

                              <div ng-show="selectedType == 1">  
 
                                <!-- Entrada de búsqueda -->
                                <input type="text" style="" placeholder="Busca las personas a las que deseas enviar un mensaje..." class="form-control jm-search-people " ng-model="pSearchInput" >
                                


                                <!-- contenedor de resultados -->
                                <div class="jm-search-suggestions-people" style="background-color: white; width: auto;"></div>


                                <br />
                                
                                <input class="form-control" type="text" id="emailSelection" style="" >
                                
                              </div>

                              <br />

                              <p ng-show="recipients == undefined" class="help-block">Debe especificar a quien va dirigido el mensaje</p>
                              


                              <?php

                                  echo $this->Form->input('subject',array(
                                          'class'=>'form-control',
                                          'label'=>'Asunto',
                                          'ng-model'=>'subject',
                                          'required',
                                          'ng-minlength'=>2, 
                                          'ng-maxlength'=>150,
                                          'name'=>'subject'
                                        )
                                  );

                              ?>

                               <p ng-show="messageForm.subject.$error.minlength" class="help-block">El asunto es muy corto</p>
                               <p ng-show="messageForm.subject.$error.maxlength" class="help-block">El asunto es muy largo</p>
                               <p ng-show="messageForm.subject.$error.required" class="help-block">El asunto es obligatorio</p>

                              <br />

                              <?php
                                  echo $this->Form->input('message',array(
                                        'class'=>'form-control',
                                        'label'=>'Mensaje',
                                        'ng-model'=>'message',
                                        'ng-minlength' => 2, 
                                        'ng-maxlength' => 1000,
                                        'required',
                                        'name'=>'message'
                                      )
                                  );

                            ?>
                            

                            <p ng-show="messageForm.message.$error.minlength" class="help-block">El mensaje es muy corto</p>
                            <p ng-show="messageForm.message.$error.maxlength" class="help-block">El mensaje es muy largo</p>
                            <p ng-show="messageForm.message.$error.required" class="help-block">El mensaje es obligatorio</p>
                              

                            </fieldset>

                            <br />

                           
                            <button type="button" class="btn btn-primary" ng-disabled="messageForm.$invalid" ng-click="sendMessage(messageForm.$valid)">
                              <i class="fa fa-envelope" ></i> Enviar
                              </button>

                           <!--  <div style="margin-top: 6px;"></div> -->



                        <?php echo $this->Form->end(); ?>
                        </div>



                      </div>
                      
                    </div>
                    <!-- // END Panes -->

                  </div>


                    <div class="modal fade" id="modalMessageReceivedInfo" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="margin-top: 130px;">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" ><i class="fa fa-fw fa-arrow-circle-down"></i> Correo Recibido</h4>
                          </div>
                          <div class="modal-body">
      
                            <label>Asunto:</label>
                            <br /> 
                            <span>{{messageReceivedContent.Message.subject}}</span>

                            <br />
                            <br />

                            <label>De:</label>
                            <span>{{messageReceivedContent.User.email}}</span>

                            
                            <br />

                            <label>Mensaje:</label>
                            <br />
                          
                              {{messageReceivedContent.Message.message}}

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>



                    <div class="modal fade" id="modalMessageInfo" tabindex="-1" role="dialog" >
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="margin-top: 130px;">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-fw fa-arrow-circle-up"></i> Correo Enviado</h4>
                          </div>
                          <div class="modal-body">
      
                            <label>Asunto:</label>
                            <br /> 
                            <span>{{messageContent.Message.subject}}</span>

                            <br />
                            <br />

                            <label>De:</label>
                            <div ng-repeat="mu in messageContent.MessagesUsers">
                                                    
                                <span>{{mu.User.email}}</span>

                            </div>
                            
                            <br />

                            <label>Mensaje:</label>
                            <br />
                          
                              {{messageContent.Message.message}}

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                          
                          </div>
                        </div>
                      </div>
                    </div>



                    <div class="modal fade" id="modalConfirmDrop" tabindex="-1" role="dialog" >
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="margin-top: 130px;">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="fa fa-fw fa-arrow-circle-up"></i> Confirmar Eliminaci&oacute;n</h4>
                          </div>
                          <div class="modal-body">
      
                            <h4>¿Esta seguro que desea eliminar el mensaje?</h4>

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" ng-click="confirmDrop()">Eliminar</button>

                          </div>
                        </div>
                      </div>
                    </div>



                </div>
    </div>
</div>


                     
<?php 


  /**
     * Arreglo con los estilos necesarios
     * @var Array
     */
    $styles = Array(
        
        /**
         * Estilos de autocompletado
         */
        'magicsuggest-min',   


        // emogify library
        'emojify',

    );



    /**
     * Arreglo con los scripts necesarios
     * @var Array
     */
    $scripts = Array(
    
        /**
         * Angular
         */
        'angular.min',

        /**
         * Angular Pagination
         */
        'paging',


        /**
         * Scripts necesarios para las funcionalidades de autocompletar
         */
        'magicsuggest-min', 


        /**
         * Animación de botones
         */
        'buttonAnimations/buttonAnimations',


        'searchPeople/searchPeople',
        


        // emogify library
        'emojify',

        /**
         * laboratorios
         */
        'messages/messages',       



        );


    /**
     * imprimimos los estilos
     */
    echo $this->Html->css($styles, null, array('block' => 'css'));   


    /**
     * Imprimimos los scripts
     */
    echo $this->Html->script($scripts, array('block' => 'scriptBottom')); 


    ?>
