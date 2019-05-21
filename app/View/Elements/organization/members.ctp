 <?php 
                        $loggedUser = AuthComponent::user();  
                        ?>

                     
                             <!-- role filter -->
                            <?php if($loggedUser['role_id'] == 3 ): ?>

                            <!-- Invitación de usuarios -->
                                
                            <!-- <label>Invitar usuarios:</label> -->
                            <!-- Campo de selección de emails -->
                            <!-- <input id="emailSelection" class="" name="emails[]" /> -->
                            <!-- Fin campo selección de emails -->
                            <!-- <br/> -->
                            <!-- Botón de invitar -->
                            <!-- <button type="button" id="invite" class="btn btn-primary" ><i class="invite-gls fa fa-user-plus"></i> Invitar</button> -->
                            <!-- Fin Botón de invitar -->
                            
                            <?php endif; ?>


                            <!-- Paginación de usuarios pertenecientes a la entidad -->

                              <!--   <h4>Miembros</h4> -->
                                    <!-- Inicio Tabla de datos -->
                                    <div class="table-responsive">
                                        <table class="table v-middle">
                                            <thead>
                                                <tr>
                                                    <th width="20">
                                                        <div class="checkbox checkbox-single margin-none">
                                                            <input id="checkAll" data-toggle="check-all" data-target="#responsive-table-body" type="checkbox" checked="">
                                                            <label for="checkAll">Check All</label>
                                                        </div>
                                                    </th>
                                                    <!-- Nombre  -->
                                                    <th><?php echo $this->Paginator->sort('User.name','Nombre'); ?></th>

                                                    <!-- Apellido  -->
                                                    <th><?php echo $this->Paginator->sort('User.surname','Apellido'); ?></th>

                                                    <!-- Nombre de usuario  -->
                                                    <th><?php echo $this->Paginator->sort('User.username','Nombre de Usuario'); ?></th>

                                                    <!-- Email  -->
                                                    <th><?php echo $this->Paginator->sort('User.email','Email'); ?></th>

                                                    <!-- Fecha  -->
                                                    <th><?php echo $this->Paginator->sort('OrganizationUser.created','Fecha'); ?></th>

                                                    <!-- Encabezado Vacío -->
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $i =1; ?>
                                                <!-- Recorrido por los resultados -->
                                                <?php foreach ($organizationUsers as $organizationUser) : ?>
                                                <tr>
                                                    <td>
                                                        <div class="checkbox checkbox-single">
                                                            <input id="checkbox<?php echo $i; ?>" type="checkbox" checked="">
                                                            <label for="checkbox<?php echo $i; ?>">Label</label>
                                                        </div>
                                                    </td>
                                                    <!-- Nombre  -->
                                                    <td><?php echo $organizationUser['User']['name']; ?></td>
                                                    
                                                    <!-- Apellido  -->
                                                    <td><?php echo $organizationUser['User']['surname']; ?></td>

                                                    <!-- Nombre de usuario  -->
                                                    <td><?php echo $organizationUser['User']['username']; ?></td>

                                                    <!-- Email  -->
                                                    <td><?php echo $organizationUser['User']['email']; ?></td>

                                                    <!-- Fecha  -->
                                                    <td><?php echo $this->Time->nice($organizationUser['OrganizationUser']['created']); ?></td>

                                                    <!-- Acciones -->
                                                    <td >
                                                        <?php
                                                            echo $this->Html->link(
                                                                'Ver Perfil',
                                                                '/Users/profile/'.$organizationUser['User']['username'],
                                                                array('class' => 'btn btn-sm btn-default', 'target' => '_blank')
                                                            );
                                                        ?>
                                                        <?php
                                                            echo $this->Html->link(
                                                                'Mensajes',
                                                                '/Users/chat',
                                                                array('class' => 'btn btn-sm btn-default not-active', 'target' => '_blank')
                                                            );
                                                        ?>

                                                        <?php
                                                            echo $this->Html->link(
                                                                'Eliminar',
                                                                '/Users/chat',
                                                                array('class' => 'btn btn-sm btn-default not-active', 'target' => '_blank')
                                                            );
                                                        ?>

                                                      </td>
                                                </tr>
                                                <?php $i = $i+1; ?>
                                                <?php endforeach; ?>
                                                <!-- Fin Recorrido de los resultados -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Fin Tabla de datos -->
                                    <!-- Pie de Página  -->
                                    <div class="panel-footer padding-none text-center">

                                        <p class="pagination-info">
                                        <?php echo $this->Paginator->counter(Array('format' => __('Página {:page} de {:pages}, se muestran {:current} resultados.'))); ?>
                                        </p>

                                        <ul class="pagination">
                                            <li>
                                                <?php echo $this->Paginator->prev('< ' . __('anterior'), array('tag' => false), null, array('class' => 'prev disabled')); ?>
                                            </li>

                                            <?php echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'a', 'currentClass' => 'active')); ?>

                                            <li>
                                                <?php echo $this->Paginator->next(__('siguiente') . ' >', array('tag' => false), null, array('class' => 'next disabled')); ?>
                                            </li>
                                        </ul>

                                    </div>
                                    <!-- Fin pie de página -->
                  