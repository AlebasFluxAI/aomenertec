#!/usr/bin/python3
import paho.mqtt.client
import requests


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
   requests.post("http://localhost/api/v1/mqtt_input", {"message": message.payload})

def mqttRealTimeMessageHandler(message):
    requests.post("http://localhost/api/v1/mqtt_input/real-time", data={"message": message.payload})



def main():
    host = 'localhost'
    port = 1883
    username = "enertec"
    password = "enertec2020**"
    client = paho.mqtt.client.Client("main_receiver", clean_session=False)
    client.subscribe([(topic_regular,0),(topic_realtime,0)])
    client.on_message = on_message
    client.username_pw_set(username, password)
    client.connect(host, port)
    client.loop_forever()

if __name__ == '__main__':
    main()
