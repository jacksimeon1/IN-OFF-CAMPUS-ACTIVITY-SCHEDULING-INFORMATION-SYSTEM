@props(['disabled' => false])

<div class="textInputWrapper">
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'textInput']) !!}>
</div>

<style>
.textInputWrapper {
    position: relative;
    width: 100%;
    margin: 12px 0;
    --accent-color: #059669;
}



.textInput::placeholder {
    transition: opacity 250ms cubic-bezier(0, 0, 0.2, 1) 0ms;
    opacity: 1;
    user-select: none;
    color: rgba(107, 114, 128, 0.7);
}

.textInputWrapper .textInput {
    border-radius: 8px;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1), 0px 1px 2px rgba(0, 0, 0, 0.06);
    max-height: 42px;
    background-color: #ffffff;
    border: 1px solid #d1d5db;
    transition-timing-function: cubic-bezier(0.25, 0.8, 0.25, 1);
    transition-duration: 200ms;
    transition-property: background-color, border-color, box-shadow;
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    padding: 12px 16px;
    width: 100%;
    outline: none;
}

.textInputWrapper .textInput:focus,
.textInputWrapper .textInput:active {
    outline: none;
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
}

.textInputWrapper:focus-within .textInput,
.textInputWrapper .textInput:focus,
.textInputWrapper .textInput:active {
    background-color: #ffffff;
    border-color: #059669;
}

.textInputWrapper:focus-within .textInput::placeholder {
    opacity: 0;
}
</style>
