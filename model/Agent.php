<?php

require dirname(__FILE__) . '/../MapasSDK/vendor/autoload.php';

class Agent {

    private $mapasObj = [];
    private $mapasApiConfig = [];
    private $mapas = null;
    

    public function  __construct($mapasApiConfig = []){

        if(!$mapasApiConfig){
            throw new Exception("Erro");
        }

        $this->mapasApiConfig = $mapasApiConfig;
        $this->setMapas();

    }

    

    public function generateWithCsv($uploadfilePath){

        $row = 1;

        if (($handle = fopen($uploadfilePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {

                $totalRowns = count($data);
                
                for ($c=0; $c < $totalRowns; $c++) {
                    $this->setInformation($data[$c], $c);
                }

                $this->createAgent();
                $this->clearMapaObject();

                $row++;
            }

            fclose($handle);
        }
    }

    public function generateAgentesWithJson($uploadfilePath){
        
        if(!file_exists($uploadfilePath)){
            throw new Exception("Arquivo para importação não encontrado. Buscando em " . $uploadfilePath);
        }

        $strJson = file_get_contents($uploadfilePath);
        $agents = json_decode($strJson, true);
        
        $i = 0;

        foreach($agents as $agent){
            

            //$agent = array_filter($agent);
            $newAgent = $this->generateAgent($agent);

            if($newAgent){
                dump("Novo agente Importado | Antigo ID: " . $agent['id'] . " | Novo ID: " . $newAgent->id . " | Email: " . $newAgent->emailPrivado . " | Nome: " . $newAgent->name . "  ");
            }
        }


        
    }

    public function generateAgent($agent = []){

        $agent = $this->format($agent);
        dump($agent);
        $newAgent = $this->mapas->createEntity('agent', $agent );
        return $newAgent;
    }

    public function format($agent){
        if(isset($agent['terms']['area']) && empty($agent['terms']['area'])){
            $agent['terms']['area'] = [""];
        }

        return [
            'name' => $agent['name'],
            'publicLocation' => isset($agent['publicLocation']) ? $agent['publicLocation'] : true,
            'location' => [
                isset($agent['latitude']) ? $agent['latitude'] : '0',
                isset($agent['longitude']) ? $agent['longitude'] : '0'
            ],
            'shortDescription' => isset($agent['shortDescription']) ? $agent['shortDescription'] : 'N/A',
            'longDescription' => isset($agent['longDescription']) ? $agent['longDescription'] : '',
            'status' => $agent['status'],
            'subsite' => $agent['subsite'],
            'En_Municipio' => isset($agent['En_Municipio']) ? $agent['En_Municipio'] : "N/A",
            'En_Estado' => isset($agent['En_Estado']) ? $agent['En_Estado'] : "N/A",
            'facebook' => "",
            'type' => $agent['type']['id'],
            'subsiteId' => $agent['subsiteId'],
            'nomeCompleto' => isset($agent['nomeCompleto']) ? $agent['nomeCompleto'] : "" ,
            'documento' => isset($agent['documento']) ? $agent['documento'] : "",
            'raca' => isset($agent['dataDeNascimento']) ? $agent['dataDeNascimento'] : "",
            'dataDeNascimento' => isset($agent['dataDeNascimento']) ? $agent['dataDeNascimento'] : "",
            "localizacao" => isset($agent['localizacao']) ? $agent['localizacao'] : "",
            "genero" => isset($agent['genero']) ? $agent['genero'] : "",
            "orientacaoSexual" => isset($agent['orientacaoSexual']) ? $agent['orientacaoSexual'] : "",
            "emailPublico" => isset($agent['emailPublico']) ? $agent['emailPublico'] : "",
            "emailPrivado" => isset($agent['emailPrivado']) ? $agent['emailPrivado'] : "",
            "telefonePublico" => isset($agent['telefonePublico']) ? $agent['telefonePublico'] : "",
            "telefone1" => isset($agent['telefone1']) ? $agent['telefone1'] : "",
            "telefone2" => isset($agent['telefone2']) ? $agent['telefone2'] : "",
            "endereco" => isset($agent['endereco']) ? $agent['endereco'] : "",
            "En_CEP" => isset($agent['En_CEP']) ? $agent['En_CEP'] : "N/A",
            "En_Nome_Logradouro" => isset($agent['En_Nome_Logradouro']) ? $agent['En_Nome_Logradouro'] : "N/A",
            "En_Num" => isset($agent['En_Num']) ? $agent['En_Num'] : "",
            "En_Complemento" => isset($agent['En_Complemento']) ? $agent['En_Complemento'] : "N/A",
            "En_Bairro" => isset($agent['En_Bairro']) ? $agent['En_Bairro'] : "N/A",
            "site" => "",
            "twitter" => "",
            "googleplus" => isset($agent['googleplus']) ? $agent['googleplus'] : "",
            "instagram" => "",
            "geoEstado" => isset($agent['geoEstado']) ? $agent['geoEstado'] : "",
            "geoEstado_cod" => isset($agent['geoEstado_cod']) ? $agent['geoEstado_cod'] : "",
            "geoMesorregiao" => isset($agent['geoMesorregiao']) ? $agent['geoMesorregiao'] : "",
            "geoMesorregiao_cod" => isset($agent['geoMesorregiao_cod']) ? $agent['geoMesorregiao_cod'] : "",
            "geoMicrorregiao" => isset($agent['geoMicrorregiao']) ? $agent['geoMicrorregiao'] : "",
            "geoMicrorregiao_cod" => isset($agent['geoMicrorregiao_cod']) ? $agent['geoMicrorregiao_cod'] : "",
            "geoMunicipio" => isset($agent['geoMunicipio']) ? $agent['geoMunicipio'] : "",
            "geoMunicipio_cod" => isset($agent['geoMunicipio_cod']) ? $agent['geoMunicipio_cod'] : "",
            "geoSetor_censitario" => isset($agent['geoSetor_censitario']) ? $agent['geoSetor_censitario'] : "",
            "geoSetor_censitario_cod" => isset($agent['geoSetor_censitario_cod']) ? $agent['geoSetor_censitario_cod'] : "",
            "num_sniic" => isset($agent['num_sniic']) ? $agent['num_sniic'] : "",
            "tipologia_individual_cbo_ocupacao" => isset($agent['tipologia_individual_cbo_ocupacao']) ? $agent['tipologia_individual_cbo_ocupacao'] : "",
            "tipologia_individual_cbo_cod" => isset($agent['tipologia_individual_cbo_cod']) ? $agent['tipologia_individual_cbo_cod'] : "",
            "tipologia_nivel1" => isset($agent['tipologia_nivel1']) ? $agent['tipologia_nivel1'] : "",
            "tipologia_nivel2" => isset($agent['tipologia_nivel2']) ? $agent['tipologia_nivel2'] : "",
            "tipologia_nivel3" => isset($agent['tipologia_nivel3']) ? $agent['tipologia_nivel3'] : "",
            "opportunityTabName" => isset($agent['opportunityTabName']) ? $agent['opportunityTabName'] : "",
            "useOpportunityTab" => isset($agent['useOpportunityTab']) ? $agent['useOpportunityTab'] : "",
            "sentNotification" => isset($agent['sentNotification']) ? $agent['sentNotification'] : "",
            "_children" => isset($agent['_children']) ? $agent['_children'] : "",
            "_spaces" => isset($agent['_spaces']) ? $agent['_spaces'] : "",
            "_projects" => isset($agent['_projects']) ? $agent['_projects'] : "",
            "_ownedOpportunities" => isset($agent['_ownedOpportunities']) ? $agent['_ownedOpportunities'] : "",
            "_events" => isset($agent['_events']) ? $agent['_events'] : "",
            "_relatedOpportunities" => isset($agent['_relatedOpportunities']) ? $agent['_relatedOpportunities'] : "",
            
            'terms' => [
                'area' => $this->getArea($agent['terms']['area'])
            ],
            
            'endereco' => isset($agent['endereco']) ? $agent['endereco'] : '',

        ];
        
    }

    private function getArea($areas){
        dump($areas);
        $newAreas = [];
        foreach($areas as $area){
            array_push($newAreas, $area);
        }
        dump($newAreas);
        return $areas;
    }

    private function setInformation($information, $position){
        $this->mapasObj[$position] = $information;
    }

    private function createAgent(){
        $newAgent = $this->mapas->createEntity('agent', $this->mapasObj);
    }

    private function clearMapaObject(){
        $this->mapasObj = [];
    }

    private function setMapas(){
        //dd($this->mapasApiConfig);
        $this->mapas = new MapasSDK\MapasSDK(
            $this->mapasApiConfig['host'],
            $this->mapasApiConfig['publicKey'],
            $this->mapasApiConfig['privateKey'],
            'HS256'
        );
    }
}