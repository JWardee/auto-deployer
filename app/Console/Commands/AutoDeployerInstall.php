<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\RSA;

class AutoDeployerInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autodeployer:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Ansible SSH keys';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating keys...');

        $rsa = new RSA;
        $rsa->setPrivateKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $rsa->setComment(config('app.name') . ' - Ansible key');

        $keys = $rsa->createKey(4096);

        Storage::put('ansible_rsa.pub', $keys['publickey']);
        Storage::put('ansible_rsa', $keys['privatekey']);

        $this->info('Restricting permissions to keys...');

        chmod(storage_path('app/ansible_rsa'), 600);

        return 0;
    }
}
