<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Support\Facades\Hash;

class CreateFilamentUser extends Command
{
    protected $signature = 'make:filament-user {email} {password} {--lastname=} {--firstname=} {--role=user} {--trip_id=}';
    
    protected $description = 'Create a new Filament user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $lastname = $this->option('lastname');
        $firstname = $this->option('firstname');
        $role = $this->option('role');
        $tripId = $this->option('trip_id');

        // Vérifier si l'utilisateur existe déjà
        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists.');
            return 1;
        }

        // Vérifier si le trip_id existe dans la table trips
        if ($tripId && !Trip::find($tripId)) {
            $this->error('The trip_id provided does not exist.');
            return 1;
        }

        // Créer l'utilisateur
        $user = User::create([
            'lastname' => $lastname,
            'firstname' => $firstname,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
            'trip_id' => $tripId,
        ]);

        $this->info("User created successfully with email {$email}");
        return 0;
    }
}
