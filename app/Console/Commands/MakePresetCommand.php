<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakePresetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:preset {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The namespace of the preset.
     *
     * @var string
     */
    protected $namespace = 'App\Presets';

    /**
     * Create a new file.
     *
     * @param string $namespace
     * @param string $name
     * @param string $stub
     */
    protected function createFile(string $namespace, string $name, string $stub): void
    {
        // Make path to support sub directories
        $dir = str_replace($this->namespace, '', $namespace);
        $dir = app_path('Presets' . str_replace('/', '\\', $dir));

        // Create the directory if it does not exist
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $path = $dir . '\\' . $name . 'Policy.php';

        if (file_exists($path)) {
            $this->error('Preset already exists!');

            exit(1);
        }

        $preset = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, $name.'Policy'], $stub);

        file_put_contents($path, $preset);

        $this->info('Preset created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return file_get_contents(base_path() . "/stubs/preset.stub");
    }

    /**
     * Create a new preset class.
     *
     * @param string $name
     */
    protected function createPreset(string $name): void
    {
        // If name already has the word policy, remove it
        if (str_contains($name, 'Policy')) {
            $name = str_replace('Policy', '', $name);
        }

        $namespace = $this->namespace;

        // if name had sub directories, remove them and add them to the namespace
        if (str_contains($name, '/')) {
            $name = str_replace('/', '\\', $name);
            $namespace .= '\\' . str_replace('/', '\\', dirname($name));
            $name = basename($name);
        }

        $stub = $this->getStub();

        $this->createFile($namespace, $name, $stub);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->createPreset($name);
    }
}
