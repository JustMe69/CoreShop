<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Component\Order\Processable;

use CoreShop\Component\Order\Model\OrderInterface;

interface ProcessableInterface
{
    public function getProcessableItems(OrderInterface $order): array;

    public function getProcessedItems(OrderInterface $order): array;

    public function isFullyProcessed(OrderInterface $order): bool;

    public function isProcessable(OrderInterface $order): bool;
}
