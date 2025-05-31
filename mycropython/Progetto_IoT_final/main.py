from controller import SmartPetFeeder
import ujson


# --- Callback MQTT esterna ---
def mqtt_callback(topic, msg):
    global scheduled_time
    print('Ricevuto:', topic, msg)
    if topic == b'/petfeeder/cmd' and msg == b'dispense':
        feeder.execute_dispense()
    elif topic == b'/petfeeder/schedule':
        try:
            scheduled_time = ujson.loads(msg)
            print("Orari programmati ricevuti:", scheduled_time)
        except Exception as e:
            print("Errore parsing JSON:", e)

# --- MAIN ---
feeder = SmartPetFeeder()
feeder.setup()
feeder.loop()