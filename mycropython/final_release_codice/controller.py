import time 
from sensors import *
from mqtt_manager import MQTTManager
from oled_screen import *
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
        self.erogazioni_anticipate = set()  # orari erogati anticipatamente (in formato "HH:MM")
        
    def setup(self):
        show_wifi_status(connecting=True) 
        self.wifi.connect()
        show_wifi_status(connecting=False)  
        time.sleep(2)
        draw_main_screen()
        self.mqtt.connect()
        
    def execute_dispense(self):
        self.buzzer.start_sound()
        self.servo.dispense()
        if self.ir.is_obstructed():
            self.buzzer.start_sound()
            print("Limite croccantini raggiunto!")
            show_ir_alert()
            self.mqtt.publish_alert(b'Accumulo rilevato!')
        self.mqtt.publish_status(b'Erogazione completata.')
        print("Erogazione completata.")
        show_dispense_message()
    

    def check_proximity(self):
        global scheduled_time

        try:
            dist = self.ultra_animal.read_distance()
            print("Distanza animale:", dist)
            if dist < 10:
                print("Animale rilevato")

                now = time.localtime(time.time() + 3600 * 2)
                hour, minute, second = now[3], now[4], now[5]
                now_secs = hour * 3600 + minute * 60 + second
                now_str = f"{hour:02d}:{minute:02d}"

                for sched in scheduled_time:
                    try:
                        sched_time = sched['time'][:5]
                        if sched_time in self.erogazioni_anticipate:
                            continue  

                        h, m = map(int, sched_time.split(":"))
                        sched_secs = h * 3600 + m * 60
                        diff = sched_secs - now_secs

                        if 0 < diff <= 600:
                            print(f"Animale rilevato entro 10 minuti da erogazione programmata ({sched_time}), erogo ora")
                            self.mqtt.publish_status(f"Erogazione anticipata per rilevamento animale ({sched_time})".encode())
                            show_early_dispense()
                            self.execute_dispense()

                            self.erogazioni_anticipate.add(sched_time)
                            time.sleep(10)
                            break  # evita doppia erogazione nel singolo ciclo
                    except Exception as e:
                        print("Errore parsing orario programmato:", e)
        except Exception as e:
            print("Errore rilevamento prossimità:", e)


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
        
    def loop(self):
     while True:
        self.mqtt.check_msg()
        self.check_proximity()
        self.check_food_level()
        self.check_button()
        
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
