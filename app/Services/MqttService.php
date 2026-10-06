<?php

namespace App\Services;

use App\Models\Sensor;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{

    public function receberDados()
    {

        $host = env('MQTT_HOST');
        $port = env('MQTT_PORT');

        $username = env('MQTT_USERNAME');
        $password = env('MQTT_PASSWORD');

        $topico = env('MQTT_TOPIC');

        $mqtt = new MqttClient($host, $port, 'Aula1-cliente' . uniqid());

        $settings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setUseTls(true);
        
        $mqtt->connect($settings, true);
        echo("Conectado no HIVE");

        $mqtt->subscribe($topico, function ($topic, $message) {

        echo("Mensagem recebida no tópico");
        echo $message . "\n";

        $dados = json_decode($message, true);

        Sensor::created([
            'sensor' => $dados['sensor'],
            'temperatura' => $dados['temperatura'],
            'umidade' => $dados['umidade'],
        ]);

        echo("Dados salvos no banco de dados");


        },0);

        echo("Aguardando mensagens no tópico: " . $topico . "\n");

        $mqtt->loop(true);
    }
}
