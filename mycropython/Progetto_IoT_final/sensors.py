import time
import network
from machine import Pin, PWM, ADC
from time_manager import sync_time

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

        self.servo.duty(40)
        time.sleep(0.2)

class BuzzerManager:
    def __init__(self, pin_start, pin_alarm):
        self.buzzer_start = Pin(pin_start, Pin.OUT)
        self.buzzer_alarm = Pin(pin_alarm, Pin.OUT)
        
    def start_sound(self):
        self.buzzer_start.value(1)
        time.sleep(1)
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
        sync_time()

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