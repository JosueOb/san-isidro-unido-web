@if (session('info'))
<div class="alert alert-info" role="alert">
        {{ session('info') }}
    </div>
@endif
@if (session('success'))
<div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('danger'))
<div class="alert alert-danger" role="alert">
        {{ session('danger') }}
    </div>
@endif