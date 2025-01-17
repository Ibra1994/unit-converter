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

namespace UnitConverter\Unit;

use UnitConverter\Calculator\Formula\FormulaInterface;
use UnitConverter\Exception\BadUnit;

/**
 * This class is the base class for all unit of measurement classes.
 * When creating a new/custom unit of measure, extend from this class.
 *
 * @version 1.0.0
 * @since 0.0.1
 * @author Jordan Brauer <18744334+jordanbrauer@users.noreply.github.com>
 */
abstract class AbstractUnit implements UnitInterface
{
    /**
     * @var string|UnitInterface $base The units' base unit classname.
     */
    protected $base;

    /**
     * An array of formulae for this unit to convert itself with.
     *
     * @var array
     */
    protected $formulae;

    /**
     * @var string $name The units' full name.
     */
    protected $name;

    /**
     * @var string The scientific symbol of the unit
     */
    protected $scientificSymbol;

    /**
     * @var string $symbol The units' symbol notation (e.g., meter = m).
     */
    protected $symbol;

    /**
     * @var string $id The units' id (by default equal to symbol).
     */
    protected $id;

    /**
     * @var string $unitOf What is this unit measuring? Length, temperatutre, etc.
     */
    protected $unitOf;

    /**
     * @var float $units The amount of base units needed to make up 1 unit.
     */
    protected $units;

    /**
     * @var int|float|string The value of the unit (how many of it are measured).
     */
    protected $value;

    /**
     * Public constructor function for units of measurement.
     *
     * @param int|float|string $value The amount of units to be reprsented by the final object as.
     */
    public function __construct($value = 1)
    {
        $this->id = $this->symbol;
        $this->value = $value;
        $this->formulae = [];

        $this->configure();
    }

    public function addFormula(string $id, string $class): void
    {
        $this->formulae[$id] = $class;
    }

    public function addFormulae(array $formulae): void
    {
        foreach ($formulae as $id => $class) {
            $this->addFormula($id, $class);
        }
    }

    /**
     * Convenience method that converts the current unit to whatever unit is
     * passed in. Optionally supply precision & whether or not to use a binary
     * calculator (BCMath).
     *
     * @param UnitInterface $unit An instance of the unit being converted to.
     * @param integer $precision The precision to calculate to.
     * @param boolean $binary Are we using BCMath?
     * @return int|float|string
     */
    public function as(UnitInterface $unit, int $precision = null, bool $binary = false)
    {
        return \UnitConverter\UnitConverter::createBuilder()
            ->{'add'.(($binary) ? 'Binary' : 'Simple').'Calculator'}() # ¯\_(ツ)_/¯
            ->addRegistryWith(array_unique([$this, $unit], SORT_REGULAR))
            ->build()
            // ->disableConversionLog() # TODO: when this returns interface, uncomment!
            ->convert((string) $this->getValue(), $precision)
            ->from($this->getId())
            ->to($unit->getId());
    }

    public function getBase(): ?UnitInterface
    {
        return new $this->base();
    }

    public function getBaseUnits(): ?float
    {
        return $this->getBase()->getUnits();
    }

    public function getFormulaFor(UnitInterface $to): ?FormulaInterface
    {
        if (empty($this->formulae)) {
            return null;
        }

        $id = $to->getId();

        if (!array_key_exists($id, $this->formulae)) {
            throw BadUnit::formula($id);
        }

        return new $this->formulae[$id]();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRegistryKey(): ?string
    {
        return $this->unitOf.'.'.$this->getId();
    }

    public function getScientificSymbol(): ?string
    {
        return $this->scientificSymbol ?? $this->getSymbol();
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function getId(): ?string
    {
        return $this->id ?? $this->getSymbol();
    }

    public function getUnitOf(): ?string
    {
        return $this->unitOf;
    }

    public function getUnits(): ?float
    {
        return $this->units;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isMultipleSiUnit(): bool
    {
        return $this instanceof SiMultipleUnitInterface;
    }

    public function isSiUnit(): bool
    {
        return $this instanceof SiBaseUnitInterface;
    }

    public function isSubmultipleSiUnit(): bool
    {
        return $this instanceof SiSubmultipleUnitInterface;
    }

    public function setBase($base): UnitInterface
    {
        $this->base = $base;

        return $this;
    }

    public function setName(string $name): UnitInterface
    {
        $this->name = $name;

        return $this;
    }

    public function setScientificSymbol(string $scientificSymbol): UnitInterface
    {
        $this->scientificSymbol = $scientificSymbol;

        return $this;
    }

    public function setSymbol(string $symbol): UnitInterface
    {
        $this->symbol = $symbol;
        if (empty($this->getId())) {
            $this->setId($symbol);
        }

        return $this;
    }

    public function setId(string $id): UnitInterface
    {
        $this->id = $id;

        return $this;
    }

    public function setUnitOf(string $unitOf): UnitInterface
    {
        $this->unitOf = $unitOf;

        return $this;
    }

    public function setUnits(float $units): UnitInterface
    {
        $this->units = $units;

        return $this;
    }

    public function setValue($value): UnitInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Configure the current unit of measure.
     *
     * @return void
     */
    protected function configure(): void
    {
    }
}
