<?php

namespace MahdiAslami\Laravel\Nginx\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'nginx:publish')]
class NginxPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nginx:publish 
                {--show : Just show the config}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish nginx config in nginx directory based on env variables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->perform();
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return 1;
        }

        return 0;
    }

    private function perform()
    {
        $content = $this->getStub();

        $variables = $this->prepareVariables($content);

        $content = Blade::render($content, $variables);

        if ($this->option('show')) {
            $this->line($content);
        } else {
            $this->save($content);
        }
    }

    private function getStub()
    {
        return file_get_contents(base_path('.nginx/config.stub'));
    }

    private function prepareVariables($content)
    {
        $variables = $this->findVariables($content);

        return $this->getDictionary($variables);
    }

    private function findVariables(string $content): array
    {
        $output = [];

        preg_match_all('/{{ \$([a-zA-Z0-9\_]*) }}/', $content, $output);

        return array_unique($output[1]);
    }

    private function getDictionary(array $variables)
    {
        $dictionary = [];

        foreach ($variables as $name) {
            $dictionary[$name] = $this->env($name);
        }

        return $dictionary;
    }

    private function env($name)
    {
        $key = 'NGINX_' . strtoupper($name);

        $value = env($key);

        if (is_null($value)) {
            throw new InvalidArgumentException("The {$key} key is required in environment.");
        }

        return $value;
    }

    private function save($content)
    {
        $filename = env('NGINX_FILENAME', 'site');

        file_put_contents("/etc/nginx/sites-available/$filename", $content);
    }
}
