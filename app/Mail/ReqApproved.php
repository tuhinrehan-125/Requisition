<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReqApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $orderId;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $orderid)
    {
        $this->data = $data;
        $this->orderId = $orderid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('imtiaz@orionisbd.com', 'IRD')->subject('Your requisition has been approved')->view('mail.reqApprovedMail')->with('data', $this->data, 'orderId', $this->orderId);
    }
}
