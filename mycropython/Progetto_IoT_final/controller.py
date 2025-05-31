import time 
from sensors import *
from main import mqtt_callback
from mqtt_manager import MQTTManager
from oled_screen import draw_main_screen, show_dispense_message


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
        global battery_voltage
        battery_voltage = 0.0
        
    def setup(self):
        self.wifi.connect()
        self.mqtt.connect()
        draw_main_screen()
        
    def execute_dispense(self):
        self.buzzer.start_sound()
        self.servo.dispense()
        if self.ir.is_obstructed():
            self.buzzer.start_sound()
            print("Ciao MARIO")
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
        
    def check_battery(self):
        global battery_voltage
        battery_voltage = self.batt_monitor.read_voltage()
        print("Tensione batteria:", battery_voltage)
        
    def check_button(self):
        current_state = self.button.value()
        if self.last_button_state == 1 and current_state == 0:
            print("Pulsante premuto, erogazione manuale")
            self.execute_dispense()
        self.last_button_state = current_state
        
    def check_schedule(self):
        global scheduled_time
        print("scheduled_time attuale:", scheduled_time)
        if scheduled_time:
            now = time.localtime(time.mktime(time.localtime()) + 3600*2)  # aggiunge 2 ore all’ora UTC
            current_time = "{:02d}:{:02d}:{:02d}".format(now[3], now[4], now[5])  # ora con secondi, es. "10:30:45"
            for schedule in scheduled_time:
                print("Controllo orario schedule:", schedule['time'][:8], "vs", current_time)
                if schedule['time'][:8] == current_time:
                    print("Orario programmato raggiunto:", current_time)
                    self.execute_dispense()
        
    def loop(self):
     while True:
        self.mqtt.check_msg()
        self.check_proximity()
        self.check_food_level()
        self.check_battery()
        self.check_button()
        self.check_schedule()
        
        # Stampa l’orario programmato attuale (se esiste)
        if scheduled_time:
            print("Orario programmato attuale:", scheduled_time)
        else:
            print("Nessun orario programmato")
        
        draw_main_screen()
        time.sleep(1)