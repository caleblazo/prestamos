@props(['label', 'name', 'options', 'selected'])

<div>
    <label class="block font-medium">{{ $label }}</label>
    <select name="{{ $name }}" class="w-full border rounded p-2">
        <option value="">Seleccione</option>
        @foreach($options as $option)
        <option value="{{ $option->id }}" {{ $selected == $option->id ? 'selected' : '' }}>
            {{ $option->nombre }}
        </option>
        @endforeach
    </select>
</div>