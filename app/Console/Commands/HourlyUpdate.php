<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Notificatiom Mail to Recipient';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function handle(){
		$controller = app('App\Http\Controllers\SubscriptionController')->sendMail();
		return $this->$controller;
    }
}
