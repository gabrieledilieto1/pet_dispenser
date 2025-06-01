import time 
from sensors import *
from mqtt_manager import MQTTManager
from oled_screen import draw_main_screen, show_dispense_message
import ujson

scheduled_time = []

class SmartPetFeeder:
    def __init__(self):
        self.ir = IRSensor(15)
        self.ultra_animal = UltrasonicSensor(4, 5)
        self.ultra_food = UltrasonicSensor(12, 13)
        self.servo = ServoDispenser(18)
        self.buzzer = BuzzerManager(21, 22)
        self.wifi = WiFiManager('NETGEAR36', 'windypotato931')
        self.batt_monitor = BatteryMonitor(adc_pin=34, r1=100000, r2=100000)
        self.mqtt = MQTTManager(
            client_id='petfeeder',
            broker_ip='broker.hivemq.com',
            topic_cmd=b'/petfeeder/cmd',
            topic_status=b'/petfeeder/status',
            topic_alert=b'/petfeeder/alert',
            feeder=self,
            callback=mqtt_callback
        )
        self.button = Pin(14, Pin.IN, Pin.PULL_UP)
        self.last_button_state = self.button.value()
        
    def setup(self):
        self.wifi.connect()
        self.mqtt.connect()
        draw_main_screen()
        
    def execute_dispense(self):
        self.buzzer.start_sound()
        self.servo.dispense()
        if self.ir.is_obstructed():
            self.buzzer.start_sound()
            print("Limite croccantini raggiunto!")
            self.mqtt.publish_alert(b'Accumulo rilevato!')
        self.mqtt.publish_status(b'Erogazione completata.')
        print("Erogazione completata.")
        show_dispense_message()
        
    def check_proximity(self):
        try:
            dist = self.ultra_animal.read_distance()
            print("Distanza animale:", dist)
            if dist < 5:
                print("Animale rilevato, erogazione")
                self.mqtt.publish_status(b'Animale rilevato, erogazione')
                self.execute_dispense()
                time.sleep(10)
        except:
            pass
        
    def check_food_level(self):
        try:
            dist = self.ultra_food.read_distance()
            print("Livello croccantini:", dist)
            if dist > 15:
                print("Livello croccantini basso!")
                self.mqtt.publish_alert(b'Livello croccantini basso!')
        except:
            pass
        
    def check_button(self):
        current_state = self.button.value()
        if self.last_button_state == 1 and current_state == 0:
            print("Pulsante premuto, erogazione manuale")
            self.execute_dispense()
        self.last_button_state = current_state

    def check_schedule(self):
        global scheduled_time
        if scheduled_time:
            formatted_times = [s['time'][:5] for s in scheduled_time]
        else:
            print("Nessun orario programmato")
        if scheduled_time:
            now = time.localtime(time.mktime(time.localtime()) + 3600*2)  # aggiunge 2 ore all’ora UTC
            current_time = "{:02d}:{:02d}:{:02d}".format(now[3], now[4], now[5])  # ora con secondi, es. "10:30:45"
            for schedule in scheduled_time:
                if schedule['time'][:8] == current_time:
                    print("Orario programmato raggiunto:", current_time)
                    self.execute_dispense()
        
    def loop(self):
     while True:
        self.mqtt.check_msg()
        self.check_proximity()
        self.check_food_level()
        self.check_button()
        self.check_schedule()
        
        # Stampa l’orario programmato attuale (se esiste)
        if scheduled_time:
            formatted_times = [s['time'][:5] for s in scheduled_time]
        else:
            print("Nessun orario programmato")
        
        draw_main_screen()
        time.sleep(1)
        
# --- Callback MQTT esterna ---
def mqtt_callback(topic, msg):
    global scheduled_time

    try:
        decoded_topic = topic.decode()
    except:
        decoded_topic = str(topic)

    try:
        decoded_msg = msg.decode()
    except:
        decoded_msg = str(msg)

    print(f"Messaggio MQTT ricevuto")
    print(f"  Topic   : {decoded_topic}")
    
    if decoded_topic == "/petfeeder/schedule":
        try:
            parsed = ujson.loads(decoded_msg)
            print("  Payload :")
            for sched in parsed:
                time_str = sched["time"][:5]
                grams = sched["grams"]
                prox = "Attivo" if sched["proximityEnabled"] else "Disattivo"
                print(f"    - Orario: {time_str} | Quantità: {grams}g | Prossimità: {prox}")
        except Exception as e:
            print("  Payload (raw):", decoded_msg)
            print("  Errore parsing JSON:", e)
    else:
        print(f"  Payload : {decoded_msg}")

    if topic == b'/petfeeder/cmd' and msg == b'dispense':
        feeder.execute_dispense()

    elif topic == b'/petfeeder/schedule':
        try:
            scheduled_time = ujson.loads(msg)
            print("Orari programmati ricevuti:")
            for sched in scheduled_time:
                print(f"  - {sched['time'][:5]}")
        except Exception as e:
            print("Errore parsing JSON:", e)