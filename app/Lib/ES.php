<?php

namespace App\Lib;

use Elasticsearch\ClientBuilder;

class ES
{
    public static function init()
    {
        $client = ClientBuilder::create()->setHosts(['www.taylorswift.cloud:9200'])->build();
        $params = [
            'index' => 'article',
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'ik_max_word',
                            'search_analyzer' => 'ik_max_word'
                        ]
                    ]
                ]
            ]
        ];
        $response = $client->indices()->create($params);
        return $response;
    }

    public static function add($data)
    {
        $client = ClientBuilder::create()->setHosts(['www.taylorswift.cloud:9200'])->build();
        $res = $client->index($data);
        return $res;
    }

    public static function search($word)
    {
        if($word==null){
            return [];
        }
        $client = ClientBuilder::create()->setHosts(['www.taylorswift.cloud:9200'])->build();
        $params = [
            'index' => 'article',
            'body' => [
                'query' => [
                    'match' => [
                        'title' => $word
                    ]
                ],
                'highlight' => [
                    'pre_tags'=>["<span style='color: orangered'>"],
                    'post_tags'=>["<span/>"],
                    'fields'=>[
                        "title"=>new \stdClass()
                    ]
                ]
            ]
        ];
        $result=$client->search($params);
        foreach ($result['hits']['hits'] as &$v){
            $v['_course']['title']=$v['highlight']['title'][0];
        }
        $result=array_column($result['hits']['hits'],"_course");
        return $result;
    }
}
