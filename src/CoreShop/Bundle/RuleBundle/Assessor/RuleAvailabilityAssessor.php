<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\RuleBundle\Assessor;

use Carbon\Carbon;
use CoreShop\Component\Rule\Condition\Assessor\RuleAvailabilityAssessorInterface;
use CoreShop\Component\Rule\Model\Condition;
use CoreShop\Component\Rule\Model\RuleInterface;
use CoreShop\Component\Rule\Repository\RuleRepositoryInterface;

final class RuleAvailabilityAssessor implements RuleAvailabilityAssessorInterface
{
    /**
     * @var RuleRepositoryInterface
     */
    protected $ruleRepository;

    /**
     * RuleAvailabilityAssessor constructor.
     *
     * @param RuleRepositoryInterface $ruleRepository
     */
    public function __construct(RuleRepositoryInterface $ruleRepository)
    {
        $this->ruleRepository = $ruleRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getRules()
    {
        return $this->ruleRepository->findBy(['active' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(RuleInterface $rule)
    {
        $valid = true;

        /** @var Condition $condition */
        foreach ($rule->getConditions() as $id => $condition) {

            if ($condition->getType() !== 'timespan') {
                continue;
            }

            $configuration = $condition->getConfiguration();
            $dateFrom = Carbon::createFromTimestamp($configuration['dateFrom'] / 1000);
            $dateTo = Carbon::createFromTimestamp($configuration['dateTo'] / 1000);

            $date = Carbon::now();

            // future rule is also valid
            if ($configuration['dateFrom'] > 0) {
                if ($dateFrom->getTimestamp() > $date->getTimestamp()) {
                    continue;
                }
            }

            if ($configuration['dateFrom'] > 0) {
                if ($date->getTimestamp() < $dateFrom->getTimestamp()) {
                    $valid = false;
                    break;
                }
            }

            if ($configuration['dateTo'] > 0) {
                if ($date->getTimestamp() > $dateTo->getTimestamp()) {
                    $valid = false;
                    break;
                }
            }

        }

        return $valid;
    }
}