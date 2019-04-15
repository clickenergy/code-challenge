<?php

namespace App\Api;

use GuzzleHttp\Client;

class MyApiClient
{
    public $baseUrl;
    public $token;

    public function __construct($token)
    {
        $this->baseUrl = env('API_BASEURL');
        $this->token = $token;
    }
    
    public function get($query, $column, $params = null)
    {
        $client = new Client();
        $path = $this->baseUrl.$query;

        if (! is_null($params)) {
            $querys = array_merge($querys, $params);
        }

        $result = $client->request('GET', $path, [
            'headers' => ['Authorization' => 'Bearer ' , $this->token],
            'query' => $querys,
        ]);
        
        $res = json_decode($result->getBody()->getContents(), true);

        return $res[$column];
    }
    
    public function post($query, $value)
    {
        $client = new Client();
        $path = $this->baseUrl.$query;

        $result = $client->request('POST', $path, [
            'headers' => ['Authorization' => 'Bearer ' , $this->token],
            'json' => $value,
        ]);
    
        return json_decode($result->getBody()->getContents(), true);
    }

    public function put($query, $value)
    {
        $client = new Client();
        $path = $this->baseUrl.$query;
      
        $result = $client->request('PUT', $path, [
                'headers' => ['Authorization' => 'Bearer ' , $this->token],
                'json' => $value,
            ]);
        
        return json_decode($result->getBody()->getContents(), true);
    }

    public function delete($query)
    {
        $client = new Client();
        $path = $this->baseUrl.$query;

        $result = $client->request('DELETE', $path, [
            'headers' => ['Authorization' => 'Bearer ' , $this->token],
        ]);
        
        return json_decode($result->getBody()->getContents(), true);
    }

    public function getAll($query, $column, array $params = null)
    {
        $client = new Client();

        $page = 1;
        $path = $this->baseUrl.$query;
        $my_arr = array();

        do {
            $query = [
                'page' => $page,
            ];
    
            if (! is_null($params)) {
                $query = array_merge($query, $params);
            }

            $result = $client->request('GET', $path, [
                'headers' => ['Authorization' => 'Bearer ' , $this->token],
                'query' => $query,
            ]);
            
            $res = json_decode($result->getBody()->getContents(), true);
 
            if (empty($res[$column])) {
                break;
            }

            foreach ($res[$column] as $item) {
                $my_arr[] = $item;
            }
            
            $page++;
        } while (1);

        return $my_arr;
    }
}
