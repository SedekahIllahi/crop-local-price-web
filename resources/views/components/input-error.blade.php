@props(['messages'])

@if ($messages)
<div class="alert alert-danger" role="alert">
    <p><b>Error : </b></p>
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
@endif
