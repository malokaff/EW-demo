# python3.6

import random
import mysql.connector
import logging
import logging.handlers
import config

sql_pass = config.pwd_mysql

def GetValue(value):
	try:
		conn = mysql.connector.connect(host=config.ip_mysql,user=config.user_mysql,password=config.pwd_mysql, database="MQTT")
		cursor = conn.cursor()
		cursor.execute("""SELECT %s FROM `mqtt-value` WHERE id = '1'""" % (value))
		rows = cursor.fetchall()
		for row in rows:
			return row[0]
		cursor.close()
	except:
		my_logger.info("ERROR: pb d'acces base de donnee pour la fonction getEtat")

def updateValue(value):
	try:
		conn = mysql.connector.connect(host=config.ip_mysql,user=config.user_mysql,password=config.pwd_mysql, database="MQTT")
		cursor = conn.cursor()
		cursor.execute("""UPDATE `mqtt-value` SET value='%s' WHERE id='1'""" % (value))
		cursor.execute("commit")
		cursor.close()
	except mysql.connector.Error as err:
		my_logger.info("ERROR SQL :Something went wrong in function updateValue: {}", err)


from paho.mqtt import client as mqtt_client

broker = config.ip_mqttbroker
port = 1883
topic = "python/mqtt-pensando"
# generate client ID with pub prefix randomly
id=format(random.randint(0, 1000))
client_id = 'python-mqtt-' + id
username = 'mqtt'
password = config.pwd_mqtt


def connect_mqtt() -> mqtt_client:
    def on_connect(client, userdata, flags, rc):
        if rc == 0:
            my_logger.info("Connected to MQTT Broker!")
        else:
            my_logger.info("Failed to connect, return code %d\n", rc)

    client = mqtt_client.Client(client_id,False)
    client.username_pw_set(username, password)
    client.on_connect = on_connect
    client.connect(broker, port)
    return client


def subscribe(client: mqtt_client):
    def on_message(client, userdata, msg):
        content = msg.payload.decode()
        #print(content)
        payload = format(msg.payload.decode())
        #print("old `" + old_payload + "` new " + new_payload)
        #print("Received `" + payload + "` from " + topic)
        updateValue(payload)

    client.subscribe(topic)
    client.on_message = on_message


def run():
    client = connect_mqtt()
    subscribe(client)
    client.loop_forever()


if __name__ == '__main__':
	LOG_FILENAME = '/var/www/EW-demo-frontend/logging-python-sql.log'
	# definition du logging
	my_logger = logging.getLogger('MQTT_PYTHON')
	my_logger.setLevel(logging.DEBUG)

# definition de la taille des fichiers de logs, de la rotation et du format
	handler = logging.handlers.RotatingFileHandler(LOG_FILENAME, maxBytes=256000, backupCount=5)
	# create formatter
	formatter = logging.Formatter("%(asctime)s - %(name)s - %(levelname)s - %(message)s")
	# add formatter to handler
	handler.setFormatter(formatter)
	my_logger.addHandler(handler)
	try:
		run()
	except:
		my_logger.exception('Got exception on main handler')
		raise

