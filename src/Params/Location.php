<?php

namespace ApiQueryParser\Params;

class Location implements LocationInterface
{
    /**
     * @var
     */
    protected $latitudeField;

    /**
     * @var string
     */
    protected $longitudeField;

    /**
     * @var float
     */
    protected $latitudeValue;

    /**
     * @var float
     */
    protected $longitudeValue;

    /**
     * @var float
     */
    protected $radiusValue;

    /**
     * Location constructor.
     *
     * @param string $latitudeField
     * @param string $longitudeField
     * @param float  $latitudeValue
     * @param float  $longitudeValue
     * @param float  $radiusValue
     */
    public function __construct(
        string $latitudeField,
        string $longitudeField,
        float $latitudeValue,
        float $longitudeValue,
        float $radiusValue
    ) {
        $this->setLatitudeField($latitudeField);
        $this->setLongitudeField($longitudeField);
        $this->setLatitudeValue($latitudeValue);
        $this->setLongitudeValue($longitudeValue);
        $this->setRadiusValue($radiusValue);
    }

    public function setLatitudeField(string $latitudeField): void
    {
        $this->latitudeField = $latitudeField;
    }

    public function getLatitudeField(): string
    {
        return $this->latitudeField;
    }

    public function setLongitudeField(string $longitudeField): void
    {
        $this->longitudeField = $longitudeField;
    }

    public function getLongitudeField(): string
    {
        return $this->longitudeField;
    }

    public function getLatitudeValue(): float
    {
        return $this->latitudeValue;
    }

    public function setLatitudeValue(float $latitudeValue): void
    {
        $this->latitudeValue = $latitudeValue;
    }

    public function getLongitudeValue(): float
    {
        return $this->longitudeValue;
    }

    public function setLongitudeValue(float $longitudeValue): void
    {
        $this->longitudeValue = $longitudeValue;
    }

    public function getRadiusValue(): float
    {
        return $this->radiusValue;
    }

    public function setRadiusValue(float $radiusValue): void
    {
        $this->radiusValue = $radiusValue;
    }
}
