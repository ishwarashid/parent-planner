{{-- resources/views/calendar/_event-form-fields.blade.php --}}

<!-- Associated Child -->
<div class="mt-4">
    <label for="child_id" class="block text-sm theme-modal-label">Child</label>
    <select id="child_id" name="child_id" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
        <option value="">-- None --</option>
        @foreach ($children as $child)
            <option value="{{ $child->id }}">{{ $child->name }}</option>
        @endforeach
    </select>
</div>

<!-- Title -->
<div class="mt-4">
    <label for="title" class="block text-sm theme-modal-label">Title</label>
    <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"
        required>
</div>

<!-- Description -->
<div class="mt-4">
    <label for="description" class="block text-sm theme-modal-label">Description</label>
    <textarea id="description" name="description" rows="3"
        class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"></textarea>
</div>

<!-- Start & End Time -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <label for="start" class="block text-sm theme-modal-label">Start Time</label>
        <input type="datetime-local" id="start" name="start"
            class="mt-1 block w-full rounded-md shadow-sm theme-modal-input" required>
    </div>
    <div>
        <label for="end" class="block text-sm theme-modal-label">End Time</label>
        <input type="datetime-local" id="end" name="end"
            class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
    </div>
</div>
