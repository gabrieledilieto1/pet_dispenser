"""
===============================================================================
               Sistema di Avvio OLED + Stato Wi-Fi + Buzzer
===============================================================================

Questo script per ESP32 con display OLED I2C e buzzer integra le seguenti funzionalità:

✅ DISPLAY OLED (SSD1306 - 128x64)
   - Visualizza il logo "Pet Feeder" al centro dello schermo.
   - Mostra un'icona Wi-Fi in alto a destra se il dispositivo è connesso.
   - Mostra un'icona Wi-Fi alternativa (con X) se non connesso.

✅ CONNESSIONE Wi-Fi
   - Si connette automaticamente alla rete Wi-Fi indicata.
   - Verifica lo stato della connessione ogni 5 secondi.

✅ BUZZER
   - Emette 1 beep quando la connessione Wi-Fi viene stabilita.
   - Emette 2 beep quando la connessione Wi-Fi viene persa.

📦 Componenti usati:
   - ESP32
   - Display OLED 128x64 I2C (basato su SSD1306)
   - Buzzer attivo/passivo collegato a GPIO 23
   - Librerie: `network`, `machine`, `ssd1306`, `framebuf`, `time`

📌 Personalizzabile:
   - Logo Pet Feeder (bytearray)
   - Icone Wi-Fi connesse/disconnesse
   - Pin del buzzer e configurazione I2C
   - Frequenza, durata e numero di beep

📅 Creato per il progetto: **Pet Dispenser IoT**
   GitHub repo: https://github.com/gabrieledilieto1/pet_dispenser.git
===============================================================================
"""


import machine
import time
import network
from machine import Pin, I2C, PWM
import ssd1306
import framebuf

# --- Configurazione OLED ---
WIDTH = 128
HEIGHT = 64
SCL_PIN = 22
SDA_PIN = 21
i2c = I2C(0, scl=Pin(SCL_PIN), sda=Pin(SDA_PIN))
display = ssd1306.SSD1306_I2C(WIDTH, HEIGHT, i2c)

# --- Buzzer su GPIO 23 ---
buzzer = PWM(Pin(23))
buzzer.duty(0)

def beep(times=1, duration=100):
    for _ in range(times):
        buzzer.freq(1000)
        buzzer.duty(512)
        time.sleep_ms(duration)
        buzzer.duty(0)
        time.sleep_ms(100)

# --- Logo Pet Feeder ---
pet_feeder_logo = bytearray(b'...')  # Inserisci qui il tuo bytearray completo

# --- Icona Wi-Fi CONNESSO ---
wifi_logo = bytearray([
    0x00, 0x00,
    0x1C, 0x00,
    0x22, 0x00,
    0x41, 0x00,
    0x80, 0x80,
    0x08, 0x80,
    0x10, 0x40,
    0x20, 0x20,
    0x40, 0x10,
    0x00, 0x08,
    0x00, 0x00,
    0x00, 0x00,
    0x08, 0x00,
    0x14, 0x00,
    0x22, 0x00,
    0x41, 0x00
])

# --- Icona Wi-Fi NON CONNESSO ---
wifi_off_logo = bytearray([
    0x00, 0x00,
    0x1C, 0x00,
    0x22, 0x00,
    0x41, 0x00,
    0x88, 0x80,
    0x10, 0x80,
    0x20, 0x40,
    0x40, 0x20,
    0x80, 0x10,
    0x00, 0x08,
    0x10, 0x10,
    0x08, 0x20,
    0x04, 0x40,
    0x02, 0x80,
    0x01, 0x00,
    0x00, 0x00
])

# --- Connessione Wi-Fi ---
sta_if = network.WLAN(network.STA_IF)
sta_if.active(True)
sta_if.connect('NOME_RETE_WIFI', 'PASSWORD_WIFI')

# --- Mostra logo con lo stato Wi-Fi ---
def show_logo_with_wifi(connected):
    display.fill(0)
    logo_fb = framebuf.FrameBuffer(pet_feeder_logo, WIDTH, HEIGHT, framebuf.MONO_HLSB)
    display.blit(logo_fb, 0, 0)
    wifi_fb = framebuf.FrameBuffer(wifi_logo if connected else wifi_off_logo, 16, 16, framebuf.MONO_HLSB)
    display.blit(wifi_fb, WIDTH - 16, 0)
    display.show()

# --- Loop con verifica stato Wi-Fi e beep ---
prev_connected = None

while True:
    connected = sta_if.isconnected()
    show_logo_with_wifi(connected)

    if connected != prev_connected:
        if connected:
            beep(1)      # Connessione attiva: 1 beep
        else:
            beep(2)      # Connessione persa: 2 beep
        prev_connected = connected

    time.sleep(5)
