<?php

declare(strict_types = 1);

/**
 * This file is part of the jordanbrauer/unit-converter PHP package.
 *
 * @copyright 2018 Jordan Brauer <18744334+jordanbrauer@users.noreply.github.com>
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitConverter\Calculator\Formula\FuelEconomy\MilesPerGallonUS;

use UnitConverter\Calculator\Formula\AbstractFormula;

/**
 * Formula to convert Kilometre Per Litre values to Miles Per Gallon.
 *
 * @version 1.0.0
 * @author Maksim Martianov <7222812+maksimru@users.noreply.github.com>
 */
class ToMilesPerLitre extends AbstractFormula
{

    const MAGIC_NUMBER = 0.264172;

    const FORMULA_STRING = 'mi/l = 0.264172 * mpg(US)';

    const FORMULA_TEMPLATE = '%s mi/l = 0.264172 * %smpg(US)';

    /**
     * {@inheritDoc}
     */
    public function describe($value, $fromUnits, $toUnits, int $precision = null)
    {
        // XXX: this formula assumes all calculators can accept strings, as it's the safest type.
        $addResult = $this->calculator->mul(self::MAGIC_NUMBER, $value);
        $result = $this->calculator->round($addResult, $precision);

        $this->plugVariables($result, $value);

        return $result;
    }
}
