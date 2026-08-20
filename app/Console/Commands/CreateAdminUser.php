<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'vanniyan:create-admin';
    protected $description = 'Create the initial System Administrator account securely';

    public function handle()
    {
        $this->info('Vanniyan System Administrator Setup');
        
        if (AdminUser::count() > 0) {
            if (!$this->confirm('An administrator account already exists. Do you want to create another one?')) {
                return;
            }
        }

        $name = $this->ask('Enter Administrator Name (e.g., Dinoja)', 'System Administrator');
        $username = $this->ask('Enter Administrator Username', 'admin');
        
        $password = $this->secret('Enter Secure Password');
        $confirmPassword = $this->secret('Confirm Secure Password');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match. Setup aborted.');
            return;
        }

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters. Setup aborted.');
            return;
        }

        if (AdminUser::where('username', $username)->exists()) {
            $this->error('An administrator with this username already exists.');
            return;
        }

        $admin = AdminUser::create([
            'name' => $name,
            'username' => $username,
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        $this->info("Administrator '{$admin->username}' created successfully.");
    }
}
