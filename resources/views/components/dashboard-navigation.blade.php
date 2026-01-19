<!-- Dashboard Navigation Component -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-compass mr-2 text-blue-600"></i>
            Explore Role Dashboards
        </h3>
        <p class="text-sm text-gray-600 mt-1">
            View different role interfaces to understand the complete approval workflow
        </p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Student Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center p-3 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors {{ Auth::user()->role === 'student' ? 'bg-blue-100 border-blue-300' : '' }}">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-graduate text-blue-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-blue-900 text-sm">Student</p>
                    <p class="text-xs text-blue-700">Submit Activities</p>
                </div>
                @if(Auth::user()->role === 'student')
                    <i class="fas fa-check-circle text-blue-600"></i>
                @endif
            </a>

            <!-- Adviser Dashboard -->
            <a href="{{ route('adviser.dashboard') }}" 
               class="flex items-center p-3 border border-green-200 rounded-lg hover:bg-green-50 transition-colors {{ Auth::user()->role === 'adviser' ? 'bg-green-100 border-green-300' : '' }}">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-green-900 text-sm">Adviser</p>
                    <p class="text-xs text-green-700">Note Activities</p>
                </div>
                @if(Auth::user()->role === 'adviser')
                    <i class="fas fa-check-circle text-green-600"></i>
                @endif
            </a>

            <!-- Dean Dashboard -->
            <a href="{{ route('dean.dashboard') }}" 
               class="flex items-center p-3 border border-purple-200 rounded-lg hover:bg-purple-50 transition-colors {{ Auth::user()->role === 'dean' ? 'bg-purple-100 border-purple-300' : '' }}">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-tie text-purple-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-purple-900 text-sm">Dean</p>
                    <p class="text-xs text-purple-700">Department Approval</p>
                </div>
                @if(Auth::user()->role === 'dean')
                    <i class="fas fa-check-circle text-purple-600"></i>
                @endif
            </a>

            <!-- PSG Adviser Dashboard -->
            <a href="{{ route('psg.dashboard') }}" 
               class="flex items-center p-3 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition-colors {{ Auth::user()->role === 'psg_adviser' ? 'bg-indigo-100 border-indigo-300' : '' }}">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-users text-indigo-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-indigo-900 text-sm">PSG Adviser</p>
                    <p class="text-xs text-indigo-700">Policy Review</p>
                </div>
                @if(Auth::user()->role === 'psg_adviser')
                    <i class="fas fa-check-circle text-indigo-600"></i>
                @endif
            </a>

            <!-- Director Dashboard -->
            <a href="{{ route('director.dashboard') }}" 
               class="flex items-center p-3 border border-yellow-200 rounded-lg hover:bg-yellow-50 transition-colors {{ Auth::user()->role === 'director_student_affairs' ? 'bg-yellow-100 border-yellow-300' : '' }}">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-building text-yellow-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-yellow-900 text-sm">Director</p>
                    <p class="text-xs text-yellow-700">University Endorsement</p>
                </div>
                @if(Auth::user()->role === 'director_student_affairs')
                    <i class="fas fa-check-circle text-yellow-600"></i>
                @endif
            </a>

            <!-- VP Dashboard -->
            <a href="{{ route('vp.dashboard') }}" 
               class="flex items-center p-3 border border-red-200 rounded-lg hover:bg-red-50 transition-colors {{ Auth::user()->role === 'vp_academics' ? 'bg-red-100 border-red-300' : '' }}">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-crown text-red-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-red-900 text-sm">VP Academics</p>
                    <p class="text-xs text-red-700">Final Approval</p>
                </div>
                @if(Auth::user()->role === 'vp_academics')
                    <i class="fas fa-check-circle text-red-600"></i>
                @endif
            </a>

            <!-- OSA Dashboard -->
            <a href="{{ route('osa.dashboard') }}" 
               class="flex items-center p-3 border border-teal-200 rounded-lg hover:bg-teal-50 transition-colors {{ Auth::user()->role === 'osa' ? 'bg-teal-100 border-teal-300' : '' }}">
                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clipboard-list text-teal-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-teal-900 text-sm">OSA Staff</p>
                    <p class="text-xs text-teal-700">Workflow Management</p>
                </div>
                @if(Auth::user()->role === 'osa')
                    <i class="fas fa-check-circle text-teal-600"></i>
                @endif
            </a>

            <!-- Admin Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors {{ Auth::user()->role === 'admin' ? 'bg-gray-100 border-gray-300' : '' }}">
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-cog text-gray-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-900 text-sm">Admin</p>
                    <p class="text-xs text-gray-700">System Management</p>
                </div>
                @if(Auth::user()->role === 'admin')
                    <i class="fas fa-check-circle text-gray-600"></i>
                @endif
            </a>

        </div>
        
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex items-center text-sm text-gray-600">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                <span>
                    <strong>Tip:</strong> Your current role is highlighted with a checkmark. 
                    You can explore other dashboards to understand how the approval workflow functions from different perspectives.
                </span>
            </div>
        </div>
    </div>
</div>
