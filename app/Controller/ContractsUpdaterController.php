<?php 

class ContractsUpdaterController extends AppController
{


    public $components = array('SimpleEmail'); 


	public function ver(){

		$this->autoRender = false;

		// $this->loadModel('InterestContract');

		// $contracts = $this->InterestContract->find('all',array('conditions'=>array('InterestContract.id in (SELECT interest_contracts_id from contract_historials where id = (SELECT id from contract_historials where interest_contracts_id = InterestContract.id order by id desc limit 1) and estado_del_proceso != "Celebrado")')));

		// pr($contracts);

		// $contracts = $this->InterestContract->find('all',array('conditions'=>array('InterestContract.id not in (SELECT interest_contracts_id from contract_historials where interest_contracts_id = InterestContract.id)')));

		// pr($contracts);

	}


	public function updater(){

		$this->autoRender = false;

		/**
		 * Cron execution in server
		 */
		// curl 'http://redkastella.com/kastella/ContractsUpdater/updater'    */1 * * * *

		$this->loadModel('InterestContract');

		$this->loadModel('ContractUpdaterLog');

		$startDate = Date('Y-m-d H:i:s');

		$contracts = $this->InterestContract->find('all',array('conditions'=>array('InterestContract.id in (SELECT interest_contracts_id from contract_historials where id = (SELECT id from contract_historials where interest_contracts_id = InterestContract.id order by id desc limit 1) and estado_del_proceso != "Celebrado")')));
		
		$this->updateContractNotifications($contracts);

		$contracts = $this->InterestContract->find('all',array('conditions' => array('InterestContract.id not in (SELECT interest_contracts_id from contract_historials where interest_contracts_id = InterestContract.id)')));
		
		$this->updateContractNotifications($contracts);
	
		$endDate = Date('Y-m-d H:i:s');

		$this->ContractUpdaterLog->Create();

		$this->ContractUpdaterLog->save(Array('start' => $startDate, 'end' => $endDate));

	}

