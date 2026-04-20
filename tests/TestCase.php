<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a fake Vite manifest so tests don't fail when views use @vite
        $buildDir = public_path('build');
        if (!is_dir($buildDir)) {
            mkdir($buildDir, 0755, true);
        }
        if (!file_exists($buildDir . '/manifest.json')) {
            file_put_contents($buildDir . '/manifest.json', json_encode([
                'resources/css/app.css' => ['file' => 'assets/app.css', 'src' => 'resources/css/app.css'],
                'resources/js/app.js' => ['file' => 'assets/app.js', 'src' => 'resources/js/app.js'],
            ]));
        }
    }
}
