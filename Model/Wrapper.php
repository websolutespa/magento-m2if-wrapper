<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\M2ifWrapper\Model;

use Exception;
use Magento\Framework\Filesystem\DirectoryList;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use TechDivision\Import\Cli\Console\ArgvInput;
use TechDivision\Import\Cli\Utils\DependencyInjectionKeys;

class Wrapper
{
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @param DirectoryList $directoryList
     */
    public function __construct(
        DirectoryList $directoryList
    ) {
        $this->directoryList = $directoryList;
    }

    /**
     * @param array $parameters
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    public function execute(array $parameters, OutputInterface $output): int
    {
        $magentoRootDirPath = $this->directoryList->getRoot();
        $vendorDirPath = implode(DIRECTORY_SEPARATOR, [
            $magentoRootDirPath,
            'vendor'
        ]);
        $baseDirPath = implode(DIRECTORY_SEPARATOR, [
            $vendorDirPath,
            'techdivision',
            'import-cli-simple'
        ]);
        $techdivisionServiceFilePath = implode(DIRECTORY_SEPARATOR, [
            $vendorDirPath,
            'techdivision',
            'import-cli',
            'symfony',
            'Resources',
            'config',
            'services.xml'
        ]);

        $container = new ContainerBuilder();
        $container->setParameter(DependencyInjectionKeys::CONFIGURATION_BASE_DIR, $baseDirPath);
        $container->setParameter(DependencyInjectionKeys::CONFIGURATION_VENDOR_DIR, $vendorDirPath);

        $defaultLoader = new XmlFileLoader($container, new FileLocator($vendorDirPath));
        $defaultLoader->load($techdivisionServiceFilePath);

        $application = $container->get(DependencyInjectionKeys::APPLICATION);
        $application->setAutoexit(false);

        /** @var ArgvInput $input */
        $input = $container->get(DependencyInjectionKeys::INPUT);
        $input->setTokens($parameters);

        return $application->run($input, $output);
    }
}
