{\rtf1\ansi\ansicpg1252\cocoartf2822
\cocoatextscaling0\cocoaplatform0{\fonttbl\f0\fswiss\fcharset0 Helvetica;}
{\colortbl;\red255\green255\blue255;}
{\*\expandedcolortbl;;}
\paperw11900\paperh16840\margl1440\margr1440\vieww11520\viewh8400\viewkind0
\pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural\partightenfactor0

\f0\fs24 \cf0 import paho.mqtt.client as mqtt\
import time\
\
# The callback for when the client receives\
# a CONNACK response from the server.\
def on_connect(client, userdata, flags, rc):\
    global loop_flag\
    print("Connected with result code " + str(rc))\
\pard\tx720\tx1440\tx1956\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural\partightenfactor0
\cf0     print("\\n connected with client " + str(client))\
\pard\tx720\tx1440\tx2160\tx2880\tx3600\tx4320\tx5040\tx5760\tx6480\tx7200\tx7920\tx8640\pardirnatural\partightenfactor0
\cf0     print("\\n connected with userdata " + str(userdata))\
    print("\\n connected with flags " + str(flags))\
    loop_flag = 0\
\
try:\
    client = mqtt.Client()\
    client.on_connect = on_connect  # attach the function to callback\
    client.connect("iot.eclipse.org", 1883, 60)\
    # client.connect("test.mosquitto.org", 1883, 60)\
    client.loop_start()  # the loop() method periodically checks for callback events\
\
    loop_flag = 1\
    counter = 0\
    while loop_flag == 1:\
        print("\\nwaiting for callback to occur ", counter)\
        time.sleep(0.1)  # pause for 1/10 seconds\
        counter += 1\
\
except Exception as e:\
    print('exception ', e)\
\
finally:\
    client.disconnect()\
    client.loop_stop()}