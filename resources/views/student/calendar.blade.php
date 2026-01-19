@extends('layouts.sidebar')

@section('title', 'Activity Calendar')
@section('page-title', 'Activity Calendar')

@section('content')
<div class="pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Calendar Container -->
        <div class="calendar-container">
            <div id="activity-calendar" class="w-full">
                <!-- Traditional calendar will be rendered here -->
            </div>

            <!-- Calendar Legend -->
            <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color bg-green-500"></div>
                    <span>Approved Activities</span>
                </div>
                <div class="text-sm text-gray-600 italic">
                    <i class="fas fa-info-circle mr-1"></i>
                    Only approved activities are displayed on the calendar
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Traditional Calendar Styles - Exact Copy from Admin */
    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .calendar-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .calendar-nav-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .calendar-nav-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    .calendar-month-year {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .calendar-weekday {
        padding: 1rem 0.5rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: white;
    }

    .calendar-day {
        min-height: 100px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .calendar-day:hover {
        background: #f0fdf4;
    }

    .calendar-day.other-month {
        background: #f8fafc;
        color: #cbd5e1;
    }

    .calendar-day.today {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 2px solid #059669;
    }

    .calendar-day.has-activities {
        background: #fefce8;
    }

    .calendar-day.has-activities.today {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    }

    .day-number {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 0.25rem;
        color: #1f2937;
    }

    .calendar-day.other-month .day-number {
        color: #cbd5e1;
    }

    .day-activities {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex-grow: 1;
    }

    .activity-item {
        background: #059669;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        line-height: 1.2;
        cursor: pointer;
        transition: all 0.2s ease;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    .activity-item:hover {
        background: #047857;
        transform: translateY(-1px);
    }

    .activity-item.status-approved {
        background: #059669;
    }

    .more-activities {
        background: #6b7280;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .more-activities:hover {
        background: #4b5563;
    }

    /* Calendar Legend */
    .calendar-legend {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    /* Activity Modal Styles */
    .activity-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .activity-modal-content {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .activity-modal-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
    }

    .activity-modal-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }

    .activity-detail {
        margin-bottom: 1rem;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #059669;
        background: #f0fdf4;
    }

    .activity-detail h4 {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .activity-detail p {
        color: #059669;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .activity-detail span {
        color: #6b7280;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .calendar-day {
            min-height: 80px;
            padding: 0.25rem;
        }

        .day-number {
            font-size: 0.875rem;
        }

        .activity-item {
            font-size: 0.7rem;
            padding: 1px 4px;
        }

        .calendar-month-year {
            font-size: 1.25rem;
        }

        .calendar-legend {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
const calendarActivities = @json($calendarActivities ?? []);

class TraditionalCalendar {
    constructor(containerId, activities) {
        this.container = document.getElementById(containerId);
        this.activities = activities;
        this.currentDate = new Date();
        this.currentMonth = this.currentDate.getMonth();
        this.currentYear = this.currentDate.getFullYear();
        this.render();
    }

    render() {
        const monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        this.container.innerHTML = `
            <div class="calendar-header">
                <div class="flex justify-between items-center">
                    <button onclick="calendar.previousMonth()" class="calendar-nav-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h2 class="calendar-month-year">
                        ${monthNames[this.currentMonth]} ${this.currentYear}
                    </h2>
                    <button onclick="calendar.nextMonth()" class="calendar-nav-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div class="calendar-weekdays">
                <div class="calendar-weekday">Sunday</div>
                <div class="calendar-weekday">Monday</div>
                <div class="calendar-weekday">Tuesday</div>
                <div class="calendar-weekday">Wednesday</div>
                <div class="calendar-weekday">Thursday</div>
                <div class="calendar-weekday">Friday</div>
                <div class="calendar-weekday">Saturday</div>
            </div>
            <div class="calendar-days">
                ${this.renderCalendarDays()}
            </div>
        `;
    }

    renderCalendarDays() {
        const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
        const daysInPrevMonth = new Date(this.currentYear, this.currentMonth, 0).getDate();

        let html = '';
        let dayCount = 1;
        let nextMonthDay = 1;

        // Calculate total cells needed (6 rows × 7 days = 42 cells)
        for (let i = 0; i < 42; i++) {
            let dayNumber, dateStr, isCurrentMonth = true, isToday = false;
            let dayClass = 'calendar-day';

            if (i < firstDayOfMonth) {
                // Previous month days
                dayNumber = daysInPrevMonth - firstDayOfMonth + i + 1;
                const prevMonth = this.currentMonth === 0 ? 11 : this.currentMonth - 1;
                const prevYear = this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear;
                dateStr = `${prevYear}-${String(prevMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                dayClass += ' other-month';
                isCurrentMonth = false;
            } else if (dayCount <= daysInMonth) {
                // Current month days
                dayNumber = dayCount;
                dateStr = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                isToday = this.isToday(dayNumber);
                dayCount++;
            } else {
                // Next month days
                dayNumber = nextMonthDay;
                const nextMonth = this.currentMonth === 11 ? 0 : this.currentMonth + 1;
                const nextYear = this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear;
                dateStr = `${nextYear}-${String(nextMonth + 1).padStart(2, '0')}-${String(dayNumber).padStart(2, '0')}`;
                dayClass += ' other-month';
                isCurrentMonth = false;
                nextMonthDay++;
            }

            // Get only approved activities for this date (including multi-day activities)
            const dayActivities = this.activities.filter(activity => {
                if (activity.status !== 'approved') return false;
                // Use string comparison to avoid timezone issues
                return dateStr >= activity.activity_date && dateStr <= activity.end_date;
            });

            if (isToday) {
                dayClass += ' today';
            }

            // Only mark days that have approved activities
            if (dayActivities.length > 0) {
                dayClass += ' has-activities';
            }

            html += `<div class="${dayClass}" onclick="calendar.showDayActivities('${dateStr}', '${dayNumber}', ${isCurrentMonth})">
                <div class="day-number">${dayNumber}</div>
                <div class="day-activities">
                    ${this.renderDayActivities(dayActivities)}
                </div>
            </div>`;
        }

        return html;
    }

    renderDayActivities(activities) {
        // Only show approved activities on the calendar
        const approvedActivities = activities.filter(activity => activity.status === 'approved');

        if (approvedActivities.length === 0) return '';

        let html = '';
        const maxVisible = 3;

        approvedActivities.slice(0, maxVisible).forEach(activity => {
            const title = activity.title.length > 12 ? activity.title.substring(0, 12) + '...' : activity.title;
            const timeInfo = `${activity.start_time} - ${activity.end_time}`;

            html += `<div class="activity-item status-approved"
                        title="${activity.title} (${timeInfo}) - Approved Activity"
                        onclick="event.stopPropagation(); calendar.showActivityDetails('${activity.id}')">
                ${title}
            </div>`;
        });

        if (approvedActivities.length > maxVisible) {
            html += `<div class="more-activities" onclick="event.stopPropagation(); calendar.showAllDayActivities('${dateStr}')">
                +${approvedActivities.length - maxVisible} more approved
            </div>`;
        }

        return html;
    }

    isToday(day) {
        const today = new Date();
        return day === today.getDate() &&
               this.currentMonth === today.getMonth() &&
               this.currentYear === today.getFullYear();
    }

    previousMonth() {
        this.currentMonth--;
        if (this.currentMonth < 0) {
            this.currentMonth = 11;
            this.currentYear--;
        }
        this.render();
    }

    nextMonth() {
        this.currentMonth++;
        if (this.currentMonth > 11) {
            this.currentMonth = 0;
            this.currentYear++;
        }
        this.render();
    }

    showDayActivities(dateStr, dayNumber, isCurrentMonth) {
        // Only show approved activities in calendar modal (including multi-day activities)
        const approvedActivities = this.activities.filter(activity => {
            if (activity.status !== 'approved') return false;
            // Use string comparison to avoid timezone issues
            return dateStr >= activity.activity_date && dateStr <= activity.end_date;
        });
        if (approvedActivities.length === 0) return;

        this.showActivitiesModal(dateStr, approvedActivities, true);
    }

    showAllDayActivities(dateStr) {
        // Only show approved activities in calendar modal (including multi-day activities)
        const approvedActivities = this.activities.filter(activity => {
            if (activity.status !== 'approved') return false;
            // Use string comparison to avoid timezone issues
            return dateStr >= activity.activity_date && dateStr <= activity.end_date;
        });
        this.showActivitiesModal(dateStr, approvedActivities, true);
    }

    showActivitiesModal(dateStr, activities, approvedOnly = false) {
        const date = new Date(dateStr);
        const formattedDate = date.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        let activitiesHtml = '';
        const headerText = activities.length === 1 ? '1 approved activity' : `${activities.length} approved activities`;

        if (activities.length === 0) {
            activitiesHtml = '<p class="text-gray-500 text-center py-8">No approved activities on this date.</p>';
        } else {
            activities.forEach(activity => {
                activitiesHtml += `
                    <div class="activity-detail">
                        <h4>${activity.title}</h4>
                        <p>
                            <i class="fas fa-clock mr-1"></i>
                            ${activity.start_time} - ${activity.end_time}
                        </p>
                        <span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            ${activity.location}
                        </span>
                    </div>
                `;
            });
        }

        const modal = document.createElement('div');
        modal.className = 'activity-modal';
        modal.innerHTML = `
            <div class="activity-modal-content">
                <div class="activity-modal-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold">${formattedDate}</h3>
                        <button onclick="this.closest('.activity-modal').remove()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <p class="text-green-100 mt-1">${headerText}</p>
                </div>
                <div class="activity-modal-body">
                    ${activitiesHtml}
                    ${approvedOnly && activities.length > 0 ? '<div class="mt-4 p-3 bg-green-100 border border-green-300 rounded-lg text-green-800 text-sm"><i class="fas fa-info-circle mr-2"></i>Only approved activities are displayed on the calendar. Use the Activity Management section to view all activities.</div>' : ''}
                </div>
            </div>
        `;

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        document.body.appendChild(modal);
    }

    showActivityDetails(activityId) {
        console.log('Show activity details for ID:', activityId);
    }
}

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Student Calendar Activities:', calendarActivities.length, 'approved activities');
    window.calendar = new TraditionalCalendar('activity-calendar', calendarActivities);
});
</script>
@endsection
