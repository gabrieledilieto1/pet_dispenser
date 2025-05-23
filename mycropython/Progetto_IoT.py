import machine
import time
import network
from machine import Pin, PWM, I2C, ADC
import ssd1306
import framebuf
from umqtt.simple import MQTTClient

# --- Configurazione OLED ---
WIDTH = 128
HEIGHT = 64
SCL_PIN = 17
SDA_PIN = 16
i2c = I2C(0, scl=Pin(SCL_PIN), sda=Pin(SDA_PIN))
display = ssd1306.SSD1306_I2C(WIDTH, HEIGHT, i2c)

# --- Logo Pet Feeder (usa il tuo pet_feeder_logo) ---
pet_feeder_logo = bytearray(b'\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x7f\xc7\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xe7?\x00\xe0\x08\x07\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfe\x0f\x9f\x03\x1f\xe7\xf9\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\xfc\x1c\x9f\xff\xff\xfc\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfes\xfc\x9f\xf0\xff\xfe\x7f\xff\xff\xff\xff\xff\xff\xff\xff\xff\x07\xfe\x1f\xfe\xfc\xff\x9f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\x87\xff\xfe?\xdf\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf0?\xff\x00\x1f\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x1f\xfe?\xe0\x10\x00\x0f\xff\xff\xff\xff\xff\xff\xff\xf9\xb8?\xfe\x7f\xc0\x00\xf8\x07\xff\xff\xff\xff\xff\xff\xff\xf9\xe3\xff\xf8\xff\xe7\xff\x00\x0f\xff\xff\xff\xff\xff\xff\xff\xfe\x00\x7f\xe3\xff\xe7\xff\xf8\x0f\xff\xff\xff\xff\xff\xff\xf8\x0f\xf9\xff\xe7\xff\xe7\xff\xf8\x0f\xff\xff\xff\xff\xff\xff\xe3\xf0\xe3\xff\xf3\xff\xe7\xff\xf8\x07\xff\xff\xff\xff\xff\xff\xcf\xff\x0f\xff\xf9\xff\xe7\xff\xf8\x07\xff\xff\xff\xff\xff\xff\xcf\x1f\x9f\xff\xfc\x7f\xe7\x8f\xf8\x07\xff\xff\xff\xff\xff\xff\xf0G\x9f\xff\xff\x1f\xe7\x87\xf8\x07\xff\xff\xff\xff\xff\xff\xff\xf1\x9f\x9f\xff\xc7\xe73\xf8\x07\xff\xff\xff\xff\xff\xff\xff\xfe\x1e\x7f\xff\xf1\xees\xf8\x07\xff\xff\xff\xff\xff\xff\xff\xff\xc1\xff\xfd\xfc\xcc\xf3\xf8\x03\xff\xff\xff\xff\xff\xff\xff\xff\x1f\xff\xe3\xfeQ\xe08\x03\xff\xff\xff\xff\xff\xff\xff\xfc\xff\xf1\xcf\xff\x0f\xc0<\x03\xff\xff\xff\xff\xff\xff\xff\xf1\xfc\x0f\x9f\xff?\x00\x1c\x03\xff\xff\xff\xff\xff\xff\xff\xf3\xc3??\xff\x1f\x00\x1c\x03\xff\xff\xff\xff\xff\xff\xff\xf3\xcf\x87\x9f\xff\x00\x1c\x1c\x01\xff\xff\xff\xff\xff\xff\xff\xf8\x1f\x80\x1f\xff \xef\x8e\x01\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x07\xfe\x07D\xce\x01\xff\xff\xff\xff\xff\xff\xff\xff\xff\xc0\x01\xfc\x0f\xff\x02\x00\xff\xff\xff\xff\xff\xff\xff\xff\xfe<\x18\xf8p\x01\xf2\x00\xff\xff\xff\xff\xff\xff\xff\xff\xfc\xfe\x7f\xf0\xff\xff\xf8\x00\xff\xff\xff\xff\xff\xff\xff\xfc\x00\x00\x00\x00\xff\xff\xf0\x1f\xff\xff\xff\xff\xff\xff\xff\xff\xf0\x00\x00\x00\x00\x00\x07\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xf8\x1c\x02\x00\xf8\x0c\x06\x03\x03\xe0\x10\x1f\xff\xff\xff\xff\xf9\xc4\x7f\xcf\xf8\xfc\xfe?\x18c\xf1\x8f\xff\xff\xff\xff\xf9\xc4\x7f\xcf\xf8\xfc\xfe?\x1ec\xf1\xcf\xff\xff\xff\xff\xf8\x04\x07\xcf\xf8\x0c\x06\x03\x1e 0\x1f\xff\xff\xff\xff\xf8\xfc\x7f\xcf\xf8\xfc\xfe?\x1cc\xf1\x1f\xff\xff\xff\xff\xf9\xfc\x03\xcf\xf8\xfc\x06\x03\x00\xe0\x11\x8f\xff\xff\xff\xff\xf9\xfc\x03\xcf\xf8\xfc\x06\x03\x07\xe0\x11\xc7\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff\xff')

