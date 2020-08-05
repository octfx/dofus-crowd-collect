<?php

namespace App\Console\Commands;

use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;

class ImportResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:resources {--update}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports json resources into the db. Add --update to also update each existing resource.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $fileContent = File::get(resource_path('data/resource.json'));
        } catch (FileNotFoundException $e) {
            $this->error("Could not find file 'resource.json' in resources/data folder.");
            return;
        }

        $parsed = json_decode($fileContent, true);

        $this->info("Adding resources to the database");

        $bar = $this->output->createProgressBar(count($parsed));

        foreach ($parsed as $item) {
            $type = ResourceType::firstOrCreate(['name' => $item['type']]);

            /** @var Resource $resource */
            $resource = Resource::firstOrCreate([
                'name' => $item['name']
            ], [
                'type_id' => $type->id,
                'level' => $item['level'],
                'description' => $item['description'],
                'img_url' => $item['imgUrl'],
                'url' => $item['url'],
            ]);

            if (!$resource->wasRecentlyCreated && $this->hasOption('update')) {
                $resource->update([
                    'type_id' => $type->id,
                    'level' => $item['level'],
                    'description' => $item['description'],
                    'img_url' => $item['imgUrl'],
                    'url' => $item['url'],
                ]);
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info("\nAll done.");
    }
}
