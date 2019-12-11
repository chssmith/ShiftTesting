<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GenericCitizenship extends Model
{
    public static function matches_expected ($container, $attribute, $expected_value): bool {
      return !empty($container) && $container->$attribute == $expected_value;
    }
}
