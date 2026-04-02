<div class="col-md-12">
    <label class="text-capitalize">{{__('validation.attributes.name')}}</label>
    <div class="input-group">
        <input id="filter-name" type="text" name="advanced_search[name]" class="form-control filters">
        <span class="input-group-text" role="button" onclick="$('#filter-name').val('').trigger('input');">
            <i class="fa-solid fa-xmark"></i>
        </span>
    </div>
</div>

<script>
    let filters_timeout;
    
    function clearFilters(){
        $("#filter-name").val("");
        
        updateFiltersCount();
    }
    
    function updateFiltersCount(){
        let filters_count = 0;
        
        filters_count += $("#filter-name").val().length > 0 ? 1 : 0;
        
        $("#filters-badge").toggleClass("d-none", filters_count == 0).html(filters_count);
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        $(".filters").on("input", function(){
            clearTimeout(filters_timeout);
            
            filters_timeout = setTimeout((input) => {
                updateFiltersCount()
                htmx.trigger("#page", "change");
            }, 400, this);
        });
    });
</script>