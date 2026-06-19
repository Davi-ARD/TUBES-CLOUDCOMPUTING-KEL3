@props(['label' => 'Generate PDF'])

<button type="submit" class="btn-primary w-full justify-center"
        style="padding: 14px 24px; font-size: 15px;">
    <i data-lucide="download" class="w-5 h-5"></i>
    {{ $label }}
</button>
