# Integracao da balanca ESP32

O firmware envia um `POST` JSON para `/api/leituras` com `device_id` e `total`.
A rota valida esses campos e grava a leitura na tabela `leituras`. Os campos
legados `cell_1` a `cell_4` sao opcionais e ficam vazios quando nao enviados.

## Iniciar o Laravel para acesso pela rede local

O servidor deve escutar em uma interface de rede, nao apenas em `localhost`.
Na pasta do projeto, execute:

```powershell
composer run serve:lan
```

Configure `API_URL` no firmware com o endereco local deste computador e a rota
`/api/leituras`, na porta `8000`. O ESP32 e o computador precisam estar na
mesma rede, e o firewall do computador deve permitir conexoes de entrada nessa
porta. Mantenha esse servidor acessivel apenas em uma rede confiavel.

Uma resposta HTTP `201` indica que a leitura foi salva. `422` indica que o
Laravel rejeitou os dados enviados; outros codigos indicam falha e devem ser
conferidos no log serial e no log do Laravel.

## Calibracao

O firmware compartilhado usa `fator[] = {1.0, 1.0, 1.0, 1.0}`. Esses valores
nao sao fatores de calibracao reais e podem fazer o HX711 fornecer contagens
brutas em vez de pesos em quilogramas. Calibre cada celula com uma massa
conhecida antes de usar os valores para registrar pesos. Os fatores exatos nao
podem ser determinados sem testar a montagem fisica.
