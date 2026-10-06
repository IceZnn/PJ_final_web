<?php

namespace App\Console\Commands;

use App\Services\MqttService;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;

#[Signature('app:mqtt-listen')]
#[Description('Comando para ouvir mensagens MQTT')]
class MqttListen extends Command
{
    protected $signature = 'app:mqtt-listen';
    protected $description = 'Comando para ouvir mensagens MQTT';

    public function handle()
    {
        $mqttService = new MqttService();
        $mqttService->receberDados();
        return Command::SUCCESS;
    }
}
