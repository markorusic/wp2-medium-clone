<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-10">
            <div class="card">
                @include('shared.resource-header', $header)
                <div class="card-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
