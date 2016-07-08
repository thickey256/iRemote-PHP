# iRemote-PHP
This is a totally unofficial and not supported interface for BMWs iRemote private API.

Your use of these API calls is entirely at your own risk. They are neither officially provided nor sanctioned.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

This is simply a bunch of curl calls that I wrapped up using PHP, all the credit and hard work was done by others and [https://github.com/edent/BMW-i-Remote](https://github.com/edent/BMW-i-Remote) was my main source of information.

## About
This currently posts the status of your car whenever anything changes or whenever the status script is called. I have designed it to be run as a cron process.  I would suggest not hammering the private API and make a call once every 5 minutes. Once your config setting are correct the code will take care of authentication and expired tokens etc.

Everything here has been copied from [here](https://shkspr.mobi/blog/2015/11/reverse-engineering-the-bmw-i3-api/), Which was the original and fantastic python implementation of API calls to ConnectedDrive.

## Requirements
* A BMW connected to [BMWs ConnectedDrive service](https://www.bmw-connecteddrive.co.uk/app/gb/index.html#/portal)
* A PHP server (5.3 or later) (A [raspberry PI](https://www.raspberrypi.org/documentation/remote-access/web-server/apache.md) would be perfectly fine for this)
* PHP Composer
* The ability to setup a cronjob
* Write access to configs directory

## How To Setup
* Clone the repo
* [Reverse engineer your Auth string](https://shkspr.mobi/blog/2015/11/reverse-engineering-the-bmw-i3-api/)
* Run composer
* Setup Cronjob to run slack_cron.php

## Saving Access Tokens and previous statuses 
Once a token has been requested it's good for 8 hours, so rather that making multiple unnecessary requests the token gets written into the configs directory for future use. This will automatically get refreshed when needed. The timestamp and event information is also written to a json file in the configs directory, this is so you don't get a slack message every time the script has run and nothing has changed.

## Unknowns etc
I have only tried this with my i3, I assume you will get much the same results if you have an i8. Currently to date BEV i3's will want to make a little change so they don't get REX specific info, this will probably be a config setting or even an API call to sort out at some point.

It is possible to send requests to your vehicle such as unlock the doors etc. I purposely haven't coded these request because it's not something I honestly think is a good idea to do yourself. The calls are all documented [here](https://shkspr.mobi/blog/2015/11/reverse-engineering-the-bmw-i3-api/) if you decided you want to use them. But I'm happy for this to just be a readonly set of functions at the moment.

This is my first 'proper' open source code, so it is without doubt rough around the edges. Please feel free to submit pull requests with changes/improvements etc.