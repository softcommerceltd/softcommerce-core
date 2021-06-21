<?php
/**
 * Copyright © Soft Commerce Ltd. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SoftCommerce\Core\Framework\DataStorage;

use Magento\Framework\Message\ManagerInterface;
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
     * @var OutputArrayInterface
     */
    private $outputArray;

    /**
     * @var OutputHtmlInterface
     */
    private $outputHtml;

    /**
     * OutputMessageManager constructor.
     * @param ManagerInterface $messageManager
     * @param OutputArrayInterface $outputArray
     * @param OutputHtmlInterface $outputHtml
     */
    public function __construct(
        ManagerInterface $messageManager,
        OutputArrayInterface $outputArray,
        OutputHtmlInterface $outputHtml
    ) {
        $this->messageManager = $messageManager;
        $this->outputArray = $outputArray;
        $this->outputHtml = $outputHtml;
    }

    /**
     * @param array $data
     * @param string|null $lineBreak
     */
    public function execute(array $data, ?string $lineBreak = null): void
    {
        foreach ($this->outputArray->execute($data) as $status => $items) {
            if (null !== $lineBreak) {
                $item = $this->outputHtml->execute(
                    $items,
                    [
                        OutputHtmlInterface::LINE_BREAK => $lineBreak,
                    ]
                );
                $this->addMessage($item, $status);
                continue;
            }

            foreach ($items as $item) {
                $this->addMessage($item, $status);
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
