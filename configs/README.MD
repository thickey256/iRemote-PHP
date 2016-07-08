# Config File
This is the tricky part, once you have these setting correct then everythign else is easy!
Copy config-sample.php to config.php in the configs directory and fill in the blanks
	
## iRemote	
### Server
There are three different servers, you need to select the correct one or else things aren't going to work.
* https://b2vapi.bmwgroup.cn:8592 - China
* https://b2vapi.bmwgroup.us - USA
* https://b2vapi.bmwgroup.com - Europe / Rest of World

### Auth
OK so this is going to be the hardest part, in short you need to use an offical client (iOS/Android) and packet sniff some of the traffic so you can get the authorisation string. I had success following the instructions [here](https://shkspr.mobi/blog/2015/11/reverse-engineering-the-bmw-i3-api/)

### Everythign else
Everything else should be self explanatory


## Slack

### Hook URL
Fill in your slack hook url, you can grab it from [here](https://api.slack.com/tokens)

### User
This is the user you want messages to come from, I would suggest the name/reg of your car

### Channel
This is the channel your status will get sent to, you can use @yourname instead if you want a DM

### Icon
This is the emoji/image you wish to have displayed, it can be a jpeg/png. Slack will want the image to be square.


## Guzzle

### Request Timeout
This is how long we should keep trying to get data before giving up, if you are going to be sending data then this needs to be quite long.
It's a config variable to make it easy to fine tune if needed.