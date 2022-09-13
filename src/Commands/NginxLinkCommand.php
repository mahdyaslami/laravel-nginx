<?php

namespace MahdiAslami\Laravel\Nginx\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * @codeCoverageIgnore
 */
#[AsCommand(name: 'nginx:link')]
class NginxLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'nginx:link
                {--relative : Create the symbolic link using relative paths}
                {--force : Recreate existing symbolic links}';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'nginx:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the symbolic links configured for the nginx';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $relative = $this->option('relative');

        foreach ($this->links() as $link => $target) {
            if (file_exists($link) && ! $this->isRemovableSymlink($link, $this->option('force'))) {
                $this->components->error("The [$link] link already exists.");
                continue;
            }

            if (is_link($link)) {
                $this->laravel->make('files')->delete($link);
            }

            if ($relative) {
                $this->laravel->make('files')->relativeLink($target, $link);
            } else {
                $this->laravel->make('files')->link($target, $link);
            }

            $this->components->info("The [$link] link has been connected to [$target].");
        }
    }

    /**
     * Get the symbolic links that are configured for the nginx.
     *
     * @return array
     */
    protected function links()
    {
        $filename = env('NGINX_FILENAME', 'lingo');

        return ["/etc/nginx/sites-enabled/$filename" => "/etc/nginx/sites-available/$filename"];
    }

    /**
     * Determine if the provided path is a symlink that can be removed.
     *
     * @param  string  $link
     * @param  bool  $force
     * @return bool
     */
    protected function isRemovableSymlink(string $link, bool $force): bool
    {
        return is_link($link) && $force;
    }
}
