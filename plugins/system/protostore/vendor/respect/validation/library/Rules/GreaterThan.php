<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Rules;

/**
 * Validates whether the input is less than a value.
 *
 * @author Henrique Moody <henriquemoody@gmail.com>
 */
final class GreaterThan extends AbstractComparison
{
    /**
     * {@inheritDoc}
     */
    protected function compare($left, $right): bool
    {
        return $left > $right;
    }
}
