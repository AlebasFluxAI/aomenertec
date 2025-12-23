#!/usr/bin/python3

import paho.mqtt.client as paho
import psycopg2
from psycopg2 import Error
from datetime import datetime
import pytz
import requests
import json
import os
from dotenv import load_dotenv

# Cargar variables de entorno
load_dotenv('/var/www/html/.env')

broker = os.getenv('MQTT_HOST', 'mosquitto')
port = int(os.getenv('MQTT_PORT', '1883'))
topic = "mc/real_time"
username = os.getenv('MQTT_AUTH_USERNAME', 'enertec')
password = os.getenv('MQTT_AUTH_PASSWORD', 'enertec2020**')
client = paho.Client("main_receiver", clean_session=False)
client.username_pw_set(username=username, password=password)
client.connect(broker)
client.subscribe(topic, qos=0)
tz = pytz.timezone("America/Bogota")
dt = datetime.now(tz=tz)
connection = psycopg2.connect(
    user=os.getenv('DB_USERNAME', 'sail'),
    password=os.getenv('DB_PASSWORD', 'password'),
    host=os.getenv('DB_HOST', 'pgsql'),
    port=os.getenv('DB_PORT', '5432'),
    database=os.getenv('DB_DATABASE', 'enertec')
)

cursor = connection.cursor()


def on_connect(client, userdata, flags, rc):
    global flag_connected
    flag_connected = 1


def on_disconnect(client, userdata, rc):
    global flag_connected
    flag_connected = 0


def on_message(client, userdata, message):
    try:
        api_url = os.getenv('LARAVEL_API_URL', 'http://localhost')
        res = requests.post(f"{api_url}/api/v1/mqtt_input/real-time", data={"message": message.payload})
        print(" -> " + res.text)
    except (Exception, Error) as error:
        print("Error while connecting to PostgreSQL", error)


client.on_connect = on_connect
client.on_disconnect = on_disconnect
client.on_message = on_message
client.loop_forever()
