<?php
class FavoritesController extends AppController
{
    
    public function beforeFilter()
    {
        parent::beforeFilter();
        
        // Allow users to register and logout.
        $this->Auth->allow('index', 'listFavorites');
    }
    
    public function listFavorites(){

        $uid = $this->Auth->user('id');
        $this->loadModel('InterestContract');
        $this->loadModel('SeenContracts');

        // Se consulta todos los favoritos
        $favorites = $this->InterestContract->find('all',
                Array('order' => Array('InterestContract.id DESC'),
                'conditions' => Array('InterestContract.users_id' => $uid)
            )
        );

        
        foreach($favorites as $key => $contract){
            $num_constancia = $contract['InterestContract']['num_constancia'];

            // Se consulta la tabla donde dice si los cambios de un contrato han sido vistos
            // Para no volver a mostrar la notificacion
            $seenContracts = $this->SeenContracts->find('all',
                    Array('order' => Array('SeenContracts.id DESC'),
                    'conditions' => Array('SeenContracts.num_constancia' => $num_constancia),
                    'limit' => 1,
                )
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://preproduccion.contratos.gov.co/administracion/api/Procesos/17-4-6421313",
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

            curl_close($curl);

            if ($err) {
                print "cURL Error #:" . $err;
            }
            $modifiedContract[] = $response;

            $historyChange = json_decode($modifiedContract[$key]);
            $lastChange = $historyChange[0];
            $arrayDate = explode(" ", $lastChange->cul_fecha);
            $lastDateChange = $arrayDate[0];

            $seenDate = isset($seenContracts[0]['SeenContracts']['seen_date']) ? $seenContracts[0]['SeenContracts']['seen_date'] : 0 ;

            // Si la fecha del cambio es mas actual ha la vista por el usuario entonces se indica que hubo cambios
            if (date($lastDateChange) >= date($seenDate) ) {

                $favorites[$key]['InterestContract']['hasChange'] = true;
            }else{
                $favorites[$key]['InterestContract']['hasChange'] = false;
            }
        
                
        }

        $this->set('listContracts', "");
        $this->set('dataSearchPreference', 0);
        $this->set('searchInput', false);
        $this->set('favorites', $favorites);
      
    }

    public function getHistoryChange(){

        $this->autoRender = false;
        $num_constancia = $this->request->data['num_constancia'];

        $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://preproduccion.contratos.gov.co/administracion/api/Procesos/17-4-6421313",
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

            curl_close($curl);

            if ($err) {
                print "cURL Error #:" . $err;
            }
            $modifiedContract = $response;
            $this->addSeenContract($num_constancia);
            print $modifiedContract;
 
    }

    public function addSeenContract($num_constancia){

        $this->loadModel('SeenContracts');
        
        $today = date('Y-m-d');
        $data = [
            'num_constancia' => $num_constancia,
            'seen_date' => $today,
            'user_id' => $this->Auth->user('id'),
        ];

        $this->SeenContracts->save($data);

    }

    public function searchContracts($searchInput = ""){
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://preproduccion.contratos.gov.co/administracion/api/Procesos/procesoFechas",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_ENCODING => "",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{
            \"fechaIni\": \"2018-05-01\",
            \"fechaFin\": \"2018-05-31\"
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
            
        }else if($searchInput == "welcome"){
            $this->set('listContracts', $listContracts);
            $this->set('dataSearchPreference', 0);
            $this->set('searchInput', "welcome");
            
        }else{
            $this->set('listContracts', $listContracts);
            $this->set('dataSearchPreference', 0);
            $this->set('searchInput', $searchInput);
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