# --- Logo Wi-Fi (devi sostituire con un vero bytearray) ---
wifi_logo = bytearray([
    0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,
    0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,
    0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,
    0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,
    0x00,0x01,0x03,0x07,0x0E,0x1C,0x38,0x70,0xE0,0xC0,0x81,0x03,0x07,0x0E,0x1C,0x38,
    0x70,0xE0,0xC0,0x81,0x03,0x07,0x0E,0x1C,0x38,0x70,0xE0,0xC0,0x80,0x01,0x03,0x07,
    0x0E,0x1C,0x38,0x70,0xE0,0xC0,0x80,0x00,0x01,0x03,0x07,0x0E,0x1C,0x38,0x70,0xE0,
    0xC0,0x80,0x00,0x00,0x01,0x03,0x07,0x0E,0x1C,0x38,0x70,0xE0,0xC0,0x80,0x00,0x00,
    0x00,0x01,0x03,0x07,0x0E,0x1C,0x38,0x70,0xE0,0xC0,0x00,0x00,0x00,0x00,0x01,0x03,
    0x07,0x0E,0x1C,0x38,0x70,0xE0,0xC0,0x00,0x00,0x00,0x00,0x01,0x03,0x07,0x0E,0x1C,
    0x38,0x70,0xE0,0xC0,0x00,0x00,0x00,0x00,0x00,0x01,0x03,0x07,0x0E,0x1C,0x38,0x70,
    0xE0,0xC0,0x00,0x00,0x00,0x00,0x00,0x00,0x01,0x03,0x07,0x0E,0x1C,0x38,0x70,0xE0,
    0xC0,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,
    0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00,
]) # 16x16 pixels, adattalo al bisogno

# --- Funzioni per mostrare immagini e testo ---
def draw_main_screen():
    global battery_voltage
    display.fill(0)
    fb_logo = framebuf.FrameBuffer(pet_feeder_logo, 128, 64, framebuf.MONO_HLSB)
    display.blit(fb_logo, 0, 0)
    fb_wifi = framebuf.FrameBuffer(wifi_logo, 16, 16, framebuf.MONO_HLSB)
    display.blit(fb_wifi, WIDTH - 16, 0)  # in alto a destra
    # Mostra tensione batteria in basso a destra
    display.text("Batt: {:.2f}V".format(battery_voltage), WIDTH - 58, HEIGHT - 10)
    display.show()

def show_dispense_message():
    display.fill(0)
    display.text("Erogazione in corso...", 10, 28)
    display.show()
    time.sleep(2)
    draw_main_screen()


# --- Sensori e periferiche ---
class IRSensor:
    def __init__(self, pin_num):
        self.sensor = Pin(pin_num, Pin.IN)
        
    def is_obstructed(self):
        return self.sensor.value() == 1


class UltrasonicSensor:
    def __init__(self, trig_pin, echo_pin):
        self.trig = Pin(trig_pin, Pin.OUT)
        self.echo = Pin(echo_pin, Pin.IN)
        
    def read_distance(self):
        self.trig.value(0)
        time.sleep_us(2)
        self.trig.value(1)
        time.sleep_us(10)
        self.trig.value(0)
        while self.echo.value() == 0:
            start = time.ticks_us()
        while self.echo.value() == 1:
            end = time.ticks_us()
        duration = time.ticks_diff(end, start)
        return (duration / 2) / 29.1  # cm


class ServoDispenser:
    def __init__(self, pin_num):
        self.servo = PWM(Pin(pin_num), freq=50)
        
    def dispense(self):
        for duty in range(40, 116, 5):  # da 40 a 115 con step 5
            self.servo.duty(duty)
            time.sleep(0.05)  
        time.sleep(2) 

        for duty in range(115, 39, -5):  # da 115 a 40 con step -5
            self.servo.duty(duty)
            time.sleep(0.05)

    # Assicura che servo sia a posizione chiusa
        self.servo.duty(40)
        time.sleep(0.2)


