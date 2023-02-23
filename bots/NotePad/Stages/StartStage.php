<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace Bots\NotePad\Stages;


use Bip\App\Stage;
use Bip\Telegram\Webhook;


class StartStage extends Stage{
    # Public Properties Are Saved In Database And Restored Automatically.
    public array $pad = [];
    # Private Properties Are Not Saved In Database. Only Be Used In This Stage.
    private string $helpMessage = <<<MSG
        What Do You Want To Do ?
        /add    : add a note to your pad.
        /list   : list your notes.
        /delete : delete a note from your pad.
        /clear  : clear your pad.
        /about  : about this bot.
        MSG;
    private string $about = <<<MSG
        This bot is created by early access Bip Library [v0.4.0]. And maybe doesn't work properly on other versions.
        Highly recommended to use Bip Library [v0.4.0]. to run this bot.
        in the new version of Bip we will update this bot for compatibility with new version.
        You can contribute to this project on [github](https://github.com/biplib/bip).
        MSG;

    public function controller(){
        route('start')->whenMessageTextIs('/start');
        route('add')->whenMessageTextIs('/add');
        route('list')->whenMessageTextIs('/list');
        route('delete')->whenMessageTextIs('/delete');
        route('clear')->whenMessageTextIs('/clear');
        route('help')->whenMessageTextIs('/help');
        route('about')->whenMessageTextIs('/about');

    }
    public function start(){
        $message = <<<MSG
            Welcome To Bip Note Pad !!
            This is simple bot that can help you to save your notes.  
            click /help to see available commands. 
            MSG;
        msg($message);
    }
    public function help(){
        msg($this->helpMessage);
    }
    public function about(){
        msg($this->about);
    }
    public function add(){
        msg('Please Send Your Note To Be Added To Your Pad :');
        bindNode('addNote');

    }
    public function addNote(){
        if(isset(Webhook::getObject()->message->text)){
            $this->pad[] = Webhook::getObject()->message->text;
            msg('Your Note Has Been Added To Your Pad.');
            msg($this->helpMessage);
            bindNode('help');
        }else{
            msg('Only Text Is Allowed.');
        }
    }
    public function list(){
        if(empty($this->pad)){
            msg('Your Pad Is Empty.');
        }else{
            foreach ($this->pad as $noteNumber => $noteValue ){
                msg('Note Number : '.($noteNumber+1)."\n".$noteValue);
            }
        }
        msg($this->helpMessage);
        bindNode('help');
    }
    public function delete(){
        msg('Please Send The Number Of Note You Want To Delete :');
        bindNode('deleteNote');
    }
    public function deleteNote(){
        if(isset(Webhook::getObject()->message->text) && is_numeric((int)Webhook::getObject()->message->text)) {
            $noteNumber = (int) Webhook::getObject()->message->text;
            if(isset($this->pad[$noteNumber-1])){
                unset($this->pad[$noteNumber-1]);
                msg('Your Note Has Been Deleted From Your Pad.');
            }else{
                msg('Note Number Is Not Valid.');
            }
            msg($this->helpMessage);
            bindNode('help');
        }else{
            msg('Only Number Is Allowed.');
        }
    }
    public function clear(){
       msg('Are You Sure You Want To Clear Your Pad ? (yes/no)');
       bindNode('clearPad');
    }
    public function clearPad(){
        if(isset(Webhook::getObject()->message->text)) {
            $answer = Webhook::getObject()->message->text;
            if($answer == 'yes'){
                $this->pad = [];
                msg('Your Pad Has Been Cleared.');
            }else{
                msg('Your Pad Is Not Cleared.');
            }
            msg($this->helpMessage);
            bindNode('help');
        }else{
            msg('Only Text Is Allowed.');
        }
    }


}