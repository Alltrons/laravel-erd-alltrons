<?php

namespace Alltrons\LaravelERdModules\Commands;

use File;
use Illuminate\Console\Command;
use Kevincobain2000\LaravelERD\LaravelERD;

class LaravelErdModulesCommand extends Command
{
    public $signature = 'erd:generate {--m|modules=* : Defines which modules to be used when generating schema. None by default}';

    public $description = 'Generate ERD files';

    protected $laravelERD;

    public function __construct(LaravelERD $laravelERD)
    {
        $this->laravelERD = $laravelERD;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modules = $this->option('modules');
        $destinationPath = config('laravel-erd.docs_path') ?? base_path('docs/erd/');
        $modelsPath = config('laravel-erd.models_path') ?? base_path('modules/EstateManager/Entities');
        // extract data
        $linkDataArray = [];
        $nodeDataArray = [];
        array_push($nodeDataArray, $this->laravelERD->getNodeDataArray($modelsPath));
        array_push($linkDataArray, $this->laravelERD->getLinkDataArray($modelsPath));
        //dd($modules);
        foreach ($modules as $module) {
            $modulePath = base_path('modules/' . $module . '/Entities');
            array_push($nodeDataArray, $this->laravelERD->getNodeDataArray($modulePath));
            array_push($linkDataArray, $this->laravelERD->getLinkDataArray($modulePath));

        }

        $linkDataArray = array_merge(...$linkDataArray);
        $nodeDataArray = array_merge(...$nodeDataArray);

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        File::put($destinationPath . '/index.html',
            view('erd::index')
                ->with([
                    'routingType' => config('laravel-erd.display.routing') ?? 'AvoidsNodes',

                    // pretty print array to json
                    'docs' => json_encode(
                        [
                            "link_data" => $linkDataArray,
                            "node_data" => $nodeDataArray,
                        ]
                    ),
                ])
                ->render()
        );

        $this->info("ERD data written successfully to $destinationPath");

        return 0;
    }
}