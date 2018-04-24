<?php

namespace boenrobot\Composer\FunctionHandler;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class Installer extends LibraryInstaller
{

    /**
     * Handles the "functionmap" section of the "extra" section.
     *
     * Each key is the name of an autoload section
     * (either "autoload" or "autoload-dev"). Each value is an object.
     *
     * Within the object, each key is the fully qualified name of a function,
     * and each value is the location of a file that defines it.
     * If the function isn't already defined, the file will be added to the
     * "files" section.
     * If the function is already defined, the file will be removed from the
     * "files" section if it exists.
     *
     * @param PackageInterface $package The package to handle.
     *
     * @return void
     */
    protected function handleFunctionmap(PackageInterface $package)
    {
        $extraData = $package->getExtra();
        if (isset($extraData['functionmap'])) {
            foreach (array(
                'autoload' => 'Autoload',
                'autoload-dev' => 'DevAutoload'
            ) as $section => $autoloader) {
                if (!isset($extraData['functionmap'][$section])) {
                    continue;
                }
                $autoloadConfig = $package->{'get' . $autoloader}();
                if (!isset($autoloadConfig['files']) || !is_array($autoloadConfig['files'])) {
                    $autoloadConfig['files'] = array();
                }
                foreach ($extraData['functionmap'][$section] as $function => $file) {
                    if (function_exists($function)) {
                        $filesPos = array_search(
                            $file,
                            $autoloadConfig['files'],
                            true
                        );
                        if (false !== $filesPos) {
                            unset($autoloadConfig['files'][$filesPos]);
                        }
                        continue;
                    }
                    $autoloadConfig['files'][] = $file;
                }
                $autoloadConfig['files'] = array_unique($autoloadConfig['files']);
                if (empty($autoloadConfig['files'])) {
                    unset($autoloadConfig['files']);
                }
                $package->{'set' . $autoloader}($autoloadConfig);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->handleFunctionmap($package);
        parent::install($repo, $package);
    }

    /**
     * @inheritDoc
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $this->handleFunctionmap($target);
        parent::update($repo, $initial, $target);
    }
}
