from machine import Pin, ADC, time_pulse_us, I2C, reset
from time import sleep, sleep_ms
import dht
import network
from umqtt.simple import MQTTClient
import buzzer
import distanza
import pompa
import button
from sensore_ambiente import sensore_ambiente
from oled_display import oled_display

greenhub_logo = bytearray(b'\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xbd\xef\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xbd\xef\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf7\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xef\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x1f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe0\xc3\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x07\xf8?\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x7f\xff\x83\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x83\xff\xff\xf0\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8?\xff\xc0\xff\x07\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc1\xff\xfe\x00\xff\xe0\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x1f\xff\xfc\x00\xff\xfe\x0f\xff\xff\xff\xff\xff\xff\xff\xff\xe0\xff\xff\xf8\x00\xff\xff\xc1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xfc\x03\xf8\x01\xff\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xfe\x008\x07\xff\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\x04\x0c?\x80\x7f\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\x80\x84x\x00\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\xe0$`@\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\xff\x00B\x03\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\xff\xf8@\x0f\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\xff\xfc\x03\xff\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\xff\xfc?\xff\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe3\xff\xff\xfc\x7f\xff\xff\xf1\xff\xff\xff\xff\xff\xff\xff\xff\xe0\x00\x00\x00\x00\x00\x00\x01\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x1f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x10\x04@\x00\x8f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf9\x9f\x9cq\xfc\x8f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc|~?\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf1\xfc\x1f\x81\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x19\xfcg\x9c\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x81\xf3\xe3\xe1\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe1\xf7\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xce\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe1\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf3\xf3\xe7\xc7\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf3\xf1\xc6{\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf3\xf6&\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf07g\x03\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\xff\xff\xff\xff\xff\xe3\xf3\xff\xe3\xff\xff\xff\xff\xff\xf9\xff\xc0\xc0x\x0e\x00\xe3\xf3\x1e`\x0f\xff\xff\xff\xff\xf1\xe0\xc7\x9f\x13\xe2<`\x03\x1ec\xe7\xff\xff\xff\xff\xf0\xfc\xc7\x9f\xf3\xfe<c\xf3\x1ec\xe3\xff\xff\xff\xff\xfc\x00\xc7\xc08\x06<c\xf3\x80`\x0f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff')
logo = [greenhub_logo]

# --- Costanti e soglie ---
SCL_PIN = 22
SDA_PIN = 21
sensor_dht = dht.DHT22(Pin(13))
s_um = sensore_ambiente(32)
buz = buzzer.BUZZER(23, 2000)
buz.stop()
led = Pin(19, Pin.OUT)
pump = pompa.PompaController(26)
btn1 = button.button(27)
sr04 = distanza.DISTANZA(16, 17)

display_controller = oled_display()
display = display_controller.inizializza(SCL_PIN, SDA_PIN, logo)


WIFI_SSID = 'S20'
WIFI_PASS = 'pagani03'
#MQTT_BROKER = 'broker.emqx.io'
MQTT_BROKER = 'broker.hivemq.com'

MQTT_TOPIC1 = b'esp32/temp'
MQTT_TOPIC2 = b'esp32/umidita'
MQTT_TOPIC3 = b'esp32/soglia_temp'
MQTT_TOPIC4 = b'esp32/soglia_umidita'
MQTT_TOPIC5 = b'esp32/accendi_spegni_allarme'
MQTT_TOPIC6 = b'esp32/irrigazione'
MQTT_TOPIC7 = b'esp32/conteggio_irrigazioni'
MQTT_TOPIC8 = b'esp32/stato_pompa'
MQTT_TOPIC9 = b'esp32/conteggio_allarmi_temp'
MQTT_TOPIC10 = b'esp32/conteggio_allarmi_um'
MQTT_TOPIC11 = b'esp32/accendi_spegni_pompa'
MQTT_TOPIC12 = b'esp32/tempo_irrigazione'

soglia_temp = 30
soglia_umidita = 50
soglia_irrigazione = -1
tempo_irrigazione = 10
switch_allarme = 'false'
switch_pompa = 'false'
conteggio_irrigazioni = 0
conteggio_temp = 0
conteggio_um = 0

# --- Callback MQTT ---
def mqtt_callback(topic, msg):
    global soglia_temp, soglia_umidita, switch_allarme, soglia_irrigazione, switch_pompa, tempo_irrigazione
    topic = topic.decode()
    msg = msg.decode().strip()

    if topic == MQTT_TOPIC3.decode():
        try:
            soglia_temp = int(msg)
        except ValueError:
            soglia_temp = 30

    elif topic == MQTT_TOPIC4.decode():
        try:
            soglia_umidita = int(msg)
            print("soglia umidita", soglia_umidita)
        except ValueError:
            soglia_umidita = 50

    elif topic == MQTT_TOPIC5.decode():
        switch_allarme = msg
        print("switch allarme", switch_allarme)

    elif topic == MQTT_TOPIC6.decode():
        try:
            soglia_irrigazione = int(msg)
        except ValueError:
            soglia_irrigazione = 10
    
    elif topic == MQTT_TOPIC12.decode():
        try:
            tempo_irrigazione = int(msg)
        except ValueError:
            tempo_irrigazione = 10

    elif topic == MQTT_TOPIC11.decode():
        switch_pompa = msg
        print("switch pompa", switch_pompa)

# --- Inizializzazione WiFi e MQTT ---
wifi = network.WLAN(network.STA_IF)
wifi.active(True)
wifi.connect(WIFI_SSID, WIFI_PASS)
while not wifi.isconnected():
    print("Connessione WiFi in corso...")
    display_controller.clear()
    display.text("Connessione WiFi", 0, 32)
    display.text("in corso...", 20, 42)
    display.show()
    sleep(1)
    
