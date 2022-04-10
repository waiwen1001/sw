<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\customer;
use App\purchase_transaction;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('customers')->truncate();
        \DB::table('purchase_transaction')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();
        $genders = ['Male', 'Female'];

        for($a = 0; $a < 2000; $a++)
        {
          $contact_number = 70000000 + ($a * 100);
          $first_name = $faker->firstName;
          $last_name = $faker->lastName;

          $customer = customer::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'gender' => $genders[rand(0,1)],
            'date_of_birth' => $faker->dateTimeBetween('1960-01-01', '2021-12-31')->format('Y-m-d'),
            'contact_number' => $contact_number,
            'email' => $first_name."_".$last_name."@gmail.com"
          ]);

          $transaction = rand(0, 6);

          for($b = 0; $b < $transaction; $b++)
          {
            $total_spent = rand(1, 100);
            $total_saving = $this->totalSaving($total_spent);

            purchase_transaction::create([
              'customer_id' => $customer->id,
              'total_spent' => $total_spent,
              'total_saving' => $total_saving,
              'transaction_at' => $faker->dateTimeBetween(date('Y-m-d', strtotime(now()." -60 days")), date('Y-m-d'))->format('Y-m-d H:i:s'),
            ]);
          }
        }

        dd("done");
    }

    public function totalSaving($total_spent)
    {
      $total_saving = rand(0, 50);
      if($total_saving >= $total_spent)
      {
        $this->totalSaving($total_spent);
      }
      else
      {
        return $total_saving;
      }
    }
}