class BuzzerManager:
    def __init__(self, pin_start, pin_alarm):
        self.buzzer_start = Pin(pin_start, Pin.OUT)
        self.buzzer_alarm = Pin(pin_alarm, Pin.OUT)
        
    def start_sound(self):
        self.buzzer_start.value(1)
        time.sleep(0.2)
        self.buzzer_start.value(0)
        
    def alarm_sound(self):
        self.buzzer_alarm.value(1)
        time.sleep(1)
        self.buzzer_alarm.value(0)


class WiFiManager:
    def __init__(self, ssid, password):
        self.ssid = ssid
        self.password = password
        
    def connect(self):
        wlan = network.WLAN(network.STA_IF)
        wlan.active(True)
        wlan.connect(self.ssid, self.password)
        timeout = 15
        start = time.time()
        while not wlan.isconnected():
            if time.time() - start > timeout:
                raise RuntimeError("Timeout connessione Wi-Fi")
            time.sleep(1)
        print('Connesso a Wi-Fi:', wlan.ifconfig())
       

class BatteryMonitor:
    def __init__(self, adc_pin, r1, r2):
        self.adc = ADC(Pin(adc_pin))
        self.adc.atten(ADC.ATTN_11DB)
        self.adc.width(ADC.WIDTH_12BIT)
        self.R1 = r1
        self.R2 = r2
        
    def read_voltage(self):
        raw = self.adc.read()
        voltage = (raw / 4095) * 3.6 * (self.R1 + self.R2) / self.R2
        return voltage


# --- MQTT Manager ---
class MQTTManager:
    def __init__(self, client_id, broker_ip, topic_cmd, topic_status, topic_alert, feeder):
        self.client = MQTTClient(client_id, broker_ip)
        self.topic_cmd = topic_cmd
        self.topic_status = topic_status
        self.topic_alert = topic_alert
        self.feeder = feeder
        self.client.set_callback(self.on_message)
        
    def connect(self):
        self.client.connect()
        self.client.subscribe(self.topic_cmd)
        
    def check_msg(self):
        self.client.check_msg()
        
    def publish_status(self, msg):
        self.client.publish(self.topic_status, msg)
        
    def publish_alert(self, msg):
        self.client.publish(self.topic_alert, msg)
        
    def on_message(self, topic, msg):
        print('Ricevuto:', topic, msg)
        if topic == self.topic_cmd and msg == b'dispense':
            self.feeder.execute_dispense()


# --- Smart Pet Feeder ---
class SmartPetFeeder:
    def __init__(self):
        self.ir = IRSensor(15)
        self.ultra_animal = UltrasonicSensor(4, 5)
        self.ultra_food = UltrasonicSensor(12, 13)
        self.servo = ServoDispenser(18)
        self.buzzer = BuzzerManager(21, 22)
        self.wifi = WiFiManager('WINDTRE-070898', '2rwdvzxaw4yjdma8')
        self.batt_monitor = BatteryMonitor(adc_pin=34, r1=100000, r2=100000)
        self.mqtt = MQTTManager(
            client_id='petfeeder',
            broker_ip='broker.hivemq.com',
            topic_cmd=b'/petfeeder/cmd',
            topic_status=b'/petfeeder/status',
            topic_alert=b'/petfeeder/alert',
            feeder=self
        )
        self.button = Pin(14, Pin.IN, Pin.PULL_UP)
        self.last_button_state = self.button.value()
        
    def setup(self):
        self.wifi.connect()
        self.mqtt.connect()
        
    def execute_dispense(self):
        self.buzzer.start_sound()
        self.servo.dispense()
        if self.ir.is_obstructed():
            self.buzzer.alarm_sound()
            self.mqtt.publish_alert(b'Accumulo rilevato!')
        self.mqtt.publish_status(b'Erogazione completata.')
        print("Erogazione completata.")
        show_dispense_message()
        
    def check_proximity(self):
        try:
            dist = self.ultra_animal.read_distance()
            print("Distanza animale:", dist)
            if dist < 20:
                print("Animale rilevato, erogazione")
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
                show_text(["Attenzione:", "livello croccantini", "basso!"])
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
        
    def loop(self):
        while True:
            self.mqtt.check_msg()
            self.check_proximity()
            self.check_food_level()
            self.check_battery()
            self.check_button()
            draw_main_screen()
            time.sleep(1)

# --- MAIN ---
feeder = SmartPetFeeder()
feeder.setup()
feeder.loop()
