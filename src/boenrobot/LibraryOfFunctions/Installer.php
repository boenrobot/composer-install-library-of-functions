<?php

namespace boenrobot\LibraryOfFunctions;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class Installer extends LibraryInstaller
{
    /**
     * Tests whether the plugin supports the package type.
     *
     * @param string $packageType The Package to test.
     *
     * @return bool TRUE if the package type is supported, FALSE otherwise.
     */
    public function supports($packageType)
    {
        return 'library-of-functions' === $packageType;
    }
}
