<?php 

    
    
    class MailboxesController extends AppController
    {
        public $name = 'Mailboxes';
        var $uses = array('Mailbox', 'CategoryMail', 'User', 'Person', 'Chapter');
        
        // Declaracion de helpers que ayudaran en la creacion de formularios
        public $helpers = array ('Html', 'Form', 'Time', 'Js','Session');
        
        // Declaracion de los componentes para inicio de sesion
        public $components = array('Session','RequestHandler', 'Paginator','SimpleEmail');
        

        public $paginate = array (  'limit' => 5,
                                    'order' => array ('Mailbox.created' => 'DESC' ) );
        
        public function beforeFilter() {
            parent::beforeFilter();
        }
         /***************************************************************************************************/
        /******************************** Bandeja de recibidos del usuario *********************************/
        public function mailbox() {
            
      //       $username=$this->Session->read('User.username');
            
      //       // Se envian a la vista las categorías de correo (asunto) 
      //       $subject_options = $this->CategoryMail->find('all');
      //       unset($subject_options[12]); //se elimina la opcion de notificacion, pues solo corresponde al sistema
            
            
      //       foreach ($subject_options as $item) {
      //           $resultados1[$item['CategoryMail']['id']]= $item['CategoryMail']['title'];
      //           $resultados2[$item['CategoryMail']['id']]= $item['CategoryMail']['description'];
      //       }
      //       $this->set('subject',$resultados1);
      //       $this->set('subject_description',$resultados2);
            
      //       $this->Mailbox->recursive = 0;
      //       $this->Paginator->settings = array('limit' => 5,
      //                                          'conditions' => array('Mailbox.username' => $username ));
      //       $mi_busqueda = $this->Paginator->paginate();
		    // $this->set('mailboxes', $mi_busqueda);
            
	   }
        
        public function sent() {
            
            $username=$this->Session->read('User.username');
            
            // Se envian a la vista las categorías de correo (asunto) 
            $subject_options = $this->CategoryMail->find('all');
            
            
            foreach ($subject_options as $item) {
                $resultados1[$item['CategoryMail']['id']]= $item['CategoryMail']['title'];
                $resultados2[$item['CategoryMail']['id']]= $item['CategoryMail']['description'];
            }
            $this->set('subject',$resultados1);
            $this->set('subject_description',$resultados2);
            
            $this->Mailbox->recursive = 0;
            $this->Paginator->settings = array('limit' => 5,
                                               'conditions' => array('Mailbox.sender' => $username ));
            $mi_busqueda = $this->Paginator->paginate();
		    $this->set('mailboxes', $mi_busqueda);
            
	   }
        /*=================================================================================================*/
        
        
        /**
         * add method
         *
         * @return void
         */
          public function add() {
              $subject_options = $this->CategoryMail->find('all');
              unset($subject_options[12]); //se elimina la opcion de notificacion, pues solo corresponde al sistema
              foreach ($subject_options as $item) {
                $resultados1[$item['CategoryMail']['id']]= $item['CategoryMail']['title'];
                $resultados2[$item['CategoryMail']['id']]= $item['CategoryMail']['description'];
            }
            $this->set('subject',$resultados1);
            $this->set('subject_description',$resultados2);
                if ($this->request->is('post')) {
                    $this->Mailbox->create();
                    if ($this->Mailbox->save($this->request->data)) {
                        $this->Session->setFlash('The mail has been sent.', 'default', array('class' => 'alert alert-success'));
                        return $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash('The mail could not be sent. Please, try again.', 'default', array('class' => 'alert alert-danger'));
                    }
                }
                $meseros = $this->Mailbox->CategoryMail->find('list');
                $this->set(compact('category-mails'));
            }
        
        public function reply($goto, $desc, $category) {
              
            $this->set('goto',$goto);
            $this->set('desc',$desc);
            $this->set('category',$category);
              
            $subject_options = $this->CategoryMail->find('all');
              
            foreach ($subject_options as $item) {
                $resultados1[$item['CategoryMail']['id']]= $item['CategoryMail']['title'];
                $resultados2[$item['CategoryMail']['id']]= $item['CategoryMail']['description'];
            }
            
            $this->set('subject',$resultados1);
            $this->set('subject_description',$resultados2);
            
                if ($this->request->is('post')) {
                    $this->Mailbox->create();
                    if ($this->Mailbox->save($this->request->data)) {
                        $this->Session->setFlash('The mail has been sent.', 'default', array('class' => 'alert alert-success'));
                        return $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash('The mail could not be sent. Please, try again.', 'default', array('class' => 'alert alert-danger'));
                    }
                }
                $meseros = $this->Mailbox->CategoryMail->find('list');
                $this->set(compact('category-mails'));
        }
        
        
        /**
         * edit method
         *
         * @throws NotFoundException
         * @param string $id
         * @return void
         */
        /*    public function edit($id = null) {
                if (!$this->Mesa->exists($id)) {
                    throw new NotFoundException(__('Invalid mesa'));
                }
                if ($this->request->is(array('post', 'put'))) {
                    if ($this->Mesa->save($this->request->data)) {
                        $this->Session->setFlash('The mesa has been saved.', 'default', array('class' => 'alert alert-success'));
                        return $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Session->setFlash('The mesa could not be saved. Please, try again.', 'default', array('class' => 'alert alert-danger'));
                    }
                } else {
                    $options = array('conditions' => array('Mesa.' . $this->Mesa->primaryKey => $id));
                    $this->request->data = $this->Mesa->find('first', $options);
                }
                $meseros = $this->Mesa->Mesero->find('list');
                $this->set(compact('meseros'));
            }
        /**
         * delete method
         *
         * @throws NotFoundException
         * @param string $id
         * @return void
         */
        /*    public function delete($id = null) {
                $this->Mesa->id = $id;
                if (!$this->Mesa->exists()) {
                    throw new NotFoundException(__('Invalid mesa'));
                }
                $this->request->allowMethod('post', 'delete');
                if ($this->Mesa->delete()) {
                    $this->Session->setFlash('The mesa has been deleted.', 'default', array('class' => 'alert alert-success'));
                } else {
                    $this->Session->setFlash('The mesa could not be deleted. Please, try again.', 'default', array('class' => 'alert alert-danger'));
                }
                return $this->redirect(array('action' => 'index'));
            }
        }*/
        
        public function view($id = null) {
            $this->Mailbox->id = $id;
            $this->set('email', $this->Mailbox->read());
        }
        
        /**
         * view method
         *
         * @throws NotFoundException
         * @param string $id
         * @return void
         */
        
        // public function view($id = null) {
        //         if (!$this->Mailbox->exists($id)) {
        //             throw new NotFoundException(__('Invalid mesa'));
        //         }
        //         $options = array('conditions' => array('Mesa.' . $this->Mailbox->primaryKey => $id));
        //         $this->set('mesa', $this->Mailbox->find('first', $options));
        //     }



        function delete($id) {
            $this->autoRender=false;
            if (!$this->request->is('post')) {
                throw new MethodNotAllowedException();
            }
            if ($this->Mailbox->delete($id)) {
                //$this->Session->setFlash('The post with id: ' . $id . ' has been deleted.');
                $this->redirect(array('action' => 'index'));
            }
        }
        
        
        public function barrascdcprint($id = null) {
            //$this->autoRender=false;
            $this->layout='zpl';
            
            $this->set('auxilio',"Chapplin");
        }



        public function messageSender(){


            // $this->SimpleEmail->contactMail("hola dsd ", 'julianmc90@gmail.com', 'pepitoperez@gmail.com', 'pepitoperez' ,'Algo Muy Importante!');

            // if ($this->request->is('post')){

            //     $data = $this->request->data;

            //     $message = $data['message'];
            //     $asunto = $data['asunto'];


            //     $this->loadModel('User');

            //     $usersEmails = $this->User->find('all',array('recursive'=> -1, 'fields'=>'email'));

            //     $emails = Array();

            //     foreach ($usersEmails as $userEmail) {
                
            //         $emails[] = $userEmail['User']['email'];  

            //     }

            //     $this->SimpleEmail->massiveMail($message, $emails, 'RedKastella',$asunto);

            // }



        }





        
    }

?>