	public function updateContractNotifications($contracts){

		$this->loadModel('ContractHistorial');


		/**
		 * Arreglo que contendra los contratos para notificarlos via email
		 */

		 $contractsToEmail = Array();


		foreach ($contracts as $contract) {

			$currentContract =  $contract['InterestContract'];
		
			$lastContractHistoryInfo = $this->findLastContractHistorial($contract['InterestContract']['id']);

				/**
				 * si se encuentra un historial del contrato
				 */
				if($lastContractHistoryInfo){

					$contractHistoryInfo = $this->requestAction(array('controller'=>'Xmlreader', 'action'=>'getByNumCons',$currentContract['num_constancia']));	

					$lastContractHistoryInfo = $lastContractHistoryInfo['ContractHistorial'];

					if(!isset($contractHistoryInfo['number_of_docs'])){
						
						$contractHistoryInfo['number_of_docs'] = 0;
					}

					/**
					 * Detección de cambios en los contratos
					 */
					if ($lastContractHistoryInfo['estado_del_proceso'] != $contractHistoryInfo['estado_del_proceso'] || 
						$lastContractHistoryInfo['number_of_docs'] != $contractHistoryInfo['number_of_docs']) {


						/**
						 * Si hubieron cambios se guarda el nuevo historico
						 */
						$contractHistoryInfo = $this->requestAction(array('controller'=>'Xmlreader', 'action'=>'getByNumCons',$currentContract['num_constancia']));	

						if(!isset($contractHistoryInfo['fecha_apertura_proceso'])){

							$contractHistoryInfo['fecha_apertura_proceso'] = "";
						}

						if(!isset($contractHistoryInfo['fecha_cierre_proceso'])){

							$contractHistoryInfo['fecha_cierre_proceso'] = "";
						}

						$this->ContractHistorial->create();

						$contractHistorial = Array(
						   
						   'estado_del_proceso' 	=> $contractHistoryInfo['estado_del_proceso'], 
												   
						   'fecha_apertura_proceso' => date("Y-m-d H:i:s ", strtotime($contractHistoryInfo['fecha_apertura_proceso'])), 

						   'fecha_cierre_proceso' 	=> date("Y-m-d H:i:s ", strtotime($contractHistoryInfo['fecha_cierre_proceso'])), 

						   'number_of_docs' 		=> $contractHistoryInfo['number_of_docs'],
						   
						   'interest_contracts_id' 	=> $currentContract['id'],
						);

						if($this->ContractHistorial->save($contractHistorial)){

							$this->ContractHistorial->clear();

							/**
							 * Identificador de la historia del contrato
							 */
							$lastContractHistoryInfo = $this->findLastContractHistorial($currentContract['id']);

							$this->saveInterestNotification($lastContractHistoryInfo['ContractHistorial']['id'],$currentContract['id']);
			

							/**
							 * contratos a notificar
							 */
							$contractsToEmail[] = Array(
									
									'id' 					=> $currentContract['id'],
									
									'title' 				=> $currentContract['title'],
									
									'nombre' 				=> $currentContract['nombre'],
									
									'contenido' 			=> $currentContract['contenido'],
									
									'valor' 				=> $currentContract['valor'],
									
									'ciudad' 				=> $currentContract['ciudad'],
									
									'departamento' 			=> $currentContract['departamento'],
									
									'estado_del_proceso' 	=> $contractHistorial['estado_del_proceso'],
									
									'number_of_docs' 		=> $contractHistorial['number_of_docs']
							);							

						}

					}


		
				
				}else{

					/**
					 * Si no se ecuentra ningun historial
					 */

					$contractHistoryInfo = $this->requestAction(array('controller'=>'Xmlreader', 'action'=>'getByNumCons',$currentContract['num_constancia']));	

					if(!isset($contractHistoryInfo['number_of_docs'])){
						
						$contractHistoryInfo['number_of_docs'] = 0;
						
					}

					if(!isset($contractHistoryInfo['fecha_apertura_proceso'])){

						$contractHistoryInfo['fecha_apertura_proceso'] = "";
					}

					if(!isset($contractHistoryInfo['fecha_cierre_proceso'])){

						$contractHistoryInfo['fecha_cierre_proceso'] = "";

					}

					$this->ContractHistorial->create();

					$contractHistorial = Array(
					   
					   'estado_del_proceso' 	=> $contractHistoryInfo['estado_del_proceso'], 
					   
					   'fecha_apertura_proceso' => date("Y-m-d H:i:s ", strtotime($contractHistoryInfo['fecha_apertura_proceso'])), 

					   'fecha_cierre_proceso' 	=> date("Y-m-d H:i:s ", strtotime($contractHistoryInfo['fecha_cierre_proceso'])), 

					   'number_of_docs' 		=> $contractHistoryInfo['number_of_docs'],
					   
					   'interest_contracts_id' 	=> $currentContract['id'],
					);

					$this->ContractHistorial->save($contractHistorial);

					$this->ContractHistorial->clear();

				}	

			}


			/**
			 * si hay contratos para notificar
			 */
			if(count($contractsToEmail)){


				/**
				 * Carga del model de interese de contratos por usuarios
				 */
				$this->loadModel('UserInterestContract');

				/**
				 * identificadores de usuarios a notificar
				 */
				$usersIds = Array();

				/**
				 * Identificadores de contratos
				 */
				$contractIds = Array();

				/**
				 * Recorrido de los contratos a notificar
				 */
				foreach ($contractsToEmail as $contractTe) {
					

					/**
					 * contratos de interes de usuario de la bd
					 */
					$uic = $this->UserInterestContract->find('all',Array('conditions'=>Array('UserInterestContract.interest_contracts_id' => $contractTe['id'] )));

					/**
					 * Identificadores de los contratos
					 */
					$uicArr = $this->getUsersIdFromInterestContracts($uic); 

					/**
					 * conbinamos los identificadores
					 */
					$usersIds = array_merge($usersIds, $uicArr);

					/**
					 * Agregamos el identificador del contrato
					 */
					$contractIds[] = $contractTe['id'];

				}

				/**
				 * Identificadores unicos de usuarios
				 * @var array
				 */
				$usersIds = array_unique($usersIds);

				/**
				 * Modelo de usuarios
				 */
				$this->loadModel('User');

				/**
				 * Emails de usuarios para notificar
				 * @var Array
				 */
				$userEmails = $this->User->find('all',Array('fields' => Array('User.id', 'User.email'), 'conditions'=>Array('User.id'=> $usersIds)));


				/**
				 * recorremos los usuarios 
				 */
				foreach ($userEmails as $userEmail) {
					
					$email = $userEmail['User']['email'];

					$userId = $userEmail['User']['id'];

					/**
					 * Obtenemos la informacion que se enviara como notificación
					 * @var [type]
					 */
					$contracNotificationToUser = $this->getNotificationsContracts($contractsToEmail, $contractIds, $userId);


					$htmlCont = "";

					/**
					 * recorremos los contratos y los formateamos
					 */
					foreach ($contracNotificationToUser as $c) {
						
						$htmlCont .= $this->getHtmlFormattedContract($c);

					}


					/**
					 * Envio del email al usuario
					 */
					$this->SimpleEmail->htmlEmail($htmlCont, $email, 'RedKastella' ,'Tienes Notificaciones de contratos!');

				}

			}


	}	


