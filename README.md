# 🐾 Pet Dispeenser

**Pet Dispeenser** è un sistema IoT per l’erogazione automatica del cibo per animali, controllabile via interfaccia web e connesso tramite protocollo MQTT. Il progetto integra componenti software sviluppate con Node-RED, MicroPython e una dashboard web responsive.

## 📁 Struttura del progetto

```plaintext
pet_dispeenser/
├── web/               # Frontend del sito per il controllo del dispenser
├── node-red/          # Flow Node-RED per automazione e logica di controllo
├── mqtt/              # Script di comunicazione tramite protocollo MQTT
├── micropython/       # Codice per microcontrollori (es. ESP32)
└── README.md          # Documentazione del progetto
