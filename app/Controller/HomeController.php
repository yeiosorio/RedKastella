<?php

App::uses('CakeEmail', 'Network/Email');

class HomeController extends AppController
{
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // Allow users to register and logout.
        $this->Auth->allow('index', 'searchContracts', 'getContractsDetails', 'saveLastSearch', 'addPlan', 'confirmPayment', 'getPlanType', 'ValidateInfoPlan', 'getBySearch', 'emailPaymentConfirm');
    }
    
    public function index(){
        $this->layout = 'home';

        // Fecha con 3 dias atras
        $today = date('Y-m-d');
        $dateIni = date("Y-m-d",strtotime($today."- 1 days")); 
        

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL => "https://www.contratos.gov.co/administracion/api/Procesos/procesoFechas",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{
            \"fechaIni\": \"$today\",
            \"fechaFin\": \"$today\"
        }",
        CURLOPT_HTTPHEADER => array(
            "authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1NXMzckM0cnAzdDQifQ.Y_bn-wolLvuKcQ8yeH5jbUnsTO-OYc-PnhL3HYaoVwk",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            print "cURL Error :" . $err;
        }
        $this->set('listContracts', $response);
      
    }

    // Metodo encargado de recuperar contratos por fecha de SECOP 1
    public function getBySearch(){
        $this->layout = 'home';

        $today = date('Y-m-d');
        $dateIni = date("Y-m-d",strtotime($today."- 1 days")); 
       

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL => "https://www.contratos.gov.co/administracion/api/Procesos/procesoFechas",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{
            \"fechaIni\": \"2019-05-21\",
            \"fechaFin\": \"2019-05-21\"
        }",
        CURLOPT_HTTPHEADER => array(
            "authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1NXMzckM0cnAzdDQifQ.Y_bn-wolLvuKcQ8yeH5jbUnsTO-OYc-PnhL3HYaoVwk",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            print "cURL Error #:" . $err;
        }
        $listContracts = $response;

        $status = $listContracts ? "OK" : null;

        print json_encode(['status' => $status, 'listContracts' => $listContracts]);
      
    }

    // Buscador principal en la parte del home
    public function searchContracts($searchInput = ""){

        $today = date('Y-m-d');
        $dateIni = date("Y-m-d",strtotime($today."- 1 days")); 
       
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL => "https://www.contratos.gov.co/administracion/api/Procesos/procesoFechas",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{
            \"fechaIni\": \"$today\",
            \"fechaFin\": \"$today\"
        }",
        CURLOPT_HTTPHEADER => array(
            "authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1NXMzckM0cnAzdDQifQ.Y_bn-wolLvuKcQ8yeH5jbUnsTO-OYc-PnhL3HYaoVwk",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            print "cURL Error #:" . $err;
        }
        $listContracts = $response;
        

        if ($searchInput == "searchPreference") {
            $this->set('listContracts', $listContracts);
            $this->set('dataSearchPreference', 1);
            $this->set('searchInput', false);
            $this->set('favorites', []);
            
        }else if($searchInput == "welcome"){
            $this->set('listContracts', $listContracts);
            $this->set('dataSearchPreference', 0);
            $this->set('searchInput', "welcome");
            $this->set('favorites', []);
            
        }else{

            $this->set('listContracts', $listContracts);
            $this->set('dataSearchPreference', 0);
            $this->set('searchInput', $searchInput);
            $this->set('favorites', []);
        }

    }

    public function getContractsDetails(){

        $this->autoRender = false;
        $num_constancia = $this->request->data['num_constancia'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_URL => "https://www.contratos.gov.co/administracion/api/Procesos/archProceso/".$num_constancia,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "{
        }",
        CURLOPT_HTTPHEADER => array(
            "authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1NXMzckM0cnAzdDQifQ.Y_bn-wolLvuKcQ8yeH5jbUnsTO-OYc-PnhL3HYaoVwk",
            "cache-control: no-cache",
            "content-type: application/json",
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            print "cURL Error api contratos #:" . $err;
        }
        $contractDocuments = $response;

        $res = ["contractDocuments" => $contractDocuments];

        print json_encode($res);
           

    }

    public function confirmPayment($data){
        $this->autoRender = false;

        $data = [
            uid => $_POST['extra1'],
            response_code_pol => $_POST['response_code_pol'],
            phone => $_POST['phone'],
            additional_value => $_POST['additional_value'],
            test => $_POST['test'],
            transaction_date => $_POST['transaction_date'],
            cc_number => $_POST['cc_number'],
            cc_holder => $_POST['cc_holder'],
            error_code_bank => $_POST['error_code_bank'],
            billing_country => $_POST['billing_country'],
            bank_referenced_name => $_POST['bank_referenced_name'],
            description => $_POST['description'],
            administrative_fee_tax => $_POST['administrative_fee_tax'],
            value => $_POST['value'],
            administrative_fee => $_POST['administrative_fee'],
            payment_method_type => $_POST['payment_method_type'],
            office_phone => $_POST['office_phone'],
            email_buyer => $_POST['email_buyer'],
            response_message_pol => $_POST['response_message_pol'],
            error_message_bank => $_POST['error_message_bank'],
            transaction_id => $_POST['transaction_id'],
            sign => $_POST['sign'],
            tax => $_POST['tax'],
            payment_method => $_POST['payment_method'],
            billing_address => $_POST['billing_address'],
            payment_method_name => $_POST['payment_method_name'],
            pse_bank => $_POST['pse_bank'],
            state_pol => $_POST['state_pol'],
            currency => $_POST['currency'],
            bank_id => $_POST['bank_id'],
            payment_request_state => $_POST['payment_request_state'],
            administrative_fee_base => $_POST['administrative_fee_base'],
            attempts => $_POST['attempts'],
            merchant_id => $_POST['merchant_id'],
            franchise => $_POST['franchise'],
            payment_method_id => $_POST['payment_method_id'],
            ip => $_POST['ip'],
            billing_city => $_POST['billing_city'],
            pse_reference1 => $_POST['pse_reference1'],
        ];

        $this->loadModel('PayuPayments');

        $this->PayuPayments->create();
        if($this->PayuPayments->save($data)){

            $this->PayuPayments->clear();

            $statePayment = $data['state_pol'];
            $emailBuyer = $data['email_buyer'];
            $uid = $data['uid'];

            $this->loadModel('User');
            // statePayment: (4): Aprobada (6): Rechazada (5): Expirada
            // Si la transaccion es aprovada se le asigna el plan al usuario
            if ($statePayment == 4) {
                $res = $this->User->query(
                    "UPDATE users SET plan_id = 2 WHERE id = $uid "
                );
            }
            
            // Envio de email notificando el estado del pago
            $this->emailPaymentConfirm($statePayment, $emailBuyer, $uid, $_POST['extra2'], $data['value']);
        }
           
    }

    // Envio de email notificando el estado del pago
    public function emailPaymentConfirm($statePayment, $emailBuyer, $uid, $buyerName, $valor){
        // statePayment: (4): Aprobada (6): Rechazada (5): Expirada
        if ($statePayment == 4) {
            $labelStatePayment = "Aprobado";
        } else if($statePayment == 5){
            $labelStatePayment = "Expridada";
        }else if($statePayment == 6){
            $labelStatePayment = "Rechazada";
        }

        $Email = new CakeEmail();
        $Email->from(array('info@redkastella.com' => 'Red Kastella'));
        $Email->template('confirmPayment'); //plantilla a utilizar
        $Email->to($emailBuyer);
        $Email->subject($buyerName." Esta es su confirmación de pago del plan premium");
        $Email->viewVars([ //enviar variables a la plantilla
            'buyerName' => $buyerName,
            'valor' => $valor,
            'labelStatePayment' => $labelStatePayment
          ]);
        $Email->send();

//        echo json_encode(array('success' => true));


    }

    public function saveLastSearch(){

        $this->autoRender = false;
        $data = $this->request->data;
        $data['user_id'] = $this->Auth->user('id');
        
        if ($this->request->is('post')) {

            $this->loadModel('LastSearchs');
            $this->LastSearchs->create();

            if ($this->LastSearchs->save($data)) {
    
                $success = true;
                print json_encode($success);
            } else {
                $success = false;
                print json_encode($success);
                
            }
        } 
    }
    
    public function addPlan(){

        $this->autoRender = false;
        $data = $this->request->data;

        $planId = $data['planId'];
        $uid = $this->Auth->user('id');
       
        if ($this->request->is('post')) {

            $this->loadModel('User');

            $res = $this->User->query(
                "UPDATE users SET plan_id = $planId WHERE id = $uid "
            );


            if ($res) {
    
                $success = true;
                print json_encode($success);
            } else {
                $success = false;
                print json_encode($success);
                
            }
        } 
    }

    public function getPlanType(){

        $this->autoRender = false;
        $uid = $this->Auth->user('id');
        
        if ($this->request->is('post')) {

            $this->loadModel('User');

            $planId = $this->User->query(
                "SELECT plan_id from users where id = $uid "
            );

            $hasPlan = $planId[0]['users']['plan_id'];

            if ($hasPlan) {
    
                $success = true;
                print json_encode($success);
            } else {
                $success = false;
                print json_encode($success);
                
            }
        } 
    }

    public function generateSignatureHash(){

        $this->autoRender = false;
        
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $ApiKey = $data['ApiKey'];
            $merchantId = $data['merchantId'];
            $referenceCode = $data['referenceCode'];
            $activePlan = $data['activePlan'];
            $currency = $data['currency'];
            
            $this->loadModel('Plan');

            $plan = $this->Plan->query(
                "SELECT plan_value from plans where id = $activePlan "
            );

            // $amount = $plan[0]['plans']['plan_value'];
            $amount = "20000";

            if ($plan) {
                // Generacion de hash para firma que se enviara a PayU
                $signatureHash = md5($ApiKey.'~'.$merchantId .'~'. $referenceCode .'~'. $amount .'~'. $currency);
                $success = true;
                print json_encode(['success' => true, 'signatureHash' => $signatureHash, 'planValue' => $amount]);
            } else {
                $success = false;
                print json_encode($success);
                
            }
        } 
    }

    public function ValidateInfoPlan(){

        $this->autoRender = false;
        $uid = $this->Auth->user('id');
        $this->loadModel('User');

        $userPlan = $this->User->query(
            "SELECT * FROM users where id = $uid "
        );

        
        $planId = $userPlan[0]['users']['plan_id'];
        
        if ($this->request->is('post')) {

            $this->loadModel('InterestContract');
            // $this->InterestContract->create();

            // Se verifica la cantidad de favoritos agregados
            $favoritesCant = $this->InterestContract->query(
                "SELECT COUNT(id) cant from interest_contracts where users_id = $uid "
            );
            // Se consulta la couta de favoritos del plan
            $planFavoritesCant = $this->InterestContract->query(
                "SELECT favorites FROM plans where id = $planId "
            );

            $favoritesCant = $favoritesCant[0][0]['cant'];
            $planFavoritesCant = $planFavoritesCant[0]['plans']['favorites'];

            // Se verifica si se supera la cuota de favoritos de acuerdo al plan
            if ($favoritesCant >= $planFavoritesCant) {
                $success = false;
                print json_encode($success);
            }else{
                $success = true;
                print json_encode($success);
            }
        } 
    }




}
