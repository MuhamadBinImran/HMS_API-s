@component('mail::message')
    # Appointment Approved 🎉

    Dear {{ $appointment->patient->user->name }},

    Your appointment with Dr. {{ $appointment->doctor->user->name }} has been **approved**.

    @component('mail::panel')
        **Date & Time:** {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('F j, Y - g:i A') }}
        **Status:** ✅ Approved
    @endcomponent

    We look forward to seeing you.

    Thanks,
    {{ config('app.name') }}
@endcomponent