print("Connesso al WiFi:", wifi.ifconfig())
display_controller.clear()
display.text("Connessione", 16, 32)
display.text("avvenuta", 32, 42)
display.show()

client = MQTTClient("esp32_client", MQTT_BROKER)
client.set_callback(mqtt_callback)
client.connect()
for topic in [MQTT_TOPIC3, MQTT_TOPIC4, MQTT_TOPIC5, MQTT_TOPIC6, MQTT_TOPIC11,MQTT_TOPIC12]:
    client.subscribe(topic)

client.publish(MQTT_TOPIC7, str(conteggio_irrigazioni))
client.publish(MQTT_TOPIC8, 'OFF')
client.publish(MQTT_TOPIC9, str(conteggio_temp))
client.publish(MQTT_TOPIC10, str(conteggio_um))

# --- Funzioni modulari ---
def gestisci_messaggi_mqtt():
    try:
        client.check_msg()
    except OSError as e:
        print("Errore MQTT:", e)
        client.disconnect()
        sleep(5)
        reset()

def gestisci_pompa_mqtt():
    global conteggio_irrigazioni
    if switch_pompa == 'true':
       # pump.accendi()
        print("ACCENSIONE POMPA")
        client.publish(MQTT_TOPIC8, 'ON')
        conteggio_irrigazioni += 1
        client.publish(MQTT_TOPIC7, str(conteggio_irrigazioni))
        display_controller.clear()
        display.text("POMPA ACCESA", 16, 32)
        display.show()
        sleep(5)
    else:
        print("SPEGNIMENTO POMPA")
        #pump.spegni()
        client.publish(MQTT_TOPIC8, 'OFF')
        

def misura_e_pubblica_dati(dist):
    sensor_dht.measure()
    temp = sensor_dht.temperature()
    umidita = s_um.leggi_umidita()
    client.publish(MQTT_TOPIC1, str(temp))
    client.publish(MQTT_TOPIC2, str(umidita))

    display_controller.clear()
    display.text("Temp:", 0, 20)
    display.text("{:.1f}".format(temp), 70, 20)
    display.text("C", 70 + 8 * len("{:.1f}".format(temp)) + 6, 20)
    x_offset = 70 + 8 * len("{:.1f}".format(temp)) + 3
    y_offset = 20
    for dx in [0, 1]:
        for dy in [0, 1]:
            display.pixel(x_offset + dx, y_offset + dy, 1)
            
    
    
   

    display.text("Umidita':", 0, 32)
    display.text("{:.1f} %".format(umidita), 70, 32)
    display.text("Livello acqua:", 8, 48)
    if dist is not None:
        percentuale = round((1 - (dist / 14)) * 100)
        display.text(str(percentuale) + "%", 52, 56)
        print("Percentuale:", percentuale)
    else:
        #display.text("Errore", 0, 10)
        print("Errore: distanza non rilevata")
    display.show()
    

    return temp, umidita

def gestisci_allarmi(temp, umidita):
    global conteggio_temp, conteggio_um
    print("switch allarmew", switch_allarme)
    print("soglia temperatura", soglia_temp)
    if temp > soglia_temp and switch_allarme == 'false':
        conteggio_temp += 1
        client.publish(MQTT_TOPIC9, str(conteggio_temp))
        display_controller.clear()
        led.on()
        # buz.play()
        display.text("TEMPERATURA ALTA!", 0, 32)
        display.show()
        sleep(5)
       
        led.on()
    elif umidita > soglia_umidita and switch_allarme == 'false':
        conteggio_um += 1
        client.publish(MQTT_TOPIC10, str(conteggio_um))
        display_controller.clear()
        led.on()
        #buz.play()
        display.text("UMIDITA' ALTA!", 0, 32)
        display.show()
        sleep(5)
       
        
    else:
        buz.stop()
        led.off()

def gestisci_irrigazione_automatica(umidita):
    global conteggio_irrigazioni
    print("soglia ittigazione", soglia_irrigazione)
    if umidita < soglia_irrigazione:
        print("ACCENSIONE POMPA")
        #pump.accendi()
        client.publish(MQTT_TOPIC8, 'ON')
        conteggio_irrigazioni += 1
        client.publish(MQTT_TOPIC7, str(conteggio_irrigazioni))
        
        for i in range(1, tempo_irrigazione + 1):
            print('Tempo irrigazione:',tempo_irrigazione)
            display_controller.clear()
            display.text("Innaffio", 25, 26)
            display.text(str(i) + " s", 52, 42)
            display.show()
            sleep(1)
        print("SPEGNIMENTO POMPA")    
        #pump.spegni()
        client.publish(MQTT_TOPIC8, 'OFF')
'''
def aggiorna_display_livello_acqua(dist):
    display.fill(0)
    display.text("Livello acqua:", 8, 24)
    if dist is not None:
        percentuale = round((1 - (dist / 14)) * 100)
        display.text(str(percentuale) + "%", 52, 40)
        print("Percentuale:", percentuale)
    else:
        display.text("Errore", 0, 10)
        print("Errore: distanza non rilevata")
    display.show()
'''
# --- Main loop ---
while True:
    
    gestisci_messaggi_mqtt()
    gestisci_pompa_mqtt()
    temp, umidita = misura_e_pubblica_dati(sr04.misura_distanza())
    sleep_ms(2000)
    gestisci_allarmi(temp, umidita)
    gestisci_irrigazione_automatica(umidita)
    #aggiorna_display_livello_acqua(sr04.misura_distanza())