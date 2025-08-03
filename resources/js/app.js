import './bootstrap';

import Alpine from 'alpinejs';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

window.Alpine = Alpine;
window.tippy = tippy;

Alpine.start();

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            resizable: true,
            events: function(fetchInfo, successCallback, failureCallback) {
                let url = document.querySelector('meta[name="visitations-api-url"]').getAttribute('content');
                const childId = document.getElementById('child_filter') ? document.getElementById('child_filter').value : '';
                if (childId) {
                    url += `?child_id=${childId}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => successCallback(data))
                    .catch(error => failureCallback(error));
            },
            eventDidMount: function(info) {
                const currentUserId = document.querySelector('meta[name="user-id"]').getAttribute('content');
                if (info.event.extendedProps.parent_id == currentUserId) {
                    info.el.classList.add('event-current-user');
                } else {
                    info.el.classList.add('event-other-user');
                }

                // Add recurring icon
                if (info.event.extendedProps.is_recurring) {
                    const titleEl = info.el.querySelector('.fc-event-title');
                    if (titleEl) {
                        titleEl.innerHTML = '🔁 ' + titleEl.innerHTML;
                    }
                }
            },
            eventMouseEnter: function(info) {
                let tooltip = document.createElement('div');
                tooltip.className = 'fc-tooltip';
                let notes = info.event.extendedProps.notes ? info.event.extendedProps.notes : 'No notes';
                tooltip.innerHTML = `<strong>${info.event.extendedProps.child_name}</strong><br>With: ${info.event.extendedProps.parent_name}<br>Notes: ${notes}`;
                document.body.appendChild(tooltip);

                // Position tooltip
                const rect = info.el.getBoundingClientRect();
                tooltip.style.left = `${rect.left + window.scrollX}px`;
                tooltip.style.top = `${rect.bottom + window.scrollY + 5}px`;
            },
            eventMouseLeave: function(info) {
                document.querySelectorAll('.fc-tooltip').forEach(tooltip => tooltip.remove());
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // Prevent browser from following URL
                let form = document.getElementById('visitationForm');
                form.reset();
                document.getElementById('visitation_id').value = info.event.id;
                document.getElementById('child_id').value = info.event.extendedProps.child_id;
                document.getElementById('date_start').value = info.event.start.toISOString().slice(0,16);
                document.getElementById('date_end').value = info.event.end ? info.event.end.toISOString().slice(0,16) : '';
                document.getElementById('is_recurring').checked = info.event.extendedProps.is_recurring;
                document.getElementById('notes').value = info.event.extendedProps.notes;

                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'visitation-form' }));
            },
            dateClick: function(info) {
                let form = document.getElementById('visitationForm');
                form.reset();
                document.getElementById('visitation_id').value = '';
                document.getElementById('date_start').value = info.dateStr + 'T12:00'; // Default to noon
                document.getElementById('date_end').value = info.dateStr + 'T13:00'; // Default to 1 hour duration

                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'visitation-form' }));
            },
            eventDrop: function(info) {
                // Handle event drag-and-drop (update visitation dates)
                let newStart = info.event.start.toISOString();
                let newEnd = info.event.end ? info.event.end.toISOString() : null;

                fetch(`/visitations/${info.event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        date_start: newStart,
                        date_end: newEnd
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Visitation updated successfully');
                        const successAlert = `
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">Visitation moved successfully.</span>
                            </div>
                        `;
                        const container = document.querySelector('.max-w-7xl.mx-auto.sm\\:px-6.lg\\:px-8 > .bg-white');
                        if (container) {
                            container.insertAdjacentHTML('afterbegin', successAlert);
                        }
                        calendar.refetchEvents(); // <-- This is the fix
                    } else {
                        console.error('Error updating visitation');
                        info.revert(); // Revert the event if update fails
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    info.revert();
                });
            },
            eventResize: function(info) {
                // Handle event resizing (update visitation end date)
                let newStart = info.event.start.toISOString();
                let newEnd = info.event.end.toISOString();

                fetch(`/visitations/${info.event.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        date_start: newStart,
                        date_end: newEnd
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Visitation resized successfully');
                        calendar.refetchEvents(); // <-- This is the fix
                    } else {
                        console.error('Error resizing visitation');
                        info.revert(); // Revert the event if update fails
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    info.revert();
                });
            }
        });
        calendar.render();

        document.getElementById('visitationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = e.target;
            let formData = new FormData(form);
            let id = formData.get('visitation_id');
            let url = id ? `/visitations/${id}` : '/visitations';
            let method = id ? 'PUT' : 'POST';

            // Manually construct the data object for JSON
            let data = {
                child_id: formData.get('child_id'),
                date_start: formData.get('date_start'),
                date_end: formData.get('date_end'),
                is_recurring: formData.get('is_recurring') ? 1 : 0,
                notes: formData.get('notes'),
                _token: formData.get('_token')
            };
             if (id) {
                data._method = 'PUT';
            }

            fetch(url, {
                method: 'POST', // Use POST for PUT due to form method limitations
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.dispatchEvent(new CustomEvent('close-modal'));
                    calendar.refetchEvents();
                    // You could add a more robust notification system here
                    alert('Visitation saved successfully!');
                } else {
                    // Handle errors (e.g., display validation messages)
                    alert('Error saving visitation: ' + JSON.stringify(data.errors));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            });
        });

        const childFilter = document.getElementById('child_filter');
        if (childFilter) {
            childFilter.addEventListener('change', function() {
                calendar.refetchEvents();
            });
        }
    }

    var generalCalendarEl = document.getElementById('general-calendar');
    if (generalCalendarEl) {
        var events = JSON.parse(generalCalendarEl.dataset.events);
        var generalCalendar = new Calendar(generalCalendarEl, {
            plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            displayEventTime: false,
            events: events,
            select: function(info) {
                document.getElementById('eventForm').reset();
                document.getElementById('event_id').value = '';

                // Function to format date for datetime-local input
                const toLocalISOString = (date) => {
                    const pad = (num) => (num < 10 ? '0' : '') + num;
                    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
                };

                let start = info.start;
                let end = info.end;

                // If a full day is clicked, set a default time range
                if (info.allDay) {
                    start.setHours(9, 0, 0); // 9:00 AM
                    end = new Date(start);
                    end.setHours(10, 0, 0); // 10:00 AM
                }

                document.getElementById('start').value = toLocalISOString(start);
                document.getElementById('end').value = toLocalISOString(end);

                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'event-form' }));
            },
            eventDrop: function(info) {
                if (!info.event.id) return;
                var eventData = {
                    title: info.event.title,
                    description: info.event.extendedProps.description,
                    start: info.event.startStr,
                    end: info.event.endStr,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                fetch('/events/' + info.event.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(eventData)
                });
            },
            eventClick: function(info) {
                if (info.event.id && confirm("Are you sure you want to delete this event?")) {
                    fetch('/events/' + info.event.id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).then(() => {
                        info.event.remove();
                    });
                }
            },
            eventDidMount: function(info) {
                let startTime = info.event.start ? new Date(info.event.start).toLocaleString() : 'N/A';
                let endTime = info.event.end ? new Date(info.event.end).toLocaleString() : 'N/A';
                let description = info.event.extendedProps.description || 'No description';

                let content = `<strong>${info.event.title}</strong><br>
                             <strong>Start:</strong> ${startTime}<br>
                             <strong>End:</strong> ${endTime}<br>
                             <strong>Details:</strong> ${description}`;

                tippy(info.el, {
                    content: content,
                    allowHTML: true,
                });
            }
        });
        generalCalendar.render();

        document.getElementById('eventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let eventId = document.getElementById('event_id').value;
            let url = eventId ? '/events/' + eventId : '/events';
            let method = eventId ? 'PUT' : 'POST';

            let formData = new FormData(this);
            let data = Object.fromEntries(formData.entries());

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (eventId) {
                    let event = generalCalendar.getEventById(eventId);
                    event.setProp('title', data.title);
                    event.setExtendedProp('description', data.description);
                    event.setStart(data.start);
                    event.setEnd(data.end);
                } else {
                    generalCalendar.addEvent({
                        id: data.id,
                        title: data.title,
                        start: data.start,
                        end: data.end,
                        description: data.description,
                        color: '#dc3545'
                    });
                }
                window.dispatchEvent(new CustomEvent('close-modal'));
            });
        });
    }
});
