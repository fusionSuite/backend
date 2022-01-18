<?php

namespace App\v1;

use Rakit\Validation\Rule;

class ValidatorType extends Rule
{
  protected $message = "The :attribute is not valid type";

  protected $fillableParams = ['type'];

  public function check($value): bool
  {
    // make sure required parameters exists
    $this->requireParameters(['type']);

    // getting parameters
    $type = $this->parameter('type');

    // true for valid, false for invalid
    return gettype($value) === $type;
  }
}