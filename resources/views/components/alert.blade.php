@php
    $durations = [
        "success" => 2000,
        "danger" => 4000,
        "info" => 1500,
        "warning" => 4000,
    ];
    
    $status = $status ?? "info";
    
    $alert_data = '
        Toastify({
            className: "'.$status.'",
            text: "'.$message.'",
            duration: '.($duration ?? $durations[$status]).',
            gravity: "'.($gravity ?? "bottom").'",
            position: "'.($position ?? "center").'",
            close: true,
            callback: function(){
                '.($callback ?? "").'
            }
        }).showToast();
    ';
@endphp

<script>
    @if (!empty($beforeshow))
        {!!$beforeshow!!}
    @endif
    
    if(document.readyState === "complete") {
        {!!$alert_data!!}
    }
    else {
        window.addEventListener("DOMContentLoaded", () => {
            {!!$alert_data!!}
        });
    }
</script>