<?php 




    /**
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * Controlador usado para la lectura de xml 
     */
    class XmlreaderController extends AppController
    {


    	public $name = 'xmlreader';



    	public function read(){

    		$this->autoRender = false;
            $this->setCharset();
    	
            //urls de las categorias de los feeds
    	        
            $parentCategories = $this->getFeedCategories();

            // $departamentos = Array();
            // $ciudades = Array();

            // // echo "<pre>";
            // // print_r($parentCategories);
            // // echo "</pre>";
 
            //      foreach ($parentCategories as $parentCategory) {
                    
                

            //         foreach ($parentCategory['categories'] as $category) {
                        
            //                 echo "<pre>";
            //         print_r($category['url']);
            //         echo "</pre>";

            //         $categoryData = $this->getUrlContent($category["url"]);   
            //                foreach ($categoryData['category']['item'] as $item) {
            //             echo "<pre>";
            //             print_r($item);
            //             $departamentos[] = $item['departamento'];
            //             $ciudades[] = $item['ciudad']; 

            //             echo "</pre>";
            //             }
                 
            //         }
            //      }




            //      $ciudades = array_unique($ciudades);
            //      $departamentos = array_unique($departamentos);

            //     echo "<pre>";
            //     print_r($ciudades);
            //     echo "</pre>";

            //     echo "<pre>";
            //     print_r($departamentos);
            //     echo "</pre>";

    	}


        public function tryMultiple(){


            $this->autoRender = false;

            $constancias = Array("16-13-4624154","16-13-4624417","16-13-4625971","16-4-4619304","16-4-4617166");

            $consRes = Array();

            foreach ($constancias as $cons) {
                
                $consRes[] = $this->getByNumCons($cons);
            }

            pr($consRes);

        }

        /**
         * Obtener informacion por un numero de constancia
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * @date     2016-09-26
         * @datetime 2016-09-26T12:00:57-0500
         * @param    [type]                   $numConstancia [description]
         * @return   [type]                                  [description]
         */
        public function getByNumCons($numConstancia){

            $this->autoRender = false;
            
            //URL of targeted site
            $ch = curl_init();  

            $url="https://www.contratos.gov.co/consultas/detalleProceso.do?numConstancia=".$numConstancia;

           


            // set URL and other appropriate options  
            curl_setopt($ch, CURLOPT_URL, $url);  
            curl_setopt($ch, CURLOPT_HEADER, 0);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  

            // grab URL and pass it to the browser  

            $output = curl_exec($ch);  

            // echo $output;

            // close curl resource, and free up system resources  
            curl_close($ch);  

            /**
             * Ignore html document loaded errors
             */
            libxml_use_internal_errors(true);

            $dom = new domDocument('1.0', 'utf-8'); 
            
            $dom->loadHTML($output); 

            $main = $dom->getElementsByTagName('table')->item(3);               

            $datos = Array();

            $datos['numConstancia'] = $numConstancia;

            $readDocContract = false;

            $numberOfDocs = 0;

            // iterate over each row in the table
            foreach($main->getElementsByTagName('tr') as $tr)
            {   

                $tds = $tr->getElementsByTagName('td'); 

                /**
                 * Finding the number of documents 
                 */
                if ($readDocContract == true) {

                    $trsInside = $tds->item(0)->getElementsByTagName('tr');

                    foreach ($trsInside as $trin) {
                            
             
                      
                       $numberOfDocs = $numberOfDocs + 1;

                    }

                    $numberOfDocs = $numberOfDocs - 1;

                    $datos['number_of_docs'] = $numberOfDocs;


                    $readDocContract = false;

                }

                        switch(strtolower(trim($tds->item(0)->nodeValue))){

                            case "estado del proceso":

                                    $datos["estado_del_proceso"] = trim($tds->item(1)->nodeValue);

                                break;

                            case "fecha y hora de apertura del proceso":
                            
                                    $datos["fecha_apertura_proceso"] = trim($tds->item(1)->nodeValue);

                                break;    

                            case "fecha y hora de cierre del proceso":

                                    $datos["fecha_cierre_proceso"] = trim($tds->item(1)->nodeValue);

                                break;                            
                        
                            case "documentos del proceso":
                                
                                $readDocContract = true;    


                                break;

                        }
 
            }

            return $datos;

        }


       /**
        * Función que obtiene xml de una url
        * @return $xmlData SimpleXml objeto con los datos del xml 
        */
        public function getXml($url = null){

            if (($response_xml_data = file_get_contents($url))===false){
                echo "Error fetching XML\n";
            } else {



               libxml_use_internal_errors(true);
               $xmlData = simplexml_load_string($response_xml_data);

               if (!$xmlData) {
                   echo "Error loading XML\n";
                   foreach(libxml_get_errors() as $error) {
                       echo "\t", $error->message;
                   }
               } else {

                return $xmlData;
            }


        }

        }

        /**
        * Función que usa getUrlContent para trar informacion de un xml y luego imprime su contenido en formato json
        *
        */

        public function getUrlJsonContent(){

            $this->autoRender = false;
            $this->setCharset();

            $url = $this->request->data['url'];
            
            $rs = $this->getUrlContent($url);

            echo json_encode($rs);
        }



        public function getUrlArrayJsonContent(){

            $this->autoRender = false;
            $this->setCharset();

            $urls = $this->request->data['urls'];

            $contents = Array();

            foreach ($urls as $url) {
                
                $contents[] = $this->getUrlContent($url);

            }

            echo json_encode($contents);

        }


        /**
         * Función que obtiene el contenido de un xml formateado por unos atributos de unos arreglos $channelIndexes e $itemIndexes
         * @param $url String url del xml
         * @return $xmlDataArray Array arreglo con los datos
         */
        public function getUrlContent($url = null){
    
            //arreglo que contendra los contenidos formateados del xml
            $xmlDataArray = Array();

            //obtenemos el contenido xml de la url
            $xmlData = $this->getXml($url);

            //nuevo objeto de tipo domDocument para procesar algunos datos de contenido del xml
            $dom = new domDocument('1.0', 'utf-8'); 

            //si hay datos en el xml
            if ($xmlData) {

                //el xml que ibtenemos contiene un indice llamado channel que contiene información importante 
                //de los datos que vienen, el arreglo $channel indexes los describe 
                $channelIndexes = Array("title","link",'description','language','copyright','pubdate');

                 //obtenemos los valores en un nuevo arreglo
                 foreach ($channelIndexes as $index) {
                     $xmlDataArray['category'][$index] = "".$xmlData->channel->$index;

                 }

                 //indices de los items para hacer algunas comparaciones y extraer datos
                 $itemIndexes = Array("title","description","link","author","category");

                 //recorremos los items del objeto item que contiene los datos de los contratos
                 foreach ($xmlData->channel->item as $item) {
                    
                    //item que se agregara al arreglo principal $xmlDataArray 
                    $newItem = Array();


                     //recorremos los indices y procesamos algunos datos
                     foreach ($itemIndexes as $index) {
                         
                         //si estamos en la descripción
                         if ($index== 'description') {
                               
                              //en la parte de la descripcion es necesario separar ciertas etiquetas
                              //cargamos el contenido de este indice en un objeto de tipo domDocument 
                              $dom->loadHTML("".$item->$index); 

                              $dom->preserveWhiteSpace = false; 

                              //obtenemos el tag strong 
                              $strong= $dom->getElementsByTagName('strong'); 

                             //extraemos el valor del primer Strong que corresponde al nombre 
                             $newItem['nombre'] = trim(utf8_decode($strong->item(0)->nodeValue));   
                             
                             //extraemos el valor del segundo Strong que corresponde al valor del contrato   
                             $newItem['valor'] =  trim(str_replace("Valor Estimado: $", "", utf8_decode($strong->item(1)->nodeValue)));   

                             //procesamos el nombre para obtener la ciudad y el departamento
                             $nameParts = explode('-', $newItem['nombre']);

                             $newItem['ciudad'] = trim($nameParts[count($nameParts)-2]);
                             $newItem['departamento'] = trim($nameParts[count($nameParts)-1]);

                             //quitamos el contenido de las etiquetas Strong y luego quitamos todos los tags de html
                             $newItem['contenido'] = strip_tags(preg_replace("/<strong.*?>(.*?)<\/strong>/", "", "".$item->$index)); 

                         }else{

                            // if($index == "link"){   

                            //     $link = "".$item->$index;

                            //     parse_str(parse_url($link, PHP_URL_QUERY), $vars);

                            //     $numConstancia = $vars['numConstancia'];

                            //     $newItem['aditionalInfo'] =  $this->getByNumCons($link);

                            // }
                            
                            $newItem[$index] = "".$item->$index;
                            
                         }


                        // linea usada para que pase derecho la iteacion y obtener los datos tal cual estan en el xml 
                        //$newItem[$index] = "".$item->$index;

                     }

                    //Agregamos el nuevo item  
                    $xmlDataArray['category']['item'][] = $newItem;
                    

                 }  

                 //asignamos los datos formateados
                 $categoriesArray[] = $xmlDataArray;

                 }

               return $xmlDataArray;
   
        }

      /**
        * Funcion que devuelve una cadena Json con las categorias  einformacion del feed rss del secop
        *
        */

        public function getFeedCategoriesArray(){

            $this->autoRender = false;
            $this->setCharset();

            echo json_encode($this->getFeedCategories());
        }

       /**
        * Función que obtiene las categorias padre pertenecientes al feed rss del secop
        */
        public function getFeedCategories(){

            //arreglo principal que almacenara todas la categorias
            $parentCategories = Array();

            //categorias de A. Material Vivo Animal y Vegetal

            //esta variable almacenara cada una de las categorias de una categoria padre
            $categories[] = Array(
                "nombre"=> "Material Vivo Vegetal y Animal, Accesorios y Suministros",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-10000000.xml"
            );

            $parentCategories[] = Array("nombre"=> "A. Material Vivo Animal y Vegetal", "categories"=> $categories);
            
            $categories = Array();


            //categorias de B. Materias Primas

            $categories[] = Array(
                "nombre"    => "Material Mineral, Textil y Vegetal y Animal No Comestible",
                "url"       =>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-11000000.xml"
            );            

            $categories[] = Array(
                "nombre"=> "Material Químico incluyendo Bioquímicos y Materiales de Gas",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-12000000.xml"
            );

            $categories[] = Array(
                "nombre"=> "Materiales de Resina, Colofonia, Caucho, Espuma, Película y Elastómericos",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-13000000.xml"
            );

            $categories[] = Array(
                "nombre"=> "Materiales y Productos de Papel",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-14000000.xml"
            );

            $categories[] = Array(
                "nombre"=> "Materiales Combustibles, Aditivos para Combustibles, Lubricantes y Anticorrosivos",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-15000000.xml"
            );
                           
            $parentCategories[] = Array("nombre"=> "B. Materias Primas", "categories"=> $categories);

            $categories = Array();  

            //categorias de C. Maquinaria, Herramientas, Equipo Industrial y Vehículos

            $categories[] = Array(
                "nombre"=> "Maquinaria y Accesorios de Minería y Perforación de Pozos",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-20000000.xml"
            );

            $categories[] = Array(
                "nombre"=> "Maquinaria y Accesorios para Agricultura, Pesca, Silvicultura y Fauna",
                "url"   =>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-21000000.xml"
            );

            $categories[] = Array(
                
                "nombre"    => "Maquinaria y Accesorios para Construcción y Edificación",

                "url"       => "https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-22000000.xml"
            
            );

            $categories[] = Array(

                "nombre"    => "Maquinaria y Accesorios para Manufactura y Procesamiento Industrial",
            
                "url"       => "https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-23000000.xml"
            );

            $categories[] = Array(
            
                "nombre"    => "Maquinaria, Accesorios y Suministros para Manejo, Acondicionamiento y Almacenamiento de Materiales",

                "url"       =>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-24000000.xml"
            );

            $categories[] = Array(

                "nombre"    => "Vehículos Comerciales, Militares y Particulares, Accesorios y Componentes",
               
                "url"       =>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-25000000.xml"
            );

            $categories[] = Array(
                "nombre"=> "Maquinaria y Accesorios para Generación y Distribución de Energía",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-26000000.xml"
            );

            $categories[] = Array(
                "nombre"=> "Herramientas y Maquinaria General",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-27000000.xml"
            );


            $parentCategories[] = Array("nombre"=> "C. Maquinaria, Herramientas, Equipo Industrial y Vehículos", "categories"=> $categories);
            $categories = Array();  

            //categorias de D. Componentes y Suministros


            $categories[] = Array(
                "nombre"=> "Herramientas y Maquinaria General",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-27000000.xml"
            );

            $categories[] = Array(
                "nombre"=>"Componentes y Suministros para Estructuras, Edificación, Construcción y Obras Civiles",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-30000000.xml"
              );     

            $categories[] = Array(
                "nombre"=>"Componentes y Suministros de Manufactura",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-31000000.xml"
            );

            $categories[] = Array(
                "nombre"=>"Componentes y Suministros Electrónicos",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-32000000.xml"
            );

            $categories[] = Array(
                "nombre"=>"Componentes, Accesorios y Suministros de Sistemas Eléctricos e Iluminación",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-39000000.xml"
            );

            $categories[] = Array(

                "nombre"=>"Componentes y Equipos para Distribución y Sistemas de Acondicionamiento",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-40000000.xml"
            );   
            $categories[] = Array(

                "nombre"=>"Equipos y Suministros de Laboratorio, de Medición, de Observación y de Pruebas",
                "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-41000000.xml"
            );

            $parentCategories[] = Array("nombre"=> "D. Componentes y Suministros", "categories"=> $categories);
            $categories = Array();  


            // categorias de E. Productos de Uso Final

            $categories[] = Array(
            "nombre"=>"Equipo Médico, Accesorios y Suministros",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-42000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Difusión de Tecnologías de Información y Telecomunicaciones",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-43000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Equipos de Oficina, Accesorios y Suministros",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-44000000.xml",
            );

            $categories[] = Array(
            "nombre"=>"Equipos y Suministros para Impresión, Fotografia y Audiovisuales",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-45000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Equipos y Suministros de Defensa, Orden Publico, Proteccion, Vigilancia y Seguridad", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-46000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Equipos y Suministros para Limpieza",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-47000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Maquinaria, Equipo y Suministros para la Industria de Servicios", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-48000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Equipos, Suministros y Accesorios para Deportes y Recreación",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-49000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Alimentos, Bebidas y Tabaco ",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-50000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Medicamentos y Productos Farmacéuticos", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-51000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Artículos Domésticos, Suministros y Productos Electrónicos de Consumo ", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-52000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Ropa, Maletas y Productos de Aseo Personal",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-53000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Productos para Relojería, Joyería y Piedras Preciosas", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-54000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Publicaciones Impresas, Publicaciones Electronicas y Accesorios",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-55000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Muebles, Mobiliario y Decoración", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-56000000.xml"
            );

            $categories[] = Array(
            "nombre"=>"Instrumentos Musicales, Juegos, Juguetes, Artes, Artesanías y Equipo educativo, Materiales, Accesorios y Suministros", 
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-60000000.xml"

            );

            $parentCategories[] = Array("nombre"=> "E. Productos de Uso Final", "categories"=> $categories);
            $categories = Array();  


            //categorias de F. Servicios
            $categories[] = Array(
            "nombre"=> "Servicios de Contratacion Agrícola, Pesquera, Forestal y de Fauna",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-70000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios de Minería, Petróleo y Gas",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-71000000.xml"
            );

            $categories[] = Array(
            "nombre"=> " Servicios de Edificación, Construcción de Instalaciones y Mantenimiento",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-72000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios de Producción Industrial y Manufactura",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-73000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios de Limpieza, Descontaminación y Tratamiento de Residuos",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-76000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Servicios Medioambientales",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-77000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Servicios de Transporte, Almacenaje y Correo",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-78000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Servicios de Gestion, Servicios Profesionales de Empresa y Servicios Administrativos",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-80000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios Basados en Ingeniería, Investigación y Tecnología",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-81000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Servicios Editoriales, de Diseño, de Artes Graficas y Bellas Artes",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-82000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Servicios Públicos y Servicios Relacionados con el Sector Público",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-83000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Servicios Financieros y de Seguros",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-84000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios de Salud",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-85000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios Educativos y de Formación",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-86000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios de Viajes, Alimentación, Alojamiento y Entretenimiento",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-90000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios Personales y Domésticos",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-91000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios de Defensa Nacional, Orden Publico, Seguridad y Vigilancia",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-92000000.xml"
            );

            $categories[] = Array(
            "nombre"=> "Servicios Políticos y de Asuntos Cívicos",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-93000000.xml"
            );

            $categories[] = Array(
             "nombre"=> "Organizaciones y Clubes",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-94000000.xml"
            );


            $parentCategories[] = Array("nombre"=> "F. Servicios", "categories"=> $categories);
            $categories = Array();  

            //categorias de G. Terrenos, Edificios, Estructuras y Vías

            $categories[] = Array(
             "nombre"=> "Terrenos, Edificios, Estructuras y Vías",
            "url"=>"https://www.contratos.gov.co/Archivos/RSSFolder/RSSFiles/rssFeed-95000000.xml"
            );


            $parentCategories[] = Array("nombre"=> "G. Terrenos, Edificios, Estructuras y Vías", "categories"=> $categories);
            $categories = Array();

            return $parentCategories;  
        }



    	public function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow();
            
            }
    }