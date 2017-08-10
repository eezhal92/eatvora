<?php

namespace App\Jobs;

use App\User;
use App\Office;
use App\Employee;
use App\Mail\EmployeeRegistrationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateEmployee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;

    public $email;

    public $password;

    public $officeId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $email, $password, $officeId)
    {
        $this->name = $name;

        $this->email = $email;

        $this->password = $password;

        $this->officeId = $officeId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        Employee::create([
            'user_id' => $user->id,
            'office_id' => $this->officeId,
        ]);

        $office = Office::with('company')->find($this->officeId);

        $message = (new EmployeeRegistrationEmail($office->company, $user, $this->password))->onQueue('emails');

        \Mail::to($this->email)
            ->queue($message);
    }
}
