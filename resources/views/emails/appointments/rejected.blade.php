@component('mail::message')
    # Appointment Rejected ❌

    Dear {{ $appointment->patient->user->name }},

    We regret to inform you that your appointment with Dr. {{ $appointment->doctor->user->name }} has been **rejected**.

    @component('mail::panel')
        **Date & Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('F j, Y - g:i A') }}
        **Status:** ❌ Rejected
    @endcomponent

    Please try rescheduling or contact the hospital for further assistance.

    Thanks,
    {{ config('app.name') }}
@endcomponent
