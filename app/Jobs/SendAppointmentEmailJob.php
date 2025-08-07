<?php
namespace App\Jobs;

use App\Models\Appointment;
use App\Mail\AppointmentCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAppointmentEmailJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public Appointment $appointment;

public function __construct(Appointment $appointment)
{
$this->appointment = $appointment;
}

public function handle(): void
{
// Send email to patient or doctor or both
$user = $this->appointment->patient->user;

Mail::to($user->email)->send(new AppointmentCreated($this->appointment));
}
}
