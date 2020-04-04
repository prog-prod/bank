<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Hashing\Hasher as Hash;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add_admin {mail} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $hash;
    private $adminRole;

    /**
     * Create a new command instance.
     *
     * @param Hash $hash
     */
    public function __construct(Hash $hash)
    {
        parent::__construct();
        $this->hash = $hash;
        $this->adminRole = config('admin.role');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setUpAdminAccount();
    }

    private function setUpAdminAccount(): void
    {
        $this->info("Let's create the admin account.");

        [$name, $email, $password] = $this->gatherAdminAccountCredentials();

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $this->hash->make($password),
            'role' =>  $this->adminRole,
        ]);

        $this->comment('Alrighty, your account has been created.');

    }
    private function gatherAdminAccountCredentials ()
    {
        $name = $this->ask('Your name', 'Admin');
        $email = $this->argument('mail');
        $password = $this->argument('password');

        return [$name, $email, $password];
    }
}
