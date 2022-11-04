import time
import paho.mqtt.client as paho
broker =  "3.12.98.178"
port = 1883
topic = "mc/real_time"
username = 'enertec'
password = 'enertec2020**'
client= paho.Client("client-001",clean_session=False)
client.username_pw_set(username=username, password=password)
client.connect(broker)#connect
a=0
while(True):
    a+=1
    client.publish(topic,str(a),qos=0)
    print(str(a))
    time.sleep(1)

