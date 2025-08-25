@php
    $status = $status ?? "info";
    
    if($status == "danger"){
        $status = "error";
    }
    
    $alert_data = '
        Swal.fire({
            title: `'.($title ?? "").'`,
            html: `'.($message ?? "").'`,
            icon: "'.$status.'",
            '.(!($confirm["disable"] ?? false) ? '
                confirmButtonText: "'.($confirm["text"] ?? "OK").'",
                confirmButtonColor: "'.($confirm["color"] ?? "#28a745").'",
                showConfirmButton: true,
            ' : 'showConfirmButton: false,').'
            '.(!($cancel["disable"] ?? true) ? '
                cancelButtonText: "'.($cancel["text"] ?? "Annulla").'",
                cancelButtonColor: "'.($cancel["color"] ?? "#DC3545").'",
                showCancelButton: true,
            ' : 'showCancelButton: false,').'
            reverseButtons: true,
            heightAuto: false,
        }).then((result) => {
            if (result.isConfirmed) {
                '.($onsuccess ?? "").'
            }
            else{
                '.($oncancel ?? "").'
            }
        });
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