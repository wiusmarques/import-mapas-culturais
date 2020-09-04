<?php

/*
* Tabelas Mapeadas
* usr, agent_meta
*/

return [

    /*
    * Relacionando as colunas do CSV com as tabelas corretas
    */

    'usr' => [
        1 => [
            'collumn' => 'email'
        ]
    ],

    'agent_meta' => [
        
        0 => [
            /*
             * Carimbo de data/hora
             */
            'inputName' => 'recordDate',
        ]
    ],
    


];

?>