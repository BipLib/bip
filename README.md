# PHP Bip Library
Create Stage Base & Fully Object Oriented Webhook Telegram Bots With PHP Bip Library.

Installation (early access)
------------
It is recommended to use [composer](https://getcomposer.org) to install the Bip :

```bash
composer require biplib/bip
```
A simple bot that takes first and last names and then prints both.
+ Stage :
```php
// StartStage.php
...
class StartStage extends Stage{
    #Manually assigned (non-primitive types)
    public Telegram $tel;
    public Bot $bot;

    #Automatically reassigned (primitive types)
    public string $name ;
    public string $lastName;

    public function controller(Bot $bot,Telegram $telegram){
        $this->tel = $telegram;
        $this->bot = $bot;

        $bot->startNode('getName');
    }
    public function getNameNode(){
        $this->tel->msg("What is your name ?");
        $this->bot->bindNode('getLastName');
    }
    public function getLastNameNode(){
        $this->name = Update::asObject()->message->text;
        $this->tel->msg("What is your lastname ?");
        $this->bot->bindNode('end');

    }
    public function endNode(){
        $this->lastName = Update::asObject()->message->text;
        $this->tel->msg("Your name is $this->name and your lastname is $this->lastName");
        unset($this->name,$this->lastname);
        $this->bot->bindNode('getName');
    }

}
```
+ Global Config Factory :
```php
//config.php
...
ConfigFactory::create('bot',[               /* creating config with name "bot"   */
    'token'     => 'your-api-key',
    
    /* more config items ...*/
    
]);
```
+ Run :
```php
//run.php
...
require __DIR__.'/vendor/autoload.php';
...
$bot = new Bot(
    new StartStage(),                       /* stage class in StartStage.php      */
    new LazyJsonDatabase('database.json'),  /* json file-based database for test  */
    new Telegram(),                         /* using telegram driver in stage     */
    ConfigFactory::get('bot'),              /* get "bot" config from config.php   */
);
$bot->run();
```
