<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class Ansible
{
    static public function run($hostAddress, $playbook, $privateKey, $user, $args = [])
    {
        $args = self::getCommand($hostAddress, $playbook, $privateKey, $user, $args);
        Log::info('Running ansible script - ' . implode(' ', $args));

        $process = new Process($args);
        $process->setTimeout(3600);
        $process->setIdleTimeout(3600);

        /**
         * Allows for real-time terminal streaming,
         * also can now log entire output to file instead of just last line
         */
        $process->run(function($type, $buffer) {
            Log::info($buffer);

            if (substr($buffer, 0, 5) == 'fatal') {
                throw new \Exception('FATAL ANSIBLE ERROR: ' . $buffer);
            }
        });

        return $process->getOutput();
    }

    static public function getCommand($hostAddress, $playbook, $privateKey, $user, $args = [])
    {
        return [
            'ansible-playbook',
            '--inventory=' . $hostAddress . ',',
            '--user=' . $user,
            '--private-key=' . $privateKey,
            '--extra-vars=' . json_encode($args), // FIXME: Sometimes extra-vars is not processed because of issues with single quotes
            $playbook
        ];
    }

    static public function runAndFormat($hostAddress, $playbook, $privateKey, $user, $args = [])
    {
        $result = '';

        preg_match(
            '/(?<=BEGIN_OUTPUT\s).*(?=\sEND_OUTPUT)/',
            self::run($hostAddress, $playbook, $privateKey, $user, $args),
            $result
        );

        return $result;
    }
}
