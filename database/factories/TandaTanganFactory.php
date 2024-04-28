<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TandaTanganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ttd_kepala' => 'data:image/png;base64,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',
            'ttd_admin' => 'data:image/png;base64,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'
        ];
    }
}