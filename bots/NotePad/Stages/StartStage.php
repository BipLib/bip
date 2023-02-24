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
use Bip\Telegram\Call;
use Bip\Telegram\Webhook;


class StartStage extends Stage{
    # Public Properties Are Saved In Database And Restored Automatically.
    public array $pad = [];

    # Private Properties Are Not Saved In Database. Only Be Used In This Stage.
    private string $about = <<<MSG
        This bot is created by early access Bip Library [v0.5.x]. And maybe doesn't work properly on other versions.
        Highly recommended to use Bip Library [v0.5.x]. to run this bot.
        in the new version of Bip we will update this bot for compatibility with new version.
        You can contribute to this project on [github](https://github.com/biplib/bip).
        MSG;

    # Controller Controls The Logic Of The Stage. It Is Called Automatically.
    public function controller(){
        route('start')->whenMessageTextIs('/start');
        route('add')->whenMessageTextIs('/add');
        route('list')->whenMessageTextIs('/list');
        route('delete')->whenMessageTextIs('/delete');
        route('clear')->whenMessageTextIs('/clear');
        route('about')->whenMessageTextIs('/about');
    }

    # Start Node.
    public function start(){
        Call::setMyCommands(
            commands: [
                ['command' => '/start', 'description' => 'Restart The Bot'],
                ['command' => '/add', 'description' => 'Add A Note To Your Pad'],
                ['command' => '/list', 'description' => 'List Your Notes'],
                ['command' => '/delete', 'description' => 'Delete A Note From Your Pad'],
                ['command' => '/clear', 'description' => 'Clear Your Pad'],
                ['command' => '/about', 'description' => 'About This Bot'],
            ],
            scope: [
                'type' => 'chat',
                'chat_id'=> Webhook::getObject()->message->chat->id
            ]
        );
        $message = <<<MSG
            Welcome To Bip Note Pad !!
            This is simple bot that can help you to save your notes.  
            MSG;
        msg($message);
    }
    # End Start Node.

    # Default Node.
    public function default(){
        msg('Please Select One Of The Menu Commands.');
    }
    # End Default Node.

    # About Node.
    public function about(){
        msg($this->about);
    }
    # End About Node.

    # Add Note To Pad Nodes.
    public function add(){
        msg('Please Send Your Note To Be Added To Your Pad :');
        bindNode('addNote');
    }
    public function addNote(){
        if(isset(Webhook::getObject()->message->text)){
            $this->pad[] = Webhook::getObject()->message->text;
            msg('Your Note Has Been Added To Your Pad.');
        }else{
            msg('Only Text Is Allowed.');
        }
        bindNode('default');
    }
    # End Add Note To Pad Nodes.

    # List Notes Nodes.
    public function list(){
        if(empty($this->pad)){
            msg('Your Pad Is Empty.');
        }else{
            foreach ($this->pad as $noteNumber => $noteValue ){
                msg('Note Number : '.($noteNumber+1)."\n".$noteValue);
            }
        }
        bindNode('default');
    }
    # End List Notes Nodes.

    # Delete Note From Pad Nodes.
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
        }else{
            msg('Only Number Is Allowed.');
        }
        bindNode('default');
    }
    # End Delete Note From Pad Nodes.

    # Clear Pad Nodes.
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
        }else{
            msg('Only Text Is Allowed.');
        }
        bindNode('default');
    }
    # End Clear Pad Nodes.


}