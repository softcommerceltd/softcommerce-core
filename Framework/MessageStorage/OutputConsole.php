<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\MessageStorage;

use Magento\Framework\Phrase;
use Plenty\Core\Framework\MessageStorageInterface;
use Plenty\Core\Model\Source\Status;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @inheritdoc
 */
class OutputConsole implements OutputConsoleInterface
{
    /**
     * @var StatusPredictionInterface
     */
    private $statusPrediction;

    /**
     * OutputConsole constructor.
     * @param StatusPredictionInterface $statusPrediction
     */
    public function __construct(StatusPredictionInterface $statusPrediction)
    {
        $this->statusPrediction = $statusPrediction;
    }

    /**
     * @param OutputInterface $output
     * @param array $data
     */
    public function execute(OutputInterface $output, array $data): void
    {
        foreach ($data as $items) {
            foreach ($items as $item) {
                if (!isset($item[MessageStorageInterface::ENTITY], $item[MessageStorageInterface::MESSAGE])) {
                    continue;
                }
                $output->writeln(
                    sprintf('Processing: <comment>%s</comment>', $item[MessageStorageInterface::ENTITY])
                );
                $output->writeln(
                    $this->getMessage(
                        $item[MessageStorageInterface::MESSAGE],
                        $item[MessageStorageInterface::STATUS] ?? $this->statusPrediction->execute($items)
                    )
                );
            }
        }
    }

    /**
     * @param $message
     * @param string $status
     * @return string
     */
    private function getMessage($message, string $status)
    {
        if ($message instanceof Phrase) {
            $message = $message->render();
        }

        switch ($status) {
            case Status::CRITICAL:
            case Status::ERROR:
            case Status::FAILED:
                $html = "<error> -- $message</error>";
                break;
            case Status::NOTICE:
            case Status::WARNING:
                $html = "<comment> -- $message</comment>";
                break;
            default:
                $html = "<info> -- $message</info>";
        }

        return $html;
    }
}
