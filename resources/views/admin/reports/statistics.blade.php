@extends('layouts.admin')

@section('title', 'Statistics Report')

@push('styles')
<style>
    /* Add pulse animation keyframe */
    @keyframes pulse {
        0% { transform: translateY(-2px) scale(1); }
        50% { transform: translateY(-2px) scale(1.05); }
        100% { transform: translateY(-2px) scale(1); }
    }


    /* Form Styles */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .form-card-header {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: #059669;
        color: white;
    }

    .btn-primary:hover {
        background: #047857;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-outline {
        background: transparent;
        color: #059669;
        border: 1px solid #059669;
    }

    .btn-outline:hover {
        background: #059669;
        color: white;
    }

    /* Enhanced Time Period Cards */
    .period-card {
        position: relative;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .period-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #059669, #10b981);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .period-card:hover {
        border-color: #059669;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -3px rgba(5, 150, 105, 0.1), 0 4px 6px -2px rgba(5, 150, 105, 0.05);
    }

    .period-card:hover::before {
        transform: scaleX(1);
    }

    .period-card.selected {
        border-color: #059669;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -3px rgba(5, 150, 105, 0.2), 0 4px 6px -2px rgba(5, 150, 105, 0.1);
    }

    .period-card.selected::before {
        transform: scaleX(1);
    }

    .period-card.selected::after {
        content: '✓';
        position: absolute;
        top: 8px;
        right: 8px;
        width: 20px;
        height: 20px;
        background: #059669;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .period-icon {
        font-size: 2.5rem;
        color: #059669;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    .period-card:hover .period-icon {
        transform: scale(1.1);
        color: #047857;
    }

    .period-card.selected .period-icon {
        transform: scale(1.1);
        color: #047857;
    }

    .period-title {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .period-card:hover .period-title,
    .period-card.selected .period-title {
        color: #047857;
    }

    .period-desc {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .period-card:hover .period-desc,
    .period-card.selected .period-desc {
        color: #059669;
    }

    /* Time Period Section Header */
    .time-period-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .time-period-header .form-label {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    .time-period-badge {
        background: linear-gradient(135deg, #059669, #047857);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.75rem;
    }

    /* Responsive Grid Enhancement */
    .period-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .period-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        .period-card {
            padding: 1rem 0.75rem;
        }
        
        .period-icon {
            font-size: 2rem;
        }
        
        .period-title {
            font-size: 0.9rem;
        }
        
        .period-desc {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .period-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Back Button Styles */
    .back-button {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: #6b7280;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        background: #4b5563;
        color: white;
        text-decoration: none;
    }

    .back-button i {
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('page-title', 'Statistics Report')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Back to Reports Button -->
            <a href="{{ route('admin.reports.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to Reports
            </a>
            <div class="form-card">
                <div class="form-card-header">
                    <h3 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        Report Configuration
                    </h3>
                </div>
                <div class="form-card-body">
                    <form action="{{ route('admin.reports.statistics.generate') }}" method="POST" id="reportForm">
                        @csrf
                        
                        <!-- Time Period Selection -->
                        <div class="form-group">
                            <div class="time-period-header">
                                <label class="form-label">Select Time Period</label>
                                <span class="time-period-badge">Choose Report Range</span>
                            </div>
                            <div class="period-grid">
                                <div class="period-card" onclick="selectPeriod('week')" data-period="week">
                                    <div class="period-icon">
                                        <i class="fas fa-calendar-week"></i>
                                    </div>
                                    <div class="period-title">This Week</div>
                                    <div class="period-desc">Last 7 days</div>
                                </div>
                                
                                <div class="period-card selected" onclick="selectPeriod('month')" data-period="month">
                                    <div class="period-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="period-title">This Month</div>
                                    <div class="period-desc">Current month</div>
                                </div>
                                
                                <div class="period-card" onclick="selectPeriod('quarter')" data-period="quarter">
                                    <div class="period-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="period-title">This Quarter</div>
                                    <div class="period-desc">Last 3 months</div>
                                </div>
                                
                                <div class="period-card" onclick="selectPeriod('year')" data-period="year">
                                    <div class="period-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="period-title">This Year</div>
                                    <div class="period-desc">Current year</div>
                                </div>
                            </div>
                            <input type="hidden" name="period" id="selectedPeriod" value="month">
                        </div>

                        <!-- Output Format -->
                        <div class="form-group">
                            <label class="form-label">Output Format</label>
                            <select name="format" class="form-input" required>
                                <option value="preview">Preview (Web)</option>
                                <option value="pdf">PDF Download</option>
                                <option value="docx">Word/DOCX Download</option>
                                <option value="excel">Excel Download</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
                            <button type="button" onclick="resetForm()" class="btn btn-secondary">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    sidebar.classList.toggle('open');
}

function selectPeriod(period) {
    // Remove selected class from all cards
    document.querySelectorAll('.period-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card with animation
    const selectedCard = event.currentTarget;
    selectedCard.classList.add('selected');
    
    // Add a subtle pulse animation
    selectedCard.style.animation = 'pulse 0.6s ease-in-out';
    setTimeout(() => {
        selectedCard.style.animation = '';
    }, 600);
    
    // Update hidden input
    document.getElementById('selectedPeriod').value = period;
    
    // Optional: Show feedback message
    showPeriodFeedback(period);
}

function showPeriodFeedback(period) {
    const periodNames = {
        'week': 'This Week',
        'month': 'This Month', 
        'quarter': 'This Quarter',
        'year': 'This Year'
    };
    
    // Create temporary feedback element
    const feedback = document.createElement('div');
    feedback.textContent = `Selected: ${periodNames[period]}`;
    feedback.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #059669;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        z-index: 1000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    `;
    
    document.body.appendChild(feedback);
    
    // Animate in
    setTimeout(() => {
        feedback.style.opacity = '1';
        feedback.style.transform = 'translateY(0)';
    }, 10);
    
    // Remove after 2 seconds
    setTimeout(() => {
        feedback.style.opacity = '0';
        feedback.style.transform = 'translateY(-20px)';
        setTimeout(() => {
            document.body.removeChild(feedback);
        }, 300);
    }, 2000);
}

function resetForm() {
    document.getElementById('reportForm').reset();
    
    // Reset period selection with animation
    document.querySelectorAll('.period-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Select month by default with animation
    const monthCard = document.querySelector('.period-card[data-period="month"]');
    setTimeout(() => {
        monthCard.classList.add('selected');
        monthCard.style.animation = 'pulse 0.6s ease-in-out';
        setTimeout(() => {
            monthCard.style.animation = '';
        }, 600);
    }, 100);
    
    document.getElementById('selectedPeriod').value = 'month';
    
    // Show reset feedback
    showPeriodFeedback('month');
}

// Close sidebar when clicking outside
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('adminSidebar');
    const toggleButton = event.target.closest('button');
    
    if (!sidebar.contains(event.target) && !toggleButton) {
        sidebar.classList.remove('open');
    }
});
</script>
@endsection
