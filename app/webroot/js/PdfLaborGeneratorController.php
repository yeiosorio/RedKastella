<?php
namespace App\Controller;

use App\Controller\AppController;
 use Cake\Core\Configure;

use Cake\Datasource\ConnectionManager;







require_once(ROOT . DS . 'vendor' . DS . "tecnick.com" . DS . "tcpdf" . DS . "tcpdf.php");
require_once(ROOT . DS . 'src'.DS.'Controller'.DS.'Component'.DS.'PDFMerger'.DS.'PDFMerger.php');
/**
 * MedicalOffices Controller
 *
 * @property \App\Model\Table\MedicalOfficesTable $MedicalOffices
 */
class PdfLaborGeneratorController extends AppController
{


    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['invoice', 'downloadPrev','printProceClinico', 'printHistoriaClinica', 'printLaborClinico', 'printFormula','printDisability','printRecommendation','downloadServices']);

        $this->loadComponent('StringUtils');
    }

    
    


    /**
     * Carlos Aguirre 2018-03-05 14:14:08
     * Consulta una historia clinica a partir del id del appointment ( appointments_id )
     */
    public function consultarHistoriaClinica($appointments_id = null){

        $manager = new \MongoDB\Driver\Manager(Configure::read('MongoDB.db'));


        $filter = ['appointments_id'=>$appointments_id];
        $options = ['sort' => ['_id' => -1], 'limit'=>1];

        $query = new \MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery(Configure::read('MongoDB.coleccion'), $query);

        foreach ($cursor as $document) {
            if( empty($document) ){
                die(json_encode( [ 'success'=>false] ));
            }else{
                // Consultar una orden
                $this->loadModel('Orders');
                $document = json_decode( json_encode( $document ), true);
                $document['orden'] = $this->Orders->obtenerPorId( $document['orders_id'] );

                return $document;
            }
            
        }
    }
    
    
    
    /**
     * Función para generar factura de ejemplo
     * @return [type] [description]
     * 
     * 2018-03-15: Modificaciones: Giovanny Mairn
     * - Adicion en Direccion y fecha de nacimiento a la consulta
     */
    private function getPatientInforByRecordId($id_medical = null){

        $idAttention = 1;

        if(!empty($id_medical)){

            $query = "SELECT 
            people.id, 
            resources.stored_file_name as path_photo,
             orders.order_consec 'numero_orden',
             CONCAT(people.first_name,
             ' ',
             people.middle_name,
             ' ',
             people.last_name,
             ' ',
             people.last_name_two) paciente,
             eps.name eps,
            CASE rh_id
            when 1 then 'A+'
            when 2 then 'A-'
            when 3 then 'B+'
            when 4 then 'B-'
            when 5 then 'AB+'
            when 6 then 'AB-'
            when 7 then 'O+'
            when 8 then 'O-'
            END rh,
            municipalities.municipality ciudad,
             people.phone 'telefono',
             people.birthdate 'fecha_nacimiento',
             people.address 'direccion',
             clients.name cliente_,
               program_plans.name plan_tarifario,
                -- people.email 'correo',
                IF ((people.email = '' OR people.email is null), 'No Reporta', people.email) 'correo',
                people.identification 'identificacion',
                orders.calculated_age 'edad',
                gender.gender 'genero',
                CONCAT(especialist.first_name,
                ' ',
                especialist.middle_name,
                ' ',
                especialist.last_name,
                ' ',
                especialist.last_name_two) especialista,
                specialists.id codigoSpecialist,
                regimes.regime regimen,
                centers.name sede,
                DATE_FORMAT(attentions.date_time_ini, '%Y-%m-%d %T') fecha_atencion,
                medical_record.id,
                CONCAT(departments.divipola,municipalities.divipola) divipola,
                document_types.initials,
                specialty_specialist.name profesion,
                specialists.professionar_card tarjeta
                FROM
                orders
                LEFT JOIN
                order_appointments ON order_appointments.orders_id = orders.id
                LEFT JOIN
                appointments ON appointments.id = order_appointments.appointments_id
                LEFT JOIN
                patients ON patients.id = orders.patients_id
                INNER JOIN eps ON (eps.id=patients.eps_id )
                LEFT JOIN
                people ON people.id = patients.people_id
             
                LEFT JOIN
                municipalities on people.municipalities_id = municipalities.id
                LEFT JOIN 
                departments on departments.id = municipalities.department_id
                LEFT JOIN 
                document_types on document_types.id = people.document_types_id
                LEFT JOIN
                regimes ON regimes.id = patients.regimes_id
                LEFT JOIN
                centers ON centers.id = orders.centers_id
                LEFT JOIN
                clients ON clients.id = orders.clients_id
 
                  LEFT JOIN
                program_plans ON program_plans.id = orders.program_plans_id
 
                LEFT JOIN
                rates ON rates.id = orders.rates_id
                LEFT JOIN
                gender ON gender.id = people.gender
                LEFT JOIN
                 attentions ON attentions.appointments_id = appointments.id -- (select MAX(att.id) from  attentions att where att.appointments_id = appointments.id )
                 LEFT JOIN
                 users ON users.id = attentions.users_id
                 LEFT JOIN
                 people especialist ON especialist.id = users.people_id
                 LEFT JOIN
                 medical_record ON medical_record.attentions_id = attentions.id
                  LEFT JOIN
                 specialists ON specialists.people_id = especialist.id
                 LEFT JOIN
                 specialty_specialist ON specialty_specialist.id = specialists.speciality
                 LEFT JOIN
                 resources ON resources.entity_id = people.id
                 WHERE
                 medical_record.id = ".$id_medical." and attentions.state = 0;";


        $connection = ConnectionManager::get('default');
        $info       = $connection->execute( $query )->fetchAll('assoc');

        // pr($info);

        // exit();

        if(count($info) > 0){

           return $info[0];

       }

       else{

        return null;
    }

}

}
        /**
         * [getDiagnostics obtiene todos los diagnosticos de un medical record]
         * @author Jefry Londoño <jjmb2789@gmail.com>
         * @date     2017-09-08
         * @datetime 2017-09-08T16:24:54-0500
         * @param    [type]                   $id_medical [description]
         * @return   [type]                               [description]
         */
        public function getDiagnostics($id_medical= null)
        {

            $connection = ConnectionManager::get('default');

            if(!empty($id_medical)){


                $this->loadModel('MedicalRecordDiagnost');

                $getDiagnostics = $this->MedicalRecordDiagnost->obtenerDiagnosticosPaciente(0,1,$id_medical);
       
            return $getDiagnostics;
        

        }

    }

    /**
     * [getOrderAndSpecialist obtiene el numero de orden y el especialista segun el medical record]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-09-08
     * @datetime 2017-09-08T17:28:02-0500
     * @param    string                   $id_medical [description]
     * @return   [type]                               [description]
     */
    public function getOrderAndSpecialist($id_medical='')
    {

        $connection = ConnectionManager::get('default');

        if(!empty($id_medical)){

            $query = "SELECT 
            orders.order_consec,
            attentions.users_id,
            medical_record.specialists_id
            FROM
            orders
            JOIN
            order_appointments ON order_appointments.orders_id = orders.id
            JOIN
            appointments ON appointments.id = order_appointments.appointments_id
            LEFT JOIN
            attentions ON appointments.id =  attentions.appointments_id
            LEFT JOIN
            medical_record ON medical_record.attentions_id = attentions.id

            where medical_record.id = ".$id_medical.";";

            $getInfo = $connection->execute($query)->fetchAll('assoc');

            $specialis = new SpecialistsController();

            $idSpecialis = $specialis->getByUser($getInfo[0]['users_id']);

            $getInfo[0]['id_user'] = $this->Auth->user('id');

            $getInfo[0]['specialists_id'] = $idSpecialis;

            if(count($getInfo) > 0){

                   return $getInfo[0];


            }else{

                return null;

            }

        }
        


    }


    /**
     * [getAccompanyingPerson obtiene el acompañante]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-08-23
     * @datetime 2017-08-23T18:02:36-0500
     * @param    [type]                   $id_medical [description]
     * @return   [type]                               [description]
     */
    public function getAccompanyingPerson($id_medical=null)
    {
        $connection = ConnectionManager::get('default');

        if(!empty($id_medical)){

            $query = "SELECT 
            medical_accompanying_person.id,
            medical_accompanying_person.name,
            medical_accompanying_person.phone,
            medical_accompanying_person.direccion,
            medical_accompanying_person.identification
            FROM
            medical_record
            LEFT JOIN
            medical_accompanying_person ON medical_accompanying_person.medical_record_id = medical_record.id
            WHERE
            medical_record.id = ".$id_medical.";";

            $getAccompanyingPerson = $connection->execute($query)->fetchAll('assoc');

            if($getAccompanyingPerson[0]['id'] != null){

               return $getAccompanyingPerson[0];

           }else{

            return null;
        }

    }

}
/**
* Jefry Londoño <jjmb2789@gmail.com> GL STUDIOS S.A.S 
* @param  null
* @return  void
* @datetime  2017-10-09
* Descripción: 
*/
public function getPrescription($id_medical = '', $prescriptionId)
{
    $connection = ConnectionManager::get('default');
    //die( __LINE__);
    if(!empty($id_medical)){

        $query = "SELECT 
        prescription.id,
        prescription.concec,
        prescription_details.posology,
        DATE_FORMAT(prescription.created,
        '%Y-%m-%d %h:%i %p') date_create,
        medicamentos.precentacion,
        medicamentos.nombre,
        medicamentos.forma_farmaceutica,
        medicamentos.concentracion,
        medicamentos.principio_activo,
        medicamentos.bio as bio,
        prescription.observation 'observacion',
        prescription_details.observation 'observaciones',
        prescription_details.quantity 'cantidad',
        prescription_details.mipres
        FROM
        medical_record
        LEFT JOIN
        clinical_prescriptions ON medical_record.id = clinical_prescriptions.medical_record_id
        LEFT JOIN
        prescription ON prescription.id = clinical_prescriptions.prescription_id
        LEFT JOIN
        prescription_details ON prescription_details.prescription_id = prescription.id
        LEFT JOIN
        medicamentos ON medicamentos.id = prescription_details.medicamentos_id
        WHERE
        medical_record.id = ".$id_medical." 
        AND prescription_details.prescription_id = ".$prescriptionId."
        AND prescription.state = 1;";


        $getPrescription = $connection->execute($query)->fetchAll('assoc');

        
        if(count($getPrescription) > 0 && $getPrescription[0]['id'] != null){

            return $getPrescription;

        }else{

            return null;

        }


}


}

    /**
     * [getServices description]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-08-24
     * @datetime 2017-08-24T14:14:21-0500
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function getServicesLabor($id_medical = '', $laboId)
    {
        $connection = ConnectionManager::get('default');

        if(!empty($id_medical)){

            $query = "SELECT 
            medical_services.id,
            DATE_FORMAT(medical_services.created,
            '%Y-%m-%d %h:%i %p') date_create,
            cups.cups,
            cups.name 'nombre',
            medical_services.observation 'observaciones_generales',
            medical_services.concec,
            medical_services_details.mipres,
            medical_services_details.observations 'observacioines',
            cups_types.type 'tipo_servicio',
            medical_services_details.quantity 'cantidad'
            FROM
            medical_record
            LEFT JOIN
            medical_services_records ON medical_record.id = medical_services_records.medical_record_id
            LEFT JOIN
            medical_services ON medical_services.id = medical_services_records.medical_services_id
            LEFT JOIN
            medical_services_details ON medical_services_details.medical_services_id = medical_services.id
            LEFT JOIN
            cups ON cups.id = medical_services_details.cups_id
            LEFT JOIN
            cups_specializations ON cups_specializations.id = cups.cups_specializations_id
            LEFT JOIN
            cups_types ON cups_types.id = cups_specializations.cups_types_id
            WHERE
            medical_record.id = ".$id_medical."
            and medical_services.id = ".$laboId."
            and cups_types.id = 17
            and medical_services_records.state = 1 
            and medical_services.state = 1 ;";

            $getService = $connection->execute($query)->fetchAll('assoc');

            if(count($getService) > 0 && $getService[0]['id'] != null){
                
                return $getService;
    
            }else{
    
                return null;
    
            }


        }

    }


    /**
    * Jefry Londoño GL STUDIOS S.A.S 
    * @param  null
    * @return  void
    * Descripción: 
    */
    public function getOrderServices($id_medical = '', $serviceId, $intern) 
    {
        $connection = ConnectionManager::get('default');

        if(!empty($id_medical)){
            
            // Si son intrabiomab
            if ($intern == 1) {
                
                    $query = "SELECT 
                    medical_services.id,
                    DATE_FORMAT(medical_services.created,
                    '%Y-%m-%d %h:%i %p') date_create,
                    studies.cup as cups,
                    studies.name 'nombre',
                    medical_services.concec,
                    medical_services_details.mipres,
                    medical_services.observation 'observaciones_generales',
                    medical_services_details.observations 'observacioines',
                    '-' as 'tipo_servicio',
                    medical_services_details.quantity 'cantidad'
                    FROM
                    medical_record
                    LEFT JOIN
                    medical_services_records ON medical_record.id = medical_services_records.medical_record_id
                    LEFT JOIN
                    medical_services ON medical_services.id = medical_services_records.medical_services_id
                    LEFT JOIN
                    medical_services_details ON medical_services_details.medical_services_id = medical_services.id
                    LEFT JOIN
                    studies ON studies.id = medical_services_details.cups_id
                    WHERE
                    medical_record.id = ".$id_medical."
                    AND medical_services.id = ".$serviceId."
                    and medical_services_records.state = 1
                    and medical_services.state = 1
                    AND studies.category_studies_id IN(1,2,3); ";

            }else{

                $query = "SELECT 
                    medical_services.id,
                    DATE_FORMAT(medical_services.created,
                    '%Y-%m-%d %h:%i %p') date_create,
                    cups.cups,
                    cups.name 'nombre',
                    medical_services.concec,
                    medical_services_details.mipres,
                    medical_services.observation 'observaciones_generales',
                    medical_services_details.observations 'observacioines',
                    cups_types.type 'tipo_servicio',
                    medical_services_details.quantity 'cantidad'
                    FROM
                    medical_record
                    LEFT JOIN
                    medical_services_records ON medical_record.id = medical_services_records.medical_record_id
                    LEFT JOIN
                    medical_services ON medical_services.id = medical_services_records.medical_services_id
                    LEFT JOIN
                    medical_services_details ON medical_services_details.medical_services_id = medical_services.id
                    LEFT JOIN
                    cups ON cups.id = medical_services_details.cups_id
                    LEFT JOIN
                    cups_specializations ON cups_specializations.id = cups.cups_specializations_id
                    LEFT JOIN
                    cups_types ON cups_types.id = cups_specializations.cups_types_id
                    WHERE
                    medical_record.id = ".$id_medical."
                    AND medical_services.id = ".$serviceId."
                    and medical_services_records.state = 1
                    and medical_services.state = 1
                    AND cups_types.id <> 17;";
            }

            $getService = $connection->execute($query)->fetchAll('assoc');

            if(count($getService) > 0 && $getService[0]['id'] != null){
                
                return $getService;
    
            }else{
    
                return null;
    
            }

        }

    }
    /**
    * Jefry Londoño GL STUDIOS S.A.S 
    * @param  null
    * @return  void
    * @datetime  2017-10-09
    * Descripción: obtiene las incapacidades
    */
    public function getDisability($id_medical = '')
    {
        $connection = ConnectionManager::get('default');

        if(!empty($id_medical)){

            $query = "
            SELECT 
            medical_disability.id,
            medical_disability.observations,
            medical_disability.date_time_ini,
            medical_disability.date_time_end,
            medical_disability.number_days,
            medical_disability.consec,
            DATE_FORMAT(medical_disability.created,
            '%Y-%m-%d %h:%i %p') date_create,
            medical_disability.created,
            medical_concept,
            type_disability.name,
            medical_disability.state
            FROM
            medical_disability
            JOIN
            type_disability ON type_disability.id = medical_disability.type_disability_id
            WHERE
            medical_disability.medical_record_id = ".$id_medical." AND medical_disability.state = 1;";

            $getDisability = $connection->execute($query)->fetchAll('assoc');

            // pr($getDisability);
            // exit();
            if( isset($getDisability[0]['id']) && $getDisability[0]['id'] != null){
         
               return $getDisability[0];

           }else{

            return null;
        }

    }

}




    /**
     * [getFirmSpecialist Funcion que trae al firma del especialista con el id del mismo]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-08-24
     * @datetime 2017-08-24T16:30:01-0500
     * @param    string                   $value [id especialista]
     * @return   [type]                          [description]
     */
    public function getFirmSpecialist($value = '')
    {   

        $specialistData =  null;

        if(!empty($value)){

            $specialis = new SpecialistsController();

            $firm = $specialis->getSpecialistSignature($value);

            $infoSpecialist = $specialis->getSpecialistById($value);

            $specialistData['url'] = $firm;

            $specialistData['tarjeta'] = $infoSpecialist['professionar_card'];


            // $specialistData['especialidad'] = $infoSpecialist['speciality'];
          
            $query = "SELECT name FROM specialty_specialist WHERE id = ".$infoSpecialist['speciality'];

            $connection = ConnectionManager::get('default');
            $especialidad      =$connection->execute($query)->fetchAll('assoc');


            $specialistData['especialidad'] = $especialidad[0]['name'];




            $specialistData['nombre'] = $infoSpecialist['person']['first_name']." ".$infoSpecialist['person']['middle_name']." ".$infoSpecialist['person']['last_name']." ".$infoSpecialist['person']['last_name_two'];

            $specialistData['iniciales'] = $infoSpecialist['person']['document_type']['initials'];

            $specialistData['identificacion'] = $infoSpecialist['person']['identification'];

        }

        return $specialistData;
    }

    
    /**
     * [getSpecialist Obtiene la informacion del usuario conectado, es decir el usario que imprimio el documento]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-09-07
     * @datetime 2017-09-07T15:43:47-0500
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function getNameUserConnect( $userId )
    {

        $UsersController = new UsersController();
        
        $persona = $UsersController->getUserById( $userId )->toArray();
        $persona = $persona['person'];

        
        $_nombrePersona= $persona['first_name'] . ' ' . $persona['middle_name'] . ' ' . $persona['last_name'] . ' ' . $persona['last_name_two'];
        $_nombrePersona = $this->StringUtils->getInitials($_nombrePersona);

        return $_nombrePersona;

    }


    public function downloadPrev($data,$title){


        if($data == 'true'){

            $this->response->file(WWW_ROOT.'history/'.$title.'.pdf', array('download' => true, 'name' => $title.'.pdf'));

        }

        $this->autoRender= false;

    }

    /**
     * Jefry londoño impresion de formula 
     */
    public function printPrescription($userId = "", $id = null, $validate = true){
        $data = $this->request->data;

        $id = empty($data['id']) ? $id : $data['id'];

        $userId = $this->Auth->user('id');

        $this->loadModel('MedicalRecord');

        $prescriptionId = $this->MedicalRecord->getPrescriptionByMedicalRecord( $id );
      

        $initialsUser = $this->getNameUserConnect( $userId );

        if($validate){

            $this->deleteFiles($initialsUser);

        }           


        foreach ($prescriptionId as $key => $value) {

            $this->printFormula($userId, $id, true, true ,$value['prescription_id'],$initialsUser);

        }


        if($validate){

            $name = 'formulas';
    
            echo json_encode(['success'=>true,'initialsUser' => $initialsUser,'name'=> $name ]);
            $success = $this->unirPDFs(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/','formulas');

            exit();

        }



       
    }

    /**
     * Descarga los servicios
     */
    public function downloadServices($initialsUser, $title){

        $this->response->file(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/'.$title.'.pdf', array('download' => true, 'name' => $title.'.pdf'));


        $this->autoRender= false;

    }

    private function unirPDFs($url = "", $nameFile = ""){
        $pdf = new \PDFMerger;
        $archivos = scandir( $url );
        
        foreach( $archivos as $archivo ){
            
            if( $archivo !== '.' && $archivo !== '..' ){
               $pdf->addPDF( $url.$archivo , 'all');
            }

        }
       
        if(count($archivos) > 2){

            $pdf->merge('file', $url.$nameFile.'.pdf');

            return true;

        }else{

            return false;

        }
        
        // $pdf->Output('file', WWW_ROOT.'history/Historia_Unida.pdf', 'FI');
    }

    /**
    * Funcion para generar el documento formulas médicas
    * @author Giovanny Marin <desarrollo@gatolocostudios.com>
    * @date     2017-08-16
    * @datetime 2017-08-16T12:00:02-0500
    * @return   [type]                   [description]
    */
    public function printFormula($userId = "", $id = null, $imprimir = true, $isArray = false, $prescriptionId = null,  $initialsUser = null)
    {
        $data = $this->request->data;
       
        $this->loadModel('MedicalRecord');

     

        if(empty($id)){

            $id = $data['medialRecord'];
            
            $prescriptionId = $data['prescriptionId'];
  
        }

        $order = $this->getOrderAndSpecialist($id);

        if( empty( $order['users_id'] ) ){
            $order['users_id'] = $userId;
        }

        $userId = ( empty( $userId )? $order['users_id']: $userId );
    
        $initialsUser = $this->getNameUserConnect( $userId );

        // Comentado debido a que no existe tal metodo
        // Yeison osorio 2018/mar/15

        $info = $this->getPatientInforByRecordId($id);
        
       
       // $diagnostico = $this->getDiagnostics($id);

        
        $prescription = $this->getPrescription($id, $prescriptionId);
    
        
        if($prescription != null){

            $print = new ControlPrintHistoryController();
            
            $print->addControlPrintHistory($prescription[0]['id'], 1 , $order['users_id']);     
                     
                /*Se obtiene la URL de la firma del espcialista
                 */
                $specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
                    
            
            
                $query = "SELECT 
                  authorize_medical_record.*, specialists.id AS specialist_id
                FROM
                    authorize_medical_record
                        left  JOIN
                    specialists ON specialists.users_id = authorize_medical_record.users_id_authorize
                WHERE
                    authorize_medical_record.medical_record_id  = '". $id."'
                    and  authorize_medical_record.state = 1 ";


                $connection = ConnectionManager::get('default');

                $specialistResult       = $connection->execute( $query )->fetchAll('assoc');

                $specialistSupervisor =  empty($specialistResult) ? '' : $this->getFirmSpecialist($specialistResult[0]['specialist_id']);
            
                if( empty($specialistSupervisor)){
                    
                    $info['especialista'] = $specialistData['nombre'];
                }else{
                    $info['especialista'] = $specialistSupervisor['nombre'];
                }
    
            $this->autoRender = false;

            // Instanciacion
            $tcpdf = new XTCPDF(); 
    
            // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');
    
            // Info del documento
            $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 
    
            // <b style="left:2em">  Fecha: '.date('Y-m-d').' '.date('H:i:s').'  Página'.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</b>
            
            // Informacion del ecabezado y footer
            
            $longitud = 7;
          
    
            $consecutivo = str_pad($prescription[0]['concec'], $longitud, '0', STR_PAD_LEFT);
    
            //print_r($info); exit();
            $tcpdf->xheadertext = '
            <table style="padding: 2px; width: 100%" border="0">
                <tr style="margin:2px; ">
                    <td style="width: 15%">
                        <br><img style=" height:70;" src="img/logo_CDC.png">
                    </td>
                    <td style="text-align:center; width: 60%; LINE-HEIGHT:11px; font-family: Verdana !important">
                        <br>
                        <br>
                        <strong style="color: #0d72b3; font-size: 80%">CDC Centro de Diagnóstico Clínico S.A.S.</strong><br>
                        <strong style="color: #0d72b3; font-size: 60%">NIT: 900.220.827-2</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Calle 17N # 11-70 Piso 4. Telefax: (6) 748 5515</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Cel: 314 680 1257 - www.cdclaboratorio.com</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Armenia - Quindío - Colombia</strong>
                    </td>
                    <td style="width:25%; text-align: right; font-size: 80%">
                        <br><br>
                        <strong>Fórmula Nº:</strong> 
                        <br><strong>'.$consecutivo.' </strong> 
                    </td>
                </tr>
            </table>

            <!--Antes Entidad - Cliente / Grupo - Tarifa -->
            <table style="border: 0,5px solid black; width: 100%;  padding: 1px;">
                <tr style="font-size: 55%">
                    <td style="width:18%; "> <br>
                        <strong>Nro. Orden: </strong><br>
                        <strong>Paciente: </strong><br>
                        <strong>Teléfono: </strong><br>
                        <strong>Cliente: </strong><br>
                        <strong>Tarifa: </strong><br>
                        <strong>Médico: </strong>
                    </td>
                    <td style="width:44%; ">  
                        '.$info['numero_orden'].'<br>
                        '.$info['paciente'].'<br>
                        '.$info['telefono'].'<br>
                        '.$info['cliente_'].'<br>
                        '.$info['tarifa'].'<br>
                        '.$info['especialista'].'
                    </td>
                    <td style="width:16%; "> <br> 
                        <strong>Nro. Documento: </strong><br>
                        <strong>Edad: </strong><br>
                        <strong>Sexo: </strong><br>
                        <strong>Regimen: </strong><br>
                        <strong>Fecha de Atención:</strong><br>
                        <strong>Sede de Atención:</strong>
                    </td>
                    <td style="width:22%; ">
                        '.$info['identificacion'].'<br>
                        '.$info['edad'].'<br>
                        '.$info['genero'].'<br>
                        '.$info['regimen'].'<br>
                        '.$info['fecha_atencion'].'<br>
                        '.$info['sede'].'
                    </td>
                </tr>
            </table>
            '; 
            
    
            
             if( empty($specialistSupervisor)){
                     
                        $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:80%; font-size:7px !important; width:80%;"> 
                                    <br><b><span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                                </td>
                            </tr>
                        </table>';

                }else{
                
       
                    $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                        <tr> 
                            <td style="text-align: right; width:20%;"> 
                                <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                            </td>
                            <td style="width:30%; font-size:7px !important; "> 
                                <br><b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                            </td>
                              <td style="text-align: right; width:20%;"> 
                                <img  src="'.$specialistSupervisor['url'].'" style="width:50px !important; height:30px !important;" >
                            </td>
                            <td style="width:30%; font-size:7px !important; "> 
                                <br><b> <span style="font-size:7px;">'.$specialistSupervisor['nombre'].' </span> <br>
                                <span style="font-size:7px;">'.$specialistSupervisor['especialidad'].'</span> <br>
                                <span style="font-size:7px;">'.$specialistSupervisor['tarjeta'].'</span></b>
                            </td>
                        </tr>
                    </table>';

                    
                }
            
            
            // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
            $tcpdf->xfootertext = '
                <table style="padding: 2px; width: 100%" border="0">
                    <tr style="font-size: 7px;">   
                        <td style=" text-align: center; font-size:7px; width:30%">
                            <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                        </td>
                        <td style="width: 10%">
                            <img style=" height:50 important;" src="'.$specialistData['url'].'" >
                        </td>
                        <td style="width:60%; font-size:7px !important; width:80%;"> 
                            <b><br><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                            <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                            <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                        </td>
                    </tr>
                </table>
            ';
                    
    
            // Fuentes del doc
            $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
            //$tcpdf->SetFont('Verdana', '', 10);
            // Margenes 
            // $tcpdf->SetMargins(10, 63, 3, false);
    
    
            /**
             * inicio de contenido
             */
          
            $tcpdf->SetMargins(15, 50, 15, false);
    
            $tcpdf->SetHeaderMargin(10);
            $tcpdf->SetFooterMargin(20);
            
            // Cambio de pagina
            $tcpdf->SetAutoPageBreak(true, 20); 
            
            //$tcpdf->setHeaderFont(array($textfont,'',40)); 
            //$tcpdf->xheadercolor = array(150,0,0); 
    
            // Validacion para la aparicion tanto del header como del footer
            $tcpdf->SetPrintHeader(true);
            $tcpdf->SetPrintFooter(true);
    
            // Adicion de nueva pagina con tamaño predefinido en mm
            //$resolution= array(216, 279);
            //$tcpdf->AddPage('P', $resolution);
            $resolution= array(216, 139);
            $tcpdf->AddPage('L', $resolution);
            
            setlocale(LC_MONETARY, 'en_US');
            
            //$tcpdf->SetFont('freesans', '', 12);
    
            // contenido del resultado
            $html="";
         
            if(!empty($diagnostico)){
             
                $html  = '
                <table style="width: 100%;  padding: 1px;">
                    <tr style="font-size: 55%">
                        <td style="width:100%; text-align:center">
                            <br><strong style="font-family:Verdana; font-size: 100%; color: #2B1C82; text-align:center">Diagnóstico(s):</strong>
                        </td>
                    </tr>
                    <tr style="font-size: 55%">
                        <td style="width:10%; border: 0,2px solid black; text-align: center">
                            <br><strong>CODIGO:</strong>
                        </td>
                        <td style="width:70%; border: 0,2px solid black;"> 
                            <br><strong>NOMBRE:</strong>
                        </td>
                        <td style="width:20%; border: 0,2px solid black;"> 
                            <br><strong>TIPO:</strong>
                        </td>
                    </tr>';
    
                    for ($i=0; $i < count($diagnostico); $i++) { 
    
                    $html.= '
                    <!-- Inicio de recorrido para diagnóstico -->
                        <tr style="font-size: 55%">
                            <td style="text-align: center; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['cie10'].'
                            </td>
                            <td style="text-align: justify; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['description'].'
                            </td>
                            <td style="text-align: center; border: 0,2px solid black;" >
                                <br>'.$diagnostico[$i]['diagnosticType'].'
                            </td>
                        </tr>';
                        
                        
                    }
    
                    $html .= ' </table>';
                }
              

                if(!empty($prescription)){
                  
                    
                    $html .= $this->getHTMLPrescription( $prescription );
                 
                }     
            
                  // pr(WWW_ROOT.'history/'.$orders.'Formula.pdf');
    
                    // exit();
    
                    // output the HTML content
                   
                    $tcpdf->writeHTML($html, true, false, true, false, '');
                  
    
                    if($isArray){
                    
                        if(!file_exists(WWW_ROOT."/history_pharmacy/".$initialsUser)){
                            

                            mkdir(WWW_ROOT."/history_pharmacy/".$initialsUser, 0777);

                        }                    
                      
                        $tcpdf->Output(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/'.$order['order_consec'].'Formula_'.$prescriptionId.'.pdf', 'F');
                     

                    }else{
                        // pr($order['order_consec']);

                    if( $imprimir ){
                        $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'Formula.pdf', 'FI');
                    }
                    else{
                        $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'Formula.pdf', 'FI');
                    }

        }
  
      
        

}

}
    
    
  /**
    * Funcion para generar el HTML DE FORMULAS MEDICAS PARA SER UTILIZADOS DESDE DIFERENTES PARTES
    
    */
    private function getHTMLPrescription($prescription){
        
        // Configure::write('debug', 2);
        

        $html = '';
                    $html .='
                    <table style="width: 99%;  padding: 1px; margin-top: 10px">
                        <tr style="font-size: 55%">
                            <td style="width:100%; text-align:center; line-height:35%">
    
                            </td>
                        </tr> 
                  
                        <tr style="font-size: 55%">
                            <td style="width:4%; border: 0,5px solid black; text-align: center">
                                <br><strong>No.</strong>
                            </td>';
    
                            $aux = false;
    
                            for ($i=0; $i < count($prescription) ; $i++) { 
                                
                                if(!empty($prescription[$i]['mipres'])){                                
    
                                    $html .='
                                    <td style="width:28%; border: 0,5px solid black; text-align: center"> 
                                        <br><strong>MEDICAMENTO</strong>
                                    </td>
                                    <td style="width:10%; border: 0,5px solid black; text-align: center"> 
                                        <br><strong>MIPRES</strong>
                                    </td>';
    
                                    $aux = true;
    
                                    break;
    
                                }
    
                            }
    
                            if(!$aux){
    
                                $html .='
                                <td style="width:38%; border: 0,5px solid black; text-align: center"> 
                                    <br><strong>MEDICAMENTO</strong>
                                </td>';
    
                            }
    
                            $html .='
                            <td style="width:20%; border: 0,5px solid black; text-align: center"> 
                                <br><strong>PRESENTACION</strong>
                            </td>
                            <td style="width:10%; border: 0,5px solid black; text-align: center"> 
                                <br><strong>CANTIDAD</strong>
                            </td>
                               <td style="width:29%; border: 0,5px solid black; text-align: center"> 
                                <br><strong>DOSIS Y FRECUENCIAS</strong>
                            </td>
                            
                        </tr>';
    
                        for ($i = 0; $i < count($prescription); $i++) { 
           
    
                            $html .= '
                            <tr style="font-size: 55%">
                                <td style="border: 0,5px solid black; text-align: center">
                                    <br>'.($i+1).'
                                </td>';
                                // pr($prescription[$i]);
                                // exit();
    
                                if($aux){
    
                                    $mipres = empty($prescription[$i]['mipres']) ? "" : $prescription[$i]['mipres'];
    
                                    $html .= '
                                        <td style="border: 0,5px solid black; text-align: justify"> 
                                            <br>'.htmlentities($prescription[$i]['nombre']).'
                                            <br><span style = "font-size: 5px; !important">'.htmlentities($prescription[$i]['principio_activo']).'</span>
                                           
                                        </td>
                                        <td style="border: 0,5px solid black; text-align: center"> 
                                            <br>'.htmlentities($mipres ).'
                                        </td>';
    
                                }else{
    
                                    $html .= '
                                    <td style="border: 0,5px solid black; text-align: justify"> 
                                        <br>'.htmlentities($prescription[$i]['nombre']).'
                                        <br><span style = "font-size: 5px; !important">'.htmlentities($prescription[$i]['principio_activo']).'</span>

                                    </td>';
    
                                }
                            
                                    if($prescription[$i]['bio'] == 1){
                                        
                                        $bio = '<br> <strong>Estado Biologico : </strong> '. htmlentities($prescription[$i]['observaciones']);
                                    }else{
                                        $bio = '';
                                    }
                                    // Devuelve la cantidad en letras

                                $letterCant = $this->numToLetras($prescription[$i]['cantidad']);

                            
                                $html .= '
                                <td style="border: 0,5px solid black; font-size: 6px; text-align: center"> 
                                    <br>'.htmlentities($prescription[$i]['forma_farmaceutica']).'   <br> '.$bio.'
                                </td>
                                <td style="border: 0,5px solid black; text-align: center">
                                    <br>'.htmlentities($prescription[$i]['cantidad']).'
                                    <br>( '.$letterCant.' )
                                </td>
                                    <td style="border: 0,5px solid black; text-align: justify"> 
                                    <br><span style = "font-size: 7px; !important"><b>'. htmlentities($prescription[$i]['posology']).'</b></span>
                                    </td>
                            </tr>
                            ';
    
                        }
    
                        $html .= '</table><br>';
                    
                    
                    
    
                        $html .= ' <table style="width: 99%;  padding: 1px; margin-top: 10px">
                                <tr style=" font-size: 55%">
                                    <td style=" text-align: justify">
                                          <br><strong>Informe Clínico: </strong>
                                          '.htmlentities($prescription[0]['observacion']).'
                                    </td>
                                </tr>
                         </table>';
                      
    return $html;
        
    }
    
    public function numToLetras($xcifra){

        if($xcifra == 1){return "UNO";}
    
        $xarray = array(0 => "Cero",
            1 => "UNO", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 
            30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 
            600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
            );
                //
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, ".");
        $xaux_int = $xcifra;
                //$xdecimales = "00";
        if (!($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = "0" . $xcifra;
                $xpos_punto = strpos($xcifra, ".");
            }
                    $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
                    //$xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
                }
    
                $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
                $xcadena = "";
                for ($xz = 0; $xz < 3; $xz++) {
                    $xaux = substr($XAUX, $xz * 6, 6);
                    $xi = 0;
                    $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
                    $xexit = true; // bandera para controlar el ciclo del While
                    while ($xexit) {
                        if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                            break; // termina el ciclo
                        }
    
                        $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                        $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                        for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                            switch ($xy) {
                                case 1: // checa las centenas
                                    if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
    
                                    } else {
                                        $key = (int) substr($xaux, 0, 3);
                                        if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                            $xseek = $xarray[$key];
                                            $xsub = $this->subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                            if (substr($xaux, 0, 3) == 100)
                                                $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                            else
                                                $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                            $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                        }
                                        else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                            $key = (int) substr($xaux, 0, 1) * 100;
                                            $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                            $xcadena = " " . $xcadena . " " . $xseek;
                                        } // ENDIF ($xseek)
                                    } // ENDIF (substr($xaux, 0, 3) < 100)
                                    break;
                                case 2: // checa las decenas (con la misma lógica que las centenas)
                                if (substr($xaux, 1, 2) < 10) {
    
                                } else {
                                    $key = (int) substr($xaux, 1, 2);
                                    if (TRUE === array_key_exists($key, $xarray)) {
                                        $xseek = $xarray[$key];
                                        $xsub = $this->subfijo($xaux);
                                        if (substr($xaux, 1, 2) == 20)
                                            $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                        else
                                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                        $xy = 3;
                                    }
                                    else {
                                        $key = (int) substr($xaux, 1, 1) * 10;
                                        $xseek = $xarray[$key];
                                        if (20 == substr($xaux, 1, 1) * 10)
                                            $xcadena = " " . $xcadena . " " . $xseek;
                                        else
                                            $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                                        } // ENDIF ($xseek)
                                    } // ENDIF (substr($xaux, 1, 2) < 10)
                                    break;
                                case 3: // checa las unidades
                                    if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
    
                                    } else {
                                        $key = (int) substr($xaux, 2, 1);
                                        $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                        $xsub = $this->subfijo($xaux);
                                        $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                    } // ENDIF (substr($xaux, 2, 1) < 1)
                                    break;
                            } // END SWITCH
                        } // END FOR
                        $xi = $xi + 3;
                    } // ENDDO
    
                    if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                    $xcadena.= " DE";
    
                    if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                    $xcadena.= " DE";
    
                    // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
                    if (trim($xaux) != "") {
                        switch ($xz) {
                            case 0:
                            if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                                $xcadena.= "UN BILLON ";
                            else
                                $xcadena.= " BILLONES ";
                            break;
                            case 1:
                            if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                                $xcadena.= "UN MILLON ";
                            else
                                $xcadena.= " MILLONES ";
                            break;
                            case 2:
                            if ($xcifra < 1) {
                                $xcadena = "";
                                    //$xcadena = "CERO PESOS $xdecimales/100 M.N.";
                            }
                            if ($xcifra >= 1 && $xcifra < 2) {
                                $xcadena = "";
                                    //$xcadena = "UN PESO $xdecimales/100 M.N. ";
                            }
                            if ($xcifra >= 2) {
                                    $xcadena.= ""; //
                                    //$xcadena.= " PESOS $xdecimales/100 M.N. "; //
                                }
                                break;
                        } // endswitch ($xz)
                    } // ENDIF (trim($xaux) != "")
                    // ------------------      en este caso, para México se usa esta leyenda     ----------------
                    $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
                    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
                    $xcadena = str_replace("UNO UNO", "UNO", $xcadena); // quito la duplicidad
                    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
                    $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
                    $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
                    $xcadena = str_replace("DE UNO", "UNO", $xcadena); // corrigo la leyenda
                } // ENDFOR ($xz)
                return trim($xcadena);
    }
        
    public function subfijo($xx){ // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
            $xsub = "";
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
            $xsub = "MIL";
        //
        return $xsub;
    }
    
    

    public function deleteFiles($initialsUser = null,$identification = ''){

        if(file_exists(WWW_ROOT."/history_pharmacy/".$initialsUser)){
    
            $archivos = scandir(WWW_ROOT."/history_pharmacy/".$initialsUser.'/'.$identification.'/' );
    
            foreach( $archivos as $archivo ){
                if( $archivo !== '.' && $archivo !== '..' ){
                    unlink( WWW_ROOT."/history_pharmacy/".$initialsUser.'/'.$identification.'/'.$archivo );
                }
            }
    
        }   
    
    }


public function printLaboratory($userId = "", $id = null, $validate = true)
{
        $data = $this->request->data;

        $id = empty($data['id']) ? $id : $data['id'] ;

        $userId = $this->Auth->user('id');

        $this->loadModel('MedicalRecord');

        $servicesId = $this->MedicalRecord->getServicesByMedicalRecord( $id , true);

        $initialsUser = $this->getNameUserConnect( $userId );


        if($validate){

            $this->deleteFiles($initialsUser);

        }


        foreach ($servicesId as $key => $value) {

            $this->printLaborClinico($userId, $id, true, true ,$value['medical_services_id'],$initialsUser);

        }

        if($validate){

            $success = $this->unirPDFs(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/','laboratorio');

            $name = 'laboratorio';

            echo json_encode(['success'=>$success,'initialsUser' => $initialsUser,'name'=> $name ]);
            exit();

        }        


}    
/**
    * Funcion para generar el documento de laboratorios clinicos
    * @author Giovanny Marin <desarrollo@gatolocostudios.com>
    * @date     2017-08-16
    * @datetime 2017-08-16T12:00:02-0500
    * @return   [type]                   [description]
    */
    public function printLaborClinico($userId = "",$id = null, $imprimir = true, $isArray = false, $laboId = null,  $initialsUser = null)
    {
        $data = $this->request->data;

        $this->loadModel('MedicalRecord');

       

        if(empty($id)){

            $id = $data['medialRecord'];

            $laboId = $data['laboId'];
            
           
        }

        $order = $this->getOrderAndSpecialist($id);

        if( empty( $order['users_id'] ) ){

            $order['users_id'] = $userId;

        }

        $userId = ( empty( $userId )? $order['users_id']: $userId );
    
        $initialsUser = $this->getNameUserConnect( $userId );

        // Comentado debido a que no existe tal metodo
        // Yeison osorio 2018/mar/15
        // $laboId = $this->MedicalRecord->getServicesByMedicalRecord( $id, true );// Obtiene el id del registro de laboratorios


        $info = $this->getPatientInforByRecordId($id);

        $diagnostico = $this->getDiagnostics($id);

        $servicio = $this->getServicesLabor($id, $laboId);

        if($servicio != null){

            $print = new ControlPrintHistoryController();
            
            $print->addControlPrintHistory($servicio[0]['id'], 2 , $order['users_id']);
    
    
              /*Se obtiene la URL de la firma del espcialista
                 */
                $specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
                    
            
            
                $query = "SELECT 
                  authorize_medical_record.*, specialists.id AS specialist_id
                FROM
                    authorize_medical_record
                        left  JOIN
                    specialists ON specialists.users_id = authorize_medical_record.users_id_authorize
                WHERE
                    authorize_medical_record.medical_record_id  = '". $id."'
                    and  authorize_medical_record.state = 1 ";


                $connection = ConnectionManager::get('default');

                $specialistResult       = $connection->execute( $query )->fetchAll('assoc');

                $specialistSupervisor =  empty($specialistResult) ? '' : $this->getFirmSpecialist($specialistResult[0]['specialist_id']) ;
    
            $this->autoRender = false;
            // Instanciacion
            $tcpdf = new XTCPDF(); 
    
            // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');
    
            // Info del documento
            $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 
    
            // <b style="left:2em">  Fecha: '.date('Y-m-d').' '.date('H:i:s').'  Página'.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</b>
            
            // Informacion del ecabezado y footer
            $longitud = 4;
    
            $consecutivo = str_pad($servicio[0]['concec'], $longitud, '0', STR_PAD_LEFT);
    
            $tcpdf->xheadertext = '
                <table style="padding: 2px; width: 100%" border="0">
                    <tr style="margin:2px; ">
                        <td style="width: 15%">
                            <br><img style=" height:70;" src="img/logo_CDC.png">
                        </td>
                        <td style="text-align:center; width: 60%; LINE-HEIGHT:11px; font-family: Verdana !important">
                            <br>
                            <strong style="color: #0d72b3; font-size: 80%">CDC Centro de Diagnóstico Clínico S.A.S.</strong><br>
                            <strong style="color: #0d72b3; font-size: 60%">NIT: 900.220.827-2</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Calle 17N # 11-70 Piso 4. Telefax: (6) 748 5515</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Cel: 314 680 1257 - www.cdclaboratorio.com</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Armenia - Quindío - Colombia</strong>
                        </td>
                        <td style="width:25%; text-align: right; font-size: 80%">
                            <br><strong>Orden de Servicio Nº: </strong> 
                            <br><strong>'.$consecutivo.' </strong> 
                        </td>
                    </tr>
                </table>

                <table style="border: 0,5px solid black; width: 100%;  padding: 1px;">
                    <tr style="font-size: 60%">
                        <td style="width:18%; "> <br>
                            <strong>Nro. Orden: </strong><br>
                            <strong>Paciente: </strong><br>
                            <strong>Teléfono: </strong><br>
                            <strong>Cliente: </strong><br>
                            <strong>Correo Electrónico: </strong><br>
                            <strong>Médico: </strong>
                        </td>
                        <td style="width:44%; ">  
                            '.$info['numero_orden'].'<br>
                            '.$info['paciente'].'<br>
                            '.$info['telefono'].'<br>
                            '.$info['cliente_'].'<br>
                            '.$info['correo'].'<br>
                            '.$info['especialista'].'
                        </td>
                        <td style="width:16%; "> <br> 
                            <strong>Nro. Documento: </strong><br>
                            <strong>Edad: </strong><br>
                            <strong>Sexo: </strong><br>
                            <strong>Regimen: </strong><br>
                            <strong>Fecha de Atención:</strong><br>
                            <strong>Sede de Atención:</strong>
                        </td>
                        <td style="width:22%; ">
                            '.$info['identificacion'].'<br>
                            '.$info['edad'].'<br>
                            '.$info['genero'].'<br>
                            '.$info['regimen'].'<br>
                            '.$info['fecha_atencion'].'<br>                    
                            '.$info['sede'].'
                        </td>
                    </tr>
                </table>
            '; 
            
            
             if( empty($specialistSupervisor)){
                     
                        $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:80%; font-size:7px !important; width:80%;"> 
                                <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                                </td>
                            </tr>
                        </table>';

                }else{
                
       
                            $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:30%; font-size:7px !important; "> 
                                <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                                </td>
                                  <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistSupervisor['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:30%; font-size:7px !important; "> 
                                <b> <span style="font-size:7px;">'.$specialistSupervisor['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['tarjeta'].'</span></b>
                                </td>
                            </tr>
                        </table>';

                    
                }
            
            // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
            $tcpdf->xfootertext = '
                <table style="padding: 2px; width: 100%" border="0">
                    <tr style="font-size: 7px;">   
                        <td style=" text-align: center; font-size:7px; width:30%">
                            <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                        </td>
                        <td style="width: 10%">
                            <img style=" height:50 important;" src="'.$specialistData['url'].'" >
                        </td>
                        <td style="width:60%; font-size:7px !important; width:80%;"> 
                            <b><br><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                            <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                            <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                        </td>
                    </tr>
                </table>
            ';
    
            // Fuentes del doc
            $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
            //$tcpdf->SetFont('Verdana', '', 10);
            // Margenes 
            // $tcpdf->SetMargins(10, 63, 3, false);
    
    
            /**
             * inicio de contenido
             */
            $tcpdf->SetMargins(15, 53, 15, false);
    
            $tcpdf->SetHeaderMargin(10);
            $tcpdf->SetFooterMargin(75);
            
            // Cambio de pagina
            $tcpdf->SetAutoPageBreak(true,20); 
            
            //$tcpdf->setHeaderFont(array($textfont,'',40)); 
            //$tcpdf->xheadercolor = array(150,0,0); 
    
            // Validacion para la aparicion tanto del header como del footer
            $tcpdf->SetPrintHeader(true);
            $tcpdf->SetPrintFooter(true);
    
            // Adicion de nueva pagina con tamaño predefinido en mm
            //$resolution= array(216, 279);
            //$tcpdf->AddPage('P', $resolution);
            $resolution= array(216, 139);
            $tcpdf->AddPage('L', $resolution);
            
            setlocale(LC_MONETARY, 'en_US');
            
            //$tcpdf->SetFont('freesans', '', 12);
    
            // contenido del resultado
            
            $html="";
    
            if(!empty($diagnostico)){
    
                $html  = '
                <table style="width: 100%;  padding: 1px;">
                    <tr style="font-size: 60%">
                        <td style="width:100%; text-align:center">
                            <br><strong style="font-family:Verdana; font-size: 115%; color: #2B1C82; text-align:center">Diagnóstico(s):</strong>
                        </td>
                    </tr>
                    <tr style="font-size: 60%">
                        <td style="width:10%; border: 0,2px solid black; text-align: center">
                            <br><strong>CODIGO:</strong>
                        </td>
                        <td style="width:70%; border: 0,2px solid black;"> 
                            <br><strong>NOMBRE:</strong>
                        </td>
                        <td style="width:20%; border: 0,2px solid black;"> 
                            <br><strong>TIPO:</strong>
                        </td>
                    </tr>';
    
                    for ($i=0; $i < count($diagnostico); $i++) { 
    
                         $html.= '
                    <!-- Inicio de recorrido para diagnóstico -->
                        <tr style="font-size: 50%">
                            <td style="text-align: center; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['cie10'].'
                            </td>
                            <td style="text-align: justify; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['description'].'
                            </td>
                            <td style="text-align: center; border: 0,2px solid black;" >
                                <br>'.$diagnostico[$i]['diagnosticType'].'
                            </td>
                        </tr>';
                        
    
                    }
    
                    $html .= ' </table>';
    
    
                }
    
                if(!empty($servicio)){
    
                    $html .= '
                    <table style="width: 100%;  padding: 1px; margin-top: 10px">
                        <tr style="font-size: 60%">
                            <td style="width:100%; text-align:center; line-height:35%">
    
                            </td>
                        </tr> 
                   
                        <tr style="font-size: 65%">
                            <td style="width:4%; border: 0,5px solid black; text-align: center"> 
                                <br><strong>No.</strong>
                            </td>
                            <td style="width:6%; border: 0,5px solid black; text-align: center">  
                                <br><strong>CUPS</strong>
                            </td>';
    
                            $aux = false;
                            
                            for ($i=0; $i < count($servicio) ; $i++) { 
                                
                                if(!empty($servicio[$i]['mipres'])){                                
    
                                    $html .='
                                    <td style="width:49%; border: 0,5px solid black; text-align: center">  
                                        <br><strong>SERVICIO</strong>
                                    </td>
                                    <td style="width:14%; border: 0,5px solid black; text-align: center"> 
                                        <br><strong>MIPRES</strong>
                                    </td>';
    
                                    $aux = true;
    
                                    break;
    
                                }
    
                            }
    
                            if(!$aux){
    
                                $html .='
                                <td style="width:63%; border: 0,5px solid black; text-align: center">  
                                <br><strong>SERVICIO</strong>
                            </td>';
    
                            }
    
    
                            $html .= '<td style="width:18%; border: 0,5px solid black; text-align: center">  
                                <br><strong>OBSERVACIONES</strong>
                            </td>
                            <td style="width:9%; border: 0,5px solid black; text-align: center">  
                                <br><strong>CANTIDAD</strong>
                            </td>
                        </tr>';
    
                        for ($i=0; $i < count($servicio); $i++) { 
    
                            $html .= '
                            <tr style="font-size: 65%">
                                <td style="border: 0,5px solid black; text-align: center"> 
                                    <br>'.($i +1).'
                                </td>
                                <td style="border: 0,5px solid black; text-align: center"> 
                                    <br>'.$servicio[$i]['cups'].'
                                </td>';
                                
                                if($aux){
                                    
                                    $mipres = empty($servicio[$i]['mipres']) ? "" : $servicio[$i]['mipres'];
    
                                    $html .= '
                                        <td style="border: 0,5px solid black; text-align: justify"> 
                                            <br>'.$servicio[$i]['nombre'].'
                                        </td>
                                        <td style="border: 0,5px solid black; text-align: center"> 
                                            <br>'.$mipres .'
                                        </td>';
    
                                }else{
    
                                    $html .= '
                                    <td style="border: 0,5px solid black; text-align: justify"> 
                                        <br>'.$servicio[$i]['nombre'].'
                                    </td>';
                                }
                                
                                $html .= '
                                <td style="border: 0,5px solid black; text-align: justify">  
                                    <br>'.$servicio[$i]['tipo_servicio'].'
                                </td>
                                <td style="border: 0,5px solid black; text-align: center"> 
                                    <br>'.$servicio[$i]['cantidad'].'
                                </td>
                            </tr>';
    
                        }
    
                        $html .='</table><br>';
    
                        $html .= '<table style=" width: 100%;  padding: 1px;" >
                                <tr style="font-size: 65%">
                                    <td style="width:17%">
                                        <br><strong>Resumen Clínico:</strong>
                                    </td>
                                    <td style="width:82%;">
                                        <br>'.$servicio[0]['observaciones_generales'].'
                                    </td>
                                    
                                </tr>
                         </table>';
    
                    }
    
                    
    
            // output the HTML content
                    $tcpdf->writeHTML($html, true, false, true, false, '');

                    if($isArray){

                        if(!file_exists(WWW_ROOT."/history_pharmacy/".$initialsUser)){

                            mkdir(WWW_ROOT."/history_pharmacy/".$initialsUser, 0777);

                        }                    

                        $tcpdf->Output(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/'.$order['order_consec'].'LaborClinico_'.$laboId.'.pdf', 'F');

                    }else{

                    if( $imprimir ){
                        $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'LaborClinico.pdf', 'FI');
                    }
                    else{
                        $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'LaborClinico.pdf', 'F');
                    }

        }
                
 }

}


public function printProceso($userId = "", $id = null, $validate = true)
{
    $data = $this->request->data;

    $id = empty($data['id']) ? $id : $data['id'];

    $userId = $this->Auth->user('id');
    

    $this->loadModel('MedicalRecord');

    $servicesId = $this->MedicalRecord->getServicesByMedicalRecord( $id , false);

    $initialsUser = $this->getNameUserConnect( $userId );

    if($validate){

        $this->deleteFiles($initialsUser);

    }


    foreach ($servicesId as $key => $value) {

        $this->printProceClinico($userId, $id, true, true ,$value['medical_services_id'],$initialsUser,$value['interno']);

    }

    if($validate){

        $success = $this->unirPDFs(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/','procedimiento');

        $name = 'procedimiento';

        echo json_encode(['success'=>$success,'initialsUser' => $initialsUser,'name'=> $name ]);
        exit();

    }
    

    
}


    /**
    * Funcion para generar el documento de procedimientos clinicos
    * @author Giovanny Marin <desarrollo@gatolocostudios.com>
    * @date     2017-08-16
    * @datetime 2017-08-16T12:00:02-0500
    * @return   [type]                   [description]
    */
    public function printProceClinico($userId = "", $id = null, $imprimir = true, $isArray = false, $serviceId = null,  $initialsUser = null, $interno = 0 )
    {

        $data = $this->request->data;

        if( empty( $order['users_id'] ) ){

            $order['users_id'] = $userId;

        }


        if(  empty( $id )){
            
            $id = $data['medialRecord'];

            $serviceId = $data['serviceId'];

            
            
        }

        $intern = empty($data['intern']) ? $interno : $data['intern'];

        $order = $this->getOrderAndSpecialist($id);

        $userId = ( empty( $userId )? $order['users_id']: $userId );

        $initialsUser = $this->getNameUserConnect( $userId );

        $this->loadModel('MedicalRecord');

        // Comentado debido a que no existe tal metodo
        // Yeison osorio 2018/mar/15
        // $serviceId = $this->MedicalRecord->getServicesByMedicalRecord($id, false);// Obtiene el id del registro del procedimiento
        
        
        $info = $this->getPatientInforByRecordId($id);
        
        $diagnostico = $this->getDiagnostics($id);
        
        $servicio = $this->getOrderServices($id, $serviceId, $intern);

        

        if($servicio != null){

            $print = new ControlPrintHistoryController();
            
            $print->addControlPrintHistory($servicio[0]['id'], 5 , $order['users_id']);
    
            $userId = ( empty( $userId )? $order['users_id'] : $userId );
    
            $initialsUser = $this->getNameUserConnect( $userId  );
    
            
    
            /*
             *Se obtiene la URL de la firma del espcialista
             */
             $specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
                    
            
            
                $query = "SELECT 
                  authorize_medical_record.*, specialists.id AS specialist_id
                FROM
                    authorize_medical_record
                        left  JOIN
                    specialists ON specialists.users_id = authorize_medical_record.users_id_authorize
                WHERE
                    authorize_medical_record.medical_record_id  = '". $id."'
                    and  authorize_medical_record.state = 1 ";


                $connection = ConnectionManager::get('default');

                $specialistResult       = $connection->execute( $query )->fetchAll('assoc');

                $specialistSupervisor =   $this->getFirmSpecialist($specialistResult[0]['specialist_id']);
    
            $this->autoRender = false;
            // Instanciacion
            $tcpdf = new XTCPDF(); 
    
            // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');
    
            // Info del documento
            $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 
    
            $longitud = 4;
    
            $consecutivo = str_pad($servicio[0]['concec'], $longitud, '0', STR_PAD_LEFT);
    
            // Informacion del ecabezado y footer
            $tcpdf->xheadertext = '
                <table style="padding: 2px; width: 100%" border="0">
                    <tr style="margin:2px; ">
                        <td style="width: 15%">
                            <br><img style=" height:70;" src="img/logo_CDC.png">
                        </td>
                        <td style="text-align:center; width: 60%; LINE-HEIGHT:11px; font-family: Verdana !important">
                            <br>
                            <strong style="color: #0d72b3; font-size: 80%">CDC Centro de Diagnóstico Clínico S.A.S.</strong><br>
                            <strong style="color: #0d72b3; font-size: 60%">NIT: 900.220.827-2</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Calle 17N # 11-70 Piso 4. Telefax: (6) 748 5515</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Cel: 314 680 1257 - www.cdclaboratorio.com</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Armenia - Quindío - Colombia</strong>
                        </td>
                        <td style="width:25%; text-align: right; font-size: 80%">
                            <br><br>
                            <strong>Orden de Servicio Nº: </strong> 
                            <br><strong>'.$consecutivo.' </strong> 
                        </td>
                    </tr>
                </table>

                <table style="border: 0,5px solid black; width: 100%;  padding: 1px;">
                    <tr style="font-size: 60%">
                        <td style="width:18%; "> <br>
                            <strong>Nro. Orden: </strong><br>
                            <strong>Paciente: </strong><br>
                            <strong>Teléfono: </strong><br>
                            <strong>Cliente: </strong><br>
                            <strong>Correo Electrónico: </strong><br>
                            <strong>Médico: </strong>
                        </td>
                        <td style="width:44%; ">  
                            '.$info['numero_orden'].'<br>
                            '.$info['paciente'].'<br>
                            '.$info['telefono'].'<br>
                            '.$info['cliente_'].'<br>
                            '.$info['correo'].'<br>
                            '.$info['especialista'].'
                        </td>
                        <td style="width:16%; "> <br> 
                            <strong>Nro. Documento: </strong><br>
                            <strong>Edad: </strong><br>
                            <strong>Sexo: </strong><br>
                            <strong>Regimen: </strong><br>
                            <strong>Fecha de Atención:</strong><br>
                            <strong>Sede de Atención:</strong>
                        </td>
                        <td style="width:22%; ">
                            '.$info['identificacion'].'<br>
                            '.$info['edad'].'<br>
                            '.$info['genero'].'<br>
                            '.$info['regimen'].'<br>
                            '.$info['fecha_atencion'].'<br>
                            '.$info['sede'].'
                        </td>
                    </tr>
                </table>
            '; 
            
            
            /*$signatures = '
                <td style="width: 20%">
                    <br><img style=" height:70;" src="img/logo_CDC.png">
                </td>
                <!--<td style="text-align: right; width:20%;"> 
                    <br><img style=" height:30;" src="'.$specialistData['url'].'"  >
                    <br><img style=" height:35;" src="img/logo_CDC.png">
                </td>-->
                <td style="width:60%; font-size:7px !important; width:80%;"> 
                    <br><b><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                    <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                    <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                </td>
            ';*/
            
            /*if( empty($specialistSupervisor))
            {
                     
                $signatures = '
                    <!--<table style="padding: 0.2px; width: 100%" border="0">
                        <tr> -->
                            <td style="text-align: right; width:20%;"> 
                                <!--<br><img style=" height:30;" src="'.$specialistData['url'].'"  >-->
                                <br><img style=" height:35;" src="img/logo_CDC.png">
                            </td>
                            <td style="width:60%; font-size:7px !important; width:80%;"> 
                                <br><b><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                                <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                                <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                            </td>
                        <!--</tr>
                    </table>-->
                ';

            }else{
                
       
                $signatures = '
                <table style="padding: 0.2px; width: 100%" border="0">
                    <tr> 
                        <td style="text-align: right; width:20%;"> 
                            <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                        </td>
                        <td style="width:30%; font-size:7px !important; "> 
                        <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                            <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                            <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                        </td>
                          <td style="text-align: right; width:20%;"> 
                            <img  src="'.$specialistSupervisor['url'].'" style="width:50px !important; height:30px !important;" >
                        </td>
                        <td style="width:30%; font-size:7px !important; "> 
                        <b> <span style="font-size:7px;">'.$specialistSupervisor['nombre'].' </span> <br>
                            <span style="font-size:7px;">'.$specialistSupervisor['especialidad'].'</span> <br>
                            <span style="font-size:7px;">'.$specialistSupervisor['tarjeta'].'</span></b>
                        </td>
                    </tr>
                </table>';                    
            }*/
            
            // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
            $tcpdf->xfootertext = '
                <table style="padding: 2px; width: 100%" border="0">
                    <tr style="font-size: 7px;">   
                        <td style=" text-align: center; font-size:7px; width:30%">
                            <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                        </td>
                        <td style="width: 10%">
                            <img style=" height:50 important;" src="'.$specialistData['url'].'" >
                        </td>
                        <td style="width:60%; font-size:7px !important; width:80%;"> 
                            <br><b><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                            <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                            <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                        </td>
                    </tr>
                </table>
            ';
            
            /*$tcpdf->xfootertext =$signatures . '
            
            <table style="padding: 2px; width: 100%" border="0">
                <tr style="font-size: 7px;">   
                    <td style=" text-align: center; font-size:7px; width:20%">
                        <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                    </td>
                </tr>
            </table>
            ';*/
    
            // Fuentes del doc
            $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
            //$tcpdf->SetFont('Verdana', '', 10);
            // Margenes 
            // $tcpdf->SetMargins(10, 63, 3, false);
    
    
            /**
             * inicio de contenido
             */
            $tcpdf->SetMargins(10, 52, 15, false);
    
            $tcpdf->SetHeaderMargin(10);
            $tcpdf->SetFooterMargin(15);
            
            // Cambio de pagina
            $tcpdf->SetAutoPageBreak(true, 20); 
            
    
            // Validacion para la aparicion tanto del header como del footer
            $tcpdf->SetPrintHeader(true);
            $tcpdf->SetPrintFooter(true);
    

            $resolution= array(216, 139);
            $tcpdf->AddPage('L', $resolution);
            
            setlocale(LC_MONETARY, 'en_US');
            
            //$tcpdf->SetFont('freesans', '', 12);
    
            // contenido del resultado

            $html = '';
    
            if(!empty($diagnostico)){
    
                $html  = '
                <table style="width: 100%;  padding: 1px;">
                    <tr style="font-size: 60%">
                        <td style="width:100%; text-align:center">
                            <br><strong style="font-family:Verdana; font-size: 115%; color: #2B1C82; text-align:center">Diagnóstico(s):</strong>
                        </td>
                    </tr>
                    <tr style="font-size: 60%">
                        <td style="width:10%; border: 0,2px solid black; text-align: center">
                            <br><strong>CODIGO:</strong>
                        </td>
                        <td style="width:70%; border: 0,2px solid black;"> 
                            <br><strong>NOMBRE:</strong>
                        </td>
                        <td style="width:20%; border: 0,2px solid black;"> 
                            <br><strong>TIPO:</strong>
                        </td>
                    </tr>';
    
                    for ($i=0; $i < count($diagnostico); $i++) { 
    
                       $html.= '
                    <!-- Inicio de recorrido para diagnóstico -->
                        <tr style="font-size: 50%">
                            <td style="text-align: center; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['cie10'].'
                            </td>
                            <td style="text-align: justify; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['description'].'
                            </td>
                            <td style="text-align: center; border: 0,2px solid black;" >
                                <br>'.$diagnostico[$i]['diagnosticType'].'
                            </td>
                        </tr>';
                        
    
                    }
    
                $html .= ' </table>';
    
    
            }
    
            
            if(!empty($servicio)){

                $html .= '
                <table style="width: 100%;  padding: 1px; margin-top: 10px">
                    <tr style="font-size: 60%">
                        <td style="width:100%; text-align:center; line-height:35%">

                        </td>
                    </tr> 
               
                    <tr style="font-size: 65%">
                        <td style="width:5%; border: 0,5px solid black; text-align: center"> 
                            <br><strong>No.</strong>
                        </td>
                        <td style="width:7%; border: 0,5px solid black; text-align: center">  
                            <br><strong>CUPS</strong>
                        </td>';

                        $aux = false;
                        
                        for ($i=0; $i < count($servicio) ; $i++) { 

                            if(!empty($servicio[$i]['mipres'])){                                

                                $html .='
                                <td style="width:49%; border: 0,5px solid black; text-align: center">  
                                    <br><strong>SERVICIO</strong>
                                </td>
                                <td style="width:14%; border: 0,5px solid black; text-align: center"> 
                                    <br><strong>MIPRES</strong>
                                </td>';

                                $aux = true;

                                break;

                            }

                        }

                        if(!$aux){

                            $html .='
                            <td style="width:63%; border: 0,5px solid black; text-align: center">  
                                <br><strong>SERVICIO</strong>
                            </td>';

                        }

                        $html .='<td style="width:16%; border: 0,5px solid black; text-align: center">  
                            <br><strong>TIPO</strong>
                        </td>
                        <td style="width:9%; border: 0,5px solid black; text-align: center">  
                            <br><strong>CANTIDAD</strong>
                        </td>
                    </tr>';

                    for ($i=0; $i < count($servicio); $i++) { 

                        $html .= '
                        <tr style="font-size: 65%">
                            <td style="border: 0,5px solid black; text-align: center"> 
                                <br>'.($i +1).'
                            </td>
                            <td style="border: 0,5px solid black; text-align: center"> 
                                <br>'.$servicio[$i]['cups'].'
                            </td>';

                            if($aux){
                                
                                $mipres = empty($servicio[$i]['mipres']) ? "" : $servicio[$i]['mipres'];

                                $html .= '
                                    <td style="border: 0,5px solid black; text-align: justify"> 
                                        <br>'.$servicio[$i]['nombre'].'
                                    </td>
                                    <td style="border: 0,5px solid black; text-align: center"> 
                                        <br>'.$mipres .'
                                    </td>';

                            }else{

                                $html .= '
                                <td style="border: 0,5px solid black; text-align: justify"> 
                                    <br>'.$servicio[$i]['nombre'].'
                                </td>';
                            }

                            $html .= '<td style="border: 0,5px solid black; text-align: justify">  
                                <br>'.$servicio[$i]['tipo_servicio'].'
                            </td>
                            <td style="border: 0,5px solid black; text-align: center"> 
                                <br>'.$servicio[$i]['cantidad'].'
                            </td>
                        </tr>';

                    }
                    
                    $html .='</table><br>';

                    $html .= '<table style=" width: 100%;  padding: 1px;" >
                            <tr style="font-size: 65%">
                                <td style="width:17%">
                                    <br><strong>Resumen Clínico:</strong>
                                </td>
                                <td style="width:82%;">
                                    <br>'.$servicio[0]['observaciones_generales'].'
                                </td>
                                
                            </tr>
                        </table>';

            }

            // output the HTML content
            $tcpdf->writeHTML($html, true, false, true, false, '');

            if($isArray){

                if(!file_exists(WWW_ROOT."/history_pharmacy/".$initialsUser)){

                    mkdir(WWW_ROOT."/history_pharmacy/".$initialsUser, 0777);

                }                    

                $tcpdf->Output(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/'.$order['order_consec'].'ProcedimientoClinico_'.$serviceId.'.pdf', 'F');

            }else{
                    
            if( $imprimir ){
                $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'ProceClinico.pdf', 'FI');
            }
            else{
                $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'ProceClinico.pdf', 'F');
            }

        }

           
    }

           

}


public function printDisabilityRecord($userId = "", $id = null, $validate = true)
{
    $data = $this->request->data;

    $id = empty($data['id']) ? $id : $data['id'];

    $userId = $this->Auth->user('id');

    $initialsUser = $this->getNameUserConnect( $userId );

    if($validate){

        $this->deleteFiles($initialsUser);

    }

    $this->printDisability($userId, $id, true);

        if($validate){

            $success = $this->unirPDFs(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/','disability');

            $name = 'disability';
        
            echo json_encode(['success'=>$success,'initialsUser' => $initialsUser,'name'=> $name ]);
            exit();
        

        }
   

    }


    /**
    * Funcion para generar el documento formulas médicas
    * @author Giovanny Marin <desarrollo@gatolocostudios.com>
    * @date     2017-08-16
    * @datetime 2017-08-16T12:00:02-0500
    * @return   [type]                   [description]
    */
    public function printDisability($userId = "",$id = null, $isArray = false)
    {
        $data = $this->request->data;

        if( empty( $id ) ){
            
            $id = $data['medialRecord'];

        }

        $order = $this->getOrderAndSpecialist($id);
        if( empty( $order['users_id'] ) ){
            $order['users_id'] = $userId;
        }


        $info = $this->getPatientInforByRecordId($id);

        $diagnostico = $this->getDiagnostics($id);

        $disability = $this->getDisability($id);

        if($disability != null){

        $print = new ControlPrintHistoryController();
        
        $print->addControlPrintHistory($disability['id'], 4 , $order['users_id']);

        $userId = ( empty( $userId )? $order['users_id'] : $userId );

        $initialsUser = $this->getNameUserConnect( $userId  );

        /*
         *Se obtiene la URL de la firma del espcialista
         */
        //$specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
        
          /*
         *Se obtiene la URL de la firma del espcialista
         */
        $specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
        
         $query = "SELECT 
                  authorize_medical_record.*, specialists.id AS specialist_id
                FROM
                    authorize_medical_record
                        left  JOIN
                    specialists ON specialists.users_id = authorize_medical_record.users_id_authorize
                WHERE
                    authorize_medical_record.medical_record_id  = '". $id."'
                    and  authorize_medical_record.state = 1 ";

        
        $connection = ConnectionManager::get('default');

        $specialistResult       = $connection->execute( $query )->fetchAll('assoc');

        $specialistSupervisor =   $this->getFirmSpecialist($specialistResult[0]['specialist_id']);


        $this->autoRender = false;
        // Instanciacion
        $tcpdf = new XTCPDF(); 

        // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');

        // Info del documento
        $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 

        // <b style="left:2em">  Fecha: '.date('Y-m-d').' '.date('H:i:s').'  Página'.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</b>
        $longitud = 7;

        $consecutivo = str_pad($disability['consec'], $longitud, '0', STR_PAD_LEFT);
        // Informacion del ecabezado y footer
        $tcpdf->xheadertext = '

            <table style="padding: 2px; width: 100%" border="0">
                <tr style="margin:2px; ">
                    <td style="width: 15%">
                        <br><img style=" height:70;" src="img/logo_CDC.png">
                    </td>
                    <td style="text-align:center; width: 60%; LINE-HEIGHT:11px; font-family: Verdana !important">
                        <br>
                        <strong style="color: #0d72b3; font-size: 80%">CDC Centro de Diagnóstico Clínico S.A.S.</strong><br>
                        <strong style="color: #0d72b3; font-size: 60%">NIT: 900.220.827-2</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Calle 17N # 11-70 Piso 4. Telefax: (6) 748 5515</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Cel: 314 680 1257 - www.cdclaboratorio.com</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Armenia - Quindío - Colombia</strong>
                    </td>
                    <td style="width:25%; text-align: right; font-size: 80%">
                        <br><br><strong>Incapacidad Nº: </strong> 
                        <br><strong>'.$consecutivo.' </strong> 
                    </td>
                </tr>
            </table>


            <table style="border: 0,5px solid black; width: 100%;  padding: 1px;">
                <tr style="font-size: 60%">
                    <td style="width:18%; "> <br>
                        <strong>Nro. Orden: </strong><br>
                        <strong>Paciente: </strong><br>
                        <strong>Teléfono: </strong><br>
                        <strong>Entidad: </strong><br>
                        <strong>Correo Electrónico: </strong><br>
                        <strong>Médico: </strong>
                    </td>
                    <td style="width:44%; ">  
                        '.$info['numero_orden'].'<br>
                        '.$info['paciente'].'<br>
                        '.$info['telefono'].'<br>
                        '.$info['cliente_'].'<br>
                        '.$info['correo'].'<br>
                        '.$info['especialista'].'
                    </td>
                    <td style="width:16%; "> <br> 
                        <strong>Nro. Documento: </strong><br>
                        <strong>Edad: </strong><br>
                        <strong>Sexo: </strong><br>
                        <strong>Regimen: </strong><br>
                        <strong>Fecha de Atención:</strong><br>
                        <strong>Sede de Atención:</strong>
                    </td>
                    <td style="width:22%; ">
                        '.$info['initials'].' '.$info['identificacion'].'<br>
                        '.$info['edad'].'<br>
                        '.$info['genero'].'<br>
                        '.$info['regimen'].'<br>
                        '.$info['fecha_atencion'].'<br>
                        '.$info['sede'].'
                    </td>
                </tr>
            </table>
        '; 
        
        
        /*if( empty($specialistSupervisor)){
                     
                        $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:80%; font-size:7px !important; width:80%;"> 
                                <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span><br>
                                    <span style="font-size:7px;">'.$specialistData['iniciales'].' '.$specialistData['identificacion'].'</span></b>
                                </td>
                            </tr>
                        </table>';

                }else{
          
                
                            $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:30%; font-size:7px !important; "> 
                                <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['iniciales'].' '.$specialistData['identificacion'].'</span></b>
                                </td>
                                  <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistSupervisor['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:30%; font-size:7px !important; "> 
                                <b> <span style="font-size:7px;">'.$specialistSupervisor['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['tarjeta'].'</span><br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['iniciales'].' '.$specialistSupervisor['identificacion'].'</span></b>

                                </td>
                            </tr>
                        </table>';

                    
                }*/
        
        

        // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
        $tcpdf->xfootertext = '
            <table style="padding: 2px; width: 100%" border="0">
                <tr style="font-size: 7px;">   
                    <td style=" text-align: center; font-size:7px; width:30%">
                        <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                    </td>
                    <td style="width: 10%">
                        <img style=" height:50 important;" src="'.$specialistData['url'].'" >
                    </td>
                    <td style="width:60%; font-size:7px !important; width:80%;"> 
                        <b><br><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                        <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                        <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span>
                        <br><span style="font-size:7px;">'.$specialistData['iniciales'].' '.$specialistData['identificacion'].'</span></b>
                    </td>
                </tr>
            </table>
        ';

        // Fuentes del doc
        $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
        //$tcpdf->SetFont('Verdana', '', 10);
        // Margenes 
        // $tcpdf->SetMargins(10, 63, 3, false);


        /**
         * inicio de contenido
         */
        $tcpdf->SetMargins(15, 53, 15, false);

        $tcpdf->SetHeaderMargin(10);
        $tcpdf->SetFooterMargin(75);
        
        // Cambio de pagina
        $tcpdf->SetAutoPageBreak(true, 20); 
        
        //$tcpdf->setHeaderFont(array($textfont,'',40)); 
        //$tcpdf->xheadercolor = array(150,0,0); 

        // Validacion para la aparicion tanto del header como del footer
        $tcpdf->SetPrintHeader(true);
        $tcpdf->SetPrintFooter(true);

        // Adicion de nueva pagina con tamaño predefinido en mm
        //$resolution= array(216, 279);
        //$tcpdf->AddPage('P', $resolution);
        $resolution= array(216, 139);
        $tcpdf->AddPage('L', $resolution);
        
        setlocale(LC_MONETARY, 'en_US');
        
        //$tcpdf->SetFont('freesans', '', 12);

        // contenido del resultado
        $html="";
        if(!empty($diagnostico)){

            $html  = '
            <table style="width: 100%;  padding: 1px;">
                <tr style="font-size: 60%">
                    <td style="width:100%; text-align:center">
                        <br><strong style="font-family:Verdana; font-size: 115%; color: #2B1C82; text-align:center">Diagnóstico(s):</strong>
                    </td>
                </tr>
                <tr style="font-size: 60%">
                    <td style="width:10%; border: 0,2px solid black; text-align: center">
                        <br><strong>CODIGO:</strong>
                    </td>
                    <td style="width:70%; border: 0,2px solid black;"> 
                        <br><strong>NOMBRE:</strong>
                    </td>
                    <td style="width:20%; border: 0,2px solid black;"> 
                        <br><strong>TIPO:</strong>
                    </td>
                </tr>';

                for ($i=0; $i < count($diagnostico); $i++) { 

                     $html.= '
                    <!-- Inicio de recorrido para diagnóstico -->
                        <tr style="font-size: 50%">
                            <td style="text-align: center; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['cie10'].'
                            </td>
                            <td style="text-align: justify; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['description'].'
                            </td>
                            <td style="text-align: center; border: 0,2px solid black;" >
                                <br>'.$diagnostico[$i]['diagnosticType'].'
                            </td>
                        </tr>';
                        
                }

                $html .= ' </table>';
            }

            if(!empty($disability)){

                $html .='

                <table style="width: 100%;  padding: 1px;" >
                    <tr style="font-size: 60%">
                        <td style="width:100%; text-align:center; line-height:35%">
                        </td>
                    </tr> 
                  
                </table>';

                $html .= '<table style="border: 0,5px solid black; width: 100%;  padding: 1px;" >
                <tr style="font-size: 65%">
                    <td style="width:17%" >
                        <br><strong>FECHA INICIO:</strong>
                    </td>
                    <td style="width:16%">
                        <br>'.$disability['date_time_ini'].'
                    </td>
                    <td style="width:15%"> 
                        <br><strong>FECHA FINALIZA:</strong>
                    </td>
                    <td style="width:17%"> 
                        <br>'.$disability['date_time_end'].'
                    </td>
                    <td style="width:16%"> 
                        <br><strong>CANTIDAD DÍAS:</strong>
                    </td>
                    <td style="width:19%"> 
                        <br>'.$disability['number_days'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width:17%;">
                        <br><strong>TIPO INCAPACIDAD:</strong>
                    </td>
                    <td style="width:83%">
                        <br>'.$disability['name'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width:17%">
                        <br><strong>CONCEPTO:</strong>
                    </td>
                    <td style="width:83%;">
                        <br>'.$disability['medical_concept'].'
                    </td>
                    
                </tr>
                <tr style="font-size: 65%">
                    <td style="width:17%">
                        <br><strong>Observaciones:</strong>
                    </td>
                    <td style="width:83%;">
                        <br>'.$disability['observations'].'
                    </td>
                    
                </tr>


            </table><br><br>';

        }
        
        
       

                    // for ($i = 0; $i < count($prescription); $i++) { 

                    //     $html .= '

                    //     <tr style="font-size: 65%">
                    //         <td style="border: 0,5px solid black; text-align: center">
                    //             <br>'.($i+1).'
                    //         </td>
                    //         <td style="border: 0,5px solid black; text-align: justify"> 
                    //             <br>'.$prescription[$i]['principio_activo'].' '.$prescription[$i]['concentracion'].'
                    //             <br><small>'.$prescription[$i]['posology'].'</small>
                    //         </td>
                    //         <td style="border: 0,5px solid black; font-size: 6px; text-align: center"> 
                    //             <br>'.$prescription[$i]['precentacion'].'
                    //         </td>
                    //         <td style="border: 0,5px solid black; text-align: center">
                    //             <br>'.$prescription[$i]['cantidad'].'
                    //         </td>
                    //     </tr>
                    //     ';


                    // }

                //    $html .= '</table>';




                // output the HTML content
        $tcpdf->writeHTML($html, true, false, true, false, '');

        if($isArray){

            if(!file_exists(WWW_ROOT."/history_pharmacy/".$initialsUser)){

                mkdir(WWW_ROOT."/history_pharmacy/".$initialsUser, 0777);

            }                    

            $tcpdf->Output(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/'.$order['order_consec'].'ProcedimientoClinico_'.$serviceId.'.pdf', 'F');

        }else{

        $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'Incapacidad.pdf', 'FI');

    }

}



}

/**
    * Funcion para generar el documento Recomendaciones
    * @author Yeison osorio
    * @date     2018-05-16
    */
    public function printRecommendation($userId = "",$id = null, $isArray = false){

        Configure::write('debug', 2);
        $this->loadModel('MedicalRecommendation');
        $connection = ConnectionManager::get('default');
        
        $data = $this->request->data;

        if(empty($id)){
            $id = $data['medialRecord'];
            $recommendationId = $data['recommendationId'];
        }

        $order = $this->getOrderAndSpecialist($id);
        if( empty( $order['users_id'] ) ){
            $order['users_id'] = $userId;
        }

        $info = $this->getPatientInforByRecordId($id);

        $diagnostico = $this->getDiagnostics($id);

        $recomendacion = $connection->execute("SELECT id, recommendation
        FROM medical_recommendation m
        WHERE m.id = '$recommendationId'
        ")->fetchAll('assoc');

        $textReco = $recomendacion[0]['recommendation'];

        $userId = ( empty( $userId )? $order['users_id'] : $userId );

        $initialsUser = $this->getNameUserConnect( $userId  );

        /*
         *Se obtiene la URL de la firma del espcialista
         */
        //$specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
        
          /*
         *Se obtiene la URL de la firma del espcialista
         */
        $specialistData = $this->getFirmSpecialist($order['specialists_id']['id']);
        
         $query = "SELECT 
                  authorize_medical_record.*, specialists.id AS specialist_id
                FROM
                    authorize_medical_record
                        left  JOIN
                    specialists ON specialists.users_id = authorize_medical_record.users_id_authorize
                WHERE
                    authorize_medical_record.medical_record_id  = '". $id."'
                    and  authorize_medical_record.state = 1 ";

        
        $specialistResult       = $connection->execute( $query )->fetchAll('assoc');

        $specialistSupervisor =   $this->getFirmSpecialist($specialistResult[0]['specialist_id']);

        $this->autoRender = false;
        // Instanciacion
        $tcpdf = new XTCPDF(); 

        // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');

        // Info del documento
        $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 

        // <b style="left:2em">  Fecha: '.date('Y-m-d').' '.date('H:i:s').'  Página'.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</b>
        $longitud = 7;

        // $consecutivo = str_pad($disability['consec'], $longitud, '0', STR_PAD_LEFT);
        // Informacion del ecabezado y footer
        $tcpdf->xheadertext = '

                <table style="padding: 2px;">
                    <tr>
                        <td style="width: 20%">
                            <br><img style=" height:80;" src="img/logo_CDC.png">
                        </td>
                        <td style="text-align:center; width: 60%; LINE-HEIGHT:11px; font-family: Verdana !important">
                            <br>
                            <br>
                            <strong style="color: #0d72b3; font-size: 80%">CDC Centro de Diagnóstico Clínico S.A.S.</strong><br>
                            <strong style="color: #0d72b3; font-size: 60%">NIT: 900.220.827-2</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Calle 17N # 11-70 Piso 4. Telefax: (6) 748 5515</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Cel: 314 680 1257 - www.cdclaboratorio.com</strong><br>
                            <strong style="color: #56ade0; font-size: 60%">Armenia - Quindío - Colombia</strong>
                        </td>
                        <td style="width:20%; text-align: right; font-size: 80%">
                            
                        </td>
                    </tr>
                </table>

            <table style="border: 0,5px solid black; width: 100%;  padding: 1px;">
                <tr style="font-size: 60%">
                    <td style="width:18%; "> <br>
                        <strong>Nro. Orden: </strong><br>
                        <strong>Paciente: </strong><br>
                        <strong>Teléfono: </strong><br>
                        <strong>Cliente: </strong><br>
                        <strong>Correo Electrónico: </strong><br>
                        <strong>Médico: </strong>
                    </td>
                    <td style="width:44%; ">  
                        '.$info['numero_orden'].'<br>
                        '.$info['paciente'].'<br>
                        '.$info['telefono'].'<br>
                        '.$info['cliente_'].'<br>
                        '.$info['correo'].'<br>
                        '.$info['especialista'].'
                    </td>
                    <td style="width:16%; "> <br> 
                        <strong>Nro. Documento: </strong><br>
                        <strong>Edad: </strong><br>
                        <strong>Sexo: </strong><br>
                        <strong>Regimen: </strong><br>
                        <strong>Fecha de Atención:</strong><br>
                        <strong>Sede de Atención:</strong>
                    </td>
                    <td style="width:22%; ">
                        '.$info['identificacion'].'<br>
                        '.$info['edad'].'<br>
                        '.$info['genero'].'<br>
                        '.$info['regimen'].'<br>
                        '.$info['fecha_atencion'].'<br>
                        '.$info['sede'].'
                    </td>
                </tr>
            </table>
        '; 
        
        
        /*if( empty($specialistSupervisor)){
                     
                        $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:80%; font-size:7px !important; width:80%;"> 
                                <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                                </td>
                            </tr>
                        </table>';

                }else{
          
                
       
                            $signatures = '<table style="padding: 0.2px; width: 100%" border="0">
                            <tr> 
                                <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:30%; font-size:7px !important; "> 
                                <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                                </td>
                                  <td style="text-align: right; width:20%;"> 
                                    <img  src="'.$specialistSupervisor['url'].'" style="width:50px !important; height:30px !important;" >
                                </td>
                                <td style="width:30%; font-size:7px !important; "> 
                                <b> <span style="font-size:7px;">'.$specialistSupervisor['nombre'].' </span> <br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['especialidad'].'</span> <br>
                                    <span style="font-size:7px;">'.$specialistSupervisor['tarjeta'].'</span></b>
                                </td>
                            </tr>
                        </table>';

                    
                }*/
        
        

        // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
        $tcpdf->xfootertext = '
            <table style="padding: 2px; width: 100%" border="0">
                <tr style="font-size: 7px;">   
                    <td style=" text-align: center; font-size:7px; width:30%">
                        <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                    </td>
                    <td style="width: 10%">
                        <img style=" height:50 important;" src="'.$specialistData['url'].'" >
                    </td>
                    <td style="width:60%; font-size:7px !important; width:80%;"> 
                        <b><br><span style="font-size:7px;">'.$specialistData['nombre'].' </span> 
                        <br><span style="font-size:7px;">'.$specialistData['especialidad'].'</span>
                        <br><span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                    </td>
                </tr>
            </table>
        ';

        // Fuentes del doc
        $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 
        //$tcpdf->SetFont('Verdana', '', 10);
        // Margenes 
        // $tcpdf->SetMargins(10, 63, 3, false);


        /**
         * inicio de contenido
         */
        $tcpdf->SetMargins(15, 51, 15, false);

        $tcpdf->SetHeaderMargin(10);
        $tcpdf->SetFooterMargin(75);
        
        // Cambio de pagina
        $tcpdf->SetAutoPageBreak(true, 25); 
        
        //$tcpdf->setHeaderFont(array($textfont,'',40)); 
        //$tcpdf->xheadercolor = array(150,0,0); 

        // Validacion para la aparicion tanto del header como del footer
        $tcpdf->SetPrintHeader(true);
        $tcpdf->SetPrintFooter(true);

        // Adicion de nueva pagina con tamaño predefinido en mm
        //$resolution= array(216, 279);
        //$tcpdf->AddPage('P', $resolution);
        $resolution= array(216, 139);
        $tcpdf->AddPage('L', $resolution);
        
        setlocale(LC_MONETARY, 'en_US');
        
        //$tcpdf->SetFont('freesans', '', 12);

        // contenido del resultado
        $html="";
        if(!empty($diagnostico)){

            $html  = '
            <table style="width: 100%;  padding: 1px;">
                <tr style="font-size: 60%">
                    <td style="width:100%; text-align:center">
                        <br><strong style="font-family:Verdana; font-size: 115%; color: #2B1C82; text-align:center">Diagnóstico(s):</strong>
                    </td>
                </tr>
                <tr style="font-size: 60%">
                    <td style="width:10%; border: 0,2px solid black; text-align: center">
                        <br><strong>CODIGO:</strong>
                    </td>
                    <td style="width:70%; border: 0,2px solid black;"> 
                        <br><strong>NOMBRE:</strong>
                    </td>
                    <td style="width:20%; border: 0,2px solid black;"> 
                        <br><strong>TIPO:</strong>
                    </td>
                </tr>';

                for ($i=0; $i < count($diagnostico); $i++) { 

                     $html.= '
                    <!-- Inicio de recorrido para diagnóstico -->
                        <tr style="font-size: 50%">
                            <td style="text-align: center; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['cie10'].'
                            </td>
                            <td style="text-align: justify; border:  0,2px solid black;" >
                                <br>'.$diagnostico[$i]['description'].'
                            </td>
                            <td style="text-align: center; border: 0,2px solid black;" >
                                <br>'.$diagnostico[$i]['diagnosticType'].'
                            </td>
                        </tr>';
                        
                }

                $html .= ' </table>';
            }

        $html.= '<table style="border: 0,5px solid black; width: 100%;  padding: 1px;" >
            <tr style="font-size: 65%">
                <td style="width:100%; text-align: justify" >
                    <br><strong>Recomendación:</strong>
                    <br><strong>'.nl2br($textReco).'</strong>
                </td>
            </tr>
        </table>';
        
        // output the HTML content
        $tcpdf->writeHTML($html, true, false, true, false, '');

        if($isArray){

            if(!file_exists(WWW_ROOT."/history_pharmacy/".$initialsUser)){

                mkdir(WWW_ROOT."/history_pharmacy/".$initialsUser, 0777);

            }                    

            $tcpdf->Output(WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/'.$order['order_consec'].'ProcedimientoClinico_'.$serviceId.'.pdf', 'F');

        }else{

            $tcpdf->Output(WWW_ROOT.'history/'.$order['order_consec'].'Recomendacion.pdf', 'FI');

        }

}

    /**
     * [getAntecedents obtiene los antecedentes]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-09-21
     * @datetime 2017-09-21T08:19:45-0500
     * @param    string                   $medical_record [description]
     * @return   [type]                                   [description]
     */
    public function getAntecedents($id_medical='')
    {
        
         $connection = ConnectionManager::get('default');

        if(!empty($id_medical)){

            $query = "
            SELECT 
            medical_record_antecedents.id,
            medical_record_antecedents.observation,
            medical_antecedents.name,
            medical_antecedents.medical_antecedents_types_id typeAntecedente
            FROM
            medical_record_antecedents
            JOIN
            medical_antecedents ON medical_record_antecedents.medical_antecedents_id = medical_antecedents.id
            WHERE
            medical_record_antecedents.medical_record_id = ".$id_medical."
            ORDER BY medical_antecedents.id ASC; ";

            $getAntecedents = $connection->execute($query)->fetchAll('assoc');

            if($getAntecedents[0]['id'] != null){

               return $getAntecedents;

           }else{

            return null;
        }

    }

    }


    /**
     * [countValues Cuenta cantidad de cada tipo de antecedentes]
     * @author Jefry Londoño <jjmb2789@gmail.com>
     * @date     2017-09-21
     * @datetime 2017-09-21T10:08:40-0500
     * @param    [type]                   $array [description]
     * @return   [type]                          [description]
     */
    public function countValues($array)
    {
        $totalPerson = 0;

        $totalFamily = 0;

        for ($i=0; $i < count($array); $i++) { 
            
            if($array[$i]['typeAntecedente'] == 1){

                $totalPerson ++;

            }

            if($array[$i]['typeAntecedente'] == 2){

                $totalFamily ++;

            }

        }

        $newArray['typePerson'] = $totalPerson;

        $newArray['typeFamily'] = $totalFamily;

        return $newArray;
    }

        
   
    
    public function printConceptoMedico($userId = "",$medical_id = null, $imprimir = true, $initialsUser = null, $identification = null, $datos, $info, $firm)
    {
        // Instanciacion
        $tcpdf = new XTCPDF(); 

        //print_r(  $datos);
        //print_r(  $info);

        // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');

        //exit();
        
        // Info del documento
        $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 
        
                        //<br><img style=" height:42px;" src="img/logo_londono.jpg">
        // Informacion del ecabezado y footer
        $tcpdf->xheadertext = '
            <table style="padding: 2px; width: 95%">
                <tr>
                    <td style="width: 20%">
                        <br><img style=" height:80;" src="img/logo_CDC.png">
                    </td>
                    <td style="text-align:center; width: 60%; LINE-HEIGHT:11px; font-family: Verdana !important">
                        <br>
                        <br>
                        <strong style="color: #0d72b3; font-size: 80%">CDC Centro de Diagnóstico Clínico S.A.S.</strong><br>
                        <strong style="color: #0d72b3; font-size: 60%">NIT: 900.220.827-2</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Calle 17N # 11-70 Piso 4 - Telefax: (6) 748 5515 - Cel: 314 680 1257</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">e-mail: servicioalcliente@cdclaboratorio.com - www.cdclaboratorio.com</strong><br>
                        <strong style="color: #56ade0; font-size: 60%">Armenia - Quindío - Colombia</strong><br>
                    </td>
                    <td style="width:18%; text-align: right; font-size: 80%">
                        <br><br><br><strong>Orden Nro.</strong><br>'.$info['numero_orden'].'
                    </td>
                    <td style="width:2%; text-align: right; font-size: 80%">
                        <br>
                    </td>
                </tr>
            </table>
        '; 

        // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
        $tcpdf->xfootertext = '<small style"font-size: 6px !important; ">Pág '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'.</small>';
        
        $tcpdf->SetMargins(10, 30, 3, false);

        // Margenes 
        $tcpdf->SetMargins(10, 35, 3, false);
        $tcpdf->SetHeaderMargin(10);
        $tcpdf->SetFooterMargin(10);

        // Cambio de pagina
        $tcpdf->SetAutoPageBreak( true, 10); 

        // Validacion para la aparicion tanto del header como del footer
        $tcpdf->SetPrintHeader(true);
        $tcpdf->SetPrintFooter(true);

        // Adicion de nueva pagina con tamaño predefinido en mm
        $resolution= array(216, 279);
        $tcpdf->AddPage('P', $resolution);
        //$resolution= array(216, 139);
        //$tcpdf->AddPage('L', $resolution);
        
        setlocale(LC_MONETARY, 'en_US');
        
        // Descomposicion la edad para asignación de años, meses o días de edad para el paciente
        $edad = explode('.', $info['edad']);

        
        // Validacion para opciones de rangos de edad
        switch($edad[0])
        {
            case '0':
                if ( $edad[1] == '0' ) 
                {
                    $nroEdad = $edad[2].' días';
                }
                else
                {
                    $nroEdad = $edad[1].' meses';
                }

                break;

            case '1':
                $nroEdad = $edad[0].' año';
                break;

            default:
                $nroEdad = $edad[0].' años';
                break;
        }
        
        //  print_r($info);
        //   exit();
        
        $html = '
            <br><strong style="color: #0d72b3; text-align: center; font-size: 80%">CONCEPTO DE APTITUD LABORAL</strong><br>


            <!-- Información del Paciente -->
            <table style="padding: 2px; width:95%">
                <tr style="font-size: 65%">
                    <td style="width: 50%; text-align: justify;">
                        <br><strong>Ciudad:</strong> Armenia - Quindío
                    </td>

                    <td style="width: 50%; text-align: justify;">
                        <br><strong>Cliente:</strong> '.$info['cliente_'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 50%; text-align: justify;">
                        <br><strong>Fecha de Ingreso a la Empresa:</strong> '.$datos['field_fech_ingreso'][3].'
                    </td>
                    <td style="width: 50%; text-align: justify;">
                        <br><strong>Empresa Contratante:</strong> '.$datos['field_nomb_empresa'][0].'
                    </td>

                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 50%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br><strong>Fecha de Realización:</strong> '.$info['fecha_atencion'].'
                    </td>
                    <td style="width: 50%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br><strong>Empresa en Misión:</strong> '.$datos['field_mision_en'][8].'
                    </td>
                </tr>
                <tr style="font-size: 40%">
                    <td style="width: 100%; text-align: justify;">
                        <br>
                    </td>
                </tr>
            </table>';

        $rutaImagen = '';
        
        if ( isset($info['path_photo']) && $info['path_photo'] != '' )
        {
            $rutaImagen = 'src="'.WWW_ROOT.'/resources/people_pictures/'.$info['path_photo'].'"';
        }
        
        $html.='
            <br><strong style="color: #0d72b3; text-align: center; font-size: 80%">Información del Paciente</strong><br>
            <!-- Datos del paciente y foto -->
            <table style="padding: 2px; width:95%">
                <tr style="font-size: 65%">
                    <td style="width: 55%; text-align: justify;">
                        <br><strong>Paciente:</strong> '.$info['paciente'].'
                    </td>
                    <td style="width: 30%; text-align: justify;">
                        <br><strong>Nro. Documento:</strong> '.$info['identificacion'].'
                    </td>
                    <td style="width: 15%; text-align: justify; " rowspan="5">
                        <br><img style=" height:80;" '.$rutaImagen.'> 
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 55%; text-align: justify;">
                        <br><strong>Dirección:</strong> '.$info['direccion'].'
                    </td>
                    <td style="width: 30%; text-align: justify;">
                        <br><strong>Ciudad:</strong>  '.$info['ciudad'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 19%; text-align: justify;">
                        <br><strong>F. Nacimiento:</strong> '.$info['fecha_nacimiento'].'
                    </td>
                    <td style="width: 13%; text-align: justify;">
                        <br><strong>Edad:</strong> '.$nroEdad.'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>Teléfono:</strong> '.$info['telefono'].'
                    </td>
                    <td style="width: 18%; text-align: justify;">
                        <br><strong>Estado Civil:</strong> '.$datos['estCivil'].'
                    </td>
                    <td style="width: 15%; text-align: justify;">
                        <br><strong>RH:</strong> '.$info['rh'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 45%; text-align: justify;">
                        <br><strong>EPS:</strong>  '.$info['eps'].'
                    </td>
                    <td style="width: 40%; text-align: justify;">
                        <br><strong>ARL:</strong> '.$datos['arl'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 85%; text-align: justify;">
                        <br><strong>Cargo o Labor:</strong> '.$datos['field_carg_actual'][5].'
                    </td>
                </tr>
                <tr style="font-size: 40%">
                    <td style="width: 100%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br>
                    </td>
                </tr> 
            </table>';
            
        /*$html.='
            <br>
            <br><strong style="color: #0d72b3; text-align: center; font-size: 80%">Énfasis</strong><br>
            <!-- Datos de exámenes -->
            <table style="padding: 2px; width:95%">
                <tr style="font-size: 65%">
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>CABEZA:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['cabeza']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>ORL:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['orl']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>CARDIOVASCULAR:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['cardiovascular']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>PULMONAR:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['pulmonar']['tiene'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>GENITO/URINARIO:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['genito_uri']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>ENDOCRINO:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['endocrino']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>NEUROLÓGICO:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['neurologico']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify;">
                        <br><strong>GASTROINTESTINAL:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center;">
                        <br>'.$datos['revision_sistemas']['gastrointestinal']['tiene'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 20%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br><strong>OSTEOMUS:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center; border-bottom: 0,6px solid black;">
                        <br>'.$datos['revision_sistemas']['osteous']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br><strong>DERMATOL:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center; border-bottom: 0,6px solid black;">
                        <br>'.$datos['revision_sistemas']['dermatol']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br><strong>PSICOLÓGICO:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center; border-bottom: 0,6px solid black;">
                        <br>'.$datos['revision_sistemas']['psicologico']['tiene'].'
                    </td>
                    <td style="width: 20%; text-align: justify; border-bottom: 0,6px solid black;">
                        <br><strong>VASC/PERFIL:</strong> 
                    </td>
                    <td style="width: 5%; text-align: center; border-bottom: 0,6px solid black;">
                        <br>'.$datos['revision_sistemas']['vasc_perfil']['tiene'].'
                    </td>
                </tr>
                <tr style="font-size: 40%">
                    <td style="width: 100%; text-align: justify;">
                        <br>
                    </td>
                </tr>
            </table>';
        */
            
        
        // Verificación según la opción Ingresada
        switch( trim($datos['concepto_laboral']['conceptos']) )
        {
            // Conceptos de Ingreso
            case 'APTO PARA EL CARGO 1':
                $textConcepto = 'Concepto de Ingreso: Apto para el cargo';
                break;
            case 'APTO CON RESTRICCIONES 1':
                $textConcepto = 'Concepto de Ingreso: Apto con restricciones';
                break;
            case 'NO APTO PARA EL CARGO 1':
                $textConcepto = 'Concepto de Ingreso: No apto para el cargo';
                break;
            case 'APLAZADO 1':
                $textConcepto = 'Concepto de Ingreso: Aplazado';
                break;
            
            // Conceptos Periódicos
            case 'SATISFACTORIO 2':
                $textConcepto = 'Concepto Periódico: Satisfactorio';
                break;
            case 'NO SATISFACTORIO 2':
                $textConcepto = 'Concepto Periódico: No Satisfactorio';
                break;
            
            // Conceptos de Egreso
            case 'SATISFACTORIO 3':
                $textConcepto = 'Concepto de Egreso: Satisfactorio';
                break;
            case 'NO SATISFACTORIO 3':
                $textConcepto = 'Concepto de Egreso: No Satisfactorio';
                break;
            
            // Conceptos Post Incapacidad
            case 'APTO PARA EL CARGO 4':
                $textConcepto = 'Concepto Post Incapacidad: Apto para el cargo';
                break;
            case 'APTO CON RESTRICCIONES 4':
                $textConcepto = 'Concepto Post Incapacidad: Apto con restricciones';
                break;
            case 'APLAZADO 4':
                $textConcepto = 'Concepto Post Incapacidad: Aplazado';
                break;
            
            // En caso de ninguna selección
            default:
                $textConcepto = 'Concepto: Concepto médico no especificado.';
                break;
        }
        
        // '.$datos['motivo_consulta']['enfasis'][0].'
        
        $html.='
            <br><strong style="color: #0d72b3; text-align: center; font-size: 80%">Concepto del Médico Ocupacional</strong><br>
            <!-- Valoración de médico ocupacional -->
            <table style="padding: 2px; width:95%">
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>Tipo de Examen Médico: </strong> '.$datos['motivo_consulta']['tipo'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>Énfasis: </strong> '.$datos['motivo_consulta']['enfasis'][0].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>'.$textConcepto.'</strong>  <!--'.$datos['concepto_laboral']['conceptos'].'-->
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>Observaciones:</strong> '.$datos['concepto_laboral']['concepto_observacion']['observaciones'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>Recomendaciones Ocupacionales:</strong> '.$datos['recomendaciones_ocupacionales'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify;">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>Recomendaciones Médicas:</strong> '.$datos['recomendaciones_medicas'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify;">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width: 100%; text-align: justify; ">
                        <br><strong>Procedimiento y/o Exámenes:</strong> 
                        '.$datos['procedimientos_examenes'].'
                    </td>
                </tr>
                <tr style="font-size: 40%">
                    <td style="width: 100%; text-align: justify;">
                        <br>
                    </td>
                </tr>
            </table>';
        
        $html.='
            <!-- Información de Consentimiento Informado -->
            <table style="padding: 2px; width:95%; ">
                <tr style="font-size: 50%">
                    <td style="width: 100%; text-align: justify; border: 0,6px solid black;">
                        <br><strong>CONSENTIMIENTO INFORMADO: </strong>
                        <br>De acuerdo a Resolución 2346 de 2007, modificado por la resolución 1918 de 2009. 
                        Manifiesto que he sido informado por parte del personal de acerca de los exámenes que me van a ser realizados, 
                        Historia Clínica Ocupacional y/o otros como ej. Audiometría, Tamizaje visual, Exámenes de laboratorio 
                        (ej. Serología, Perfil lipídico, prueba de embarazo, glucometría, cuadro hemático, creatinina, nitrógeno ureico, 
                        transaminasas, bilirrubinas, parcial de orina), enfermería, odontología, psicología y promoción de la salud 
                        (crecimiento y desarrollo, electrocardiograma, afinamientos) por parte del grupo asistencial de, 
                        comprendo y estoy de acuerdo, en consecuencia doy mi consentimiento para la realización de estos, 
                        y autorizo al personal de a que proceda con la realización. 
                        Autorizo a que se suministre la información necesaria a las personas o entidades contempladas en la legislación 
                        para dar cumplimiento al programa de salud ocupacional o aquellas personas según lo contemple la ley o que yo 
                        autorice por escrito la entrega de mis reportes e historia clínica, teniendo en cuenta la reserva de historia 
                        clínica que está contemplado en el decreto 1995 de 1999. Además estoy de acuerdo y aseguro que toda la información 
                        que he suministrado para la realización de esta historia es verdadera y que no he ocultado información relevante 
                        para mi condición de salud.
                    </td>
                </tr>
                <tr style="font-size: 40%">
                    <td style="width: 100%; text-align: justify;">
                        <br>
                    </td>
                </tr>
            </table>';
        
        $html.='
            <!-- Valoración de médico ocupacional -->
            <table style="padding: 2px; width:95%">
                <tr style="font-size: 65%">
                    <td style="width: 50%; height: 60px; text-align: justify;">
                        <br><img  src="'.$firm.'" style="width:100px !important; height:60px !important;" >
                        <!--<br><strong>Profesional de Atención:</strong> 
                        <br><strong>'.$info['especialista'].'</strong>
                        <br>'.$info['profesion'].'
                        <br>'.$info['tarjeta'].'-->
                    </td>
                    <td style="width: 50%; height: 60px; text-align: justify; ">
                        <br><img style=" height:50;" >
                        <!--<br>
                        <br>
                        <br>
                        <br>
                        <br>-->
                        <!--<br><strong>Aspirante y/o Trabajador:</strong> 
                        <br><strong>'.$info['paciente'].'</strong>
                        <br>Nro. Documento: '.$info['identificacion'].'
                        <br>-->
                    </td>
                </tr>
                
                <tr style="font-size: 65%">
                    <td style="width: 50%; text-align: justify;">
                        <!--<br><img  src="'.$firm.'" style="width:100px !important; height:60px !important;" >-->
                        <br><strong>Profesional de Atención:</strong> 
                        <br><strong>'.$info['especialista'].'</strong>
                        <br>'.$info['profesion'].'
                        <br>'.$info['tarjeta'].'
                    </td>
                    <td style="width: 50%; text-align: justify; ">
                        <br><strong>Aspirante y/o Trabajador:</strong> 
                        <br><strong>'.$info['paciente'].'</strong>
                        <br>Nro. Documento: '.$info['identificacion'].'
                        <br>
                    </td>
                    
                </tr>
            </table>

        ';
        
        // src="img/logo_CDC.png"   esto era la firma del aspirante de logo cdc  ojo se colocaron
        // puros br esto debe cambiar
        $tcpdf->writeHTML($html, true, false, true, false, '');
        
        $tcpdf->Output(WWW_ROOT.'history/'.$info['numero_orden'].'HistoriaClinica.pdf', 'FI'); 
        exit();
    }
    
    // Fin de impresión de concepto médico

    /*
    * Modificacion: 2018-03-15
    * - Correcciones para ingreso de información de paicnete
    * - Correcciones para ingreso de exámenes de laboratorio solicitados
    */
    public function printHistoriaClinica($userId = "",$medical_id = null, $imprimir = true, $initialsUser = null, $identification = null){
        
        //Configure::write('debug', 2);
        $this->autoRender = false;
        
        
		$this->loadModel('MedicalRecord');
    

           if( empty( $medical_id ) ){
            
            $medical_id = $data['medialRecord'];

        }
        

         // Obtiene Appointmens ID.
        $queryAP = " 
        SELECT 
            appointments.id ,
            studies.name ,
            studies.type
        FROM
            medical_record
                INNER JOIN
            attentions ON attentions.id = medical_record.attentions_id
                INNER JOIN
            appointments ON appointments.id = attentions.appointments_id
            inner join  studies on studies.id =  appointments.studies_id
        WHERE
            medical_record.id = '". $medical_id."'"; 

    
        $connection = ConnectionManager::get('default');

        $appointment  = $connection->execute($queryAP)->fetchAll('assoc');
   
        $id_appointment = $appointment[0]['id'];
        
        
        // OBTIENE EL TIPO DE ATENCION DESDE MYSQL Y NO DESSDE MONGO -- DEICY
        $tipoHistoria = $appointment[0]['type'];
        
        
        
        // Llamado a funcion para obtener información del paciente
        $info = $this->getPatientInforByRecordId($medical_id);
		
        $patient_id = $info['id'];
             

        $specialistData = $this->getFirmSpecialist($info['codigoSpecialist']);

        $firm = $specialistData['url'];

        // Consulta de laboratorios clinicos
        $laboId = $this->MedicalRecord->getServicesByMedicalRecord($medical_id, true );// Obtiene el id del registro de laboratorios
        

        $serviceId = $this->MedicalRecord->getServicesByMedicalRecord($medical_id, false);
        
        $servicio = '';
        
        $count = 0;

        
        // Verificación de variable existente
        if ( count($laboId) > 0 )
        {
            foreach ($laboId as $key => $value) {
            
                $servicio [$count] = $this->getServicesLabor($medical_id, $value['medical_services_id']);
                    if(!empty($servicio[$count][0]['observaciones_generales'])){
                     $servicio [$count]['observaciones'] =  $servicio[$count][0]['concec'].' - '. $servicio[$count][0]['observaciones_generales'];
                    }
                    $servicio [$count]['tipo'] = 'Laboratorio';
                 $count ++;
            }
                //$count ++;
        }
        
            $cantLaboratories = count( $laboId );
        
        
          // Verificación de variable existente
        if ( count($serviceId) > 0 )
        {
            foreach ($serviceId as $key => $value) {
            
                $servicio[$count] = $this->getOrderServices($medical_id, $value['medical_services_id'], $value['interno']);
                if(!empty($servicio[$count][0]['observaciones_generales'])){
                     $servicio [$count]['observaciones'] =  $servicio[$count][0]['concec'].' - '. $servicio[$count][0]['observaciones_generales'];
                }
               
                $servicio [$count]['tipo'] = 'Servicio';
                $count ++;
            }
        }
        
        $cantServices = count( $serviceId );
        
         // OBTENER FORMULAS
        $prescriptionId = $this->MedicalRecord->getPrescriptionByMedicalRecord( $medical_id );
       
        $prescription  = '';
        
        $countP = 0;

        $htmlPrescription = '';
         if ( count($prescriptionId) > 0 )
        {
            foreach ($prescriptionId as $key => $value) {
                
                
                 //  $prescription = $this->getPrescription($id, $prescriptionId);
                     
                     $item =  $this->getPrescription($medical_id, $value['prescription_id']); 
                
                     $prescription[$countP]  = $item; 
                
                     $htmlPrescription .= $this->getHTMLPrescription($item);
                
                   
                    $countP ++;
              
            }
               
           
        }  

        
      
        $cantPresctiption = count($prescriptionId);
        
          // OBTENGO INCAPACIDADES
        $disability = $this->getDisability( $medical_id );

        
        
          // OBITENE DATOS HC MONGO...     aqui obtiene la info de mongo     
        $medicalRecord = new MedicalRecordController();
 
        $datos = $this->consultarHistoriaClinica( $id_appointment );
        
        
        
        
        
        
        
        
        
        
        /**
         * Obtiene la información del paciente si hace parte de investigacion
         */
        $investigation = new MedicalRecordInvestigationController();

        $objInvestigation['medical'] = $datos['medical_record_id'];

      //  $resultInvestigation = $investigation->getLastMedicalRecordInvestigation($objInvestigation);        
        
        // datos de los antecedentes reumatologicos
        //$cantReumatologicos = count($datos['antecedentes']['reumatologicos']);
        //$reumatologicos=$datos['antecedentes']['reumatologicos'];
        //print_r($reumatologicos);
         //       exit();
        // for($i=0; $i <  $cantReumatologicos; $i++) 
        // { 
        //     if($reumatologicos[''.$i.'']['valor'] == ''){
        //         $reumatologicos[''.$i.'']['valor'] = 'No';
        //     }else{
        //         $reumatologicos[''.$i.'']['valor'] = 'Si';
        //     }
        // }
        
        
         // datos de los antecedentes no reumatologicos
        
      //  $cantNoReumatologicos = count($datos['antecedentes']['no_reumatologicos']);
       // $noreumatologicos=$datos['antecedentes']['no_reumatologicos'];
                  
        
        //for ($j=0; $j <  $cantNoReumatologicos  ; $j++)
        //{
        //    $cant = count($noreumatologicos['0']);
            
       
            //for($i=0; $i <  $cant -2 ; $i++) { 
            
                //if ($noreumatologicos[''.$j.''][''.$i.''] ==''){
                //  $noreumatologicos[''.$j.''][''.$i.''] = 'Si';
                //}else{
                //   $noreumatologicos[''.$j.''][''.$i.''] = 'No';
                //}

            
            //}
        //}
        
        
        // variables de antecedentes reumatologicos
        // $artrosis= $reumatologicos['0']['valor'];
        // $artrosisObs = $reumatologicos['0']['observacion'];
        // $osteoporosis = $reumatologicos['1']['valor'];
        // $osteoporosisObs = $reumatologicos['1']['observacion'];
        // $fibromalgia = $reumatologicos['2']['valor'];
        // $fibromalgiaObs = $reumatologicos['2']['observacion'];
        // $lups = $reumatologicos['3']['valor'];
        // $lupusObs = $reumatologicos['3']['observacion'];
        // $arematoide = $reumatologicos['4']['valor'];
        // $arematoideObs = $reumatologicos['4']['observacion'];
        // $sjorgen = $reumatologicos['5']['valor'];
        // $sjorgenObs = $reumatologicos['5']['observacion'];
        // $spa = $reumatologicos['6']['valor'];
        // $spaObs = $reumatologicos['6']['observacion'];
        // $esclerodermia = $reumatologicos['7']['valor'];
        // $esclerodermiaObs = $reumatologicos['7']['observacion'];        
        // $otrosAntecedentes = $reumatologicos['8']['otros'];

        // variables de antecedentes no reumatologicos yenifer aqui 
        //$antCliDiagHta.=  ( isset($datos['qf_37']) && $datos['qf_37'] === 'si') ? 'Si' : (( isset($datos['qf_37']) && $datos['qf_37'] === 'no') ? 'No' : '');
        // $hipertencion = ( isset($datos['antecedentes']['no_reumatologicos']['0']['0']) && $datos['antecedentes']['no_reumatologicos']['0']['0'] === 'HIPERTENSIÓN ARTERIAL') ? "Si" : "No";
        // $infartotto = ( isset($datos['antecedentes']['no_reumatologicos']['0']['1']) && $datos['antecedentes']['no_reumatologicos']['0']['1'] === 'INFARTO CON TTO MEDICO') ? "Si" : "No";
        // $infartocatete = ( isset($datos['antecedentes']['no_reumatologicos']['0']['2']) && $datos['antecedentes']['no_reumatologicos']['0']['2'] === 'INFARTO CON CATETERISMO') ? "Si" : "No";
        // $revascularizacion = ( isset($datos['antecedentes']['no_reumatologicos']['0']['3']) && $datos['antecedentes']['no_reumatologicos']['0']['3'] === 'REVASCULARIZACIÓN MIOCARDICA') ? "Si" : "No";
        // $icc = ( isset($datos['antecedentes']['no_reumatologicos']['0']['4'] ) && $datos['antecedentes']['no_reumatologicos']['0']['4'] === 'INSUFICIENCIA CARDIACA (ICC)') ? "Si" : "No";
        // $arritmia =( isset($datos['antecedentes']['no_reumatologicos']['0']['5'] ) && $datos['antecedentes']['no_reumatologicos']['0']['5'] === 'ARRITMIA CARDIACA') ? "Si" : "No";
        // $valvulopatia=( isset($datos['antecedentes']['no_reumatologicos']['0']['6'] ) && $datos['antecedentes']['no_reumatologicos']['0']['6'] === 'VALVULOPATIA') ? "Si" : "No";
        // $otrosCardiovascular =$datos['antecedentes']['no_reumatologicos']['0']['otro']; // Campo de texto
        // $diabetes1 =( isset($datos['antecedentes']['no_reumatologicos']['1']['0'] ) && $datos['antecedentes']['no_reumatologicos']['1']['0'] === 'DIABETES TIPO 1') ? "Si" : "No";
        // $diabetes2 =( isset($datos['antecedentes']['no_reumatologicos']['1']['2'] ) && $datos['antecedentes']['no_reumatologicos']['1']['2'] === 'DIABETES TIPO 2') ? "Si" : "No";
        // $hiperColester = ( isset($datos['antecedentes']['no_reumatologicos']['1']['4']) && $datos['antecedentes']['no_reumatologicos']['1']['4'] === 'HIPER COLESTERLOMIA' ) ? "Si" : "No";
        // $hiperTricli= ( isset($datos['antecedentes']['no_reumatologicos']['1']['6']) && $datos['antecedentes']['no_reumatologicos']['1']['6'] === 'HIPER TRICLICERIDEMIA' ) ? "Si" : "No";
        // $hiperMixta= ( isset($datos['antecedentes']['no_reumatologicos']['1']['1']) && $datos['antecedentes']['no_reumatologicos']['1']['1'] === 'HIPERLIPIDEMIA MIXTA' ) ? "Si" : "No";
        // //$hipotiroidismo=$noReumatologicos['1']['3'];
        // $hipotiroidismo= ( isset($datos['antecedentes']['no_reumatologicos']['1']['3'])  && $datos['antecedentes']['no_reumatologicos']['1']['3'] === 'DEFCIT HDL' ) ? "Si" : "No";
        // $hiperGota= ( isset($datos['antecedentes']['no_reumatologicos']['1']['5'])  && $datos['antecedentes']['no_reumatologicos']['1']['5'] === 'OSTEOPOROSIS') ? "Si" : "No";
        // $obesidad= ( isset($datos['antecedentes']['no_reumatologicos']['1']['7'])  && $datos['antecedentes']['no_reumatologicos']['1']['7'] === 'OSTEOPOROSIS') ? "Si" : "No";
        // $otrosMetabolicos = $datos['antecedentes']['no_reumatologicos']['1']['otro']; // Campo de texto
        // $epoc = ( isset($datos['antecedentes']['no_reumatologicos']['2']['0'] ) && $datos['antecedentes']['no_reumatologicos']['2']['0'] === 'ECOP') ? "Si" : "No";
        // $silicosis= ( isset($datos['antecedentes']['no_reumatologicos']['2']['2'] ) && $datos['antecedentes']['no_reumatologicos']['2']['2'] === 'SILICOSIS') ? "Si" : "No";
        // $asma= ( isset($datos['antecedentes']['no_reumatologicos']['2']['3'] ) && $datos['antecedentes']['no_reumatologicos']['2']['3'] === 'ASMA') ? "Si" : "No";
        // $bagazosis= ( isset($datos['antecedentes']['no_reumatologicos']['2']['4'] ) && $datos['antecedentes']['no_reumatologicos']['2']['4'] === 'BAGAZOSIS') ? "Si" : "No";
        // $tabaquismo= ( isset($datos['antecedentes']['no_reumatologicos']['2']['1'] ) && $datos['antecedentes']['no_reumatologicos']['2']['1'] === 'BAGAZOSIS') ? "Si" : "No";
        // $neumoconiosis= ( isset($datos['antecedentes']['no_reumatologicos']['2']['23'] ) && $datos['antecedentes']['no_reumatologicos']['2']['23'] === 'ECOP') ? "Si" : "No";
        // $otrosPulmonares= $datos['antecedentes']['no_reumatologicos']['2']['otros']; // Campo de texto
        // $cancer= $datos['antecedentes']['no_reumatologicos']['3']['0'];
        // $enfermedadRenal= $datos['antecedentes']['no_reumatologicos']['3']['1'];
        // $enfermedadPerio= ( isset($datos['antecedentes']['no_reumatologicos']['3']['2']) ) ? "Si" : "No";
        // $vih= ( isset($datos['antecedentes']['no_reumatologicos']['3']['3']) ) ? "Si" : "No";
        // $enfermedadNeuro= ( isset($datos['antecedentes']['no_reumatologicos']['3']['4']) ) ? "Si" : "No";
        // $otrosOtros2= $datos['antecedentes']['no_reumatologicos']['3']['otro'];
        
        

    //    consultarHistoriaClinicaPruebas 
   
       // Recuperación del tipo de historia clinica
       //  $tipoHistoria = $datos['type_attention'];

       
        
        // Datos adicionales del paciente
        $ocupacion = '';
       
        $order = $this->getOrderAndSpecialist($medical_id);

	
        $dataDiagnostico = $this->getDiagnostics($medical_id);
     

        
        if( empty( $order['users_id'] ) ){

            $order['users_id'] = $userId;

        }


        // Cargar Historia clinica  
        
        $idsHistoriaClinica = $this->MedicalRecord->obtenerTablasRelacionadas(  $id_appointment  );
      
        
        $nameFormato = 'HISTORIA CLÍNICA';
        $informDataHistory = ''; 
        $infoAntecedentes  = '';   
        $enfermedadActual = '';
         
        //echo $tipoHistoria;
        //print_r($datos);
        // Asignación de variables según el tipo de historia clínica - studies_type_attention
        switch( $tipoHistoria )
        {
            // Casos para asignación de variables
            case 3: // Historia clinica a partir de información de reumatología
     
            //     $haveAntecents  = true;
                  
            //      /**
            //      * Sección para las recomendaciones
            //      */
            //     $htmlRecommendation = '';

            //     $queryRecommendation = 'SELECT id, recommendation FROM medical_recommendation WHERE medical_record_id = ' .$medical_id;
                  
            //     $resulRecomendacion = $connection->execute($queryRecommendation)->fetchAll('assoc');

            //     if(count($resulRecomendacion) > 0){

            //         $textReco = $resulRecomendacion[0]['recommendation'];

            //         $htmlRecommendation = '<br>'.nl2br($textReco).'';

            //     }
                
            //     // // Variables relacionadas al acompañante
            //     // $acompañante    =  htmlentities(  $datos['reuma_6']);
            //     // $parentezco     =  htmlentities(  $datos['reuma_8']);
            //     // $telefonoAcom   =  htmlentities(  $datos['reuma_9']);
            
            //     // // Variables que representan signos vitales
            //     // $frecCardiaca   = $datos['reuma_1884'];
            //     // $frecRespira    = $datos['reuma_1885'];
            //     // $temperatura    = $datos['reuma_1886'];
            //     // $tenArterial    = $datos['reuma_1883'];
              
            //     // // Variables que representan examen fisico
            //     // $peso       = $datos['reuma_1887'];
            //     // $talla      = $datos['reuma_1888'];
            //     // $imc        = $datos['reuma_1889'];
             
            //     // $estadoGeneral = $datos['reuma_1915est'];
            //     // $estadoGeneralObse = $datos['reuma_1916est'];
                  
            //     // $orl = $datos['reuma_1891'];
            //     // $orlDesc =$datos['reuma_1892'];
                  
            //     // $cabeCuello =$datos['reuma_1894'];
            //     // $cabeCuelloDesc=$datos['reuma_1895'];
                  
            //     // $cardiaco =$datos['reuma_1897'];
            //     // $cardiacoDesc =$datos['reuma_1898'];
                  
            //     // $pulmonar =$datos['reuma_1900'];
            //     // $pulmonarDesc =$datos['reuma_1901'];
                  
            //     // $abdomen =$datos['reuma_1903'];
            //     // $abdomenDesc =$datos['reuma_1904'];
                  
            //     // $extremidad =$datos['reuma_1906'];
            //     // $extremidadDesc =$datos['reuma_1907']; 
                  
            //     // $piel =$datos['reuma_1909'];
            //     // $pielDesc =$datos['reuma_1910'];
                  
            //     // $neuro =$datos['reuma_1912'];
            //     // $neuroDesc =$datos['reuma_1913'];
                  
            //     // $genito =$datos['reuma_1915']; 
            //     // $genitoDesc =$datos['reuma_1916']; 
                  
            //     // $metabolico =$datos['reuma_1912met']; 
            //     // $metabolicoDesc =$datos['reuma_1913met'];
                  
            //     // $vascular =$datos['reuma_1909vas']; 
            //     // $vascularDesc =$datos['reuma_1910vas']; 
                  
            //     // $otrosOtros = $datos['reuma_1918']; 
   
              
                  
                                  
                         
            //     $informDataHistory = '';
  
  
            //     $examenFisOtros = '
                
            //               <table>           
            //                   <tr style="font-size: 55%">
            //                       <td style="width: 15%;"><strong>Frec. Cardíaca: </strong>'.$frecCardiaca.'</td>
            //                       <td style="width: 15%;"><strong>Frec. Respiratoria: </strong> '.$frecRespira.'</td>
            //                       <td style="width: 15%;"><strong>Temperatura: </strong> '.$temperatura.'</td>
            //                       <td style="width: 15%;"><strong>Tensión Arterial: </strong>  '.$tenArterial .'</td>
                             
            //                       <td style="width: 15%;"><strong>Peso: </strong>'.$peso.'</td>
            //                       <td style="width: 15%;"><strong>Talla: </strong> '.$talla.'</td>
            //                       <td style="width: 10%;"><strong>IMC: </strong> '.$imc.'</td>
            //                   </tr>
                            
                             
            //               </table>   
            //               <table>
                          
            //                 <tr style="font-size: 55%">
            //                   <td style="width: 50%;"><strong>ORL: </strong> '.$orl.' '.$orlDesc.'</td>
            //                   <td style="width: 50%;"><strong>Cabeza y cuello:</strong> '.$cabeCuello.' '.htmlentities($cabeCuelloDesc).'</td>
            //               </tr>
                       
                          
            //               <tr style="font-size: 55%">
            //                   <td style="width: 50%;"><strong>Cardíaco: </strong> '.$cardiaco.' '.$cardiacoDesc.'</td>
            //                   <td style="width: 50%;"><strong>Pulmonar:</strong> '.$pulmonar.' '.htmlentities($pulmonarDesc).'</td>
            //               </tr>
                   
                          
            //               <tr style="font-size: 55%">
            //                   <td style="width: 50%;"><strong>Abdomen: </strong>'.$abdomen.' '.htmlentities($abdomenDesc).'</td>
            //                   <td style="width: 50%;"><strong>Extremidades: </strong>'.$extremidad.' '.htmlentities($extremidadDesc).'</td>
            //               </tr>
                            
            //                 <tr style="font-size: 55%">
            //                   <td style="width: 50%;"><strong>Piel: </strong>'.$piel.' '.htmlentities($pielDesc).'</td>
            //                   <td style="width: 50%;"><strong>Neurológico: </strong>'.$neuro.' '.htmlentities($neuroDesc).'</td>
            //                 </tr> 
            //                 <tr style="font-size: 55%">
            //                   <td style="width: 50%;"><strong>Genito urinario  : </strong>'.$genito.' '.htmlentities($genitoDesc).'</td>
            //                   <td style="width: 50%;"><strong>Metabolico  : </strong>'.$metabolico.' '.htmlentities($metabolicoDesc).'</td>
            //                   <td></td>
            //                 </tr >
            //                <tr style="font-size: 55%">
            //                   <td style="width: 50%;"><strong>Vascular:  </strong>'.$vascular.' '.htmlentities($vascularDesc).'</td>
            //                   <td style="width: 50%;"><strong>Estado General:</strong>  '.$estadoGeneral .' '.htmlentities($estadoGeneralObse).'</td>
  
  
            //                 </tr>
                            
            //                   <tr style="font-size: 55%">
            //                   <td style="width: 100%;"><strong>Otros:  </strong>'. htmlentities( $otrosOtros).'</td>
            //                    </tr>
                            
                          
            //               </table>';
            //    //  $examenFisOtros = $datos['reuma_1918'];
            //     $informDataHistory = '';
                
            //     $antTratBio= '';
            //     $antTratBio.= ( isset($datos['reuma_1360_122']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenEta25 = '';
            //     $antTratBioMotSuspenEta25.= ($datos['reuma_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenEta25.= ($datos['reuma_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenEta25.= ($datos['reuma_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenEta25.= ($datos['reuma_1369'] == 'otros') ? ' - OTROS' : '';
                
            
            //     $antTratBioEta50= '';
            //     $antTratBioEta50.= ( isset($datos['reuma_1360_123']) ) ? 'Si' : 'No';
                
            //     $antTratBioMotSuspenEta50 = '';
            //     $antTratBioMotSuspenEta50.= ($datos['reuma_1376'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenEta50.= ($datos['reuma_1377'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenEta50.= ($datos['reuma_1378'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenEta50.= ($datos['reuma_1379'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioAndali= '';
            //     $antTratBioAndali.= ( isset($datos['reuma_1360_1241']) ) ? 'Si' : 'No';
                
            //     $antTratBioMotSuspenAndali = '';
            //     $antTratBioMotSuspenAndali.= ($datos['reuma_1386'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenAndali.= ($datos['reuma_1387'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenAndali.= ($datos['reuma_1388'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenAndali.= ($datos['reuma_1389'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioInflix= '';
            //     $antTratBioInflix.= ( isset($datos['reuma_1360_124']) ) ? 'Si' : 'No';
                
            //     $antTratBioMotSuspenInflix = '';
            //     $antTratBioMotSuspenInflix.= ($datos['reuma_1396'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenInflix.= ($datos['reuma_1397'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenInflix.= ($datos['reuma_1398'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenInflix.= ($datos['reuma_1399'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioCerto= '';
            //     $antTratBioCerto.= ( isset($datos['reuma_1360_126']) ) ? 'Si' : 'No';
                
            //     $antTratBioMotSuspenCerto = '';
            //     $antTratBioMotSuspenCerto.=($datos['reuma_1416'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenCerto.=($datos['reuma_1417'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenCerto.=($datos['reuma_1418'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenCerto.=($datos['reuma_1419'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioGoli = '';
            //     $antTratBioGoli.= ( isset($datos['reuma_1360_127']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenGoli = '';
            //     $antTratBioMotSuspenGoli.= ($datos['reuma_1426'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenGoli.= ($datos['reuma_1427'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenGoli.= ($datos['reuma_1428'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenGoli.= ($datos['reuma_1429'] == 'otros') ? ' - OTROS' : '';
                    
            //     $antTratBioAbaIV = '';
            //     $antTratBioAbaIV.= ( isset($datos['reuma_1360_128']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenAbaIV = '';
            //     $antTratBioMotSuspenAbaIV.=($datos['reuma_1436'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenAbaIV.=($datos['reuma_1437'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenAbaIV.=($datos['reuma_1438'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenAbaIV.=($datos['reuma_1439'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioAbaSC = '';
            //     $antTratBioAbaSC.= ( isset($datos['reuma_1360_129']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenAbaSC = '';
            //     $antTratBioMotSuspenAbaSC.=($datos['reuma_1446'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenAbaSC.=($datos['reuma_1447'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenAbaSC.=($datos['reuma_1448'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenAbaSC.=($datos['reuma_1449'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioTociIV = '';
            //     $antTratBioTociIV.= ( isset($datos['reuma_1360_130']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenTociIV = '';
            //     $antTratBioMotSuspenTociIV.= ($datos['reuma_1456'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenTociIV.= ($datos['reuma_1457'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenTociIV.= ($datos['reuma_1458'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenTociIV.= ($datos['reuma_1459'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioTociSC = '';
            //     $antTratBioTociSC.= ( isset($datos['reuma_1360_131']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenTociSC = '';
            //     $antTratBioMotSuspenTociSC.= ($datos['reuma_1476'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenTociSC.= ($datos['reuma_1477'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenTociSC.= ($datos['reuma_1478'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenTociSC.= ($datos['reuma_1479'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioRitux = '';
            //     $antTratBioRitux.= ( isset($datos['reuma_1360_132']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenRitux = '';
            //     $antTratBioMotSuspenRitux.= ($datos['reuma_1486'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenRitux.= ($datos['reuma_1487'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenRitux.= ($datos['reuma_1488'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenRitux.= ($datos['reuma_1489'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioBeli = '';
            //     $antTratBioBeli.= ( isset($datos['reuma_1360_133']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenBeli = '';
            //     $antTratBioMotSuspenBeli.= ($datos['reuma_1496'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenBeli.= ($datos['reuma_1497'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenBeli.= ($datos['reuma_1498'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenBeli.= ($datos['reuma_1499'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioTofac = '';
            //     $antTratBioTofac.= ( isset($datos['reuma_1360_135']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenTofac = '';
            //     $antTratBioMotSuspenTofac.= ($datos['reuma_1516'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenTofac.= ($datos['reuma_1517'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenTofac.= ($datos['reuma_1518'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenTofac.= ($datos['reuma_1519'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioSecuk = '';
            //     $antTratBioSecuk.= ( isset($datos['reuma_1360_137']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenSecuk = '';
            //     $antTratBioMotSuspenSecuk.= ($datos['reuma_1526_121'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenSecuk.= ($datos['reuma_1527_121'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenSecuk.= ($datos['reuma_1528_121'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenSecuk.= ($datos['reuma_1529_121'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratBioUstek = '';
            //     $antTratBioUstek.= ( isset($datos['reuma_1360_138']) ) ? 'Si' : 'No';
            
            //     $antTratBioMotSuspenUstek = '';
            //     $antTratBioMotSuspenUstek.= ($datos['reuma_1526_122'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratBioMotSuspenUstek.= ($datos['reuma_1527_122'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratBioMotSuspenUstek.= ($datos['reuma_1528_122'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratBioMotSuspenUstek.= ($datos['reuma_1529_122'] == 'otros') ? ' - OTROS' : '';
                
            //     $antTratBioOtro = $datos['reuma_1532'];
            
            //     // Bloque de validacion para antecendentes de tratamiento recibido - Dosis usado para validacion
            //     $informDataTrataRecibido = '';
            //     if ( isset($datos['reuma_1360']) && $datos['reuma_1360'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ETANERCEPT 25:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBio.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1362'].' '.$datos['reuma_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenEta25.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1370']) && $datos['reuma_1370'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ETANERCEPT 50:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioEta50.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1370'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1372'].' '.$datos['reuma_1375'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenEta50.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1380']) && $datos['reuma_1380'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ADALIMUMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioAndali.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1380'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1382'].' '.$datos['reuma_1385'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenAndali.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1390']) && $datos['reuma_1390'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>INFLIXIMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioInflix.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1390'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1392'].' '.$datos['reuma_1395'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenInflix.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1410']) && $datos['reuma_1410'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>CERTOLIZUMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioCerto.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1410'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1412'].' '.$datos['reuma_1415'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenCerto.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1420']) && $datos['reuma_1420'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>GOLIMUMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioGoli.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1420'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1422'].' '.$datos['reuma_1425'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenGoli.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1430']) && $datos['reuma_1430'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ABATACEPT IV:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioAbaIV.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1430'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1432'].' '.$datos['reuma_1435'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenAbaIV.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1440']) && $datos['reuma_1440'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ABATACEPT SC:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioAbaSC.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1440'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1442'].' '.$datos['reuma_1445'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenAbaSC.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1450']) && $datos['reuma_1450'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>TOCILIZUMAB IV:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioTociIV.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1450'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1452'].' '.$datos['reuma_1455'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenTociIV.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1470']) && $datos['reuma_1470'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>TOCILIZUMAB SC:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioTociSC.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1470'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1472'].' '.$datos['reuma_1475'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenTociSC.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1480']) && $datos['reuma_1480'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>RITUXIMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioRitux.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1480'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1482'].' '.$datos['reuma_1485'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenRitux.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1490']) && $datos['reuma_1490'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>BELIMUMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioBeli.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1490'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1492'].' '.$datos['reuma_1495'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenBeli.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1510']) && $datos['reuma_1510'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>TOFACITINIB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioTofac.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1510'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1512'].' '.$datos['reuma_1515'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenTofac.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1520_2']) && $datos['reuma_1520_2'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>SECUKINUMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioSecuk.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1520_2'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1522_121'].' '.$datos['reuma_1525_121'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenSecuk.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($datos['reuma_1520_3']) && $datos['reuma_1520_3'] != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>USTEKINUMAB:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biosimilar:</strong> '.$antTratBioUstek.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['reuma_1520_3'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1522_122'].' '.$datos['reuma_1525_122'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:55%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratBioMotSuspenUstek.'
            //                   </td>
            //               </tr>
            //               </table>
            //         ';
            //     }
            //     if ( isset($antTratBioOtro) && $antTratBioOtro != '' )
            //     {
            //         $informDataTrataRecibido.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>Otro:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$antTratBioOtro.'
            //                   </td>
            //               </tr>
            //             </table>
            //         ';
            //     }
                
            //     // Validacion de ingreso de bloque
            //     if ( $informDataTrataRecibido != '' )
            //     {
            //         // Bloque de Antecedentes de Tratamiento Recibido
            //         $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 70%">
            //                 <td style="text-align: center;" >
            //                     <br><br><strong>Antecedentes de Tratamiento Recibido</strong>
            //                 </td>
            //             </tr>
            //         </table>
                    
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><strong>Tratamiento Recibido por Terapia Biológica</strong>
            //                   </td>
            //               </tr>
            //         </table>';
                
            //         $informDataHistory.= $informDataTrataRecibido;

            //         //$informDataHistory.= '
            //         //</table>';

            //     }
                
                
            //     $antTratMediSuspenLeflu = '';
            //     $antTratMediSuspenLeflu.= ($datos['lf_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenLeflu.= ($datos['lf_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenLeflu.= ($datos['lf_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenLeflu.= ($datos['lf_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenMetrote = '';
            //     $antTratMediSuspenMetrote.= ($datos['metotre_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenMetrote.= ($datos['metotre_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenMetrote.= ($datos['metotre_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenMetrote.= ($datos['metotre_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenSulfasa = '';
            //     $antTratMediSuspenSulfasa.= ($datos['sulfa_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenSulfasa.= ($datos['sulfa_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenSulfasa.= ($datos['sulfa_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenSulfasa.= ($datos['sulfa_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenCloro = '';
            //     $antTratMediSuspenCloro.= ($datos['cloro_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenCloro.= ($datos['cloro_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenCloro.= ($datos['cloro_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenCloro.= ($datos['cloro_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenHidrox = '';
            //     $antTratMediSuspenHidrox.= ($datos['hidroxi_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenHidrox.= ($datos['hidroxi_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenHidrox.= ($datos['hidroxi_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenHidrox.= ($datos['hidroxi_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenAzatio = '';
            //     $antTratMediSuspenAzatio.= ($datos['azati_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenAzatio.= ($datos['azati_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenAzatio.= ($datos['azati_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenAzatio.= ($datos['azati_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenPenici = '';
            //     $antTratMediSuspenPenici.= ($datos['peni_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenPenici.= ($datos['peni_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenPenici.= ($datos['peni_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenPenici.= ($datos['peni_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenCiclo = '';
            //     $antTratMediSuspenCiclo.= ($datos['ciclo_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenCiclo.= ($datos['ciclo_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenCiclo.= ($datos['ciclo_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenCiclo.= ($datos['ciclo_1369'] == 'otros') ? ' - OTROS' : '';
            
            //     $antTratMediSuspenSales = '';
            //     $antTratMediSuspenSales.= ($datos['sales_1366'] == 'no efectividad') ? ' - NO EFECTIVIDAD' : '';
            //     $antTratMediSuspenSales.= ($datos['sales_1367'] == 'dispionibilidad') ? ' - DISPONIBILIDAD' : '';
            //     $antTratMediSuspenSales.= ($datos['sales_1368'] == 'evento adverso') ? ' - EVENTO ADVERSO' : '';
            //     $antTratMediSuspenSales.= ($datos['sales_1369'] == 'otros') ? ' - OTROS' : '';
                
            //     $antTratMediAines = '';
            //     $antTratMediAines.= ( isset($datos['reuma_728_AINES']) && ($datos['reuma_728_AINES'] == 'TENDON DE AQUILES') ) ? ' - IBUPROFENO'        : '';
            //     $antTratMediAines.= ( isset($datos['reuma_729_AINES']) && ($datos['reuma_729_AINES'] == 'TENDON SUPRAPATELAR') ) ? ' - DICLOFENACO'     : '';
            //     $antTratMediAines.= ( isset($datos['reuma_730_AINES']) && ($datos['reuma_730_AINES'] == 'COSTO-CONDRITIS') ) ? ' - NAPROXENO'           : '';
            //     $antTratMediAines.= ( isset($datos['reuma_731_AINES']) && ($datos['reuma_731_AINES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - ETORICOXIB'  : '';
            //     $antTratMediAines.= ( isset($datos['reuma_732_AINES']) && ($datos['reuma_732_AINES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - CELECOXIB'   : '';
            //     $antTratMediAines.= ( isset($datos['reuma_733_AINES']) && ($datos['reuma_733_AINES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - OXAPROZINA'  : '';
            //     $antTratMediAines.= ( isset($datos['reuma_734_AINES']) && ($datos['reuma_734_AINES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - KETOPROFENO' : '';
                
            //     $antTratMediAnaOpi = '';
            //     $antTratMediAnaOpi.= ( isset($datos['reuma_728_APOIDES']) && ($datos['reuma_728_APOIDES'] == 'TENDON DE AQUILES') )   ? ' - ACETAMINOFÉN + CODEÍNA'     : '';
            //     $antTratMediAnaOpi.= ( isset($datos['reuma_729_APOIDES']) && ($datos['reuma_729_APOIDES'] == 'TENDON SUPRAPATELAR') ) ? ' - ACETAMINOFÉN + HIDROCODONA' : '';
            //     $antTratMediAnaOpi.= ( isset($datos['reuma_730_APOIDES']) && ($datos['reuma_730_APOIDES'] == 'COSTO-CONDRITIS') )     ? ' - ACETAMINOFÉN + TRAMADOL'    : '';
            
            //     $antTratMediAnaNoOpi = '';
            //     $antTratMediAnaNoOpi.= ( isset($datos['reuma_728_NO_OPOIDES']) && ($datos['reuma_728_NO_OPOIDES'] == 'ACETAMINOFÉN') ) ? ' - ACETAMINOFÉN' : '';
            
            //     $antTratMediAnaCortico = '';
            //     $antTratMediAnaCortico.= ( isset($datos['reuma_728_CORTICOIDES']) && ($datos['reuma_728_CORTICOIDES'] == 'TENDON DE AQUILES') ) ? ' - PREDNISOLONA'         : '';
            //     $antTratMediAnaCortico.= ( isset($datos['reuma_729_CORTICOIDES']) && ($datos['reuma_729_CORTICOIDES'] == 'TENDON SUPRAPATELAR') ) ? ' - DEFLAZACORT'        : '';
            //     $antTratMediAnaCortico.= ( isset($datos['reuma_730_CORTICOIDES']) && ($datos['reuma_730_CORTICOIDES'] == 'COSTO-CONDRITIS') ) ? ' - METILPREDNISOLONA'      : '';
            //     $antTratMediAnaCortico.= ( isset($datos['reuma_731_CORTICOIDES']) && ($datos['reuma_731_CORTICOIDES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - BETAMETASONA'   : '';
            //     $antTratMediAnaCortico.= ( isset($datos['reuma_732_CORTICOIDES']) && ($datos['reuma_732_CORTICOIDES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - DEXAMETASONA'   : '';
            //     $antTratMediAnaCortico.= ( isset($datos['reuma_733_CORTICOIDES']) && ($datos['reuma_733_CORTICOIDES'] == 'DOLOR GLUTEO ALTERNANTE') ) ? ' - HIDROCORTISONA' : '';
                
                
                
            //     // Lineas de registros a ingresar si estos poseen dosis
                
            //     $informDataTrataMedico = '';
            //     if ( (isset($datos['lf_1360']) && $datos['lf_1360'] != '') || (isset($datos['lf_1362']) && $datos['lf_1362'] != '') || ($antTratMediSuspenLeflu != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>LEFLUNOMIDA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['lf_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['lf_1362'].' '.$datos['lf_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenLeflu.'
            //                   </td>
            //               </tr>';
            //     }
            //     if ( (isset($datos['metotre_1360']) && $datos['metotre_1360'] != '') || (isset($datos['metotre_1362']) && $datos['metotre_1362'] != '') || ($antTratMediSuspenMetrote != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>METOTREXATE:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['metotre_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['metotre_1362'].' '.$datos['metotre_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenMetrote.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( (isset($datos['sulfa_1360']) && $datos['sulfa_1360'] != '') || (isset($datos['sulfa_1362']) && $datos['sulfa_1362'] != '') || ($antTratMediSuspenSulfasa != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>SULFASALAZINA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['sulfa_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['sulfa_1362'].' '.$datos['sulfa_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenSulfasa.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( (isset($datos['cloro_1360']) && $datos['cloro_1360'] != '') || (isset($datos['cloro_1362']) && $datos['cloro_1362'] != '') || ($antTratMediSuspenCloro != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>CLOROQUINA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['cloro_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['cloro_1362'].' '.$datos['cloro_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenCloro.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
                
            //     if ( (isset($datos['hidroxi_1360']) && $datos['hidroxi_1360'] != '') || (isset($datos['hidroxi_1362']) && $datos['hidroxi_1362'] != '') || ($antTratMediSuspenHidrox != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>HIDROXICLOROQUINA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['hidroxi_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['hidroxi_1362'].' '.$datos['hidroxi_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenHidrox.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( (isset($datos['azati_1360']) && $datos['azati_1360'] != '') || (isset($datos['azati_1362']) && $datos['azati_1362'] != '') || ($antTratMediSuspenAzatio != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>AZATIOPRINA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['azati_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['azati_1362'].' '.$datos['azati_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenAzatio.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( (isset($datos['peni_1360']) && $datos['peni_1360'] != '') || (isset($datos['peni_1362']) && $datos['peni_1362'] != '') || ($antTratMediSuspenPenici != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>PENICILAMINA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['peni_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['peni_1362'].' '.$datos['peni_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenPenici.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( (isset($datos['ciclo_1360']) && $datos['ciclo_1360'] != '') || (isset($datos['ciclo_1362']) && $datos['ciclo_1362'] != '') || ($antTratMediSuspenCiclo != '') )
            //     {
            //         $informDataTrataMedico.= '
            //              <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>CICLOSPORINA:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['ciclo_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['ciclo_1362'].' '.$datos['ciclo_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenCiclo.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( (isset($datos['sales_1360']) && $datos['sales_1360'] != '') || (isset($datos['sales_1362']) && $datos['sales_1362'] != '') || ($antTratMediSuspenSales != '') )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>SALES DE ORO:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Dosis:</strong> 
            //                     <br>'.$datos['sales_1360'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['sales_1362'].' '.$datos['sales_1365'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:60%">
            //                     <br><strong>Motivo de Suspensión:</strong> 
            //                     <br>'.$antTratMediSuspenSales.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($antTratMediAines) && $antTratMediAines != '' )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>AINES:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:80%">
            //                     <br>'.$antTratMediAines.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1300_AINES'].' '.$datos['reuma_1302_AINES'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($antTratMediAnaOpi) && $antTratMediAnaOpi != '' )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ANALGÉSICOS OPIODES:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:80%">
            //                     <br>'.$antTratMediAnaOpi.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1300_APOIDES'].' '.$datos['reuma_1301_APOIDES'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($antTratMediAnaNoOpi) && $antTratMediAnaNoOpi != '' )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>ANALGÉSICOS NO OPOIDES:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:80%">
            //                     <br>'.$antTratMediAnaNoOpi.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1300_NO_OPOIDES'].' '.$datos['reuma_1301_NO_OPOIDES'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($antTratMediAnaCortico) && $antTratMediAnaCortico != '' )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>CORTICOIDES:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:80%">
            //                     <br>'.$antTratMediAnaCortico.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                     <br><strong>Tiempo de Uso:</strong> 
            //                     <br>'.$datos['reuma_1300_CORTICOIDES'].' '.$datos['reuma_1301_CORTICOIDES'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['reuma_13011_CORTICOIDES']) && $datos['reuma_13011_CORTICOIDES'] != '' )
            //     {
            //         $informDataTrataMedico.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="4">
            //                       <br><strong>OBSERVACIONES:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$datos['reuma_13011_CORTICOIDES'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
                
            //     // Validacion para mostrar el bloque de uso de antecedentes médicos
            //     if ( $informDataTrataMedico != '' )
            //     {
            //         $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><br><strong>Tratamiento de DMARDs</strong>
            //                   </td>
            //               </tr>';
            
            //         $informDataHistory.= $informDataTrataMedico;

            //         $informDataHistory.= '
            //         </table>';
            //     }
            
            
            //     // Validacion para mostrar el bloque de uso de antecedentes médicos
            //     if ( $datos['reuma_1532_descripcion'] != '' )
            //     {
            //         $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><br><strong>TRATAMIENTO DE USO OTROS MEDICAMENTOS</strong>
            //                   </td>
            //               </tr>';
            
            //         $informDataHistory.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                       <br><strong>OBSERVACIONES:</strong> '.$datos['reuma_1532_descripcion'].'
            //                   </td>
            //               </tr>
            //         ';

            //         $informDataHistory.= '
            //         </table>';
            //     }
            
            
            
            
            //     // Variables para valores de ubicación de Síntomas Clínicos
            //     $sintomaArUbicArt= '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_642']) ) ? ' - '.$datos['reuma_642'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_643']) ) ? ' - '.$datos['reuma_643'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_644']) ) ? ' - '.$datos['reuma_644'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_645']) ) ? ' - '.$datos['reuma_645'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_646']) ) ? ' - '.$datos['reuma_646'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_647']) ) ? ' - '.$datos['reuma_647'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_648']) ) ? ' - '.$datos['reuma_648'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_649']) ) ? ' - '.$datos['reuma_649'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_650']) ) ? ' - '.$datos['reuma_650'] : '';
            //     $sintomaArUbicArt.= ( isset($datos['reuma_651']) ) ? ' - '.$datos['reuma_651'] : '';
                
            //     $sintomaArUbicArtri = '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_658']) ) ? ' - '.$datos['reuma_658'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_659']) ) ? ' - '.$datos['reuma_659'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_660']) ) ? ' - '.$datos['reuma_660'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_661']) ) ? ' - '.$datos['reuma_661'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_662']) ) ? ' - '.$datos['reuma_662'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_663']) ) ? ' - '.$datos['reuma_663'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_664']) ) ? ' - '.$datos['reuma_664'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_665']) ) ? ' - '.$datos['reuma_665'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_666']) ) ? ' - '.$datos['reuma_666'] : '';
            //     $sintomaArUbicArtri.= ( isset($datos['reuma_667']) ) ? ' - '.$datos['reuma_667'] : '';
                
            //     $sintomaEspDolLumTipo = '';
            //     $sintomaEspDolLumTipo.= ( isset($datos['reuma_705']) ) ? ' - '.$datos['reuma_705'] : '';
            //     $sintomaEspDolLumTipo.= ( isset($datos['reuma_706']) ) ? ' - '.$datos['reuma_706'] : '';
            //     $sintomaEspDolLumTipo.= ( isset($datos['reuma_707']) ) ? ' - '.$datos['reuma_707'] : '';
                
            //     $sintomaEspDolCevTipo = '';
            //     $sintomaEspDolCevTipo.= ( isset($datos['reuma_712']) ) ? ' - '.$datos['reuma_712'] : '';
            //     $sintomaEspDolCevTipo.= ( isset($datos['reuma_713']) ) ? ' - '.$datos['reuma_713'] : '';
            //     $sintomaEspDolCevTipo.= ( isset($datos['reuma_714']) ) ? ' - '.$datos['reuma_714'] : '';
                
            //     switch ($datos['reuma_810_3']) 
            //     {
            //         case "ar_manisclinicas_rigidez_años1":
            //             $datos['reuma_810_3'] = 'Años';
            //             break;
            //         case "ar_manisclinicas_rigidez_meses2":
            //             $datos['reuma_810_3'] = 'Meses';
            //             break;
            //     }
                
            //     $sintomaEspEstenso = '';
            //     $sintomaEspEstenso.= ( isset($datos['reuma_728']) ) ? ' - '.$datos['reuma_728'] : '';
            //     $sintomaEspEstenso.= ( isset($datos['reuma_729']) ) ? ' - '.$datos['reuma_729'] : '';
            //     $sintomaEspEstenso.= ( isset($datos['reuma_730']) ) ? ' - '.$datos['reuma_730'] : '';
            //     $sintomaEspEstenso.= ( isset($datos['reuma_731']) ) ? ' - '.$datos['reuma_731'] : '';
            //     $sintomaEspEstenso.= ( isset($datos['reuma_732']) ) ? ' - '.'Otro: '.$datos['reuma_677_5'] : '';
            
            //     $sintomaEspDedoSal = '';
            //     $sintomaEspDedoSal.= ( isset($datos['reuma_737']) ) ? ' - '.$datos['reuma_737'] : '';
            //     $sintomaEspDedoSal.= ( isset($datos['reuma_738']) ) ? ' - '.$datos['reuma_738'] : '';
            //     $sintomaEspDedoSal.= ( isset($datos['reuma_720_3']) ) ? '<br>Descripción: '.$datos['reuma_720_3'] : '';
                
            //     $sintomaEspUvei = '' ;
            //     $sintomaEspUvei.= ( isset($datos['reuma_7512']) && ($datos['reuma_7512'] == 'Si') ) ? 'Unilateral' : ( isset($datos['reuma_7512']) && ($datos['reuma_7512'] == 'No') ) ? 'Bilateral' : '';
                
            //     $sintomaEspUveiTipo = '';
            //     $sintomaEspUveiTipo.= ( isset($datos['reuma_728_1']) ) ? 'Anterior - ' : '';
            //     $sintomaEspUveiTipo.= ( isset($datos['reuma_729_1']) ) ? 'Interior - ' : '';
            //     $sintomaEspUveiTipo.= ( isset($datos['reuma_730_1']) ) ? 'Posterior - ' : '';
            //     $sintomaEspUveiTipo.= ( isset($datos['reuma_731_1']) ) ? 'Panuveitis' : '';
            //     //$sintomaEspUveiTipo.= ( isset($datos['reuma_728']) ) ? 'Anterior - ' : ''; // Comentadas por renombrado de campos
            //     //$sintomaEspUveiTipo.= ( isset($datos['reuma_729']) ) ? 'Interior - ' : ''; // Comentadas por renombrado de campos
            //     //$sintomaEspUveiTipo.= ( isset($datos['reuma_730']) ) ? 'Posterior - ' : ''; // Comentadas por renombrado de campos
            //     //$sintomaEspUveiTipo.= ( isset($datos['reuma_731']) ) ? 'Panuveitis' : ''; // Comentadas por renombrado de campos
            //     //$sintomaEspUveiTipo.= ( isset($datos['reuma_731']) ) ? 'Panuveitis' : '';
            
            //     $sintomaEspUveiPerfil = $datos['reuma_7512_12'];
                
            //     $sintomaEspUveiLimMovi = '';
            //     $sintomaEspUveiLimMovi = 'Columna Cervical: ';
            //     $sintomaEspUveiLimMovi.= ( isset($datos['reuma_755']) && ($datos['reuma_755'] == 'Si') ) ? 'Si<br>' : ( isset($datos['reuma_755']) && ($datos['reuma_755'] == 'No') ) ? 'No<br>' : '<br>';
            //     $sintomaEspUveiLimMovi = 'Columna Lumbar: ';
            //     $sintomaEspUveiLimMovi.= ( isset($datos['reuma_744']) && ($datos['reuma_744'] == 'Si') ) ? 'Si<br>' : ( isset($datos['reuma_744']) && ($datos['reuma_744'] == 'No') ) ? 'No<br>' : '<br>';
            //     // Revisar Columna Lumbar
            //     $sintomaEspUveiLimMovi = 'Columna Toráxica: ';
            //     $sintomaEspUveiLimMovi.= ( isset($datos['reuma_7461']) && ($datos['reuma_7461'] == 'Si') ) ? 'Si<br>' : ( isset($datos['reuma_7461']) && ($datos['reuma_7461'] == 'No') ) ? 'No' : '<br>';
                
            //     $sintomaEspPsoLocal = '';
            //     $sintomaEspPsoLocal.= ( isset($datos['reuma_757']) ) ? ' - '.$datos['reuma_757'] : '';
            //     $sintomaEspPsoLocal.= ( isset($datos['reuma_758']) ) ? ' - '.$datos['reuma_758'] : '';
            //     $sintomaEspPsoLocal.= ( isset($datos['reuma_759']) ) ? ' - '.$datos['reuma_759'] : '';
            //     $sintomaEspPsoLocal.= ( isset($datos['reuma_760']) ) ? ' - '.$datos['reuma_760'] : '';
                
            //     $sintomaEspUnas = '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_765']) ) ? ' - '.$datos['reuma_765'] : '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_766']) ) ? ' - '.$datos['reuma_766'] : '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_767']) ) ? ' - '.$datos['reuma_767'] : '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_768']) ) ? ' - '.$datos['reuma_768'] : '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_769']) ) ? ' - '.$datos['reuma_769'] : '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_770']) ) ? ' - '.$datos['reuma_770'] : '';
            //     $sintomaEspUnas.= ( isset($datos['reuma_771']) ) ? ' - '.$datos['reuma_771'] : '';
                
            //     $sintomaDermaDebiGrad = '';
            //     $sintomaDermaDebiGrad.= ( isset($datos['reuma_865']) && ($datos['reuma_865'] == '1') ) ? 'Grado 1<br>' : '';
            //     $sintomaDermaDebiGrad.= ( isset($datos['reuma_866']) && ($datos['reuma_866'] == '2') ) ? 'Grado 2<br>' : '';
            //     $sintomaDermaDebiGrad.= ( isset($datos['reuma_867']) && ($datos['reuma_867'] == '3') ) ? 'Grado 3<br>' : '';
            //     $sintomaDermaDebiGrad.= ( isset($datos['reuma_868']) && ($datos['reuma_868'] == '4') ) ? 'Grado 4<br>' : '';
            //     $sintomaDermaDebiGrad.= ( isset($datos['reuma_869']) && ($datos['reuma_869'] == '5') ) ? 'Grado 5' : '';
                
            
            
            //     // Bloque de validaciones para Sintomas clinicos de AR - Solo cargan respuestas en SI
            //     $informDataEnferActualAr = '';
                
            //     if ( isset($datos['reuma_639']) && $datos['reuma_639'] == 'Si' )
            //     {
            //         $informDataEnferActualAr.= '
                        
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; width:15%"  colspan="1">
            //                       <br><strong>ARTRALGIA:</strong>
            //                   </td>
            //                   <td style="text-align: justify; width:85%"  colspan="3">
            //                       <br>'.$datos['reuma_639'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo:</strong> '.$datos['reuma_641'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br><strong>Ubicación:</strong> 
            //                     <br>'.$sintomaArUbicArt.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['reuma_654']) && $datos['reuma_654'] == 'Si' )
            //     {
            //         $informDataEnferActualAr.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>ARTRITIS (SINOVITIS):</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_654'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Simetría:</strong> '.$datos['reuma_656'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br><strong>Ubicación:</strong> 
            //                     <br>'.$sintomaArUbicArtri.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['reuma_670']) && $datos['reuma_670'] == 'Si' )
            //     {
            //         $informDataEnferActualAr.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>RIGIDEZ MATINAL:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_670'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$datos['reuma_677'].' Minutos
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['reuma_675']) && $datos['reuma_675'] == 'Si' )
            //     {
            //         $informDataEnferActualAr.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>NÓDULO REUMATOIDE:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_675'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br><strong>Descripción:</strong>
            //                     <br>'.$datos['reuma_677_1'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['arOtro_3']) && $datos['arOtro_3'] != '' )
            //     {
            //         $informDataEnferActualAr.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>OTRO:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$datos['arOtro_3'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
                
            
            //     // Bloque de exámen físico
            //     if( $examenFisOtros != '' )
            //     {
            //         $informDataHistory.= '
            //        <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 10%">
            //                 <td style="text-align: center;" >

            //                 </td>
            //             </tr>
            //             <tr style="font-size: 70%">
            //                 <td style="text-align: center;" >
            //                     <br><strong>Exámen Fisico</strong>
            //                 </td>
            //             </tr>
            //               <tr>
            //                <td style="border: 0,5px solid black;" >
            //                     '.$examenFisOtros.'
            //                 </td>
            //             </tr>
            //         </table>
            //       ';
            //     }
                
            
                
            //     // Bloque de Síntomas Clinicos - Enfermedad Actual
            //     $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 70%">
            //                 <td style="text-align: center;" >
            //                     <br><br><strong>Enfermedad Actual</strong>
            //                 </td>
            //             </tr>
            //         </table>
            //     ';
                
            //     if ( $informDataEnferActualAr != '' )
            //     {
            //         $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><strong>Síntomas Clínicos: AR</strong>
            //                   </td>
            //               </tr>
            //         ';
                
            //         $informDataHistory.= $informDataEnferActualAr;
                    
            //         $informDataHistory.= '
            //         </table>';    
            //     }
                
                
            //     // Bloque de validaciones para Sintomas clinicos de Espondilo
            //     $informDataEnferActualEspon = '';
                    
            //     if ( isset($datos['reuma_703']) && $datos['reuma_703'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>DOLOR LUMBAR:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_703'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo de Dolor:</strong> 
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br><strong>Ubicación:</strong> 
            //                     <br>'.$sintomaEspDolLumTipo.'
            //                   </td>
            //               </tr>
            //         ';
            //     }   
            //     if ( isset($datos['reuma_710']) && $datos['reuma_710'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>DOLOR CERVICAL:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_710'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo de Dolor:</strong> 
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br><strong>Ubicación:</strong> 
            //                     <br>'.$sintomaEspDolCevTipo.'
            //                   </td>
            //               </tr>
            //         ';
            //     }   
            //     if ( isset($datos['reuma_717']) && $datos['reuma_717'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>RIGIDEZ ESPINAL MATUTINO:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_717'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo de Dolor:</strong> 
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br><strong>Tiempo:</strong> 
            //                     <br>'.$datos['reuma_720_1'].' '.$datos['reuma_810_3'].' 
            //                   </td>
            //               </tr>
            //         ';
            //     }   
            //     if ( isset($datos['reuma_722']) && $datos['reuma_722'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>DOLOR NOCTURNO ESPINAL:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$datos['reuma_722'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }   
            //     if ( isset($datos['reuma_726']) && $datos['reuma_726'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>ENTESOPATÍA:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_726'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$sintomaEspEstenso.' 
            //                   </td>
            //               </tr>
            //         ';
            //     }   
            //     if ( isset($datos['reuma_735']) && $datos['reuma_735'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>DEDO SALCHICHA:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_735'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo de Dolor:</strong> 
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br>'.$sintomaEspDedoSal.'
            //                   </td>
            //               </tr>
            //         ';
            //     }  
            //     if ( isset($datos['reuma_741']) && $datos['reuma_741'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>UVEITIS:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_741'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo: </strong> '.$sintomaEspUvei.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br>'.$sintomaEspUveiTipo.'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Perfil Infeccioso: </strong> '.$sintomaEspUveiPerfil.'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br><strong>Dx:</strong> '.$datos['reuma_743'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }  
            //     if ( isset($datos['reuma_746']) && $datos['reuma_746'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>LIMITACIONES DE MOVIMIENTOS ESPINALES:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_746'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Tipo de Dolor:</strong> 
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                         <br>'.$sintomaEspUveiLimMovi.'
            //                   </td>
            //               </tr>
            //         ';
            //     }  
            //     if ( isset($datos['reuma_754']) && $datos['reuma_754'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>LESIONES PSORIASIS:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_754'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Biopsia:</strong> '.$datos['reuma_7512_biopsia'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                         <br><strong>Localización de la Biopsia</strong>
            //                         <br>'.$sintomaEspPsoLocal.'
            //                   </td>
            //               </tr>
            //         ';
            //     }  
            //     if ( isset($datos['reuma_763']) && $datos['reuma_763'] == 'Si' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>UÑAS:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>'.$datos['reuma_763'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$sintomaEspUnas.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['espondiloOtro_3']) && $datos['espondiloOtro_3'] != '' )
            //     {
            //         $informDataEnferActualEspon.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="1">
            //                       <br><strong>OTRO:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="3">
            //                       <br>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                     <br>'.$datos['espondiloOtro_3'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
                
            
            //     if ( $informDataEnferActualEspon != '' )
            //     {
            //         $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><strong>Síntomas Clínicos: Espóndilo</strong>
            //                   </td>
            //               </tr>
            //               ';
            
            //         $informDataHistory.= $informDataEnferActualEspon;

            //         $informDataHistory.= '
            //             </table>
            //         ';
            //     }
                
                
                
                
            //     // Bloque de validaciones para Sintomas clinicos de Dermatopolimiositis
            //     $informDataEnferActualDermato = '';    
            
            //     if ( isset($datos['reuma_863']) && $datos['reuma_863'] == 'Si' )
            //     {
            //         $informDataEnferActualDermato.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify;"  colspan="2">
            //                       <br><strong>DEBILIDAD SIMÉTRICA CINTURA ESCAPULAR O PÉLVICA:</strong>
            //                   </td>
            //                   <td style="text-align: justify;"  colspan="2">
            //                       <br>'.$datos['reuma_863'].'
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>Grado:</strong> 
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:85%">
            //                     <br>'.$sintomaDermaDebiGrad.'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( ( isset($datos['reuma_872']) && $datos['reuma_872'] == 'Si' ) || ( isset($datos['reuma_877']) && $datos['reuma_877'] == 'Si' ) )
            //     {
            //         $informDataEnferActualDermato.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; width:50%">
            //                       <br><br><strong>PÁRPADOS SUPERIORES COLOR PÚRPURA:</strong>
            //                   </td>
            //                   <td style="text-align: justify; width:50%">
            //                       <br><br><strong>PÁPULA DE GOTTRON:</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; width:50%">
            //                       <br>'.$datos['reuma_872'].'
            //                   </td>
            //                   <td style="text-align: justify; width:50%">
            //                       <br>'.$datos['reuma_877'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
            //     if ( isset($datos['dermatoOtro_3']) && $datos['dermatoOtro_3'] != '' )
            //     {
            //         $informDataEnferActualDermato.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; width:15%">
            //                       <br><br><strong>Otro:</strong>
            //                   </td>
            //                   <td style="text-align: justify; width:85%">
            //                       <br><br>'.$datos['dermatoOtro_3'].'
            //                   </td>
            //               </tr>
            //         ';
            //     }
                
            
            
            //     if ( $informDataEnferActualDermato != '' )
            //     {
            //         $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><strong>Síntomas Clínicos: Dermatopolimiositis</strong>
            //                   </td>
            //               </tr>
            //               ';
            
            //         $informDataHistory.= $informDataEnferActualDermato;
                
                
            //         $informDataHistory.= '
            //         </table>
            //         ';
            //     }
                
                
            //     // Bloque de exámenes 
            //     /*$informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><br><strong>Exámenes</strong>
            //                   </td>
            //               </tr>
            //         </table>
            //     ';*/
                
            //     $informDataHistoryExam = '';
            
            //     // Verificación de campos vacíos
            //     if ( $datos['paraclinicos']['hemografia'][0]['resultado'] != '' || 
            //          $datos['paraclinicos']['hemografia'][0]['punto_corte'] != '' || 
            //          $datos['paraclinicos']['hemografia'][3]['resultado'] != '' || 
            //          $datos['paraclinicos']['hemografia'][4]['punto_corte'] != '' || 
            //          $datos['reuma_1666'] != '' || 
            //          $datos['reuma_1665'] != '' || 
            //          $datos['paraclinicos']['hemografia'][10]['punto_corte'] != '' ||
            //          $datos['paraclinicos']['hemografia'][9]['resultado'] != '' ||
            //          $datos['paraclinicos']['hemografia'][8]['fecha'] != '' ||
            //          $datos['paraclinicos']['hemografia'][9]['punto_corte'] != ''
            //     )
            //     {
            //         $informDataHistoryExam.= '
            //             <table style="padding: 2px; width: 100%">
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; width:100%" colspan="4">
            //                           <br><strong>Hemograma:</strong>
            //                       </td>
            //                   </tr>
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:12%">
            //                           <!--<br><strong>Hb g/dL:</strong> '.htmlentities($datos['paraclinicos']['hemografia'][0]['resultado']).'-->
            //                           <br><strong>Hemoglobina g/dL:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][0]['resultado']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][0]['fecha']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:11%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][0]['punto_corte']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:13%">
            //                           <br><strong>Leucocitos:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][3]['resultado']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][0]['fecha']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:11%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][4]['punto_corte']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:12%">
            //                           <br><strong>VSG mm/hora:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][9]['resultado']).' '.htmlentities($datos['reuma_1666']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][0]['fecha']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:11%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][9]['punto_corte']).' '.htmlentities($datos['paraclinicos']['hemografia'][10]['punto_corte']).'
            //                       </td>
            //                   </tr>
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:12%">
            //                           <br><strong>VCM:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][10]['resultado']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][1]['fecha']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:11%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][10]['punto_corte']).'
            //                       </td>
                                  
            //                       <td style="text-align: justify; border: 0,5px solid black; width:13%">
            //                           <br><strong>HCM:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][11]['resultado']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][0]['fecha']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:11%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][11]['punto_corte']).'
            //                       </td>
            //                   </tr>
            //                   <!--<tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>VSG mm/hora:</strong> 
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Resultado:</strong> '.htmlentities($datos['paraclinicos']['hemografia'][9]['resultado']).' '.htmlentities($datos['reuma_1666']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Fecha:</strong> '.htmlentities($datos['paraclinicos']['hemografia'][8]['fecha']).' '.htmlentities($datos['reuma_1665']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Punto de Corte:</strong> '.htmlentities($datos['paraclinicos']['hemografia'][9]['punto_corte']).' '.htmlentities($datos['paraclinicos']['hemografia'][10]['punto_corte']).'
            //                       </td>
            //                   </tr>-->
            //             </table>
            //         ';
            //     }
            
            //     if ( $datos['reuma_1660'] != '' || 
            //          $datos['reuma_1660-1'] != '' || 
            //          $datos['reuma_1661_2'] != '' || 
            //          $datos['reuma_1662_2'] != '' || 
            //          $datos['reuma_1663_2'] != '' || 
            //          $datos['reuma_1655'] != '' || 
            //          $datos['reuma_1656'] != '' || 
            //          $datos['reuma_1657'] != ''
            //     )
            //     {
            //         $informDataHistoryExam.= '
            //             <table style="padding: 2px; width: 100%">
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; width:100%" colspan="5">
            //                           <br><strong>Uroanálisis:</strong>
            //                       </td>
            //                   </tr>
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Fecha:</strong> '.htmlentities($datos['reuma_1660']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Resultado:</strong> '.htmlentities($datos['reuma_1660-1']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Proteínas:</strong> '.htmlentities($datos['reuma_1661_2']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Hematies:</strong> '.htmlentities($datos['reuma_1662_2']).'
            //                       </td>
            //                   </tr>
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Bacterias:</strong> '.htmlentities($datos['reuma_1663_2']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Fecha TFG:</strong> '.htmlentities($datos['reuma_1655']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>TFG:</strong> '.htmlentities($datos['reuma_1656']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:25%">
            //                           <br><strong>Punto de Corte:</strong> '.htmlentities($datos['reuma_1657']).'
            //                       </td>
            //                   </tr>
            //             </table>
            //         ';
            //     }
                
            //     if ( $datos['reuma_1671'] != '' || 
            //          $datos['reuma_1670'] != '' ||
            //          $datos['paraclinicos']['hemografia'][33]['punto_corte'] != '' ||
            //          $datos['reuma_1681'] != '' || 
            //          $datos['reuma_1680'] != '' || 
            //          $datos['paraclinicos']['hemografia'][13]['punto_corte'] != '' || 
            //          $datos['reuma_1696'] != '' || 
            //          $datos['reuma_1695'] != '' || 
            //          $datos['paraclinicos']['hemografia'][16]['punto_corte'] != '' || 
            //          $datos['reuma_1706'] != '' || 
            //          $datos['reuma_1705'] != '' || 
            //          $datos['paraclinicos']['hemografia'][18]['punto_corte'] != ''
            //     )
            //     {
            //         $informDataHistoryExam.= '
            //             <table style="padding: 2px; width: 100%">
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; width:100%" colspan="5">
            //                           <br><strong>Rectantes / Hepático / Renal:</strong>
            //                       </td>
            //                   </tr>
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>PCR mg/dL:</strong> <br>'.htmlentities($datos['reuma_1671']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['reuma_1670']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][33]['punto_corte']).'
            //                       </td>
                                  
                                  
                                  
                              
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>TGP - ALT U/L:</strong> <br>'.htmlentities($datos['reuma_1681']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['reuma_1680']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][13]['punto_corte']).'
            //                       </td>
            //                 </tr>
            //                 <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Fosfatasa Alcalina Ui/L:</strong> <br>'.htmlentities($datos['reuma_1696']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['reuma_1695']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][16]['punto_corte']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Creatinina mg/dL:</strong> <br>'.htmlentities($datos['reuma_1706']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['reuma_1705']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Punto de Corte:</strong> <br>'.htmlentities($datos['paraclinicos']['hemografia'][18]['punto_corte']).'
            //                       </td>
            //                   </tr>
            //             </table>
            //         ';
            //     }
                
            //     if ( $datos['reuma_1751'] != '' || 
            //          $datos['reuma_1752'] != '' || 
            //          $datos['reuma_1756'] != '' || 
            //          $datos['reuma_1757'] != ''
            //     )
            //     {
            //         $informDataHistoryExam.= '
            //             <table style="padding: 2px; width: 100%">
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                       <td style="text-align: justify; width:100%" colspan="5">
            //                           <br><strong>Inmunológicos Diagnóstico:</strong>
            //                       </td>
            //                   </tr>
            //                   <tr style="font-size: 50%; line-height: 9px">
            //                     <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>FR:</strong> '.htmlentities($datos['reuma_1751']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['reuma_1750']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Punto de Corte:</strong> '.htmlentities($datos['reuma_1752']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>antiCCP:</strong> '.htmlentities($datos['reuma_1756']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:10%">
            //                           <br><strong>Fecha:</strong> <br>'.htmlentities($datos['reuma_1750']).'
            //                       </td>
            //                       <td style="text-align: justify; border: 0,5px solid black; width:20%">
            //                           <br><strong>Punto de Corte:</strong> '.htmlentities($datos['reuma_1757']).'
            //                       </td>
            //                   </tr>
            //             </table>
            //             ';
            //     }
                
            //     // Verificación para ingreso de datos
            //     if ( $informDataHistoryExam != '' )
            //     {
            //         // Bloque de exámenes 
            //         $informDataHistory.= '
            //             <table style="padding: 2px; width: 100%">
            //                   <tr style="font-size: 60%; line-height: 9px">
            //                       <td style="text-align: center;"  colspan="4">
            //                           <br><br><strong>Exámenes</strong>
            //                       </td>
            //                   </tr>
            //             </table>
            //         ';
                    
            //         $informDataHistory.= $informDataHistoryExam;
            //     }
            
            
            //     // Bloque de Imágenes 
            //     $bloqueRx = '';
            
            //     // RX de Manos - Validaicon para seleccion de campo SI
            //     if ( htmlentities($datos['reuma_331_1']) == 'Si') // Normal en SI: Por defecto Sin Erosiones
            //     {
            //         $bloqueRx.= '
            //           <td style="text-align: justify; border: 0,5px solid black; width:33%">
            //               <br><strong>Rx de Manos: </strong> Sin Erosiones
            //           </td>';
            //     }
            //     else // Normal en No
            //     {
            //         if ( htmlentities($datos['reuma_333']) == 'Si' ) // Erosiones en Si
            //         {
            //             $bloqueRx.= '
            //               <td style="text-align: justify; border: 0,5px solid black; width:33%">
            //                   <br><strong>Rx de Manos: </strong> Con Erosiones
            //               </td>';
            //         }


            //         if ( htmlentities($datos['reuma_333']) == 'No' ) // Erosiones en No
            //         {
            //             $bloqueRx.= '
            //               <td style="text-align: justify; border: 0,5px solid black; width:33%">
            //                   <br><strong>Rx de Manos: </strong> Sin Erosiones
            //               </td>';
            //         }
            //     }
                
            //     // RX de Pies - Validaicon para seleccion de campo SI
            //     if ( htmlentities($datos['reuma_341_1']) == 'Si') // Normal en SI: Por defecto Sin Erosiones
            //     {
            //         $bloqueRx.= '
            //           <td style="text-align: justify; border: 0,5px solid black; width:33%">
            //               <br><strong>Rx de Pies: </strong> Sin Erosiones
            //           </td>';
            //     }
            //     else // Normal en No
            //     {
            //         if ( htmlentities($datos['reuma_343']) == 'Si' ) // Erosiones en Si
            //         {
            //             $bloqueRx.= '
            //               <td style="text-align: justify; border: 0,5px solid black; width:33%">
            //                   <br><strong>Rx de Pies: </strong> Con Erosiones
            //               </td>';
            //         }

            //         if ( htmlentities($datos['reuma_343']) == 'No' ) // Erosiones en No
            //         {
            //             $bloqueRx.= '
            //               <td style="text-align: justify; border: 0,5px solid black; width:33%">
            //                   <br><strong>Rx de Pies: </strong> Sin Erosiones
            //               </td>';
            //         }
            //     }
            
            //     // Columna Lumbar - Validaicon para seleccion de campo SI
            //     if ( htmlentities($datos['reuma_363_1']) == 'Si') // Normal en SI: Por defecto Sin Erosiones
            //     {
            //         $bloqueRx.= '
            //           <td style="text-align: justify; border: 0,5px solid black; width:34%">
            //               <br><strong>Columna Lumbar: </strong> Sin Erosiones
            //           </td>';
            //     }
            //     else // Normal en No
            //     {
            //         if ( htmlentities($datos['reuma_365']) == 'Si' ) // Erosiones en Si
            //         {
            //             $bloqueRx.= '
            //               <td style="text-align: justify; border: 0,5px solid black; width:34%">
            //                   <br><strong>Columna Lumbar: </strong> Con Erosiones
            //               </td>';
            //         }
            //         if ( htmlentities($datos['reuma_365']) == 'No' ) // Erosiones en No
            //         {
            //             $bloqueRx.= '
            //               <td style="text-align: justify; border: 0,5px solid black; width:34%">
            //                   <br><strong>Columna Lumbar: </strong> Sin Erosiones
            //               </td>';
            //         }
            //     }
            
            //     // Validaicón para selección de bloque "No reporta"
            //     $noReportaImagenes = ($datos['enfermedadActual']['imagenes'] == 'No aplica') ? 'SI' : 'NO';
            //     //if ( $datos['enfermedadActual']['imagenes'] == "" ) 
            //     //{
            //     //}
            
            //     if ( $noReportaImagenes == "NO" )
            //     {
            //         // Verificación para ingreso de bloque de imagenes cuando exista información de ingreso
            //         if ( $bloqueRx != '' )
            //         {
            //             $informDataHistory.= '
            //                 <table style="padding: 2px; width: 100%">
            //                       <tr style="font-size: 60%; line-height: 9px">
            //                           <td style="text-align: center;"  colspan="4">
            //                               <br><br><strong>Imágenes</strong> '.$datos['enfermedadActual']['imagenes'].'
            //                           </td>
            //                       </tr>
            //                 </table>
            //                 <table style="padding: 2px; width: 100%">
            //                       <tr style="font-size: 50%; line-height: 9px">
            //                         '.$bloqueRx.'
            //                       </tr>
            //                 </table>
            //             ';
            //         }
            //     }
            //     else
            //     {
                    
            //     }


            
            //     // Fin Bloque Imágenes
            
            //     $informDataHistory.= '        
            //         <table style="padding: 2px; width: 100%">
            //               <tr style="font-size: 60%; line-height: 9px">
            //                   <td style="text-align: center;"  colspan="4">
            //                       <br><br><strong>Subjetivo</strong>
            //                   </td>
            //               </tr>
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:100%">
            //                       <br><strong>Descripción:</strong> '.htmlentities($datos['reuma_5_11111']).'
            //                   </td>
            //               </tr>

            //         </table>
            //     '; 
            
            //     // Bloque de clinimetrias
            //     $informDataHistory.= '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="font-size: 70%; line-height: 8px">
            //                 <td style="text-align: center;"  colspan="4">
            //                     <br><br><strong>Clinimetrías:</strong>
            //                 </td>
            //             </tr>
            //             <tr style="font-size: 60%; line-height: 8px">
            //               <td style="text-align: left;"  colspan="">
            //                   <br><br><strong>AR:</strong>
            //               </td>
            //           </tr>
            //               <!--<tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; width:50%" colspan="2">
            //                       <br><strong>DAS28:</strong>
            //                   </td>
            //                   <td style="text-align: justify; width:50%" colspan="2">
            //                       <br><strong>HAQ:</strong>
            //                   </td>
            //               </tr>-->
            //               <tr style="font-size: 50%; line-height: 9px">
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>DAS28:</strong> '.$datos['clinimetria'][0]['resultado'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //                     <br><strong>OBSERVACIONES:</strong> '.$datos['clinimetria'][0]['interpretacion'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                     <br><strong>HAQ:</strong> '.$datos['clinimetria'][2]['resultado'].'
            //                   </td>
            //                   <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //                     <br><strong>OBSERVACIONES:</strong> '.$datos['clinimetria'][2]['interpretacion'].'
            //                   </td>
            //                   </tr>';

            //             if(!empty($datos['clinimetria'][1]['resultado']) || !empty($datos['clinimetria'][3]['resultado'])){
            //                 $informDataHistory.= '
            //                 <tr style="font-size: 50%; line-height: 9px">
            //                     <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                         <br><strong>CDAI:</strong> '.(empty($datos['clinimetria'][1]['resultado']) ? '---' : htmlentities($datos['clinimetria'][1]['resultado'])).'
            //                     </td>
            //                     <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //                         <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][1]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][1]['interpretacion'])).'
            //                     </td>
            //                     <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //                         <br><strong>HAQDI:</strong> '.(empty($datos['clinimetria'][3]['resultado']) ? '---' : htmlentities($datos['clinimetria'][3]['resultado'])).'
            //                     </td>
            //                     <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //                         <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][3]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][3]['interpretacion'])).'
            //                     </td>
            //             </tr>';

            //             }

            //             if(!empty($datos['clinimetria'][4]['resultado'])){

            //                 $informDataHistory.= '
            //             <tr style="font-size: 50%; line-height: 9px">
            //                 <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //                   <br><strong>EVA ESCALA CONTEO ARTICULACIONES:</strong> '.(empty($datos['clinimetria'][4]['resultado']) ? '---' : htmlentities($datos['clinimetria'][4]['resultado'])).'
            //                 </td>
            //                 <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //                   <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][4]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][4]['interpretacion'])).'
            //                 </td>
            //               </tr>
            //         ';
            
            //     }

            //     if(!empty($datos['clinimetria'][5]['resultado']) || !empty($datos['clinimetria'][6]['resultado']) || !empty($datos['clinimetria'][7]['resultado']) || !empty($datos['clinimetria'][8]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 60%; line-height: 9px">
            //         <td style="text-align: left;"  colspan="">
            //             <br><br><strong>OA:</strong>
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][5]['resultado']) || !empty($datos['clinimetria'][6]['resultado'])){

            //         $informDataHistory.= '                            
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>WOMAC:</strong> '.(empty($datos['clinimetria'][5]['resultado']) ? '---' : htmlentities($datos['clinimetria'][5]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][5]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][5]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>LESQUESNE:</strong> '.(empty($datos['clinimetria'][6]['resultado']) ? '---' : htmlentities($datos['clinimetria'][6]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][6]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][6]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][7]['resultado']) || !empty($datos['clinimetria'][8]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>EVA ESCALA VISUAL:</strong> '.(empty($datos['clinimetria'][7]['resultado']) ? '---' : htmlentities($datos['clinimetria'][7]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][7]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][7]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>CONTEOS ARTICULARES:</strong> '.(empty($datos['clinimetria'][8]['resultado']) ? '---' : htmlentities($datos['clinimetria'][8]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][8]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][8]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][9]['resultado']) || !empty($datos['clinimetria'][10]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 60%; line-height: 9px">
            //         <td style="text-align: left;"  colspan="">
            //             <br><br><strong>LUPUS:</strong>
            //         </td>
            //     </tr>';                           

            //     }

            //     if(!empty($datos['clinimetria'][9]['resultado']) || !empty($datos['clinimetria'][10]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>SLEDAI:</strong> '.(empty($datos['clinimetria'][9]['resultado']) ? '---' : htmlentities($datos['clinimetria'][9]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][9]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][9]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>BILAG:</strong> '.(empty($datos['clinimetria'][10]['resultado']) ? '---' : htmlentities($datos['clinimetria'][10]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][10]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][10]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][11]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //           <br><strong>SLICC/ACR INDEX:</strong> '.(empty($datos['clinimetria'][11]['resultado']) ? '---' : htmlentities($datos['clinimetria'][11]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][11]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][11]['interpretacion'])).'
            //         </td>
                   
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][13]['resultado']) || !empty($datos['clinimetria'][14]['resultado']) || !empty($datos['clinimetria'][15]['resultado']) || !empty($datos['clinimetria'][16]['resultado'])){

            //         $informDataHistory.= '
            //         <tr style="font-size: 60%; line-height: 9px">
            //           <td style="text-align: left;"  >
            //               <br><br><strong>SPA:</strong>
            //           </td>
            //         </tr>';
                    
            //     }

            //     if(!empty($datos['clinimetria'][13]['resultado']) || !empty($datos['clinimetria'][14]['resultado'])){

            //         $informDataHistory.= '
                    
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //             <br><strong>BASDAI:</strong> '.(isset($datos['clinimetria'][13]['resultado'] ) && $datos['clinimetria'][13]['resultado'] != '0'  && $datos['clinimetria'][13]['resultado'] != 0 ? htmlentities($datos['clinimetria'][13]['resultado'])  : '---').'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][13]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][13]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>ASDAS:</strong> '.(empty($datos['clinimetria'][14]['resultado']) ? '---' : htmlentities($datos['clinimetria'][14]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][14]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][14]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][15]['resultado']) || !empty($datos['clinimetria'][16]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>BASFI:</strong> '.(empty($datos['clinimetria'][15]['resultado']) ? '---' : htmlentities($datos['clinimetria'][15]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][15]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][15]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>PASI90:</strong> '.(empty($datos['clinimetria'][16]['resultado']) ? '---' : htmlentities($datos['clinimetria'][16]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][16]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][16]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][17]['resultado'])){

            //         $informDataHistory.= '
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //           <br><strong>MOSES:</strong> '.(empty($datos['clinimetria'][17]['resultado']) ? '---' : htmlentities($datos['clinimetria'][17]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][17]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][17]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }


            //     if(!empty($datos['clinimetria'][18]['resultado']) || !empty($datos['clinimetria'][19]['resultado'])){

            //         $informDataHistory.= '
            //         <tr style="font-size: 60%; line-height: 9px">
            //           <td style="text-align: left;"  colspan="">
            //               <br><br><strong>ESCLERODERMIA:</strong>
            //           </td>
            //       </tr>
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>RODNAN:</strong> '.(empty($datos['clinimetria'][18]['resultado']) ? '---' : htmlentities($datos['clinimetria'][18]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][18]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][18]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>EScSG ÍNDICE DE ACTIVIDAD:</strong> '.(empty($datos['clinimetria'][19]['resultado']) ? '---' : htmlentities($datos['clinimetria'][19]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][19]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][19]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][20]['resultado'])){

            //         $informDataHistory.= '
            //         <tr style="font-size: 60%; line-height: 9px">
            //           <td style="text-align: left;"  colspan="">
            //               <br><br><strong>SJÖGREN:</strong>
            //           </td>
            //       </tr>
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //           <br><strong>ESSDAI:</strong> '.(empty($datos['clinimetria'][20]['resultado']) ? '---' : htmlentities($datos['clinimetria'][20]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:50%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][20]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][20]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     if(!empty($datos['clinimetria'][21]['resultado']) || !empty($datos['clinimetria'][22]['resultado'])){

            //         $informDataHistory.= '
            //         <tr style="font-size: 60%; line-height: 9px">
            //           <td style="text-align: left; width:30%"  colspan="" >
            //               <br><br><strong>DERMATOPOLIMIOSITIS:</strong>
            //           </td>
            //       </tr>
            //     <tr style="font-size: 50%; line-height: 9px">
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>FUERZA MUSCULAR:</strong> '.(empty($datos['clinimetria'][21]['resultado']) ? '---' : htmlentities($datos['clinimetria'][21]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][21]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][21]['interpretacion'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:15%">
            //           <br><strong>FATIGA:</strong> '.(empty($datos['clinimetria'][22]['resultado']) ? '---' : htmlentities($datos['clinimetria'][22]['resultado'])).'
            //         </td>
            //         <td style="text-align: justify; border: 0,5px solid black; width:35%">
            //           <br><strong>OBSERVACIONES:</strong> '.(empty($datos['clinimetria'][22]['interpretacion']) ? 'NO REPORTA' : htmlentities($datos['clinimetria'][22]['interpretacion'])).'
            //         </td>
            //     </tr>';

            //     }

            //     $informDataHistory.= '</table>';

            // //       '
                  
            // //       
                  
                  

            //     //print_r($datos);
            
            //     // Arreglos para datos de DAS 28
            //     $arregloDASDol = explode(',', $datos['ptnhumanoDolorosa']);
            //     $arregloDASInf = explode(',', $datos['ptnhumanoInflamada']);
                
            //     $contDol = sizeof($arregloDASDol);
            
            //     $opcionesDol = '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="line-height: 9px">
            //     ';
                
            //     $operacionDol = 0;
            
            //     // Recorrido para reemplazo de articulaciones dolorosas
            //     for ( $i = 0; $i < $contDol; $i++ )
            //     {
            //         switch ( $arregloDASDol[$i] )
            //         {
            //             case "hombroI": $arregloDASDol[$i] = 'Hombro Izquierdo'; break;
            //             case "codoI":   $arregloDASDol[$i] = 'Codo Izquierdo'; break;
            //             case "munecaI": $arregloDASDol[$i] = 'Muñeca Izquierdo'; break;
            //             case "rodillaI":$arregloDASDol[$i] = 'Rodilla Izquierdo'; break;
            //             case "hombroD": $arregloDASDol[$i] = 'Hombro Derecho'; break;
            //             case "codoD":   $arregloDASDol[$i] = 'Codo Derecho';   break;
            //             case "munecaD": $arregloDASDol[$i] = 'Muñeca Derecho'; break;
            //             case "rodillaD":$arregloDASDol[$i] = 'Rodilla Derecho';break;
            //             case "1MFPI":   $arregloDASDol[$i] = '1MCF Izquierda'; break;
            //             case "1IFPI":   $arregloDASDol[$i] = '1IFD Izquierda'; break;
            //             case "2MFPI":   $arregloDASDol[$i] = '2MCF Izquierda'; break;
            //             case "2IFPI":   $arregloDASDol[$i] = '2IFD Izquierda'; break;
            //             case "3MFPI":   $arregloDASDol[$i] = '3MCF Izquierda'; break;
            //             case "3IFPI":   $arregloDASDol[$i] = '3IFD Izquierda'; break;
            //             case "4MFPI":   $arregloDASDol[$i] = '4MCF Izquierda'; break;
            //             case "4IFPI":   $arregloDASDol[$i] = '4IFD Izquierda'; break;
            //             case "5MFPI":   $arregloDASDol[$i] = '5MCF Izquierda'; break;
            //             case "5IFPI":   $arregloDASDol[$i] = '5IFD Izquierda'; break;
            //             case "1MFPD":   $arregloDASDol[$i] = '1MCF Derecha';   break;
            //             case "1IFPD":   $arregloDASDol[$i] = '1IFD Derecha';   break;
            //             case "2MFPD":   $arregloDASDol[$i] = '2MCF Derecha';   break;
            //             case "2IFPD":   $arregloDASDol[$i] = '2IFD Derecha';   break;
            //             case "3MFPD":   $arregloDASDol[$i] = '3MCF Derecha';   break;
            //             case "3IFPD":   $arregloDASDol[$i] = '3IFD Derecha';   break;
            //             case "4MFPD":   $arregloDASDol[$i] = '4MCF Derecha';   break;
            //             case "4IFPD":   $arregloDASDol[$i] = '4IFD Derecha';   break;
            //             case "5MFPD":   $arregloDASDol[$i] = '5MCF Derecha';   break;
            //             case "5IFPD":   $arregloDASDol[$i] = '5IFD Derecha';   break;
            //         }
                    
            //         if ( $operacionDol == 3 ) // Validación para texto por solo tres columnas
            //         {
            //             $opcionesDol.= '
            //                 </tr>
            //                 <tr style="line-height: 9px">
            //                     <td style="width:33%; font-size: 95%; text-align: justify;">
            //                         <br>'.$arregloDASDol[$i].'
            //                     </td>
            //             ';
                        
            //             $operacionDol = 1;
            //         }
            //         else
            //         {
            //             $opcionesDol.= '
            //                 <td style="width:33%; font-size: 95%; text-align: justify;">
            //                     <br>'.$arregloDASDol[$i].'
            //                 </td>
            //             ';
                        
            //             $operacionDol++;
            //         }
                    
            //     }
                
            //     $opcionesDol.= '
            //             </tr>
            //         </table>';
                
            //     $contInfla = sizeof($arregloDASInf);
                
            //     $opcionesInf = '
            //         <table style="padding: 2px; width: 100%">
            //             <tr style="line-height: 9px">
            //     ';

            //     $operacionInf = 0;
            
            //     // Recorrido para reemplazo de articulaciones inflamadas
            //     for ( $i = 0; $i < $contInfla; $i++ )
            //     {
            //         switch ( $arregloDASInf[$i] )
            //         {
            //             case "hombroI": $arregloDASInf[$i] = 'Hombro Izquierdo'; break;
            //             case "codoI":   $arregloDASInf[$i] = 'Codo Izquierdo'; break;
            //             case "munecaI": $arregloDASInf[$i] = 'Muñeca Izquierdo'; break;
            //             case "rodillaI":$arregloDASInf[$i] = 'Rodilla Izquierdo'; break;
            //             case "hombroD": $arregloDASInf[$i] = 'Hombro Derecho'; break;
            //             case "codoD":   $arregloDASInf[$i] = 'Codo Derecho'; break;
            //             case "munecaD": $arregloDASInf[$i] = 'Muñeca Derecho'; break;
            //             case "rodillaD":$arregloDASInf[$i] = 'Rodilla Derecho'; break;
            //             case "1MFPI":   $arregloDASInf[$i] = '1MCF Izquierda';  break;
            //             case "1IFPI":   $arregloDASInf[$i] = '1IFD Izquierda';  break;
            //             case "2MFPI":   $arregloDASInf[$i] = '2MCF Izquierda';  break;
            //             case "2IFPI":   $arregloDASInf[$i] = '2IFD Izquierda';  break;
            //             case "3MFPI":   $arregloDASInf[$i] = '3MCF Izquierda';  break;
            //             case "3IFPI":   $arregloDASInf[$i] = '3IFD Izquierda';  break;
            //             case "4MFPI":   $arregloDASInf[$i] = '4MCF Izquierda';  break;
            //             case "4IFPI":   $arregloDASInf[$i] = '4IFD Izquierda';  break;
            //             case "5MFPI":   $arregloDASInf[$i] = '5MCF Izquierda';  break;
            //             case "5IFPI":   $arregloDASInf[$i] = '5IFD Izquierda';  break;
            //             case "1MFPD":   $arregloDASInf[$i] = '1MCF Derecha';    break;
            //             case "1IFPD":   $arregloDASInf[$i] = '1IFD Derecha';    break;
            //             case "2MFPD":   $arregloDASInf[$i] = '2MCF Derecha';    break;
            //             case "2IFPD":   $arregloDASInf[$i] = '2IFD Derecha';    break;
            //             case "3MFPD":   $arregloDASInf[$i] = '3MCF Derecha';    break;
            //             case "3IFPD":   $arregloDASInf[$i] = '3IFD Derecha';    break;
            //             case "4MFPD":   $arregloDASInf[$i] = '4MCF Derecha';    break;
            //             case "4IFPD":   $arregloDASInf[$i] = '4IFD Derecha';    break;
            //             case "5MFPD":   $arregloDASInf[$i] = '5MCF Derecha';    break;
            //             case "5IFPD":   $arregloDASInf[$i] = '5IFD Derecha';    break;
            //         }
                    
            //         if ( $operacionInf == 3 ) // Validación para texto por solo tres columnas
            //         {
            //             $opcionesInf.= '
            //                 </tr>
            //                 <tr style="line-height: 9px">
            //                     <td style="width:33%; font-size: 95%; text-align: justify;">
            //                         <br>'.$arregloDASInf[$i].'
            //                     </td>
            //             ';
                        
            //             $operacionInf = 1;
            //         }
            //         else
            //         {
            //             $opcionesInf.= '
            //                 <td style="width:33%; font-size: 95%; text-align: justify;">
            //                     <br>'.$arregloDASInf[$i].'
            //                 </td>
            //             ';
                        
            //             $operacionInf++;
            //         }
            //     }
            
            //     $opcionesInf.= '
            //             </tr>
            //         </table>';
            
            //     // Validación para ingreso de bloque de DAS 28 solo si alguna de las dos posee valores
            //     if ( ($contDol - 1) > 0 || ($contInfla - 1) > 0)
            //     {
            //         $informDataHistory.='
            //             <table style="padding: 2px; width: 100%">
            //                 <tr style="font-size: 55%; line-height: 9px">
            //                     <td style="width:50%; border: 0,5px solid black; text-align: center;">
            //                         <br><strong>DAS28 - Recuento de Articulaciones Dolorosas</strong><br>
            //                         '.$opcionesDol.'
            //                     </td>
            //                     <td style="width:50%; border: 0,5px solid black; text-align: center;">
            //                         <br><strong>DAS28 - Recuento de Articulaciones Inflamadas</strong><br>
            //                         '.$opcionesInf.'
            //                     </td>
            //                 </tr>
            //             </table>
            //         ';
            //     }
            
                
            

                
                
            //     // Variable de evolucion
            //     $evolucion = '';
                
            //     // Motivo de consulta
            //     $motivoConsulta = htmlentities( $datos['reuma_5_1']);
            //     $enfermedadActual = '<br><strong>Fecha de Inciio de Síntomas:</strong> '.htmlentities( $datos['antecedentes']['evolEnfermedad']['inicioSintomas']);
            //     $enfermedadActual.= '<br><strong>Fecha Primera Visita por Especialista:</strong> '.htmlentities( $datos['antecedentes']['evolEnfermedad']['visitaEspecialista']);
            //     $enfermedadActual.= '<br><strong>Fecha Diagnóstico:</strong> '.htmlentities( $datos['antecedentes']['evolEnfermedad']['diagnostico']);
            //     $enfermedadActual.= '<br><strong>Observación </strong>'.htmlentities( $datos['antecedentes']['evolEnfermedad']['EnfermedadActual']) ;

                
                
            //     // Nota Médica
            //     $planDiag =  htmlentities(  $datos['reuma_2134']);
            //     $planTrat =  htmlentities(  $datos['reuma_2135']);
            //     $analisis =  htmlentities(  $datos['reuma_2133']);
            
                
            
            //     // Atención supervisada
            //     $atenSuper = 1;
                
            //     // Clinimetrías
            //     $climinetria = '';
            $this->printConceptoMedico($userId, $medical_id, $imprimir, $initialsUser, $identification, $datos, $info, $firm);
            
                break;
            
            
            // Componente para usuario de impresión getnameusetconect
            
            case 4: // Historia clinica a partir de información de farmacovigilancia o quimica farmaceutica
                $nameFormato = 'PROCEDIMIENTO';
                $haveAntecents  = false;
                // Variables relacionadas al acompañante
                $acompañante    = htmlentities($datos['qf_57']);
                $parentezco     = htmlentities($datos['qf_61']);
                $telefonoAcom   = htmlentities($datos['qf_63']);
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
                
                
                $examenFisOtros = '';
                /*$examenFisOtros = '
                <table>           
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Frec. Cardíaca:</strong>'.$frecCardiaca.'</td>
                        <td style="width: 25%;"><strong>Frec. Respiratoria:</strong> '.$frecRespira.'</td>
                        <td style="width: 25%;"><strong>Temperatura:</strong>'.$temperatura.'</td>
                        <td style="width: 25%;"><strong>Tensión Arterial:</strong>'.$tenArterial.'</td>
                    </tr>
                  
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Peso: </strong> '.$peso.'</td>
                        <td style="width: 25%;"><strong>Talla: </strong>'.$talla.'</td>
                        <td style="width: 25%;"><strong>IMC: </strong>'.$imc.'</td>
                        <td style="width: 25%;"> </td>
                    </tr>
                </table>   
                ';*/

                $informDataHistory = '';
            
                $antCliDiagHta.=  ( isset($datos['qf_37']) && $datos['qf_37'] === 'si') ? 'Si' : (( isset($datos['qf_37']) && $datos['qf_37'] === 'no') ? 'No' : '');
                $antCliDiagHip.=  ( isset($datos['qf_38']) && $datos['qf_38'] === 'si') ? 'Si' : (( isset($datos['qf_38']) && $datos['qf_38'] === 'no') ? 'No' : '');
                $antCliDiagDiab.= ( isset($datos['qf_39']) && $datos['qf_39'] === 'si') ? 'Si' : (( isset($datos['qf_39']) && $datos['qf_39'] === 'no') ? 'No' : '');
                
            
                $antCliDiagEpoc.= ( isset($datos['qf_40']) && $datos['qf_40'] === 'si') ? 'Si' : (( isset($datos['qf_40']) && $datos['qf_40'] === 'no') ? 'No' : '');
                $antCliDiagArte.= ( isset($datos['qf_43']) && $datos['qf_43'] === 'si') ? 'Si' : (( isset($datos['qf_43']) && $datos['qf_43'] === 'no') ? 'No' : '');
                $antCliDiagInsu.= ( isset($datos['qf_46']) && $datos['qf_46'] === 'si') ? 'Si' : (( isset($datos['qf_46']) && $datos['qf_46'] === 'no') ? 'No' : '');
                $antCliDiagAde.=  ( isset($datos['qf_49']) && $datos['qf_49'] === 'si') ? 'Si' : (( isset($datos['qf_49']) && $datos['qf_49'] === 'no') ? 'No' : '');
                $antCliDiagRena.= ( isset($datos['qf_52']) && $datos['qf_52'] === 'si') ? 'Si' : (( isset($datos['qf_52']) && $datos['qf_52'] === 'no') ? 'No' : '');
                $antCliDiagReuma.=( isset($datos['qf_53']) && $datos['qf_53'] === 'si') ? 'Si' : (( isset($datos['qf_53']) && $datos['qf_53'] === 'no') ? 'No' : '');
                $antCliDiagOtro.= $datos['qf_55'];
                
                // Bloque de Antecedentes Clinicos Diagnósticos
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" >
                                <br><br><strong>Antecedente Clínicos Diagnósticos </strong>
                            </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: center; border: 0,5px solid black; width: 20%" >
                                  <br><strong>Verificación</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black; width: 20%" >
                                  <br><strong>Si / No</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black; width: 60%" >
                                  <br><strong>Observaciones</strong>
                              </td>
                          </tr>
                    </table>
                    
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black; width: 20%" >
                                  <br><strong>Hipertensión Arterial Alta:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black; width: 20%" >
                                  <br>'.$antCliDiagHta.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black; width: 60%" >
                                  <br>'.$datos['qf_37_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Hiperlipidemia:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagHip.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_38_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Diabetes Mellitus:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagDiab.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_39_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Enfermedad Pulmonar Obstructiva Crónica - EPOC</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagEpoc.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_40_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Arteriopatia Periférica</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagArte.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_43_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Insuficiencia Cardíaca</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagInsu.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_46_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Adenoma Próstata</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagAde.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_49_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Insuficiencia Renal Crónica</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagRena.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_52_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Artritis Reumatoide</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagReuma.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_53_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Otro</strong> 
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br>'.$antCliDiagOtro.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_55_obs'].'
                              </td>
                          </tr>
                    </table>
                ';
                
                // Bloque de Antecedentes de uso de medicamentos
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" colspan="4">
                                <br><br><strong>Antecedentes de Uso de Medicamentos (BIOLÓGICOS, DMARDs, OTROS) </strong>
                            </td>
                        </tr>
                        <!--<tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Medicamento:</strong>
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Dosis:</strong>
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Vía:</strong>
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Frecuencia:</strong>
                              </td>
                          </tr>-->
                    </table>
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 60%">
                              <td style="text-align: center;" colspan="4">
                                <br><br><strong>DMARDs</strong>
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Medicamento:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Dosis:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Vía:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Frecuencia:</strong>
                              </td>
                        </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_1'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_2'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_3'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_4'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_5'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_6'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_630_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_631_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_632_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_633_7'].'
                              </td>
                          </tr>
                          
                          
                          <tr style="font-size: 60%">
                              <td style="text-align: center;" colspan="4">
                                <br><br><strong>Biológicos</strong>
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Medicamento:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Dosis:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Vía:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Frecuencia:</strong>
                              </td>
                        </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_671'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_672'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_671_0'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_672_0'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_673'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_674'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_673_0'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_674_0'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_0'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_0'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_01'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_01'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_05'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_05'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_06'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_06'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_675_07'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_676_07'].'
                              </td>
                          </tr>
                          
                          <tr style="font-size: 60%">
                              <td style="text-align: center;" colspan="4">
                                <br><br><strong>Otros</strong>
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Medicamento:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Dosis:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Vía:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Frecuencia:</strong>
                              </td>
                        </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_1'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_2'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_3'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_4'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_5'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_6'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_677_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_678_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_679_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_680_7'].'
                              </td>
                          </tr>
                    </table>
                ';
                
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" colspan="4">
                                <br><br><strong>Tratamiento Farmacológico Actual </strong>
                            </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Medicamento:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Dosis:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Vía:</strong>
                              </td>
                              <td style="text-align: center; border: 0,5px solid black;" >
                                  <br><strong>Frecuencia:</strong>
                              </td>
                        </tr>
                    </table>
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_1'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_1'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_2'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_2'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_3'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_3'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_4'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_4'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_5'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_5'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_6'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_6'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_7'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_7'].'
                              </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_690_8'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_691_8'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_692_8'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br>'.$datos['qf_693_8'].'
                              </td>
                        </tr>
                    </table>
                ';
            
                $adhTraFarmaAutoM.=  ( isset($datos['qf_81']) && $datos['qf_81'] === 'si') ? 'Si' : (( isset($datos['qf_81']) && $datos['qf_81'] === 'no') ? 'No' : '');
                $adhTraFarmaAutoP.=  ( isset($datos['qf_86']) && $datos['qf_86'] === 'si') ? 'Si' : (( isset($datos['qf_86']) && $datos['qf_86'] === 'no') ? 'No' : '');    
            
                $adhTraFarmaAlerg.=  ( isset($datos['qf_91']) && $datos['qf_91'] === 'si') ? 'Si' : (( isset($datos['qf_91']) && $datos['qf_91'] === 'no') ? 'No' : '');
                $adhTraFarmaCumpl.=  ( isset($datos['qf_96']) && $datos['qf_96'] === 'si') ? 'Si' : (( isset($datos['qf_96']) && $datos['qf_96'] === 'no') ? 'No' : '');    
            
                $adhTraFarmaCon.=    ( isset($datos['qf_101']) && $datos['qf_101'] === 'si') ? 'Si' : (( isset($datos['qf_101']) && $datos['qf_101'] === 'no') ? 'No' : '');
                $adhTraFarmaHomeo.=  ( isset($datos['qf_106']) && $datos['qf_106'] === 'si') ? 'Si' : (( isset($datos['qf_106']) && $datos['qf_106'] === 'no') ? 'No' : '');    
            
                $adhTraFarmaNatu.=   ( isset($datos['qf_111']) && $datos['qf_111'] === 'si') ? 'Si' : (( isset($datos['qf_111']) && $datos['qf_111'] === 'no') ? 'No' : '');    
                
            
                
                $paciReferMejoria.=   ( isset($datos['qf_116']) && $datos['qf_116'] === 'si') ? 'Si' : (( isset($datos['qf_116']) && $datos['qf_116'] === 'no') ? 'No' : '');    
                $paciReferTtoAct.=    ( isset($datos['qf_119']) && $datos['qf_119'] === 'si') ? 'Si' : (( isset($datos['qf_119']) && $datos['qf_119'] === 'no') ? 'No' : '');    
                $paciReferFrac.=      ( isset($datos['qf_122']) && $datos['qf_122'] === 'si') ? 'Si' : (( isset($datos['qf_122']) && $datos['qf_122'] === 'no') ? 'No' : '');    
                $paciReferAdhe.=      ( isset($datos['qf_123']) && $datos['qf_123'] === 'si') ? 'Si' : (( isset($datos['qf_123']) && $datos['qf_123'] === 'no') ? 'No' : '');    
            
            
                // Bloque de adherencia al tratamiento
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" >
                                <br><br><strong>Adherencia a Tratamiento Farmacológico </strong>
                            </td>
                        </tr>
                    </table>
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Automedicación:</strong> '.$adhTraFarmaAutoM.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_84'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Autoprescrición:</strong> '.$adhTraFarmaAutoP.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_89'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Alergias a Medicamentos:</strong> '.$adhTraFarmaAlerg.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_94'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Cumple Indicación Medica:</strong> '.$adhTraFarmaCumpl.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_99'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Conoce su tratamiento actual:</strong> '.$adhTraFarmaCon.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_104'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Uso De Medicamentos homeopáticos:</strong> '.$adhTraFarmaHomeo.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_109'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Uso De Medicamentos Naturales:</strong> '.$adhTraFarmaCon.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_114'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Siente mejoría con tto actual:</strong> '.$paciReferMejoria.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_116_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Presenta reacción adversa con tto actual:</strong> '.$paciReferTtoAct.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_119_obs'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>¿El paciente es adherente al tratamiento?:</strong> '.$paciReferAdhe.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_123_obs'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>¿Ha presentado fracaso a otros Tratamientos?:</strong> '.$paciReferFrac.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Descripción:</strong> '.$datos['qf_122_obs'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" colspan="2">
                                  <br><strong>¿Cuál?:</strong> '.$datos['qf_125'].'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" colspan="4">
                                  <br><strong>Observaciones Generales:</strong> '.$datos['qf_11411'].'
                              </td>
                          </tr>
                    </table>
                ';

                
                $orientPaciUsoRac.= ( isset($datos['qf_127']) && $datos['qf_127'] === 'si') ? 'Si' : (( isset($datos['qf_127']) && $datos['qf_127'] === 'no') ? 'No' : '');    
                $orientPaciIndica.= ( isset($datos['qf_130']) && $datos['qf_130'] === 'si') ? 'Si' : (( isset($datos['qf_130']) && $datos['qf_130'] === 'no') ? 'No' : '');    
                $orientPaciAlmace.= ( isset($datos['qf_133']) && $datos['qf_133'] === 'si') ? 'Si' : (( isset($datos['qf_133']) && $datos['qf_133'] === 'no') ? 'No' : '');    
                $orientPaciAdmini.= ( isset($datos['qf_136']) && $datos['qf_136'] === 'si') ? 'Si' : (( isset($datos['qf_136']) && $datos['qf_136'] === 'no') ? 'No' : '');    
                $orientPaciAdhier.= ( isset($datos['qf_139']) && $datos['qf_139'] === 'si') ? 'Si' : (( isset($datos['qf_139']) && $datos['qf_139'] === 'no') ? 'No' : '');    
            
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" >
                                <br><br><strong>Orientación al paciente con el tto. farmacológico </strong>
                            </td>
                        </tr>
                    </table>
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Se explica la importancia del uso racional de los medicamentos:</strong> '.$orientPaciUsoRac.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Se explica la importancia de seguir la indicación dada por el médico:</strong> '.$orientPaciIndica.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Se explica la forma correcta de almacenar los medicamentos:</strong> '.$orientPaciAlmace.'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Se explica la forma correcta de administración de los medicamentos:</strong> '.$orientPaciAdmini.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;">
                                  <br><strong>Se explica la importancia de adherirse al tto faramacológico:</strong> '.$orientPaciAdhier.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;">
                                  <br><strong>Observación:</strong> '.$datos['qf_142'].'
                              </td>
                          </tr>
                    </table>
                ';
            
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = $datos['qf_631'];
                
                // Nota Médica
                $planDiag = '';
                $planTrat = '';
                $analisis = $datos['qf_144'];
                //$planDiag = $datos['qf_146'];
                //$planTrat = $datos['qf_147'];
                //$analisis = $datos['qf_145'];
            
                // Atención supervisada
                $atenSuper = 0;
                
                // Clinimetrías
                $climinetria = '';
                
                break;
            
            case 5: // Historia clinica a partir de información de terapia fisica
                
                $haveAntecents  = true;

                // Variables relacionadas al acompañante
                $acompañante    = htmlentities($datos['tf_6']);
                $parentezco     = htmlentities($datos['tf_8']);
                $telefonoAcom   = htmlentities($datos['tf_9']);
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
                $examenFisOtros = '
                <table>           
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Frec. Cardíaca:</strong>'.$frecCardiaca.'</td>
                        <td style="width: 25%;"><strong>Frec. Respiratoria:</strong> '.$frecRespira.'</td>
                        <td style="width: 25%;"><strong>Temperatura:</strong>'.$temperatura.'</td>
                        <td style="width: 25%;"><strong>Tensión Arterial:</strong>'.$tenArterial.'</td>
                    </tr>
                  
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Peso: </strong> '.$peso.'</td>
                        <td style="width: 25%;"><strong>Talla: </strong>'.$talla.'</td>
                        <td style="width: 25%;"><strong>IMC: </strong>'.$imc.'</td>
                        <td style="width: 25%;"> </td>
                    </tr>
                </table>   
                ';
                                
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = $datos['tf_10'] ;
                
                
             
                
                
                $planDiag =  htmlentities($datos['tf_323']);
                $planTrat =  htmlentities($datos['tf_324']);
                $analisis = htmlentities( $datos['reuma_2133']);
                
               // $movArticuolar = htmlentities( $datos['tf_259']);
                
                $movArticuolar = htmlentities( $datos['tf_259']);
               
                $fortMuscular = htmlentities( $datos['tf_261']);
               
                $flex = htmlentities( $datos['tf_263']);
             
                $medFisicos = htmlentities($datos['tf_265']);
                
                $observaciones = htmlentities($datos['tf_265_1']);
               
                
                $informDataHistory = '
                    <table style="padding: 2px; width: 100%">
                       <tr style="font-size: 10%">
                           <td style="text-align: center;" >

                           </td>
                       </tr>
                       <tr style="font-size: 70%">
                           <td style="text-align: center;" >
                               <br><strong> ACTIVIDADES / PLANES DE MANEJO </strong>
                           </td>
                       </tr>
                         <tr  style="font-size: 55%">
                          <td style="border: 0.2px solid black;" >
                               <strong>MOVILIDAD ARTICULAR :</strong>'.$movArticuolar.'<br>
                               <strong>FORTALECIMIENTO MUSCULAR: </strong> '.$fortMuscular.'<br>
                               <strong>EJERCICIOS DE FLEXIBILIDAD: </strong> '.$flex.'<br>
                               <strong>APLICACIÓN DE MEDIOS FÍSICOS: </strong> '.$medFisicos.'<br>
                           </td>
                       </tr>
                          <tr  style="font-size: 55%">
                          <td style="border: 0.2px solid black;" >
                               <strong>OBSERVACIONES :</strong>'.$observaciones.' <br>   
                           </td>
                       </tr>
                       </table>';
                
            

                            // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';
            
                break;
            
            case 6: // Historia clinica a partir de información de nutricion
                
                // Variables relacionadas al acompañante
                $acompañante    = htmlentities($datos['nut_6']);
                $parentezco     = htmlentities($datos['nut_8']);
                $telefonoAcom   = htmlentities( $datos['nut_9']);
            
                $haveAntecents  = true;


                // Variables que representan signos vitales
                $frecCardiaca   = htmlentities($datos['nut_498']);
                $frecRespira    = htmlentities($datos['nut_499']);
                $temperatura    = htmlentities($datos['nut_500']);
                $tenArterial    = htmlentities($datos['nut_497']);
            
                // Variables que representan examen fisico
                $peso       = htmlentities($datos['nut_521']);
                $talla      = htmlentities($datos['nut_522']);
                $imc        = htmlentities($datos['nut_523']);
                $superficie = '';
            
                $enfermedadActual = $datos['nut_11'];
                    
                $examenFisOtros = '
                <table>           
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Frec. Cardíaca:</strong>'.$frecCardiaca.'</td>
                        <td style="width: 25%;"><strong>Frec. Respiratoria:</strong> '.$frecRespira.'</td>
                        <td style="width: 25%;"><strong>Temperatura:</strong>'.$temperatura.'</td>
                        <td style="width: 25%;"><strong>Tensión Arterial:</strong>'.$tenArterial.'</td>
                    </tr>
                  
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Peso: </strong> '.$peso.'</td>
                        <td style="width: 25%;"><strong>Talla: </strong>'.$talla.'</td>
                        <td style="width: 25%;"><strong>IMC: </strong>'.$imc.'</td>
                        <td style="width: 25%;"> </td>
                    </tr>
                </table>   
                ';

            
                // Variable de evolucion
                $evolucion = '';
                /*$antecedentes = ' <table>           
                            <tr style="font-size: 55%">
                                <td style="width: 25%;"><strong>Frec. Cardíaca:</strong>'.$frecCardiaca.'</td>
                                <td style="width: 25%;"><strong>Frec. Respiratoria:</strong> '.$frecRespira.'</td>
                                <td style="width: 25%;"><strong>Temperatura:</strong>'.$temperatura.'</td>
                                <td style="width: 25%;"><strong>Tensión Arterial:</strong>'.$tenArterial.'</td>
                            </tr>
                          
                            <tr style="font-size: 55%">
                                <td style="width: 25%;"><strong>Peso: </strong> '.$peso.'</td>
                                <td style="width: 25%;"><strong>Talla: </strong>'.$talla.'</td>
                                <td style="width: 25%;"><strong>IMC: </strong>'.$imc.'</td>
                                <td style="width: 25%;"> </td>
                            </tr>
                        </table>   ';*/
                  
                
                $informDataHistory = '';
                
            
                // Motivo de consulta
                $motivoConsulta =htmlentities($datos['derm_6']);//$datos['nut_11'];
            
                // $planDiag = htmlentities($datos['nut_654']);
                // $planTrat = htmlentities($datos['nut_655']);
                $analisis = htmlentities($datos['nut_653']);
                       
                // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';
            

            
                break;
            
            case 7: // Historia clinica a partir de información de sala de infusión
                
                $haveAntecents  = false;
            
                // Historia valorada por caso aparte al ser historia diferente
                $nameFormato = 'PROCEDIMIENTO DE SALA DE INFUSIÓN ';
                //print_r($datos);
                //exit();

                // Variables relacionadas al acompañante
                $acompañante    = htmlentities($datos['psico_332']); //$datos['sinfu_0'];
                $parentezco     = htmlentities($datos['psico_334']);// $datos['sinfu_2'];
                $telefonoAcom   = htmlentities($datos['psico_335']);// $datos['sinfu_3'];

                $motivo_consulta = '';
                    
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
            
                $examenFisOtros = '';
            
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = $datos['motivo_consulta'];
                
                $planDiag = ''; //$datos['sinfu_65'];
                $planTrat = ''; //$datos['sinfu_66'];
                $analisis = ''; //$datos['sinfu_64'];
                $enfermedadActual = '';
            
                // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';


                /* BLOQUE DE INFORMACIÓN ADICIONAL PROPIA DE SALA DE INFUSIÓN */
                $procinfec = '';
                
                // Bloque de Indagación Inicial
                $indProcInfec   = htmlentities($datos['sinfu_7']);
                $indAlerMedi    = htmlentities($datos['sinfu_9']);
                $indCual        = htmlentities($datos['sinfu_11']);
                $indHeridas     = htmlentities($datos['sinfu_12']);
                $indAlfebril    = htmlentities($datos['sinfu_14']);
                $indPteApli     = htmlentities($datos['sinfu_16']);
                $indfecReag     = htmlentities($datos['sinfu_18']);
                $indUltCtrl     = htmlentities($datos['sinfu_19']);
                $indNumAuto     = htmlentities($datos['sinfu_20']);
              
                // Bloque de 10 Correctos
                if ( htmlentities($datos['sinfu_29']) == 'Si' )
                {
                    $pacienteCorrecto = 'APLICA';
                }
                else
                {
                    if ( htmlentities($datos['sinfu_29']) == 'No' )
                    {
                        $pacienteCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $pacienteCorrecto = '';
                    }
                }
            
                if ( htmlentities($datos['sinfu_31']) == 'Si' )
                {
                    $fecAdmidCorrecto = 'APLICA';
                }
                else
                {
                    if ( htmlentities($datos['sinfu_31']) == 'No' )
                    {
                        $fecAdmidCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $fecAdmidCorrecto = '';
                    }
                }
            
                if ( htmlentities($datos['sinfu_33']) == 'Si' )
                {
                    $HoraAdminCorrecto = 'APLICA';
                }
                else
                {
                    if ( htmlentities($datos['sinfu_33']) == 'No' )
                    {
                        $HoraAdminCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $HoraAdminCorrecto = '';
                    }
                }
                
                if ( htmlentities($datos['sinfu_35']) == 'Si' )
                {
                    $medicamentoCorrecto = 'APLICA';
                }
                else
                {
                    if ( htmlentities($datos['sinfu_35']) == 'No' )
                    {
                        $medicamentoCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $medicamentoCorrecto = '';
                    }
                }
                
                if ( htmlentities($datos['sinfu_37']) == 'Si' )
                {
                    $dosisCorrecto = 'APLICA';
                }
                else
                {
                    if ( htmlentities($datos['sinfu_37']) == 'No' )
                    {
                        $dosisCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $dosisCorrecto = '';
                    }
                }
            
                if ( htmlentities($datos['sinfu_39']) === 'Si' )
                {
                    $fecVenciCorrecto = 'APLICA';
                }
                else
                {
                    if ( htmlentities($datos['sinfu_39']) === 'No' )
                    {
                        $fecVenciCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $fecVenciCorrecto = $datos['sinfu_39'];
                    }
                }
            
                if ( $datos['sinfu_41'] == 'iv' )
                {
                    $viaCorrecto = 'IV';
                }
                else
                {
                    if ( $datos['sinfu_41'] == 'sv' )
                    {
                        $viaCorrecto = 'SB';
                    }
                    else
                    {
                        $viaCorrecto = '';
                    }
                }
            
                if ( $datos['sinfu_43'] == 'Si' )
                {
                    $dilucionCorrecto = 'APLICA';
                }
                else
                {
                    if ( $datos['sinfu_43'] == 'No' )
                    {
                        $dilucionCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $dilucionCorrecto = '';
                    }
                }
            
                if ( $datos['sinfu_45'] == 'Si' )
                {
                    $coteoCorrecto = 'APLICA';
                }
                else
                {
                    if ( $datos['sinfu_45'] == 'No' )
                    {
                        $coteoCorrecto = 'NO APLICA';
                    }
                    else
                    {
                        $coteoCorrecto = '';
                    }
                }
            
                if ( $datos['sinfu_47'] == 'Si' )
                {
                    $educacionCorrecto = 'SI';
                }
                else
                {
                    if ( $datos['sinfu_45'] == 'No' )
                    {
                        $educacionCorrecto = 'NO';
                    }
                    else
                    {
                        $educacionCorrecto = '';
                    }
                }
            
                  //$pacienteCorrecto       = htmlentities($datos['sinfu_29']);
                  //$fecAdmidCorrecto       = htmlentities($datos['sinfu_31']);
                  //$HoraAdminCorrecto      = htmlentities($datos['sinfu_33']);
                  //$medicamentoCorrecto    = htmlentities($datos['sinfu_35']);
                  //$dosisCorrecto          = htmlentities($datos['sinfu_37']);
                  //$fecVenciCorrecto       = htmlentities($datos['sinfu_39']);
                  //$viaCorrecto            = htmlentities($datos['sinfu_41']);
                  //$dilucionCorrecto       = htmlentities($datos['sinfu_43']);
                  //$coteoCorrecto          = htmlentities($datos['sinfu_45']);
                  //$educacionCorrecto      = htmlentities($datos['sinfu_47']);
              
                  // Variables de Signos Vitales
                  // Ingreso
                  $pesoIngreso        = htmlentities($datos['sinfu_21']);    
                  $frecCardIngreso    = htmlentities($datos['sinfu_22']);    
                  $satIngreso         = htmlentities($datos['sinfu_23']);    
                  $tensionArtIngreso  = htmlentities($datos['sinfu_24']);    
                  $tempIngreso        = htmlentities($datos['sinfu_25']);    
                  $medicaIngreso      = htmlentities($datos['sinfu_26']);    
                  $progInicialIngreso = htmlentities($datos['sinfu_28']);    
                  $auxiliarIngreso    = htmlentities($datos['aux_responsable_signos_ingreso']);    
              
                  // Egreso
                  $pesoEgreso         = htmlentities($datos['sinfu_2121']);
                  $frecCardiEgreso    = htmlentities($datos['sinfu_2221']);
                  $satEgreso          = htmlentities($datos['sinfu_2321']);
                  $tensionArtEgreso   = htmlentities($datos['sinfu_2421']);
                  $tempEgreso         = htmlentities($datos['sinfu_2521']);
                  $auxiliarEgreso     = htmlentities($datos['aux_responsable_signos_egreso']);
                  
                  // Variables de Procedimiento
                  $procDosis  = htmlentities($datos['producto_infu_1']);
                  $procPunDer = htmlentities($datos['producto_infu_2']);
                  $procPunIzq = htmlentities($datos['producto_infu_3']);
                  $procResul  = htmlentities($datos['producto_infu_4']);
                  $procDura   = htmlentities($datos['producto_infu_5']);
                  $procObserv = htmlentities($datos['producto_infu_6']);
                 
                 
                 
                 // Variables para bloque de Eventos Adversos
                  $eventoAdverso = $datos['sinfu_56'];
                 
                 
                 $informDataHistory = '';
                 
                 
                 // Variable con html de medicamentos de sala de infusion
                 $SinfuMedicamento = '
                     <tr style="font-size: 50%;">
                         <td style="text-align: justify; border: 0,5px solid black;  width: 40%" >
                             <br><strong>Producto</strong>
                         </td>
                         <td style="text-align: justify; border: 0,5px solid black;  width: 15%" >
                             <br><strong>Cantidad</strong>
                         </td>
                         <td style="text-align: justify; border: 0,5px solid black;  width: 15%" >
                             <br><strong>Lote</strong>
                         </td>
                         <td style="text-align: justify; border: 0,5px solid black;  width: 15%" >
                             <br><strong>Fec. Vencimiento</strong>
                         </td>
                         <td style="text-align: justify; border: 0,5px solid black;  width: 15%" >
                             <br><strong>Verificado</strong>
                         </td>
                     </tr>
                 ';
                 
                 $cantMedicamento = empty($datos['producto_infu']) ? count($datos['producto_infu_manual']) : count($datos['producto_infu']);
                 
                 // Verificacion para existencia de medicamentos
                 if ( $cantMedicamento != 0 )
                 {
                     // Recorrido de medicamentos existentes
                     for ( $k = 0; $k < $cantMedicamento; $k++ )
                     {

                        $medi = empty($datos['producto_infu']) ? $datos['producto_infu_manual'] : $datos['producto_infu'];

                         $arrayTemp = (array)$medi[$k] ;
                         
                         $SinfuMedicamento.= '
                             <tr style="font-size: 50%;">
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['nombre'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['cantidad'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['lote'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['vencimiento'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['verificado'].'
                                 </td>
                             </tr>
                         ';
                     }
                 }

                 $cantMedicamentoManual = empty($datos['producto_infu_manual']) ? 0 : count($datos['producto_infu_manual']);
                 
                 // Verificacion para existencia de medicamentos
                 if ( $cantMedicamentoManual != 0 )
                 {
                    $medi = $datos['producto_infu_manual'] ;
                     // Recorrido de medicamentos existentes
                     for ( $k = 0; $k < $cantMedicamentoManual; $k++ )
                     {
                        

                         $arrayTemp = (array)$medi[$k] ;
                         
                         $SinfuMedicamento.= '
                             <tr style="font-size: 50%;">
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['nombre'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['cantidad'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['lote'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['vencimiento'].'
                                 </td>
                                 <td style="text-align: justify; border: 0,5px solid black;" >
                                     <br>'.$arrayTemp['verificado'].'
                                 </td>
                             </tr>
                         ';
                     }
                 }
                   
                
                
                  // Bloque de Indagación Inicial
                  $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 7px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>Indagación Inicial:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Proceso infeccioso:</strong>
                              <br>'.$indProcInfec.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Alergias Medicamentosas:</strong>
                              <br>'.$indAlerMedi.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>¿Cuál?</strong>
                              <br>'.$indCual.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Heridas:</strong>
                              <br>'.$indHeridas.'
                          </td>
                      </tr>
                  </table>
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 50%; line-height: 7px">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Alfebril:</strong>
                              <br>'.$indAlfebril.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Pte. Apto para Medicamento:</strong>
                              <br>'.$indPteApli.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Fec. Reagendamiento:</strong>
                              <br>'.$indfecReag.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Último Control Reumatología:</strong>
                              <br>'.$indUltCtrl.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Nro. Autorización:</strong>
                              <br>'.$indNumAuto.'
                          </td>
                      </tr>
                  </table>
              ';
          
              // Bloque de Signos Vitales de Ingreso
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>Signos Vitales de ingreso:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Peso:</strong> '.$pesoIngreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Frec. Cardíaca:</strong> '.$frecCardIngreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Saturación:</strong> '.$satIngreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Tensión Arterial:</strong> '.$tensionArtIngreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 20%" >
                              <br><strong>Temperatura: </strong>'.$tempIngreso.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>Medicamento por Bomba:</strong> '.$medicaIngreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" colspan="2">
                              <br><strong>Programación Inicial de la Bomba:</strong> '.$progInicialIngreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" colspan="2">
                              <br><strong>Auxiliar Responsable:</strong> '.$auxiliarIngreso.'
                          </td>
                      </tr>
                  </table>
              ';
              
              
              // Bloque de Medicamento
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>Medicamento:</strong>
                          </td>
                      </tr>
                      '.$SinfuMedicamento.'
                  </table>
              ';
          
              // Bloque 10 Correctos
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>10 Correctos:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 35%" >
                              <br><strong></strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 15%" >
                              <br><strong>Aplica/No Aplica:</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 35%" >
                              <br><strong></strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 15%" >
                              <br><strong>Aplica/No Aplica:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>PACIENTE CORRECTO</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$pacienteCorrecto.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>FECHA CORRECTA DE ADMINISTRACIÓN</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$fecAdmidCorrecto.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>HORA CORRECTA DE ADMINISTRACIÓN</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$HoraAdminCorrecto.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>MEDICAMENTO CORRECTO</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$medicamentoCorrecto.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>DOSIS CORRECTA</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$dosisCorrecto.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>FECHA DE VENCIMIENTO DEL MEDICAMENTO</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$fecVenciCorrecto.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>VIA CORRECTA DE ADMINISTRACIÓN </strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$viaCorrecto.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>DILUCIÓN CORRECTA</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$dilucionCorrecto.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>GOTEO CORRECTO</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$coteoCorrecto.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br><strong>EDUCACIÓN AL PACIENTE</strong>
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;" >
                              <br>'.$educacionCorrecto.'
                          </td>
                      </tr>
                  </table>
              ';
              
          
              // Bloque de Procedimiento
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>Procedimiento:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black; width: 15%" >
                              <br><strong>Dosis Administrada:</strong> 
                              <br>'.$procDosis.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black; width: 23%" >
                              <br><strong>Nro. Punciones en Miembro Derecho:</strong> 
                              <br>'.$procPunDer.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black; width: 23%" >
                              <br><strong>Nro. Punciones en Miembro Izquierdo:</strong> 
                              <br>'.$procPunIzq.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black; width: 24%" >
                              <br><strong>Resultado del Procedimiento:</strong> 
                              <br>'.$procResul.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black; width: 15%" >
                              <br><strong>Duración de la Infusión:</strong> 
                              <br>'.$procDura.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          
                          <td style="text-align: justify; border: 0,5px solid black;" colspan="5">
                              <br><strong>Observaciones:</strong> '.$procObserv.'
                          </td>
                      </tr>
                  </table>
              ';
          
              // Bloque de Eventos Adversos
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>Eventos Adversos:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 100%" >
                              <br><strong>Detalle:</strong>
                              <br>'.$eventoAdverso.'
                          </td>
                      </tr>
                  </table>
              ';
          
              // Bloque de Signos Vitales de Egreso
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: center;"  colspan="4">
                              <br><br><strong>Signos Vitales de Egreso:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Peso:</strong> '.$pesoEgreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Frec. Cardíaca:</strong> '.$frecCardiEgreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Saturación:</strong> '.$satEgreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Tensión Arterial:</strong> '.$tensionArtEgreso.'
                          </td>
                      </tr>
                      <tr style="font-size: 50%;">
                          <td style="text-align: justify; border: 0,5px solid black;  width: 25%" >
                              <br><strong>Temperatura:</strong> '.$tempEgreso.'
                          </td>
                          <td style="text-align: justify; border: 0,5px solid black;  width: 75%" colspan="3">
                              <br><strong>Auxiliar Responsable:</strong> '.$auxiliarEgreso.'
                          </td>
                      </tr>
                  </table>
              ';
              
              // Bloque de texto de verificación y espacio para firmas
              $informDataHistory.= '
                  <table style="padding: 2px; width: 100%">
                      <tr style="font-size: 60%; line-height: 9px">
                          <td style="text-align: justify;"  colspan="4">
                              <br><br><strong>Autorización de Aplicación de Medicamentos:</strong>
                          </td>
                      </tr>
                      <tr style="font-size: 60%;">
                          <td style="text-align: justify; width: 100%" >
                              <br>Se verifica que el paciente ha comprendido adecuadamente 
                              la información que contiene este documento y autorizo la 
                              aplicación del medicamento biológico.
                          </td>
                      </tr>
                  </table>

              ';
              
              if(!empty($datos['sinfu_2421_01']) ){
                  
                $informDataHistory .= '
                <table style="padding: 2px; width: 100%">
                   <tr style="font-size: 10%">
                       <td style="text-align: center;" >
  
                       </td>
                   </tr>
                   <tr style="font-size: 70%">
                       <td style="text-align: center;" >
                           <br><strong>OBSERVACIONES</strong>
                       </td>
                   </tr>
                      <tr  style="font-size: 55%">
                      <td style="border: 0.2px solid black;" >
                           <strong>OBSERVACIONES :</strong>'.$datos['sinfu_2421_01'].' <br>   
                       </td>
                   </tr>
                   </table>';
  
               }
                
              
                break;
            
            case 8: // Historia clinica a partir de información de psicología
                $haveAntecents  = true;
                // Variables relacionadas al acompañante
                $acompañante    = htmlentities($datos['psico_332']);
                $parentezco     = htmlentities($datos['psico_334']);
                $telefonoAcom   = htmlentities($datos['psico_335']);
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
                $examenFisOtros = '';
            
                // Variable de evolucion
                //$evolucion = htmlentities($datos['psico_228']);
                $evolucion = '';
                
                // Motivo de consulta
                $pr_ctrl = $datos['psico_4_2_01'].'<br>';
                $motEmo = ($datos['psico_4_1_1'] == 'Emocional') ? ' - Seguimiento Emocional<br>' : '';
                $motIna = ($datos['psico_4_1'] == 'Inadherencia') ? ' - Seguimiento Inadherencia<br>' : '';
                $motPri = $datos['psico_4_2'].'<br>';
            
                $motivoConsulta = $pr_ctrl.''.$motEmo.''.$motIna.''.$motPri.''.htmlentities($datos['psico_4']);
                
                $enfermedadActual = htmlentities($datos['psico_228']);
            
                $planDiag = htmlentities($datos['psico_317']);
                $planTrat = htmlentities($datos['psico_318']);
                //$planTrat = '';
                $analisis = htmlentities($datos['psico_316']);
            
                // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';
                
                // Historia específica de psicología
                $informDataHistory = '';
                $informDataHistoryPsico1 = '';
                $informDataHistoryPsico2 = '';
                $informDataHistoryPsico3 = '';

                // Bloque de Antecedentes psicológicos - Solo títulos
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 60%; line-height: 9px">
                              <td style="text-align: center;"  colspan="4">
                                  <br><br><strong>Antecedentes Psicológicos:</strong>
                              </td>
                          </tr>
                    </table>';
                

                // Bloque de validación de ingreso de campos de antecedentes personales psicologicos
                if ( trim($datos['psico_17']) != '' )
                {
                    $informDataHistoryPsico1.= '
                          <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 1: '.htmlentities($datos['psico_17']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                
                if ( trim($datos['psico_18']) != '' )
                {
                    $informDataHistoryPsico1.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 2: '.htmlentities($datos['psico_18']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                
                if ( trim($datos['psico_19']) != '' )
                {
                    $informDataHistoryPsico1.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 3: '.htmlentities($datos['psico_19']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                
                if ( trim($datos['psico_20']) != '' )
                {
                    $informDataHistoryPsico1.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 4: '.htmlentities($datos['psico_20']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                
                if ( trim($datos['psico_21']) != '' )
                {
                    $informDataHistoryPsico1.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 5: '.htmlentities($datos['psico_21']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                
                

                if ( trim($datos['psico_23']) != '' )
                {
                    $informDataHistoryPsico2.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 1: '.htmlentities($datos['psico_23']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }      
                if ( trim($datos['psico_24']) != '' )
                {
                    $informDataHistoryPsico2.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 2: '.htmlentities($datos['psico_24']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }      
                if ( trim($datos['psico_25']) != '' )
                {
                    $informDataHistoryPsico2.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 3: '.htmlentities($datos['psico_25']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }      
                if ( trim($datos['psico_26']) != '' )
                {
                    $informDataHistoryPsico2.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 4: '.htmlentities($datos['psico_26']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }      
                if ( trim($datos['psico_27']) != '' )
                {
                    $informDataHistoryPsico2.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 5: '.htmlentities($datos['psico_27']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                
                

                
                if ( trim($datos['psico_29']) != '' )
                {
                    $informDataHistoryPsico3.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 1: '.htmlentities($datos['psico_29']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                if ( trim($datos['psico_30']) != '' )
                {
                    $informDataHistoryPsico3.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 2: '.htmlentities($datos['psico_30']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                if ( trim($datos['psico_31']) != '' )
                {
                    $informDataHistoryPsico3.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 3: '.htmlentities($datos['psico_31']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                if ( trim($datos['psico_32']) != '' )
                {
                    $informDataHistoryPsico3.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 4: '.htmlentities($datos['psico_32']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                if ( trim($datos['psico_33']) != '' )
                {
                    $informDataHistoryPsico3.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 97%" >
                                  <br>Antecedente 5: '.htmlentities($datos['psico_33']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                    
                if ( trim($informDataHistoryPsico1) != '' || trim($informDataHistoryPsico2) != '' || trim($informDataHistoryPsico3) != '' )
                {
                    $informDataHistory.= '
                    <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                          <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 100%" colspan="2">
                                  <br><strong>Antecedentes Personales Psicológicos:</strong>
                              </td>
                          </tr>
                    </table>';
                }
            
                // Verificación para ingreso de bloque completo de antecedentes personales psicologicos
                if ( trim($informDataHistoryPsico1) != '' )
                {
            
                    $informDataHistory.= '
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                              <tr style="font-size: 50%;">
                                  <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                      <br>
                                  </td>
                                  <td style="text-align: justify; width: 97%">
                                      <br><strong>Psicológicos:</strong>
                                  </td>
                              </tr>
                        </table>
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                            '.$informDataHistoryPsico1.'
                        </table>';
                }
                
                // Verificación para ingreso de bloque completo de antecedentes personales psicologicos
                if ( trim($informDataHistoryPsico2) != '' )
                {
                    $informDataHistory.= '
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                              <tr style="font-size: 50%;">
                                  <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                      <br>
                                  </td>
                                  <td style="text-align: justify; width: 97%">
                                      <br><strong>Psicológicos:</strong>
                                  </td>
                              </tr>
                        </table>
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                            '.$informDataHistoryPsico2.'
                        </table>';
                }
            
                // Verificación para ingreso de bloque completo de antecedentes personales psicologicos
                if ( trim($informDataHistoryPsico3) != '' )
                {
                    $informDataHistory.= '
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                              <tr style="font-size: 50%;">
                                  <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                      <br>
                                  </td>
                                  <td style="text-align: justify; width: 97%">
                                      <br><strong>Psicológicos:</strong>
                                  </td>
                              </tr>
                        </table>
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                            '.$informDataHistoryPsico3.'
                        </table>';
                }
                     
                /*$informDataHistory.= '     
                      </table>';*/
            
                $informDataHistoryPsico4 = '';
                

            
                if ( ( isset($datos['psico_34']) && $datos['psico_34'] != '' ) || $datos['psico_35'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_34']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_35']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_36']) && $datos['psico_36'] != '' ) || $datos['psico_37'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_36']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_37']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_38']) && $datos['psico_38'] != '' ) || $datos['psico_39'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_38']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_39']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_40']) && $datos['psico_40'] != '' ) || $datos['psico_41'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_40']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_41']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_42']) && $datos['psico_42'] != '' ) || $datos['psico_43'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_42']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_43']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_44']) && $datos['psico_44'] != '' ) || $datos['psico_45'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_44']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_45']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_46']) && $datos['psico_46'] != '' ) || $datos['psico_47'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_46']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_47']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
            
                if ( ( isset($datos['psico_48']) && $datos['psico_48'] != '' ) || $datos['psico_49'] != '' )
                {
                    $informDataHistoryPsico4.= '
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; width: 3%" ><!-- Espacio definido como tabulador -->
                                  <br>
                              </td>
                              <td style="text-align: justify; width: 10%" ><!-- Espacio definido como tabulador -->
                                  <br>'.htmlentities($datos['psico_48']).'
                              </td>
                              <td style="text-align: justify; width: 87%" >
                                  <br>'.htmlentities($datos['psico_49']).'
                                  <br>
                              </td>
                          </tr>
                    ';
                }
                          
                          
                        
                          
                // Validación para ingreso de texto relacionado a antecedentes familiares psicologicos       
                if ( $informDataHistoryPsico4 != '' )
                {
                    $informDataHistory.= '
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                              <tr style="font-size: 50%;">
                                  <td style="text-align: justify; width: 100%" colspan="2">
                                      <br><strong>Antecedentes Familiares Psicológicos:</strong>
                                  </td>
                              </tr>
                        </table>
                        <table style="padding: 2px; border: 0,5px solid black; width: 100%">
                            '.$informDataHistoryPsico4.'
                        </table>
                    ';
                }
                
                $data203 = '';
            
                if ( isset($datos['psico_203']) )
                    $data203 = htmlentities($datos['psico_203']);
            
                // Bloque de Antecedentes Datos Socio Demográficos
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 60%; line-height: 9px">
                              <td style="text-align: center;"  colspan="4">
                                  <br><br><strong>Datos Socio Demográficos:</strong>
                              </td>
                          </tr>
                          <tr style="font-size: 50%;">
                              <td style="text-align: justify; border: 0,5px solid black;  width: 10%" >
                                  <br><strong>Hijos:</strong>
                                  <br>'.htmlentities($datos['psico_199']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;  width: 30%">
                                  <br><strong>Observación:</strong>
                                  <br>'.htmlentities($datos['psico_201']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;  width: 20%">
                                  <br><strong>Adherencia Farmacológica:</strong>
                                  <br>'.htmlentities($datos['psico_356']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;  width: 40%">
                                  <br><strong>Observación:</strong>
                                  <br>'.htmlentities($datos['psico_202']).'
                              </td>
                          </tr>
                          <tr style="font-size: 50%;">
                              <td style="text-align: justify; border: 0,5px solid black;">
                                  <br><strong>Tipo de Familia:</strong>
                                  <br>'.htmlentities($datos['psico_357']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;">
                                  <br><strong>Observación:</strong>
                                  <br>'.$data203.'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;" >
                                  <br><strong>Características Laborales:</strong>
                                  <br>'.htmlentities($datos['psico_204']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;">
                                  <br><strong>Actividades al Tiempo Libre:</strong>
                                  <br>'.htmlentities($datos['psico_205']).'
                              </td>
                          </tr>
                          <tr style="font-size: 50%;">
                              <td style="text-align: justify; border: 0,5px solid black; width:25%">
                                  <br><strong>Acompañante:</strong>
                                  <br>'.htmlentities($datos['psico_206']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black; width:15%" >
                                  <br><strong>Adherencia al Modelo:</strong>
                                  <br>'.htmlentities($datos['psico_358']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black; width:60%">
                                  <br><strong>Observación:</strong>
                                  <br>'.htmlentities($datos['psico_207']).'
                              </td>
                          </tr>
                          <tr style="font-size: 50%;">
                              <td style="text-align: justify; border: 0,5px solid black; width:50%">
                                  <br><strong>Personas con quien reside:</strong>
                                  <br>'.htmlentities($datos['psico_208']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black; width:50%" >
                                  <br><strong>Quién reporta en consulta:</strong>
                                  <br>'.htmlentities($datos['psico_209']).'
                              </td>
                          </tr>
                    </table>
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 60%; line-height: 9px">
                              <td style="text-align: justify;"  colspan="4">
                                  <br><br><strong>Red de Apoyo</strong>
                              </td>
                        </tr>
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; border: 0,5px solid black;  width: 50%" >
                                  <br><strong>Familiar:</strong>
                                  <br>'.htmlentities($datos['psico_210']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;  width: 50%">
                                  <br><strong>Social:</strong>
                                  <br>'.htmlentities($datos['psico_211']).'
                              </td>
                        </tr>
                        <tr style="font-size: 50%;">
                              <td style="text-align: justify; border: 0,5px solid black;  width: 50%">
                                  <br><strong>Laboral:</strong>
                                  <br>'.htmlentities($datos['psico_212']).'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black;  width: 50%">
                                  <br><strong>Académica:</strong>
                                  <br>'.htmlentities($datos['psico_213']).'
                              </td>
                          </tr>
                    </table>
                ';

            
                break;
            
            
            
            case 9: // Historia clinica a partir de información de Dermatología
                $haveAntecents  = true;
               // Variables relacionadas al acompañante
               $acompañante    = htmlentities( $datos['derm_0'] );
               $parentezco     = htmlentities( $datos['derm_2'] );
               $telefonoAcom   = htmlentities( $datos['derm_3'] );
           
             // Variables que representan signos vitales
               $frecCardiaca   = $datos['derm_484'];
               $frecRespira    = $datos['derm_485'];
               $temperatura    = $datos['derm_486'];
               $tenArterial    = $datos['derm_483'];
           
               // Variables que representan examen fisico
               $peso       = $datos['derm_1887'];
               $talla      = $datos['derm_1888'];
               $imc        = $datos['derm_1889'];
          
               $estadoGeneral = $datos['derm_487'];
               $estadoGeneralObse = $datos['derm_488'];
               
               $orl = $datos['derm_1891'];
               $orlDesc =$datos['fisia_1892'];
               $cabeCuello =$datos['derm_489'];
               $cabeCuelloDesc=$datos['derm_90'];;
               $cardiaco =$datos['derm_1897'];
               $cardiacoDesc =$datos['derm_1898'];
               $pulmonar =$datos['derm_1900'];
               $pulmonarDesc =$datos['derm_1901'];
               $abdomen =$datos['derm_493'];
               $abdomenDesc =$datos['derm_494'];
               $extremidad =$datos['derm_497'];
               $extremidadDesc =$datos['derm_498']; 
               $piel =$datos['derm_505'];
               $pielDesc =$datos['derm_506'];
               $neuro =$datos['derm_499'];
               $neuroDesc =$datos['derm_500'];
               $genito =$datos['derm_495']; 
               $genitoDesc =$datos['derm_496']; 
               $metabolico =$datos['derm_503']; 
               $metabolicoDesc =$datos['derm_504']; 
               $vascular =$datos['derm_501']; 
               $vascularDesc =$datos['derm_502']; 
               $otrosOtros = $datos['derm_1918']; 
                    
            
                
                                
                       
                $informDataHistory = '';


                $examenFisOtros = '
              
                            <table>           
                            <tr style="font-size: 55%">
                                <td style="width: 15%;"><strong>Frec. Cardíaca: </strong>'.$frecCardiaca.'</td>
                                <td style="width: 15%;"><strong>Frec. Respiratoria: </strong> '.$frecRespira.'</td>
                                <td style="width: 15%;"><strong>Temperatura: </strong> '.$temperatura.'</td>
                                <td style="width: 15%;"><strong>Tensión Arterial: </strong>  '.$tenArterial .'</td>
                           
                                <td style="width: 15%;"><strong>Peso: </strong>'.$peso.'</td>
                                <td style="width: 15%;"><strong>Talla: </strong> '.$talla.'</td>
                                <td style="width: 10%;"><strong>IMC: </strong> '.$imc.'</td>
                            </tr>
                          
                           
                        </table>   
                        <table>
                        
                          <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>ORL: </strong> '.$orl.' '.$orlDesc.'</td>
                            <td style="width: 50%;"><strong>Cabeza y cuello:</strong> '.$cabeCuello.' '.htmlentities($cabeCuelloDesc).'</td>
                        </tr>
                     
                        
                        <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Cardíaco: </strong> '.$cardiaco.' '.$cardiacoDesc.'</td>
                            <td style="width: 50%;"><strong>Pulmonar:</strong> '.$pulmonar.' '.htmlentities($pulmonarDesc).'</td>
                        </tr>
                 
                        
                        <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Abdomen: </strong>'.$abdomen.' '.htmlentities($abdomenDesc).'</td>
                            <td style="width: 50%;"><strong>Extremidades: </strong>'.$extremidad.' '.htmlentities($extremidadDesc).'</td>
                        </tr>
                          
                          <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Piel: </strong>'.$piel.' '.htmlentities($pielDesc).'</td>
                            <td style="width: 50%;"><strong>Neurológico: </strong>'.$neuro.' '.htmlentities($neuroDesc).'</td>
                          </tr> 
                          <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Genito urinario  : </strong>'.$genito.' '.htmlentities($genitoDesc).'</td>
                            <td style="width: 50%;"><strong>Metabolico  : </strong>'.$metabolico.' '.htmlentities($metabolicoDesc).'</td>
                            <td></td>
                          </tr >
                         <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Vascular:  </strong>'.$vascular.' '.htmlentities($vascularDesc).'</td>
                            <td style="width: 50%;"><strong>Estado General:</strong>  '.$estadoGeneral .' '.htmlentities($estadoGeneralObse).'</td>


                          </tr>
                          
                            <tr style="font-size: 55%">
                             <td style="width: 50%;"><strong>Otros:  </strong>'. htmlentities($otrosOtros).'</td>
                             </tr>
                          
                        
                        </table>';
                              
                /*$antecedente = '
                    <table> 
                          
                            
                           
                           <tr style="font-size: 55%">
                                <td style="width: 50%;"><strong>Atrosis </strong>'.$artrosis.'  '. $artrosisObs.' </td>
                                <td style="width: 50%;"><strong>Osteoporosis:</strong> '.$Osteoporosis.'  '.$OsteoporosisObs.'</td>
                       
                            </tr>
                          <tr style="font-size: 55%">
                                <td style="width: 50%;"><strong>Fibromalgia </strong>'.$fibromalgia.'  '. $fibromalgiaObs.' </td>       
                                <td style="width: 50%;"><strong>Lupus </strong>'.$lups.'  '. $lupusObs.' </td>
                               
                          </tr>
                         <tr style="font-size: 55%">
                            
                                <td style="width: 50%;"><strong>Artritis Rematoide:</strong> '.$Arematoide.'  '.$ArematoideObs.'</td>
                                <td style="width: 50%;"><strong>SJORGEN </strong>'.$sjorgen.'  '. $sjorgenObs.' </td>
                              </tr>   
                            
                                <tr style="font-size: 55%">
                            
                                <td style="width: 50%;"><strong>SPA:</strong> '.$spa.'  '.$spaObs.'</td>
                                <td style="width: 50%;"><strong>Esclerodermia </strong>'.$esclerodermia.'  '. $esclerodermiaObs.' </td>
                            </tr>                      
                        </table>
                        
                        '; 
                
                        $infoAntecedentes.= '
                   <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 10%">
                            <td style="text-align: center;" >

                            </td>
                        </tr>
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" >
                                <br><strong> Antecedentes Personales</strong>
                            </td>
                        </tr>
                          <tr>
                           <td style="border: 0,5px solid black;" >
                                '.$antecedente.'
                            </td>
                        </tr>
                        </table>
                  ';*/

            
                  
                   // Motivo de consulta
                $motivoConsulta =htmlentities($datos['derm_6']);//$datos['nut_11'];
                $enfermedadActual = htmlentities( $datos['derm_7']);
            
                  // Variable de evolucion
                  $evolucion = '';


                  $planDiag = htmlentities( $datos['reuma_2134']);
                  $planTrat = htmlentities( $datos['reuma_2135']);
                  $analisis = htmlentities( $datos['reuma_2133']);

          
                //$planDiag = $datos['derm_517'];
                //$planTrat = $datos['derm_518'];
                //$analisis = $datos['derm_516'];
            
                // Atención supervisada
                $atenSuper = 0;
            
                // Datos adicionales del paciente
                $ocupacion = '';
            
                // Clinimetrías
                $climinetria = '';
            
                break;
            
               
            case 10: // Historia clinica a partir de información de Ecografía
                $haveAntecents  = false;
                $nameFormato = 'PROCEDIMIENTO';
            
                // Variables relacionadas al acompañante
                $acompañante    = $datos['eco_6'];
                $parentezco     = $datos['eco_8'];
                $telefonoAcom   = $datos['eco_9'];
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
                $examenFisOtros = '';
            
                // Variable de evolucion
                $evolucion = '';
                
                             // Motivo de consulta
                $motivoConsulta = htmlentities( $datos['derm_6']);

                $planDiag =  htmlentities($datos['eco_2036']);
                $planTrat =  htmlentities($datos['eco_2037']);
                $analisis = htmlentities( $datos['eco_2035']);
            
                // Atención supervisada
                $atenSuper = 0;
            
                // Datos adicionales del paciente
                $ocupacion = '';
            
                // Clinimetrías
                $climinetria = '';

                $informDataHistory='
                    <style>
                    .linea td, tr {
                      border-bottom: rgba(0,0,0,1) solid 0.5px;
                      border-left: rgba(0,0,0,1) solid 0.5px;
                      border-right: rgba(0,0,0,1) solid 0.5px;
                      border-top: rgba(0,0,0,1) solid 0.5px;
                    }

                    .linea {
                      border-bottom: rgba(0,0,0,1) solid 0.5px;
                      border-left: rgba(0,0,0,1) solid 0.5px;
                      border-right: rgba(0,0,0,1) solid 0.5px;
                      border-top: rgba(0,0,0,1) solid 0.5px;
                    }
                    </style>
                ';

            
                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE MUÑECA Y MANO CARPO - DERECHO -- OJO FALTA VALIDAR SI NO HAY INFO

                if($datos['eco_11'] != '' || $datos['eco_13'] != '' || $datos['eco_13'] != '' || $datos['eco_14'] != '' || $datos['eco_15'] != ''
                 || $datos['eco_17'] != '' || $datos['eco_18'] != '' || $datos['eco_19'] != '' || $datos['eco_21'] != ''
                 || $datos['eco_23'] != '' || $datos['eco_25'] != '' || $datos['eco_26'] != ''
                 || $datos['eco_27'] != '' || $datos['eco_29'] != '' || $datos['eco_31'] != ''
                 || $datos['eco_33'] != '' || $datos['eco_34'] != '' || $datos['eco_35'] != ''
                 || $datos['eco_37'] != '' || $datos['eco_39'] != '' || $datos['eco_41'] != ''
                 || $datos['eco_42'] != '' || $datos['eco_43'] != '' || $datos['eco_44'] != ''
                 || $datos['eco_45'] != '' || $datos['eco_47'] != '' || $datos['eco_48'] != ''
                 || $datos['eco_49'] != '' || $datos['eco_50'] != '' || $datos['eco_51'] != ''
                 || $datos['eco_53'] != '' || $datos['eco_54'] != '' || $datos['eco_55'] != ''
                 || $datos['eco_56'] != '' || $datos['eco_57'] != '' || $datos['eco_59'] != ''
                 || $datos['eco_60'] != '' || $datos['eco_61'] != '' || $datos['eco_62'] != ''
                 || $datos['eco_63'] != '' || $datos['eco_65'] != '' || $datos['eco_67'] != ''
                 || $datos['eco_68'] != '' || $datos['eco_69'] != '' || $datos['eco_71'] != ''
                 || $datos['eco_72'] != '' || $datos['eco_72'] != '' || $datos['eco_73'] != ''
                 || $datos['eco_74'] != '' || $datos['eco_75'] != '' || $datos['eco_77'] != ''
                 || $datos['eco_78'] != '' || $datos['eco_79'] != '' || $datos['eco_80'] != ''
                 ){


                $ecoMuMaCarpoDer = '
                <!--CARPO DERECHO-->
                <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>CARPO DERECHO</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_11"><strong>N.Mediano (área) Normal: </strong>'.$datos['eco_11'].'</td>
                        <td name="eco_13"><strong>Área aumentada (mm): </strong>'.$datos['eco_13'].'</td>
                        <td name="eco_14"><strong>Ecogenicidad: </strong>'.$datos['eco_14'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>PALMAR</strong> </td>
                        <td name="eco_15"><strong>DERRAME: </strong>'.$datos['eco_15'].' </td>
                        <td name="eco_17"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_17'].' </td>
                        <td name="eco_18"><strong>Señal Power Doppler:</strong> '.$datos['eco_18'].'</td>
                        <td name="eco_19"><strong>EROSIONES: </strong> '.$datos['eco_19'].'</td>
                        <td name="eco_21"><strong>Proliferación Ósea:</strong> '.$datos['eco_21'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>DORSAL </strong></td>
                        <td name="eco_23"><strong>DERRAME: </strong>'.$datos['eco_23'].'</td>
                        <td name="eco_25"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_25'].'</td>
                        <td name="eco_26"><strong>Señal Power Doppler: </strong>'.$datos['eco_26'].'</td>
                        <td name="eco_27"><strong>EROSIONES: </strong>'.$datos['eco_27'].'</td>
                        <td name="eco_29"><strong>Proliferación Ósea: </strong>'.$datos['eco_29'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>ATMC </strong></td>
                        <td name="eco_31"><strong>DERRAME: </strong>'.$datos['eco_31'].'</td>
                        <td name="eco_33"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_33'].'</td>
                        <td name="eco_34"><strong>Señal Power Doppler: </strong>'.$datos['eco_34'].'</td>
                        <td name="eco_35"><strong>EROSIONES: </strong>'.$datos['eco_35'].'</td>
                        <td name="eco_37"><strong>Proliferación Ósea: </strong>'.$datos['eco_37'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>FLEXORES </strong></td>
                        <td name="eco_39"><strong>DERRAME: </strong>'.$datos['eco_39'].'</td>
                        <td name="eco_41"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_41'].'</td>
                        <td name="eco_42"><strong>Señal Power Doppler: </strong>'.$datos['eco_42'].'</td>
                        <td name="eco_43"><strong>ECOGENICIDAD: </strong>'.$datos['eco_43'].'</td>
                        <td name="eco_44"><strong>ROTURA: </strong>'.$datos['eco_44'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>ALP/ECP</strong></td>
                        <td><strong>DERRAME:</strong> '.$datos['eco_45'].'</td>
                        <td name="eco_47"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_47'].'</td>
                        <td name="eco_48"><strong>Señal Power Doppler:</strong> '.$datos['eco_48'].'</td>
                        <td name="eco_49"><strong>ECOGENICIDAD:</strong> '.$datos['eco_49'].'</td>
                        <td name="eco_50"><strong>ROTURA:</strong> '.$datos['eco_50'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>RADIALES </strong></td>
                        <td name="eco_51"><strong>DERRAME:</strong> '.$datos['eco_51'].'</td>
                        <td name="eco_53"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_53'].'</td>
                        <td name="eco_54"><strong>Señal Power Doppler:</strong> '.$datos['eco_54'].'</td>
                        <td name="eco_55"><strong>ECOGENICIDAD:</strong> '.$datos['eco_55'].'</td>
                        <td name="eco_56"><strong>ROTURA:</strong> '.$datos['eco_56'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>ELP </strong></td>
                        <td name="eco_57"><strong>DERRAME: </strong>'.$datos['eco_57'].'</td>
                        <td name="eco_59"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_59'].'</td>
                        <td name="eco_60"><strong>Señal Power Doppler: </strong>'.$datos['eco_60'].'</td>
                        <td name="eco_61"><strong>ECOGENICIDAD: </strong>'.$datos['eco_61'].'</td>
                        <td name="eco_62"><strong>ROTURA: </strong>'.$datos['eco_62'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>EXTENSORES </strong></td>
                        <td name="eco_63"><strong>DERRAME: </strong>'.$datos['eco_63'].'</td>
                        <td name="eco_65"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_65'].'</td>
                        <td name="eco_66"><strong>Señal Power Doppler: </strong>'.$datos['eco_66'].'</td>
                        <td name="eco_67"><strong>ECOGENICIDAD: </strong>'.$datos['eco_67'].'</td>
                        <td name="eco_68"><strong>ROTURA: </strong>'.$datos['eco_68'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>EM </strong></td>
                        <td name="eco_69"><strong>DERRAME: </strong>'.$datos['eco_69'].'</td>
                        <td name="eco_71"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_71'].'</td>
                        <td name="eco_72"><strong>Señal Power Doppler: </strong>'.$datos['eco_72'].'</td>
                        <td name="eco_73"><strong>ECOGENICIDAD: </strong>'.$datos['eco_73'].'</td>
                        <td name="eco_74"><strong>ROTURA: </strong>'.$datos['eco_74'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>CUBITAL </strong></td>
                        <td name="eco_75"><strong>DERRAME: </strong>'.$datos['eco_75'].'</td>
                        <td name="eco_77"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_77'].'</td>
                        <td name="eco_78"><strong>Señal Power Doppler: </strong>'.$datos['eco_78'].'</td>
                        <td name="eco_79"><strong>ECOGENICIDAD: </strong>'.$datos['eco_79'].'</td>
                        <td name="eco_80"><strong>ROTURA: </strong>'.$datos['eco_80'].'</td>
                    </tr>
                </table>
                                ';


                    }else{
                        $ecoMuMaCarpoDer='';
                    }
                     // ecografia de muñeca y mano - carpo - izquierdo

                if($datos['eco_81'] != '' || $datos['eco_83'] != '' || $datos['eco_84'] != '' 
                || $datos['eco_85'] != '' || $datos['eco_87'] != ''
                || $datos['eco_88'] != '' || $datos['eco_89'] != '' || $datos['eco_91'] != '' 
                || $datos['eco_93'] != ''
                || $datos['eco_95'] != '' || $datos['eco_96'] != '' || $datos['eco_97'] != ''
                || $datos['eco_99'] != '' || $datos['eco_101'] != '' || $datos['eco_103'] != ''
                || $datos['eco_104'] != '' || $datos['eco_105'] != '' || $datos['eco_107'] != ''
                || $datos['eco_149'] != '' || $datos['eco_150'] != '' 
                || $datos['eco_109'] != '' || $datos['eco_111'] != '' || $datos['eco_112'] != ''
                || $datos['eco_113'] != '' || $datos['eco_114'] != '' || $datos['eco_115'] != ''
                || $datos['eco_117'] != '' || $datos['eco_118'] != '' || $datos['eco_119'] != ''
                || $datos['eco_120'] != '' || $datos['eco_121'] != '' || $datos['eco_123'] != ''
                || $datos['eco_124'] != '' || $datos['eco_125'] != '' || $datos['eco_126'] != ''
                || $datos['eco_127'] != '' || $datos['eco_129'] != '' || $datos['eco_130'] != ''
                || $datos['eco_131'] != '' || $datos['eco_132'] != '' || $datos['eco_133'] != ''
                || $datos['eco_135'] != '' || $datos['eco_136'] != '' || $datos['eco_137'] != ''
                || $datos['eco_138'] != '' || $datos['eco_139'] != '' || $datos['eco_141'] != ''
                || $datos['eco_142'] != '' || $datos['eco_143'] != '' || $datos['eco_144'] != ''
                || $datos['eco_145'] != '' || $datos['eco_147'] != '' || $datos['eco_148'] != ''
                ){
                $ecoMuMaCarpoIzq = '
                <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>CARPO IZQUIERDO</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_81"><strong>N.Mediano (área) Normal:</strong> '.$datos['eco_81'].'</td>
                        <td name="eco_83"><strong>Área aumentada (mm):</strong> '.$datos['eco_83'].'</td>
                        <td name="eco_84"><strong>Ecogenicidad: </strong>'.$datos['eco_84'].' </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>PALMAR</strong> </td>
                        <td name="eco_85"><strong>DERRAME:</strong> '.$datos['eco_85'].'</td>
                        <td name="eco_87"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_87'].'</td>
                        <td name="eco_88"><strong>Señal Power Doppler:</strong> '.$datos['eco_88'].'</td>
                        <td name="eco_89"><strong>EROSIONES:</strong> '.$datos['eco_89'].'</td>
                        <td name="eco_91"><strong>Proliferación Ósea: </strong>'.$datos['eco_91'].' </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>DORSAL</strong> </td>
                        <td name="eco_93"><strong>DERRAME:</strong> '.$datos['eco_93'].'</td>
                        <td name="eco_95"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_95'].'</td>
                        <td name="eco_96"><strong>Señal Power Doppler:</strong> '.$datos['eco_96'].'</td>
                        <td name="eco_97"><strong>EROSIONES: </strong>'.$datos['eco_97'].' </td>
                        <td name="eco_99"><strong>Proliferación Ósea: </strong>'.$datos['eco_99'].' </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>ATMC</strong> </td>
                        <td name="eco_101"><strong>DERRAME:</strong> '.$datos['eco_101'].'</td>
                        <td name="eco_103"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_103'].'</td>
                        <td name="eco_104"><strong>Señal Power Doppler:</strong> '.$datos['eco_104'].'</td>
                        <td name="eco_105"><strong>EROSIONES: </strong>'.$datos['eco_105'].' </td>
                        <td name="eco_107"><strong>Proliferación Ósea: </strong>'.$datos['eco_107'].' </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>FLEXORES</strong> </td>
                        <td name="eco_109"><strong>DERRAME:</strong> '.$datos['eco_109'].'</td>
                        <td name="eco_111"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_111'].'</td>
                        <td name="eco_112"><strong>Señal Power Doppler:</strong> '.$datos['eco_112'].'</td>
                        <td name="eco_113"><strong>ECOGENICIDAD:</strong> '.$datos['eco_113'].'</td>
                        <td name="eco_114"><strong>ROTURA:</strong> '.$datos['eco_114'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>ALP/EC</strong>P</td>
                        <td name="eco_115"><strong>DERRAME:</strong> '.$datos['eco_115'].'</td>
                        <td name="eco_117"><strong>Hipertrofia Sinovial:</strong> '.$datos['eco_117'].'</td>
                        <td name="eco_118"><strong>Señal Power Doppler:</strong> '.$datos['eco_118'].'</td>
                        <td name="eco_119"><strong>ECOGENICIDAD:</strong> '.$datos['eco_119'].'</td>
                        <td name="eco_120"><strong>ROTURA:</strong> '.$datos['eco_120'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>RADIALES</strong> </td>
                        <td name="eco_121"><strong>DERRAME: </strong>'.$datos['eco_121'].'</td>
                        <td name="eco_123"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_123'].'</td>
                        <td name="eco_124"><strong>Señal Power Doppler: </strong>'.$datos['eco_124'].'</td>
                        <td name="eco_125"><strong>ECOGENICIDAD: </strong>'.$datos['eco_125'].'</td>
                        <td name="eco_126"><strong>ROTURA: </strong>'.$datos['eco_126'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>ELP</strong> </td>
                        <td name="eco_127"><strong>DERRAME: </strong>'.$datos['eco_127'].'</td>
                        <td name="eco_129"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_129'].'</td>
                        <td name="eco_130"><strong>Señal Power Doppler: </strong>'.$datos['eco_130'].'</td>
                        <td name="eco_131"><strong>ECOGENICIDAD: </strong>'.$datos['eco_131'].'</td>
                        <td name="eco_132"><strong>ROTURA: </strong>'.$datos['eco_132'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>EXTENSORES</strong> </td>
                        <td name="eco_133"><strong>DERRAME: </strong>'.$datos['eco_133'].'</td>
                        <td name="eco_135"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_135'].'</td>
                        <td name="eco_136"><strong>Señal Power Doppler: </strong>'.$datos['eco_136'].'</td>
                        <td name="eco_137"><strong>ECOGENICIDAD: </strong>'.$datos['eco_137'].'</td>
                        <td name="eco_138"><strong>ROTURA: </strong>'.$datos['eco_138'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>EM</strong> </td>
                        <td name="eco_139"><strong>DERRAME: </strong>'.$datos['eco_139'].'</td>
                        <td name="eco_141"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_141'].'</td>
                        <td name="eco_142"><strong>Señal Power Doppler: </strong>'.$datos['eco_142'].'</td>
                        <td name="eco_143"><strong>ECOGENICIDAD: </strong>'.$datos['eco_143'].'</td>
                        <td name="eco_144"><strong>ROTURA: </strong>'.$datos['eco_144'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>CUBITAL</strong> </td>
                        <td name="eco_145"><strong>DERRAME: </strong>'.$datos['eco_145'].'</td>
                        <td name="eco_147"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_147'].'</td>
                        <td name="eco_148"><strong>Señal Power Doppler: </strong>'.$datos['eco_148'].'</td>
                        <td name="eco_149"><strong>ECOGENICIDAD: </strong>'.$datos['eco_149'].'</td>
                        <td name="eco_150"><strong>ROTURA: </strong>'.$datos['eco_150'].'</td>
                    </tr>


                </table>
                    ';


                }else{
                $ecoMuMaCarpoIzq='';
                }


                     // ecografia de muñeca y mano - manos - derecha

                     if($datos['eco_151'] != '' || $datos['eco_153'] != '' || $datos['eco_154'] != '' 
                     || $datos['eco_155'] != '' || $datos['eco_157'] != ''
                     || $datos['eco_159'] != '' || $datos['eco_161'] != '' || $datos['eco_162'] != '' 
                     || $datos['eco_163'] != ''
                     || $datos['eco_165'] != '' || $datos['eco_167'] != '' || $datos['eco_169'] != ''
                     || $datos['eco_170'] != '' || $datos['eco_171'] != '' || $datos['eco_173'] != ''
                     || $datos['eco_175'] != '' || $datos['eco_177'] != '' || $datos['eco_178'] != ''
                     || $datos['eco_179'] != '' || $datos['eco_181'] != '' || $datos['eco_185'] != ''
                     || $datos['eco_183'] != '' || $datos['eco_186'] != '' || $datos['eco_187'] != ''
                     || $datos['eco_189'] != '' || $datos['eco_191'] != '' || $datos['eco_193'] != ''
                     || $datos['eco_194'] != '' || $datos['eco_195'] != '' || $datos['eco_197'] != ''
                     || $datos['eco_199'] != '' || $datos['eco_201'] != '' || $datos['eco_202'] != ''
                     || $datos['eco_203'] != '' || $datos['eco_205'] != '' || $datos['eco_207'] != ''
                     || $datos['eco_209'] != '' || $datos['eco_210'] != '' || $datos['eco_211'] != ''
                     || $datos['eco_213'] != '' || $datos['eco_215'] != '' || $datos['eco_217'] != ''
                     || $datos['eco_218'] != '' || $datos['eco_219'] != '' || $datos['eco_221'] != ''
                     || $datos['eco_223'] != '' || $datos['eco_225'] != '' || $datos['eco_226'] != ''
                     || $datos['eco_227'] != '' || $datos['eco_229'] != '' || $datos['eco_231'] != ''
                     || $datos['eco_233'] != '' || $datos['eco_235'] != '' || $datos['eco_237'] != ''
                     || $datos['eco_239'] != '' || $datos['eco_241'] != '' || $datos['eco_242'] != ''
                     || $datos['eco_243'] != '' || $datos['eco_245'] != '' || $datos['eco_247'] != ''
                     || $datos['eco_249'] != '' || $datos['eco_250'] != '' || $datos['eco_251'] != ''
                     || $datos['eco_253'] != '' || $datos['eco_255'] != '' || $datos['eco_257'] != ''
                     || $datos['eco_258'] != '' || $datos['eco_259'] != '' || $datos['eco_261'] != ''
                     || $datos['eco_263'] != '' || $datos['eco_265'] != '' || $datos['eco_266'] != ''
                     || $datos['eco_267'] != '' || $datos['eco_269'] != '' || $datos['eco_271'] != ''
                     || $datos['eco_273'] != '' || $datos['eco_274'] != '' || $datos['eco_275'] != ''
                     || $datos['eco_276'] != '' || $datos['eco_277'] != '' || $datos['eco_279'] != ''
                     || $datos['eco_280'] != '' || $datos['eco_281'] != '' || $datos['eco_282'] != ''
                     || $datos['eco_283'] != '' || $datos['eco_285'] != '' || $datos['eco_286'] != ''
                     || $datos['eco_287'] != '' || $datos['eco_288'] != '' || $datos['eco_289'] != ''
                     || $datos['eco_291'] != '' || $datos['eco_292'] != '' || $datos['eco_293'] != ''
                     || $datos['eco_294'] != '' || $datos['eco_295'] != '' || $datos['eco_297'] != ''
                     || $datos['eco_298'] != '' || $datos['eco_299'] != '' || $datos['eco_300'] != ''
                     ){
                $ecoMuManoDer = '
                <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                     <tr style="font-size: 55%; line-height: 9px">
                         <td style="text-align: center;padding: 15px;" colspan="6">
                             <span><strong>MANO DERECHA</strong></span>
                         </td>
                     </tr>
                     <tr style="font-size: 50%;">

                        <td><strong>1MCF</strong></td>
                        <td name="eco_151"><strong>DERRAME: </strong>'.$datos['eco_151'].'</td>
                        <td name="eco_153"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_153'].'</td>
                        <td name="eco_154"><strong>Señal Power Doppler: </strong>'.$datos['eco_154'].'</td>
                        <td name="eco_155"><strong>EROSIONES: </strong>'.$datos['eco_155'].'</td>
                        <td name="eco_157"><strong>Proliferación Osea: </strong>'.$datos['eco_157'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>2MCF</strong></td>
                        <td name="eco_159"><strong>DERRAME: </strong>'.$datos['eco_159'].'</td>
                        <td name="eco_161"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_161'].'</td>
                        <td name="eco_162"><strong>Señal Power Doppler: </strong>'.$datos['eco_162'].'</td>
                        <td name="eco_163"><strong>EROSIONES: </strong>'.$datos['eco_163'].'</td>
                        <td name="eco_165"><strong>Proliferación Osea: </strong>'.$datos['eco_165'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>3MCF</strong></td>
                        <td name="eco_167"><strong>DERRAME: </strong>'.$datos['eco_167'].'</td>
                        <td name="eco_169"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_169'].'</td>
                        <td name="eco_170"><strong>Señal Power Doppler: </strong>'.$datos['eco_170'].'</td>
                        <td name="eco_171"><strong>EROSIONES: </strong>'.$datos['eco_171'].'</td>
                        <td name="eco_173"><strong>Proliferación Osea: </strong>'.$datos['eco_173'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>4MCF</strong></td>
                        <td name="eco_175"><strong>DERRAME: </strong>'.$datos['eco_175'].'</td>
                        <td name="eco_177"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_177'].'</td>
                        <td name="eco_178"><strong>Señal Power Doppler: </strong>'.$datos['eco_178'].'</td>
                        <td name="eco_179"><strong>EROSIONES: </strong>'.$datos['eco_179'].'</td>
                        <td name="eco_181"><strong>Proliferación Osea: </strong>'.$datos['eco_181'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>5MCF</strong></td>
                        <td name="eco_183"><strong>DERRAME: </strong>'.$datos['eco_183'].'</td>
                        <td name="eco_185"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_185'].'</td>
                        <td name="eco_186"><strong>Señal Power Doppler: </strong>'.$datos['eco_186'].'</td>
                        <td name="eco_187"><strong>EROSIONES: </strong>'.$datos['eco_187'].'</td>
                        <td name="eco_189"><strong>Proliferación Osea: </strong>'.$datos['eco_189'].'</td>
                    </tr>

                    <!--bloque 2-->
                    <tr style="font-size: 50%;">
                        <td><strong>1IFP</strong></td>
                        <td name="eco_191"><strong>DERRAME: </strong>'.$datos['eco_191'].'</td>
                        <td name="eco_193"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_193'].'</td>
                        <td name="eco_194"><strong>Señal Power Doppler: </strong>'.$datos['eco_194'].'</td>
                        <td name="eco_195"><strong>EROSIONES: </strong>'.$datos['eco_195'].'</td>
                        <td name="eco_197"><strong>Proliferación Osea: </strong>'.$datos['eco_197'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>2IFP</strong></td>
                        <td name="eco_199"><strong>DERRAME: </strong>'.$datos['eco_199'].'</td>
                        <td name="eco_201"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_201'].'</td>
                        <td name="eco_202"><strong>Señal Power Doppler: </strong>'.$datos['eco_202'].'</td>
                        <td name="eco_203"><strong>EROSIONES: </strong>'.$datos['eco_203'].'</td>
                        <td name="eco_205"><strong>Proliferación Osea: </strong>'.$datos['eco_205'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>3IFP</strong></td>
                        <td name="eco_207"><strong>DERRAME: </strong>'.$datos['eco_207'].'</td>
                        <td name="eco_209"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_209'].'</td>
                        <td name="eco_210"><strong>Señal Power Doppler: </strong>'.$datos['eco_210'].'</td>
                        <td name="eco_211"><strong>EROSIONES: </strong>'.$datos['eco_211'].'</td>
                        <td name="eco_213"><strong>Proliferación Osea: </strong>'.$datos['eco_213'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>4IFP</strong></td>
                        <td name="eco_215"><strong>DERRAME: </strong>'.$datos['eco_215'].'</td>
                        <td name="eco_217"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_217'].'</td>
                        <td name="eco_218"><strong>Señal Power Doppler: </strong>'.$datos['eco_218'].'</td>
                        <td name="eco_219"><strong>EROSIONES: </strong>'.$datos['eco_219'].'</td>
                        <td name="eco_221"><strong>Proliferación Osea: </strong>'.$datos['eco_221'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>5IFP</strong></td>
                        <td name="eco_223"><strong>DERRAME: </strong>'.$datos['eco_223'].'</td>
                        <td name="eco_225"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_225'].'</td>
                        <td name="eco_226"><strong>Señal Power Doppler: </strong>'.$datos['eco_226'].'</td>
                        <td name="eco_227"><strong>EROSIONES: </strong>'.$datos['eco_227'].'</td>
                        <td name="eco_229"><strong>Proliferación Osea: </strong>'.$datos['eco_229'].'</td>
                    </tr>
                    <!--bloque 3-->

                    <tr style="font-size: 50%;">
                        <td><strong>1IFD</strong></td>
                        <td name="eco_231"><strong>DERRAME: </strong>'.$datos['eco_231'].'</td>
                        <td name="eco_233"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_233'].'</td>
                        <td name="eco_234"><strong>Señal Power Doppler: </strong>'.$datos['eco_234'].'</td>
                        <td name="eco_235"><strong>EROSIONES: </strong>'.$datos['eco_235'].'</td>
                        <td name="eco_237"><strong>Proliferación Osea: </strong>'.$datos['eco_237'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>2IFD</strong></td>
                        <td name="eco_239"><strong>DERRAME: </strong>'.$datos['eco_239'].'</td>
                        <td name="eco_241"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_241'].'</td>
                        <td name="eco_242"><strong>Señal Power Doppler: </strong>'.$datos['eco_242'].'</td>
                        <td name="eco_243"><strong>EROSIONES: </strong>'.$datos['eco_243'].'</td>
                        <td name="eco_245"><strong>Proliferación Osea: </strong>'.$datos['eco_245'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>3IFD</strong></td>
                        <td name="eco_247"><strong>DERRAME: </strong>'.$datos['eco_247'].'</td>
                        <td name="eco_249"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_249'].'</td>
                        <td name="eco_250"><strong>Señal Power Doppler: </strong>'.$datos['eco_250'].'</td>
                        <td name="eco_251"><strong>EROSIONES: </strong>'.$datos['eco_251'].'</td>
                        <td name="eco_253"><strong>Proliferación Osea: </strong>'.$datos['eco_253'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>4IFD</strong></td>
                        <td name="eco_255"><strong>DERRAME: </strong>'.$datos['eco_255'].'</td>
                        <td name="eco_257"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_257'].'</td>
                        <td name="eco_258"><strong>Señal Power Doppler: </strong>'.$datos['eco_258'].'</td>
                        <td name="eco_259"><strong>EROSIONES: </strong>'.$datos['eco_259'].'</td>
                        <td name="eco_261"><strong>Proliferación Osea: </strong>'.$datos['eco_261'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>5IFD</strong></td>
                        <td name="eco_263"><strong>DERRAME: </strong>'.$datos['eco_263'].'</td>
                        <td name="eco_265"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_265'].'</td>
                        <td name="eco_266"><strong>Señal Power Doppler: </strong>'.$datos['eco_266'].'</td>
                        <td name="eco_267"><strong>EROSIONES: </strong>'.$datos['eco_267'].'</td>
                        <td name="eco_269"><strong>Proliferación Osea: </strong>'.$datos['eco_269'].'</td>
                    </tr>
                    <!--inicio bloque 4-->
                    <tr style="font-size: 50%;">
                        <td><strong>1TDF</strong></td>
                        <td name="eco_271"><strong>DERRAME: </strong>'.$datos['eco_271'].'</td>
                        <td name="eco_273"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_273'].'</td>
                        <td name="eco_274"><strong>Señal Power Doppler: </strong>'.$datos['eco_274'].'</td>
                        <td name="eco_275"><strong>EROSIONES: </strong>'.$datos['eco_275'].'</td>
                        <td name="eco_276"><strong>Proliferación Osea: </strong>'.$datos['eco_276'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>2TDF</strong></td>
                        <td name="eco_277"><strong>DERRAME: </strong>'.$datos['eco_277'].'</td>
                        <td name="eco_279"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_279'].'</td>
                        <td name="eco_280"><strong>Señal Power Doppler: </strong>'.$datos['eco_280'].'</td>
                        <td name="eco_281"><strong>Ecogenicidad: </strong>'.$datos['eco_281'].'</td>
                        <td name="eco_282"><strong>Rotura: </strong>'.$datos['eco_282'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>3TDF</strong></td>
                        <td name="eco_283"><strong>DERRAME: </strong>'.$datos['eco_283'].'</td>
                        <td name="eco_285"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_285'].'</td>
                        <td name="eco_286"><strong>Señal Power Doppler: </strong>'.$datos['eco_286'].'</td>
                        <td name="eco_287"><strong>Ecogenicidad: </strong>'.$datos['eco_287'].'</td>
                        <td name="eco_288"><strong>Rotura: </strong>'.$datos['eco_288'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>4TDF</strong></td>
                        <td name="eco_289"><strong>DERRAME: </strong>'.$datos['eco_289'].'</td>
                        <td name="eco_291"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_291'].'</td>
                        <td name="eco_292"><strong>Señal Power Doppler: </strong>'.$datos['eco_292'].'</td>
                        <td name="eco_293"><strong>Ecogenicidad: </strong>'.$datos['eco_293'].'</td>
                        <td name="eco_294"><strong>Rotura: </strong>'.$datos['eco_294'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td><strong>5TDF</strong></td>
                        <td name="eco_295"><strong>DERRAME: </strong>'.$datos['eco_295'].'</td>
                        <td name="eco_297"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_297'].'</td>
                        <td name="eco_298"><strong>Señal Power Doppler: </strong>'.$datos['eco_298'].'</td>
                        <td name="eco_299"><strong>Ecogenicidad: </strong>'.$datos['eco_299'].'</td>
                        <td name="eco_300"><strong>Rotura: </strong>'.$datos['eco_300'].'</td>
                    </tr>

                     </table>
                         ';


                     }else{
                     $ecoMuManoDer='';
                     }

                     // ecografia de muñeca y mano - manos - izquierda

                     if($datos['eco_301'] != '' || $datos['eco_303'] != '' || $datos['eco_304'] != '' 
                     || $datos['eco_305'] != '' || $datos['eco_307'] != ''
                     || $datos['eco_309'] != '' || $datos['eco_311'] != '' || $datos['eco_312'] != '' 
                     || $datos['eco_313'] != ''
                     || $datos['eco_315'] != '' || $datos['eco_317'] != '' || $datos['eco_319'] != ''
                     || $datos['eco_320'] != '' || $datos['eco_321'] != '' || $datos['eco_323'] != ''
                     || $datos['eco_325'] != '' || $datos['eco_327'] != '' || $datos['eco_328'] != ''
                     || $datos['eco_329'] != '' || $datos['eco_331'] != '' || $datos['eco_335'] != ''
                     || $datos['eco_333'] != '' || $datos['eco_336'] != '' || $datos['eco_337'] != ''
                     || $datos['eco_339'] != '' || $datos['eco_341'] != '' || $datos['eco_343'] != ''
                     || $datos['eco_344'] != '' || $datos['eco_345'] != '' || $datos['eco_347'] != ''
                     || $datos['eco_349'] != '' || $datos['eco_351'] != '' || $datos['eco_352'] != ''
                     || $datos['eco_353'] != '' || $datos['eco_355'] != '' || $datos['eco_357'] != ''
                     || $datos['eco_359'] != '' || $datos['eco_360'] != '' || $datos['eco_361'] != ''
                     || $datos['eco_363'] != '' || $datos['eco_365'] != '' || $datos['eco_367'] != ''
                     || $datos['eco_368'] != '' || $datos['eco_369'] != '' || $datos['eco_371'] != ''
                     || $datos['eco_373'] != '' || $datos['eco_375'] != '' || $datos['eco_376'] != ''
                     || $datos['eco_377'] != '' || $datos['eco_379'] != '' || $datos['eco_381'] != ''
                     || $datos['eco_383'] != '' || $datos['eco_385'] != '' || $datos['eco_387'] != ''
                     || $datos['eco_389'] != '' || $datos['eco_391'] != '' || $datos['eco_392'] != ''
                     || $datos['eco_393'] != '' || $datos['eco_395'] != '' || $datos['eco_397'] != ''
                     || $datos['eco_399'] != '' || $datos['eco_400'] != '' || $datos['eco_401'] != ''
                     || $datos['eco_403'] != '' || $datos['eco_405'] != '' || $datos['eco_407'] != ''
                     || $datos['eco_408'] != '' || $datos['eco_409'] != '' || $datos['eco_411'] != ''
                     || $datos['eco_413'] != '' || $datos['eco_415'] != '' || $datos['eco_416'] != ''
                     || $datos['eco_417'] != '' || $datos['eco_419'] != '' || $datos['eco_421'] != ''
                     || $datos['eco_423'] != '' || $datos['eco_424'] != '' || $datos['eco_425'] != ''
                     || $datos['eco_426'] != '' || $datos['eco_427'] != '' || $datos['eco_429'] != ''
                     || $datos['eco_430'] != '' || $datos['eco_431'] != '' || $datos['eco_432'] != ''
                     || $datos['eco_433'] != '' || $datos['eco_435'] != '' || $datos['eco_436'] != ''
                     || $datos['eco_437'] != '' || $datos['eco_438'] != '' || $datos['eco_439'] != ''
                     || $datos['eco_441'] != '' || $datos['eco_442'] != '' || $datos['eco_443'] != ''
                     || $datos['eco_444'] != '' || $datos['eco_445'] != '' || $datos['eco_447'] != ''
                     || $datos['eco_448'] != '' || $datos['eco_449'] != '' || $datos['eco_450'] != ''
                     ){
                     $ecoMuManoIzq = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                     <tr style="font-size: 55%; line-height: 9px">
                         <td style="text-align: center;padding: 15px;" colspan="6">
                             <span><strong>MANO IZQUIERDA</strong></span>
                         </td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>1MCF</strong></td>
                         <td name="eco_301"><strong>DERRAME: </strong>'.$datos['eco_301'].'</td>
                         <td name="eco_303"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_303'].'</td>
                         <td name="eco_304"><strong>Señal Power Doppler: </strong>'.$datos['eco_304'].'</td>
                         <td name="eco_305"><strong>EROSIONES: </strong>'.$datos['eco_305'].'</td>
                         <td name="eco_307"><strong>Proliferación Osea: </strong>'.$datos['eco_307'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>2MCF</strong></td>
                         <td name="eco_309"><strong>DERRAME: </strong>'.$datos['eco_309'].'</td>
                         <td name="eco_311"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_311'].'</td>
                         <td name="eco_312"><strong>Señal Power Doppler: </strong>'.$datos['eco_312'].'</td>
                         <td name="eco_313"><strong>EROSIONES </strong>'.$datos['eco_313'].'</td>
                         <td name="eco_315"><strong>Proliferación Osea: </strong>'.$datos['eco_315'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>3MCF</strong></td>
                         <td name="eco_317"><strong>DERRAME: </strong>'.$datos['eco_317'].'</td>
                         <td name="eco_319"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_319'].'</td>
                         <td name="eco_320"><strong>Señal Power Doppler: </strong>'.$datos['eco_320'].'</td>
                         <td name="eco_321"><strong>EROSIONES: </strong>'.$datos['eco_321'].'</td>
                         <td name="eco_323"><strong>Proliferación Osea: </strong>'.$datos['eco_323'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>4MCF</strong></td>
                         <td name="eco_325"><strong>DERRAME: </strong>'.$datos['eco_325'].'</td>
                         <td name="eco_327"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_327'].'</td>
                         <td name="eco_328"><strong>Señal Power Doppler: </strong>'.$datos['eco_328'].'</td>
                         <td name="eco_329"><strong>EROSIONES: </strong>'.$datos['eco_329'].'</td>
                         <td name="eco_331"><strong>Proliferación Osea: </strong>'.$datos['eco_331'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>5MCF</strong></td>
                         <td name="eco_333"><strong>DERRAME: </strong>'.$datos['eco_333'].'</td>
                         <td name="eco_335"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_335'].'</td>
                         <td name="eco_336"><strong>Señal Power Doppler: </strong>'.$datos['eco_336'].'</td>
                         <td name="eco_337"><strong>EROSIONES: </strong>'.$datos['eco_337'].'</td>
                         <td name="eco_339"><strong>Proliferación Osea: </strong>'.$datos['eco_339'].'</td>
                     </tr>

                     <!--bloque 2-->
                     <tr style="font-size: 50%;">
                         <td><strong>1IFP</strong></td>
                         <td name="eco_341"><strong>DERRAME: </strong>'.$datos['eco_341'].'</td>
                         <td name="eco_343"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_343'].'</td>
                         <td name="eco_344"><strong>Señal Power Doppler: </strong>'.$datos['eco_344'].'</td>
                         <td name="eco_345"><strong>EROSIONES: </strong>'.$datos['eco_345'].'</td>
                         <td name="eco_347"><strong>Proliferación Osea: </strong>'.$datos['eco_347'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>2IFP</strong></td>
                         <td name="eco_349"><strong>DERRAME: </strong>'.$datos['eco_349'].'</td>
                         <td name="eco_351"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_351'].'</td>
                         <td name="eco_352"><strong>Señal Power Doppler: </strong>'.$datos['eco_352'].'</td>
                         <td name="eco_353"><strong>EROSIONES: </strong>'.$datos['eco_353'].'</td>
                         <td name="eco_355"><strong>Proliferación Osea: </strong>'.$datos['eco_355'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>3IFP</strong></td>
                         <td name="eco_357"><strong>DERRAME: </strong>'.$datos['eco_357'].'</td>
                         <td name="eco_359"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_359'].'</td>
                         <td name="eco_360"><strong>Señal Power Doppler: </strong>'.$datos['eco_360'].'</td>
                         <td name="eco_361"><strong>EROSIONES: </strong>'.$datos['eco_361'].'</td>
                         <td name="eco_363"><strong>Proliferación Osea: </strong>'.$datos['eco_363'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>4IFP</strong></td>
                         <td name="eco_365"><strong>DERRAME: </strong>'.$datos['eco_365'].'</td>
                         <td name="eco_367"><strong>Hipertrofia Sinovial </strong>'.$datos['eco_367'].':</td>
                         <td name="eco_368"><strong>Señal Power Doppler: </strong>'.$datos['eco_368'].'</td>
                         <td name="eco_369"><strong>EROSIONES: </strong>'.$datos['eco_369'].'</td>
                         <td name="eco_371"><strong>Proliferación Osea: </strong>'.$datos['eco_371'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>5IFP</strong></td>
                         <td name="eco_373"><strong>DERRAME: </strong>'.$datos['eco_373'].'</td>
                         <td name="eco_375"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_375'].'</td>
                         <td name="eco_376"><strong>Señal Power Doppler: </strong>'.$datos['eco_376'].'</td>
                         <td name="eco_377"><strong>EROSIONES: </strong>'.$datos['eco_377'].'</td>
                         <td name="eco_379"><strong>Proliferación Osea: </strong>'.$datos['eco_379'].'</td>
                     </tr>
                     <!--bloque 3-->

                     <tr style="font-size: 50%;">
                         <td><strong>1IFD</strong></td>
                         <td name="eco_381"><strong>DERRAME: </strong>'.$datos['eco_381'].'</td>
                         <td name="eco_383"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_383'].'</td>
                         <td name="eco_384"><strong>Señal Power Doppler: </strong>'.$datos['eco_384'].'</td>
                         <td name="eco_385"><strong>EROSIONES: </strong>'.$datos['eco_385'].'</td>
                         <td name="eco_387"><strong>Proliferación Osea: </strong>'.$datos['eco_387'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>2IFD</strong></td>
                         <td name="eco_389"><strong>DERRAME: </strong>'.$datos['eco_389'].'</td>
                         <td name="eco_391"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_391'].'</td>
                         <td name="eco_392"><strong>Señal Power Doppler: </strong>'.$datos['eco_392'].'</td>
                         <td name="eco_393"><strong>EROSIONES: </strong>'.$datos['eco_393'].'</td>
                         <td name="eco_395"><strong>Proliferación Osea: </strong>'.$datos['eco_395'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>3IFD</strong></td>
                         <td name="eco_397"><strong>DERRAME: </strong>'.$datos['eco_397'].'</td>
                         <td name="eco_399"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_399'].'</td>
                         <td name="eco_400"><strong>Señal Power Doppler: </strong>'.$datos['eco_400'].'</td>
                         <td name="eco_401"><strong>EROSIONES: </strong>'.$datos['eco_401'].'</td>
                         <td name="eco_403"><strong>Proliferación Osea: </strong>'.$datos['eco_403'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>4IFD</strong></td>
                         <td name="eco_405"><strong>DERRAME: </strong>'.$datos['eco_405'].'</td>
                         <td name="eco_407"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_407'].'</td>
                         <td name="eco_408"><strong>Señal Power Doppler: </strong>'.$datos['eco_408'].'</td>
                         <td name="eco_409"><strong>EROSIONES: </strong>'.$datos['eco_409'].'</td>
                         <td name="eco_411"><strong>Proliferación Osea: </strong>'.$datos['eco_411'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>5IFD</strong></td>
                         <td name="eco_413"><strong>DERRAME: </strong>'.$datos['eco_413'].'</td>
                         <td name="eco_415"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_415'].'</td>
                         <td name="eco_416"><strong>Señal Power Doppler: </strong>'.$datos['eco_416'].'</td>
                         <td name="eco_417"><strong>EROSIONES: </strong>'.$datos['eco_417'].'</td>
                         <td name="eco_419"><strong>Proliferación Osea: </strong>'.$datos['eco_419'].'</td>
                     </tr>
                     <!--inicio bloque 4-->
                     <tr style="font-size: 50%;">
                         <td><strong>1TDF</strong></td>
                         <td name="eco_421"><strong>DERRAME: </strong>'.$datos['eco_421'].'</td>
                         <td name="eco_423"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_423'].'</td>
                         <td name="eco_424"><strong>Señal Power Doppler: </strong>'.$datos['eco_424'].'</td>
                         <td name="eco_425"><strong>EROSIONES: </strong>'.$datos['eco_425'].'</td>
                         <td name="eco_426"><strong>Proliferación Osea: </strong>'.$datos['eco_426'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>2TDF</strong></td>
                         <td name="eco_427"><strong>DERRAME </strong>'.$datos['eco_427'].'</td>
                         <td name="eco_429"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_429'].'</td>
                         <td name="eco_430"><strong>Señal Power Doppler: </strong>'.$datos['eco_430'].'</td>
                         <td name="eco_431"><strong>Ecogenicidad: </strong>'.$datos['eco_431'].'</td>
                         <td name="eco_432"><strong>Rotura: </strong>'.$datos['eco_432'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>3TDF</strong></td>
                         <td name="eco_433"><strong>DERRAME: </strong>'.$datos['eco_433'].'</td>
                         <td name="eco_435"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_435'].'</td>
                         <td name="eco_436"><strong>Señal Power Doppler: </strong>'.$datos['eco_436'].'</td>
                         <td name="eco_437"><strong>Ecogenicidad: </strong>'.$datos['eco_437'].'</td>
                         <td name="eco_438"><strong>Rotura: </strong>'.$datos['eco_438'].'</td>

                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>4TDF</strong></td>
                         <td name="eco_439"><strong>DERRAME </strong>'.$datos['eco_439'].'</td>
                         <td name="eco_441"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_441'].'</td>
                         <td name="eco_442"><strong>Señal Power Doppler: </strong>'.$datos['eco_442'].'</td>
                         <td name="eco_443"><strong>Ecogenicidad: </strong>'.$datos['eco_443'].'</td>
                         <td name="eco_444"><strong>Rotura: </strong>'.$datos['eco_444'].'</td>
                     </tr>
                     <tr style="font-size: 50%;">
                         <td><strong>5TDF</strong></td>
                         <td name="eco_445"><strong>DERRAME: </strong>'.$datos['eco_445'].'</td>
                         <td name="eco_447"><strong>Hipertrofia Sinovial: </strong>'.$datos['eco_447'].'</td>
                         <td name="eco_448"><strong>Señal Power Doppler: </strong>'.$datos['eco_448'].'</td>
                         <td name="eco_449"><strong>Ecogenicidad: </strong>'.$datos['eco_449'].'</td>
                         <td name="eco_450"><strong>Rotura: </strong>'.$datos['eco_450'].'</td>
                     </tr>

                     </table>
                         ';


                     }else{
                     $ecoMuManoIzq='';
                     }


                // construccion del bloque - Ecografia de mano y muñeca
                if(empty($ecoMuMaCarpoDer) && empty($ecoMuMaCarpoIzq) && empty($ecoMuManoDer) && empty($ecoMuManoIzq) ){
                    $informDataHistory.='';
                }
                else{
                    $informDataHistory.=  
                                '<table style="padding: 2px; width: 100%">
                                <tr style="font-size: 10%">
                                    <td style="text-align: center;" >

                                    </td>
                                </tr>
                                <tr style="font-size: 70%">
                                    <td style="text-align: center;" >
                                        <br><strong> ECOGRAFÍA DE ALTA RESOLUCIÓN DE MUÑECA Y MANO</strong>
                                    </td>
                                </tr>

                          </table>
                        ';
                }

                if(!empty($ecoMuMaCarpoIzq) ){

                    $informDataHistory.= '
                    '.$ecoMuMaCarpoIzq.'
                             ';
                };

                if(!empty($ecoMuMaCarpoDer) ){

                    $informDataHistory.= '
                    '.$ecoMuMaCarpoDer.'
                             ';
                };
                if(!empty($ecoMuManoDer) ){

                    $informDataHistory.= '
                    '.$ecoMuManoDer.'
                             ';
                };
                if(!empty($ecoMuManoIzq) ){

                    $informDataHistory.= '
                    '.$ecoMuManoIzq.'
                             ';
                };

                // ecografia  de codo RECESO ARTICULAR ANTERIOR- derecho

                if ($datos['eco_451'] != '' || $datos['eco_453'] != ''
                    ||$datos['eco_454'] != '' || $datos['eco_455'] != ''
                   || $datos['eco_457'] != '' || $datos['eco_459'] != ''
                   || $datos['eco_461'] != '' || $datos['eco_463'] != ''
                   || $datos['eco_465'] != ''
                ){
                  $ecoCodoDer= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>RECESO ARTICULAR ANTERIOR - CODO DERECHO</strong></span>
                  </td>
                </tr>
                  <tr style="font-size: 50%;">
                        <td colspan="2" name="eco_451">DERRAME: '.$datos['eco_453'].'</td>
                        <td colspan="2" name="eco_453">Hipertrofia Sinovial: '.$datos['eco_455'].'</td>
                        <td colspan="2" name="eco_454">SEÑAL POWER DOPPLER INTRAARTICULAR: '.$datos['eco_454'].'</td>
                    </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>BORDES ÓSEOS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_455">NITIDOS: '.$datos['eco_455'].'</td>
                        <td name="eco_457">IRREGULARIDADES: '.$datos['eco_457'].'</td>
                        <td name="eco_459">EROSIONES: '.$datos['eco_459'].'</td>
                        <td name="eco_461">PROLIFERACIÓN OSEA: '.$datos['eco_461'].'</td>
                        <td name="eco_463">MÚSCULO BRANQUIAL CON ECOESTRUCTURA/ECOGENICIDAD NORMAL: '.$datos['eco_463'].'</td>
                        <td name="eco_465">ASPECTO DEL CARTILAGO ARTICULAR: '.$datos['eco_465'].'</td>
                    </tr>
                </table>
                  ';
                }else{
                    $ecoCodoDer='';
                }
                // ecografia  de codo RECESO ARTICULAR ANTERIOR- izquierdo

                if ($datos['eco_467'] != '' || $datos['eco_469'] != ''
                    ||$datos['eco_470'] != '' || $datos['eco_471'] != ''
                   || $datos['eco_473'] != '' || $datos['eco_475'] != ''
                   || $datos['eco_477'] != '' || $datos['eco_479'] != ''
                   || $datos['eco_481'] != ''
                ){
                  $ecoCodoIzq= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>RECESO ARTICULAR ANTERIOR - CODO IZQUIERDO</strong></span>
                  </td>
                </tr>

                    <tr style="font-size: 50%;">
                        <td colspan="2" name="eco_467">DERRAME: '.$datos['eco_467'] .'</td>
                        <td colspan="2" name="eco_469">Hipertrofia Sinovial: '.$datos['eco_469'] .'</td>
                        <td colspan="2" name="eco_470">SEÑAL POWER DOPPLER INTRAARTICULAR: '.$datos['eco_470'] .'</td>
                    </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>BORDES ÓSEOS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_471">NITIDOS: '.$datos['eco_471'] .'</td>
                    <td name="eco_473">IRREGULARIDADES: '.$datos['eco_473'] .'</td>
                    <td name="eco_475">EROSIONES '.$datos['eco_475'] .'</td>
                    <td name="eco_477">PROLIFERACIÓN OSEA '.$datos['eco_477'] .'</td>
                    <td name="eco_479">MÚSCULO BRANQUIAL CON ECOESTRUCTURA/ECOGENICIDAD NORMAL: '.$datos['eco_479'] .'</td>
                    <td name="eco_481">ASPECTO DEL CARTILAGO ARTICULAR: '.$datos['eco_481'] .'</td>
                </tr>
                </table>
                  ';
                }else{
                    $ecoCodoIzq='';
                }

                // ecografia  de codo EPITROCLEA- derecho

                if ($datos['eco_483'] != '' || $datos['eco_485'] != ''
                    ||$datos['eco_487'] != '' || $datos['eco_489'] != ''
                   || $datos['eco_490'] != '' || $datos['eco_492'] != ''
                   || $datos['eco_494'] != '' || $datos['eco_496'] != ''
                   || $datos['eco_498'] != '' || $datos['eco_499'] != ''
                   || $datos['eco_500'] != '' || $datos['eco_501'] != ''
                ){
                  $ecoEpitrocleaCodoDer= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>EPITROCLEA - CODO DERECHO</strong></span>
                  </td>
                </tr>
                <tr style="font-size: 40%; line-height: 9px">
                <td style="text-align: center;padding: 5px;" colspan="7">
                    <span><strong>INSERCIÓN T. FLEXOR COMÚN</strong></span>
                </td>
                </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_483">LIMITES: '.$datos['eco_483'].' </td>
                    <td name="eco_485">ECOESTRUCTURA: '.$datos['eco_485'].'</td>
                    <td name="eco_487">ECOGENICIDAD (NORMAL): '.$datos['eco_487'].'</td>
                    <td name="eco_489">ALTERADA: '.$datos['eco_489'].'</td>
                    <td name="eco_490">ESPESOR: '.$datos['eco_490'].'</td>
                    <td name="eco_492">ROTURA: '.$datos['eco_492'].'</td>
                    <td name="eco_494">TIPO: '.$datos['eco_494'].'</td>
                </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>ALTERACIONES EN ENTESIS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_496">IRREGULARIDADES CORTICALES EN SINSERCIÓN: '.$datos['eco_496'].'</td>
                        <td name="eco_498">EROSIONES (MM): '.$datos['eco_498'].'</td>
                        <td name="eco_499">PROLIFERACIÓN (MM): '.$datos['eco_499'].'</td>
                        <td colspan="2" name="eco_500">SEÑAL PD EN ENTESIS: '.$datos['eco_500'].'</td>
                        <td colspan="2" name="eco_501">GRADO SEÑAL PD: '.$datos['eco_501'].'</td>
                    </tr>
                </table>
                  ';
                }else{
                    $ecoEpitrocleaCodoDer='';
                }
                // ecografia  de codo EPITROCLEA- izquierdo

                if ($datos['eco_502'] != '' || $datos['eco_504'] != ''
                    ||$datos['eco_506'] != '' || $datos['eco_508'] != ''
                   || $datos['eco_509'] != '' || $datos['eco_511'] != ''
                   || $datos['eco_513'] != '' || $datos['eco_515'] != ''
                   || $datos['eco_517'] != '' || $datos['eco_518'] != ''
                   || $datos['eco_519'] != '' || $datos['eco_520'] != ''
                ){
                  $ecoEpitrocleaCodoIzq= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>EPITROCLEA - CODO IZQUIERDO</strong></span>
                  </td>
                </tr>
                <tr style="font-size: 40%; line-height: 9px">
                <td style="text-align: center;padding: 5px;" colspan="7">
                    <span><strong>INSERCIÓN T. FLEXOR COMÚN</strong></span>
                </td>
                </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_502">LIMITES: '.$datos['eco_502'].'</td>
                        <td name="eco_504">ECOESTRUCTURA: '.$datos['eco_504'].'</td>
                        <td name="eco_506">ECOGENICIDAD (NORMAL): '.$datos['eco_506'].'</td>
                        <td name="eco_508">ALTERADA: '.$datos['eco_508'].'</td>
                        <td name="eco_509">ESPESOR: '.$datos['eco_509'].'</td>
                        <td name="eco_511">ROTURA: '.$datos['eco_511'].'</td>
                        <td name="eco_513">TIPO: '.$datos['eco_513'].'</td>
                </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>ALTERACIONES EN ENTESIS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_515">IRREGULARIDADES CORTICALES EN SINSERCIÓN: '.$datos['eco_515'].'</td>
                    <td name="eco_517">EROSIONES (MM): '.$datos['eco_517'].'</td>
                    <td name="eco_518">PROLIFERACIÓN (MM): '.$datos['eco_518'].'</td>
                    <td colspan="2" name="eco_519">SEÑAL PD EN ENTESIS: '.$datos['eco_519'].'</td>
                    <td colspan="2" name="eco_520">GRADO SEÑAL PD: '.$datos['eco_520'].'</td>
                </tr>
                </table>
                  ';
                }else{
                    $ecoEpitrocleaCodoIzq='';
                }

                // ecografia  de codo ESCOPONDILO- DERECHO

                if ($datos['eco_521'] != '' || $datos['eco_523'] != ''
                    ||$datos['eco_525'] != '' || $datos['eco_527'] != ''
                   || $datos['eco_528'] != '' || $datos['eco_530'] != ''
                   || $datos['eco_532'] != '' || $datos['eco_534'] != ''
                   || $datos['eco_536'] != '' || $datos['eco_537'] != ''
                   || $datos['eco_538'] != '' || $datos['eco_539'] != ''
                ){
                  $ecoEscopondiloCodoDer= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>ESCOPONDILO - CODO DERECHO</strong></span>
                  </td>
                </tr>
                <tr style="font-size: 40%; line-height: 9px">
                <td style="text-align: center;padding: 5px;" colspan="7">
                    <span><strong>INSERCIÓN T. FLEXOR COMÚN</strong></span>
                </td>
                </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_521">LIMITES: '.$datos['eco_521'].'</td>
                        <td name="eco_523">ECOESTRUCTURA: '.$datos['eco_523'].'</td>
                        <td name="eco_525">ECOGENICIDAD (NORMAL): '.$datos['eco_525'].'</td>
                        <td name="eco_527">ALTERADA: '.$datos['eco_527'].'</td>
                        <td name="eco_528">ESPESOR: '.$datos['eco_528'].'</td>
                        <td name="eco_530">ROTURA: '.$datos['eco_530'].'</td>
                        <td name="eco_532">TIPO: '.$datos['eco_532'].'</td>
                </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>ALTERACIONES EN ENTESIS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_534">IRREGULARIDADES CORTICALES EN SINSERCIÓN: '.$datos['eco_534'].'</td>
                        <td name="eco_536">EROSIONES (MM): '.$datos['eco_536'].'</td>
                        <td name="eco_537">PROLIFERACIÓN (MM): '.$datos['eco_537'].'</td>
                        <td colspan="2" name="eco_538">SEÑAL PD EN ENTESIS: '.$datos['eco_538'].'</td>
                        <td colspan="2" name="eco_539">GRADO SEÑAL PD: '.$datos['eco_539'].'</td>
                    </tr>
                </table>
                  ';
                }else{
                    $ecoEscopondiloCodoDer='';
                }

                // ecografia  de codo ESCOPONDILO- IZQUIERDO

                if ($datos['eco_540'] != '' || $datos['eco_542'] != ''
                    ||$datos['eco_544'] != '' || $datos['eco_546'] != ''
                   || $datos['eco_547'] != '' || $datos['eco_549'] != ''
                   || $datos['eco_551'] != '' || $datos['eco_553'] != ''
                   || $datos['eco_555'] != '' || $datos['eco_556'] != ''
                   || $datos['eco_557'] != '' || $datos['eco_558'] != ''
                ){
                  $ecoEscopondiloCodoIzq= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>ESCOPONDILO - CODO IZQUIERDO</strong></span>
                  </td>
                </tr>
                <tr style="font-size: 40%; line-height: 9px">
                <td style="text-align: center;padding: 5px;" colspan="7">
                    <span><strong>INSERCIÓN T. FLEXOR COMÚN</strong></span>
                </td>
                </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_540">LIMITES: '.$datos['eco_540'].'</td>
                    <td name="eco_542">ECOESTRUCTURA: '.$datos['eco_542'].'</td>
                    <td name="eco_544">ECOGENICIDAD (NORMAL): '.$datos['eco_544'].'</td>
                    <td name="eco_546">ALTERADA: '.$datos['eco_546'].'</td>
                    <td name="eco_547">ESPESOR: '.$datos['eco_547'].'</td>
                    <td name="eco_549">ROTURA: '.$datos['eco_549'].'</td>
                    <td name="eco_551">TIPO: '.$datos['eco_551'].'</td>
                </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>ALTERACIONES EN ENTESIS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                            <td name="eco_553">IRREGULARIDADES CORTICALES EN SINSERCIÓN: '.$datos['eco_553'].'</td>
                            <td name="eco_555">EROSIONES (MM): '.$datos['eco_555'].'</td>
                            <td name="eco_556">PROLIFERACIÓN (MM): '.$datos['eco_556'].'</td>
                            <td colspan="2" name="eco_557">SEÑAL PD EN ENTESIS: '.$datos['eco_557'].'</td>
                            <td colspan="2" name="eco_558">GRADO SEÑAL PD: '.$datos['eco_558'].'</td>
                        </tr>
                </table>
                  ';
                }else{
                    $ecoEscopondiloCodoIzq='';
                }

                // ecografia  de codo region posterior- derecho

                if ($datos['eco_559'] != '' || $datos['eco_561'] != ''
                    ||$datos['eco_563'] != '' || $datos['eco_565'] != ''
                   || $datos['eco_566'] != '' || $datos['eco_568'] != ''
                   || $datos['eco_570'] != '' || $datos['eco_572'] != ''
                   || $datos['eco_574'] != '' || $datos['eco_575'] != ''
                   || $datos['eco_576'] != '' || $datos['eco_577'] != ''
                   || $datos['eco_578'] != '' || $datos['eco_580'] != ''
                   || $datos['eco_582'] != '' || $datos['eco_583'] != ''
                   || $datos['eco_585'] != '' || $datos['eco_587'] != ''
                ){
                  $ecoRegPostCodoDer= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>REGION POSTERIOR - CODO DERECHO</strong></span>
                  </td>
                </tr>
                <tr style="font-size: 40%; line-height: 9px">
                <td style="text-align: center;padding: 5px;" colspan="7">
                    <span><strong>TENDÓN TRICEPS BRANQUIAL</strong></span>
                </td>
                </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_559">LIMITES: '.$datos['eco_559'].'</td>
                        <td name="eco_561">ECOESTRUCTURA: '.$datos['eco_561'].'</td>
                        <td name="eco_563">ECOGENICIDAD (NORMAL): '.$datos['eco_563'].'</td>
                        <td name="eco_565">ALTERADA: '.$datos['eco_565'].'</td>
                        <td name="eco_566">ESPESOR: '.$datos['eco_566'].'</td>
                        <td name="eco_568">ROTURA: '.$datos['eco_568'].'</td>
                        <td name="eco_570">TIPO: '.$datos['eco_570'].'</td>
                </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>ALTERACIONES EN ENTESIS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                            <td name="eco_572">IRREGULARIDADES CORTICALES EN SINSERCIÓN: '.$datos['eco_572'].'</td>
                            <td name="eco_574">EROSIONES (MM): '.$datos['eco_574'].'</td>
                            <td name="eco_575">PROLIFERACIÓN (MM): '.$datos['eco_575'].'</td>
                            <td colspan="2" name="eco_576">SEÑAL PD EN ENTESIS: '.$datos['eco_576'].'</td>
                            <td colspan="2" name="eco_577">GRADO SEÑAL PD: '.$datos['eco_577'].'</td>
                        </tr>
                    <tr style="font-size: 50%;">
                        <td colspan="2" name="eco_578">BURSITIS OLECRANIANA: '.$datos['eco_578'].'</td>
                        <td name="eco_580">SINOVITIS (DERRAME/HIPERTROFIA SINOVIAL) RECESO POSTERIOR: '.$datos['eco_580'].'</td>
                        <td name="eco_582">GRADO '.$datos['eco_582'].'</td>
                        <td name="eco_583">SEÑAL PD INTRAARTICULAR EN RECESO POSTERIOR: '.$datos['eco_583'].'</td>
                        <td name="eco_585">LESIONES OCUPANTES EN FOSA EPITROCLEAR: '.$datos['eco_585'].'</td>
                        <td name="eco_587">NERVIO CUBITAL, ASPECTO Y LOCALIZACIÓN: '.$datos['eco_587'].'</td>
                    </tr>  
                </table>
                  ';
                }else{
                    $ecoRegPostCodoDer='';
                }

                // ecografia  de codo region posterior- IZQUIERDO

                if ($datos['eco_589'] != '' || $datos['eco_591'] != ''
                    ||$datos['eco_593'] != '' || $datos['eco_595'] != ''
                   || $datos['eco_596'] != '' || $datos['eco_598'] != ''
                   || $datos['eco_600'] != '' || $datos['eco_602'] != ''
                   || $datos['eco_604'] != '' || $datos['eco_605'] != ''
                   || $datos['eco_606'] != '' || $datos['eco_607'] != ''
                   || $datos['eco_608'] != '' || $datos['eco_610'] != ''
                   || $datos['eco_612'] != '' || $datos['eco_613'] != ''
                   || $datos['eco_615'] != '' || $datos['eco_617'] != ''
                ){
                  $ecoRegPostCodoIzq= '

                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                  <tr style="font-size: 55%; line-height: 9px">
                  <td style="text-align: center;padding: 15px;" colspan="6">
                      <span><strong>REGION POSTERIOR - CODO IZQUIERDO</strong></span>
                  </td>
                </tr>
                <tr style="font-size: 40%; line-height: 9px">
                <td style="text-align: center;padding: 5px;" colspan="7">
                    <span><strong>TENDÓN TRICEPS BRANQUIAL</strong></span>
                </td>
                </tr>
                    <tr style="font-size: 50%;">
                            <td name="eco_589">LIMITES: '.$datos['eco_589'].'</td>
                            <td name="eco_591">ECOESTRUCTURA: '.$datos['eco_591'].'</td>
                            <td name="eco_593">ECOGENICIDAD (NORMAL): '.$datos['eco_593'].'</td>
                            <td name="eco_595">ALTERADA: '.$datos['eco_595'].'</td>
                            <td name="eco_596">ESPESOR: '.$datos['eco_596'].'</td>
                            <td name="eco_598">ROTURA: '.$datos['eco_598'].'</td>
                            <td name="eco_600">TIPO: '.$datos['eco_600'].'</td>
                </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="6">
                            <span><strong>ALTERACIONES EN ENTESIS</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                            <td name="eco_602">IRREGULARIDADES CORTICALES EN SINSERCIÓN: '.$datos['eco_602'].'</td>
                            <td name="eco_604">EROSIONES (MM): '.$datos['eco_604'].'</td>
                            <td name="eco_605">PROLIFERACIÓN (MM): '.$datos['eco_605'].'</td>
                            <td colspan="2" name="eco_606">SEÑAL PD EN ENTESIS: '.$datos['eco_606'].'</td>
                            <td colspan="2" name="eco_607">GRADO SEÑAL PD: '.$datos['eco_607'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td colspan="2" name="eco_608">BURSITIS OLECRANIANA: '.$datos['eco_608'].'</td>
                            <td name="eco_610">SINOVITIS (DERRAME/HIPERTROFIA SINOVIAL) RECESO POSTERIOR: '.$datos['eco_610'].'</td>
                            <td name="eco_612">GRADO: '.$datos['eco_612'].' </td>
                            <td name="eco_613">SEÑAL PD INTRAARTICULAR EN RECESO POSTERIOR: '.$datos['eco_613'].'</td>
                            <td name="eco_615">LESIONES OCUPANTES EN FOSA EPITROCLEAR: '.$datos['eco_615'].'</td>
                            <td name="eco_617">NERVIO CUBITAL, ASPECTO Y LOCALIZACIÓN: '.$datos['eco_617'].'</td>
                        </tr>

                </table>
                  ';
                }else{
                    $ecoRegPostCodoIzq='';
                }

                // construccion del bloque de ecografia de codo
                if(empty($ecoCodoDer) && empty($ecoCodoIzq)  && empty($ecoEpitrocleaCodoDer) 
                && empty($ecoEpitrocleaCodoIzq) && empty($ecoEscopondiloCodoDer) && empty($ecoEscopondiloCodoIzq)
                && empty($ecoRegPostCodoDer) && empty($ecoRegPostCodoIzq)
                ){
                    $informDataHistory.='';
                }
                else{
                    $informDataHistory.=  
                                '<table style="padding: 2px; width: 100%">
                                <tr style="font-size: 10%">
                                    <td style="text-align: center;" >

                                    </td>
                                </tr>
                                <tr style="font-size: 70%">
                                    <td style="text-align: center;" >
                                        <br><strong> ECOGRAFÍA DE ALTA RESOLUCIÓN DE CODO</strong>
                                    </td>
                                </tr>

                          </table>
                        ';
                }

                if(!empty($ecoCodoDer)  ){
                    $informDataHistory.='
                    '.$ecoCodoDer.'
                    ';
                }
                if(!empty($ecoCodoIzq)  ){
                    $informDataHistory.='
                    '.$ecoCodoIzq.'
                    ';
                }  
                if(!empty($ecoEpitrocleaCodoDer)  ){
                    $informDataHistory.='
                    '.$ecoEpitrocleaCodoDer.'
                    ';
                } 
                if(!empty($ecoEpitrocleaCodoIzq)  ){
                    $informDataHistory.='
                    '.$ecoEpitrocleaCodoIzq.'
                    ';
                } 
                if(!empty($ecoEscopondiloCodoDer)  ){
                    $informDataHistory.='
                    '.$ecoEscopondiloCodoDer.'
                    ';
                }
                if(!empty($ecoEscopondiloCodoIzq)  ){
                    $informDataHistory.='
                    '.$ecoEscopondiloCodoIzq.'
                    ';
                }
                if(!empty($ecoRegPostCodoDer)  ){
                    $informDataHistory.='
                    '.$ecoRegPostCodoDer.'
                    ';
                }
                if(!empty($ecoRegPostCodoIzq)  ){
                    $informDataHistory.='
                    '.$ecoRegPostCodoIzq.'
                    ';
                }

                //ecografia de hombro - EXPLORACIÓN ANTERIOR - derecho
                if(
                    $datos['eco_619']!= ''  || $datos['eco_621']!= ''
                    || $datos['eco_623']!= '' || $datos['eco_625']!= ''  
                    || $datos['eco_626']!= '' || $datos['eco_627']!= ''
                    || $datos['eco_629']!= '' || $datos['eco_631']!= ''
                    || $datos['eco_634']!= '' || $datos['eco_636']!= ''
                    || $datos['eco_638']!= '' || $datos['eco_640']!= ''
                    || $datos['eco_642']!= '' || $datos['eco_644']!= ''
                    || $datos['eco_646']!= '' || $datos['eco_647']!= ''
                    || $datos['eco_649']!= '' || $datos['eco_651']!= ''
                    || $datos['eco_654']!= '' || $datos['eco_655']!= ''
                    || $datos['eco_657']!= '' || $datos['eco_658']!= ''
                    || $datos['eco_660']!= '' || $datos['eco_661']!= ''
                    || $datos['eco_662']!= '' || $datos['eco_664']!= ''
                    || $datos['eco_665']!= '' || $datos['eco_666']!= ''
                    || $datos['eco_668']!= '' || $datos['eco_669']!= ''
                    || $datos['eco_671']!= '' || $datos['eco_673']!= ''
                    || $datos['eco_674']!= '' || $datos['eco_675']!= ''
                    || $datos['eco_677']!= '' || $datos['eco_678']!= ''

                ){
                    $ecoExpoantHombroDer = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN ANTERIOR- HOMBRO DERECHO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDON DEL BICEPS</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_619">EN CORREDERA: '.$datos['eco_619'].'</td>
                        <td name="eco_621"> '.$datos['eco_621'].'</td>
                        <td name="eco_623">AUMENTO DE LIQUIDO EN VAINAS TENDINOSAS '.$datos['eco_623'].'</td>
                        <td name="eco_625">SEÑAL PD VAINA: '.$datos['eco_625'].'</td>
                        <td name="eco_626">SINOVITIS: '.$datos['eco_626'].'</td>
                        <td name="eco_627">LIMITES: '.$datos['eco_627'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_629">ECOESTRUCTURA: '.$datos['eco_629'].'</td>
                        <td name="eco_631">ECOGENICIDAD (NORMAL): '.$datos['eco_631'].'</td>
                        <td name="eco_633">ALTERADA: '.$datos['eco_633'].'</td>
                        <td name="eco_634">ESPESOR: '.$datos['eco_634'].'</td>
                        <td name="eco_636">ROTURA: '.$datos['eco_636'].'</td>
                        <td name="eco_638">TIPO: '.$datos['eco_638'].'</td>
                    </tr>


                    <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDÓN DEL SUBESCAPULAR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                    <td name="eco_640">LIMITES:  '.$datos['eco_640'].'</td>
                    <td name="eco_642">ECOESTRUCTURA:  '.$datos['eco_642'].'</td>
                    <td name="eco_644">ECOGENICIDAD (NORMAL):  '.$datos['eco_644'].'</td>
                    <td name="eco_646">ALTERADA:  '.$datos['eco_646'].'</td>
                    <td name="eco_647">ESPESOR:  '.$datos['eco_647'].'</td>
                    <td name="eco_649">ROTURA: '.$datos['eco_649'].'</td>
                    </tr>

                    <tr style="font-size: 50%;">
                        <td name="eco_651">TIPO: '.$datos['eco_651'].'</td>
                        <td colspan="2" >MEDIDA ROTURA:</td>
                        <td name="eco_654">TRANSVERSAL (MM): '.$datos['eco_654'].'</td>
                        <td  name="eco_655">LONGITUDINAL (MM): '.$datos['eco_655'].'</td>

                </tr>


                    <tr style="font-size: 50%;">
                        <td name="eco_657">CALIFICACIÓN: '.$datos['eco_657'].'</td>
                        <td name="eco_658">IRREGULARIDADES CORTICALES EN TROQUÍN: '.$datos['eco_658'].'</td>
                        <td name="eco_660">EROSIONES (MM): '.$datos['eco_660'].'</td>
                        <td name="eco_661">PROLIFERACIÓN (MM): '.$datos['eco_661'].'</td>
                        <td name="eco_662">INESPECIFICAS: '.$datos['eco_662'].'</td>
                        <td name="eco_664">BURSITIS SUBCORACOIDEA: '.$datos['eco_664'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_665">HIPERTROFIA SINOVIAL: '.$datos['eco_665'].'</td>
                        <td name="eco_666">ECOS INTERNOS: '.$datos['eco_666'].'</td>
                        <td name="eco_668">SEÑAL DOPPLER: '.$datos['eco_668'].'</td>
                    </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ARTICULACIÓN ACROMIOCLAVICULAR</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_669">DISTENSIÓN CAPSULAR: '.$datos['eco_669'].'</td>
                        <td name="eco_671">SINOVITIS (DERRAME/HS): '.$datos['eco_671'].'</td>
                        <td name="eco_673">GRADO: '.$datos['eco_673'].'</td>
                        <td name="eco_674">SEÑAL PD: '.$datos['eco_674'].'</td>
                        <td colspan="2" name="eco_675">IRREGULARIDADES CORTICALES: '.$datos['eco_675'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_677">PROLIFERACIÓN (MM): '.$datos['eco_677'].'</td>
                        <td name="eco_678">EROSIONES (MM): '.$datos['eco_678'].'</td>
                    </tr>

                  </table>    
                    ';

                }
                else{
                    $ecoExpoantHombroDer ='';
                }
                //ecografia de hombro - EXPLORACIÓN ANTERIOR - izquierdo
                if(
                    $datos['eco_679']!= ''  || $datos['eco_681']!= ''
                    || $datos['eco_683']!= '' || $datos['eco_685']!= ''  
                    || $datos['eco_686']!= '' || $datos['eco_687']!= ''
                    || $datos['eco_689']!= '' || $datos['eco_691']!= ''
                    || $datos['eco_694']!= '' || $datos['eco_696']!= ''
                    || $datos['eco_698']!= '' || $datos['eco_700']!= ''
                    || $datos['eco_702']!= '' || $datos['eco_704']!= ''
                    || $datos['eco_706']!= '' || $datos['eco_707']!= ''
                    || $datos['eco_709']!= '' || $datos['eco_711']!= ''
                    || $datos['eco_714']!= '' || $datos['eco_715']!= ''
                    || $datos['eco_717']!= '' || $datos['eco_718']!= ''
                    || $datos['eco_720']!= '' || $datos['eco_721']!= ''
                    || $datos['eco_722']!= '' || $datos['eco_724']!= ''
                    || $datos['eco_725']!= '' || $datos['eco_726']!= ''
                    || $datos['eco_728']!= '' || $datos['eco_729']!= ''
                    || $datos['eco_731']!= '' || $datos['eco_733']!= ''
                    || $datos['eco_734']!= '' || $datos['eco_735']!= ''
                    || $datos['eco_737']!= '' || $datos['eco_738']!= ''

                ){
                    $ecoExpoantHombroIzq = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN ANTERIOR- HOMBRO IZQUIERDO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDON DEL BICEPS</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_679">EN CORREDERA: '.$datos['eco_739'].'</td>
                        <td name="eco_681"> '.$datos['eco_681'].'</td>
                        <td name="eco_683">AUMENTO DE LIQUIDO EN VAINAS TENDINOSAS '.$datos['eco_683'].'</td>
                        <td name="eco_685">SEÑAL PD VAINA: '.$datos['eco_685'].'</td>
                        <td name="eco_686">SINOVITIS: '.$datos['eco_686'].'</td>
                        <td name="eco_687">LIMITES: '.$datos['eco_687'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_689">ECOESTRUCTURA: '.$datos['eco_689'].'</td>
                        <td name="eco_691">ECOGENICIDAD (NORMAL): '.$datos['eco_691'].'</td>
                        <td name="eco_693">ALTERADA: '.$datos['eco_693'].'</td>
                        <td name="eco_694">ESPESOR: '.$datos['eco_694'].'</td>
                        <td name="eco_696">ROTURA: '.$datos['eco_696'].'</td>
                        <td name="eco_698">TIPO: '.$datos['eco_698'].'</td>
                    </tr>


                    <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDÓN DEL SUBESCAPULAR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                    <td name="eco_700">LIMITES:  '.$datos['eco_700'].'</td>
                    <td name="eco_702">ECOESTRUCTURA:  '.$datos['eco_702'].'</td>
                    <td name="eco_704">ECOGENICIDAD (NORMAL):  '.$datos['eco_704'].'</td>
                    <td name="eco_706">ALTERADA:  '.$datos['eco_706'].'</td>
                    <td name="eco_707">ESPESOR:  '.$datos['eco_707'].'</td>
                    <td name="eco_709">ROTURA: '.$datos['eco_709'].'</td>
                    </tr>

                    <tr style="font-size: 50%;">
                        <td name="eco_711">TIPO: '.$datos['eco_711'].'</td>
                        <td colspan="2" >MEDIDA ROTURA:</td>
                        <td name="eco_714">TRANSVERSAL (MM): '.$datos['eco_714'].'</td>
                        <td  name="eco_715">LONGITUDINAL (MM): '.$datos['eco_715'].'</td>

                </tr>


                    <tr style="font-size: 50%;">
                        <td name="eco_717">CALIFICACIÓN: '.$datos['eco_717'].'</td>
                        <td name="eco_718">IRREGULARIDADES CORTICALES EN TROQUÍN: '.$datos['eco_718'].'</td>
                        <td name="eco_720">EROSIONES (MM): '.$datos['eco_720'].'</td>
                        <td name="eco_721">PROLIFERACIÓN (MM): '.$datos['eco_721'].'</td>
                        <td name="eco_722">INESPECIFICAS: '.$datos['eco_722'].'</td>
                        <td name="eco_724">BURSITIS SUBCORACOIDEA: '.$datos['eco_724'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_725">HIPERTROFIA SINOVIAL: '.$datos['eco_725'].'</td>
                        <td name="eco_726">ECOS INTERNOS: '.$datos['eco_726'].'</td>
                        <td name="eco_728">SEÑAL DOPPLER: '.$datos['eco_728'].'</td>
                    </tr>
                    <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ARTICULACIÓN ACROMIOCLAVICULAR</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_729">DISTENSIÓN CAPSULAR: '.$datos['eco_729'].'</td>
                        <td name="eco_731">SINOVITIS (DERRAME/HS): '.$datos['eco_731'].'</td>
                        <td name="eco_733">GRADO: '.$datos['eco_733'].'</td>
                        <td name="eco_734">SEÑAL PD: '.$datos['eco_734'].'</td>
                        <td colspan="2" name="eco_735">IRREGULARIDADES CORTICALES: '.$datos['eco_735'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_737">PROLIFERACIÓN (MM): '.$datos['eco_737'].'</td>
                        <td name="eco_738">EROSIONES (MM): '.$datos['eco_738'].'</td>
                    </tr>

                  </table>    
                    ';

                }
                else{
                    $ecoExpoantHombroIzq ='';
                } 

                //ecografia de hombro - EXPLORACIÓN ANTERIOlteral - derecho
                if(
                    $datos['eco_739']!= ''  || $datos['eco_741']!= ''
                    || $datos['eco_743']!= '' || $datos['eco_745']!= ''  
                    || $datos['eco_746']!= '' || $datos['eco_748']!= ''
                    || $datos['eco_750']!= '' || $datos['eco_753']!= ''
                    || $datos['eco_754']!= '' || $datos['eco_696']!= ''
                    || $datos['eco_755']!= '' || $datos['eco_756']!= ''
                    || $datos['eco_758']!= '' || $datos['eco_760']!= ''
                    || $datos['eco_762']!= '' || $datos['eco_764']!= ''
                    || $datos['eco_765']!= '' || $datos['eco_767']!= ''
                    || $datos['eco_769']!= '' || $datos['eco_772']!= ''
                    || $datos['eco_773']!= '' || $datos['eco_774']!= ''
                    || $datos['eco_776']!= '' || $datos['eco_777']!= ''
                    || $datos['eco_779']!= '' || $datos['eco_780']!= ''
                    || $datos['eco_782']!= '' || $datos['eco_783']!= ''
                    || $datos['eco_785']!= '' || $datos['eco_787']!= ''
                    || $datos['eco_788']!= '' || $datos['eco_789']!= ''
                    || $datos['eco_790']!= '' || $datos['eco_792']!= ''
                    || $datos['eco_793']!= '' || $datos['eco_794']!= ''
                    || $datos['eco_797']!= '' || $datos['eco_798']!= ''
                    || $datos['eco_800']!= ''

                ){
                    $ecoExpoantLHombroDer = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN ANTERIOLTERAL- HOMBRO DERECHO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDÓN DEL SUPRAESPINOSO</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_739">LIMITES: '.$datos['eco_739'].'</td>
                        <td name="eco_741">ECOESTRUCTURA: '.$datos['eco_741'].'</td>
                        <td name="eco_743">ECOGENICIDAD (NORMAL): '.$datos['eco_743'].'</td>
                        <td name="eco_745">ALTERADA: '.$datos['eco_745'].'</td>
                        <td name="eco_746">ESPESOR: '.$datos['eco_746'].'</td>
                        <td name="eco_748">ROTURA: '.$datos['eco_748'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_750">TIPO: '.$datos['eco_750'].'</td>
                        <td >MEDIDA ROTURA: </td>
                        <td colspan="2" name="eco_753">TRANSVERSAL (MM): '.$datos['eco_753'].'</td>
                        <td colspan="2" name="eco_754">LONGITUDINAL (MM): '.$datos['eco_754'].'</td>   
                    </tr>
                    <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_755">ESTADÍO DE RETRACCIÓN: '.$datos['eco_755'].'</td>
                        <td colspan="4" name="eco_756">CALIFICACIÓN: '.$datos['eco_756'].'</td>
                    </tr>

                    <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDÓN INFRAESPINOSO</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                    <td name="eco_758">LIMITES:  '.$datos['eco_758'].'</td>
                    <td name="eco_760">ECOESTRUCTURA:  '.$datos['eco_760'].'</td>
                    <td name="eco_762">ECOGENICIDAD (NORMAL):  '.$datos['eco_762'].'</td>
                    <td name="eco_764">ALTERADA:  '.$datos['eco_764'].'</td>
                    <td name="eco_765">ESPESOR:  '.$datos['eco_765'].'</td>
                    <td name="eco_767">ROTURA: '.$datos['eco_767'].'</td>
                    </tr>

                    <tr style="font-size: 50%;">
                        <td name="eco_769">TIPO: '.$datos['eco_769'].'</td>
                        <td  >MEDIDA ROTURA: </td>
                        <td colspan="2" name="eco_772">TRANSVERSAL (MM): '.$datos['eco_772'].'</td>
                        <td  colspan="2" name="eco_773">LONGITUDINAL (MM): '.$datos['eco_773'].'</td>

                </tr>

                <tr style="font-size: 50%;">
                            <td colspan="4" name="eco_774">ESTADÍO DE RETRACCIÓN: '.$datos['eco_774'].'</td>
                            <td colspan="4" name="eco_776">CALIFICACIÓN: '.$datos['eco_776'].'</td>
                </tr>


                    <tr style="font-size: 50%;">
                    <td name="eco_777">BURSITIS SUBDELTOIDEA: '.$datos['eco_777'].'</td>
                    <td name="eco_779">HIPERTROFIA SINOVIAL: '.$datos['eco_779'].'</td>
                    <td name="eco_780">ECOS INTERNOS: '.$datos['eco_780'].'</td>
                    <td name="eco_782">SEÑAL DOPPLER: '.$datos['eco_782'].'</td>
                    <td name="eco_783">ASPECTO DEL CARTÍLAGO ARTICULAR HUMERAL: '.$datos['eco_783'].'</td>
                    <td name="eco_785">IRREGULARIDADES CORTICALES EN TROQUÍTER: '.$datos['eco_785'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_787">EROSIONES (MM): '.$datos['eco_787'].'</td>
                    <td name="eco_788">PROLIFERACIÓN (MM): '.$datos['eco_788'].'</td>
                    <td name="eco_789">INESPECIFICAS: '.$datos['eco_789'].'</td>
                    <td name="eco_790">IRREGULARIDADES CORTICALES EN CABEZA HUMERAL: '.$datos['eco_790'].'</td>
                    <td name="eco_792">EROSIONES (MM): '.$datos['eco_792'].'</td>
                    <td name="eco_793">PROLIFERACIÓN (MM): '.$datos['eco_793'].'</td>
                    </tr>

                    <tr style="font-size: 50%;">
                    <td name="eco_794">MUSCULO DELTOIDES: '.$datos['eco_794'].'</td>
                    <td name="eco_797">ECOESTRUCTURA/ECOGENICIDAD ALTERAS: '.$datos['eco_797'].'</td>
                    <td name="eco_798">ROTURA PARCIAL: '.$datos['eco_798'].'</td>
                    <td name="eco_800">ROTURA TOTAL: '.$datos['eco_800'].'</td>
                    </tr>


                  </table>    
                    ';

                }
                else{
                    $ecoExpoantLHombroDer ='';
                } 
                //ecografia de hombro - EXPLORACIÓN ANTERIOlteral- izquierdo
                if(
                    $datos['eco_799']!= ''  || $datos['eco_801']!= ''
                    || $datos['eco_803']!= '' || $datos['eco_805']!= ''  
                    || $datos['eco_806']!= '' || $datos['eco_808']!= ''
                    || $datos['eco_810']!= '' || $datos['eco_813']!= ''
                    || $datos['eco_814']!= '' || $datos['eco_815']!= ''
                    || $datos['eco_816']!= '' || $datos['eco_818']!= ''
                    || $datos['eco_821']!= '' || $datos['eco_822']!= ''
                    || $datos['eco_824']!= '' || $datos['eco_825']!= ''
                    || $datos['eco_827']!= '' || $datos['eco_829']!= ''
                    || $datos['eco_832']!= '' || $datos['eco_835']!= ''
                    || $datos['eco_833']!= '' || $datos['eco_834']!= ''
                    || $datos['eco_837']!= ''
                    || $datos['eco_839']!= '' || $datos['eco_840']!= ''
                    || $datos['eco_842']!= '' || $datos['eco_843']!= ''
                    || $datos['eco_845']!= '' || $datos['eco_847']!= ''
                    || $datos['eco_848']!= '' || $datos['eco_849']!= ''
                    || $datos['eco_850']!= '' || $datos['eco_852']!= ''
                    || $datos['eco_853']!= '' || $datos['eco_854']!= ''
                    || $datos['eco_857']!= '' || $datos['eco_858']!= ''
                    || $datos['eco_860']!= ''

                ){
                    $ecoExpoantLHombroIzq = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN ANTERIOLTERAL- HOMBRO IZQUIERDO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDÓN DEL SUPRAESPINOSO</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_799">LIMITES: '.$datos['eco_799'].'</td>
                        <td name="eco_801">ECOESTRUCTURA: '.$datos['eco_801'].'</td>
                        <td name="eco_803">ECOGENICIDAD (NORMAL): '.$datos['eco_803'].'</td>
                        <td name="eco_805">ALTERADA: '.$datos['eco_805'].'</td>
                        <td name="eco_806">ESPESOR: '.$datos['eco_806'].'</td>
                        <td name="eco_808">ROTURA: '.$datos['eco_808'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_810">TIPO: '.$datos['eco_810'].'</td>
                        <td  >MEDIDA ROTURA: </td>
                        <td colspan="2" name="eco_813">TRANSVERSAL (MM): '.$datos['eco_813'].'</td>
                        <td colspan="2" name="eco_814">LONGITUDINAL (MM): '.$datos['eco_814'].'</td>   
                    </tr>
                    <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_815">ESTADÍO DE RETRACCIÓN: '.$datos['eco_815'].'</td>
                        <td colspan="4" name="eco_816">CALIFICACIÓN: '.$datos['eco_816'].'</td>
                    </tr>

                    <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>TENDÓN INFRAESPINOSO</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                    <td name="eco_818">LIMITES: '.$datos['eco_818'].'</td>
                    <td name="eco_821">ECOESTRUCTURA: '.$datos['eco_821'].'</td>
                    <td name="eco_822">ECOGENICIDAD (NORMAL): '.$datos['eco_822'].'</td>
                    <td name="eco_824">ALTERADA: '.$datos['eco_824'].'</td>
                    <td name="eco_825">ESPESOR: '.$datos['eco_825'].'</td>
                    <td name="eco_827">ROTURA: '.$datos['eco_827'].'</td>
                    </tr>

                    <tr style="font-size: 50%;">
                        <td name="eco_829">TIPO: '.$datos['eco_829'].'</td>
                        <td >MEDIDA ROTURA: </td>
                        <td colspan="2"  name="eco_832">TRANSVERSAL (MM): '.$datos['eco_832'].'</td>
                        <td  colspan="2"  name="eco_833">LONGITUDINAL (MM): '.$datos['eco_833'].'</td>

                </tr>

                <tr style="font-size: 50%;">
                            <td colspan="4" name="eco_834">ESTADÍO DE RETRACCIÓN: '.$datos['eco_834'].'</td>
                            <td colspan="4" name="eco_835">CALIFICACIÓN: '.$datos['eco_835'].'</td>
                </tr>


                    <tr style="font-size: 50%;">
                    <td name="eco_837">BURSITIS SUBDELTOIDEA: '.$datos['eco_837'].'</td>
                    <td name="eco_839">HIPERTROFIA SINOVIAL: '.$datos['eco_839'].'</td>
                    <td name="eco_840">ECOS INTERNOS: '.$datos['eco_840'].'</td>
                    <td name="eco_842">SEÑAL DOPPLER: '.$datos['eco_842'].'</td>
                    <td name="eco_843">ASPECTO DEL CARTÍLAGO ARTICULAR HUMERAL: '.$datos['eco_843'].'</td>
                    <td name="eco_845">IRREGULARIDADES CORTICALES EN TROQUÍTER: '.$datos['eco_845'].'</td>

                    </tr>
                    <tr style="font-size: 50%;">
                    <td name="eco_847">EROSIONES (MM): '.$datos['eco_847'].'</td>
                    <td name="eco_848">PROLIFERACIÓN (MM): '.$datos['eco_848'].'</td>
                    <td name="eco_849">INESPECIFICAS: '.$datos['eco_849'].'</td>
                    <td name="eco_850">IRREGULARIDADES CORTICALES EN CABEZA HUMERAL: '.$datos['eco_850'].'</td>
                    <td name="eco_852">EROSIONES (MM): '.$datos['eco_852'].'</td>
                    <td name="eco_853">PROLIFERACIÓN (MM): '.$datos['eco_853'].'</td>
                    </tr>

                    <tr style="font-size: 50%;">
                    <td name="eco_854">MUSCULO DELTOIDES: '.$datos['eco_854'].'</td>
                    <td colspan="2" name="eco_857">ECOESTRUCTURA/ECOGENICIDAD ALTERAS: '.$datos['eco_857'].'</td>
                    <td name="eco_858">ROTURA PARCIAL: '.$datos['eco_858'].'</td>
                    <td name="eco_860">ROTURA TOTAL: '.$datos['eco_860'].'</td>
                    </tr>


                  </table>    
                    ';

                }
                else{
                    $ecoExpoantLHombroIzq ='';
                } 

                //ecografia de hombro - EXPLORACIÓN Posterior- derecho
                if(
                    $datos['eco_859']!= ''  || $datos['eco_861']!= ''
                    || $datos['eco_863']!= '' || $datos['eco_864']!= ''  
                    || $datos['eco_867']!= '' || $datos['eco_868']!= ''
                    || $datos['eco_870']!= '' || $datos['eco_871']!= ''
                    || $datos['eco_872']!= '' || $datos['eco_874']!= ''



                ){
                    $ecoExpoantPotHombroDer = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN POSTERIOR- HOMBRO DERECHO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>RECESO GLENOHUMERAL POSTERIOR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_859">DERRAME: '.$datos['eco_859'].'</td>
                        <td name="eco_861">HIPERTROFIA SINOVIAL: '.$datos['eco_861'].'</td>
                        <td name="eco_863">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_863'].'</td>
                        <td name="eco_864">ECOS INTERNOS: '.$datos['eco_864'].'</td>
                        <td name="eco_867">SEÑAL DE DOPPLER: '.$datos['eco_867'].'</td>
                        <td name="eco_868">GRADO SEÑAL DOPPLER: '.$datos['eco_868'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_870">LABRUM POSTERIOR: '.$datos['eco_870'].'</td>
                        <td colspan="2" name="eco_871">ECOESTRUCTURA/ECOGENICIDAD ALTERAS: '.$datos['eco_871'].'</td>
                        <td name="eco_872">ROTURA: '.$datos['eco_872'].'</td>
                        <td name="eco_874">ROTURA QUISTE: '.$datos['eco_874'].'</td>

                    </tr>



                  </table>    
                    ';

                }
                else{
                    $ecoExpoantPotHombroDer ='';
                } 

                //ecografia de hombro - EXPLORACIÓN Posterior- IZQUIERDOs
                if(
                    $datos['eco_859']!= ''  || $datos['eco_861']!= ''
                    || $datos['eco_863']!= '' || $datos['eco_864']!= ''  
                    || $datos['eco_867']!= '' || $datos['eco_868']!= ''
                    || $datos['eco_870']!= '' || $datos['eco_871']!= ''
                    || $datos['eco_872']!= '' || $datos['eco_874']!= ''



                ){
                    $ecoExpoantPotHombroIzq = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN POSTERIOR- HOMBRO IZQUIERDO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>RECESO GLENOHUMERAL POSTERIOR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_859">DERRAME: '.$datos['eco_859'].'</td>
                        <td name="eco_861">HIPERTROFIA SINOVIAL: '.$datos['eco_861'].'</td>
                        <td name="eco_863">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_863'].'</td>
                        <td name="eco_864">ECOS INTERNOS: '.$datos['eco_864'].'</td>
                        <td name="eco_867">SEÑAL DE DOPPLER: '.$datos['eco_867'].'</td>
                        <td name="eco_868">GRADO SEÑAL DOPPLER: '.$datos['eco_868'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_870">LABRUM POSTERIOR: '.$datos['eco_870'].'</td>
                        <td colspan="2"  name="eco_871">ECOESTRUCTURA/ECOGENICIDAD ALTERAS: '.$datos['eco_871'].'</td>
                        <td name="eco_872">ROTURA: '.$datos['eco_872'].'</td>
                        <td name="eco_874">ROTURA QUISTE: '.$datos['eco_874'].'</td>

                    </tr>

                  </table>    
                    ';

                }
                else{
                    $ecoExpoantPotHombroIzq ='';
                } 

                //ecografia de hombro - EXPLORACIÓN AXILAR- DERECHO
                if(
                    $datos['eco_887']!= ''  || $datos['eco_890']!= ''
                    || $datos['eco_891']!= '' || $datos['eco_892']!= ''  
                    || $datos['eco_894']!= '' || $datos['eco_896']!= ''




                ){
                    $ecoExpoaxiHombroDer = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN AXILAR- HOMBRO DERECHO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>RECESO GLENOHUMERAL POSTERIOR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_887">DERRAME: '.$datos['eco_887'].'</td>
                        <td name="eco_890">HIPERTROFIA SINOVIAL: '.$datos['eco_890'].'</td>
                        <td name="eco_891">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_891'].'</td>
                        <td name="eco_892">ECOS INTERNOS: '.$datos['eco_892'].'</td>
                        <td name="eco_894">SEÑAL DE DOPPLER: '.$datos['eco_894'].'</td>
                        <td name="eco_896">GRADO SEÑAL DOPPLER: '.$datos['eco_896'].'</td>
                    </tr>


                  </table>    
                    ';

                }
                else{
                    $ecoExpoaxiHombroDer ='';
                } 
                //ecografia de hombro - EXPLORACIÓN AXILAR- IZQUIERDO
                if(
                    $datos['eco_897']!= ''  || $datos['eco_899']!= ''
                    || $datos['eco_901']!= '' || $datos['eco_902']!= ''  
                    || $datos['eco_904']!= '' || $datos['eco_906']!= ''




                ){
                    $ecoExpoaxiHombroIzq = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN AXILAR- HOMBRO IZQUIERDO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>RECESO GLENOHUMERAL POSTERIOR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_897">DERRAME: '.$datos['eco_887'].'</td>
                        <td name="eco_899">HIPERTROFIA SINOVIAL: '.$datos['eco_890'].'</td>
                        <td name="eco_901">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_891'].'</td>
                        <td name="eco_902">ECOS INTERNOS: '.$datos['eco_892'].'</td>
                        <td name="eco_904">SEÑAL DE DOPPLER: '.$datos['eco_894'].'</td>
                        <td name="eco_906">GRADO SEÑAL DOPPLER: '.$datos['eco_896'].'</td>
                    </tr>


                  </table>    
                    ';

                }
                else{
                    $ecoExpoaxiHombroIzq ='';
                } 
                //ecografia de hombro - EXPLORACIÓN DINAMICA- DERECHO
                if(
                    $datos['eco_909']!= ''  || $datos['eco_907']!= ''
                    || $datos['eco_910']!= '' || $datos['eco_911']!= '' 




                ){
                    $ecoExpodinaHombroDer = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN DINAMICA - HOMBRO DERECHO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>RECESO GLENOHUMERAL AXILAR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_907">TENDÓN DEL SUPRAESPINOSO EN ABDUCCIÓN: '.$datos['eco_907'].'</td>
                        <td >TENDÓN DEL BICEPS EN ROTACIONES: </td>
                        <td name="eco_909">EN CORREDERA: '.$datos['eco_909'].'</td>
                        <td name="eco_910">SUBLUXACIÓN/LUXACIÓN MEDIAL: '.$datos['eco_910'].'</td>
                        <td name="eco_911">SUBLUXACIÓN/LUXACIÓN LATERAL: '.$datos['eco_911'].'</td>
                    </tr>


                  </table>    
                    ';

                }
                else{
                    $ecoExpodinaHombroDer ='';
                } 
                //ecografia de hombro - EXPLORACIÓN DINAMICA- izquierdo
                if(
                    $datos['eco_913']!= ''  || $datos['eco_915']!= ''
                    || $datos['eco_916']!= '' || $datos['eco_917']!= ''  

                ){
                    $ecoExpodinaHombroIzq = '
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN DINAMICA - HOMBRO IZQUIERDO</strong></span>
                    </td>
                  </tr>
                  <tr style="font-size: 40%; line-height: 9px">
                  <td style="text-align: center;padding: 5px;" colspan="7">
                      <span><strong>RECESO GLENOHUMERAL AXILAR</strong></span>
                  </td>
                  </tr>
                    <tr style="font-size: 50%;">

                        <td name="eco_907">TENDÓN DEL SUPRAESPINOSO EN ABDUCCIÓN: '.$datos['eco_907'].'</td>
                        <td >TENDÓN DEL BICEPS EN ROTACIONES: </td>
                        <td name="eco_909">EN CORREDERA: '.$datos['eco_909'].'</td>
                        <td name="eco_910">SUBLUXACIÓN/LUXACIÓN MEDIAL: '.$datos['eco_910'].'</td>
                        <td name="eco_911">SUBLUXACIÓN/LUXACIÓN LATERAL: '.$datos['eco_911'].'</td>
                    </tr>


                  </table>    
                    ';

                }
                else{
                    $ecoExpodinaHombroIzq ='';
                } 
                // contruccion del bloque de ecorafia de hombro

                if (empty($ecoExpoantHombroDer) && empty($ecoExpoantHombroIzq) && empty($ecoExpoantLHombroDer) 
                && empty($ecoExpoantLHombroIzq) && empty($ecoExpoantPotHombroDer)  && empty($ecoExpoantPotHombroIzq) 
                && empty($ecoExpoaxiHombroDer)  && empty($ecoExpoaxiHombroIzq) && empty($ecoExpodinaHombroDer)
                && empty($ecoExpodinaHombroIzq) 
                ){
                    $informDataHistory.='';
                }else{
                    $informDataHistory.=  
                    '<table style="padding: 2px; width: 100%">
                    <tr style="font-size: 10%">
                        <td style="text-align: center;" >

                        </td>
                    </tr>
                    <tr style="font-size: 70%">
                        <td style="text-align: center;" >
                            <br><strong> ECOGRAFÍA DE ALTA RESOLUCIÓN DE HOMBRO</strong>
                        </td>
                    </tr>

                </table>
                ';
                }
                if(!empty($ecoExpoantHombroDer) ){

                    $informDataHistory.= '
                    '.$ecoExpoantHombroDer.'
                             ';
                };
                if(!empty($ecoExpoantHombroIzq) ){

                    $informDataHistory.= '
                    '.$ecoExpoantHombroIzq.'
                             ';
                };
                if(!empty($ecoExpoantLHombroDer) ){

                    $informDataHistory.= '
                    '.$ecoExpoantLHombroDer.'
                             ';
                };
                if(!empty($ecoExpoantLHombroIzq) ){

                    $informDataHistory.= '
                    '.$ecoExpoantLHombroIzq.'
                             ';
                };
                if(!empty($ecoExpoantPotHombroDer) ){

                    $informDataHistory.= '
                    '.$ecoExpoantPotHombroDer.'
                             ';
                };
                if(!empty($ecoExpoantPotHombroIzq) ){

                    $informDataHistory.= '
                    '.$ecoExpoantPotHombroIzq.'
                             ';
                };
                if(!empty($ecoExpoaxiHombroDer) ){

                    $informDataHistory.= '
                    '.$ecoExpoaxiHombroDer.'
                             ';
                };
                if(!empty($ecoExpodinaHombroDer) ){

                    $informDataHistory.= '
                    '.$ecoExpodinaHombroDer.'
                             ';
                };
                if(!empty($ecoExpodinaHombroIzq) ){

                    $informDataHistory.= '
                    '.$ecoExpodinaHombroIzq.'
                             ';
                };

                // ecografia de cadera - EXPLORACIÓN ANTERIOR- derecha

                if(
                    $datos['eco_919']!= ''  || $datos['eco_921']!= ''
                    || $datos['eco_923']!= '' || $datos['eco_925']!= ''  
                    || $datos['eco_926']!= '' || $datos['eco_928']!= ''
                    || $datos['eco_930']!= '' || $datos['eco_931']!= ''
                    || $datos['eco_932']!= '' || $datos['eco_933']!= ''
                    || $datos['eco_934']!= '' 

                ){
                    $ecoExpodinaAntCaderaDer='
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN ANTERIOR - CADERA DERECHA</strong></span>
                    </td>
                  </tr>

                    <tr style="font-size: 50%;">
                        <td name="eco_919">ARTICULACION COXFEMORAL / RECESO: '.$datos['eco_919'].'</td>
                        <td name="eco_921">IRREGULARIDADES BORDES OSEOS: '.$datos['eco_921'].'</td>
                        <td name="eco_923">SINOVITIS (DERRAME/HIPERTROFIA SINOVIAL): '.$datos['eco_923'].'</td>
                        <td name="eco_925">GRADO: '.$datos['eco_925'].'</td>
                        <td name="eco_926">SEÑAL POWER DOPPLER INTRAARTICULAR: '.$datos['eco_926'].'</td>
                        <td name="eco_928">GRADO: '.$datos['eco_928'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_930">ASPECTO DEL CARTILAGO ARTICULAR VISIBLE: '.$datos['eco_930'].'</td>
                        <td name="eco_931">BURSA ILIOPSOAS: '.$datos['eco_931'].'</td>
                        <td name="eco_932">BURSITIS ILIOPSOAS: '.$datos['eco_932'].'</td>
                        <td name="eco_933">MÚSCULO PSOAS: '.$datos['eco_933'].'</td>
                        <td colspan="2" name="eco_934">ALTERACIONES ECOESTRUCTURA/ECOGENICIDAD: '.$datos['eco_934'].'</td>
                </tr>

                    </table>  
                    ';

                }
                else{
                    $ecoExpodinaAntCaderaDer = '';
                }

                // ecografia de cadera - EXPLORACIÓN ANTERIOR- iquierda

                if(
                     $datos['eco_935']!= ''
                    || $datos['eco_937']!= '' || $datos['eco_939']!= ''
                    || $datos['eco_941']!= '' || $datos['eco_942']!= ''
                    || $datos['eco_944']!= '' || $datos['eco_945']!= ''
                    || $datos['eco_947']!= '' || $datos['eco_948']!= ''
                    || $datos['eco_949']!= '' || $datos['eco_950']!= ''

                ){
                    $ecoExpodinaAntCaderaIzq='
                    <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                    <tr style="font-size: 55%; line-height: 9px">
                    <td style="text-align: center;padding: 15px;" colspan="6">
                        <span><strong>EXPLORACIÓN ANTERIOR - CADERA IZQUIERDA</strong></span>
                    </td>
                  </tr>

                    <tr style="font-size: 50%;">
                        <td name="eco_935">ARTICULACION COXFEMORAL / RECESO: '.$datos['eco_935'].'</td>
                        <td name="eco_937">IRREGULARIDADES BORDES OSEOS: '.$datos['eco_937'].'</td>
                        <td name="eco_939">SINOVITIS (DERRAME/HIPERTROFIA SINOVIAL): '.$datos['eco_939'].'</td>
                        <td name="eco_941">GRADO: '.$datos['eco_941'].'</td>
                        <td name="eco_942">SEÑAL POWER DOPPLER INTRAARTICULAR: '.$datos['eco_942'].'</td>
                        <td name="eco_944">GRADO: '.$datos['eco_944'].'</td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_945">ASPECTO DEL CARTILAGO ARTICULAR VISIBLE: '.$datos['eco_945'].'</td>
                        <td name="eco_947">BURSA ILIOPSOAS: '.$datos['eco_947'].'</td>
                        <td name="eco_948">BURSITIS ILIOPSOAS: '.$datos['eco_948'].'</td>
                        <td name="eco_949">MÚSCULO PSOAS: '.$datos['eco_949'].'</td>
                        <td colspan="2" name="eco_950">ALTERACIONES ECOESTRUCTURA/ECOGENICIDAD: '.$datos['eco_950'].'</td>
                </tr>

                    </table>  
                    ';

                }
                else{
                    $ecoExpodinaAntCaderaIzq = '';
                }

                // ecografia de cadera - EEXPLORACION LATERAL (TRONCATER)- DERECHA

                if(
                    $datos['eco_951']!= ''
                   || $datos['eco_953']!= '' || $datos['eco_955']!= ''
                   || $datos['eco_957']!= '' || $datos['eco_959']!= ''
                   || $datos['eco_961']!= '' || $datos['eco_963']!= ''
                   || $datos['eco_965']!= '' 


                ){
                   $ecoExpodinaLatCaderaDer='
                   <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                   <tr style="font-size: 55%; line-height: 9px">
                   <td style="text-align: center;padding: 15px;" colspan="6">
                       <span><strong>EXPLORACION LATERAL (TRONCATER) - CADERA DERECHA</strong></span>
                   </td>
                 </tr>

                   <tr style="font-size: 50%;">
                            <td name="eco_951">INSERCIÓN GLUTEOS: '.$datos['eco_951'].'</td>
                            <td name="eco_953">LIMITES BIEN DEFINIDOS: '.$datos['eco_953'].'</td>
                            <td name="eco_955">ECOESTRUCTURA HOMOGÉNEA: '.$datos['eco_955'].'</td>
                            <td name="eco_957">ECOGENICIDAD NORMAL: '.$datos['eco_957'].'</td>
                            <td name="eco_959">ROTURA: '.$datos['eco_959'].'</td>
                            <td name="eco_961">IRREGULARIDADES CORTICALES: '.$datos['eco_961'].'</td>
                   </tr>
                   <tr style="font-size: 50%;">
                            <td colspan="2" name="eco_963">BURSITIS TROCANTEREA PROFUNDA: '.$datos['eco_963'].'</td>
                            <td colspan="2" name="eco_965">BURSITIS TROCANTEREA SUPERFICIAL: '.$datos['eco_965'].'</td>
                    </tr>

                   </table>  
                   ';

                }
                else{
                   $ecoExpodinaLatCaderaDer = '';
                }
                // ecografia de cadera - EXPLORACION LATERAL (TRONCATER)-iZquierda

                if(
                    $datos['eco_967']!= ''
                   || $datos['eco_969']!= '' || $datos['eco_971']!= ''
                   || $datos['eco_973']!= '' || $datos['eco_975']!= ''
                   || $datos['eco_979']!= '' || $datos['eco_981']!= ''
                   || $datos['eco_977']!= '' 


                ){
                   $ecoExpodinaLatCaderaIzq='
                   <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                   <tr style="font-size: 55%; line-height: 9px">
                   <td style="text-align: center;padding: 15px;" colspan="6">
                       <span><strong>EXPLORACION LATERAL (TRONCATER) - CADERA IZQUIERDA</strong></span>
                   </td>
                 </tr>

                   <tr style="font-size: 50%;">
                            <td name="eco_967">INSERCIÓN GLUTEOS: '.$datos['eco_967'].'</td>
                            <td name="eco_969">LIMITES BIEN DEFINIDOS: '.$datos['eco_969'].'</td>
                            <td name="eco_971">ECOESTRUCTURA HOMOGÉNEA: '.$datos['eco_971'].'</td>
                            <td name="eco_973">ECOGENICIDAD NORMAL: '.$datos['eco_973'].'</td>
                            <td name="eco_975">ROTURA: '.$datos['eco_975'].'</td>
                            <td name="eco_977">IRREGULARIDADES CORTICALES: '.$datos['eco_977'].'</td>
                   </tr>
                   <tr style="font-size: 50%;">
                            <td colspan="2" name="eco_979">BURSITIS TROCANTEREA PROFUNDA: '.$datos['eco_979'].'</td>
                            <td colspan="2" name="eco_981">BURSITIS TROCANTEREA SUPERFICIAL: '.$datos['eco_981'].'</td>
                    </tr>

                   </table>  
                   ';

                }
                else{
                   $ecoExpodinaLatCaderaIzq = '';
                }

                // construccion del bloque de ecografia de cadera
                if (empty($ecoExpodinaAntCaderaDer)  && empty($ecoExpodinaAntCaderaIzq)  
                    && empty($ecoExpodinaLatCaderaDer) && empty($ecoExpodinaLatCaderaIzq)
                    ){
                    $informDataHistory.='';
                }else{
                    '<table style="padding: 2px; width: 100%">
                    <tr style="font-size: 10%">
                        <td style="text-align: center;" >

                        </td>
                    </tr>
                    <tr style="font-size: 70%">
                        <td style="text-align: center;" >
                            <br><strong> ECOGRAFÍA DE ALTA RESOLUCIÓN DE CADERA</strong>
                        </td>
                    </tr>

                </table>
                ';
                }
                if(!empty($ecoExpodinaAntCaderaDer) ){

                    $informDataHistory.= '
                    '.$ecoExpodinaAntCaderaDer.'
                             ';
                };
                if(!empty($ecoExpodinaAntCaderaIzq) ){

                    $informDataHistory.= '
                    '.$ecoExpodinaAntCaderaIzq.'
                             ';
                };
                if(!empty($ecoExpodinaLatCaderaDer) ){

                    $informDataHistory.= '
                    '.$ecoExpodinaLatCaderaDer.'
                             ';
                };
                if(!empty($ecoExpodinaLatCaderaIzq) ){

                    $informDataHistory.= '
                    '.$ecoExpodinaLatCaderaIzq.'
                             ';
                };

                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPARTIMIENTO ANTERIOR - SUPRAPATELAR DERECHO

                if(
                    $datos['eco_983']!= '' || $datos['eco_985']!= '' 
                    || $datos['eco_987']!= ''  || $datos['eco_989']!= ''
                    || $datos['eco_990']!= ''  || $datos['eco_992']!= ''
                    || $datos['eco_994']!= ''  || $datos['eco_995']!= ''
                    || $datos['eco_996']!= ''  || $datos['eco_997']!= ''
                    || $datos['eco_999']!= ''  || $datos['eco_1000']!= ''
                    || $datos['eco_1001']!= ''  || $datos['eco_1002']!= ''
                    || $datos['eco_1005']!= ''  || $datos['eco_1008']!= ''
                    || $datos['eco_1007']!= ''   || $datos['eco_1011']!= ''
                    || $datos['eco_1009']!= ''  || $datos['eco_1010']!= ''
                    || $datos['eco_1013']!= ''  || $datos['eco_1012']!= ''
                    || $datos['eco_1014']!= ''  || $datos['eco_1016']!= ''
                    || $datos['eco_1017']!= ''  || $datos['eco_1019']!= ''
                    || $datos['eco_1021']!= ''  || $datos['eco_1022']!= ''
                    || $datos['eco_1024']!= ''  || $datos['eco_1026']!= ''
                    || $datos['eco_1027']!= ''  || $datos['eco_1029']!= ''
                    || $datos['eco_1031']!= ''  || $datos['eco_1032']!= ''
                    || $datos['eco_1034']!= ''  || $datos['eco_1036']!= ''
                    || $datos['eco_1037']!= ''  || $datos['eco_1039']!= ''
                    || $datos['eco_1041']!= ''
                ){
                  $ecoSupratelarAntDer= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPARTIMIENTO ANTERIOR - SUPRAPATELAR DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN CUADRICEPS</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_983">LIMITES: '.$datos['eco_983'].'</td>
                            <td name="eco_985">ECOESTRUCTURA: '.$datos['eco_985'].'</td>
                            <td colspan="2" name="eco_987">ECOGENICIDAD (NORMAL): '.$datos['eco_987'].'</td>
                            <td colspan="2" name="eco_989">ECOGENICIDAD ALTERADA: '.$datos['eco_989'].'</td>

                        </tr>
                        <tr style="font-size: 50%;">
                             <td name="eco_990">ESPESOR: '.$datos['eco_990'].'</td>
                            <td name="eco_992">ROTURA: '.$datos['eco_992'].'</td>
                            <td name="eco_994">TIPO DE ROTURA: '.$datos['eco_994'].'</td>

                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>ALTERACIONES EN</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                             <td colspan="2" name="eco_995">ENTESIS: '.$datos['eco_995'].'</td>
                            <td colspan="2" name="eco_996">PROXIMAL A ENTESIS: '.$datos['eco_996'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">

                            <td colspan="2" name="eco_997">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_997'].'</td>
                            <td name="eco_999">EROSIONES (MM): '.$datos['eco_999'].'</td>
                            <td  name="eco_1000">PROLIFERACIÓN (MM): '.$datos['eco_1000'].'</td>
                            <td name="eco_1001">SEÑAL PD EN ENTESIS: '.$datos['eco_1001'].'</td>
                            <td name="eco_1002">GRADO SEÑAL PD: '.$datos['eco_1002'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ALTERACIONES EN</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                             <td colspan="2" name="eco_1005">TRANSVERSAL (MM): '.$datos['eco_1005'].'</td>
                            <td colspan="2" name="eco_1008">LONGITUDINAL (MM): '.$datos['eco_1008'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>MUSCULO CUADRICEPS</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1007"> MUSCULO CUADRICEPS: '.$datos['eco_1007'].'</td>
                            <td name="eco_1011">MEDIDA ROTURA (MM): '.$datos['eco_1011'].'</td>
                            <td name="eco_1009">ECOESTRUCTURA/ECOGENICIDAD ALTERADAS: '.$datos['eco_1009'].'</td>
                            <td name="eco_1010">ROTURA PARCIAL: '.$datos['eco_1010'].'</td>
                            <td name="eco_1013">ROTURA TOTAL: '.$datos['eco_1013'].'</td>
                        </tr>


                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSA SUPRAPATELAR</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1012">DERRAME: '.$datos['eco_1012'].'</td>
                            <td name="eco_1014">HIPERTROFIA SINOVIAL: '.$datos['eco_1014'].'</td>
                            <td name="eco_1016">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_1016'].'</td>
                            <td name="eco_1017">ECOS INTERNOS: '.$datos['eco_1017'].'</td>
                            <td name="eco_1019">SEÑAL DE DOPPLER: '.$datos['eco_1019'].'</td>
                            <td name="eco_1021">GRADO SEÑAL DE DOPPLER: '.$datos['eco_1021'].'</td>
                    </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>RECESO PARAPATERAL EXTERNO</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1022">DERRAME: '.$datos['eco_1022'].'</td>
                                <td name="eco_1024">HIPERTROFIA SINOVIAL: '.$datos['eco_1024'].'</td>
                                <td name="eco_1026">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_1026'].'</td>
                                <td name="eco_1027">ECOS INTERNOS: '.$datos['eco_1027'].'</td>
                                <td name="eco_1029">SEÑAL DE DOPPLER: '.$datos['eco_1029'].'</td>
                                <td name="eco_1031">GRADO SEÑAL DE DOPPLER: '.$datos['eco_1031'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>RECESO PARAPATERAL INTERNO</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1032">DERRAME: '.$datos['eco_1032'].'</td>
                                <td name="eco_1034">HIPERTROFIA SINOVIAL: '.$datos['eco_1034'].'</td>
                                <td name="eco_1036">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_1036'].'</td>
                                <td name="eco_1037">ECOS INTERNOS: '.$datos['eco_1037'].'</td>
                                <td name="eco_1039">SEÑAL DE DOPPLER: '.$datos['eco_1039'].'</td>
                                <td name="eco_1041">GRADO SEÑAL DE DOPPLER: '.$datos['eco_1041'].'</td>
                        </tr>

                  </table>  
                  ';
                }
                else{
                    $ecoSupratelarAntDer='';
                }


                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPARTIMIENTO ANTERIOR - SUPRAPATELAR IZQUIERDO

                if(
                    $datos['eco_1042']!= '' || $datos['eco_1044']!= '' 
                    || $datos['eco_1046']!= ''  || $datos['eco_1048']!= ''
                    || $datos['eco_1049']!= ''  || $datos['eco_1051']!= ''
                    || $datos['eco_1053']!= ''  || $datos['eco_1054']!= ''
                    || $datos['eco_1055']!= ''  || $datos['eco_1056']!= ''
                    || $datos['eco_1058']!= ''  || $datos['eco_1059']!= ''
                    || $datos['eco_1060']!= ''  || $datos['eco_1062']!= ''
                    || $datos['eco_1065']!= ''  || $datos['eco_1064']!= ''
                    || $datos['eco_1066']!= ''   || $datos['eco_1070']!= ''
                    || $datos['eco_1068']!= ''  || $datos['eco_1069']!= ''
                    || $datos['eco_1072']!= ''  || $datos['eco_1071']!= ''
                    || $datos['eco_1073']!= ''  || $datos['eco_1075']!= ''
                    || $datos['eco_1076']!= ''  || $datos['eco_1078']!= ''
                    || $datos['eco_1080']!= ''  || $datos['eco_1081']!= ''
                    || $datos['eco_1083']!= ''  || $datos['eco_1085']!= ''
                    || $datos['eco_1086']!= ''  || $datos['eco_1088']!= ''
                    || $datos['eco_1090']!= ''  || $datos['eco_1091']!= ''
                    || $datos['eco_1093']!= ''  || $datos['eco_1095']!= ''
                    || $datos['eco_1096']!= ''  || $datos['eco_1098']!= ''
                    || $datos['eco_1100']!= ''
                ){
                  $ecoSupratelarAntIzq= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPARTIMIENTO ANTERIOR - SUPRAPATELAR IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN CUADRICEPS</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1042">LIMITES: '.$datos['eco_1042'].'</td>
                            <td name="eco_1044">ECOESTRUCTURA: '.$datos['eco_1044'].'</td>
                            <td colspan="2" name="eco_1046">ECOGENICIDAD (NORMAL): '.$datos['eco_1046'].'</td>
                            <td colspan="2" name="eco_1048">ECOGENICIDAD ALTERADA: '.$datos['eco_1048'].'</td>
                            <td name="eco_1049">ESPESOR: '.$datos['eco_1049'].'</td>
                            <td name="eco_1051">ROTURA: '.$datos['eco_1051'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1049">ESPESOR: '.$datos['eco_1049'].'</td>
                            <td name="eco_1051">ROTURA: '.$datos['eco_1051'].'</td>
                            <td name="eco_1053">TIPO DE ROTURA: '.$datos['eco_1053'].'</td>
                        </tr>   
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ALTERACIONES EN</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">

                            <td  colspan="2" name="eco_1054">ENTESIS: '.$datos['eco_1054'].'</td>
                            <td  colspan="2" name="eco_1055">PROXIMAL A ENTESIS: '.$datos['eco_1055'].'</td>
                        </tr>

                        <tr style="font-size: 50%;">
                            <td colspan="2" name="eco_1056">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1056'].'</td>
                            <td name="eco_1058">EROSIONES (MM): '.$datos['eco_1058'].'</td>
                            <td  name="eco_1059">PROLIFERACIÓN (MM): '.$datos['eco_1059'].'</td>
                            <td name="eco_1060">SEÑAL PD EN ENTESIS: '.$datos['eco_1060'].'</td>
                            <td name="eco_1062">GRADO SEÑAL PD: '.$datos['eco_1062'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ALTERACIONES EN</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td colspan="2" name="eco_1065">TRANSVERSAL (MM): '.$datos['eco_1065'].'</td>
                                <td colspan="2" name="eco_1064">LONGITUDINAL (MM): '.$datos['eco_1064'].'</td>

                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                        <span><strong>MUSCULO CUADRICEPS</strong></span>
                        </td>
                    </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1066"> MUSCULO CUADRICEPS: '.$datos['eco_1066'].'</td>
                            <td name="eco_1070">MEDIDA ROTURA (MM): '.$datos['eco_1070'].'</td>
                             <td name="eco_1070">ECOESTRUCTURA/ECOGENICIDAD ALTERADAS: '.$datos['eco_1070'].'</td>
                             <td name="eco_1069">ROTURA PARCIAL: '.$datos['eco_1069'].'</td>
                             <td name="eco_1072">ROTURA TOTAL: '.$datos['eco_1072'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSA SUPRAPATELAR</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1071">DERRAME: '.$datos['eco_1071'].'</td>
                            <td name="eco_1073">HIPERTROFIA SINOVIAL: '.$datos['eco_1073'].'</td>
                            <td name="eco_1075">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_1075'].'</td>
                            <td name="eco_1076">ECOS INTERNOS: '.$datos['eco_1076'].'</td>
                            <td name="eco_1078">SEÑAL DE DOPPLER: '.$datos['eco_1078'].'</td>
                            <td name="eco_1080">GRADO SEÑAL DE DOPPLER: '.$datos['eco_1080'].'</td>
                    </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>RECESO PARAPATERAL EXTERNO</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1081">DERRAME: '.$datos['eco_1081'].'</td>
                                <td name="eco_1083">HIPERTROFIA SINOVIAL: '.$datos['eco_1083'].'</td>
                                <td name="eco_1085">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_1085'].'</td>
                                <td name="eco_1086">ECOS INTERNOS: '.$datos['eco_1086'].'</td>
                                <td name="eco_1088">SEÑAL DE DOPPLER: '.$datos['eco_1088'].'</td>
                                <td name="eco_1090">GRADO SEÑAL DE DOPPLER: '.$datos['eco_1090'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>RECESO PARAPATERAL INTERNO</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1091">DERRAME: '.$datos['eco_1091'].'</td>
                                <td name="eco_1093">HIPERTROFIA SINOVIAL: '.$datos['eco_1093'].'</td>
                                <td name="eco_1095">GRADO HIPERTROFIA SINOVIAL: '.$datos['eco_1095'].'</td>
                                <td name="eco_1096">ECOS INTERNOS: '.$datos['eco_1096'].'</td>
                                <td name="eco_1098">SEÑAL DE DOPPLER: '.$datos['eco_1098'].'</td>
                                <td name="eco_1100">GRADO SEÑAL DE DOPPLER: '.$datos['eco_1100'].'</td>
                        </tr>

                  </table>  
                  ';
                }
                else{
                    $ecoSupratelarAntIzq='';
                }


                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA INFRAPATELAR DERECHO

                if(
                    $datos['eco_1101']!= '' || $datos['eco_1103']!= '' 
                    || $datos['eco_1105']!= ''  || $datos['eco_1107']!= ''
                    || $datos['eco_1108']!= ''  || $datos['eco_1110']!= ''
                    || $datos['eco_1112']!= ''  || $datos['eco_1113']!= ''
                    || $datos['eco_1114']!= ''  || $datos['eco_1115']!= ''
                    || $datos['eco_1116']!= ''  || $datos['eco_1117']!= ''
                    || $datos['eco_1118']!= ''  || $datos['eco_1120']!= ''
                    || $datos['eco_1122']!= ''  || $datos['eco_1123']!= ''
                    || $datos['eco_1124']!= ''   || $datos['eco_1126']!= ''
                    || $datos['eco_1128']!= ''  || $datos['eco_1130']!= ''
                    || $datos['eco_1134']!= ''  || $datos['eco_1131']!= ''
                    || $datos['eco_1135']!= ''  || $datos['eco_1136']!= ''
                    || $datos['eco_1138']!= ''  || $datos['eco_1139']!= ''
                    || $datos['eco_1141']!= ''  || $datos['eco_1142']!= ''
                    || $datos['eco_1144']!= ''  || $datos['eco_1145']!= ''
                    || $datos['eco_1147']!= ''  || $datos['eco_1148']!= ''
                    || $datos['eco_1150']!= ''  || $datos['eco_1151']!= ''
                    || $datos['eco_1153']!= ''  || $datos['eco_1154']!= ''
                    || $datos['eco_1156']!= ''  || $datos['eco_1159']!= ''
                    || $datos['eco_1161']!= '' || $datos['eco_1163']!= ''
                    || $datos['eco_1165']!= '' || $datos['eco_1167']!= ''
                    || $datos['eco_1169']!= '' || $datos['eco_1171']!= ''
                    || $datos['eco_1172']!= '' || $datos['eco_1173']!= ''
                    || $datos['eco_1175']!= '' || $datos['eco_1176']!= ''
                    || $datos['eco_1177']!= '' || $datos['eco_1179']!= ''
                    || $datos['eco_1181']!= '' || $datos['eco_1182']!= ''
                ){
                  $ecoIntraAntDer= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>INFRAPATELAR DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN ROTULIANO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1101">LIMITES: '.$datos['eco_1101'].'</td>
                            <td name="eco_1103">ECOESTRUCTURA: '.$datos['eco_1103'].'</td>
                            <td colspan="2" name="eco_1105">ECOGENICIDAD (NORMAL): '.$datos['eco_1105'].'</td>
                            <td colspan="2" name="eco_1107">ECOGENICIDAD ALTERADA: '.$datos['eco_1107'].'</td>

                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1108">ESPESOR: '.$datos['eco_1108'].'</td>
                            <td name="eco_1113">ROTURA: '.$datos['eco_1113'].'</td>
                            <td name="eco_1112">TIPO DE ROTURA: '.$datos['eco_1112'].'</td>
                         </tr>
                         <tr style="font-size: 40%; line-height: 9px">
                         <td style="text-align: center;padding: 5px;" colspan="7">
                             <span><strong>ALTERACIONES EN</strong></span>
                                </td>
                        </tr>  
                        <tr style="font-size: 50%;"> 
                            <td name="eco_1114">ENTESIS: '.$datos['eco_1114'].'</td>
                            <td name="eco_1115">ROTULIANA; '.$datos['eco_1115'].'</td>
                            <td name="eco_1116">TIBIAL: '.$datos['eco_1116'].'</td>
                            <td name="eco_1117">NO ENTESIS: '.$datos['eco_1117'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1118">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1118'].'</td>
                        <td name="eco_1120">ROTULIANA: '.$datos['eco_1120'].'</td>
                        <td name="eco_1122">EROSIONES (MM): '.$datos['eco_1122'].'</td>
                        <td name="eco_1123">PROLIFERACIÓN (MM): '.$datos['eco_1123'].'</td>
                        <td name="eco_1124">TIBIAL: '.$datos['eco_1124'].'</td>
                        <td name="eco_1126">EROSIONES (MM): '.$datos['eco_1126'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="2" name="eco_1127">PROLIFERACIÓN (MM): '.$datos['eco_1127'].'</td>
                        <td name="eco_1128">SEÑAL PD EN ENTESIS: '.$datos['eco_1128'].'</td>
                        <td name="eco_1130">GRADO SEÑAL PD: '.$datos['eco_1130'].'</td>
                        <td colspan="2" name="eco_1131">TIPO SEÑAL PD: '.$datos['eco_1131'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="2">MEDIDA ROTURA: </td>
                        <td name="eco_1134">TRANSVERSAL (MM): '.$datos['eco_1134'].'</td>
                        <td name="eco_1135">LONGITUDINAL (MM): '.$datos['eco_1135'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSITIS PRERROTULIANA</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1136">BURSITIS PRERROTULIANA: '.$datos['eco_1136'].'</td>
                        <td name="eco_1138">HIPERTROFIA SINOVIAL: '.$datos['eco_1138'].'</td>
                        <td name="eco_1139">ECOS INTERNOS: '.$datos['eco_1139'].'</td>
                        <td name="eco_1141">SEÑAL DOPPLER: '.$datos['eco_1141'].'</td>
                       </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSITIS INFRARROTULIANA</strong></span>
                            </td>
                        </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1142">BURSITIS PRERROTULIANA: '.$datos['eco_1142'].'</td>
                                <td name="eco_1144">HIPERTROFIA SINOVIAL: '.$datos['eco_1144'].'</td>
                                <td name="eco_1145">ECOS INTERNOS: '.$datos['eco_1145'].'</td>
                                <td name="eco_1147">SEÑAL DOPPLER: '.$datos['eco_1147'].'</td>
                            </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>BURSITIS INFRARROTULIANA PROFUNDA</strong></span>
                                </td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td colspan="2" name="eco_1148">BURSITIS INFRARROTULIANA PROFUNDA: '.$datos['eco_1148'].'</td>
                                <td name="eco_1150">HIPERTROFIA SINOVIAL: '.$datos['eco_1150'].'</td>
                                <td name="eco_1151">ECOS INTERNOS: '.$datos['eco_1151'].'</td>
                                <td name="eco_1153">SEÑAL DOPPLER: '.$datos['eco_1153'].'</td>
                            </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>CARTILAGO ARTICULAR</strong></span>
                            </td>
                             </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1154">ECOESTRUCTURA: '.$datos['eco_1154'].'</td>
                                <td name="eco_1156">LIMITES: '.$datos['eco_1156'].'</td>
                                <td colspan="2" name="eco_1158">GROSOR MM: '.$datos['eco_1158'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>INSERCION ANERNINA</strong></span>
                        </td>
                    </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1159">LIMITES: '.$datos['eco_1159'].'</td>
                                <td name="eco_1161">ECOESTRUCTURA: '.$datos['eco_1161'].'</td>
                                <td name="eco_1163">ECOGENICIDAD (NORMAL): '.$datos['eco_1163'].'</td>
                                <td name="eco_1165">ALTERADA: '.$datos['eco_1165'].'</td>
                                <td name="eco_1167">ESPESOR: '.$datos['eco_1167'].'</td>
                                <td name="eco_1169">ROTURA: '.$datos['eco_1169'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1171">TIPO DE ROTURA: '.$datos['eco_1171'].'</td>
                                <td name="eco_1172">MEDIDA ROTURA MM: '.$datos['eco_1172'].'</td>
                                <td colspan="2" name="eco_1173">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1173'].'</td>
                                <td name="eco_1175">EROSIONES (MM): '.$datos['eco_1175'].'</td>
                                <td name="eco_1176">PROLIFERACIÓN (MM): '.$datos['eco_1176'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>BURSITIS ANSERINA</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_1177">BURSITIS ANSERINA: '.$datos['eco_1177'].'</td>
                        <td name="eco_1179">HIPERTROFIA SINOVIAL: '.$datos['eco_1179'].'</td>
                        <td name="eco_1181">ECOS INTERNOS: '.$datos['eco_1181'].'</td>
                        <td name="eco_1182">SEÑAL DOPPLER: '.$datos['eco_1182'].'</td>
                    </tr>


                  </table>  
                  ';
                }
                else{
                    $ecoIntraAntDer='';
                }
                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA CINFRAPATELAR IZQUIERDO 

                if(
                    $datos['eco_1183']!= '' || $datos['eco_1185']!= '' 
                    || $datos['eco_1187']!= ''  || $datos['eco_1189']!= ''
                    || $datos['eco_1190']!= ''  || $datos['eco_1192']!= ''
                    || $datos['eco_1194']!= ''  || $datos['eco_1195']!= ''
                    || $datos['eco_1196']!= ''  || $datos['eco_1197']!= ''
                    || $datos['eco_1198']!= ''  || $datos['eco_1199']!= ''
                    || $datos['eco_1200']!= ''  || $datos['eco_1202']!= ''
                    || $datos['eco_1204']!= ''  || $datos['eco_1205']!= ''
                    || $datos['eco_1208']!= ''   || $datos['eco_1209']!= ''
                    || $datos['eco_1210']!= ''  || $datos['eco_1212']!= ''
                    || $datos['eco_1213']!= ''  || $datos['eco_1216']!= ''
                    || $datos['eco_1217']!= ''  || $datos['eco_1218']!= ''
                    || $datos['eco_1220']!= ''  || $datos['eco_1221']!= ''
                    || $datos['eco_1223']!= ''  || $datos['eco_1224']!= ''
                    || $datos['eco_1226']!= ''  || $datos['eco_1227']!= ''
                    || $datos['eco_1229']!= ''  || $datos['eco_1230']!= ''
                    || $datos['eco_1232']!= ''  || $datos['eco_1233']!= ''
                    || $datos['eco_1235']!= ''  || $datos['eco_1236']!= ''
                    || $datos['eco_1238']!= ''  || $datos['eco_1240']!= ''
                    || $datos['eco_1241']!= '' || $datos['eco_1243']!= ''
                    || $datos['eco_1245']!= '' || $datos['eco_1247']!= ''
                    || $datos['eco_1249']!= '' || $datos['eco_1251']!= ''
                    || $datos['eco_1253']!= '' || $datos['eco_1255']!= ''
                    || $datos['eco_1257']!= '' || $datos['eco_1258']!= ''
                    || $datos['eco_1259']!= '' || $datos['eco_1261']!= ''
                    || $datos['eco_1262']!= '' || $datos['eco_1264']!= ''
                    || $datos['eco_1254']!= ''
                ){
                  $ecoIntraAntIzq= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>INFRAPATELAR IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN ROTULIANO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1183">LIMITES: '.$datos['eco_1183'].'</td>
                            <td name="eco_1185">ECOESTRUCTURA: '.$datos['eco_1185'].'</td>
                            <td colspan="2" name="eco_1187">ECOGENICIDAD (NORMAL): '.$datos['eco_1187'].'</td>
                            <td colspan="2" name="eco_1189">ECOGENICIDAD ALTERADA: '.$datos['eco_1189'].'</td>

                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1190">ESPESOR: '.$datos['eco_1190'].'</td>
                            <td name="eco_1192">ROTURA: '.$datos['eco_1192'].'</td>
                            <td name="eco_1194">TIPO DE ROTURA: '.$datos['eco_1194'].'</td>
                         </tr>
                         <tr style="font-size: 40%; line-height: 9px">
                         <td style="text-align: center;padding: 5px;" colspan="7">
                             <span><strong>ALTERACIONES EN</strong></span>
                                </td>
                        </tr>  
                        <tr style="font-size: 50%;"> 
                            <td  name="eco_1196">ENTESIS: '.$datos['eco_1196'].'</td>
                            <td name="eco_1197">ROTULIANA; '.$datos['eco_1197'].'</td>
                            <td name="eco_1198">TIBIAL: '.$datos['eco_1198'].'</td>
                            <td name="eco_1199">NO ENTESIS: '.$datos['eco_1199'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1200">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1200'].'</td>
                        <td name="eco_1202">ROTULIANA: '.$datos['eco_1202'].'</td>
                        <td name="eco_1204">EROSIONES (MM): '.$datos['eco_1204'].'</td>
                        <td name="eco_1205">PROLIFERACIÓN (MM): '.$datos['eco_1205'].'</td>
                        <td name="eco_1206">TIBIAL: '.$datos['eco_1206'].'</td>
                        <td name="eco_1208">EROSIONES (MM): '.$datos['eco_1208'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="2" name="eco_1209">PROLIFERACIÓN (MM): '.$datos['eco_1209'].'</td>
                        <td name="eco_1210">SEÑAL PD EN ENTESIS: '.$datos['eco_1210'].'</td>
                        <td name="eco_1212">GRADO SEÑAL PD: '.$datos['eco_1212'].'</td>
                        <td colspan="2" name="eco_1213">TIPO SEÑAL PD: '.$datos['eco_1213'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="2">MEDIDA ROTURA: </td>
                        <td name="eco_1216">TRANSVERSAL (MM): '.$datos['eco_1216'].'</td>
                        <td name="eco_1217">LONGITUDINAL (MM): '.$datos['eco_1217'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSITIS PRERROTULIANA</strong></span>
                            </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1218">BURSITIS PRERROTULIANA: '.$datos['eco_1218'].'</td>
                        <td name="eco_1220">HIPERTROFIA SINOVIAL: '.$datos['eco_1220'].'</td>
                        <td name="eco_1221">ECOS INTERNOS: '.$datos['eco_1221'].'</td>
                        <td name="eco_1223">SEÑAL DOPPLER: '.$datos['eco_1223'].'</td>
                       </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSITIS INFRARROTULIANA</strong></span>
                            </td>
                        </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1224">BURSITIS INFRARROTULIANA: '.$datos['eco_1224'].'</td>
                                <td name="eco_1226">HIPERTROFIA SINOVIAL: '.$datos['eco_1226'].'</td>
                                <td name="eco_1227">ECOS INTERNOS: '.$datos['eco_1227'].'</td>
                                <td name="eco_1229">SEÑAL DOPPLER: '.$datos['eco_1229'].'</td>
                            </tr>

                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>BURSITIS INFRARROTULIANA PROFUNDA</strong></span>
                            </td>
                        </tr>
                            <tr style="font-size: 50%;">
                                <td colspan="2" name="eco_1230">BURSITIS INFRARROTULIANA PROFUNDA: '.$datos['eco_1224'].'</td>
                                <td name="eco_1232">HIPERTROFIA SINOVIAL: '.$datos['eco_1232'].'</td>
                                <td name="eco_1233">ECOS INTERNOS: '.$datos['eco_1233'].'</td>
                                <td name="eco_1235">SEÑAL DOPPLER: '.$datos['eco_1235'].'</td>
                            </tr>

                            <tr style="font-size: 40%; line-height: 9px">
                            <td style="text-align: center;padding: 5px;" colspan="7">
                                <span><strong>CARTILAGO ARTICULAR</strong></span>
                            </td>
                             </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1236">ECOESTRUCTURA: '.$datos['eco_1236'].'</td>
                                <td name="eco_1238">LIMITES: '.$datos['eco_1238'].'</td>
                                <td colspan="2" name="eco_1240">GROSOR MM: '.$datos['eco_1240'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>INSERCION ANERNINA</strong></span>
                        </td>
                    </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1241">LIMITES: '.$datos['eco_1241'].'</td>
                                <td name="eco_1243">ECOESTRUCTURA: '.$datos['eco_1243'].'</td>
                                <td name="eco_1245">ECOGENICIDAD (NORMAL): '.$datos['eco_1245'].'</td>
                                <td name="eco_1247">ALTERADA: '.$datos['eco_1247'].'</td>
                                <td name="eco_1249">ESPESOR: '.$datos['eco_1249'].'</td>
                                <td name="eco_1251">ROTURA: '.$datos['eco_1251'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                                <td name="eco_1253">TIPO DE ROTURA: '.$datos['eco_1253'].'</td>
                                <td name="eco_1254">MEDIDA ROTURA MM: '.$datos['eco_1254'].'</td>
                                <td colspan="2" name="eco_1255">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1255'].'</td>
                                <td name="eco_1257">EROSIONES (MM): '.$datos['eco_1175'].'</td>
                                <td name="eco_1258">PROLIFERACIÓN (MM): '.$datos['eco_1258'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>BURSITIS ANSERINA</strong></span>
                        </td>
                    </tr>
                    <tr style="font-size: 50%;">
                        <td name="eco_1259">BURSITIS ANSERINA: '.$datos['eco_1259'].'</td>
                        <td name="eco_1261">HIPERTROFIA SINOVIAL: '.$datos['eco_1261'].'</td>
                        <td name="eco_1262">ECOS INTERNOS: '.$datos['eco_1262'].'</td>
                        <td name="eco_1264">SEÑAL DOPPLER: '.$datos['eco_1264'].'</td>
                    </tr>


                  </table>  
                  ';
                }
                else{
                    $ecoIntraAntIzq='';
                }

                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPORTAMIENTO MEDIAL - derecho

                if(
                    $datos['eco_1265']!= '' || $datos['eco_1267']!= '' 
                    || $datos['eco_1268']!= ''  || $datos['eco_1269']!= ''
                    || $datos['eco_1271']!= ''  || $datos['eco_1273']!= ''
                    || $datos['eco_1274']!= ''  || $datos['eco_1275']!= ''
                    || $datos['eco_1277']!= ''  || $datos['eco_1279']!= ''
                    || $datos['eco_1281']!= ''  || $datos['eco_1283']!= ''
                    || $datos['eco_1285']!= ''  || $datos['eco_1287']!= '' 
                ){
                  $ecoCompDer= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPORTAMIENTO MEDIAL - DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>BORDES ÓSEOS</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1265">IRREGULARIDADES: '.$datos['eco_1265'].'</td>
                        <td name="eco_1267">EROSIONES (MM): '.$datos['eco_1267'].'</td>
                        <td name="eco_1268">PROLIFERACIÓN (MM): '.$datos['eco_1268'].'</td>

                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>LIGAMENTO COLATERAL INTERNO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1269">ECOESTRUCTURA: '.$datos['eco_1269'].'</td>
                        <td name="eco_1271">ROTURA: '.$datos['eco_1271'].'</td>
                        <td name="eco_1273">TIPO ROTURA: '.$datos['eco_1273'].'</td>
                        <td name="eco_1274">MEDIDA ROTURA (MM): '.$datos['eco_1274'].'</td>
                        <td colspan="2" name="eco_1275">ABOMBAMIENTO: '.$datos['eco_1275'].'</td> 
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>CUERNO ANTERIOR MENISCO INTERNO (PORCIÓN PERIFÉRICA)</strong></span>
                        </td>
                </tr>

                <tr style="font-size: 50%;">
                        <td name="eco_1277">ECOGENICIDAD (NORMAL): '.$datos['eco_1277'].'</td>
                        <td name="eco_1279">ALTERADA: '.$datos['eco_1279'].'</td>
                        <td name="eco_1281">ECOESTRUCTURA: '.$datos['eco_1281'].'</td>
                        <td name="eco_1283">DEFECTOS HIPOECÓICOS: '.$datos['eco_1283'].'</td>
                        <td name="eco_1285">QUISTE MENISCAL: '.$datos['eco_1285'].'</td>
                        <td name="eco_1287">PROTRUSIÓN: '.$datos['eco_1287'].'</td>
                </tr>


                  </table>  
                  ';
                }
                else{
                    $ecoCompDer='';
                }

                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPORTAMIENTO MEDIAL - IZQUIERDO

                if(
                    $datos['eco_1289']!= '' || $datos['eco_1291']!= '' 
                    || $datos['eco_1292']!= ''  || $datos['eco_1293']!= ''
                    || $datos['eco_1295']!= ''  || $datos['eco_1297']!= ''
                    || $datos['eco_1299']!= ''  || $datos['eco_1301']!= ''
                    || $datos['eco_1303']!= ''  || $datos['eco_1305']!= ''
                    || $datos['eco_1307']!= ''  || $datos['eco_1309']!= ''
                    || $datos['eco_1311']!= ''  || $datos['eco_1298']!= '' 
                ){
                  $ecoCompIzq= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPORTAMIENTO MEDIAL - IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>BORDES ÓSEOS</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1289">IRREGULARIDADES: '.$datos['eco_1289'].'</td>
                        <td name="eco_1291">EROSIONES (MM): '.$datos['eco_1291'].'</td>
                        <td name="eco_1292">PROLIFERACIÓN (MM): '.$datos['eco_1292'].'</td>

                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>LIGAMENTO COLATERAL INTERNO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1293">ECOESTRUCTURA: '.$datos['eco_1293'].'</td>
                        <td name="eco_1295">ROTURA: '.$datos['eco_1295'].'</td>
                        <td name="eco_1297">TIPO ROTURA: '.$datos['eco_1297'].'</td>
                        <td name="eco_1298">MEDIDA ROTURA (MM): '.$datos['eco_1298'].'</td>
                        <td colspan="2" name="eco_1299">ABOMBAMIENTO: '.$datos['eco_1299'].'</td> 
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>CUERNO ANTERIOR MENISCO INTERNO (PORCIÓN PERIFÉRICA)</strong></span>
                        </td>
                </tr>

                <tr style="font-size: 50%;">
                        <td name="eco_1301">ECOGENICIDAD (NORMAL): '.$datos['eco_1301'].'</td>
                        <td name="eco_1303">ALTERADA: '.$datos['eco_1303'].'</td>
                        <td name="eco_1305">ECOESTRUCTURA: '.$datos['eco_1305'].'</td>
                        <td name="eco_1307">DEFECTOS HIPOECÓICOS: '.$datos['eco_1307'].'</td>
                        <td name="eco_1309">QUISTE MENISCAL: '.$datos['eco_1309'].'</td>
                        <td name="eco_1311">PROTRUSIÓN: '.$datos['eco_1311'].'</td>
                </tr>


                  </table>  
                  ';
                }
                else{
                    $ecoCompIzq='';
                }

                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPORTAMIENTO LATERAL - DERECHO

                if(
                    $datos['eco_1313']!= '' || $datos['eco_1315']!= '' 
                    || $datos['eco_1316']!= ''  || $datos['eco_1317']!= ''
                    || $datos['eco_1319']!= ''  || $datos['eco_1321']!= ''
                    || $datos['eco_1322']!= ''  || $datos['eco_1323']!= ''
                    || $datos['eco_1325']!= ''  || $datos['eco_1327']!= ''
                    || $datos['eco_1329']!= ''  || $datos['eco_1331']!= ''
                    || $datos['eco_1333']!= ''  || $datos['eco_1335']!= '' 
                    || $datos['eco_1339']!= ''  || $datos['eco_1341']!= '' 
                    || $datos['eco_1343']!= ''  || $datos['eco_1345']!= ''
                    || $datos['eco_1346']!= ''  || $datos['eco_1347']!= '' 
                    || $datos['eco_1349']!= '' || $datos['eco_1351']!= '' 
                    || $datos['eco_1353']!= '' || $datos['eco_1355']!= '' 
                    || $datos['eco_1357']!= '' || $datos['eco_1359']!= '' 
                    || $datos['eco_1361']!= '' || $datos['eco_1362']!= '' 
                    || $datos['eco_1363']!= '' || $datos['eco_1365']!= '' 
                    || $datos['eco_1366']!= '' || $datos['eco_1367']!= '' 
                    || $datos['eco_1369']!= '' || $datos['eco_1370']!= '' 
                    || $datos['eco_1372']!= ''  || $datos['eco_1374']!= '' 
                ){
                  $ecoCompLDer= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPORTAMIENTO LATERAL - DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>BORDES ÓSEOS</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1313">IRREGULARIDADES: '.$datos['eco_1313'].'</td>
                        <td name="eco_1315">EROSIONES (MM): '.$datos['eco_1315'].'</td>
                        <td name="eco_1316">PROLIFERACIÓN (MM): '.$datos['eco_1316'].'</td>

                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>LIGAMENTO COLATERAL INTERNO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1317">ECOESTRUCTURA: '.$datos['eco_1317'].'</td>
                        <td name="eco_1319">ROTURA: '.$datos['eco_1319'].'</td>
                        <td name="eco_1321">TIPO ROTURA: '.$datos['eco_1321'].'</td>
                        <td name="eco_1322">MEDIDA ROTURA (MM): '.$datos['eco_1322'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>CUERNO ANTERIOR MENISCO INTERNO (PORCIÓN PERIFÉRICA)</strong></span>
                        </td>
                </tr>

                <tr style="font-size: 50%;">
                        <td name="eco_1323">ECOGENICIDAD (NORMAL): '.$datos['eco_1323'].'</td>
                        <td name="eco_1325">ALTERADA: '.$datos['eco_1325'].'</td>
                        <td name="eco_1327">ECOESTRUCTURA: '.$datos['eco_1327'].'</td>
                        <td name="eco_1329">DEFECTOS HIPOECÓICOS: '.$datos['eco_1329'].'</td>
                        <td name="eco_1331">QUISTE MENISCAL: '.$datos['eco_1331'].'</td>
                        <td name="eco_1333">PROTRUSIÓN: '.$datos['eco_1333'].'</td>
                </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>CINTILLA ILOTIBIAL</strong></span>
                                </td>
                            </tr>
                            <tr style="font-size: 50%;">
                                      <td name="eco_1335">LIMITES: '.$datos['eco_1335'].'</td>
                                        <td name="eco_1337">ECOESTRUCTURA: '.$datos['eco_1337'].'</td>
                                        <td name="eco_1339">ECOGENICIDAD (NORMAL): '.$datos['eco_1339'].'</td>
                                        <td name="eco_1341">ALTERADA: '.$datos['eco_1341'].'</td>
                                        <td name="eco_1343">ROTURA: '.$datos['eco_1343'].'</td>
                                        <td name="eco_1345">TIPO ROTURA:'.$datos['eco_1345'].'</td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td colspan="2" name="eco_1346">MEDIDA ROTURA (MM): '.$datos['eco_1346'].'</td>
                                <td name="eco_1347">BURSITIS: '.$datos['eco_1347'].'</td>
                            </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN BICIPITAL</strong></span>
                                </td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1349">LIMITES: '.$datos['eco_1349'].'</td>
                                <td name="eco_1351">ECOESTRUCTURA: '.$datos['eco_1351'].'</td>
                                <td name="eco_1353">ECOGENICIDAD (NORMAL): '.$datos['eco_1353'].'</td>
                                <td name="eco_1355">ALTERADA: '.$datos['eco_1355'].'</td>
                                <td name="eco_1357">ESPESOR: '.$datos['eco_1357'].'</td>
                                <td name="eco_1359">ROTURA: '.$datos['eco_1359'].'</td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1361">ROTURA: '.$datos['eco_1361'].'</td>
                                <td name="eco_1362">MEDIDA ROTURA (MM): '.$datos['eco_1362'].'</td>
                                <td name="eco_1363">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1363'].'</td>
                                <td name="eco_1365">EROSIONES (MM): '.$datos['eco_1365'].'</td>
                                <td name="eco_1366">PROLIFERACIÓN (MM): '.$datos['eco_1366'].'</td>
                                <td name="eco_1367">SEÑAL PD EN ENTESIS: '.$datos['eco_1367'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                             <td colspan="3" name="eco_1369">GRADO SEÑAL PD: '.$datos['eco_1369'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>MÚSCULOS BICEPS FEMORAL</strong></span>
                        </td>
                    </tr>

                    <tr style="font-size: 50%;">
                            <td name="eco_1370">MÚSCULOS BICEPS FEMORAL: '.$datos['eco_1370'].'</td>
                            <td name="eco_1372">ECOESTRUCTURA/ECOGENICIDAD: '.$datos['eco_1372'].'</td>
                            <td name="eco_1374">MEDIDA ROTURA (MM): '.$datos['eco_1374'].'</td>
                </tr>

                  </table>  
                  ';
                }
                else{
                    $ecoCompLDer='';
                }
                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPORTAMIENTO LATERAL - IZQUIERDO

                if(
                    $datos['eco_1375']!= '' || $datos['eco_1377']!= '' 
                    || $datos['eco_1379']!= ''  || $datos['eco_1378']!= ''
                    || $datos['eco_1381']!= ''  || $datos['eco_1383']!= ''
                    || $datos['eco_1384']!= ''  || $datos['eco_1385']!= ''
                    || $datos['eco_1387']!= ''  || $datos['eco_1389']!= ''
                    || $datos['eco_1391']!= ''  || $datos['eco_1393']!= ''
                    || $datos['eco_1395']!= ''  || $datos['eco_1397']!= '' 
                    || $datos['eco_1399']!= ''  || $datos['eco_1401']!= '' 
                    || $datos['eco_1403']!= ''  || $datos['eco_1405']!= ''
                    || $datos['eco_1407']!= ''  || $datos['eco_1408']!= '' 
                    || $datos['eco_1409']!= '' || $datos['eco_1411']!= '' 
                    || $datos['eco_1413']!= '' || $datos['eco_1415']!= '' 
                    || $datos['eco_1417']!= '' || $datos['eco_1419']!= '' 
                    || $datos['eco_1421']!= '' || $datos['eco_1423']!= '' 
                    || $datos['eco_1425']!= '' || $datos['eco_1427']!= '' 
                    || $datos['eco_1428']!= '' || $datos['eco_1429']!= '' 
                    || $datos['eco_1431']!= '' || $datos['eco_1432']!= '' 
                    || $datos['eco_1434']!= ''  || $datos['eco_1436']!= '' 
                ){
                  $ecoCompLIzq= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPORTAMIENTO LATERAL - IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>BORDES ÓSEOS</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1375">IRREGULARIDADES: '.$datos['eco_1375'].'</td>
                        <td name="eco_1377">EROSIONES (MM): '.$datos['eco_1377'].'</td>
                        <td name="eco_1378">PROLIFERACIÓN (MM): '.$datos['eco_1378'].'</td>

                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>LIGAMENTO COLATERAL INTERNO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1379">ECOESTRUCTURA: '.$datos['eco_1379'].'</td>
                        <td name="eco_1381">ROTURA: '.$datos['eco_1381'].'</td>
                        <td name="eco_1383">TIPO ROTURA: '.$datos['eco_1383'].'</td>
                        <td name="eco_1384">MEDIDA ROTURA (MM): '.$datos['eco_1384'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>CUERNO ANTERIOR MENISCO INTERNO (PORCIÓN PERIFÉRICA)</strong></span>
                        </td>
                </tr>

                <tr style="font-size: 50%;">
                        <td name="eco_1385">ECOGENICIDAD (NORMAL): '.$datos['eco_1385'].'</td>
                        <td name="eco_1387">ALTERADA: '.$datos['eco_1387'].'</td>
                        <td name="eco_1389">ECOESTRUCTURA: '.$datos['eco_1389'].'</td>
                        <td name="eco_1391">DEFECTOS HIPOECÓICOS: '.$datos['eco_1391'].'</td>
                        <td name="eco_1393">QUISTE MENISCAL: '.$datos['eco_1393'].'</td>
                        <td name="eco_1395">PROTRUSIÓN: '.$datos['eco_1395'].'</td>
                </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>CINTILLA ILOTIBIAL</strong></span>
                                </td>
                            </tr>
                            <tr style="font-size: 50%;">
                                      <td name="eco_1397">LIMITES: '.$datos['eco_1397'].'</td>
                                        <td name="eco_1399">ECOESTRUCTURA: '.$datos['eco_1399'].'</td>
                                        <td name="eco_1401">ECOGENICIDAD (NORMAL): '.$datos['eco_1401'].'</td>
                                        <td name="eco_1403">ALTERADA: '.$datos['eco_1403'].'</td>
                                        <td name="eco_1405">ROTURA: '.$datos['eco_1405'].'</td>
                                        <td name="eco_1407">TIPO ROTURA:'.$datos['eco_1407'].'</td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td colspan="2" name="eco_1408">MEDIDA ROTURA (MM): '.$datos['eco_1408'].'</td>
                                <td name="eco_1409">BURSITIS: '.$datos['eco_1409'].'</td>
                            </tr>
                            <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN BICIPITAL</strong></span>
                                </td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1411">LIMITES: '.$datos['eco_1411'].'</td>
                                <td name="eco_1413">ECOESTRUCTURA: '.$datos['eco_1413'].'</td>
                                <td name="eco_1415">ECOGENICIDAD (NORMAL): '.$datos['eco_1415'].'</td>
                                <td name="eco_1417">ALTERADA: '.$datos['eco_1417'].'</td>
                                <td name="eco_1419">ESPESOR: '.$datos['eco_1419'].'</td>
                                <td name="eco_1421">ROTURA: '.$datos['eco_1421'].'</td>
                            </tr>
                            <tr style="font-size: 50%;">
                                <td name="eco_1423">ROTURA: '.$datos['eco_1423'].'</td>
                                <td name="eco_1424">MEDIDA ROTURA (MM): '.$datos['eco_1424'].'</td>
                                <td name="eco_1425">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1425'].'</td>
                                <td name="eco_1427">EROSIONES (MM): '.$datos['eco_1427'].'</td>
                                <td name="eco_1428">PROLIFERACIÓN (MM): '.$datos['eco_1428'].'</td>
                                <td name="eco_1429">SEÑAL PD EN ENTESIS: '.$datos['eco_1429'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                             <td colspan="3" name="eco_1431">GRADO SEÑAL PD: '.$datos['eco_1431'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>MÚSCULOS BICEPS FEMORAL</strong></span>
                        </td>
                    </tr>

                    <tr style="font-size: 50%;">
                            <td name="eco_1432">MÚSCULOS BICEPS FEMORAL: '.$datos['eco_1432'].'</td>
                            <td name="eco_1434">ECOESTRUCTURA/ECOGENICIDAD: '.$datos['eco_1434'].'</td>
                            <td name="eco_1436">MEDIDA ROTURA (MM): '.$datos['eco_1436'].'</td>
                </tr>

                  </table>  
                  ';
                }
                else{
                    $ecoCompLIzq='';
                }
                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPORTAMIENTO POSTERIOR - DERECHO

                if(
                    $datos['eco_1437']!= '' || $datos['eco_1440']!= '' 
                    || $datos['eco_1441']!= ''  || $datos['eco_1442']!= ''
                    || $datos['eco_1444']!= ''  || $datos['eco_1445']!= ''
                    || $datos['eco_1446']!= ''  || $datos['eco_1447']!= ''
                    || $datos['eco_1449']!= ''  || $datos['eco_1451']!= ''
                    || $datos['eco_1452']!= ''  || $datos['eco_1453']!= ''
                    || $datos['eco_1457']!= ''  || $datos['eco_1459']!= '' 
                    || $datos['eco_1463']!= '' || $datos['ecoes1455r_1']!= ''
                    || $datos['eco1461r_2']!= ''  
                ){
                  $ecoCompPDer= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPORTAMIENTO POSTERIOR - DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>QUISTE DE BAKER</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1437">QUISTE DE BAKER: '.$datos['eco_1437'].'</td>
                        <td colspan="2" >TAMAÑO:</td>
                        <td name="eco_1440">LONGITUDINAL (MM): '.$datos['eco_1440'].'</td>
                        <td name="eco_1441">TRANSVERSAL (MM): '.$datos['eco_1441'].'</td>
                        </tr>


                        <tr style="font-size: 50%;">
                            <td name="eco_1442">INTEGRO: '.$datos['eco_1442'].'</td>
                            <td name="eco_1444">HIPERTROFIA SINOVIAL: '.$datos['eco_1444'].'</td>
                            <td colspan="2" name="eco_1445">ECOS INTERNOS: '.$datos['eco_1445'].'</td>
                            <td colspan="2" name="eco_1446">SEÑAL DOPPLER: '.$datos['eco_1446'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>LIGAMENTO CRUZADO POSTERIOR</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1447">ECOESTRUCTURA: '.$datos['eco_1447'].'</td>
                            <td name="eco_1449">ROTURA: '.$datos['eco_1449'].'</td>
                            <td name="eco_1451">TIPO ROTURA: '.$datos['eco_1451'].'</td>
                            <td name="eco_1452">MEDIDA ROTURA MM: '.$datos['eco_1452'].'</td>
                         </tr>

                         <tr style="font-size: 40%; line-height: 9px">
                         <td style="text-align: center;padding: 5px;" colspan="7">
                             <span><strong>CUERPO POSTERIOR MENISCO EXTERNO</strong></span>
                         </td>
                         </tr>
                         <tr style="font-size: 50%;">
                            <td name="eco_1453">ECOGENICIDAD (NORMAL): '.$datos['eco_1453'].'</td>
                            <td name="ecoes1455r_1">ALTERADA: '.$datos['ecoes1455r_1'].'</td>
                            <td colspan="2" name="eco_1457">DEFECTOS HIPOECÓICOS: '.$datos['eco_1457'].'</td>
                          </tr>

                          <tr style="font-size: 40%; line-height: 9px">
                          <td style="text-align: center;padding: 5px;" colspan="7">
                              <span><strong>CUERPO POSTERIOR MENISCO INTERNO</strong></span>
                          </td>
                          </tr>
                          <tr style="font-size: 50%;">
                                <td name="eco_1459">ECOGENICIDAD (NORMAL): '.$datos['eco_1459'].'</td>
                                <td name="eco1461r_2">ALTERADA: '.$datos['eco1461r_2'].'</td>
                                <td colspan="2" name="eco_1463">DEFECTOS HIPOECÓICOS: '.$datos['eco_1463'].'</td>
                           </tr>

                  </table>  
                  ';
                }
                else{
                    $ecoCompPDer='';
                }
                // ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA COMPORTAMIENTO POSTERIOR - IZQUIERDO

                if(
                    $datos['eco_1465']!= '' || $datos['eco_1468']!= '' 
                    || $datos['eco_1469']!= ''  || $datos['eco_1470']!= ''
                    || $datos['eco_1472']!= ''  || $datos['eco_1473']!= ''
                    || $datos['eco_1474']!= ''  || $datos['eco_1475']!= ''
                    || $datos['eco_1477']!= ''  || $datos['eco_1479']!= ''
                    || $datos['eco_1480']!= ''  || $datos['eco_1481']!= ''
                    || $datos['eco_1485']!= ''  || $datos['eco_1487']!= '' 
                    || $datos['eco_1491']!= '' || $datos['eco1483er_2']!= ''
                    || $datos['eco_1491']!= ''  
                ){
                  $ecoCompPIzq= '
                  <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>COMPORTAMIENTO POSTERIOR - IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>QUISTE DE BAKER</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1465">QUISTE DE BAKER: '.$datos['eco_1465'].'</td>
                        <td colspan="2" >TAMAÑO:</td>
                        <td name="eco_1468">LONGITUDINAL (MM): '.$datos['eco_1468'].'</td>
                        <td name="eco_1469">TRANSVERSAL (MM): '.$datos['eco_1469'].'</td>
                        </tr>


                        <tr style="font-size: 50%;">
                            <td name="eco_1470">INTEGRO: '.$datos['eco_1470'].'</td>
                            <td name="eco_1472">HIPERTROFIA SINOVIAL: '.$datos['eco_1472'].'</td>
                            <td colspan="2" name="eco_1473">ECOS INTERNOS: '.$datos['eco_1473'].'</td>
                            <td colspan="2" name="eco_1474">SEÑAL DOPPLER: '.$datos['eco_1474'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>LIGAMENTO CRUZADO POSTERIOR</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                            <td name="eco_1475">ECOESTRUCTURA: '.$datos['eco_1475'].'</td>
                            <td name="eco_1477">ROTURA: '.$datos['eco_1477'].'</td>
                            <td name="eco_1479">TIPO ROTURA: '.$datos['eco_1479'].'</td>
                            <td name="eco_1480">MEDIDA ROTURA MM: '.$datos['eco_1480'].'</td>
                         </tr>

                         <tr style="font-size: 40%; line-height: 9px">
                         <td style="text-align: center;padding: 5px;" colspan="7">
                             <span><strong>CUERPO POSTERIOR MENISCO EXTERNO</strong></span>
                         </td>
                         </tr>
                         <tr style="font-size: 50%;">
                            <td name="eco_1481">ECOGENICIDAD (NORMAL): '.$datos['eco_1481'].'</td>
                            <td name="eco1483er_2">ALTERADA: '.$datos['eco1483er_2'].'</td>
                            <td colspan="2" name="eco_1485">DEFECTOS HIPOECÓICOS: '.$datos['eco_1485'].'</td>
                          </tr>

                          <tr style="font-size: 40%; line-height: 9px">
                          <td style="text-align: center;padding: 5px;" colspan="7">
                              <span><strong>CUERPO POSTERIOR MENISCO INTERNO</strong></span>
                          </td>
                          </tr>
                          <tr style="font-size: 50%;">
                                <td name="eco_1487">ECOGENICIDAD (NORMAL): '.$datos['eco_1487'].'</td>
                                <td name="eco1489er_2">ALTERADA: '.$datos['eco1489er_2'].'</td>
                                <td colspan="2" name="eco_1463">DEFECTOS HIPOECÓICOS: '.$datos['eco_1463'].'</td>
                           </tr>

                  </table>  
                  ';
                }
                else{
                    $ecoCompPIzq='';
                }
                // construccion del bloque de ecografia de rodilla
                if (empty($ecoSupratelarAntDer) &&  empty($ecoSupratelarAntIzq) 
                &&  empty($ecoIntraAntDer) &&  empty($ecoIntraAntIzq) 
                &&  empty($ecoCompDer)  &&  empty($ecoCompIzq) 
                &&  empty($ecoCompLDer)  &&  empty($ecoCompLIzq) 
                &&  empty($ecoCompPDer)  &&  empty($ecoCompPIzq) 
                ){
                    $informDataHistory.='';

                }
                else{
                    $informDataHistory.=  
                    '<table style="padding: 2px; width: 100%">
                    <tr style="font-size: 10%">
                        <td style="text-align: center;" >

                        </td>
                    </tr>
                    <tr style="font-size: 70%">
                        <td style="text-align: center;" >
                            <br><strong> ECOGRAFÍA DE ALTA RESOLUCIÓN DE RODILLA</strong>
                        </td>
                    </tr>

                </table>
                ';
                }
                if(!empty($ecoSupratelarAntDer) ){

                    $informDataHistory.= '
                    '.$ecoSupratelarAntDer.'
                             ';
                };
                if(!empty($ecoSupratelarAntIzq) ){

                    $informDataHistory.= '
                    '.$ecoSupratelarAntIzq.'
                             ';
                };
                if(!empty($ecoIntraAntDer) ){

                    $informDataHistory.= '
                    '.$ecoIntraAntDer.'
                             ';
                };
                if(!empty($ecoIntraAntIzq) ){

                    $informDataHistory.= '
                    '.$ecoIntraAntIzq.'
                             ';
                };
                if(!empty($ecoCompDer) ){

                    $informDataHistory.= '
                    '.$ecoCompDer.'
                             ';
                };
                if(!empty($ecoCompIzq) ){

                    $informDataHistory.= '
                    '.$ecoCompIzq.'
                             ';
                };
                if(!empty($ecoCompLDer) ){

                    $informDataHistory.= '
                    '.$ecoCompLDer.'
                             ';
                };
                if(!empty($ecoCompLIzq) ){

                    $informDataHistory.= '
                    '.$ecoCompLIzq.'
                             ';
                };
                if(!empty($ecoCompPDer) ){

                    $informDataHistory.= '
                    '.$ecoCompPDer.'
                             ';
                };
                if(!empty($ecoCompPIzq) ){

                    $informDataHistory.= '
                    '.$ecoCompPIzq.'
                             ';
                };

                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN ANTERIOR - TOBILLO DERECHO
                if(
                    $datos['eco_1493']!= '' || $datos['eco_1495']!= '' 
                    || $datos['eco_1496']!= ''  || $datos['eco_1498']!= '' 
                    || $datos['eco_1500']!= ''  || $datos['eco_1501']!= '' 
                    || $datos['eco_1503']!= ''  || $datos['eco_1504']!= '' 
                    || $datos['eco_1506']!= '' || $datos['eco_1508']!= ''  
                    || $datos['eco_1509']!= '' || $datos['eco_1511']!= '' 
                    || $datos['eco_1512']!= ''  || $datos['eco_1514']!= '' 
                    || $datos['eco_1516']!= ''  || $datos['eco_1517']!= '' 
                    || $datos['eco_1519']!= '' || $datos['eco_1521']!= '' 
                    || $datos['eco_1524']!= '' || $datos['eco_1525']!= '' 
                    || $datos['eco_1527']!= '' || $datos['eco_1529']!= '' 
                    || $datos['eco_1530']!= '' || $datos['eco_1532']!= '' 
                    || $datos['eco_1534']!= ''  || $datos['eco_1535']!= '' 
                    || $datos['eco_1537']!= '' || $datos['eco_1538']!= '' 
                    || $datos['eco_1540']!= ''  || $datos['eco_1542']!= '' 
                    || $datos['eco_1543']!= ''  || $datos['eco_1545']!= '' 
                    || $datos['eco_1547']!= ''  || $datos['eco_1548']!= '' 
                    || $datos['eco_1550']!= ''  || $datos['eco_1551']!= '' 
                    || $datos['eco_1553']!= ''  || $datos['eco_1555']!= '' 
                    || $datos['eco_1553']!= ''  || $datos['eco_1555']!= '' 
                    || $datos['eco_1556']!= ''  || $datos['eco_1560']!= '' 
                    || $datos['eco_1561']!= ''   || $datos['eco_1563']!= '' 
                    || $datos['eco_1564']!= ''  || $datos['eco_1566']!= '' 
                    || $datos['eco_1568']!= ''   || $datos['eco_1569']!= '' 
                    || $datos['eco_1571']!= ''  || $datos['eco_1573']!= '' 
                    || $datos['eco_1574']!= ''   || $datos['eco_1576']!= '' 
                    || $datos['eco_1577']!= ''  || $datos['eco_1579']!= '' 
                    || $datos['eco_1581']!= ''  || $datos['eco_1582']!= ''  
                    || $datos['eco_1584']!= ''   || $datos['eco_1586']!= '' 
                    || $datos['eco_1588']!= ''  || $datos['eco_1590']!= '' 
                    || $datos['eco_1591']!= ''   || $datos['eco_1593']!= '' 
                    || $datos['eco_1595']!= ''   || $datos['eco_1597']!= '' 
                    || $datos['eco_1598']!= ''  || $datos['eco_1600']!= ''  
                ){
                 $ecoExploAntTobiDer='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN ANTERIOR - TOBILLO DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>ARTICULACIÓN TIBIOASTRAGALINA</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1493">DERRAME/HS: '.$datos['eco_1493'].'</td>
                        <td name="eco_1495">SELECCIONE: '.$datos['eco_1495'].'</td>
                        <td name="eco_1496">SEÑAL PD: '.$datos['eco_1496'].'</td>
                        <td name="eco_1498">SELECCIONE: '.$datos['eco_1498'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1500">IRREGULARIDADES CORTICALES: '.$datos['eco_1500'].'</td>
                        <td name="eco_1501">EROSIONES: '.$datos['eco_1501'].'</td>
                        <td name="eco_1503">MM: '.$datos['eco_1503'].'</td>
                        <td name="eco_1504">PLORIFERACIÓN ÓSEA: '.$datos['eco_1504'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ARTICULACIÓN DEL TARSO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1506">DERRAME/HS: '.$datos['eco_1506'].'</td>
                        <td name="eco_1508">SELECCIONE: '.$datos['eco_1508'].'</td>
                        <td name="eco_1509">SEÑAL PD: '.$datos['eco_1509'].'</td>
                        <td name="eco_1511">SELECCIONE: '.$datos['eco_1511'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1512">IRREGULARIDADES CORTICALES: '.$datos['eco_1512'].'</td>
                        <td name="eco_1514">EROSIONES: '.$datos['eco_1514'].'</td>
                        <td name="eco_1516">MM: '.$datos['eco_1516'].'</td>
                        <td name="eco_1517">PLORIFERACIÓN ÓSEA: '.$datos['eco_1517'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>1MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1519">DERRAME/HS: '.$datos['eco_1519'].'</td>
                        <td name="eco_1521">SELECCIONE: '.$datos['eco_1521'].'</td>
                        <td name="eco_1522">SEÑAL PD: '.$datos['eco_1522'].'</td>
                        <td name="eco_1524">SELECCIONE: '.$datos['eco_1524'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1525">IRREGULARIDADES CORTICALES: '.$datos['eco_1525'].'</td>
                        <td name="eco_1527">EROSIONES: '.$datos['eco_1527'].'</td>
                        <td name="eco_1529">MM: '.$datos['eco_1529'].'</td>
                        <td name="eco_1530">PLORIFERACIÓN ÓSEA: '.$datos['eco_1530'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>2MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1532">DERRAME/HS: '.$datos['eco_1532'].'</td>
                        <td name="eco_1534">SELECCIONE: '.$datos['eco_1534'].'</td>
                        <td name="eco_1535">SEÑAL PD: '.$datos['eco_1535'].'</td>
                        <td name="eco_1537">SELECCIONE: '.$datos['eco_1537'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1538">IRREGULARIDADES CORTICALES: '.$datos['eco_1538'].'</td>
                        <td name="eco_1540">EROSIONES: '.$datos['eco_1540'].'</td>
                        <td name="eco_1542">MM: '.$datos['eco_1542'].'</td>
                        <td name="eco_1543">PLORIFERACIÓN ÓSEA: '.$datos['eco_1543'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>3MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1545">DERRAME/HS: '.$datos['eco_1545'].'</td>
                        <td name="eco_1547">SELECCIONE: '.$datos['eco_1547'].'</td>
                        <td name="eco_1548">SEÑAL PD: '.$datos['eco_1548'].'</td>
                        <td name="eco_1550">SELECCIONE: '.$datos['eco_1550'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1551">IRREGULARIDADES CORTICALES: '.$datos['eco_1551'].'</td>
                        <td name="eco_1553">EROSIONES: '.$datos['eco_1553'].'</td>
                        <td name="eco_1555">MM: '.$datos['eco_1555'].'</td>
                        <td name="eco_1556">PLORIFERACIÓN ÓSEA: '.$datos['eco_1556'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>4MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1558">DERRAME/HS: '.$datos['eco_1558'].'</td>
                        <td name="eco_1560">SELECCIONE: '.$datos['eco_1560'].'</td>
                        <td name="eco_1561">SEÑAL PD: '.$datos['eco_1561'].'</td>
                        <td name="eco_1563">SELECCIONE: '.$datos['eco_1563'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1564">IRREGULARIDADES CORTICALES: '.$datos['eco_1564'].'</td>
                        <td name="eco_1566">EROSIONES: '.$datos['eco_1566'].'</td>
                        <td name="eco_1568">MM: '.$datos['eco_1568'].'</td>
                        <td name="eco_1569">PLORIFERACIÓN ÓSEA: '.$datos['eco_1569'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>5MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1571">DERRAME/HS: '.$datos['eco_1571'].'</td>
                        <td name="eco_1573">SELECCIONE: '.$datos['eco_1573'].'</td>
                        <td name="eco_1574">SEÑAL PD: '.$datos['eco_1574'].'</td>
                        <td name="eco_1576">SELECCIONE: '.$datos['eco_1576'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1577">IRREGULARIDADES CORTICALES: '.$datos['eco_1577'].'</td>
                        <td name="eco_1579">EROSIONES: '.$datos['eco_1579'].'</td>
                        <td name="eco_1581">MM: '.$datos['eco_1581'].'</td>
                        <td name="eco_1582">PLORIFERACIÓN ÓSEA: '.$datos['eco_1582'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>TENDÓN TIBIAL, EXTENSOR LARGO DEL PRIMER DEDO Y EXTENSOR COMÚN DE LOS DEDOS</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1584">LIMITES: '.$datos['eco_1584'].'</td>
                        <td name="eco_1586">ECOESTRUCTURA: '.$datos['eco_1586'].'</td>
                        <td name="eco_1588">ECOGENICIDAD (NORMAL): '.$datos['eco_1588'].'</td>
                        <td name="eco_1590">ALTERADA: '.$datos['eco_1590'].'</td>
                        <td name="eco_1591">ESPESOR: '.$datos['eco_1591'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1593">ROTURA: '.$datos['eco_1593'].'</td>
                        <td name="eco_1595">TIPO: '.$datos['eco_1595'].'</td>
                        <td name="eco_1597">MM: '.$datos['eco_1597'].'</td>
                        <td colspan="2" name="eco_1598">AUMENTO DE LÍQUIDO EN VAINAS TENDINO: '.$datos['eco_1598'].'</td>
                        <td name="eco_1600">SEÑAL PD VAINA: '.$datos['eco_1600'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploAntTobiDer='';
                }
                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN ANTERIOR - TOBILLO IZQUIERDO
                if(
                    $datos['eco_1601']!= '' || $datos['eco_1603']!= '' 
                    || $datos['eco_1604']!= ''  || $datos['eco_1606']!= '' 
                    || $datos['eco_1607']!= ''  || $datos['eco_1609']!= '' 
                    || $datos['eco_1611']!= ''  || $datos['eco_1612']!= '' 
                    || $datos['eco_1614']!= '' || $datos['eco_1616']!= ''  
                    || $datos['eco_1617']!= '' || $datos['eco_1619']!= '' 
                    || $datos['eco_1620']!= ''  || $datos['eco_1622']!= '' 
                    || $datos['eco_1624']!= ''  || $datos['eco_1625']!= '' 
                    || $datos['eco_1627']!= '' || $datos['eco_1629']!= '' 
                    || $datos['eco_1630']!= '' || $datos['eco_1632']!= '' 
                    || $datos['eco_1633']!= '' || $datos['eco_1635']!= '' 
                    || $datos['eco_1637']!= '' || $datos['eco_1638']!= '' 
                    || $datos['eco_1640']!= ''  || $datos['eco_1642']!= '' 
                    || $datos['eco_1643']!= '' || $datos['eco_1645']!= '' 
                    || $datos['eco_1646']!= ''  || $datos['eco_1648']!= '' 
                    || $datos['eco_1650']!= ''  || $datos['eco_1651']!= '' 
                    || $datos['eco_1653']!= ''  || $datos['eco_1655']!= '' 
                    || $datos['eco_1656']!= ''  || $datos['eco_1658']!= '' 
                    || $datos['eco_1659']!= ''  || $datos['eco_1661']!= '' 
                    || $datos['eco_1663']!= ''  || $datos['eco_1664']!= '' 
                    || $datos['eco_1666']!= ''  || $datos['eco_1668']!= '' 
                    || $datos['eco_1669']!= ''   || $datos['eco_1671']!= '' 
                    || $datos['eco_1672']!= ''  || $datos['eco_1674']!= '' 
                    || $datos['eco_1676']!= ''   || $datos['eco_1677']!= '' 
                    || $datos['eco_1679']!= ''  || $datos['eco_1681']!= '' 
                    || $datos['eco_1682']!= ''   || $datos['eco_1684']!= '' 
                    || $datos['eco_1685']!= ''  || $datos['eco_1687']!= '' 
                    || $datos['eco_1689']!= ''  || $datos['eco_1690']!= ''  
                    || $datos['eco_1692']!= ''   || $datos['eco_1694']!= '' 
                    || $datos['eco_1696']!= ''  || $datos['eco_1698']!= '' 
                    || $datos['eco_1699']!= ''   || $datos['eco_1701']!= '' 
                    || $datos['eco_1703']!= ''   || $datos['eco_1705']!= '' 
                    || $datos['eco_1706']!= ''  || $datos['eco_1708']!= ''  
                ){
                 $ecoExploAntTobiIzq='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN ANTERIOR - TOBILLO IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>ARTICULACIÓN TIBIOASTRAGALINA</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1601">DERRAME/HS: '.$datos['eco_1601'].'</td>
                        <td name="eco_1603">SELECCIONE: '.$datos['eco_1603'].'</td>
                        <td name="eco_1604">SEÑAL PD: '.$datos['eco_1604'].'</td>
                        <td name="eco_1606">SELECCIONE: '.$datos['eco_1606'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1607">IRREGULARIDADES CORTICALES: '.$datos['eco_1607'].'</td>
                        <td name="eco_1609">EROSIONES: '.$datos['eco_1609'].'</td>
                        <td name="eco_1611">MM: '.$datos['eco_1611'].'</td>
                        <td name="eco_1612">PLORIFERACIÓN ÓSEA: '.$datos['eco_1612'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ARTICULACIÓN DEL TARSO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1614">DERRAME/HS: '.$datos['eco_1614'].'</td>
                        <td name="eco_1616">SELECCIONE: '.$datos['eco_1616'].'</td>
                        <td name="eco_1617">SEÑAL PD: '.$datos['eco_1617'].'</td>
                        <td name="eco_1619">SELECCIONE: '.$datos['eco_1619'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1620">IRREGULARIDADES CORTICALES: '.$datos['eco_1620'].'</td>
                        <td name="eco_1622">EROSIONES: '.$datos['eco_1622'].'</td>
                        <td name="eco_1624">MM: '.$datos['eco_1624'].'</td>
                        <td name="eco_1625">PLORIFERACIÓN ÓSEA: '.$datos['eco_1625'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>1MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1627">DERRAME/HS: '.$datos['eco_1627'].'</td>
                        <td name="eco_1629">SELECCIONE: '.$datos['eco_1629'].'</td>
                        <td name="eco_1630">SEÑAL PD: '.$datos['eco_1630'].'</td>
                        <td name="eco_1632">SELECCIONE: '.$datos['eco_1632'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1633">IRREGULARIDADES CORTICALES: '.$datos['eco_1633'].'</td>
                        <td name="eco_1635">EROSIONES: '.$datos['eco_1635'].'</td>
                        <td name="eco_1637">MM: '.$datos['eco_1637'].'</td>
                        <td name="eco_1638">PLORIFERACIÓN ÓSEA: '.$datos['eco_1638'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>2MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1640">DERRAME/HS: '.$datos['eco_1640'].'</td>
                        <td name="eco_1642">SELECCIONE: '.$datos['eco_1642'].'</td>
                        <td name="eco_1643">SEÑAL PD: '.$datos['eco_1643'].'</td>
                        <td name="eco_1645">SELECCIONE: '.$datos['eco_1645'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1646">IRREGULARIDADES CORTICALES: '.$datos['eco_1646'].'</td>
                        <td name="eco_1648">EROSIONES: '.$datos['eco_1648'].'</td>
                        <td name="eco_1650">MM: '.$datos['eco_1650'].'</td>
                        <td name="eco_1651">PLORIFERACIÓN ÓSEA: '.$datos['eco_1651'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>3MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1653">DERRAME/HS: '.$datos['eco_1653'].'</td>
                        <td name="eco_1655">SELECCIONE: '.$datos['eco_1655'].'</td>
                        <td name="eco_1656">SEÑAL PD: '.$datos['eco_1656'].'</td>
                        <td name="eco_1658">SELECCIONE: '.$datos['eco_1658'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1659">IRREGULARIDADES CORTICALES: '.$datos['eco_1659'].'</td>
                        <td name="eco_1661">EROSIONES: '.$datos['eco_1661'].'</td>
                        <td name="eco_1663">MM: '.$datos['eco_1663'].'</td>
                        <td name="eco_1664">PLORIFERACIÓN ÓSEA: '.$datos['eco_1664'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>4MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1666">DERRAME/HS: '.$datos['eco_1666'].'</td>
                        <td name="eco_1668">SELECCIONE: '.$datos['eco_1668'].'</td>
                        <td name="eco_1669">SEÑAL PD: '.$datos['eco_1669'].'</td>
                        <td name="eco_1671">SELECCIONE: '.$datos['eco_1671'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1672">IRREGULARIDADES CORTICALES: '.$datos['eco_1672'].'</td>
                        <td name="eco_1674">EROSIONES: '.$datos['eco_1674'].'</td>
                        <td name="eco_1676">MM: '.$datos['eco_1676'].'</td>
                        <td name="eco_1677">PLORIFERACIÓN ÓSEA: '.$datos['eco_1677'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>5MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1679">DERRAME/HS: '.$datos['eco_1679'].'</td>
                        <td name="eco_1681">SELECCIONE: '.$datos['eco_1681'].'</td>
                        <td name="eco_1682">SEÑAL PD: '.$datos['eco_1682'].'</td>
                        <td name="eco_1684">SELECCIONE: '.$datos['eco_1684'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1685">IRREGULARIDADES CORTICALES: '.$datos['eco_1685'].'</td>
                        <td name="eco_1687">EROSIONES: '.$datos['eco_1687'].'</td>
                        <td name="eco_1689">MM: '.$datos['eco_1689'].'</td>
                        <td name="eco_1690">PLORIFERACIÓN ÓSEA: '.$datos['eco_1690'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>TENDÓN TIBIAL, EXTENSOR LARGO DEL PRIMER DEDO Y EXTENSOR COMÚN DE LOS DEDOS</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1692">LIMITES: '.$datos['eco_1692'].'</td>
                        <td name="eco_1694">ECOESTRUCTURA: '.$datos['eco_1694'].'</td>
                        <td name="eco_1696">ECOGENICIDAD (NORMAL): '.$datos['eco_1696'].'</td>
                        <td name="eco_1698">ALTERADA: '.$datos['eco_1698'].'</td>
                        <td name="eco_1699">ESPESOR: '.$datos['eco_1699'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1701">ROTURA: '.$datos['eco_1701'].'</td>
                        <td name="eco_1703">TIPO: '.$datos['eco_1703'].'</td>
                        <td name="eco_1705">MM: '.$datos['eco_1705'].'</td>
                        <td colspan="2" name="eco_1706">AUMENTO DE LÍQUIDO EN VAINAS TENDINO: '.$datos['eco_1706'].'</td>
                        <td name="eco_1708">SEÑAL PD VAINA: '.$datos['eco_1708'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploAntTobiIzq='';
                }

                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN MEDIAL - TOBILLO DERECHO
                if(
                    $datos['eco_1709']!= '' || $datos['eco_1711']!= '' 
                    || $datos['eco_1713']!= ''  || $datos['eco_1715']!= '' 
                    || $datos['eco_1716']!= ''  || $datos['eco_1718']!= '' 
                    || $datos['eco_1720']!= ''  || $datos['eco_1722']!= '' 
                    || $datos['eco_1723']!= '' || $datos['eco_1725']!= ''  
                    || $datos['eco_1726']!= '' || $datos['eco_1728']!= '' 
                    || $datos['eco_1729']!= ''  || $datos['eco_1730']!= '' 
                    || $datos['eco_1732']!= ''  || $datos['eco_1734']!= '' 

                ){
                 $ecoExploMTobiDer='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN MEDIAL - TOBILLO DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN TIBIAL</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1709">LIMITES: '.$datos['eco_1709'].'</td>
                        <td name="eco_1711">ECOESTRUCTURA: '.$datos['eco_1711'].'</td>
                        <td name="eco_1713">ECOGENICIDAD (NORMAL): '.$datos['eco_1713'].'</td>
                        <td name="eco_1715">ALTERADA: '.$datos['eco_1715'].'</td>
                        <td name="eco_1716">ESPESOR: '.$datos['eco_1716'].'</td>
                        <td name="eco_1718">ROTURA: '.$datos['eco_1718'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1720">TIPO: '.$datos['eco_1720'].'</td>
                        <td name="eco_1722">MM: '.$datos['eco_1722'].'</td>
                        <td name="eco_1723">AUMENTO DE LÍQUIDO EN VAINAS TENDINO: '.$datos['eco_1723'].'</td>
                        <td name="eco_1725">SEÑAL PD VAINA: '.$datos['eco_1725'].'</td>
                        <td name="eco_1726">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1726'].'</td>
                        <td name="eco_1728">EROSIONES: '.$datos['eco_1728'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_1729">PLORIFERACIÓN: '.$datos['eco_1729'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>LIGAMENTO DELTOIDEO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1730">ECOESTRUCTURA: '.$datos['eco_1730'].'</td>
                        <td name="eco_1732">ROTURA: '.$datos['eco_1732'].'</td>
                        <td colspan="2" name="eco_1734">TIPO: '.$datos['eco_1734'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploMTobiDer='';
                }


                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN MEDIAL - TOBILLO IZQUIERDO
                if(
                    $datos['eco_1736']!= '' || $datos['eco_1738']!= '' 
                    || $datos['eco_1740']!= ''  || $datos['eco_1742']!= '' 
                    || $datos['eco_1745']!= ''  || $datos['eco_1743']!= '' 
                    || $datos['eco_1743_1']!= ''  || $datos['eco_1749']!= '' 
                    || $datos['eco_1750']!= '' || $datos['eco_1752']!= ''  
                    || $datos['eco_1753']!= '' || $datos['eco_1755']!= '' 
                    || $datos['eco_1756']!= ''  || $datos['eco_1757']!= '' 
                    || $datos['eco_1759']!= ''  || $datos['eco_1761']!= '' 

                ){
                 $ecoExploMTobiIzq='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN MEDIAL - TOBILLO IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN TIBIAL</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1736">LIMITES: '.$datos['eco_1736'].'</td>
                        <td name="eco_1738">ECOESTRUCTURA: '.$datos['eco_1738'].'</td>
                        <td name="eco_1740">ECOGENICIDAD (NORMAL): '.$datos['eco_1740'].'</td>
                        <td name="eco_1742">ALTERADA: '.$datos['eco_1742'].'</td>
                        <td name="eco_1743">ESPESOR: '.$datos['eco_1743'].'</td>
                        <td name="eco_1745">ROTURA: '.$datos['eco_1745'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1743_1">TIPO: '.$datos['eco_1743_1'].'</td>
                        <td name="eco_1749">MM: '.$datos['eco_1749'].'</td>
                        <td name="eco_1750">AUMENTO DE LÍQUIDO EN VAINAS TENDINO: '.$datos['eco_1750'].'</td>
                        <td name="eco_1752">SEÑAL PD VAINA: '.$datos['eco_1752'].'</td>
                        <td name="eco_1753">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1753'].'</td>
                        <td name="eco_1755">EROSIONES: '.$datos['eco_1755'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_1756">PLORIFERACIÓN: '.$datos['eco_1756'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>LIGAMENTO DELTOIDEO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1757">ECOESTRUCTURA: '.$datos['eco_1757'].'</td>
                        <td name="eco_1759">ROTURA: '.$datos['eco_1759'].'</td>
                        <td colspan="2" name="eco_1761">TIPO: '.$datos['eco_1761'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploMTobiIzq='';
                }

                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN LATERAL - TOBILLO DERECHO
                if(
                    $datos['eco_1763']!= '' || $datos['eco_1765']!= '' 
                    || $datos['eco_1765']!= ''  || $datos['eco_1767']!= '' 
                    || $datos['eco_1769']!= ''  || $datos['eco_1770']!= '' 
                    || $datos['eco_1772']!= ''  || $datos['eco_1774']!= '' 
                    || $datos['eco_1776']!= '' || $datos['eco_1777']!= ''  
                    || $datos['eco_1779']!= '' || $datos['eco_1780']!= '' 
                    || $datos['eco_1782']!= ''  || $datos['eco_1783']!= '' 
                    || $datos['eco_1784']!= ''  || $datos['eco_1786']!= '' 
                    || $datos['eco_1788']!= '' 

                ){
                 $ecoExploLTobiDer='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN LATERAL - TOBILLO DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN PERONEO</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1763">LIMITES: '.$datos['eco_1763'].'</td>
                        <td name="eco_1765">ECOESTRUCTURA: '.$datos['eco_1765'].'</td>
                        <td name="eco_1767">ECOGENICIDAD (NORMAL): '.$datos['eco_1767'].'</td>
                        <td name="eco_1769">ALTERADA: '.$datos['eco_1769'].'</td>
                        <td name="eco_1770">ESPESOR: '.$datos['eco_1770'].'</td>
                        <td name="eco_1772">ROTURA: '.$datos['eco_1772'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1774">TIPO: '.$datos['eco_1774'].'</td>
                        <td name="eco_1776">MM: '.$datos['eco_1776'].'</td>
                        <td name="eco_1777">AUMENTO DE LÍQUIDO EN VAINAS TENDINO: '.$datos['eco_1777'].'</td>
                        <td name="eco_1779">SEÑAL PD VAINA: '.$datos['eco_1779'].'</td>
                        <td name="eco_1780">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1780'].'</td>
                        <td name="eco_1782">EROSIONES: '.$datos['eco_1782'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_1783">PLORIFERACIÓN: '.$datos['eco_1783'].'</td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>LIGAMENTO PERONEOASTRAGALINO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1784">ECOESTRUCTURA: '.$datos['eco_1784'].'</td>
                        <td name="eco_1786">ROTURA: '.$datos['eco_1786'].'</td>
                        <td colspan="2" name="eco_1788">TIPO: '.$datos['eco_1788'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploLTobiDer='';
                }

                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN POSTERIOR - TOBILLO DERECHO
                if(
                    $datos['eco_1817']!= '' || $datos['eco_1819']!= '' 
                    || $datos['eco_1821']!= ''  || $datos['eco_1823']!= '' 
                    || $datos['eco_1824']!= ''  || $datos['eco_1826']!= '' 
                    || $datos['eco_1828']!= ''  || $datos['eco_1830']!= '' 
                    || $datos['eco_1831']!= '' || $datos['eco_1833']!= ''  
                    || $datos['eco_1835']!= '' || $datos['eco_1836']!= '' 
                    || $datos['eco_1837']!= ''  || $datos['eco_1839']!= '' 
                    || $datos['eco_1840']!= ''  || $datos['eco_1841']!= ''
                    || $datos['eco_1842']!= ''  || $datos['eco_1846']!= ''
                    || $datos['eco_1850']!= '' || $datos['eco_1852']!= ''
                    || $datos['eco_1854']!= '' || $datos['eco_1856']!= ''
                    || $datos['eco_1857']!= '' || $datos['eco_1858']!= ''
                    || $datos['eco_1859']!= '' || $datos['eco_1861']!= ''
                    || $datos['eco_1862']!= '' || $datos['eco_1863']!= ''
                    || $datos['eco_1864']!= '' || $datos['eco_1866']!= ''
                    || $datos['eco_1868']!= '' || $datos['eco_1870']!= ''
                    || $datos['eco_1871']!= '' || $datos['eco_1873']!= ''
                    || $datos['eco_1875']!= '' || $datos['eco_1877']!= ''
                    || $datos['eco_1878']!= '' || $datos['eco_1880']!= ''
                    || $datos['eco_1882']!= '' || $datos['eco_1883']!= ''
                    || $datos['eco_1884']!= '' || $datos['eco_1886']!= ''
                    || $datos['eco_1888']!= '' || $datos['eco_1889']!= ''
                    || $datos['eco_1891']!= '' || $datos['eco_1892']!= ''
                    || $datos['eco_1894']!= '' || $datos['eco_1896']!= ''
                    || $datos['eco_1897']!= '' || $datos['eco_1899']!= ''
                    || $datos['eco_1900']!= '' || $datos['eco_1902']!= ''
                    || $datos['eco_1904']!= '' || $datos['eco_1905']!= ''
                    || $datos['eco_1907']!= '' || $datos['eco_1908']!= ''
                    || $datos['eco_1910']!= '' || $datos['eco_1912']!= ''
                    || $datos['eco_1913']!= '' || $datos['eco_1915']!= ''
                    || $datos['eco_1916']!= ''|| $datos['eco_1920']!= ''
                    || $datos['eco_1921']!= '' || $datos['eco_1923']!= ''
                    || $datos['eco_1924']!= '' || $datos['eco_1925']!= ''
                    || $datos['eco_1918']!= ''


                ){
                 $ecoExploPTobiDer='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN POSTERIOR - TOBILLO DERECHO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN AQUILES</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1817">LIMITES: '.$datos['eco_1817'].'</td>
                        <td name="eco_1819">ECOESTRUCTURA: '.$datos['eco_1819'].'</td>
                        <td name="eco_1821">ECOGENICIDAD (NORMAL): '.$datos['eco_1821'].'</td>
                        <td name="eco_1823">ALTERADA: '.$datos['eco_1823'].'</td>
                        <td name="eco_1824">ESPESOR: '.$datos['eco_1824'].'</td>
                        <td name="eco_1826">ROTURA: '.$datos['eco_1826'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1828">TIPO: '.$datos['eco_1828'].'</td>
                        <td name="eco_1830">MM: '.$datos['eco_1830'].'</td>
                        <td name="eco_1831">ALTERACIONES EN: '.$datos['eco_1831'].'</td>
                        <td name="eco_1833">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1833'].'</td>
                        <td name="eco_1835">EROSIONES: '.$datos['eco_1835'].'</td>
                        <td name="eco_1836">PLORIFERACIÓN: '.$datos['eco_1836'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1837">SEÑAL PD EN ENTESIS: '.$datos['eco_1837'].'</td>
                        <td name="eco_1839">GRADO SEÑAL PD: '.$datos['eco_1839'].'</td>
                        <td name="eco_1840">MEDIDA DE ROTURA TRANSVERSAL: '.$datos['eco_1840'].'</td>
                        <td name="eco_1841">MEDIDA DE ROTURA LONGITUDINAL: '.$datos['eco_1841'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ÚSCULO GEMELO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_1842">'.$datos['eco_1842'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>MÚSCULO SÓLEO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td  name="eco_1846"> '.$datos['eco_1846'].'</td>
                        <td name="eco_1850">DERRAME/HS EN RECESO POSTERIOR: '.$datos['eco_1850'].'</td>
                        <td  name="eco_1852">SEÑAL PD: '.$datos['eco_1852'].'</td>
                        <td name="eco_1854">BURSITIS PREAQUILEA: '.$datos['eco_1854'].'</td>
                        <td name="eco_1856">HS: '.$datos['eco_1856'].'</td>
                        <td name="eco_1857">ECOS INTERNOS: '.$datos['eco_1857'].'</td>

                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1858">SEÑAL PD: '.$datos['eco_1858'].'</td>
                        <td name="eco_1859">BURSITIS PREAQUILEA: '.$datos['eco_1859'].'</td>
                        <td name="eco_1861">HS: '.$datos['eco_1861'].'</td>
                        <td name="eco_1862">ECOS INTERNOS: '.$datos['eco_1862'].'</td>
                        <td name="eco_1863">SEÑAL PD: '.$datos['eco_1863'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>FASCIA PLANTAR</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1864">LIMITES: '.$datos['eco_1864'].'</td>
                        <td name="eco_1866">ECOESTRUCTURA: '.$datos['eco_1866'].'</td>
                        <td name="eco_1868">ECOGENICIDAD (NORMAL): '.$datos['eco_1868'].'</td>
                        <td name="eco_1870">ALTERADA: '.$datos['eco_1870'].'</td>
                        <td name="eco_1871">ESPESOR: '.$datos['eco_1871'].'</td>
                        <td name="eco_1873">ROTURA: '.$datos['eco_1873'].'</td>

                        </tr>

                        <tr style="font-size: 50%;">
                        <td name="eco_1875">TIPO: '.$datos['eco_1875'].'</td>
                        <td name="eco_1877">MM: '.$datos['eco_1877'].'</td>
                        <td name="eco_1878">ALTERACIONES EN: '.$datos['eco_1878'].'</td>
                        <td name="eco_1880">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1880'].'</td>
                        <td name="eco_1882">EROSIONES: '.$datos['eco_1882'].'</td>
                        <td name="eco_1883">PLORIFERACIÓN: '.$datos['eco_1883'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>1MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_1884">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_1884'].'</td>
                        <td name="eco_1886">SINOVITIS: '.$datos['eco_1886'].'</td>
                        <td name="eco_1888">SELECCIONE: '.$datos['eco_1888'].'</td>
                        <td name="eco_1889">EROSIONES: '.$datos['eco_1889'].'</td>
                        <td colspan="2" name="eco_1891">MM: '.$datos['eco_1891'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>2MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_1892">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_1892'].'</td>
                        <td name="eco_1894">SINOVITIS: '.$datos['eco_1894'].'</td>
                        <td name="eco_1896">SELECCIONE: '.$datos['eco_1896'].'</td>
                        <td name="eco_1897">EROSIONES: '.$datos['eco_1897'].'</td>
                        <td colspan="2" name="eco_1899">MM: '.$datos['eco_1899'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>3MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_1900">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_1900'].'</td>
                        <td name="eco_1902">SINOVITIS: '.$datos['eco_1902'].'</td>
                        <td name="eco_1904">SELECCIONE: '.$datos['eco_1904'].'</td>
                        <td name="eco_1905">EROSIONES: '.$datos['eco_1905'].'</td>
                        <td colspan="2" name="eco_1907">MM: '.$datos['eco_1907'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>4MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_1908">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_1908'].'</td>
                        <td name="eco_1910">SINOVITIS: '.$datos['eco_1910'].'</td>
                        <td name="eco_1912">SELECCIONE: '.$datos['eco_1912'].'</td>
                        <td name="eco_1913">EROSIONES: '.$datos['eco_1913'].'</td>
                        <td colspan="2" name="eco_1915">MM: '.$datos['eco_1915'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>5MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_1916">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_1916'].'</td>
                        <td name="eco_1918">SINOVITIS: '.$datos['eco_1918'].'</td>
                        <td name="eco_1920">SELECCIONE: '.$datos['eco_1920'].'</td>
                        <td name="eco_1921">EROSIONES: '.$datos['eco_1921'].'</td>
                        <td colspan="2" name="eco_1923">MM: '.$datos['eco_1923'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ESPACIOS INTERDIGITALES</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td colspan="2" name="eco_1924">COLECCIONES LÍQUIDAS NEUROMA DE MORTON: '.$datos['eco_1924'].'</td>
                        <td colspan="2" name="eco_1925">LOCALIZACIÓN: '.$datos['eco_1925'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploPTobiDer='';
                }

                //   ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE - EXPLORACIÓN POSTERIOR - TOBILLO IZQUIERDO
                if(
                    $datos['eco_1926']!= '' || $datos['eco_1928']!= '' 
                    || $datos['eco_1930']!= ''  || $datos['eco_1932']!= '' 
                    || $datos['eco_1933']!= ''  || $datos['eco_1935']!= '' 
                    || $datos['eco_1937']!= ''  || $datos['eco_1939']!= '' 
                    || $datos['eco_1940']!= '' || $datos['eco_1942']!= ''  
                    || $datos['eco_1944']!= '' || $datos['eco_1945']!= '' 
                    || $datos['eco_1946']!= ''  || $datos['eco_1948']!= '' 
                    || $datos['eco_1949']!= ''  || $datos['eco_1950']!= ''
                    || $datos['eco_1951']!= ''  || $datos['eco_1955']!= ''
                    || $datos['eco_1959']!= '' || $datos['eco_1961']!= ''
                    || $datos['eco_1963']!= '' || $datos['eco_1965']!= ''
                    || $datos['eco_1966']!= '' || $datos['eco_1967']!= ''
                    || $datos['eco_1968']!= '' || $datos['eco_1970']!= ''
                    || $datos['eco_1971']!= '' || $datos['eco_1972']!= ''
                    || $datos['eco_1973']!= '' || $datos['eco_1975']!= ''
                    || $datos['eco_1977']!= '' || $datos['eco_1979']!= ''
                    || $datos['eco_1980']!= '' || $datos['eco_1982']!= ''
                    || $datos['eco_1984']!= '' || $datos['eco_1986']!= ''
                    || $datos['eco_1987']!= '' || $datos['eco_1989']!= ''
                    || $datos['eco_1991']!= '' || $datos['eco_1992']!= ''
                    || $datos['eco_1993']!= '' || $datos['eco_1995']!= ''
                    || $datos['eco_1997']!= '' || $datos['eco_1998']!= ''
                    || $datos['eco_2000']!= '' || $datos['eco_2001']!= ''
                    || $datos['eco_2003']!= '' || $datos['eco_2005']!= ''
                    || $datos['eco_2006']!= '' || $datos['eco_2008']!= ''
                    || $datos['eco_2009']!= '' || $datos['eco_2011']!= ''
                    || $datos['eco_2013']!= '' || $datos['eco_2014']!= ''
                    || $datos['eco_2016']!= '' || $datos['eco_2017']!= ''
                    || $datos['eco_2019']!= '' || $datos['eco_2021']!= ''
                    || $datos['eco_2022']!= '' || $datos['eco_2024']!= ''
                    || $datos['eco_2025']!= ''|| $datos['eco_2027']!= ''
                    || $datos['eco_2029']!= '' || $datos['eco_2030']!= ''
                    || $datos['eco_2032']!= '' || $datos['eco_2033']!= ''
                    || $datos['eco_2034']!= ''


                ){
                 $ecoExploPTobiIzq='
                 <table style="padding: 2px; width: 100%; margin-bottom: 20px;" class="linea">
                        <tr style="font-size: 55%; line-height: 9px">
                        <td style="text-align: center;padding: 15px;" colspan="6">
                            <span><strong>EXPLORACIÓN POSTERIOR - TOBILLO IZQUIERDO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 40%; line-height: 9px">
                                <td style="text-align: center;padding: 5px;" colspan="7">
                                    <span><strong>TENDÓN AQUILES</strong></span>
                                </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1926">LIMITES: '.$datos['eco_1926'].'</td>
                        <td name="eco_1928">ECOESTRUCTURA: '.$datos['eco_1928'].'</td>
                        <td name="eco_1930">ECOGENICIDAD (NORMAL): '.$datos['eco_1930'].'</td>
                        <td name="eco_1932">ALTERADA: '.$datos['eco_1932'].'</td>
                        <td name="eco_1933">ESPESOR: '.$datos['eco_1933'].'</td>
                        <td name="eco_1935">ROTURA: '.$datos['eco_1935'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1937">TIPO: '.$datos['eco_1937'].'</td>
                        <td name="eco_1939">MM: '.$datos['eco_1939'].'</td>
                        <td name="eco_1940">ALTERACIONES EN: '.$datos['eco_1940'].'</td>
                        <td name="eco_1942">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1942'].'</td>
                        <td name="eco_1944">EROSIONES: '.$datos['eco_1944'].'</td>
                        <td name="eco_1945">PLORIFERACIÓN: '.$datos['eco_1945'].'</td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1946">SEÑAL PD EN ENTESIS: '.$datos['eco_1946'].'</td>
                        <td name="eco_1948">GRADO SEÑAL PD: '.$datos['eco_1948'].'</td>
                        <td name="eco_1949">MEDIDA DE ROTURA TRANSVERSAL: '.$datos['eco_1949'].'</td>
                        <td name="eco_1950">MEDIDA DE ROTURA LONGITUDINAL: '.$datos['eco_1950'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ÚSCULO GEMELO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td colspan="4" name="eco_1951">'.$datos['eco_1951'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>MÚSCULO SÓLEO</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td  name="eco_1955"> '.$datos['eco_1955'].'</td>
                        <td name="eco_1959">DERRAME/HS EN RECESO POSTERIOR: '.$datos['eco_1959'].'</td>
                        <td  name="eco_1961">SEÑAL PD: '.$datos['eco_1961'].'</td>
                        <td name="eco_1963">BURSITIS PREAQUILEA: '.$datos['eco_1963'].'</td>
                        <td name="eco_1965">HS: '.$datos['eco_1965'].'</td>
                        <td name="eco_1966">ECOS INTERNOS: '.$datos['eco_1966'].'</td>

                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1967">SEÑAL PD: '.$datos['eco_1967'].'</td>
                        <td name="eco_1970">BURSITIS PREAQUILEA: '.$datos['eco_1970'].'</td>
                        <td name="eco_1971">HS: '.$datos['eco_1971'].'</td>
                        <td name="eco_1971">ECOS INTERNOS: '.$datos['eco_1971'].'</td>
                        <td name="eco_1972">SEÑAL PD: '.$datos['eco_1972'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>FASCIA PLANTAR</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">
                        <td name="eco_1973">LIMITES: '.$datos['eco_1973'].'</td>
                        <td name="eco_1975">ECOESTRUCTURA: '.$datos['eco_1975'].'</td>
                        <td name="eco_1977">ECOGENICIDAD (NORMAL): '.$datos['eco_1977'].'</td>
                        <td name="eco_1979">ALTERADA: '.$datos['eco_1979'].'</td>
                        <td name="eco_1980">ESPESOR: '.$datos['eco_1980'].'</td>
                        <td name="eco_1982">ROTURA: '.$datos['eco_1982'].'</td>

                        </tr>

                        <tr style="font-size: 50%;">
                        <td name="eco_1984">TIPO: '.$datos['eco_1984'].'</td>
                        <td name="eco_1986">MM: '.$datos['eco_1986'].'</td>
                        <td name="eco_1987">ALTERACIONES EN: '.$datos['eco_1987'].'</td>
                        <td name="eco_1989">IRREGULARIDADES CORTICALES EN INSERCIÓN: '.$datos['eco_1989'].'</td>
                        <td name="eco_1991">EROSIONES: '.$datos['eco_1991'].'</td>
                        <td name="eco_1992">PLORIFERACIÓN: '.$datos['eco_1992'].'</td>

                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>1MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_1993">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_1993'].'</td>
                        <td name="eco_1995">SINOVITIS: '.$datos['eco_1995'].'</td>
                        <td name="eco_1997">SELECCIONE: '.$datos['eco_1997'].'</td>
                        <td name="eco_1998">EROSIONES: '.$datos['eco_1998'].'</td>
                        <td colspan="2" name="eco_2000">MM: '.$datos['eco_2000'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>2MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_2001">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_2001'].'</td>
                        <td name="eco_2003">SINOVITIS: '.$datos['eco_2003'].'</td>
                        <td name="eco_2005">SELECCIONE: '.$datos['eco_2005'].'</td>
                        <td name="eco_2006">EROSIONES: '.$datos['eco_2006'].'</td>
                        <td colspan="2" name="eco_2008">MM: '.$datos['eco_2008'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>3MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_2009">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_2009'].'</td>
                        <td name="eco_2011">SINOVITIS: '.$datos['eco_2011'].'</td>
                        <td name="eco_2013">SELECCIONE: '.$datos['eco_2013'].'</td>
                        <td name="eco_2014">EROSIONES: '.$datos['eco_2014'].'</td>
                        <td colspan="2" name="eco_2016">MM: '.$datos['eco_2016'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>4MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_2017">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_2017'].'</td>
                        <td name="eco_2019">SINOVITIS: '.$datos['eco_2019'].'</td>
                        <td name="eco_2021">SELECCIONE: '.$datos['eco_2021'].'</td>
                        <td name="eco_2022">EROSIONES: '.$datos['eco_2022'].'</td>
                        <td colspan="2" name="eco_2024">MM: '.$datos['eco_2024'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>5MTF</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td name="eco_2025">AUMENTO DE LÍQUIDO EN VAINAS: '.$datos['eco_2025'].'</td>
                        <td name="eco_2027">SINOVITIS: '.$datos['eco_2027'].'</td>
                        <td name="eco_2029">SELECCIONE: '.$datos['eco_2029'].'</td>
                        <td name="eco_2030">EROSIONES: '.$datos['eco_2030'].'</td>
                        <td colspan="2" name="eco_2032">MM: '.$datos['eco_2032'].'</td>
                        </tr>

                        <tr style="font-size: 40%; line-height: 9px">
                        <td style="text-align: center;padding: 5px;" colspan="7">
                            <span><strong>ESPACIOS INTERDIGITALES</strong></span>
                        </td>
                        </tr>
                        <tr style="font-size: 50%;">

                        <td colspan="2" name="eco_2033">COLECCIONES LÍQUIDAS NEUROMA DE MORTON: '.$datos['eco_2033'].'</td>
                        <td colspan="2" name="eco_2034">LOCALIZACIÓN: '.$datos['eco_2034'].'</td>
                        </tr>

                </table>        
                 ';
                }
                else{
                    $ecoExploPTobiIzq='';
                }
                // construccion del bloque de ecografia de tobillo
                if (empty($ecoExploAntTobiDer) &&  empty($ecoExploAntTobiIzq) 
                &&  empty($ecoExploMTobiDer) &&  empty($ecoExploMTobiIzq) 
                &&  empty($ecoExploLTobiDer)  &&  empty($ecoExploLTobiIzq) 
                &&  empty($ecoExploPTobiDer)  &&  empty($ecoExploPTobiIzq) 

                ){
                    $informDataHistory.='';

                }
                else{
                    $informDataHistory.=  
                    '<table style="padding: 2px; width: 100%">
                    <tr style="font-size: 10%">
                        <td style="text-align: center;" >

                        </td>
                    </tr>
                    <tr style="font-size: 70%">
                        <td style="text-align: center;" >
                            <br><strong> ECOGRAFÍA DE ALTA RESOLUCIÓN DE TOBILLO Y PIE</strong>
                        </td>
                    </tr>

                </table>
                ';
                }
                if(!empty($ecoExploAntTobiDer) ){

                    $informDataHistory.= '
                    '.$ecoExploAntTobiDer.'
                             ';
                };
                if(!empty($ecoExploAntTobiIzq) ){

                    $informDataHistory.= '
                    '.$ecoExploAntTobiIzq.'
                             ';
                };
                if(!empty($ecoExploMTobiDer) ){

                    $informDataHistory.= '
                    '.$ecoExploMTobiDer.'
                             ';
                };
                if(!empty($ecoExploMTobiIzq) ){

                    $informDataHistory.= '
                    '.$ecoExploMTobiIzq.'
                             ';
                };
                if(!empty($ecoExploLTobiDer) ){

                    $informDataHistory.= '
                    '.$ecoExploLTobiDer.'
                             ';
                };
                if(!empty($ecoExploLTobiIzq) ){

                    $informDataHistory.= '
                    '.$ecoExploLTobiIzq.'
                             ';
                };
                if(!empty($ecoExploPTobiIzq) ){

                    $informDataHistory.= '
                    '.$ecoExploPTobiIzq.'
                             ';
                };
                if(!empty($ecoExploPTobiDer) ){

                    $informDataHistory.= '
                    '.$ecoExploPTobiDer.'
                             ';
                };


                break;     
            
            case 11: // Historia clinica a partir de información de terapia Ocupacional
            
                $haveAntecents  = true;
                // Variables relacionadas al acompañante
                $acompañante    = htmlentities($datos['terap_ocupa10']);
                $parentezco     = htmlentities($datos['terap_ocupa11']);
                $telefonoAcom   = htmlentities($datos['reuma_9']);
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
                $examenFisOtros = '
                <table>           
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Frec. Cardíaca:</strong>'.$frecCardiaca.'</td>
                        <td style="width: 25%;"><strong>Frec. Respiratoria:</strong> '.$frecRespira.'</td>
                        <td style="width: 25%;"><strong>Temperatura:</strong>'.$temperatura.'</td>
                        <td style="width: 25%;"><strong>Tensión Arterial:</strong>'.$tenArterial.'</td>
                    </tr>
                  
                    <tr style="font-size: 55%">
                        <td style="width: 25%;"><strong>Peso: </strong> '.$peso.'</td>
                        <td style="width: 25%;"><strong>Talla: </strong>'.$talla.'</td>
                        <td style="width: 25%;"><strong>IMC: </strong>'.$imc.'</td>
                        <td style="width: 25%;"> </td>
                    </tr>
                </table>   
                ';
                                
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = htmlentities($datos['terap_ocupa_141']);
                $enfermedadActual = htmlentities($datos['terap_ocup_cont1']);

                $planDiag =  htmlentities($datos['terap_ocupa_141_6']);
                $planTrat =  htmlentities($datos['terap_ocupa_141_5']);
                $analisis = htmlentities( $datos['terap_ocupa_141_4']);
                
                
                $informDataHistory = '';

                            // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';
            
                break;
            
            
            case 12: // Historia clinica a partir de información de Fisiatria
                $haveAntecents  = true;
                // Variables relacionadas al acompañante
                $acompañante    = $datos['fisia_6'];
                $parentezco     = $datos['fisia_8'];
                $telefonoAcom   = $datos['fisia_9'];
            
                // Variables que representan signos vitales
                $frecCardiaca   = $datos['fisia_420'];
                $frecRespira    = $datos['fisia_421'];
                $temperatura    = $datos['fisia_422'];
                $tenArterial    = $datos['fisia_419'];
            
                // Variables que representan examen fisico
                $peso       = $datos['fisia_1887'];
                $talla      = $datos['fisia_1888'];
                $imc        = $datos['fisia_1889'];
           
                $temperatura = $datos['fisia_422'];
                $estadoGeneral = $datos['fisia_423'];
                $estadoGeneralObse = $datos['fisia_423'];


                $haveAntecents  = true;
                
                $orl = $datos['fisia_1891'];
                $orlDesc =$datos['fisia_1892'];
                $cabeCuello =$datos['fisia_425'];
                $cabeCuelloDesc=$datos['fisia_426'];;
                $cardiaco =$datos['fisia_1897'];
                $cardiacoDesc =$datos['fisia_1898'];
                $pulmonar =$datos['fisia_1900'];
                $pulmonarDesc =$datos['fisia_1901'];
                $abdomen =$datos['fisia_429'];
                $abdomenDesc =$datos['fisia_430'];
                $extremidad =$datos['fisia_433'];
                $extremidadDesc =$datos['fisia_434']; 
                $piel =$datos['fisia_441'];
                $pielDesc =$datos['fisia_442'];
                $neuro =$datos['fisia_435'];
                $neuroDesc =$datos['fisia_436'];
                $genito =$datos['fisia_431']; 
                $genitoDesc =$datos['fisia_432']; 
                $metabolico =$datos['fisia_439']; 
                $metabolicoDesc =$datos['fisia_440']; 
                $vascular =$datos['fisia_437']; 
                $vascularDesc =$datos['fisia_438']; 
                $otrosOtros = $datos['reuma_1918']; 

                $superficie = '';

                $claseFuncional = '';
            
                if ( $datos['fisia_443'] != '' )
                    $claseFuncional.= 'Clase I';
                
                if ( $datos['fisia_444'] != '' )
                    $claseFuncional.= 'Clase II';
                
                if ( $datos['fisia_445'] != '' )
                    $claseFuncional.= 'Clase III';
            
                if ( $datos['fisia_446'] != '' )
                    $claseFuncional.= 'Clase IV';
                  
                $examenFisOtros = ' 
                            <table>           
                            <tr style="font-size: 55%">
                                <td style="width: 15%;"><strong>Frec. Cardíaca: </strong>'.$frecCardiaca.'</td>
                                <td style="width: 15%;"><strong>Frec. Respiratoria: </strong> '.$frecRespira.'</td>
                                <td style="width: 15%;"><strong>Temperatura: </strong> '.$temperatura.'</td>
                                <td style="width: 15%;"><strong>Tensión Arterial: </strong>  '.$tenArterial .'</td>
                           
                                <td style="width: 15%;"><strong>Peso: </strong>'.$peso.'</td>
                                <td style="width: 15%;"><strong>Talla: </strong> '.$talla.'</td>
                                <td style="width: 10%;"><strong>IMC: </strong> '.$imc.'</td>
                            </tr>
                          
                           
                        </table>   
                        <table>
                        
                          <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>ORL: </strong> '.$orl.' '.$orlDesc.'</td>
                            <td style="width: 50%;"><strong>Cabeza y cuello:</strong> '.$cabeCuello.' '.htmlentities($cabeCuelloDesc).'</td>
                        </tr>
                     
                        
                        <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Cardíaco: </strong> '.$cardiaco.' '.$cardiacoDesc.'</td>
                            <td style="width: 50%;"><strong>Pulmonar:</strong> '.$pulmonar.' '.htmlentities($pulmonarDesc).'</td>
                        </tr>
                 
                        
                        <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Abdomen: </strong>'.$abdomen.' '.htmlentities($abdomenDesc).'</td>
                            <td style="width: 50%;"><strong>Extremidades: </strong>'.$extremidad.' '.htmlentities($extremidadDesc).'</td>
                        </tr>
                          
                          <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Piel: </strong>'.$piel.' '.htmlentities($pielDesc).'</td>
                            <td style="width: 50%;"><strong>Neurológico: </strong>'.$neuro.' '.htmlentities($neuroDesc).'</td>
                          </tr> 
                          <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Genito urinario  : </strong>'.$genito.' '.htmlentities($genitoDesc).'</td>
                            <td style="width: 50%;"><strong>Metabolico  : </strong>'.$metabolico.' '.htmlentities($metabolicoDesc).'</td>
                            <td></td>
                          </tr >
                         <tr style="font-size: 55%">
                            <td style="width: 50%;"><strong>Vascular:  </strong>'.$vascular.' '.htmlentities($vascularDesc).'</td>
                            <td style="width: 50%;"><strong>Estado General:</strong>  '.$estadoGeneral .' '.htmlentities($estadoGeneralObse).'</td>


                          </tr>
                          
                            <tr style="font-size: 55%">
                            <td style="width: 50%;" colspan="2"><strong>Otros:  </strong>'. htmlentities( $otrosOtros).'</td>
                             </tr>
                          
                        
                        </table>
                        
                        <table style="padding: 2px; width: 100%">
                            <tr style="font-size: 55%">
                                <td style="text-align: center;" >
                                    <br><strong>Clase Funcional: </strong>
                                </td>
                                <td style="text-align: center;" >
                                    <br>'.$claseFuncional.'
                                </td>
                            </tr>
                        </table>
                ';
            
            
                /*// Información adicional de Examen físico solamente para caso de fisiatría
                if ( $tipoHistoria == 12 )
                {
                      $html.= '
                                    <td style="text-align: center;" >
                                        <br>'.$datos['fisia_443'].' '.$datos['fisia_444'].' '.$datos['fisia_445'].' '.$datos['fisia_446'].'
                                    </td>
                    ';
                }*/
                
                
                
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = $datos['fisia_10'];
                
                $planDiag = $datos['fisia_2134'];
                $planTrat = $datos['fisia_2135'];
                $analisis = $datos['fisia_2133'];
                //$planDiag = $datos['derm_517'];
                //$planTrat = $datos['derm_518'];
                //$analisis = $datos['derm_516'];
                
                
                $enfermedadActual = $datos['fisia_11'];
            
                // Atención supervisada
                $atenSuper = 0;
            
                // Datos adicionales del paciente
                $ocupacion = '';
            
                // Clinimetrías
                $climinetria = '';
                
            
                // Historia específica de psicología
                $informDataHistory = '';
                


                    // Bloque de Funcionalidad
                    $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 60%; line-height: 9px">
                              <td style="text-align: center;"  colspan="4">
                                  <br><br><strong>Clinimetría:</strong>
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                              <td style="text-align: justify; border: 0,5px solid black; width:25%">
                                <br><strong>HAQDI: </strong> 
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black; width:25%">
                                <br><strong>RESULTADO:</strong> '.$datos['clinimetria'][3]['resultado'].'
                              </td>
                              <td style="text-align: justify; border: 0,5px solid black; width:50%">
                                <br><strong>OBSERVACIONES:</strong> '.$datos['clinimetria'][3]['interpretacion'].'
                              </td>
                          </tr>
                    </table>';
            
                break;
                case 15: // Historia clinica de sst
                    // Llamado a la funcion para generación de pdf del concepto médico
                    $this->printConceptoMedico($userId, $medical_id, $imprimir, $initialsUser, $identification, $datos, $info, $firm);
                break;
                case 14: // Historia clinica a partir de información de infiltraciones
                $nameFormato = 'PROCEDIMIENTO';
                $haveAntecents  = false;
                // Variables relacionadas al acompañante
                $acompañante    = '';
                $parentezco     = '';
                $telefonoAcom   = '';
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';


                
                                
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = htmlentities($datos['terap_ocupa_141']);
                $enfermedadActual = htmlentities($datos['terap_ocup_cont1']);

                $planDiag =  htmlentities($datos['terap_ocupa_141_6']);
                $planTrat =  htmlentities($datos['terap_ocupa_141_5']);
                $analisis = htmlentities( $datos['terap_ocupa_141_4']);
                
                
               
                $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                          <tr style="font-size: 60%; line-height: 9px">
                              <td style="text-align: center;"  colspan="4">
                                  <br><br><strong>Infiltraciones:</strong>
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                               <td style="text-align: justify; border: 0.5px solid black; width:100%">
                                <br><strong>Infiltración Ordenada por:</strong> '.htmlentities($datos['parainfi_01']).'
                              </td>
                          </tr>
                          <tr style="font-size: 50%; line-height: 9px">
                               <td style="text-align: justify; border: 0.5px solid black; width:100%">
                                <br><strong>Sitio Anatómico:</strong> '.htmlentities($datos['parainfi_02']).'
                              </td>
                          </tr>
                            <tr style="font-size: 50%; line-height: 9px">
                                <td style="text-align: justify; border: 0.5px solid black; width:100%">
                                    <br><strong>Aguja Hipodermica:</strong> '.htmlentities($datos['parainfi_06']).'
                                </td>
                            </tr>
                            <tr style="font-size: 50%; line-height: 9px">
                                <td style="text-align: justify; border: 0.5px solid black; width:100%">
                                    <br><strong>Riesgos y Beneficios del Procedimiento:</strong> '.htmlentities($datos['parainfi_08']).'
                                </td>
                            </tr>
                            <tr style="font-size: 50%; line-height: 9px">
                                <td style="text-align: justify; border: 0.5px solid black; width:100%">
                                    <br><strong>Procedimiento:</strong> '.htmlentities($datos['parainfi_07']).'
                                </td>
                            </tr>
                    </table>';

                    $informDataHistory.= '
                    <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 60%; line-height: 9px">
                            <td style="text-align: center;"  colspan="4">
                                <br><br><strong>Medicamentos:</strong>
                            </td>
                        </tr>
                        <tr style="font-size: 50%; line-height: 9px">
                            <td style="text-align: justify; border: 0.5px solid black; width:33%">
                                <br><strong>Medicamento:</strong> '.htmlentities($datos['parainfi_03']).'
                            </td>
                            
                            <td style="text-align: justify; border: 0.5px solid black; width:33%">
                                <br><strong>Lote:</strong> '.htmlentities($datos['parainfi_04']).'
                            </td>
                            <td style="text-align: justify; border: 0.5px solid black; width:34%">
                                <br><strong>Fecha de Vencimiento:</strong> '.htmlentities($datos['parainfi_05']).'
                            </td>
                            </tr>';

                    if( !empty($datos['parainfi_09']) ){

                        $informDataHistory.= '
                        <tr style="font-size: 50%; line-height: 9px">
                            <td style="text-align: justify; border: 0.5px solid black; width:33%">
                                <br><strong>Medicamento:</strong> '.htmlentities($datos['parainfi_09']).'
                            </td>
                            
                            <td style="text-align: justify; border: 0.5px solid black; width:33%">
                                <br><strong>Lote:</strong> '.htmlentities($datos['parainfi_10']).'
                            </td>
                            <td style="text-align: justify; border: 0.5px solid black; width:34%">
                                <br><strong>Fecha de Vencimiento:</strong> '.htmlentities($datos['parainfi_11']).'
                            </td>
                        </tr>';


                    }
    
    
                    $informDataHistory.= '</table>';

                    $analisis = 'undefined';

                    $informDataHistory.= '<table style="padding: 2px; width: 100%">
                    <tr style="font-size: 5%">
                        <td style="text-align: center;" colspan="6">
                            <br>
                        </td>
                    </tr>
                    <tr style="font-size: 50%">
                        <td style="text-align: justify; border: 0,5px solid black; width: 100%" >
                            <br><strong>Recomendaciones:</strong>
                            <br>'.htmlentities(  $datos['reuma_2133']).'<br>

                        </td>
                    </tr>
                </table>';

                            // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';
            
                break;

                case 16:

                    // tipo de procedimiento | Esquema de vacunacion contra el cph
                    $informDataHistory = '
                    <table style="border-collapse: collapse;border-spacing: 0px;border:0.5px solid black;margin-top:15px;" width="100%">
                        <tbody>
                            <tr style="border: 0.5px solid black;">
                                <th style="border: 0.5px solid black; width:50%;" align="center" ><span style="font-size:10px"    >Tipo de procedimiento</span></th>
                                <th style="border: 0.5px solid black; width:50%;" align="center" ><span style="font-size:10px"    >Esquema de vacunación contra el VPH</span></th>
                            </tr>
                    ';

                    if(
                        !empty( $datos['tipo_procedimiento'][0] )
                        ||
                        !empty( $datos['tipo_procedimiento'][1] )
                        ||
                        !empty( $datos['tipo_procedimiento'][2] )
                        ||
                        !empty( $datos['tipo_procedimiento'][3] )
                        ||
                        !empty( $datos['esquema_vph'][0] )
                        ||
                        !empty( $datos['esquema_vph'][1] )
                        ||
                        !empty( $datos['esquema_vph'][2] )
                    ){
                        $informDataHistory .='
                        <tr>
                            <td style="width:50%;border:0.5px solid black;">
                                <span><small> '.
                                (!empty($datos['tipo_procedimiento'][0])? 'citología convencional,' :'').
                                (!empty($datos['tipo_procedimiento'][1])? 'citología medio líquido,' :'').
                                (!empty($datos['tipo_procedimiento'][2])? 'prueba ADN - VPH' :'')
                                .'</small></span>
                                <br>
                                <span><small><b>Fecha toma de citología:</b>'.(!empty($datos['tipo_procedimiento'][3])? $datos['tipo_procedimiento'][3] :'').' </small></span>
                            </td>
                            <td style="width:50%;border:0.5px solid black;">
                                <span>
                                    <small>'.
                                    (!empty($datos['esquema_vph'][0])? 'completo' :'').
                                    (!empty($datos['esquema_vph'][1])? ' incompleto' :'')
                                    .'</small>
                                </span>
                                <br>
                                <span><small><b>Fecha de vacunación:</b> '.(!empty($datos['esquema_vph'][2])? $datos['esquema_vph'][2] :'').'</small></span>
                            </td>
                        </tr>';
                    }
                        


                    $informDataHistory .='    
                        </tbody>
                    </table>
                    ';


                    // Gestaciones
                    $informDataHistory .= '
                    <table style="border-collapse: collapse;border-spacing: 0px;border:0.5px solid black;margin-top:15px;" width="100%">
                        <tbody>
                            <tr style="border: 0.5px solid black;">
                                <th style="border: 0.5px solid black;" colspan="4" align="center"><span style="font-size:10px" >Gestaciones</span></th>
                            </tr>
                    ';

                    if(
                        !empty( $datos['gestaciones'][0] )
                        ||
                        !empty( $datos['gestaciones'][1] )
                        ||
                        !empty( $datos['gestaciones'][2] )
                        ||
                        !empty( $datos['gestaciones'][3] )
                        ||
                        !empty( $datos['gestaciones'][4] )
                        ||
                        !empty( $datos['gestaciones'][5] )
                        ||
                        !empty( $datos['gestaciones'][6] )
                        ||
                        !empty( $datos['gestaciones'][7] )
                        ||
                        !empty( $datos['gestaciones'][8] )
                        ||
                        !empty( $datos['gestaciones'][9] )
                        ||
                        !empty( $datos['gestaciones'][10] )

                    ){

                        $informDataHistory .= '
                        <tr>
                            <td style="width:21%;border: 0.5px solid black;"><b style="font-size:9px" >Lactancia actual: '.(!empty($datos['gestaciones'][0])? $datos['gestaciones'][0] :'').'</b> </td>
                            <td style="width:21%;"><small><b style="font-size:9px" >Número de gestaciones: </b> '.(!empty($datos['gestaciones'][1])? $datos['gestaciones'][1] :'').'</small></td>
                            <td style="width:21%;"><small><b style="font-size:9px" >Número de partos: </b>'.(!empty($datos['gestaciones'][2])? $datos['gestaciones'][2] :'').'</small></td>
                            <td style="width:37%;"><small><b style="font-size:9px" >Número de cesareas:</b> '.(!empty($datos['gestaciones'][3])? $datos['gestaciones'][3] :'').'</small></td>
                        </tr>
                        <tr style="border: 0.5px solid black;">
                            <td style="border: 0.5px solid black;"><b style="font-size:9px">Abortos: '.(!empty($datos['gestaciones'][4])? $datos['gestaciones'][4] :'').'</b> </td>
                            <td><small><b style="font-size:9px">Edad inicio relaciones sexuales:</b> '.(!empty($datos['gestaciones'][5])? $datos['gestaciones'][5] :'').'</small></td>
                            <td><small><b style="font-size:9px">Fecha última menstruación:</b> '.(!empty($datos['gestaciones'][6])? $datos['gestaciones'][6] :'').'</small></td>
                            <td>
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="width:60%; border: 0.4px solid black"><small><b style="font-size:9px">Método de planificación:</b> '.(!empty($datos['gestaciones'][7])? $datos['gestaciones'][7] :'').'</small></td>
                                            <td style="width:40%; border: 0.4px solid black"><small><b style="font-size:9px">Tiempo uso: </b>'.(!empty($datos['gestaciones'][8])? $datos['gestaciones'][8] :'').'</small></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="" colspan="2"><small><b style="font-size:9px">Embarazo actual:</b>'.(!empty($datos['gestaciones'][9])? $datos['gestaciones'][9] :'').'</small></td>
                            <td style="" colspan="2"><small><b style="font-size:9px">Fecha último parto:</b> '.(!empty($datos['gestaciones'][10])? $datos['gestaciones'][10] :'').'</small></td>
                        </tr>
                        ';
                    }



                    $informDataHistory .='
                        </tbody>
                    </table>
                    ';


                    // Informacion adicional
                    $informDataHistory .= '
                    <table style="border-collapse: collapse;border-spacing: 0px;border:0.5px solid black;margin-top:15px;" width="100%">
                        <tbody>
                            <tr style="border: 0.5px solid black;">
                                <th style="border: 0.5px solid black;" colspan="2" align="center"><span style="font-size:10px"    >Información adicional</span></th>
                            </tr>
                    ';

                    if(
                        !empty( $datos['info_adicional'][0] )
                        ||
                        !empty( $datos['info_adicional'][1] )
                        ||
                        !empty( $datos['info_adicional'][2] )
                        ||
                        !empty( $datos['info_adicional'][3] )
                    ){
                        $informDataHistory .= '
                        <tr>
                            <td style="width:50%;">
                                <span><small><b>Fecha última citología: </b>'.(!empty($datos['info_adicional'][0])? $datos['info_adicional'][0] :'').' </small></span>
                                <br>
                                <span><small><b>Resultado:</b> '.(!empty($datos['info_adicional'][1])? $datos['info_adicional'][1] :'').'</small></span>
                            </td>
                            <td style="width:50%;">
                                <span><small><b>Fecha último tamizaje de VPH:</b> '.(!empty($datos['info_adicional'][2])? $datos['info_adicional'][2] :'').'</small></span>
                                <br>
                                <span><small><b>Resultado:</b> '.(!empty($datos['info_adicional'][3])? $datos['info_adicional'][3] :'').'</small></span>
                            </td>
                        </tr>
                        '; 
                    }


                    $informDataHistory .= '
                        </tbody>
                    </table>
                    '; 

                    // PROCEDIMIENTOS ANTERIORES EN CUELLO | ASPECTO DEL CUELLO
                    $informDataHistory .= '
                    <table style="border-collapse: collapse;border-spacing: 0px;border:0.5px solid black;margin-top:15px;" width="100%">
                        <tbody>
                            <tr style="border: 0.5px solid black;">
                                <th style="border: 0.5px solid black; width:50%;" align="center" ><span style="font-size:10px" >Procedimientos anteriores en cuello</span></th>
                                <th style="border: 0.5px solid black; width:50%;" align="center" ><span style="font-size:10px" >Aspecto del cuello</span></th>
                            </tr>
                    ';

                    if(
                        !empty( $datos['procedimientos_anteriores'][0] )
                        ||
                        !empty( $datos['procedimientos_anteriores'][1] )
                        ||
                        !empty( $datos['procedimientos_anteriores'][2] )
                        ||
                        !empty( $datos['procedimientos_anteriores'][3] )
                        ||
                        !empty( $datos['procedimientos_anteriores'][4] )
                        ||
                        !empty( $datos['aspecto_cuello'][0] )
                        ||
                        !empty( $datos['aspecto_cuello'][1] )
                        ||
                        !empty( $datos['aspecto_cuello'][2] )
                        ||
                        !empty( $datos['aspecto_cuello'][3] )
                        ||
                        !empty( $datos['aspecto_cuello'][4] )
                        ||
                        !empty( $datos['aspecto_cuello'][5] )
                        ||
                        !empty( $datos['aspecto_cuello'][6] )
                    ){
                        $informDataHistory .= '
                        <tr>
                            <td style="width:50%;border:0.5px solid black;">
                                <span><small><b>Cauterización:</b> '.(!empty($datos['procedimientos_anteriores'][0])? $datos['procedimientos_anteriores'][0] :'').'</small></span>
                                <br>
                                <span><small><b>Histerectomía:</b> '.(!empty($datos['procedimientos_anteriores'][1])? $datos['procedimientos_anteriores'][1] :'').'</small></span>
                                <br>
                                <span><small><b>Vaporización:</b> '.(!empty($datos['procedimientos_anteriores'][2])? $datos['procedimientos_anteriores'][2] :'').'</small></span>
                                <br>
                                <span><small><b>Conización:</b> '.(!empty($datos['procedimientos_anteriores'][3])? $datos['procedimientos_anteriores'][3] :'').'</small></span>
                                <br>
                                <span><small><b>Crioterapía:</b> </small></span>
                            </td>
                            <td style="width:50%;border:0.5px solid black;">
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="50%">
                                                <span><small><b>Ausente:</b> '.(!empty($datos['aspecto_cuello'][0])? $datos['aspecto_cuello'][0] :'').'</small></span>
                                                <br>
                                                <span><small><b>Sano:</b> '.(!empty($datos['aspecto_cuello'][1])? $datos['aspecto_cuello'][1] :'').'</small></span>
                                                <br>
                                                <span><small><b>Atrófico:</b> '.(!empty($datos['aspecto_cuello'][2])? $datos['aspecto_cuello'][2] :'').'</small></span>
                                                <br>
                                                <span><small><b>Congestivo:</b> '.(!empty($datos['aspecto_cuello'][3])? $datos['aspecto_cuello'][3] :'').'</small></span>
                                                <br>
                                                <span><small><b>Ulcerado:</b> '.(!empty($datos['aspecto_cuello'][4])? $datos['aspecto_cuello'][4] :'').'</small></span>
                                            </td>
                                            <td width="50%">
                                                <span><small><b>Polipo:</b> '.(!empty($datos['aspecto_cuello'][5])? $datos['aspecto_cuello'][5] :'').'</small></span>
                                                <br>
                                                <span><small><b>Masa:</b> '.(!empty($datos['aspecto_cuello'][6])? $datos['aspecto_cuello'][6] :'').'</small></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        '; 
                    }


                    $informDataHistory .= '
                        </tbody>
                    </table>
                    ';

                    if(
                        !empty($datos['observaciones'])
                    ){
                        $informDataHistory .= '
                        <table style="border-collapse: collapse;border-spacing: 0px;border:0.5px solid black;margin-top:15px;" width="100%">
                            <tbody>
                                <tr style="border: 0.5px solid black;">
                                    <td style="border: 0.5px solid black; width:100%;padding-bottom:45px;font-size:9px"><b style="font-size:9px">Observaciones:</b> '.$datos['observaciones'].'</td>
                                </tr>
                            </tbody>
                        </table>
                    ';
                    }

                break;
                default: // Caso no especificado para valores vacíos
                $haveAntecents  = false;
                // Variables relacionadas al acompañante
                $acompañante    = '';
                $parentezco     = '';
                $telefonoAcom   = '';
            
                // Variables que representan signos vitales
                $frecCardiaca   = '';
                $frecRespira    = '';
                $temperatura    = '';
                $tenArterial    = '';
            
                // Variables que representan examen fisico
                $peso       = '';
                $talla      = '';
                $imc        = '';
                $superficie = '';
                $examenFisOtros = '';
            
                // Variable de evolucion
                $evolucion = '';
                
                // Motivo de consulta
                $motivoConsulta = '';
                
                $planDiag = '';
                $planTrat = '';
                $analisis = '';
                
                // Atención supervisada
                $atenSuper = 0;
            
                // Clinimetrías
                $climinetria = '';
            
                break;


     
     
     
            }
        
     
	 
	    //border: 0,5px solid black;
			
        // Instanciacion
        $tcpdf = new XTCPDF(); 

        

        // $pdf->addText(puntos_cm(4),puntos_cm(26.7),12,'Encabezado');

        // Info del documento
        $tcpdf->SetAuthor("Gatoloco Studios S.A.S."); 

        // <b style="left:2em">  Fecha: '.date('Y-m-d').' '.date('H:i:s').'  Página'.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</b>

        // Informacion del ecabezado y footer
        $tcpdf->xheadertext = '<br>
            <table style="padding: 2px; width: 100%; border: 0,5px solid black;">
                <tr style="font-size: 60% ; margin:2px; ">
                    <td style="width:30%">
                        <br><img style=" height:42px;" src="img/logo_londono.jpg">
                    </td>
                    <td style="width:50%; text-align: center">
                        <br>CENTRO DE ATENCION INTEGRAL DE ARTRITIS REUMATOIDE<br>
                        NIT: 900.374.337-6 <br>   
                        BOGOTÁ D.C. 
                        
                                  <br><strong style="font-family:Verdana; font-size: 150%; color: #2B1C82; text-align:center">  '.$nameFormato.' </strong>
          
                    </td>
                    <td style="width:20%; text-align: right; font-size: 130%">
                        <br>
                    </td>
                </tr>
            </table>
        '; 

        // $tcpdf->variable = $opcion; // Set de la variable de validación de previsualización 
        $tcpdf->xfootertext = '<br>   
            <table style="padding: 2px; width: 100%; border: 0,5px solid black;">
                <tr style="font-size: 7px;">        
                    <td style=" text-align: left; font-size:7px;  width:50%">
                        <br><strong><i>Innovando en la excelencia</i></strong>
                        <br>Calle 48# 13-86 Chapinero
                        <br>tel. 7944494 - 3115184516
                    </td>
                    <td style="text-align: right; font-size:7px;  width:50%"> 
                        <br><i>Visitanos en:</i> 
                        <br> <span style="text-decoration:underline; color:#1F1A5A">http://biomab.com.co/</span>
                        <br> <span style="text-decoration:underline; color:#1F1A5A">info@biomab.co</span>
                    </td>
                </tr>
                <!--<tr> 
                    <td colspan="3" style"font-size:6px;"> Software de Administración Médica "SAM" V.1.1 ® - https://samsalud.info ®<td> 
                </tr>-->
                <tr style="font-size: 7px;">   
                    <td style=" text-align: left; font-size:7px; width:50%">
                        <br><strong>Página '.$tcpdf->getAliasNumPage().' de '.$tcpdf->getAliasNbPages().'</strong>
                    </td>
                    <td style=" text-align: right; font-size:7px; width:50%">
                        
                        <br>Fecha y Hora de Impresión: '.date('Y-m-d H:i:s').'<br>
                        '.$initialsUser.'
                    </td>
                </tr>
            </table>
        ';

        // Fuentes del doc
        $textfont = ''; // looks better, finer, and more condensed than 'dejavusans' 
        //$tcpdf->SetFont('Verdana', '', 10);
        // Margenes 
        // $tcpdf->SetMargins(10, 63, 3, false);


        /**
         * inicio de contenido
         */
        $tcpdf->SetMargins(10, 23, 15, false);

        $tcpdf->SetHeaderMargin(10);
        $tcpdf->SetFooterMargin(75);
        
        // Cambio de pagina
        $tcpdf->SetAutoPageBreak(true, 26); 
        
        //$tcpdf->setHeaderFont(array($textfont,'',40)); 
        //$tcpdf->xheadercolor = array(150,0,0); 

        // Validacion para la aparicion tanto del header como del footer
        $tcpdf->SetPrintHeader(true);
        $tcpdf->SetPrintFooter(true);

        // Adicion de nueva pagina con tamaño predefinido en mm
        $resolution= array(216, 279);
        $tcpdf->AddPage('P', $resolution);
        //$resolution= array(216, 139);
        //$tcpdf->AddPage('L', $resolution);
        
        setlocale(LC_MONETARY, 'en_US');
        

        //$this->loadModel('MedicalRecord');
        //$dataHistory = $this->MedicalRecord->consultarHistoriaClinica($medical_id);
        
        $this->loadModel('MedicalRecordDiagnost');

        // -------- BLOQUE DE ANTECEDENTES GENERICO ---------------------------



        if($haveAntecents == true){
            // Sin antecedentes para ecografía
                $antecedente = '
                          <table style="border: 0,5px solid black; width:100%">  
                            
                            <tr style="font-size: 65%">
                                <td style="width: 100%;">
                                    <br><strong>Antecedentes Personales Reumatológicos: </strong>  </td>
                       
                            </tr>
                            <tr style="font-size: 55%">
                                <td style="width: 50%;"><strong>Artrosis: </strong>'.$artrosis.'  '.htmlentities($artrosisObs).' </td>
                                <td style="width: 50%;"><strong>Osteoporosis:</strong> '.$osteoporosis.'  '.htmlentities($osteoporosisObs).'</td>
                       
                            </tr>
                            <tr style="font-size: 55%">
                                <td style="width: 50%;"><strong>Fibromialgia: </strong>'.$fibromalgia.'  '.htmlentities($fibromalgiaObs).' </td>       
                                <td style="width: 50%;"><strong>Lupus: </strong>'.$lups.'  '. htmlentities($lupusObs).' </td>
                               
                            </tr>
                            <tr style="font-size: 55%">
                            
                                <td style="width: 50%;"><strong>Artritis Reumatoide: </strong> '.$arematoide.'  '.htmlentities($arematoideObs).'</td>
                                <td style="width: 50%;"><strong>SJORGEN: </strong>'.$sjorgen.'  '. htmlentities($sjorgenObs).' </td>
                            </tr>   
                            
                                <tr style="font-size: 55%">
                            
                                <td style="width: 50%;"><strong>SPA: </strong> '.$spa.'  '.htmlentities($spaObs).'</td>
                                <td style="width: 50%;"><strong>Esclerodermia: </strong>'.$esclerodermia.'  '. $esclerodermiaObs.' </td>
                            </tr>
                            
                    
                            
                            <tr style="font-size: 55%">
                                <td colspan="4"><strong>Otros: </strong>'. htmlentities( $otrosAntecedentes).'</td>
                            </tr>
                            
                        </table>
                        <table style="border: 0,5px solid black; width:100%">      
                            <tr style="font-size: 65%">
                                <td style="width: 100%;"><strong>Antecedentes Personales No Reumatológicos: </strong>  </td>
                       
                            </tr>
                          
                            <tr style="font-size: 60%">
                            
                                <td style="width: 100%;"><strong>Cardiovasculares: </strong> </td>
                                    <!--<td style="width: 20%;"><strong>Hipertensión Arterial: </strong>'.$hipertencion.' </td>
                                    <td style="width: 20%;"><strong>Infarto con Tto médico: </strong>'.$infartotto.' </td>
                                    <td style="width: 20%;"><strong>Infarto con cateterismo: </strong>'.$infartocatete.' </td>
                                    <td style="width: 20%;"><strong>Revascularización miocardia: </strong>'.$revascularizacion.' </td>-->
                            </tr>
                            
                            <tr style="font-size: 55%">
                                <td style="width: 25%;"><strong>Hipertensión Arterial: </strong>'.$hipertencion.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['0_observaciones'].'</td>
                                <td style="width: 25%;"><strong>Infarto con Tto médico: </strong>'.$infartotto.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['1_observaciones'].'</td>
                            </tr>
                            <tr style="font-size: 55%">
                                <td style="width: 25%;"><strong>Infarto con cateterismo: </strong>'.$infartocatete.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['2_observaciones'].'</td>
                                <td style="width: 25%;"><strong>Revascularización miocárdica: </strong>'.$revascularizacion.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['3_observaciones'].'</td>
                            </tr>
                            
                            <tr style="font-size: 55%">
                                <!--<td style="width: 20%;"><strong> </strong> </td>-->
                                <td style="width: 25%;"><strong>Insuficiencia cardíaca (ICC): </strong>'.$icc.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['4_observaciones'].'</td>
                                <td style="width: 25%;"><strong>Arritmia cardíaca: </strong>'.$arritmia.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['5_observaciones'].'</td>
                            </tr>
                            <tr style="font-size: 55%">
                                <td style="width: 25%;"><strong>Valvulopatía: </strong>'.$valvulopatia.' </td>
                                <td style="width: 25%;">'.$datos['antecedentes']['no_reumatologicos']['0']['6_observaciones'].'</td>
                                <td style="width: 50%;"><strong>Otros: </strong>'.$otrosCardiovascular.' </td>
                            </tr>
                                
                                
                                
                            <tr style="font-size: 60%"> 
                                <td style="width: 100%;"><strong>Metabólicos: </strong> </td>
                            </tr>
                            <tr style="font-size: 55%"> 
                                <td style="width: 25%;"><strong>Diabetes tipo 1: </strong>'.$diabetes1.' </td>
                                <td style="width: 25%;"><strong>Diabetes tipo 2: </strong>'.$diabetes2.' </td>
                                <td style="width: 25%;"><strong>Hiper colesterolemia: </strong>'.$hiperColester.' </td>
                                <td style="width: 25%;"><strong>Hiper tricliceridemia: </strong>'.$hiperTricli.' </td>
                            </tr>
                            
                            <tr style="font-size: 55%">
                                <!--<td style="width: 20%;"><strong> </strong> </td>-->
                                <td style="width: 25%;"><strong>Hiperlipidemia mixta: </strong>'.$hiperMixta.' </td>
                                <td style="width: 25%;"><strong>Hipotiroidismo: </strong>'.$hipotiroidismo.' </td>
                                <td style="width: 25%;"><strong>Hiperuricemia - gota: </strong>'.$hiperGota.' </td>
                                <td style="width: 25%;"><strong>Obesidad: </strong>'.$obesidad.' </td>
                            </tr>
                            
                            <tr style="font-size: 55%">
                                <!--<td style="width: 20%;"><strong> </strong> </td>-->
                                <td style="width: 100%;"><strong>Otros: </strong>'. htmlentities( $otrosMetabolicos).'</td>
                            </tr>
                            
                            
                            <tr style="font-size: 60%"> 
                                <td style="width: 100%;"><strong>Pulmonares y Ocupacionales:</strong> </td>
                                <!--<td style="width: 20%;"><strong>EPOC: </strong>'.$epoc.' </td>
                                <td style="width: 20%;"><strong>Silicosis: </strong>'.$silicosis.' </td>
                                <td style="width: 20%;"><strong>Asma: </strong>'.$asma.' </td>
                                <td style="width: 20%;"><strong>Bagazosis: </strong>'.$bagazosis.' </td>-->
                            </tr>
                            <tr style="font-size: 55%"> 
                                <!--<td style="width: 20%; font-size: 110%"><strong>Pulmonares y</strong> </td>-->
                                <td style="width: 25%;"><strong>EPOC: </strong>'.$epoc.' </td>
                                <td style="width: 25%;"><strong>Silicosis: </strong>'.$silicosis.' </td>
                                <td style="width: 25%;"><strong>Asma: </strong>'.$asma.' </td>
                                <td style="width: 25%;"><strong>Bagazosis: </strong>'.$bagazosis.' </td>
                            </tr>
                            
                            <tr style="font-size: 55%">
                                <!--<td style="width: 20%; font-size: 110%"><strong>Ocupacionales: </strong> </td>-->
                                <td style="width: 25%;"><strong>Tabaquismo: </strong>'.$tabaquismo.' </td>
                                <td style="width: 25%;"><strong>Neumoconiosis: </strong>'.$neumoconiosis.' </td>
                                <td style="width: 50%;"><strong>Otros: </strong>'.$otrosPulmonares.' </td>
                            </tr>
                            
                            <tr style="font-size: 60%"> 
                                <td style="width: 20%;"><strong>Otros: </strong> </td>
                                <!--<td style="width: 20%;"><strong>Cáncer: </strong>'.$cancer.' </td>
                                <td style="width: 20%;"><strong>Enfermedad renal crónica: </strong>'.$enfermedadRenal.' </td>
                                <td style="width: 20%;"><strong>Enfermedad periodontal: </strong>'.$enfermedadPerio.' </td>-->
                            </tr>
                            
                            <tr style="font-size: 55%"> 
                                <!--<td style="width: 20%; font-size: 110%"><strong>Otros: </strong> </td>-->
                                <td style="width: 25%;"><strong>Cáncer: </strong>'.$cancer.' </td>
                                <td style="width: 25%;"><strong>Enfermedad renal crónica: </strong>'.$enfermedadRenal.' </td>
                                <td style="width: 25%;"><strong>Enfermedad periodontal: </strong>'.$enfermedadPerio.' </td>
                            </tr>
                            
                            <tr style="font-size: 55%">
                                <!--<td style="width: 20%;"><strong> </strong> </td>-->
                                <td style="width: 25%;"><strong>VIH: </strong>'.$vih.' </td>
                                <td style="width: 25%;"><strong>Enfermedad neurológica: </strong>'.$enfermedadNeuro.' </td>
                                <td style="width: 50%;"><strong>Otros: </strong>'.$otrosOtros2.' </td>
                            </tr>
                        </table>';
                       
                            $antecedenteAler = '';
                            $antecedenteQuiru = '';
                            $infecPrevias = '';
            
                            switch( $tipoHistoria )
                            {
                                case 3: // Antecedentes adicionales para Reumatología
                                    // Variables para antecedentes quirurgicos 
                                    $quirHombro = (isset( $datos['antecedentes']['quirurjicos']['0']['0'] ) ) ? "Si" : "No";
                                    $quirRodilla = (isset( $datos['antecedentes']['quirurjicos']['0']['1'] ) ) ? "Si" : "No";
                                    $quirCadera = (isset( $datos['antecedentes']['quirurjicos']['0']['2'] ) ) ? "Si" : "No";
                                    $quirTobillo = (isset( $datos['antecedentes']['quirurjicos']['0']['3'] ) ) ? "Si" : "No";
                                    $quirCodo = (isset( $datos['antecedentes']['quirurjicos']['0']['4'] ) ) ? "Si" : "No";
                                
                                    $quirCirHombro = (isset( $datos['antecedentes']['quirurjicos']['1']['0'] ) ) ? "Si" : "No";
                                    $quirCirCodo = (isset( $datos['antecedentes']['quirurjicos']['1']['1'] ) ) ? "Si" : "No";
                                    $quirCirMano = (isset( $datos['antecedentes']['quirurjicos']['1']['2'] ) ) ? "Si" : "No";
                                    $quirCirCadera = (isset( $datos['antecedentes']['quirurjicos']['1']['3'] ) ) ? "Si" : "No";
                                    $quirCirRodilla = (isset( $datos['antecedentes']['quirurjicos']['1']['4'] ) ) ? "Si" : "No";
                                    $quirCirPies = (isset( $datos['antecedentes']['quirurjicos']['1']['5'] ) ) ? "Si" : "No";
                                    $quirCirTobillo = (isset( $datos['antecedentes']['quirurjicos']['1']['6'] ) ) ? "Si" : "No";
                                    
                                    //$antecedente.= '
                                    $antecedenteAler.= '
                                    <table style="border: 0,5px solid black; width:100%">
                                        <tr style="font-size: 65%">
                                            <td style="width: 100%;"><strong>Antecedentes Alérgicos: </strong>  </td>
                       
                                        </tr>
                                        <tr style="font-size: 55%">
                                            <td style="width: 100%;">'.htmlentities($datos['reuma_5_1111']).'</td>
                                        </tr>
                                    </table>';
                                    
                                    //$antecedente.='
                                    $antecedenteQuiru.='
                                    <table style="border: 0,5px solid black; width:100%">
                                        <tr style="font-size: 65%">
                                            <td style="width: 100%;"><strong>Antecedentes Quirúrgicos: </strong>  </td>
                       
                                        </tr>
                                        <tr style="font-size: 55%"> 
                                            <td style="width: 20%;"><strong>Reemplazo Articular: </strong>'.$datos['antecedentes']['quirurjicos']['0']['tiene'].' </td>
                                            <td style="width: 20%;"><strong>Hombro: </strong>'.$quirHombro.' </td>
                                            <td style="width: 20%;"><strong>Rodilla: </strong>'.$quirRodilla.' </td>
                                            <td style="width: 20%;"><strong>Cadera: </strong>'.$quirCadera.' </td>
                                            <td style="width: 20%;"><strong>Tobillo: </strong>'.$quirTobillo.' </td>
                                        </tr>
                                        <tr style="font-size: 55%"> 
                                            <td style="width: 20%;"><strong></strong> </td>
                                            <td style="width: 20%;"><strong>Codo: </strong>'.$quirCodo.' </td>
                                            <td style="width: 60%;"><strong>Otro: </strong>'.htmlentities( $datos['antecedentes']['quirurjicos']['0']['otro']).' </td>
                                        </tr>
                                        <tr style="font-size: 55%"> 
                                            <td style="width: 20%;"><strong>Otra Cirugías del aparato osteomuscular: </strong>'.$datos['antecedentes']['quirurjicos']['1']['tiene'].' </td>
                                            <td style="width: 20%;"><strong>Cirugía Hombro: </strong>'.$quirCirHombro.' </td>
                                            <td style="width: 20%;"><strong>Cirugía Codo: </strong>'.$quirCirCodo.' </td>
                                            <td style="width: 20%;"><strong>Cirugía Mano: </strong>'.$quirCirMano.' </td>
                                            <td style="width: 20%;"><strong>Cirugía Cadera: </strong>'.$quirCirCadera.' </td>
                                        </tr>
                                        <tr style="font-size: 55%"> 
                                            <td style="width: 20%;"></td>
                                            <td style="width: 20%;"><strong>Cirugía Rodilla: </strong>'.$quirCirRodilla.' </td>
                                            <td style="width: 20%;"><strong>Cirugía Pies: </strong>'.$quirCirPies.' </td>
                                            <td style="width: 20%;"><strong>Cirugía Tobillo: </strong>'.$quirCirTobillo.' </td>
                                            <td style="width: 20%;"></td>
                                        </tr>
                                        <tr style="font-size: 55%">
                                            <td style="width: 100%;"><strong>Otros antecedentes quirúrgicos: </strong>'.htmlentities($datos['antecedentes']['quirurjicos']['1']['otro']).' </td>
                                        </tr>
                                        <tr style="font-size: 55%">
                                            <td style="width: 100%;"><strong>Otros antecedentes: </strong>'.htmlentities($datos['antecedentes']['quirurjicos']['otro']).' </td>
                                        </tr>
                                    </table>';

                                   
                                    $infecPrevias.= '
                                    <table style="border: 0,5px solid black; width:100%">
                                        <tr style="font-size: 65%">
                                            <td style="width: 100%;"><strong>Infecciones Previas: </strong>  </td>
                                            
                                        </tr>';
                                        if(!empty($datos['antecedentes']['infecciones_previas'][0]['recibe_tto'])){
                                            $infecPrevias.= '<tr style="font-size: 55%"> 
                                            <td style="width: 20%;"><strong>Tuberculosis: </strong></td>
                                            <td style="width: 80%;">Recibió Tratamiento: '.$datos['antecedentes']['infecciones_previas'][0]['recibe_tto'].' </td>
                                        </tr>';

                                        }
                                        

                                        $infecPrevias.= '</table>
                                    ';
                                
                                    break;
                                
                                case 9: // Antecedentes adicionales para Dermatologia
                                        $antecedenteAler.= '
                                    <table style="border: 0,5px solid black; width:100%">
                                        <tr style="font-size: 65%">
                                            <td style="width: 100%;"><strong>Antecedentes Alérgicos: </strong>  </td>
                       
                                        </tr>
                                        <tr style="font-size: 55%">
                                            <td style="width: 100%;">'.htmlentities($datos['reuma_5_1111']).'</td>
                                        </tr>
                                    </table>'; // 20142737
                            }
                            
                        //$antecedente.= '</table> '; 
            
                $infoAntecedentes.= '
                   <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 10%">
                            <td style="text-align: center;" >

                            </td>
                        </tr>
                        <tr style="font-size: 70%">
                            <td style="text-align: center;" >
                                <br><strong>Antecedentes </strong>
                            </td>
                        </tr>
                    </table>
                    '.$antecedente.'
                    '.$antecedenteAler.'
                    '.$antecedenteQuiru.'
                    '.$infecPrevias.'
                ';
               
        }else {
            $infoAntecedentes = '';
        }

                
             





        // Descomposicion la edad para asignación de años, meses o días de edad para el paciente
        $edad = explode('.', $info['edad']);

        // Validacion para opciones de rangos de edad
        switch($edad[0])
        {
            case '0':
                if ( $edad[1] == '0' ) 
                {
                    $nroEdad = $edad[2].' días';
                }
                else
                {
                    $nroEdad = $edad[1].' meses';
                }

                break;

            case '1':
                $nroEdad = $edad[0].' año';
                break;

            default:
                $nroEdad = $edad[0].' años';
                break;
        }
        
        // Bloque de Información de Acompañante
        $html = '<br>
           <table style="padding: 2px; width: 100%; ">
                <tr style="font-size: 55%">
                    <td style="width: 13%; border-top: 0,5px solid black; border-left: 0,5px solid black;"><br><strong>Paciente:</strong></td>
                    <td style="width: 40%; border-top: 0,5px solid black;"><br>'.$info['paciente'].'</td>
                    <td style="width: 17%; border-top: 0,5px solid black;"><br><strong>Nro. Documento:</strong></td>
                    <td style="width: 30%; border-top: 0,5px solid black;border-right: 0,5px solid black;"><br>'.$info['initials'].': '.$info['identificacion'].'</td>
                </tr>
                <tr style="font-size: 55%">
                    <td style="border-left: 0,5px solid black;"><br><strong>Dirección:</strong></td>
                    <td style=""><br>'.$info['divipola'].'.'.$info['direccion'].'</td>
                    <td style=""><br><strong>Fec. Nacimiento / Edad:</strong></td>
                    <td style="border-right: 0,5px solid black;"><br>'.$info['fecha_nacimiento'].' / '.$nroEdad.'</td>
                </tr>
                <tr style="font-size: 55%">
                    <td style="border-left: 0,5px solid black;"><br><strong>Teléfono / Celular:</strong></td>
                    <td style=""><br>'.$info['telefono'].'</td>
                    <td style=""><br><strong>Sexo:</strong></td>
                    <td style="border-right: 0,5px solid black;"><br>'.$info['genero'].'</td>
                </tr>
                <tr style="font-size: 55%">
                    <td style="border-left: 0,5px solid black;"><br><strong>Correo Electrónico:</strong></td>
                    <td style=""><br>'.$info['correo'].'</td>
                    <td style=""><br><strong>Fecha de Atención / Sede:</strong></td>
                    <td style="border-right: 0,5px solid black;"><br>'. $info['fecha_atencion'].'/ '.$info['sede'].'</td>
                </tr>
                <tr style="font-size: 55%">
                    <td style="border-left: 0,5px solid black;"><br><strong>Ocupación:</strong></td>
                    <td style=""><br>'. $info['ocupacion'].'</td>
                    <td style=""><br><strong>Estado Civil:</strong></td>
                    <td style="border-right: 0,5px solid black;"><br></td>
                </tr>
                <tr style="font-size: 55%">
                    <td style="border-bottom: 0,5px solid black; border-left: 0,5px solid black;"><br><strong>Entidad y Grupo:</strong></td>
                    <td style="border-bottom: 0,5px solid black;"><br>'.$info['cliente_'].'   /  '.$info['plan_tarifario']  .'</td>
                    <td style="border-bottom: 0,5px solid black;"><br><strong>Nro. Orden:</strong></td>
                    <td style="border-bottom: 0,5px solid black; border-right: 0,5px solid black;"><br>'.$info['numero_orden'].'</td>
                </tr>
            </table>'
        ;
        
        // Bloque de Información de Acompañante
        // $html.= '
        //    <table style="padding: 2px; width: 100%; ">
        //         <tr style="font-size: 5%">
        //             <td style="text-align: center;" colspan="6">
        //                 <br>
        //             </td>
        //         </tr>
        //         <tr style="font-size: 50%">
        //             <td style="text-align: center; width: 15%; border: 0,5px solid black; background-color: #56ade0;"><br><strong>Acompañante:</strong></td>
        //             <td style="width: 35%; border: 0,5px solid black;"><br>'.$acompañante.'</td>
        //             <td style="width: 25%; border: 0,5px solid black;"><br><strong>Parentesco:</strong> '.$parentezco.'</td>
        //             <td style="width: 25%; border: 0,5px solid black;"><br><strong>Tel.:</strong> '.$telefonoAcom.'</td>
        //         </tr>
        //     </table>
        // ';
        
        // Información relacioanada a la Historia Clínica y Diagnóstico
        $html.= '
            <table style="padding: 2px; width: 100%; margin-bottom: 20px;">
                <tr style="font-size: 75%; line-height: 9px">
                    <td style="text-align: center;" colspan="3">
                        <br><br><strong> '. $appointment[0]['name'].'</strong>
                    </td>
                </tr>

            </table>
        ';

        if($resultInvestigation[0]['research_participant'] == 1){
            pr('entre');
            $html.= '
            <table style="padding: 2px; width: 100%">
                <tr style="font-size: 5%">
                    <td style="text-align: center;" colspan="6">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 50%;">
                    <td style="text-align: justify; border: 0,5px solid black;  width: 100%" >
                        <br><strong>Sujeto participante en investigación</strong>
                    </td>
                </tr>
            </table>
        ';

        }

        // Bloque de motivo de consulta
        // if($tipoHistoria != 14){

        //     $html.= '
        //     <table style="padding: 2px; width: 100%">
        //         <tr style="font-size: 5%">
        //             <td style="text-align: center;" colspan="6">
        //                 <br>
        //             </td>
        //         </tr>
        //         <tr style="font-size: 50%;">
        //             <td style="text-align: justify; border: 0,5px solid black;  width: 100%" >
        //                 <br><strong>Motivo de Consulta:</strong>
        //                 <br>'.$motivoConsulta.'
        //             </td>
        //         </tr>
        //     </table>
        // ';

        // }
        

    /*    // Información relacioanada a la Historia Clínica y Diagnóstico
        $html.= '
            <table style="padding: 2px; width: 100%; margin-bottom: 20px;">
                <tr style="font-size: 75%; line-height: 9px">
                    <td style="text-align: center;" colspan="3">
                        <br><br><strong>Historia Clínica</strong>
                        
                        <br> <strong> '. $appointment[0]['name'].'</strong>
                    </td>
                </tr>
                
                </table>';*/
        
        


        if( $enfermedadActual != ''){
            $tituloEnfActual =  ( $tipoHistoria == 3 || $tipoHistoria == 8 ) ? "Evolución de la Enfermedad" : "Enfermedad Actual";
            
            // Bloque para Enfermedad Actual - Evolución de la enfermedad en caso de Reumatología
            $html.= '
            <table style="padding: 2px; width: 100%">
                <tr style="font-size: 5%">
                    <td style="text-align: center;" colspan="6">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 50%;">
                    <td style="text-align: justify; border: 0,5px solid black;  width: 100%" >
                        <!--<br><strong>Enfermedad Actual:</strong>-->
                        <br><strong>'.$tituloEnfActual.':</strong>
                        <br>'.$enfermedadActual.'
                    </td>
                </tr>
            </table>
            ';
        }
        
        
         
        
    // DESCOMENTAR BLOQUE PARA PONDER ANTECEDENTES 
     if($infoAntecedentes != ''){

        $html .= $infoAntecedentes;
        
       } 
        
        
        if(count($dataDiagnostico)>0){
             // BLOQUE DE DIAGNOSTICOS 
        
           $html.= '
            <table style="padding: 2px; width: 100%; margin-bottom: 20px;">
                <tr style="font-size: 60%; line-height: 9px">
                    <td style="text-align: center;"  colspan="3">
                        <br><br><strong>Diagnóstico:</strong>
                    </td>
                </tr>
                <tr style="font-size: 50%">
                    <td style="width: 15%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Código:</strong>
                    </td>
                    <td style="width: 55%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Nombre:</strong>
                    </td>
                    <td style="width: 15%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Tipo:</strong>
                    </td>
                    <td style="width: 15%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Prioridad</strong>
                    </td>
                </tr>
            ';

  
            //print_r($dataDiagnostico);

            for ( $i = 0; $i < sizeof( $dataDiagnostico ); $i++  )
            {

                // if($info['fecha_atencion'] >= $dataDiagnostico[$i]['fech_creacion'] ){

                //     if(($info['fecha_atencion'] >= $dataDiagnostico[$i]['fech_modifica'] && $dataDiagnostico[$i]['current'] == 1) || ($info['fecha_atencion'] < $dataDiagnostico[$i]['fech_modifica'] && $dataDiagnostico[$i]['current'] == 0)){

                        $html.= '
                        <!-- Inicio de recorrido para diagnóstico -->
                            <tr style="font-size: 50%">
                                <td style="text-align: center; border: 0,5px solid black;" >
                                    <br>'.$dataDiagnostico[$i]['cie10'].'
                                </td>
                                <td style="text-align: justify; border: 0,5px solid black;" >
                                    <br>'.$dataDiagnostico[$i]['description'].'
                                </td>
                                <td style="text-align: center; border: 0,5px solid black;" >
                                    <br>'.$dataDiagnostico[$i]['diagnosticType'].'
                                </td>
                                <td style="text-align: center; border: 0,5px solid black;" >
                                    <br>'.$dataDiagnostico[$i]['estado'].'
                                </td>
                            </tr>';

                    // }
                // }
            }
            // Observaciones de los diagnósticos
            if ( $datos['reuma_Dx_Observaciones'] != '' )
            {
                $html.= '
                    <tr style="font-size: 50%">
                        <td style="text-align: justify; border: 0,5px solid black; width: 100%" >
                            <br><strong>Observaciones:</strong>
                            <br>'.htmlentities($datos['reuma_Dx_Observaciones']).'
                        </td>
                    </tr>';
            }

            $html.= '</table>';
            
            
        }
        
        
        // Ingreso de bloque específico segun variable 
        if($informDataHistory != ''){

            $html .= $informDataHistory;
        }
        
        // Bloque para Examen Fisico 
        
        // Información relacioanada a Órdenes de Servicios

        if( $examenFisOtros != '' && $tipoHistoria != 3)
        {


            $html.= '
           <table style="padding: 2px; width: 100%">
                <tr style="font-size: 10%">
                    <td style="text-align: center;" >
                        
                    </td>
                </tr>
                <tr style="font-size: 70%">
                    <td style="text-align: center;" >
                        <br><strong>Exámen Fisico</strong>
                    </td>
                </tr>
                  <tr>
                   <td style="border: 0,5px solid black;" >
                        '.$examenFisOtros.'
                    </td>
                </tr>
            </table>
          ';
        }
        
             if(  $cantServices > 0){

                // Información relacioanada a Órdenes de Servicios
                $html.= '
                   <table style="padding: 2px; width: 100%">
                        <tr style="font-size: 10%">
                            <td style="text-align: center;" colspan="3">

                            </td>
                        </tr>
                        <tr style="font-size: 75%">
                            <td style="text-align: center;" colspan="3">
                                <br><strong>Órdenes de Servicios</strong>
                            </td>
                        </tr>
                        <tr style="font-size: 50%">
                            <td style="width: 10%; text-align: center; border: 0,5px solid black;" >
                                <br><strong>Nro.</strong>
                            </td>
                            <td style="width: 80%; text-align: center; border: 0,5px solid black;" >
                                <br><strong>Descripción:</strong>
                            </td>
                              <td style="width: 10%; text-align: center; border: 0,5px solid black;" >
                                <br><strong>Cantidad:</strong>
                            </td>
                        </tr>';

              //  echo($html);


                    // Recorrido para exámenes de laboratorio
                    for ( $i = 0; $i < sizeof($servicio); $i++ )
                    {

                                for ($j=0; $j < sizeof($servicio[$i])-1; $j++) 
                                { 
                                         if(  $servicio[$i]['tipo'] == 'Servicio' ){


                                        $html.= '      
                                        <tr style="font-size: 50%">
                                            <td style="text-align: center; border: 0,5px solid black;" >
                                                 <br>'.$servicio[$i][$j]['concec'].'
                                            </td>
                                            <td style="text-align: justify; border: 0,5px solid black;" >
                                            <br>'.$servicio[$i][$j]['cups'].' - '.$servicio[$i][$j]['nombre'].'
                                            </td>

                                               <td style="text-align: center; border: 0,5px solid black;" >
                                                <br>'.$servicio[$i][$j]['cantidad'].'
                                            </td>
                                        </tr>';
                                        if( $j == 0){
                                         $observacionesServicios .= $servicio[$i]['observaciones']. '';
                                     }
                                   /*  if (empty($servicio[$i][$j]['observaciones_generales'])){
                                        $observacionesServicios ='';
                                    }else{
                                      $observacionesServicios .=''.$servicio[$i][$j]['concec'].'- '.$servicio[$i][$j]['observaciones_generales'].'<br>  ';     
        
                                    }      */   
                                                
                                }
                           }

                    }


                $html .= '</table> ';
                $html .= '<table style=" width: 100%;  padding: 1px;" >
                <tr style="font-size: 50%">
                    <td style="width:17%">
                        <br><strong>Resumen Clínico:</strong>
                    </td>
                    <td style="width:72%;">
                    '.htmlentities($observacionesServicios).'<br>
                    </td>
                    
                </tr>
            </table>';

             }
        
    
             if(  $cantLaboratories > 0){
        
            // Información relacioanada a Órdenes de Servicios
        $html.= '
           <table style="padding: 2px; width: 100%">
                <tr style="font-size: 10%">
                    <td style="text-align: center;" colspan="2">
                        
                    </td>
                </tr>
                <tr style="font-size: 75%">
                    <td style="text-align: center;" colspan="2">
                        <br><strong>Órdenes de Laboratorios</strong>
                    </td>
                </tr>
                <tr style="font-size: 50%">
                    <td style="width: 10%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Nro.</strong>
                    </td>
                    <td style="width: 80%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Descripción:</strong>
                    </td>
                        <td style="width: 10%; text-align: center; border: 0,5px solid black;" >
                        <br><strong>Cantidad:</strong>
                    </td>
                </tr>';
    

      //  echo($html);
        

        
        
           // Recorrido para exámenes de laboratorio

        //    echo '<pre>servicio';   
        //    var_dump( $servicio );
        //    echo '</pre>';
            for ( $i = 0; $i < sizeof($servicio); $i++ )
            {
                        
                    for ($j=0; $j < sizeof($servicio[$i])-1; $j++) 
                    { 
                        
                        if(  $servicio[$i]['tipo'] == 'Laboratorio' ){
                    
                            $html.= '      
                            <tr style="font-size: 50%">
                                <td style="text-align: center; border: 0,5px solid black;" >
                                    <br>'.$servicio[$i][$j]['concec'].'
                                </td>
                                <td style="text-align: justify; border: 0,5px solid black;" >
                                <br>'.$servicio[$i][$j]['cups'].' - '.$servicio[$i][$j]['nombre'].'
                                </td>
                                 <td style="text-align: center; border: 0,5px solid black;" >
                                        <br>'.$servicio[$i][$j]['cantidad'].'
                                    </td>
                            </tr>';
                                if( $j == 0){
                             $observacionesLabora .= $servicio[$i]['observaciones'].'';
                                }
/*
                            if (empty($servicio[$i][$j]['observaciones_generales'])){
                                $observacionesLabora ='';
                            }else{
                              $observacionesLabora .=''.$servicio[$i][$j]['concec'].'- '.$servicio[$i][$j]['observaciones_generales'].'<br>  ';     

                            }*/

                    }
                }
            }
        
            $html .= '</table> ';
            
            $html .= '<table style=" width: 100%;  padding: 1px;" >
            <tr style="font-size: 50%">
                <td style="width:17%">
                    <br><strong>Resumen Clínico:</strong>
                </td>
                <td style="width:72%;">
                    '.htmlentities($observacionesLabora).'<br>
                </td>
                
            </tr>
        </table>';
                 
        
        
            }
            // Pinta el Html si existe alguna formula medica
              if(  $cantPresctiption  > 0){
                  $html.= '
                  <table style="padding: 2px; width: 100%">
                    <tr style="font-size: 10%">
                    <td style="text-align: center;" >
                        
                    </td>
                </tr>
                <tr style="font-size: 75%">
                    <td style="text-align: center;" >
                    ';
                    
                    if ( $tipoHistoria == 4 )
                    {
                        $html.= '
                            <br><strong>Tratamiento Farmacológico Actual</strong>
                        </td>';
                    }
                    else
                    {
                        $html.= '
                            <br><strong>Fórmula Médica</strong>
                        </td>';
                    }
                  
                  $html.= '
                </tr>
                </table>';
                  $html .=$htmlPrescription;
           }
        
                  if(!empty($disability)){

                $html .='

                <table style="width: 100%;  padding: 1px;" >
                    <tr style="font-size: 60%">
                        <td style="width:100%; text-align:center; line-height:35%">
                        </td>
                    </tr> 
                    <tr style="font-size: 60%">
                        <td style="width:100%; text-align:center">
                            <br><strong style="font-family:Verdana; text-align:center">INCAPACIDAD MÉDICA:</strong><br>
                        </td>
                    </tr>
                </table>';

                $html .= '
                <table style="border: 0,5px solid black; width: 100%;  padding: 1px;" >
                <tr style="font-size: 65%">
                    <td style="width:17%" >
                        <br><strong>FECHA INICIO:</strong>
                    </td>
                    <td style="width:16%">
                        <br>'.$disability['date_time_ini'].'
                    </td>
                    <td style="width:15%"> 
                        <br><strong>FECHA FINALIZA:</strong>
                    </td>
                    <td style="width:17%"> 
                        <br>'.$disability['date_time_end'].'
                    </td>
                    <td style="width:16%"> 
                        <br><strong>CANTIDAD DÍAS:</strong>
                    </td>
                    <td style="width:18%"> 
                        <br>'.$disability['number_days'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width:17%;">
                        <br><strong>TIPO INCAPACIDAD:</strong>
                    </td>
                    <td style="width:82%">
                        <br>'.$disability['name'].'
                    </td>
                </tr>
                <tr style="font-size: 65%">
                    <td style="width:17%">
                        <br><strong>CONCEPTO:</strong>
                    </td>
                    <td style="width:82%;">
                        <br>'.$disability['medical_concept'].'
                    </td>
                    
                </tr>
                <tr style="font-size: 65%">
                    <td style="width:17%">
                        <br><strong>Observaciones:</strong>
                    </td>
                    <td style="width:82%;">
                        <br>'.$disability['observations'].'
                    </td>
                    
                </tr>
            </table>';

        }
        
        /**
         * BLOQUE PARA LAS RECOMENDACIONES DE REUMATOLOGIA
         */
        if($htmlRecommendation != ''){

            // $html .='

            //     <table style="width: 100%;  padding: 1px;" >
            //         <tr style="font-size: 60%">
            //             <td style="width:100%; text-align:center; line-height:35%">
            //             </td>
            //         </tr> 
            //         <tr style="font-size: 60%">
            //             <td style="width:100%; text-align:center">
            //                 <br><strong style="font-family:Verdana; text-align:center">RECOMENDACIONES:</strong><br>
            //             </td>
            //         </tr>
            //     </table>';
            
            $html.= '
           <table style="padding: 2px; width: 100%">
                <tr style="font-size: 5%">
                    <td style="text-align: center;" colspan="6">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 50%">
                    <td style="text-align: justify; border: 0,5px solid black; width: 100%" >
                    <br><strong>Recomendaciones:</strong>
                        <br>'.$htmlRecommendation.'
                    </td>
                </tr>
            </table>
        ';

        }
        
        if($planTrat != ''){
              $html.= '
           <table style="padding: 2px; width: 100%">
                <tr style="font-size: 5%">
                    <td style="text-align: center;" colspan="6">
                        <br>
                    </td>
                </tr>
                <tr style="font-size: 50%">
                    <td style="text-align: justify; border: 0,5px solid black; width: 100%" >
                        <br><strong>Plan Tratamiento:</strong>
                        <br>'.$planTrat.'
                    </td>
                </tr>
            </table>
        ';
            
        }
        
        // if ( $tipoHistoria != 7 )
        // {
        //     if( $analisis != 'undefined'  &&  $planDiag != 'undefined'){
        //         // Información relacionada a Análisis y Planes
        //         $html.= '
        //            <table style="padding: 2px; width: 100%">
        //                 <tr style="font-size: 5%">
        //                     <td style="text-align: center;" colspan="6">
        //                         <br>
        //                     </td>
        //                 </tr>
        //                 <tr style="font-size: 50%">
        //                     <td style="text-align: justify; border: 0,5px solid black; width: 100%" >
        //                         <br><strong>Análisis y Plan:</strong>
        //                         <br>'.$analisis.'<br>
        //                         <br>'.$planDiag.'
        //                     </td>
        //                 </tr>
        //             </table>
        //         ';
        //     }
        // }
        
        
       
    // Información relacionada a Análisis y Planes
        

      

        
    

		
        $specialist = $order['specialists_id']['id'];
        	
        $specialistData  = $this->getFirmSpecialist($specialist);

        $query = "SELECT 
            authorize_medical_record.*, specialists.id AS specialist_id
        FROM
            authorize_medical_record
                left  JOIN
            specialists ON specialists.users_id = authorize_medical_record.users_id_authorize
        WHERE
            authorize_medical_record.medical_record_id  = '". $medical_id."'";

        
        $connection = ConnectionManager::get('default');

        $specialistResult       = $connection->execute( $query )->fetchAll('assoc');
            
        $specialistSupervisor =   $this->getFirmSpecialist($specialistResult[0]['specialist_id']);


        // echo '<pre>';
        // var_dump( $specialistData );
        // var_dump($specialistSupervisor);
        // echo '</pre>';
  
      
        
        // Información relacionada a Atención Supervisada
        if ( $atenSuper == 0 )
        {
            // Verificación adicional para ingreso de firmas segun tipo de historia clinica
			if ( $tipoHistoria == 7 ) // HC Sala de Infusión
            {
                $html.= '
               <table style="padding: 2px; width: 100%">
                    <tr>
                        <!-- -->
                        <td style="text-align: right; width:10%;"> 
                            <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                        </td>
                        <td style="font-size:7px !important; width:30%;"> 
                            <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                            <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                            <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                        </td>
                        <!-- Espacio para Firma del auxiliar -->
                        <td style="font-size:7px !important; width:30%; text-align:center"> 
                            <b><br><br>
                            <span style="font-size:7px;">________________________________</span> <br>
                            <span style="font-size:7px;">FIRMA DEL AUXILIAR</span></b>
                        </td>
                        <!-- Espacio para firma del paciente -->
                        <td style="font-size:7px !important; width:30%; text-align:center"> 
                            <b><br><br>
                            <span style="font-size:7px;">________________________________</span> <br>
                            <span style="font-size:7px;">FIRMA DEL PACIENTE</span></b>
                        </td>
                    </tr>
                </table>
                ';
            }
            else // Demás HC
            {
                $html.= '
               <table style="padding: 2px; width: 100%">
                    <tr style="font-size: 5%">
                        <td style="text-align: center;">
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right; width:50%;"> 
                        <img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                        </td>
                        <td style="font-size:7px !important; width:80%;"> 
                        <b> <span style="font-size:7px;">'.$specialistData['nombre'].' </span> <br>
                            <span style="font-size:7px;">'.$specialistData['especialidad'].'</span> <br>
                            <span style="font-size:7px;">'.$specialistData['tarjeta'].'</span></b>
                        </td>
                    </tr>
                </table>
                ';
            }
        }else{
            //echo __LINE__;
               // Información relacionada a Firmas de médicos  expertos y reumatólogos
                $html.= '
                <table style="padding: 2px; width: 100%">
                    <tr style="font-size: 20%">
                        <td style="text-align: center;" colspan="4">
                            <br>
                        </td>
                    </tr>
                    <tr style="font-size: 60%">
                        <td style="width: 15%;text-align: justify;" >
                            <br><img  src="'.$specialistData['url'].'" style="width:50px !important; height:30px !important;" >
                        </td>
                        <td style="width: 35%;text-align: justify;" >
                            <strong>'.$specialistData['nombre'].' </strong><br>
                            <strong>'.$specialistData['especialidad'].'</strong><br>
                            <strong>'.$specialistData['tarjeta'].'</strong>
                        </td>
                        <td style="width: 15%;text-align: justify;" >
                            <br><img  src="'.$specialistSupervisor['url'].'" style="width:50px !important; height:30px !important;" >
                        </td>
                        <td style="width: 35%;text-align: justify;" >
                        <strong>'.$specialistSupervisor['nombre'].' </strong><br>
                        <strong>'.$specialistSupervisor['especialidad'].'</strong><br>
                        <strong>'.$specialistSupervisor['tarjeta'].'</strong>
                        </td>
                    </tr>
                </table>
            ';
            
            
        }

      
        // exit();
        $tcpdf->writeHTML($html, true, false, true, false, '');

        //$tcpdf->Output(WWW_ROOT.'history/HistoriaClinica.pdf', 'FI');
        if( $imprimir ){

            $tcpdf->Output(WWW_ROOT.'history/'.$info['numero_orden'].'HistoriaClinica.pdf', 'FI');
            
        }
        else{

            if(!file_exists(WWW_ROOT."/history_pharmacy/".$identification)){

                mkdir(WWW_ROOT."/history_pharmacy/".$identification, 0777);

            }                    

            $tcpdf->Output(WWW_ROOT.'/history_pharmacy/'.$identification.'/'.substr($info['fecha_atencion'],0,10)."-".$info['numero_orden'].'HistoriaClinica.pdf', 'F');
            
        }
    }


 
    /**
    * Giovanny Marin GL STUDIOS S.A.S 2018-03-06 11:33:26
    * @param  null
    * @return  void
    * Descripción: Genera todos los PDFs requeridos a partir de los parametros de la historia clinica
    */
    // printProceClinico( $userId = "",$id = null, $imprimir = true )
    public function generarTodosHistoria( $idHistoria, $idUsuario )
    {
        $this->loadModel( 'Patients' );
        
        echo __LINE__."<br/>";
        //$data = $this->request->data;
        //$this->eliminarDocumentosHistoria();
        //$historial = $this->Patients->obtenerHistorialPaciente( $idUsuario , $data['date_ini'], $data['date_end']);
        
        //$i =0;
        //foreach( $historial as $historia ){

            // Formula medica
            //if( !empty( $data['tiposImpresion']['formula']	 ) && $data['tiposImpresion']['formula'] == "true" && !empty($idHistoria)){
               
                $this->printFormula( $idUsuario, $idHistoria, false );

            //}
            // Servicios
            //if( !empty( $data['tiposImpresion']['servicio']	 ) && $data['tiposImpresion']['servicio'] == "true" && !empty($idHistoria)){
                $this->printProceClinico( $idUsuario, $idHistoria, false  );
            //}
            // Laboratorio
            //if( !empty( $data['tiposImpresion']['laboratorio']	 ) && $data['tiposImpresion']['laboratorio'] == "true" && !empty($idHistoria) ){
                $this->printLaborClinico( $idUsuario, $idHistoria, false  );
            //}
            //Historia Clinica
            //if( !empty( $data['tiposImpresion']['historia']	 ) && $data['tiposImpresion']['historia'] == "true"	&& !empty($idHistoria) ){
            //    $this->printHistoriaClinica( $idUsuario, $idHistoria, false  );
            //}
            //$i++;
        //}
        
        echo __LINE__."<br/>";
        $success = $this->unirPDFs();
        //echo $success;
        echo json_encode(['success'=>$success]);
        //exit();

    }


    /**
    * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-09-13 11:33:26
    * @param  null
    * @return  void
    * Descripción: Genera todos los PDFs requeridos
    */
    public function generarTodos(){

        $this->loadModel( 'Patients' );
        $data = $this->request->data;
        $this->eliminarDocumentosHistoria();
        $historial = $this->Patients->obtenerHistorialPaciente( $data['patient_id'] , $data['date_ini'], $data['date_end']);
        
        $i =0;
        foreach( $historial as $historia ){

            // Formula medica
            if( !empty( $data['tiposImpresion']['formula']	 ) && $data['tiposImpresion']['formula'] == "true" && !empty($historia['id_medical_record'])){
               
                $this->printFormula( $data[ 'patient_user_id' ], $historia['id_medical_record'], false );

            }
            // Servicios
            if( !empty( $data['tiposImpresion']['servicio']	 ) && $data['tiposImpresion']['servicio'] == "true" && !empty($historia['id_medical_record'])){
                $this->printProceClinico( $data[ 'patient_user_id' ], $historia['id_medical_record'], false  );
            }
            // Laboratorio
            if( !empty( $data['tiposImpresion']['laboratorio']	 ) && $data['tiposImpresion']['laboratorio'] == "true" && !empty($historia['id_medical_record']) ){
                $this->printLaborClinico( $data[ 'patient_user_id' ], $historia['id_medical_record'], false  );
            }
            //Historia Clinica
            if( !empty( $data['tiposImpresion']['historia']	 ) && $data['tiposImpresion']['historia'] == "true"	&& !empty($historia['id_medical_record']) ){
                $this->printHistoriaClinica( $data[ 'patient_user_id' ], $historia['id_medical_record'], false  );
            }
            $i++;
        }
        

        $success = $this->unirPDFs(WWW_ROOT.'history/', 'Historia_Unida');
        echo json_encode(['success'=>$success]);
        exit();

    }


    public function generateAllServices(){
        
        $data = $this->request->data;

        $this->loadModel( 'Patients' );

        if($data['dateRanger']){

            $historial = $this->Patients->obtenerHistorialPaciente( $data['patient_id'] , $data['date_ini'], $data['date_end'],true, empty($data['patient_id']) ? false : true);

        }
        
        $userId = $this->Auth->user('id');
        
        $initialsUser = $this->getNameUserConnect( $userId );

        $this->deleteFiles($data['cc']); 

        foreach ($historial as $key => $value) {     

            $id = $value['id_medical_record'];

            // Formula medica tiposImpresion[formula]
            if( !empty( $data['tiposImpresion']['formula']	 ) && $data['tiposImpresion']['formula'] == "true" && !empty($value['id_medical_record'])){

                $this->printPrescription($userId, $id, false, $data['cc']);

            }
           
            // Laboratorio
            if( !empty( $data['tiposImpresion']['laboratorio']	 ) && $data['tiposImpresion']['laboratorio'] == "true" && !empty($value['id_medical_record']) ){

                $this->printLaboratory($userId, $id, false);

            }
            
            // Servicios
            if( !empty( $data['tiposImpresion']['servicio']	 ) && $data['tiposImpresion']['servicio'] == "true" && !empty($value['id_medical_record'])){

                $this->printProceso($userId, $id, false);

            }
    
            //incapaciadades
            if( !empty( $data['tiposImpresion']['incapacidad']	 ) && $data['tiposImpresion']['incapacidad'] == "true"	&& !empty($value['id_medical_record']) ){

                $this->printDisabilityRecord($userId, $id, false);

            }
            
    
            //Historia Clinica
            if( !empty( $data['tiposImpresion']['historia']	 ) && $data['tiposImpresion']['historia'] == "true"	&& !empty($value['id_medical_record']) ){

                $this->printHistoriaClinica( $userId, $id, false, $initialsUser, $data['cc'] );

            }
            


            
            // // Formula medica
            // if( !empty( $data['tiposImpresion']['formula']	 ) && $data['tiposImpresion']['formula'] == "true" && !empty($historia['id_medical_record'])){
               
            //     $this->printPrescription($userId, $id, false);

            // }
            // // Servicios
            // if( !empty( $data['tiposImpresion']['servicio']	 ) && $data['tiposImpresion']['servicio'] == "true" && !empty($historia['id_medical_record'])){
            //     $this->printProceso($userId, $id, false);
            // }
            // // Laboratorio
            // if( !empty( $data['tiposImpresion']['laboratorio']	 ) && $data['tiposImpresion']['laboratorio'] == "true" && !empty($historia['id_medical_record']) ){
            //     $this->printLaboratory($userId, $id, false);
            // }
            // //Historia Clinica
            // if( !empty( $data['tiposImpresion']['historia']	 ) && $data['tiposImpresion']['historia'] == "true"	&& !empty($historia['id_medical_record']) ){
            //     $this->printHistoriaClinica( $userId, $id, false, $initialsUser  );
            // }

        }
        //echo $initialsUser;
        


        //echo WWW_ROOT.'/history_pharmacy/'.$initialsUser.'/','Historia';

        $success = $this->unirPDFs(WWW_ROOT.'/history_pharmacy/'.$data['cc'].'/','Historia');
        //echo $success;
        echo json_encode(['success'=>$success,'initialsUser'=>$data['cc']]);
        exit();

    }

    /**
    * Carlos Felipe Aguirre Taborda GL STUDIOS S.A.S 2017-09-13 10:39:12
    * @param  null
    * @return  void
    * Descripción: Borra los documentos documentos de la carpeta de historia clinica en 
    * www_root/history/
    */
    public function eliminarDocumentosHistoria(){
        $archivos = scandir( WWW_ROOT.'history/' );

        foreach( $archivos as $archivo ){
            if( $archivo !== '.' && $archivo !== '..' ){
                unlink( WWW_ROOT.'history/'.$archivo );
            }
        }

    }
}





//aaaaaaaaaa
class XTCPDF extends \TCPDF
{
    var $xfooterfont  = PDF_FONT_NAME_MAIN ;
        //var $xfooterfontsize = 8 ;
    /**
     * Instanciacion del Header para crear un pdf.
     * @author Deicy Rojas <deirojas.1@gmail.com>
     * @date     2017-02-16
     * @datetime 2017-02-16T14:03:31-0500
     */
    function Header()
    {


            //list($r, $b, $g) = $this->xheadercolor;
        $this->setY(5);
            //$this->SetFillColor($r, $b, $g);
            //$this->SetTextColor(0 , 0, 0);
            //$this->Cell(0,20, '', 0,1,'C', 1);
            //$this->Text(15,26,$this->xheadertext );
        
        
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = WWW_ROOT.'/img/Resul_Agua.jpg';
        $this->Image($img_file, 0, 0, 216, 279, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
        

        $this->writeHTML($this->xheadertext, true, false, true, false, '');

            // Transformacion para la rotacion de el numero de orden y el contenedor de la muestra
        $this->StartTransform();
        //$this->SetFont('freesans', '', 5);
        $this->SetFont('', '', 5);
        $this->Rotate(-90, 117, 117);
            //$tcpdf->Rect(39, 50, 40, 10, 'D');
        $this->Text(5, 30, 'Software Asistencial Médico "SAM" V.1.1 ® - https://samsalud.info ®');
            // Stop Transformation
        $this->StopTransform();

            // if ( $this->variable == 1 )
            // {
                // draw jpeg image                         x,  y  ancho, alto
                // $this->Image(WWW_ROOT.'/img/BORRADOR.png', 40, 60, 450, 250, '', '', '', true, 72);

                // restore full opacity
        $this->SetAlpha(0);
            // }

    }

    /**
     * Instanciacion del Footer para crea un pdf.
     * @author Deicy Rojas <deirojas.1@gmail.com>
     * @date     2017-02-16
     * @datetime 2017-02-16T14:03:48-0500
     */
    function Footer()
    {
            //$year = date('Y');
            //$footertext = sprintf($this->xfootertext, '');
        $this->SetY(-15);
        //$this->SetFont('', '', 5);
            //$this->SetTextColor(0, 0, 0);
            //$this->SetFont($this->xfooterfont,'',$this->xfooterfontsize);
        $this->writeHTML($this->xfootertext, true, false, true, false, '');
            //$this->Cell(0,8, $footertext,'T',3,'L');
    }
}