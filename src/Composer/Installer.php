<?php

declare(strict_types=1);

namespace FilipeMglhs\MagentoDevelopmentInstallerPlugin\Composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use React\Promise\PromiseInterface;

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
     * @return string|null
     */
    public function getRegistration(PackageInterface $package): ?string
    {
        $extra = $this->composer->getPackage()->getExtra();
        return $extra[self::CONFIG_IDENTIFIER][$package->getName()] ?? null;
    }

    /**
     * Method Description
     *
     * @param PackageInterface $package
     *
     * @return string
     */
    public function pathBuilder(PackageInterface $package): string
    {
        $registration = $this->getRegistration($package);
        if (!$registration) {
            $this->getInstallPath($package);
        }

        $path = $this->getInstallPath($package);
        switch ($package->getType()) {
            case 'magento2-module':
                $path = "app/code/" . str_replace('_', '/', $registration);
                break;
            case 'magento2-theme':
                $path = "app/design/" . $registration;
                break;
            case 'magento2-language':
                $parts = explode('_', $registration);
                $vendor = array_shift($parts);
                $path = "app/i18n/" . $vendor . "/" . implode('_', $parts);
                break;
        }

        return $path;
    }

    /**
     * @inheritDoc
     */
    public function prepare(
        $type,
        PackageInterface $package,
        PackageInterface $prevPackage = null
    ): ?PromiseInterface {
        if ($this->getRegistration($package)) {
            $package->setInstallationSource('source');
        }

        return parent::prepare($type, $package, $prevPackage);
    }

    /**
     * @inheritDoc
     */
    public function getInstallPath(PackageInterface $package): string
    {
        return $this->pathBuilder($package);
    }

    /**
     * @inheritDoc
     */
    public function supports($packageType): bool
    {
        return in_array(
            $packageType,
            [
                'magento2-module',
                'magento2-theme',
                'magento2-language'
            ]
        );
    }
}
