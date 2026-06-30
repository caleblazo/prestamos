@component('mail::message')
    # 📊 Capital Total Actualizado

    Hola Eric,
    Aquí tienes el capital total actualizado al día de hoy.

    @component('mail::panel')
        ### 💰 Capital Total:
        **S/ {{ number_format($capitalTotal, 2) }}**

        📅 Fecha de cálculo:
        **{{ $fecha }}**
    @endcomponent

    Este monto incluye:

    - Último capital registrado
    - Préstamos activos
    - Capital pendiente por recuperar

    Gracias por usar **Prestamo LYH**.
@endcomponent
