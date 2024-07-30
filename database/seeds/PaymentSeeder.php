<?php

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        try {
            Payment::create([
                'payment_id' => 'test123',
                'payer_id' => 'payer123',
                'payer_email' => 'test@example.com',
                'amount' => 99.99,
                'currency' => 'USD',
                'payment_status' => 'Completed',
            ]);

            Payment::create([
                'payment_id' => 'test124',
                'payer_id' => 'payer124',
                'payer_email' => 'test2@example.com',
                'amount' => 49.99,
                'currency' => 'USD',
                'payment_status' => 'Pending',
            ]);

            Payment::create([
                'payment_id' => 'test125',
                'payer_id' => 'payer125',
                'payer_email' => 'test3@example.com',
                'amount' => 199.99,
                'currency' => 'USD',
                'payment_status' => 'Failed',
            ]);

            Log::info('Payments seeded successfully.');

        } catch (\Exception $e) {
            Log::error('Error seeding payments: ' . $e->getMessage());
        }
    }
}
