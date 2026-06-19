@php
    $flashes = [
        'success' => ['class' => 'alert-success', 'icon' => 'check-circle'],
        'error'   => ['class' => 'alert-error',   'icon' => 'x-circle'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'alert-triangle'],
        'info'    => ['class' => 'alert-info',     'icon' => 'info'],
    ];
@endphp

@foreach ($flashes as $type => $meta)
    @if (session($type))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="{{ $meta['class'] }} mb-6 flex items-start gap-3"
             role="alert">
            <i data-lucide="{{ $meta['icon'] }}" class="w-5 h-5 shrink-0" style="margin-top: 1px;"></i>
            <span class="flex-1">{{ session($type) }}</span>
            <button type="button" @click="show = false"
                    aria-label="Tutup"
                    style="background: none; border: none; cursor: pointer; color: inherit; opacity: 0.7; padding: 0; line-height: 1;">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif
@endforeach
