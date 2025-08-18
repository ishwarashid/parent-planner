{{-- resources/views/calendar/_event-form-fields.blade.php --}}

<!-- Associated Child -->
<div class="">
    <label for="child_id" class="block text-sm theme-modal-label">Child</label>
    <select id="child_id" name="child_id" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
        <option value="">-- None --</option>
        @foreach ($children as $child)
            <option value="{{ $child->id }}">{{ $child->name }}</option>
        @endforeach
    </select>
</div>

<!-- Assigned To -->
<div class="mt-2">
    <label for="assigned_to" class="block text-sm theme-modal-label">Assigned To</label>
    <select id="assigned_to" name="assigned_to" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input">
        <option value="">-- Unassigned --</option>
        @foreach (auth()->user()->getFamilyMemberIds() as $userId)
            @php
                $user = \App\Models\User::find($userId);
            @endphp
            @if ($user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endif
        @endforeach
    </select>
</div>

<!-- Title -->
<div class="mt-2">
    <label for="title" class="block text-sm theme-modal-label">Title</label>
    <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"
        required>
</div>

<!-- Description -->
<div class="mt-2">
    <label for="description" class="block text-sm theme-modal-label">Description</label>
    <textarea id="description" name="description" rows="3"
        class="mt-1 block w-full rounded-md shadow-sm theme-modal-input"></textarea>
</div>

<!-- Start & End Time -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
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
