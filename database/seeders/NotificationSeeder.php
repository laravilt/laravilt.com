<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
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

        $notifications = [
            [
                'type' => 'App\Notifications\NewOrderNotification',
                'data' => [
                    'title' => 'New Order Received',
                    'body' => 'You have received a new order #12345 for $299.99',
                    'icon' => 'ShoppingCart',
                    'color' => 'success',
                    'action_url' => '/admin/orders/12345',
                ],
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\LowStockNotification',
                'data' => [
                    'title' => 'Low Stock Alert',
                    'body' => 'iPhone 15 Pro is running low on stock (5 remaining)',
                    'icon' => 'AlertTriangle',
                    'color' => 'warning',
                    'action_url' => '/admin/products',
                ],
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\NewUserNotification',
                'data' => [
                    'title' => 'New User Registration',
                    'body' => 'John Doe has registered a new account',
                    'icon' => 'UserPlus',
                    'color' => 'info',
                    'action_url' => '/admin/users',
                ],
                'read_at' => now()->subMinutes(30),
            ],
            [
                'type' => 'App\Notifications\PaymentReceivedNotification',
                'data' => [
                    'title' => 'Payment Received',
                    'body' => 'Payment of $1,250.00 has been received for invoice #INV-2024-001',
                    'icon' => 'DollarSign',
                    'color' => 'success',
                    'action_url' => '/admin/invoices',
                ],
                'read_at' => now()->subHours(2),
            ],
            [
                'type' => 'App\Notifications\SystemUpdateNotification',
                'data' => [
                    'title' => 'System Update Available',
                    'body' => 'A new system update (v2.5.0) is available. Click to learn more.',
                    'icon' => 'RefreshCw',
                    'color' => 'primary',
                    'action_url' => '/admin/settings/updates',
                ],
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\ReviewNotification',
                'data' => [
                    'title' => 'New Product Review',
                    'body' => 'A customer left a 5-star review on MacBook Pro 14"',
                    'icon' => 'Star',
                    'color' => 'warning',
                    'action_url' => '/admin/reviews',
                ],
                'read_at' => now()->subDays(1),
            ],
            [
                'type' => 'App\Notifications\SecurityAlertNotification',
                'data' => [
                    'title' => 'Security Alert',
                    'body' => 'Unusual login activity detected from a new device',
                    'icon' => 'Shield',
                    'color' => 'danger',
                    'action_url' => '/admin/security',
                ],
                'read_at' => null,
            ],
            [
                'type' => 'App\Notifications\BackupCompleteNotification',
                'data' => [
                    'title' => 'Backup Complete',
                    'body' => 'Daily database backup completed successfully',
                    'icon' => 'Database',
                    'color' => 'success',
                    'action_url' => '/admin/backups',
                ],
                'read_at' => now()->subHours(6),
            ],
        ];

        foreach ($notifications as $notification) {
            $user->notifications()->create([
                'id' => Str::uuid(),
                'type' => $notification['type'],
                'data' => $notification['data'],
                'read_at' => $notification['read_at'],
                'created_at' => now()->subMinutes(rand(5, 1440)),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Notifications seeded successfully!');
    }
}
