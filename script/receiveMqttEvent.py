#!/usr/bin/python3
import paho.mqtt.client
import requests
import os
from dotenv import load_dotenv

# Cargar variables de entorno
load_dotenv('/var/www/html/.env')

topic_regular = "mc/data"
topic_realtime= "mc/real_time"

topics_mapping={
    topic_regular:'mqttRegularMessageHandler',
    topic_realtime:'mqttRealTimeMessageHandler'
}

def on_message(client, userdata, message):
       try:
        globals()[topics_mapping[message.topic]](message)
       except:
        pass

def mqttRegularMessageHandler(message):
   api_url = os.getenv('LARAVEL_API_URL', 'http://localhost')
   requests.post(f"{api_url}/api/v1/mqtt_input", {"message": message.payload})

def mqttRealTimeMessageHandler(message):
    api_url = os.getenv('LARAVEL_API_URL', 'http://localhost')
    requests.post(f"{api_url}/api/v1/mqtt_input/real-time", data={"message": message.payload})



def main():
    host = os.getenv('MQTT_HOST', 'mosquitto')
    port = int(os.getenv('MQTT_PORT', '1883'))
    username = os.getenv('MQTT_AUTH_USERNAME', 'enertec')
    password = os.getenv('MQTT_AUTH_PASSWORD', 'enertec2020**')
    client = paho.mqtt.client.Client("main_receiver", clean_session=False)
    client.subscribe([(topic_regular,0),(topic_realtime,0)])
    client.on_message = on_message
    client.username_pw_set(username, password)
    client.connect(host, port)
    client.loop_forever()

if __name__ == '__main__':
    main()
