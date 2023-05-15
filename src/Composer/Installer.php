<?php

declare(strict_types=1);

namespace FilipeMglhs\MagentoDevelopmentInstallerPlugin\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

/**
 * Class Installer
 */
class Installer extends LibraryInstaller
{
    /** Extra Configuration ID */
    public const CONFIG_IDENTIFIER = 'development-components';

    /**
     * Verify if package is in extra development-components configuration
     *
     * @param PackageInterface $package
     *
     * @return bool
     */
    public function isDevelopment(PackageInterface $package): bool
    {
        $targetList = $this->composer->getPackage()
                ->getExtra()[self::CONFIG_IDENTIFIER] ?? [];

        return in_array($package->getName(), $targetList);
    }

    /**
     * Method Description
     *
     * @param PackageInterface $package
     *
     * @return string
     */
    public function buildPath(PackageInterface $package): string
    {
        $autoload = $package->getAutoload()['psr-4'][0] ?? false;
        return $autoload
            ? str_replace('\\', '/', $autoload)
            : $this->getInstallPath($package) ;
    }

    /**
     * @inheritDoc
     */
    public function prepare(
        $type,
        PackageInterface $package,
        PackageInterface $prevPackage = null
    ) {
        if ($this->isDevelopment($package)) {
            $package->setInstallationSource('source');
        }

        return parent::prepare($type, $package, $prevPackage);
    }

    /**
     * @inheritDoc
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->isDevelopment($package)
            ? $this->buildPath($package)
            : parent::getInstallPath($package);
    }

    /**
     * @inheritDoc
     */
    public function supports($packageType)
    {
        return $packageType === 'magento2-module';
    }
}
