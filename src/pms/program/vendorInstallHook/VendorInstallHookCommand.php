<?php

namespace pms\program\vendorInstallHook;

use pms\app\TerminalCommandApp;
use pms\facade\Path;

class VendorInstallHookCommand extends TerminalCommandApp
{
    protected string $name = "vendor:install:hook";
    protected array $validate = [];

    public function entry()
    {
        $path = Path::getRoot('vendor/composer/installed.json');
        if (file_exists($path)) {
            $info = json_decode(file_get_contents($path), true);
            if (array_key_exists('packages', $info)) {
                $packages = $info['packages'];
                foreach ($packages as $package) {
                    if (array_key_exists('extra', $package) && array_key_exists('pms', $package['extra'])) {
                        $pmsExtra = $package['extra']['pms'];
                        if (array_key_exists('config', $pmsExtra)) {
                            $config = $pmsExtra['config'];
                            $this->installConfig($config, $package);
                        }
                    }
                }
            }
        }
    }

    private function installConfig(array $config, array $package): void{
        $vendorRootPath = Path::getRoot('vendor/composer/', $package['install-path']);
        foreach ($config as $key => $value) {
            $path = Path::getConfig($key . '.php');
            if (!file_exists($path)) {
                $sourcePath = path_join($vendorRootPath, $value);
                copy($sourcePath, $path);

            }
        }
    }
}