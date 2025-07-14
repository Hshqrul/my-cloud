<div class="fixed top-0 right-0 z-50 px-4 py-6 space-y-4 sm:px-6">
    @if (session()->has('success'))
        <flux:callout
            variant="success"
            icon="check-circle"
            heading="{{ session('success') }}"
        />
    @endif

    @if (session()->has('warning'))
        <flux:callout
            variant="warning"
            icon="exclamation-circle"
            heading="{{ session('warning') }}"
        />
    @endif

    @if (session()->has('error'))
        <flux:callout
            variant="error"
            icon="x-circle"
            heading="{{ session('error') }}"
        />
    @endif
</div>

