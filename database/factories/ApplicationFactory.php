<?php

namespace Database\Factories;

use App\Models\Commune;
use App\Models\County;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rpw' => 'RPW/104294/2025',
            'data_wplywu' => fake()->date(),
            'nr_wniosku' => fake()->numberBetween(100,1000),
            'powiat_gmina' => fake()->randomElement(['P','G']),
            'urzad' => Commune::all()->random()->name,
            'gmina' => Commune::all()->random()->name,
            'powiat' => County::all()->random()->name,
            'ulica' => fake()->streetName,
            'nr_budynku' => fake()->numberBetween(1,10),
            'kod_pocztowy' => '88-888',
            'miejscowosc' => Commune::all()->random()->name,
            'task_id' => Task::all()->random()->id,
            'tresc' => fake()->sentence(),
            'schron_liczba' => 1,
            'schron_liczba_osob' => 2,
            'mds_liczba' => 3,
            'mds_liczba_osob' => 4,
            'ukrycie_liczba' => 5,
            'ukrycie_liczba_osob' => 5,
            'koszt_calkowity' => 250000.35,
            'wydatki_biezace' => 5000.0,
            'wydatki_inwestycyjne' => 5700.0,
            'dotacja_biezaca' => 2000,
            'dotacja_na_wydatki' => 900,
            'srodki_biezaca' => 1200,
            'srodki_inwestycyjne' => 2345,
            'dyspozycja' => 33,
            'dzial' => 752,
            'status' => 1,
            'kwota_umowy' => 5000.0,
            'nr_zad_umowy' => Task::all()->random()->numer,
            'typ_zadania' => 1,
            'uwagi' => fake()->sentence(),
            'wysylka_data' => fake()->date(),
            'wysylka_nr_pozycji' => 44,
            'zgoda_mswia' => 1,
            'user_id' => 1,
        ];
    }
}

