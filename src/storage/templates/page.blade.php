<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head :title="__('advanced-model::modal.title', ["model" => ":MODEL_NAME:" ])" />
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-disabled-elt="this" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container">
            <x-backoffice.title :title="__('advanced-model::modal.title', ["model" => ":MODEL_NAME:" ]) subtitle="Aggiungi e modifica :MODEL_NAME_LOWER:" />
        
            {{-- Search Table --}}
            <x-search-table::table :model="new App\Models\:MODEL_NAME:()" showadvancefilters="offcanvas" />
        </div>
        
        {{-- Footer --}}
        {{-- <x-backoffice.footer /> --}}
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Modal --}}
        <x-modal />
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
        
        {{-- Scripts --}}
        <x-script />
        <script>
            
        </script>
    </body>
</html>