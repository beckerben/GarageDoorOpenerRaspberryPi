PROJECT GOALS
=================================================================================================
There are a plethora of Raspberry Pi garage door openers on the net.  I studied those and decided to add my own flare based on the specific requirements I had in mind.  Many of the other solutions were overly complex in terms of authentication and dependencies and they lacked one or more elements I was looking for, so I decided to roll my own.

This project uses the model B+ raspberry pi running debian wheezy (Raspian) to do the following functions as it relates to my garage:

1. Monitor garage door state (open / closed) via a magnetic switch [Product Link](http://www.amazon.com/gp/product/B0009SUF08/ref=oh_aui_detailpage_o00_s00?ie=UTF8&psc=1)

2. Toggle the garage door to open or close it via a relay module [Product Link](http://www.amazon.com/gp/product/B0057OC6D8/ref=oh_aui_detailpage_o01_s00?ie=UTF8&psc=1)

3. Monitor the temperature and humidity inside the garage via a DHT11 sensor [Product Link](http://www.amazon.com/gp/product/B0066YD3GM/ref=oh_aui_detailpage_o02_s00?ie=UTF8&psc=1)

4. Monitor the outdoor temperature via a JSON call to openweathermap.org

5. Visual confirmation of the garage door state via a USB webcam. [Known Supported Cams](http://elinux.org/RPi_USB_Webcams)

All of these components are connected to the Raspberry Pi via USB or via the GPIO pins.  I leveraged a breadboard for connections to the magnetic switch and the DHT11 temperature sensor.  

###Screenshots
This is the output of the simple index.php page that presents the login dialog.

![Login Page](https://github.com/beckerben/GarageDoorRaspberryPi/blob/master/misc/Login.png "Login Page")

This is the output of the garage_status.php.

![Garage Status Page](https://github.com/beckerben/GarageDoorRaspberryPi/blob/master/misc/GetGarageStatus.png "Garage Status Page")

Project components mounted to a piece of cardboard and secured via zipties, if you wanted to get crazy you could 3d print a custom case or use some plexi glass...a project for another day!

![Installed Board](https://github.com/beckerben/GarageDoorRaspberryPi/blob/master/misc/BoardOverview.jpg "Installed Picture")

For more information for setup and configuration of the software components, please see the [Wiki](https://github.com/beckerben/GarageDoorRaspberryPi/wiki).
