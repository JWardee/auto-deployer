<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\RSA;

class Project extends Model
{
    private $storageSshDir = 'ssh-keys/';

    public function deploy()
    {
        $args = [
            'local_ssh_dir' => $this->storageSshDir,
            'public_key' => $this->getPublicKeyFileName(),
            'private_key' => $this->getPrivateKeyFileName(),
            'server_directory' => $this->server_directory,
            'git_repo_url' => $this->git_repo_ssh_url,
            'branch_to_pull' => $this->branch_to_pull,
        ];

        if ($this->before_commands != null) {
            $args['before_deploy_commands'] = explode(PHP_EOL, $this->before_commands);
        }

        if ($this->after_commands != null) {
            $args['after_deploy_commands'] = explode(PHP_EOL, $this->after_commands);
        }

        return Ansible::run(
            $this->server_address,
            storage_path('app/deploy.yml'),
            storage_path('app/ansible_rsa'),
            $this->server_user,
            $args
        );
    }

    public function save(array $options = [])
    {
        if ($this->hasDeployKey() == false) {
            $this->generateSshKeys();
        }

        return parent::save($options);
    }

    public function generateSshKeys()
    {
        $rsa = new RSA;
        $rsa->setPrivateKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $rsa->setPublicKeyFormat(RSA::PUBLIC_FORMAT_OPENSSH);
        $rsa->setComment(config('app.name') . ' - Deploy key');

        $keys = $rsa->createKey(4096);

        Storage::put($this->storageSshDir . $this->getPublicKeyFileName(), $keys['publickey']);
        Storage::put($this->storageSshDir . $this->getPrivateKeyFileName(), $keys['privatekey']);
    }

    public function hasDeployKey()
    {
        return Storage::exists($this->storageSshDir . $this->getPublicKeyFileName());
    }

    public function getDeployKeyAttribute()
    {
        return $this->hasDeployKey() ? Storage::get($this->storageSshDir . $this->getPublicKeyFileName()) : 'Deploy key will be generated on save';
    }

    private function getPublicKeyFileName()
    {
        return 'project-' . $this->id . '_rsa.pub';
    }

    private function getPrivateKeyFileName()
    {
        return 'project-' . $this->id . '_rsa';
    }
}
