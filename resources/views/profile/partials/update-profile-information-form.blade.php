<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Username')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-amber-600 bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ __('Your username is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-amber-700 hover:text-amber-800 ml-1">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg p-3">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Department Information (if applicable) -->
        @if($user->role === 'student' || $user->department)
        <div class="border-t border-gray-200 pt-6">
            <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-building text-green-600 mr-2"></i>
                Department Information
            </h4>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <x-input-label for="department" :value="__('School')" />
                    <select id="department" name="department" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm">
                        <option value="">Select School</option>
                        <option value="SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION" {{ old('department', $user->department) === 'SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION' ? 'selected' : '' }}>SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION</option>
                        <option value="SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT" {{ old('department', $user->department) === 'SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT' ? 'selected' : '' }}>SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT</option>
                        <option value="SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING" {{ old('department', $user->department) === 'SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING' ? 'selected' : '' }}>SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING</option>
                        <option value="SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES" {{ old('department', $user->department) === 'SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES' ? 'selected' : '' }}>SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES</option>
                        <option value="SCHOOL OF MEDICINE" {{ old('department', $user->department) === 'SCHOOL OF MEDICINE' ? 'selected' : '' }}>SCHOOL OF MEDICINE</option>
                        <option value="Other" {{ old('department', $user->department) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('department')" />
                </div>
            </div>
        </div>
        @endif

        <!-- Role Information (Read-only) -->
        <div class="border-t border-gray-200 pt-6">
            <h4 class="text-md font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user-tag text-blue-600 mr-2"></i>
                Account Information
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="role_display" :value="__('Role')" />
                    <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                        <span class="inline-flex items-center">
                            <i class="fas fa-user-circle mr-2 text-green-600"></i>
                            {{ $user->role === 'student' ? 'User' : ucfirst($user->role) }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Role cannot be changed. Contact administrator if needed.</p>
                </div>

                <div>
                    <x-input-label for="status_display" :value="__('Account Status')" />
                    <div class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                        <span class="inline-flex items-center">
                            @if($user->is_active)
                                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                Active
                            @else
                                <i class="fas fa-times-circle mr-2 text-red-600"></i>
                                Inactive
                            @endif
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Status is managed by administrators.</p>
                </div>
            </div>

            <!-- School Information Display -->
            @if($user->department)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-university text-green-600 mr-2"></i>
                    School Affiliation
                </h5>
                <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-green-600 text-lg"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h6 class="text-sm font-medium text-gray-900">{{ $user->department }}</h6>
                            <p class="text-xs text-gray-600 mt-1">
                                @if($user->role === 'student')
                                    Your academic school
                                @elseif($user->role === 'adviser')
                                    Faculty member
                                @elseif($user->role === 'dean')
                                    Dean/Unit Head
                                @else
                                    Affiliated school
                                @endif
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Verified
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm text-green-600 bg-green-50 border border-green-200 rounded-lg px-3 py-2 flex items-center"
                    >
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('Profile updated successfully!') }}
                    </p>
                @endif
            </div>

            <div class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Last updated: {{ $user->updated_at->format('M d, Y \a\t g:i A') }}
            </div>
        </div>
    </form>
</section>
