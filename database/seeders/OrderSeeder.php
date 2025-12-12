<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->warn('No user found. Please create a user first.');
            return;
        }

        $customers = Customer::where('user_id', $user->id)->get();
        $products = Product::where('user_id', $user->id)->get();

        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please run CustomerSeeder first.');
            return;
        }

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run ProductSeeder first.');
            return;
        }

        $faker = Faker::create();
        $count = 1000; // Create 1000 orders

        $this->command->info("Creating {$count} orders with items...");

        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        $paymentMethods = ['credit_card', 'debit_card', 'paypal', 'bank_transfer', 'cash', 'stripe'];
        $currencies = ['USD', 'EUR', 'GBP', 'CAD', 'AUD'];

        for ($i = 0; $i < $count; $i++) {
            $customer = $customers->random();
            $orderStatus = $faker->randomElement($orderStatuses);
            $paymentStatus = $faker->randomElement($paymentStatuses);

            // Generate order number with more unique identifier
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'order_number' => $orderNumber,
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
                'payment_method' => $faker->randomElement($paymentMethods),
                'subtotal' => 0,
                'tax' => $faker->randomFloat(2, 0, 50),
                'shipping' => $faker->randomFloat(2, 0, 25),
                'discount' => $faker->randomFloat(2, 0, 20),
                'total' => 0,
                'currency' => $faker->randomElement($currencies),
                'shipping_address' => $customer->address . ', ' . $customer->city . ', ' . $customer->state . ' ' . $customer->postal_code,
                'billing_address' => $customer->address . ', ' . $customer->city . ', ' . $customer->state . ' ' . $customer->postal_code,
                'notes' => $faker->optional(0.3)->sentence,
                'paid_at' => $paymentStatus === 'paid' ? $faker->dateTimeBetween('-1 year', 'now') : null,
                'shipped_at' => in_array($orderStatus, ['shipped', 'delivered']) ? $faker->dateTimeBetween('-1 year', 'now') : null,
                'delivered_at' => $orderStatus === 'delivered' ? $faker->dateTimeBetween('-1 year', 'now') : null,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);

            // Create 1-5 order items
            $itemCount = $faker->numberBetween(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = $faker->numberBetween(1, 5);
                $unitPrice = $product->price;
                $itemDiscount = $faker->optional(0.2)->randomFloat(2, 0, 10) ?? 0;
                $totalPrice = ($unitPrice * $quantity) - $itemDiscount;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'discount' => $itemDiscount,
                ]);

                $subtotal += $totalPrice;
            }

            // Update order totals
            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal + $order->tax + $order->shipping - $order->discount,
            ]);

            if (($i + 1) % 100 === 0) {
                $this->command->info("Created " . ($i + 1) . " orders...");
            }
        }

        $this->command->info("{$count} orders seeded successfully!");
    }
}
