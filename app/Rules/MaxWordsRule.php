<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxWordsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $max_words;

    public function __construct($max_words)
    {
        $this->max_words = $max_words;
    }


    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $words = explode(' ', $value);

        $nbWords = count($words);
          if($nbWords == $this->max_words){
               return $nbWords;
          }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot be longer than ' . $this->max_words . ' words.';

    }
}
