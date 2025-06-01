from umqtt.simple import MQTTClient

# --- MQTT Manager ---
class MQTTManager:
    def __init__(self, client_id, broker_ip, topic_cmd, topic_status, topic_alert, feeder, callback):
        self.client = MQTTClient(client_id, broker_ip)
        self.topic_cmd = topic_cmd
        self.topic_status = topic_status
        self.topic_alert = topic_alert
        self.feeder = feeder
        self.client.set_callback(callback)
        
    def connect(self):
        self.client.connect()
        self.client.subscribe(self.topic_cmd)
        self.client.subscribe(b'/petfeeder/schedule')
        
    def check_msg(self):
        self.client.check_msg()
        
    def publish_status(self, msg):
        self.client.publish(self.topic_status, msg)
        
    def publish_alert(self, msg):
        self.client.publish(self.topic_alert, msg)

