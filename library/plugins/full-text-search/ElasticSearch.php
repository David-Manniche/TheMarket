<?php

require_once CONF_INSTALLATION_PATH . 'library/elasticsearch/vendor/autoload.php';

use Elasticsearch\ClientBuilder;

class ElasticSearch extends FullTextSearchBase
{
    private $client;
    private $indexName;
    public $error = false;
    const KEY_NAME = "ElasticSearch";
    const INDEX_PREFIX = "yk-products-";

    /* Creating ElasticSearch Connection
    *
    *  @indexName - Pass Index Name you Want to Create in Elasticsearch
    */
    public function __construct($langId)
    {
        $this->indexName = self::INDEX_PREFIX . $langId;
        $settings = $this->getSettings();
        $this->langId = $langId;
        
        $reqKeys = array('host', 'username', 'password');
        
        foreach ($reqKeys as $key) {
            if (!array_key_exists($key, $settings)) {
                $this->error = Labels::getLabel('MSG_SETTINGS_NOT_UPDATED', $this->langId);
                return false;
            }
        }
       
        $this->client = ClientBuilder::create()
             ->setElasticCloudId($settings['host'])
             ->setBasicAuthentication($settings['username'], $settings['password'])
             ->build();
    }
    
    /* Parameter Name
    *	@queryData => Pass the query data
    *	@source => Pass the Fields you want to select
    *	@groupByField => Pass the Groupby Field in string
    *	@sort => Sorting Field array
    *   @aggregation => performing text based aggregation get min and max price in query
    *	@from => same as offset field in mysql
    *	@size => same as limit field in mysql
    */

