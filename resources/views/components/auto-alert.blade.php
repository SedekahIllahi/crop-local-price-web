@if($message)
    <div class="alert alert-{{ $type }} alert-dismissible alert-dismissible bg-light-{{ $type }} border border-{{ $type }} fade show" role="alert">
        @if($type == 'success')
        <i class="bi bi-check-circle me-2"></i>
        @elseif($type == 'danger')
            <i class="bi bi-x-circle me-2"></i>
        @elseif($type == 'warning')
        <i class="bi bi-exclamation-triangle me-2"></i>
        @endif
        {!! $message !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible bg-light-danger border border-danger fade show" role="alert">
        <i class="bi bi-x-circle me-2"></i> Error
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif