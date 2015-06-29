# ShortURL
This project is to help create friendly short URL's for RAMCO Meetings and Classes. As configured by default, everytime you nagivate to shortcode.php, the script pulls any classes and meetings created in the past two days and creates short URLs for registration and mobile check in.

This is intended to be run via a cron job or can also be loaded automatically through an iFrame in the meeting record itself.


# Usage
You need to create custom attributes for following entity types:

Classes:
MAR_short_url,
MAR_checkinlink

Meetings:
MAR_short_url,
MAR_checkinlink


*you will have to rename the prefix

You also need to get a Google API Key to use their URL Shortener API

Get API key from : http://code.google.com/apis/console/

