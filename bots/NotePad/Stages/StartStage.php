<?php
/*
 * This file is part of the Bip Library.
 *
 *  (c) Sepehr Soheili <sepehr0617@gmail.com>
 *
 *  For the full copyright and license information, please view the
 *   LICENSE file that was distributed with this source code.
 */

namespace bots\NotePad\Stages;


use Bip\App\Stage;
use Bip\Logger\Logger;
use Bip\Telegram\Call;
use Bip\Telegram\Webhook;


class StartStage extends Stage{
    # Public Properties Are Saved In Database And Restored Automatically.
    public array $pad = [];

    # Private Properties Are Not Saved In Database. Only Be Used In This Stage.
    private string $about = <<<MSG
        This bot is created by early access Bip Library [v0.6.x]. And maybe doesn't work properly on other versions.
        Highly recommended to use Bip Library [v0.6.x]. to run this bot.
        in the new version of Bip we will update this bot for compatibility with new version.
        You can contribute to this project on [github](https://github.com/biplib/bip).
        MSG;

    # Controller Controls The Logic Of The Stage. It Is Called Automatically.
    public function controller(){
        route('start')->onMessageText('/start');
        route('add')->onMessageText('/add');
        route('list')->onMessageText('/list');
        route('delete')->onMessageText('/delete');
        route('clear')->onMessageText('/clear');
        route('about')->onMessageText('/about');

    }

    # Start Node.
    public function start(){
        Logger::add('Start Node Called.');
        Call::setMyCommands(
            commands: [
                ['command' => '/start', 'description' => 'Restart The Bot'],
                ['command' => '/add', 'description' => 'Add A Note To Your Pad'],
                ['command' => '/list', 'description' => 'List Your Notes'],
                ['command' => '/delete', 'description' => 'To Delete A Note From Your Pad'],
                ['command' => '/clear', 'description' => 'Clear Your Pad'],
                ['command' => '/about', 'description' => 'About This Bot'],
            ],
            scope: [
                'type' => 'chat',
                'chat_id'=> Webhook::getObject()->message->chat->id
            ]
        );
        $message = <<<MSG
            Welcome to Bip NotePad !!
            This is simple bot that can help you to save your notes.  
            MSG;
        msg($message);
        closeNode();
    }
    # End Start Node.

    # Default Node.
    public function default(){
        msg('Please select one of the menu commands.');
    }
    # End Default Node.

    # About Node.
    public function about(){
        msg($this->about);
        closeNode();
    }
    # End About Node.

    # Add Note To Pad Nodes.
    public function add(){
        msg('Please send your note to be added to your pad :');
        bindNode('addNote');
    }
    public function addNote(){
        if(isset(Webhook::getObject()->message->text)){
            $this->pad[] = Webhook::getObject()->message->text;
            msg('Note added successfully.');
            closeNode();
        }else{
            msg('You can only add text notes, Please send text note.');
        }
    }
    # End Add Note To Pad Nodes.

    # List Notes Nodes.
    public function list(){
        if(empty($this->pad)){
            msg('Your pad is empty.');
        }else{
            foreach ($this->pad as $noteNumber => $noteValue ){
                msg('Note number : '.($noteNumber+1)."\n".$noteValue);
            }
        }
        closeNode();
    }
    # End List Notes Nodes.

    # Delete Note From Pad Nodes.
    public function delete(){
        if (empty($this->pad)){
            msg('Your pad is empty.');
            closeNode();
        }else{
            msg('Please send the number of the note you want to delete from your pad :');
            bindNode('deleteNote');
        }


    }
    public function deleteNote(){
        if(isset(Webhook::getObject()->message->text) && is_numeric((int)Webhook::getObject()->message->text)) {
            $noteNumber = (int) Webhook::getObject()->message->text;
            if(isset($this->pad[$noteNumber-1])){
                unset($this->pad[$noteNumber-1]);
                msg('Note deleted successfully.');
                closeNode();
            }else{
                msg('Note number is not valid, Please send a valid note number.');
            }
        }else{
            msg('Only numbers are allowed, Please send a number.');

        }
    }
    # End Delete Note From Pad Nodes.

    # Clear Pad Nodes.
    public function clear(){
        if(empty($this->pad)){
            msg('Your pad is empty.');
            closeNode();
        }else{
            msg('Are you sure you want to clear your pad ? (yes/no)');
            bindNode('clearPad');
        }
    }
    public function clearPad(){
        if(isset(Webhook::getObject()->message->text)) {
            $answer = Webhook::getObject()->message->text;
            if($answer == 'yes'){
                $this->pad = [];
                msg('Your pad has been cleared.');
            }else{
                msg('Your pad is not cleared.');
            }
            closeNode();
        }else{
            msg('Please send yes or no.');
        }
    }
    # End Clear Pad Nodes.

}