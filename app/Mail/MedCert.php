<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class MedCert extends Mailable
{
    use Queueable, SerializesModels;

    public $_from;
    public $_to;
    public $_title;
    public $_subject;
    public $_name;
    public $_doctor;
    public $_number;
    public $_case;
    public $_description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from, $name, $doctors_name, $contact_number, $case_number, $description)
    {
        $this->_from = $from;
        // $this->to = $to;
        $this->_name = $name;
        $this->_doctor = $doctors_name;
        $this->_number = $contact_number;
        $this->_case = $case_number;
        $this->_description = $description;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Request of Medical Certificate';
        $intance =  $this->from($this->_from->email, $this->_from->name)->subject($subject);


        return $intance->view('med-template.index');
    }
}
