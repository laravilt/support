<form {{ $attributes->merge($getFormAttributes()) }}>
    @csrf

    @if ($needsMethodSpoofing())
        @method($getSpoofedMethod())
    @endif

    {{ $slot }}
</form>

@if ($spa)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('{{ $formId }}');

    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        const method = '{{ $method }}';
        const action = '{{ $action }}';

        try {
            const response = await fetch(action, {
                method: method === 'GET' ? 'GET' : 'POST',
                headers: {
                    'X-Laravilt': 'true',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: method === 'GET' ? null : formData,
            });

            const data = await response.json();

            if (data.laravilt && data.html) {
                // Emit custom event for SPA navigation
                window.dispatchEvent(new CustomEvent('laravilt:navigate', {
                    detail: {
                        html: data.html,
                        title: data.title,
                        url: action,
                    }
                }));
            } else if (data.redirect) {
                window.location.href = data.redirect;
            }

            // Emit form submitted event
            window.dispatchEvent(new CustomEvent('laravilt:form-submitted', {
                detail: { form: form, response: data }
            }));

        } catch (error) {
            console.error('Form submission error:', error);

            // Emit error event
            window.dispatchEvent(new CustomEvent('laravilt:form-error', {
                detail: { form: form, error: error }
            }));
        }
    });
});
</script>
@endif
