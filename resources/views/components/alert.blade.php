@if ($message)
    <div
        class="alert alert-{{ $type }} d-flex align-items-center p-2 bg-light-{{ $type }} border border-{{ $type }}">
        <i class="{{ $icon }} fs-3 text-{{ $type }} mx-3"></i>

        <div class="d-flex flex-column">
            {{-- Title --}}
            @if ($title)
                <h5 class="mb-1 text-{{ $type }}">{{ $title }}</h5>
            @endif

            {{-- Custom Message --}}
            @if (is_array($message))
                <div class="d-flex flex-column">
                    @foreach ($message as $msg)
                        <li class="d-flex align-items-center py-2">
                            <span class="bullet me-5"></span> {{ $msg }}
                        </li>
                    @endforeach
                </div>
            @else
                <span>{{ $message }}</span>
            @endif
        </div>
    </div>
@endif
{{-- Error --}}
@if ($errors->any())
<div class="alert alert-danger d-flex align-items-center p-2 bg-light-danger border border-danger">
    <i class="ph-duotone ph-x-circle me-2 fs-3 text-danger mx-3"></i>
    <div class="d-flex flex-column">
        {{-- Title --}}
        <h5 class="mb-1 text-danger">Error</h5>

        {{-- Error message --}}
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif