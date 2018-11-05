<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Reminder extends Mailable
{
    use Queueable, SerializesModels;
    private $project;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project)
    {
         $this->project = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hello@app.com', 'Your Application')
                    ->subject('Event Reminder: ' . $this->project->title)
                    ->view('emails.test', ['project' => $this->project]);
    }
}