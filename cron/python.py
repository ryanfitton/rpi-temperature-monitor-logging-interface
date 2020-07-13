#!/usr/bin/env python3

import time
import Adafruit_DHT

#Sensor variables
DHT_SENSOR = Adafruit_DHT.DHT22
DHT_PIN = 4

#Read data from sensor
humidity, temperature = Adafruit_DHT.read_retry(DHT_SENSOR, DHT_PIN)

#Loop data
while True:
    try:

        #Check if data returned is not #None'
        if humidity is not None and temperature is not None:

            #Output the data
            #print("Temp={0:0.1f} Humidity={1:0.1f}".format(temperature, humidity)#Human-readable
            
            print("{0:0.1f} {1:0.1f}".format(temperature, humidity))#No text description

            #Stop loop if data is found
            break

        #If data is 'None'
        #else:
            #print("Data returned 0")

		
    #Show error if data cannot be retrieved    
    except RuntimeError as e:
        # Reading doesn't always work! Just print error and we'll try again
        print("Failure reading data from sensor: ", e.args)