<?php
namespace Enum;

enum TemperatureUnitType: string {
    case Imperial = "fahrenheit";
    case Metric = "celcius";
    case Standard = "kelvin";
}
