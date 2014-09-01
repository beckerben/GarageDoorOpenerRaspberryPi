PROJECT GOALS
=================================================================================================
There are a plethora of Raspberry Pi garage door openers on the net.  I studied these and decided to add my own flare based on the specific requirements I had in mind which.  Many of the other solutions were overly complex in terms of authentication and dependencies and they lacked one or more elements I was looking for, so I decided to roll my own.

This project uses the model B+ raspberry pi running debian wheezy (Raspian) to do the following functions as it relates to my garage:

1. Monitor garage door state (open / closed) via a magnetic switch (http://www.amazon.com/gp/product/B0009SUF08/ref=oh_aui_detailpage_o00_s00?ie=UTF8&psc=1)

2. Toggle the garage door to open or close it via a relay module (http://www.amazon.com/gp/product/B0057OC6D8/ref=oh_aui_detailpage_o01_s00?ie=UTF8&psc=1)

3. Monitor the temperature and humidity inside the garage via a DHT11 sensor (http://www.amazon.com/gp/product/B0066YD3GM/ref=oh_aui_detailpage_o02_s00?ie=UTF8&psc=1)

4. Monitor the outdoor temperature via a JSON call to openweathermap.org

5. Visual confirmation of the garage door state via a USB webcam.

All of these components are connected to the Raspberry Pi via USB or via the GPIO pins.  I leverage a breadboard for connections to the magnetic switch and the DHT11 temperature sensor.  


KEY PROJECT COMPONENTS
=================================================================================================
The project consists of the following primary software components:

1. index.php = The login page, i used simple authentication to check for my garage code which is embedded in the index.php file.

2. garage_status.php = This is the controlling page with inline PHP which shows the commands and current state of the door.  It sends commands for wiringpi, fswebcam and also calls the python script for getting the garage temperature and makes the JSON call for the outdoor temperature.

3. getGarageTemp - The python script for getting the readings from the DHT11 module.


PROJECT DEPENDENCIES
=================================================================================================
The project has the following dependencies:
1. FSWebCam - command line utility for taking a picture from the webcam

2. Python - for interacting with the temperature sensor

3. Adafruit_DHT python library - for interacting with the temperature sensor from Python

4. PHP and Apache2 - used to power the front-end website

5. Wiringpi - provides easy to use command for reading and writing to GPIO pins for toggling the switch and reading from the magnetic relay (http://wiringpi.com/download-and-install/)

6. Twitter Bootstrap - leveraged the twitter bootstrap for styling of the website, these files are contained within the www directory already as part of this project.



PROJECT SOFTWARE SETUP
=================================================================================================
Before starting, ensure the dependencies are met and the hardware is setup and configured.  See the wiki for more information on the hardware setup.

1. Get Raspberry Pi setup with Raspian (Debian Wheezy)

2. Confirm internet connection via WIFI. 

3. Install wiringpi (see link above)

4. Install PHP / Apache2
sudo apt-get install apache2
sudo apt-get install php5
sudo apt-get install libapache2-mod-php5
sudo /etc/init.d/apache2 restart

5. Install Adafruit_DHT python library:
git clone https://github.com/adafruit/Adafruit_Python_DHT.git
cd Adafruit_Python_DHT
sudo apt-get install build-essential python-dev
sudo python setup.py install

6. Setup www-data account used by the web server with permissions to execute fswebcam and the python script without a password:
sudo visudo
www-data ALL=(ALL) NOPASSWD: /usr/bin/fswebcam, /var/www/getGarageTemp

7. The magnetic switch leverages a pull down mode for the GPIO pin that it was connected to...in my case wiringpi pin 0.  To accomplish setting this and also setting the default state of the relay switch on wiringpi pin 1. I leveraged a script in /etc/init.d/garageinit.sh that looked like this:

File contents of /etc/init.d/garageinit.sh:

case "$1" in
start)
echo "Starting Relay"
# Turn 1 on which keeps relay off
/usr/local/bin/gpio mode 0 down
/usr/local/bin/gpio write 1 1
#Start Gpio
/usr/local/bin/gpio mode 1 out
;;
stop)
echo "Stopping gpio"
;;
*)
echo "Usage: /etc/init.d/garagerelay {start|stop}"
exit 1
;;
esac
exit 0

This startup script is executed via rc.local on startup of the pi.

7. Copy the www files down:
git clone https://github.com/beckerben/GarageDoorRaspberryPi.git
copy the www files from the project to your apache www directory

8. Setup the password in the www/index.php file and tweak the page.

9. Setup the getGarageTemp with the GPIO pin number for the DHT11, in my case GPIO 27.

10. Setup the garage_status.php referencing the correct wiringpi pins for the magnetic sensor and relay switch.  Also change your location for the JSON call for outdoor temperature.

11. Plugin a compatible webcam via USB.

12.  If all goes well, you should be able to hit the page via a browser and login.  I used NAT routing to allow external access to my opener.  


