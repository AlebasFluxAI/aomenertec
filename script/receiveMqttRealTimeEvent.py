#!/usr/bin/python3

import paho.mqtt.client as paho
import psycopg2
from psycopg2 import Error
from datetime import datetime
import pytz
import requests
import json

broker = "localhost"
port = 1883
topic = "mc/real_time"
username = 'enertec'
password = 'enertec2020**'
client = paho.Client("main_receiver", clean_session=False)
client.username_pw_set(username=username, password=password)
client.connect(broker)
client.subscribe(topic, qos=0)
tz = pytz.timezone("America/Bogota")
dt = datetime.now(tz=tz)
connection = psycopg2.connect(user="enertec",
                              password="rootenertec",
                              host="127.0.0.1",
                              port="5432",
                              database="enertec")

cursor = connection.cursor()


def on_connect(client, userdata, flags, rc):
    global flag_connected
    flag_connected = 1


def on_disconnect(client, userdata, rc):
    global flag_connected
    flag_connected = 0


def on_message(client, userdata, message):
    try:
        res = requests.post("http://localhost/api/v1/mqtt_input/real-time", data={"message": message.payload})
        print(" -> " + res.text)
    except (Exception, Error) as error:
        print("Error while connecting to PostgreSQL", error)


client.on_connect = on_connect
client.on_disconnect = on_disconnect
client.on_message = on_message
client.loop_forever()
