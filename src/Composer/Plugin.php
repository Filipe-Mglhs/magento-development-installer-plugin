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

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

/**
 * Class Plugin
 */
class Plugin implements PluginInterface
{
    /**
     * Plugin constructor.
     *
     * @param Installer $installer
     */
    public function __construct(
        private readonly Installer $installer
    ) {
    }

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $composer->getInstallationManager()->addInstaller($this->installer);
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        $composer->getInstallationManager()->removeInstaller($this->installer);
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
