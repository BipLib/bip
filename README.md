# PHP Bip Library
Create Stage Base & Powerful Webhook Telegram Bots With PHP Bip Library.
 

Installation (v0.4.0 early access)
------------
It is recommended to use [composer](https://getcomposer.org) to install the Bip :

```bash
composer require biplib/bip
```
### Get Started With Simple NotePad Bot
1. After installing Bip Library, Create a new telegram bot with [BotFather](https://t.me/botfather) and get your bot token.
2. go to ```bots/NotePad/config.php``` and set your bot token and yours chat_id (you can get your chat_id with [userinfobot](https://t.me/userinfobot)).
3. for connect your bot to telegram webhook, you need to run following url in your browser:

   ```https://api.telegram.org/bot<YOUR_BOT_TOKEN>/setWebhook?url=<YOUR_SERVER_URL>/bip/bots/NotePad/index.php```  
4. Now you can start your bot and enjoy it.


### Make your own bots with Bip Library
1. Same as NotePad Bot, Create a new directory in ```bots``` directory and name it as you want.
2. You need to create a new Stage ```(extends Bip\App\Stage)``` and set it as your bot's first stage by Calling ```Bot::init(new YourStage())``` in ```index.php```.
3. By calling```Bot::run()``` method, you can run your bot.
4. Connect your bot to telegram webhook.
5. create a public ``` controller()``` method in your stage and set your bot's routes to ```Nodes``` in this method.
6. create your Nodes and set your bot's logic in them.
7. Now you can start your bot and enjoy it.
  


