<!DOCTYPE html>
<html>
    {{-- Head --}}
    <x-backoffice.head title=":MODEL_NAME_PLURAL:"></x-backoffice.head>
    
    <body class="container-fluid" hx-headers='{"X-CSRF-TOKEN": "{{csrf_token()}}"}' hx-ext="ajax-header" hx-target="#request-response">
        {{-- Header --}}
        <x-backoffice.header />

        <div id="container">
            <x-backoffice.title title="Gestione :MODEL_NAME:" subtitle="Aggiungi e modifica :MODEL_NAME_LOWER:"/>
        
            {{-- Search Table --}}
            <x-search-table::table :model="new App\Models\:MODEL_NAME:()" query="" showadvancefilters></x-search-table::table>
        </div>
        
        {{-- Footer --}}
        {{-- <x-backoffice.footer /> --}}
                
        {{-- Menu --}}
        <x-backoffice.menu />
        
        {{-- Modal --}}
        <x-modal></x-modal>
        
        {{-- Ajax responses --}}
        <div id="request-response"></div>
        
        {{-- Scripts --}}
        <x-backoffice.script></x-backoffice.script>
        <script>
            
        </script>
    </body>
</html>