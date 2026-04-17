@props(['status'])

@if ($status)
<div class="alert alert-success" role="alert">
    <h5><b>Success : </b></h5>
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 dark:text-green-400']) }}>
        {{ $status }}
    </div>
</div>
@endif
