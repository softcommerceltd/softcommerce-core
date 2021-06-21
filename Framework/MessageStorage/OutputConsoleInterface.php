<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\MessageStorage;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface OutputConsoleInterface used to output
 * data to console log.
 */
interface OutputConsoleInterface
{
    public const LIMIT = 1000;

    /**
     * @param OutputInterface $output
     * @param array $data
     */
    public function execute(OutputInterface $output, array $data): void;
}
