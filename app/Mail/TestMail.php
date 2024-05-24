<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $_from;
    public $_to;
    public $_title;
    public $_subject;
    public $_content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from,  $content)
    {
        $this->_from = $from;
        // $this->to = $to;
        $this->_content = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br/>",$content);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Password Change Confirmation';
        $intance =  $this->from($this->_from->email, $this->_from->name)->subject($subject);


        return $intance->view('mail-template.index');
    }
}
