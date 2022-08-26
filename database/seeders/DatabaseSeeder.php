<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Currency;
use App\Models\Pair;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    private const Currencies = [
        ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
        ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
        ['code' => 'GBP', 'name' => 'GB Pound', 'symbol' => '£'],
        ['code' => 'RON', 'name' => 'Romanian Leu', 'symbol' => 'lei'],
        ['code' => 'AUD', 'name' => 'Australian dollar', 'symbol' => '$'],
        ['code' => 'CZK', 'name' => 'Czech koruna', 'symbol' => 'Kč'],
        ['code' => 'DKK', 'name' => 'Danish krone', 'symbol' => 'kr'],
        ['code' => 'HUF', 'name' => 'forint', 'symbol' => 'Ft'],
        ['code' => 'HRK', 'name' => 'kuna', 'symbol' => 'kn'],
        ['code' => 'ILS', 'name' => 'shekel', 'symbol' => '₪'],
        ['code' => 'MYR', 'name' => 'ringgit', 'symbol' => 'RM'],
        ['code' => 'NZD', 'name' => 'New Zealand dollar', 'symbol' => '$'],
        ['code' => 'NOK', 'name' => 'Norwegian krone', 'symbol' => 'kr'],
        ['code' => 'PLN', 'name' => 'zloty', 'symbol' => 'zł'],
        ['code' => 'RSD', 'name' => 'Serbian dinar', 'symbol' => ''],
        ['code' => 'SGD', 'name' => 'Singapore dollar', 'symbol' => '$'],
        ['code' => 'SEK', 'name' => 'krona', 'symbol' => 'kr'],
        ['code' => 'CHF', 'name' => 'Swiss franc', 'symbol' => 'CHF'],
        ['code' => 'UAH', 'name' => 'hryvnia', 'symbol' => '₴'],
        ['code' => 'AED', 'name' => 'UAE dirham', 'symbol' => 'AED'],
    ];
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'name' => 'Philippe',
            'email' => 'philippe@gmail.com',
         ]);
         
        (new Collection(self::Currencies))
            ->when(! App::environment('testing'), fn($currencies) => $currencies
                ->each(fn ($currency) => Currency::create($currency)));
        Pair::factory(10)->create();

    }
}