	/**
	 * Función que obtiene una notificacion de contrato en formato html
	 * @param  Array $c contrato
	 */
	public function getHtmlFormattedContract($c){


		$cr = "<div class='panel' style='border: 1px solid #E8E8E8; width: 332px; padding: 13px; float: left; margin: 10px; color: #000; height: 371px !important;'>";

			$cr .= "<div class='panel-heading' style='color: #000; background-color: black; color: white; margin: -14px; height: 33px; padding-top: 14px; padding-left: 14px;'>".$c['title']."</div>";
				
				$cr .= "<div class='panel-body' style='color: #000;'>";
				
				$cr .= "<h4 style='color: #000;'>".$c['nombre']."</h4>";
				
				$cr .= "<p style='text-align: justify; color: #000;'>".$this->limitLetters($c['contenido'])."</p>";
				
				$cr .= "<p style='color: #000;'><strong>Valor Estimado:</strong> $".$c['valor']."</p>";
				
				$cr .= "<p style='color: #000;'><strong>Ciudad:</strong> ".$c['ciudad']."</p>";
				
				$cr .= "<p style='color: #000;'><strong>Departamento:</strong> ".$c['departamento']."</p>";
				
				$cr .= "<p style='color: #000;'><strong>Estado:</strong> ".$c['estado_del_proceso']."</p>";
				
				$cr .= "<p style='color: #000;'><strong>Documentos:</strong> ".$c['number_of_docs']."</p>";
				
				$cr .='<br />';		

				$cr .='<a href="http://redkastella.com/kastella/InterestContracts/interestedInContracts" style="color: #fff; text-decoration: none; background-color: #000; border-radius: 5px; border-color: black; font-size: 13px; padding: 10px; padding-left: 15px; padding-right: 16px;">Ver en Kastella</a>';

				$cr .= "</div>";				
				
			$cr .= "</div>";

		$cr .= "</div>";

		return $cr;

	}

	/**
	 * funcion que devuelve un strin recortado por un numero de caracteres definido
	 * @param  String $text texto
	 */
	public function limitLetters($text){

		$length = strlen($text);
		
		$res = "";

		if($length < 108){

			$res = $text;
	
		}else{

			$res = substr($text, 0, 108);
		
		}

		return  $res . ' ...';
	}


	/**
	 * Funcion que obtiene los contratos que le intersan a un usuario 
	 */
	public function getNotificationsContracts($contracts, $contractIds, $userId){

			$this->loadModel('UserInterestContract');

			$interests = $this->UserInterestContract->find('all',array('conditions'=>array('UserInterestContract.users_id'=> $userId,'UserInterestContract.interest_contracts_id'=> $contractIds)));

			$result = Array();

			foreach ($interests as $interest) {
				
				$result[] = $this->getContractByIdFromArray($contracts, $interest['UserInterestContract']['interest_contracts_id']);	


			}

			return $result;

	}


	/**
	 * funcion que retorna un contrato por identificador
	 */
	public function getContractByIdFromArray($contracts, $id){


		foreach ($contracts as $contract) {
			

			if($contract['id'] == $id){

				return $contract;
				
				break;
			
			}

		}

	}



	/**
	 * Identificadores de usuarios por interes de contratos
	 * @return Array
	 */
	public function getUsersIdFromInterestContracts($usersInterestContracts){

		$userIds = Array();

		foreach ($usersInterestContracts as $uic) {
				
			$userIds[] = $uic['UserInterestContract']['users_id'];

		}	

		return $userIds;

	}


	/**
	 * Función que guarda las notificaciones de un contrato relacionadas a los usuarios
	 */
	public function saveInterestNotification($contractHistorialId,  $contractId){


		$userInterestContractIds = $this->getUserInterestContractId($contractId);

		if($userInterestContractIds){
	


			$this->loadModel('NotificationsInterestContract');


			foreach ($userInterestContractIds as $id) {

				$this->NotificationsInterestContract->create();	
				
				$this->NotificationsInterestContract->save(Array('contract_historials_id'=>$contractHistorialId, 'user_interest_contracts_id'=> $id,'viewed'=>0));	

				$this->NotificationsInterestContract->clear();

			}


		}else{


		}

	}



	/**
	 * Funcion que encuentra la relacion de a que usuarios les interesa el contrato actualizado 
	 */
	public function getUserInterestContractId($contractId){

	
		$this->loadModel('UserInterestContract');


		$relatedUsers = $this->UserInterestContract->find('all',array('conditions'=>array('UserInterestContract.interest_contracts_id' => $contractId)));

		$userInterestContractIds = Array();

		foreach($relatedUsers as $relatedUser) {
			
			$userInterestContractIds[] = $relatedUser['UserInterestContract']['id'];

		}

		return $userInterestContractIds;


	}

	/**
	 * Funcion que encuentra el ultimo historial de un contrato
	 */
	public function findLastContractHistorial($interestContractsId){


		$this->loadModel('ContractHistorial');
	

		$foundContractHistorial = $this->ContractHistorial->find('first',array('order'=>'ContractHistorial.id DESC', 'conditions'=> array('ContractHistorial.interest_contracts_id' => $interestContractsId)));

		return $foundContractHistorial;

	}




	public function beforeFilter() {
    
        parent::beforeFilter();
 	    $this->Auth->allow('updater');
    }
    

}