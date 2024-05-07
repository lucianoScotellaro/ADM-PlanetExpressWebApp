@props(['name'])
@error($name)
<p class="text-red-600">{{ $message }}</p>
@enderror
