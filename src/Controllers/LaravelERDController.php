<?php

namespace Kevincobain2000\LaravelERD\Controllers;

use Illuminate\Routing\Controller;
use Kevincobain2000\LaravelERD\LaravelERD;

class LaravelERDController extends Controller
{
    private $laravelERD;

    public function __construct(LaravelERD $laravelERD)
    {
        $this->laravelERD = $laravelERD;
    }

    public function index()
    {
        $modelsPath = config('laravel-erd.models_path') ?? base_path('app/Models');
        // extract data
        $linkDataArray = [];
        $nodeDataArray = [];
        array_push($nodeDataArray, $this->laravelERD->getNodeDataArray($modelsPath));
        array_push($linkDataArray, $this->laravelERD->getLinkDataArray($modelsPath));
        $modulesPath = config('laravel-erd.modules_path');
        //dd($modulesPath);
        foreach ($modulesPath as $modulePath) {
            array_push($nodeDataArray, $this->laravelERD->getNodeDataArray($modulePath));
            array_push($linkDataArray, $this->laravelERD->getLinkDataArray($modulePath));

        }

        $linkDataArray = array_merge(...$linkDataArray);
        $nodeDataArray = array_merge(...$nodeDataArray);

        return view('erd::index')->with([
            'routingType' => config('laravel-erd.display.routing') ?? 'AvoidsNodes',

            // pretty print array to json
            'docs' => json_encode(
                [
                    "link_data" => $linkDataArray,
                    "node_data" => $nodeDataArray,
                ]
            ),
        ]);
    }

}