@php
    $validationMessages = [];

    foreach ($errors->getBags() as $errorBag) {
        $validationMessages = array_merge($validationMessages, $errorBag->all());
    }

    $alertPayload = [
        'errors' => array_values(array_unique($validationMessages)),
        'error' => session('error'),
        'success' => session('success'),
    ];
@endphp

<script id="app-alert-data" type="application/json">{!! json_encode($alertPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
