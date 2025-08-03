<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js'></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: @json($events),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                editable: true,
                selectable: true,
                select: function(info) {
                    var title = prompt('Enter Event Title:');
                    if (title) {
                        var description = prompt('Enter Event Description:');
                        var eventData = {
                            title: title,
                            description: description,
                            start: info.startStr,
                            end: info.endStr,
                            _token: '{{ csrf_token() }}'
                        };
                        fetch('{{ route('events.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(eventData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            calendar.addEvent({
                                id: data.id,
                                title: data.title,
                                start: data.start,
                                end: data.end,
                                description: data.description,
                                color: '#dc3545'
                            });
                        });
                    }
                },
                eventDrop: function(info) {
                    var eventData = {
                        title: info.event.title,
                        description: info.event.extendedProps.description,
                        start: info.event.startStr,
                        end: info.event.endStr,
                        _token: '{{ csrf_token() }}'
                    };
                    fetch('/events/' + info.event.id, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(eventData)
                    });
                },
                eventClick: function(info) {
                    if (confirm("Are you sure you want to delete this event?")) {
                        fetch('/events/' + info.event.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(() => {
                            info.event.remove();
                        });
                    }
                },
                eventMouseEnter: function(info) {
                    tippy(info.el, {
                        content: info.event.extendedProps.description,
                    });
                }
            });
            calendar.render();
        });
    </script>
    @endpush
</x-app-layout>
