@php
    $:MODEL_NAME_VARIABLE: ??= null;
@endphp
<div class="modal-header">
    <h1 class="modal-title fs-5" id="modalLabel">{{__('advanced-model::modal.title', ["model" => ":MODEL_NAME:" ])}}</h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row g-3">
        <div class="col-md-6 order-3">
            <label class="text-capitalize">{{__('validation.attributes.name')}}</label>
            <input type="text" class="form-control" id=":MODEL_NAME_LOWER:-name" name="name" value="{{$:MODEL_NAME_VARIABLE:->name ?? ""}}">
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="row w-100">
        @isset($:MODEL_NAME_VARIABLE:)
            <div class="col p-0">
                <button id=":MODEL_NAME_LOWER:-delete" type="button" class="btn btn-danger"
                    hx-delete="{{route(":MODEL_NAME_PLURAL_LOWER:.destroy", [$:MODEL_NAME_VARIABLE:])}}" hx-target="#request-response" hx-confirm="Eliminare :MODEL_NAME_LOWER: {{$:MODEL_NAME_VARIABLE:->name}}?" hx-params="none">
                    {{__('advanced-model::actions.delete')}}
                </button>
            </div>
        @endisset
        <div class="col p-0 text-end">
            @csrf
            <button id=":MODEL_NAME_LOWER:-save" type="button" class="btn btn-primary"
                @isset($:MODEL_NAME_VARIABLE:)
                    hx-put="{{route(":MODEL_NAME_PLURAL_LOWER:.update", [$:MODEL_NAME_VARIABLE:])}}"
                @else
                    hx-post="{{route(":MODEL_NAME_PLURAL_LOWER:.store")}}"
                @endisset
                hx-target="#request-response">
                {{__('advanced-model::actions.'.(isset($:MODEL_NAME_VARIABLE:) ? 'update' : 'create'))}}
            </button>
        </div>
    </div>
</div>

<script>
    modal.show();
</script>
