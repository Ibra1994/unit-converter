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

namespace UnitConverter\Unit\FuelEconomy;

use UnitConverter\Calculator\Formula\FuelEconomy\MilesPerGallonImperial\ToMilesPerLitre;
use UnitConverter\Calculator\Formula\FuelEconomy\MilesPerGallonImperial\ToKilometrePerLitre;
use UnitConverter\Calculator\Formula\FuelEconomy\MilesPerGallonImperial\ToLitrePer100Kilometres;
use UnitConverter\Calculator\Formula\FuelEconomy\MilesPerGallonImperial\ToMilesPerGallonUS;
use UnitConverter\Calculator\Formula\NullFormula;

/**
 * MilesPerGallon (US) unit data class.
 *
 * @version 1.0.0
 * @since 0.9.0
 */
class MilesPerGallonImperial extends FuelEconomyUnit
{
    protected function configure(): void
    {
        $this
            ->setName("miles per Imperial gallon")

            ->setSymbol("mpg")

            ->setId('mpg uk')

            ->setScientificSymbol('mpg<sub>Imp</sub>')

            ->addFormulae([
                'L/100km' => ToLitrePer100Kilometres::class,
                'km/l' => ToKilometrePerLitre::class,
                'mpg' => ToMilesPerGallonUS::class,
                'mi/l' => ToMilesPerLitre::class,
                'mpg uk' => NullFormula::class,
            ]);
    }
}
