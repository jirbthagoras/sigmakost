<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:register 
                            {--name= : Admin name}
                            {--email= : Admin email}
                            {--password= : Admin password}
                            {--phone= : Admin phone number}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daftarkan pengguna admin baru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Membuat pengguna admin baru...');

        // Get input values
        $name = $this->option('name') ?? $this->ask('Masukkan nama admin');
        $email = $this->option('email') ?? $this->ask('Masukkan email admin');
        $phone = $this->option('phone') ?? $this->ask('Masukkan nomor telepon admin (opsional)', null);
        
        // Get password
        $password = $this->option('password');
        if (!$password) {
            $password = $this->secret('Masukkan kata sandi admin');
            $confirmPassword = $this->secret('Konfirmasi kata sandi');
            
            if ($password !== $confirmPassword) {
                $this->error('Kata sandi tidak cocok!');
                return 1;
            }
        }

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        try {
            // Create admin user
            $admin = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'phone' => $phone,
                'email_verified_at' => now(),
            ]);

            $this->info("Pengguna admin berhasil dibuat!");
            $this->table(
                ['Field', 'Nilai'],
                [
                    ['ID', $admin->id],
                    ['Nama', $admin->name],
                    ['Email', $admin->email],
                    ['Peran', $admin->role],
                    ['Telepon', $admin->phone ?? 'T/A'],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            $this->error('Kesalahan membuat pengguna admin: ' . $e->getMessage());
            return 1;
        }
    }
}
