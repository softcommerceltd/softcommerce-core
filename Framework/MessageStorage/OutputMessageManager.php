<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\MessageStorage;

use Magento\Framework\Message\ManagerInterface;
use Plenty\Core\Framework\MessageStorageInterface;
use Plenty\Core\Model\Source\Status;

/**
 * @inheritdoc
 */
class OutputMessageManager implements OutputMessageManagerInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var OutputHtmlInterface
     */
    private $outputHtml;

    /**
     * @var StatusPredictionInterface
     */
    private $statusPrediction;

    /**
     * OutputMessageManager constructor.
     * @param ManagerInterface $messageManager
     * @param OutputHtmlInterface $outputHtml
     * @param StatusPredictionInterface $statusPrediction
     */
    public function __construct(
        ManagerInterface $messageManager,
        OutputHtmlInterface $outputHtml,
        StatusPredictionInterface $statusPrediction
    ) {
        $this->messageManager = $messageManager;
        $this->outputHtml = $outputHtml;
        $this->statusPrediction = $statusPrediction;
    }

    /**
     * @param array $data
     * @param string|null $lineBreak
     */
    public function execute(array $data, ?string $lineBreak = null): void
    {
        foreach ($data as $items) {
            if (null !== $lineBreak) {
                $item = $this->outputHtml->execute(
                    $items,
                    [
                        OutputHtmlInterface::LINE_BREAK => $lineBreak,
                    ]
                );
                $status = $this->statusPrediction->execute($items);
                $this->addMessage($item, $status);
                continue;
            }

            foreach ($items as $item) {
                if (!isset($item[MessageStorageInterface::MESSAGE])) {
                    continue;
                }
                $status = $item[MessageStorageInterface::STATUS] ?? $this->statusPrediction->execute($items);
                $this->addMessage($item[MessageStorageInterface::MESSAGE], $status);
            }
        }
    }

    /**
     * @param $message
     * @param string $status
     */
    private function addMessage($message, string $status): void
    {
        switch ($status) {
            case Status::CRITICAL:
            case Status::ERROR:
            case Status::FAILED:
                $this->messageManager->addErrorMessage($message);
                break;
            case Status::WARNING:
                $this->messageManager->addWarningMessage($message);
                break;
            case Status::NOTICE:
                $this->messageManager->addNoticeMessage($message);
                break;
            default:
                $this->messageManager->addSuccessMessage($message);
        }
    }
}
