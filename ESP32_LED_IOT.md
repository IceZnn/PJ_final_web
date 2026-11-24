# Indicador de desperdício com ESP32 e LED RGB

Este arquivo fica fora da pasta `PJ_final_web`. O aplicativo não precisa de uma balança conectada: os pesos do desperdício continuam sendo registrados manualmente em kg. O ESP32 consulta a média dos registros da semana atual e acende um LED RGB.

## Regras de cor

A média considera somente os registros de peso da semana atual (segunda a domingo), do usuário autenticado:

| Média semanal | Cor |
| --- | --- |
| De 1 kg até menos de 6 kg | Verde |
| De 6 kg até 12 kg, inclusive | Amarelo |
| Abaixo de 1 kg, acima de 12 kg ou sem registros (média 0) | Vermelho |

As faixas contínuas evitam lacunas quando a média tiver casas decimais.

## Peças

- ESP32 Dev Module (placa selecionada como **ESP32 Dev Module** na Arduino IDE).
- 1 LED RGB de **cátodo comum**.
- 3 resistores de 220–330 ohms (um por canal).
- Jumpers e cabo USB.

## Pinagem

| LED RGB | Resistor | ESP32 Dev Module |
| --- | --- | --- |
| R (vermelho) | 220–330 ohms em série | GPIO 25 |
| G (verde) | 220–330 ohms em série | GPIO 26 |
| B (azul) | 220–330 ohms em série | GPIO 27 |
| Cátodo comum (perna comum, normalmente a mais longa) | — | GND |

Confirme o tipo do LED antes de ligar. Este exemplo usa cátodo comum; não conecte o LED diretamente aos GPIOs e não aplique 5 V nos pinos do ESP32.

## Preparar a aplicação e a placa

1. Inicie o Laravel acessível na mesma rede Wi-Fi do ESP32, por exemplo com `php artisan serve --host=0.0.0.0 --port=8000`. No computador, use `ipconfig` para encontrar o IPv4 da rede local; permita a porta 8000 no firewall da rede privada, se necessário.
2. Faça login pela aplicação e obtenha um token válido. O endpoint `/api/login` aceita `cpf` e `senha` em JSON e devolve `token`; os tokens atuais expiram em dois dias.
3. Na Arduino IDE, instale o suporte a ESP32, selecione **Tools > Board > ESP32 Arduino > ESP32 Dev Module** e instale **ArduinoJson** pelo Library Manager.
4. Copie o sketch abaixo para um arquivo `indicador_desperdicio.ino`. Preencha SSID, senha, endereço LAN do servidor Laravel e token. Não publique o token nem o sketch preenchido.
5. Se o servidor usar uma porta diferente, ajuste-a em `API_URL`. A API requer `Authorization: Bearer <token>` e retorna JSON.
6. Grave o sketch, abra o Serial Monitor em 115200 baud e verifique a mensagem de conexão e as respostas HTTP.

Teste a API antes de ligar a placa:

```text
GET http://IP_DO_SERVIDOR:8000/api/led/status
Authorization: Bearer TOKEN_VALIDO
Accept: application/json
```

Resposta de exemplo:

```json
{
  "media_semanal_kg": 4.5,
  "cor": "verde",
  "registros_considerados": 2,
  "semana": {
    "inicio": "2026-11-23",
    "fim": "2026-11-29"
  }
}
```

## Sketch ESP32

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <string.h>

const char* WIFI_SSID = "NOME_DA_REDE";
const char* WIFI_PASSWORD = "SENHA_DA_REDE";
const char* API_URL = "http://192.168.1.10:8000/api/led/status";
const char* API_TOKEN = "TOKEN_VALIDO";

const int PINO_VERMELHO = 25;
const int PINO_VERDE = 26;
const int PINO_AZUL = 27;
const unsigned long INTERVALO_MS = 30000;
unsigned long ultimaConsulta = 0;

void definirCor(int vermelho, int verde, int azul) {
  analogWrite(PINO_VERMELHO, vermelho);
  analogWrite(PINO_VERDE, verde);
  analogWrite(PINO_AZUL, azul);
}

void apagarLed() {
  definirCor(0, 0, 0);
}

void consultarIndicador() {
  WiFiClient cliente;
  HTTPClient http;

  if (!http.begin(cliente, API_URL)) {
    Serial.println("Falha ao iniciar a conexão HTTP.");
    apagarLed();
    return;
  }

  http.addHeader("Accept", "application/json");
  http.addHeader("Authorization", String("Bearer ") + API_TOKEN);
  const int codigo = http.GET();

  if (codigo != HTTP_CODE_OK) {
    Serial.printf("API respondeu HTTP %d; LED apagado.\n", codigo);
    apagarLed();
    http.end();
    return;
  }

  JsonDocument resposta;
  const DeserializationError erro = deserializeJson(resposta, http.getString());
  if (erro) {
    Serial.printf("JSON inválido: %s\n", erro.c_str());
    apagarLed();
    http.end();
    return;
  }

  const char* cor = resposta["cor"] | "";
  if (strcmp(cor, "verde") == 0) {
    definirCor(0, 255, 0);
  } else if (strcmp(cor, "amarelo") == 0) {
    definirCor(255, 100, 0);
  } else if (strcmp(cor, "vermelho") == 0) {
    definirCor(255, 0, 0);
  } else {
    Serial.println("Cor desconhecida; LED apagado.");
    apagarLed();
  }

  Serial.printf("Média semanal: %.2f kg; cor: %s\n",
                resposta["media_semanal_kg"] | 0.0, cor);
  http.end();
}

void setup() {
  Serial.begin(115200);
  pinMode(PINO_VERMELHO, OUTPUT);
  pinMode(PINO_VERDE, OUTPUT);
  pinMode(PINO_AZUL, OUTPUT);
  apagarLed();

  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Conectando ao Wi-Fi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.printf("\nConectado. IP da placa: %s\n",
                WiFi.localIP().toString().c_str());
  consultarIndicador();
  ultimaConsulta = millis();
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    apagarLed();
    WiFi.reconnect();
    delay(1000);
    return;
  }

  if (millis() - ultimaConsulta >= INTERVALO_MS) {
    consultarIndicador();
    ultimaConsulta = millis();
  }
  delay(100);
}
```

## Operação e segurança

- O ESP32 consulta a API a cada 30 segundos. Se a API falhar, o sketch apaga o LED e informa o erro no Serial Monitor.
- A rota é protegida por token de usuário e não é pública. Gere um token novo quando o atual expirar.
- O endereço HTTP do exemplo é adequado somente para um teste em rede local confiável. Não exponha a API nem envie token pela internet sem HTTPS; para implantação real, use HTTPS e credenciais próprias para o dispositivo.
- Não existe comunicação direta entre o navegador e o ESP32: a placa consulta o Laravel e o dashboard exibe a mesma média e cor.
