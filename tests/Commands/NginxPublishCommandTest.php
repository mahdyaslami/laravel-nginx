<?php

namespace Tests\Commands;

use Tests\TestCase;

/**
 * @covers \MahdiAslami\Laravel\Nginx\Commands\NginxPublishCommand
 */
class NginxPublishCommandTest extends TestCase
{
    public static $data;

    public static $path;

    public function setUp(): void
    {
        parent::setUp();

        static::$data = '';
        static::$path = '';
    }

    public function test_copy_config_to_sites_available()
    {
        $_SERVER['NGINX_VARIABLE'] = 'good-value';

        $this->artisan('nginx:publish')
            ->assertSuccessful();

        $this->assertEquals(
            '/etc/nginx/sites-available/lingo',
            static::$path
        );

        $this->assertEquals(
            'good-value',
            static::$data
        );
    }

    public function test_show_config()
    {
        $this->withoutExceptionHandling();
        $_SERVER['NGINX_VARIABLE'] = 'good-value';

        $this->artisan('nginx:publish --show')
            ->assertSuccessful()
            ->expectsOutput('good-value');

        $this->assertEquals(
            '',
            static::$path,
            '--show option must not copy config to nginx directory.'
        );
    }

    public function test_variables_should_exists_in_env()
    {
        unset($_SERVER['NGINX_VARIABLE']);

        $this->artisan('nginx:publish --show')
            ->assertFailed();
    }

    public static function fileGetContents()
    {
        return '{{ $variable }}';
    }

    public static function filePutContents($path, $data)
    {
        static::$path = $path;
        static::$data = $data;
    }
}

namespace MahdiAslami\Laravel\Nginx\Commands;

use Tests\Commands\NginxPublishCommandTest;

function file_get_contents($path)
{
    return NginxPublishCommandTest::fileGetContents();
}

function file_put_contents($path, $data)
{
    NginxPublishCommandTest::filePutContents($path, $data);
}