    public function search($queryData, $from, $size, $aggregation = false, $source = array(), $groupByField = null, $sort = array())
    {
        $result = array();
        $params = [
            'index' => $this->indexName,
            'body'  => [
                "_source" => $source,
                'query' => [
                    'bool' => $queryData,
                ],
                //'min_score'=>5,
                'sort' => $sort,
                'from' => $from,
                'size' => $size,
            ]
        ];
        if (isset($groupByField) && !empty($groupByField)) {
            $params['body']['collapse'] = [ "field" => $groupByField . '.keyword'];
        }
        if ($aggregation) {
            $params['body']['aggregations'] = [
                'min_price' => [ 'min' => ['field' => 'general.theprice' ] ],
                'max_price' => [ 'max' => ['field' => 'general.theprice' ] ]
            ];
        }
        try {
            $results = $this->client->search($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        
        if ($aggregation) {
            return $results;
        }
        return array_key_exists('hits', $results) ? $results['hits'] : $results;
    }
    

    /*	Creating Index into the ElasticSearch Host
    *
    */

    public function createIndex()
    {
        if ($this->isIndexExists()) {
            $this->error = Labels::getLabel('MSG_INDEX_ALREADY_EXISTS', $this->langId);
            return false;
        }
        $language = Language::getAttributesById($this->langId, "language_name");
        if (empty($language)) {
            $this->error = Labels::getLabel('MSG_NO_RECORD_FOUND', $this->langId);
            return false;
        }
        $params = [
            'index'  =>  $this->indexName,
            'body'   =>  [
                'settings' => [
                    'analysis' =>[
                        'filter' => [
                            mb_strtolower($language) . "_stop" => [ "type" => "stop","stopwords" => "_" . mb_strtolower($language) . "_"],
                            mb_strtolower($language) . "_stemmer"  => [ "type" => "stemmer", "language" => mb_strtolower($language) ]
                        ],
                        "analyzer" => [
                            "rebuilt_" . mb_strtolower($language) => [
                                "tokenizer" => "standard",
                                "filter"  => [ "lowercase", "decimal_digit", mb_strtolower($language) . "_stop", mb_strtolower($language) . "_stemmer", "snowball"]
                            ]
                        ]
                    ]
                ]
             ]
          ];

        // index name
        switch ($this->langId) {
            case '1':
                $arr = [
                        'type' => 'stemmer',
                        'language' => 'possessive_' . mb_strtolower($language)
                ];
            $params['body']['settings']['analysis']['filter'][ mb_strtolower($language) . '_possessive_stemmer'] = $arr;
            array_push($params['body']['settings']['analysis']['analyzer']["rebuilt_" . mb_strtolower($language) ]["filter"], mb_strtolower($language) . "_possessive_stemmer");
            break;
        }
                
        try {
            $response = $this->client->indices()->create($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }

    /*	Deleting Index in ElasticSearch Host
    *
    */
    public function deleteIndex()
    {
        $params = ['index' => $this->indexName];
        try {
            $response = $this->client->indices()->delete($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }
    
    /*	Adding Data to the ElasticSearch Index
    *
    *   @id  -  Pass Unique document Id
    *   @data - Pass Data array
    */

    public function createDocument($documentId, $data)
    {
        $params = [
            'index' => $this->indexName,
            'type' => 'data',
            'id' =>  $documentId,
            'body' => $data
        ];

        try {
            $response = $this->client->index($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }

    /*	Deleting Data into the ElasticSearch Index
    *
    *	@documentId  - Pass Unique document Id
    *
    */

    public function deleteDocument($documentId)
    {
        $params = [
            'index' => $this->indexName,
            'id'    => $documentId
        ];
        try {
            $response = $this->client->delete($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }
    
    /*	Updating Main Document Data To The ElasticSearch Index
    *
    *   @id  -  Pass Unique document Id
    *   @data - Pass Data array
    */
    public function updateDocument($documentId, $data)
    {
        $params = [
            'index' => $this->indexName,
            'id'    => $documentId,
            'body'  => [
                'doc' => $data
            ]
        ];
        try {
            $response = $this->client->update($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }

    /*	Searching By Id into the ElasticSearch Index
    *
    *   @documentId  -  Pass Unique document Id
    *
    */

    public function isDocumentExists($documentId)
    {
        $params = [
            'index' => $this->indexName,
            'id' => $documentId
        ];
        try {
            $response = $this->client->get($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }
    
    public function isIndexExists()
    {
        $params = [
            'index' => $this->indexName
        ];
        try {
            $response = $this->client->indices()->exists($params);
            if (!$response) {
                return false;
            }
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }
    
    /*	Updating Nested Data Into The ElasticSearch Index
    *
    *   @documentId     - Pass Unique Id document id
    *   @dataIndexName  - Pass data index name where you want to push(like inventory)
    *   @dataIndexArray - Pass data in array with key name and value name like in case of inventory ('selprod_id' => value);
    *   @data           - Pass the Data Parameters
    */

    public function updateDocumentData($documentId, $dataIndexName, $dataIndexArray, $data)
    {
        $response = $this->deleteDocumentData($documentId, $dataIndexName, $dataIndexArray);
        if (!$response) {
            return false;
        }

        $params = [
            'index' => $this->indexName,
            'id'    => $documentId,
            'body'  => [
                'script' => array(
                    "source" => "ctx._source." . $dataIndexName . ".add(params." . $dataIndexName . ")",
                    "params" => $data
                )
            ]
        ];
        try {
            $response = $this->client->update($params);
        } catch (exception $e) {
            $this->setErrorMessage($e);
            return false;
        }
        return true;
    }

    /*	Delete Nested Data Into The ElasticSearch Index
    *
    *   @documentId  - Pass Unique document Id
    *   @data - Pass Data array with selprod_id
    *   @dataIndexArray - Pass the array with keyname and value
    */

    public function deleteDocumentData($documentId, $dataIndexName, $dataIndexArray)
    {
        if (!is_array($dataIndexArray)) {
            return false;
        }
        $dataIndexColumnName = array_key_first($dataIndexArray);

        $params = [
            'index' => $this->indexName,
            'id'    => $documentId,
            'body'  => [
                'script' => array(
                    "source" => "ctx._source.".$dataIndexName.".removeIf(data -> data.".$dataIndexColumnName." == params.".$dataIndexColumnName.")",
                    "params" => $dataIndexArray
                )
            ]
        ];

        try {
            $response = $this->client->update($params);
        } catch (exception $e) {
            //$this->error = $e->getMessage();
            return true; // sending true because in case of document data not exists then we are pushing  if exits then delete the document data and then pushing
        }
        return true;
    }

    public function getError()
    {
        return $this->error;
    }
    
    private function setErrorMessage($e)
    {
        $error = json_decode($e->getMessage(), true);
        $this->error = isset($error['error']['reason']) ? $error['error']['reason'] : "error";
    }
}
