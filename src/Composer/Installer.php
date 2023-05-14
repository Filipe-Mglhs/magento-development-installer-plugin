<?php

/**
 * PHP version 8
 *
 * @author      Webjump Core Team <dev@webjump.com.br>
 * @copyright   2023 Webjump (https://www.webjump.com.br)
 * @license     https://www.webjump.com.br Copyright
 * @link        https://www.webjump.com.br
 */

declare(strict_types=1);

namespace FilipeMglhs\MagentoDevelopmentInstallerPlugin\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Class Installer
 */
class Installer extends LibraryInstaller
{
    /**
     * @inheritDoc
     */
    public function getInstallPath(PackageInterface $package)
    {
        $name = $package->getName();
        $composerPackage = $this->composer->getPackage();
        if ($composerPackage) {
            $extra = $composerPackage->getExtra();
            $installPaths = $extra['installer-paths'] ?? [];
            foreach ($installPaths as $path => $names) {
                $names = (array) $names;
                if (in_array($name, $names)) {
                    return $path;
                }
            }
        }

        return parent::getInstallPath($package);
    }
}
