<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImportResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports json resources into the db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $fileContent = File::get(resource_path('data/resource.json'));
        } catch (FileException $e) {
            $this->error("Could not find file 'resource.json' in resources/data folder.");
            return;
        }

        $parsed = json_decode($fileContent, true);

        $this->info("Adding resources to the database");

        $bar = $this->output->createProgressBar(count($parsed));

        foreach ($parsed as $item) {
            $type = ResourceType::firstOrCreate(['name' => $item['type']]);

            Resource::firstOrCreate([
                'name' => $item['name']
            ], [
                'level' => $item['level'],
                'description' => $item['description'],
                'img_url' => $item['imgUrl'],
                'type_id' => $type->id
            ]);

            $bar->advance();
        }

        $bar->finish();

        $this->info("\nAll done.");
    }
}
