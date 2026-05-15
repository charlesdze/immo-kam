<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    $name = $this->ask('Nom de l\'admin ?');
    $email = $this->ask('Email de l\'admin ?');
    
    // Vérifier si l'utilisateur existe déjà
    if (\App\Models\User::where('email', $email)->exists()) {
        $this->error("Erreur : Un utilisateur avec l'adresse $email existe déjà.");
        return;
    }

    $password = $this->secret('Mot de passe ?');

    try {
        \App\Models\User::create([
            'name' => $name,
            'email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password),
            'is_admin' => 1,
        ]);

        $this->info("Le compte administrateur $email a été créé avec succès !");
    } catch (\Exception $e) {
        $this->error("Une erreur est survenue : " . $e->getMessage());
    }
}   
}
