<?php

/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

declare(strict_types=1);

namespace Respect\Validation\Exceptions;

/**
 * @author Henrique Moody <henriquemoody@gmail.com>
 * @author Danilo Correa <danilosilva87@gmail.com>
 * @author Kleber Hamada Sato <kleberhs007@yahoo.com>
 */
final class ConsonantException extends FilteredValidationException
{
    /**
     * {@inheritDoc}
     */
    protected $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} must contain only consonants',
            self::EXTRA => '{{name}} must contain only consonants and {{additionalChars}}',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} must not contain consonants',
            self::EXTRA => '{{name}} must not contain consonants or {{additionalChars}}',
        ],
    ];
}
