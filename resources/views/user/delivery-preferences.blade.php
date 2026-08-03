@extends('layouts.user')

@section('title', __('Complete Your Profile') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Customer Onboarding'))

@section('content')

<style>
[x-cloak]{display:none!important}
.form-label {
    display: block;
    margin-bottom: .35rem;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #6b7280
}

.form-input {
    width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: .75rem;
    padding: .7rem .8rem;
    background: #fff;
    font-size: .875rem;
    outline: none
}

.form-input:focus {
    border-color: #6E7A25;
    box-shadow: 0 0 0 3px rgba(110, 122, 37, .14)
}

.choice-chip {
    min-height: 2.75rem;
    border: 1px solid #e5e7eb;
    border-radius: .75rem;
    padding: .6rem .75rem;
    background: #fff;
    font-size: .75rem;
    font-weight: 600;
    color: #4b5563;
    transition: .2s
}

.choice-chip:hover {
    border-color: #9CAB46;
    background: #F8FAF0
}

.choice-chip-selected {
    border-color: #6E7A25 !important;
    background: #F1F5D9 !important;
    color: #173327 !important
}

.health-plan-card {
    width: 100%;
    min-width: 0;
    position: relative;
    overflow: hidden;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1.5rem;
    padding: 2rem;
    box-shadow: 0 18px 45px rgba(23, 51, 39, .08)
}

.health-plan-accent {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #173327, #6E7A25)
}

.health-plan-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.8rem
}

.health-kicker {
    font-size: .66rem;
    font-weight: 800;
    letter-spacing: .12em;
    color: #6E7A25
}

.health-title {
    margin-top: .25rem;
    font-size: 1.35rem;
    line-height: 1.25;
    font-weight: 900;
    color: #173327
}

.health-subtitle {
    margin-top: .4rem;
    max-width: 38rem;
    font-size: .79rem;
    line-height: 1.6;
    color: #7b8497
}

.health-status-pill {
    display: flex;
    align-items: center;
    gap: .45rem;
    border: 1px solid #DDE4B4;
    border-radius: 999px;
    padding: .45rem .75rem;
    background: #F7F9EA;
    color: #53601B;
    font-size: .7rem;
    font-weight: 800;
    white-space: nowrap
}

.health-status-dot {
    width: .45rem;
    height: .45rem;
    border-radius: 999px;
    background: #6E7A25
}

.health-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem
}

.health-field { min-width: 0; }

.health-field label {
    display: block;
    margin-bottom: .4rem;
    font-size: .68rem;
    font-weight: 800;
    letter-spacing: .045em;
    color: #667085
}

.health-field label small {
    text-transform: none;
    font-size: .68rem;
    font-weight: 500;
    letter-spacing: 0;
    color: #99A1B3
}

.health-input-wrap {
    position: relative
}

.health-input-wrap input,
.health-input-wrap select {
    display: block;
    width: 100%;
    height: 3rem;
    border: 1px solid #DDE2EA;
    border-radius: .85rem;
    padding: 0 3.4rem 0 1rem;
    background: #FBFCFD;
    color: #182230;
    font-size: .88rem;
    outline: none;
    transition: .2s
}

.health-input-wrap select {
    appearance: auto;
    padding-right: 1rem
}

.health-input-wrap input:focus,
.health-input-wrap select:focus {
    border-color: #6E7A25;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(110, 122, 37, .12)
}

.health-input-wrap>span {
    position: absolute;
    top: 50%;
    right: 1rem;
    transform: translateY(-50%);
    color: #98A2B3;
    font-size: .7rem;
    pointer-events: none
}

.health-helper {
    margin-top: .35rem;
    font-size: .68rem;
    color: #8390A6
}

.gender-toggle {
    display: grid;
    grid-template-columns: 1fr 1fr;
    overflow: hidden;
    height: 3rem;
    border: 1px solid #DDE2EA;
    border-radius: .85rem;
    background: #FBFCFD
}

.gender-toggle button {
    border: 0;
    background: transparent;
    color: #667085;
    font-size: .78rem;
    font-weight: 700;
    transition: .2s
}

.gender-toggle .gender-active {
    background: #21A600;
    color: #fff
}

.health-hidden-select {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    opacity: 0 !important;
    pointer-events: none !important
}

.health-calculate-btn {
    width: 100%;
    margin-top: 1.5rem;
    min-height: 3.1rem;
    border-radius: .85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .65rem;
    background: linear-gradient(90deg, #173327, #6E7A25);
    color: #fff;
    font-size: .83rem;
    font-weight: 900;
    box-shadow: 0 10px 24px rgba(23, 51, 39, .16);
    transition: .2s
}

.health-calculate-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 30px rgba(23, 51, 39, .2)
}

.health-calculate-btn svg {
    width: 1rem;
    height: 1rem;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8
}

.health-results-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: .85rem;
    margin-top: 1.65rem
}

.health-result-box {
    min-height: 7.2rem;
    border: 1px dashed #DDE2EA;
    border-radius: 1rem;
    background: #FBFCFD;
    padding: 1rem .65rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center
}

.health-result-box p {
    min-height: 1.7rem;
    font-size: .58rem;
    font-weight: 800;
    letter-spacing: .04em;
    color: #A0A9BA
}

.health-result-box strong {
    margin-top: .35rem;
    font-size: 1.05rem;
    font-weight: 900;
    color: #173327
}

.health-result-box strong.health-result-text {
    font-size: .72rem;
    line-height: 1.2
}

.health-result-box span {
    margin-top: .35rem;
    font-size: .58rem;
    color: #98A2B3
}

.health-disclaimer {
    margin-top: 1.4rem;
    text-align: center;
    font-size: .69rem;
    color: #98A2B3
}


.profile-submit-btn {
    width: 100%;
    min-height: 3.6rem;
    margin-top: .25rem;
    padding: .9rem 1.25rem;
    border: 0;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .65rem;
    background: linear-gradient(90deg, #173327 0%, #415526 52%, #7A8B20 100%);
    color: #ffffff !important;
    font-size: .92rem;
    font-weight: 900;
    line-height: 1.2;
    letter-spacing: .01em;
    cursor: pointer;
    box-shadow: 0 12px 28px rgba(23, 51, 39, .22);
    transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
}

.profile-submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 16px 34px rgba(23, 51, 39, .28);
}

.profile-submit-btn:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(110, 122, 37, .2),
                0 12px 28px rgba(23, 51, 39, .22);
}

.profile-submit-btn:disabled {
    cursor: not-allowed;
    opacity: .65;
}

.profile-submit-btn span {
    display: inline-block;
    color: #ffffff !important;
    visibility: visible !important;
}


.profile-submit-label {
    display: inline-block !important;
    visibility: visible !important;
    opacity: 1 !important;
    color: #ffffff !important;
    -webkit-text-fill-color: #ffffff !important;
    font-size: .95rem !important;
    font-weight: 900 !important;
    line-height: 1.25 !important;
    text-indent: 0 !important;
    white-space: normal !important;
}

.profile-submit-icon,
.profile-submit-spinner {
    width: 1.15rem;
    height: 1.15rem;
    flex: 0 0 auto;
    color: #ffffff;
}

.profile-submit-spinner {
    animation: profile-spin .8s linear infinite;
}

.profile-spinner-track {
    opacity: .25;
}

.profile-spinner-head {
    opacity: .9;
}

@keyframes profile-spin {
    to { transform: rotate(360deg); }
}


.gender-toggle button.gender-active,
.gender-toggle button.is-selected {
    background: #21A600 !important;
    color: #ffffff !important;
    font-weight: 900 !important;
    box-shadow: inset 0 0 0 2px rgba(255,255,255,.22);
}

.gender-toggle button:not(.gender-active):not(.is-selected) {
    background: #FBFCFD !important;
    color: #667085 !important;
    font-weight: 700 !important;
}

.calculator-feedback {
    margin-top: 1rem;
    padding: .85rem 1rem;
    border-radius: .85rem;
    font-size: .8rem;
    font-weight: 800;
    text-align: center;
}

.calculator-feedback.success {
    display: block !important;
    background: #F1F8E8;
    border: 1px solid #C9DA9A;
    color: #365314;
}

.calculator-feedback.error {
    display: block !important;
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #B42318;
}

.health-results-grid.results-filled .health-result-box {
    border-style: solid;
    border-color: #C9DA9A;
    background: #FAFCEF;
}

.health-results-grid.results-filled .health-result-box strong {
    color: #173327;
}


.native-submit-message {
    display: block !important;
    margin-top: 1rem;
    padding: .9rem 1rem;
    border-radius: .85rem;
    font-size: .82rem;
    font-weight: 700;
    line-height: 1.45;
}

.native-submit-message.error {
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #b42318;
}

.native-submit-message.success {
    border: 1px solid #bbf7d0;
    background: #f0fdf4;
    color: #166534;
}

@media(max-width:900px) {
    .health-results-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr))
    }
}

@media(max-width:700px) {
    .health-plan-card {
    width: 100%;
    min-width: 0;
        padding: 1.25rem
    }

    .health-plan-header {
        flex-direction: column
    }

    .health-grid {
        grid-template-columns: 1fr
    }

    .health-results-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr))
    }
}
</style>

<div id="customer-onboarding-root" x-data="customerOnboardingPage()" x-init="init()" class="max-w-4xl mx-auto space-y-5">

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div
        class="bg-gradient-to-br from-[#173327] to-[#025C5F] rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
        <div class="relative z-10">
            <h2 class="text-lg font-bold">{{ __('Complete Your Health Profile') }}</h2>
            <p class="text-xs text-white/75 mt-1">
                {{ __('This information helps our nutrition team assign meals that match your goals and health needs.') }}
            </p>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <div class="rounded-xl p-3 bg-white/15 border border-white/10">
                    <p class="text-[10px] uppercase text-white/60">{{ __('Step 1') }}</p>
                    <p class="text-sm font-bold">{{ __('Health Profile') }}</p>
                </div>
                <div class="rounded-xl p-3 bg-white/10 border border-white/10">
                    <p class="text-[10px] uppercase text-white/60">{{ __('Step 2') }}</p>
                    <p class="text-sm font-bold">{{ __('Delivery Preferences') }}</p>
                </div>
            </div>
        </div>
    </div>

    <form
    id="health-profile-form"
    @submit.prevent="saveProfile()"
    x-show="!deliveryModalOpen"
    x-cloak
    class="space-y-5"
>

        {{-- Health Calculator Style Section --}}
        <section class="health-plan-card">
            <div class="health-plan-accent"></div>

            <div class="health-plan-header">
                <div>
                    <p class="health-kicker">{{ __('HEALTH PROFILE') }}</p>
                    <h3 class="health-title">{{ __('Build Your Healthy Meal Plan') }}</h3>
                    <p class="health-subtitle">
                        {{ __('Enter your age, body information, lifestyle and main goal so we can personalize your meals.') }}
                    </p>
                </div>

                <div class="health-status-pill">
                    <span class="health-status-dot"></span>
                    {{ __('Personalized') }}
                </div>
            </div>

            <div class="health-grid">
                <div class="health-field">
                    <label for="age">{{ __('AGE') }}</label>
                    <div class="health-input-wrap">
                        <input
                            id="age"
                            type="number"
                            min="18"
                            max="100"
                            step="1"
                            x-model.number="profile.age"
                            required
                            placeholder="30"
                        >
                        <span>{{ __('years') }}</span>
                    </div>
                </div>

                <div class="health-field">
                    <label for="weight">{{ __('WEIGHT') }}</label>
                    <div class="health-input-wrap">
                        <input
                            id="weight"
                            type="number"
                            min="25"
                            max="400"
                            step="0.1"
                            x-model.number="profile.weight_kg"
                            required
                            placeholder="70"
                        >
                        <span>{{ __('kg') }}</span>
                    </div>
                </div>

                <div class="health-field">
                    <label for="height">{{ __('HEIGHT') }}</label>
                    <div class="health-input-wrap">
                        <input
                            id="height"
                            type="number"
                            min="80"
                            max="250"
                            step="0.1"
                            x-model.number="profile.height_cm"
                            required
                            placeholder="175"
                        >
                        <span>{{ __('cm') }}</span>
                    </div>
                </div>

                <div class="health-field">
                    <label>{{ __('GENDER') }}</label>

                    <div class="gender-toggle">
                        <button
                            id="gender-male-btn"
                            type="button"
                            @click.prevent="selectGender('male')"
                            onclick="window.selectCustomerGender && window.selectCustomerGender('male')"
                            :class="{ 'gender-active': profile.gender === 'male' }"
                            aria-pressed="false"
                        >
                            {{ __('Male') }}
                        </button>

                        <button
                            id="gender-female-btn"
                            type="button"
                            @click.prevent="selectGender('female')"
                            onclick="window.selectCustomerGender && window.selectCustomerGender('female')"
                            :class="{ 'gender-active': profile.gender === 'female' }"
                            aria-pressed="false"
                        >
                            {{ __('Female') }}
                        </button>
                    </div>

                    <input id="selected-gender" type="hidden" name="gender" x-model="profile.gender">
                    <p id="selected-gender-text" class="health-helper" style="display:none;">
                        {{ __('Selected gender:') }} <strong></strong>
                    </p>
                </div>

                <div class="health-field">
                    <label for="fitness_goal">{{ __('GOAL') }}</label>
                    <div class="health-input-wrap">
                        <select id="fitness_goal" x-model="profile.fitness_goal" required>
                            <option value="">{{ __('Select a goal') }}</option>
                            <option value="weight_loss">{{ __('Weight Loss') }}</option>
                            <option value="weight_maintenance">{{ __('Maintain Weight') }}</option>
                            <option value="muscle_gain">{{ __('Muscle Gain') }}</option>
                            <option value="healthy_eating">{{ __('Healthy Eating') }}</option>
                            <option value="improve_fitness">{{ __('Improve Fitness') }}</option>
                            <option value="sports_performance">{{ __('Sports Performance') }}</option>
                        </select>
                    </div>
                </div>

                <div class="health-field">
                    <label for="activity_level">{{ __('ACTIVITY LEVEL') }}</label>
                    <div class="health-input-wrap">
                        <select id="activity_level" x-model="profile.activity_level" required>
                            <option value="">{{ __('Select activity level') }}</option>
                            <option value="sedentary">{{ __('Sedentary') }}</option>
                            <option value="lightly_active">{{ __('Lightly Active') }}</option>
                            <option value="moderately_active">{{ __('Moderately Active') }}</option>
                            <option value="very_active">{{ __('Very Active') }}</option>
                            <option value="athlete">{{ __('Athlete') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <button
                type="button"
                class="health-calculate-btn"
                @click.prevent="calculatePlan()"
                onclick="window.calculateCustomerPlan && window.calculateCustomerPlan()"
            >
                <svg viewBox="0 0 24 24">
                    <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                    <path d="M8 7h8M8 11h2m4 0h2M8 15h2m4 0h2M8 19h2m4 0h2"></path>
                </svg>

                {{ __('Calculate My Plan') }}
            </button>

            <div id="calculator-feedback" class="calculator-feedback" style="display:none;"></div>

            <div id="health-results" x-ref="healthResults" class="health-results-grid">
                <div class="health-result-box">
                    <p>{{ __('AGE') }}</p>
                    <strong id="result-age" x-text="profile.age || '---'">---</strong>
                    <span>{{ __('years') }}</span>
                </div>

                <div class="health-result-box">
                    <p>{{ __('BMI') }}</p>
                    <strong id="result-bmi" x-text="bmi !== null ? bmi : '---'">---</strong>
                    <span>{{ __('estimated') }}</span>
                </div>

                <div class="health-result-box">
                    <p>{{ __('CURRENT WEIGHT') }}</p>
                    <strong id="result-weight" x-text="profile.weight_kg || '---'">---</strong>
                    <span>{{ __('kg') }}</span>
                </div>

                <div class="health-result-box">
                    <p>{{ __('HEIGHT') }}</p>
                    <strong id="result-height" x-text="profile.height_cm || '---'">---</strong>
                    <span>{{ __('cm') }}</span>
                </div>

                <div class="health-result-box">
                    <p>{{ __('ACTIVITY') }}</p>
                    <strong
                        id="result-activity"
                        class="health-result-text"
                        x-text="activityLevelLabel || '---'"
                    >---</strong>
                    <span>{{ __('level') }}</span>
                </div>

                <div class="health-result-box">
                    <p>{{ __('GOAL') }}</p>
                    <strong
                        id="result-goal"
                        class="health-result-text"
                        x-text="fitnessGoalLabel || '---'"
                    >---</strong>
                    <span>{{ __('selected') }}</span>
                </div>
            </div>

            <p class="health-disclaimer">
                {{ __('Results are estimates. Your nutrition team will review your complete profile before assigning meals.') }}
            </p>
        </section>

        <div x-show="healthOptionsLoading" x-cloak
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-center gap-3 text-sm text-gray-600">
            <svg class="w-5 h-5 animate-spin text-[#6E7A25]" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"></circle>
                <path class="opacity-90" fill="currentColor"
                    d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3z"></path>
            </svg>
            <span>{{ __('Loading health profile options...') }}</span>
        </div>

        <div x-show="healthOptionsError" x-cloak
            class="bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4 text-sm">
            <p class="font-bold">{{ __('Unable to load health profile options.') }}</p>
            <p class="mt-1" x-text="healthOptionsError"></p>
            <button type="button" @click="loadHealthOptions()"
                class="mt-3 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold">
                {{ __('Try Again') }}
            </button>
        </div>

        <section x-show="!healthOptionsLoading && !healthOptionsError"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Dietary Preferences') }}</h3>
            </div>
            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="item in dietaryOptions" :key="item.value">
                    <button type="button" @click="toggleArrayValue('dietary_preferences', item.value)"
                        class="choice-chip"
                        :class="isSelected('dietary_preferences', item.value) ? 'choice-chip-selected' : ''"
                        x-text="item.label"></button>
                </template>
            </div>
        </section>

        <section x-show="!healthOptionsLoading && !healthOptionsError"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Food Allergies') }}</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <template x-for="item in allergyOptions" :key="item.value">
                        <button type="button" @click="toggleExclusiveNone('allergies', item.value)" class="choice-chip"
                            :class="isSelected('allergies', item.value) ? 'choice-chip-selected' : ''"
                            x-text="item.label"></button>
                    </template>
                </div>
                <input type="text" x-model.trim="profile.other_allergy" class="form-input"
                    placeholder="{{ __('Other allergy') }}">
            </div>
        </section>

        <section x-show="!healthOptionsLoading && !healthOptionsError"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Health Conditions') }}</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <template x-for="item in conditionOptions" :key="item.value">
                        <button type="button" @click="toggleExclusiveNone('chronic_conditions', item.value)"
                            class="choice-chip"
                            :class="isSelected('chronic_conditions', item.value) ? 'choice-chip-selected' : ''"
                            x-text="item.label"></button>
                    </template>
                </div>
                <input type="text" x-model.trim="profile.other_condition" class="form-input"
                    placeholder="{{ __('Other condition') }}">
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Meal Assignment Notes') }}</h3>
            </div>
            <div class="p-5 space-y-4">
                <textarea x-model.trim="profile.foods_to_avoid" rows="3" class="form-input"
                    placeholder="{{ __('Foods to avoid') }}"></textarea>
                <textarea x-model.trim="profile.health_notes" rows="4" class="form-input"
                    placeholder="{{ __('Additional health or nutrition notes') }}"></textarea>
            </div>
        </section>

        <div id="profile-submit-message" x-show="profileMessage" x-cloak class="px-4 py-3 rounded-xl"
            :class="profileSuccess ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'">
            <p x-text="profileMessage"></p>
            <template x-if="profileErrors.length">
                <ul class="mt-2 text-xs list-disc list-inside">
                    <template x-for="error in profileErrors" :key="error">
                        <li x-text="error"></li>
                    </template>
                </ul>
            </template>
        </div>

        <button id="continue-delivery-btn"
            type="submit"
            :disabled="savingProfile || healthOptionsLoading || !!healthOptionsError"
            class="profile-submit-btn"
            aria-label="{{ __('Continue to Delivery Preferences') }}">

            <svg id="continue-btn-icon" x-show="!savingProfile" class="profile-submit-icon" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 12h14m-6-6 6 6-6 6" />
            </svg>

            <svg id="continue-btn-loader" x-show="savingProfile" x-cloak class="profile-submit-spinner"
                viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle class="profile-spinner-track" cx="12" cy="12" r="9"
                    stroke="currentColor" stroke-width="3"></circle>
                <path class="profile-spinner-head" fill="currentColor"
                    d="M12 3a9 9 0 0 1 9 9h-3a6 6 0 0 0-6-6V3z"></path>
            </svg>

            <span id="continue-btn-text" x-show="!savingProfile" class="profile-submit-label">
                {{ __('Continue to Delivery Preferences') }}
            </span>

            <span id="continue-btn-loading-text" x-show="savingProfile" x-cloak class="profile-submit-label">
                {{ __('Saving health details...') }}
            </span>
        </button>
    </form>

    <div id="delivery-preferences-modal" x-show="deliveryModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="absolute inset-0 bg-gray-950/65 backdrop-blur-sm"></div>

        <div
            class="relative z-10 bg-gray-50 w-full max-w-4xl max-h-[92vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-[#173327] to-[#025C5F] text-white px-5 py-4 flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] uppercase text-white/60">{{ __('Step 2 of 2') }}</p>
                    <h3 class="text-base font-bold">{{ __('Set Your Delivery Destinations') }}</h3>
                    <p class="text-xs text-white/75 mt-1 max-w-2xl">
    {{ __('Tell us where to deliver each meal category — Breakfast, Lunch, Dinner and Snack.') }}
</p>
                </div>
                <button type="button" @click="closeDeliveryModal()" class="w-9 h-9 rounded-xl bg-white/10">×</button>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
    <p class="text-sm font-bold text-blue-900">
        {{ __('Different meals can have different delivery locations.') }}
    </p>

    <p class="text-xs text-blue-700 mt-1 leading-5">
        {{ __('For example, breakfast can be delivered to your home, lunch to your workplace, dinner to your home, and your snack to the gym.') }}
    </p>
</div>

            <div class="overflow-y-auto p-4 sm:p-5 space-y-4">
                <div class="bg-white rounded-xl border p-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-xs font-bold">{{ __('Progress') }}</span>
                        <span class="text-xs font-bold text-[#6E7A25]"
                            x-text="completedCount + ' / ' + preferences.length"></span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-full bg-[#6E7A25] rounded-full" :style="'width:' + deliveryProgress + '%'"></div>
                    </div>
                </div>

                <form id="delivery-form" @submit.prevent="saveDelivery()" class="space-y-4">
                    <template x-for="(pref,index) in preferences" :key="pref.meal_category_id || index">
                        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                            <div class="p-4 border-b bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-bold" x-text="pref.category_name"></h4>
                                    <span class="text-xs"
                                        :class="isCategoryComplete(pref) ? 'text-green-600' : 'text-amber-500'"
                                        x-text="isCategoryComplete(pref) ? '{{ __('Completed') }}' : '{{ __('Required') }}'"></span>
                                </div>

                                <button
                                    type="button"
                                    @click="copyLocationToAll(pref)"
                                    class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl
                                           bg-[#173327] text-white text-xs font-bold
                                           hover:bg-[#6E7A25] transition-colors shadow-sm">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="11" height="11" rx="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    {{ __('Use this location for all meals') }}
                                </button>
                            </div>

                            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">{{ __('Place Type') }} *</label>
                                    <select x-model="pref.place_type" required class="form-input">
                                        <option value="">{{ __('Select') }}</option>
                                        <option value="home">{{ __('Home') }}</option>
                                        <option value="work">{{ __('Work') }}</option>
                                        <option value="gym">{{ __('Gym') }}</option>
                                        <option value="school">{{ __('School') }}</option>
                                        <option value="university">{{ __('University') }}</option>
                                        <option value="other">{{ __('Other') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">{{ __('Place Name') }}</label>
                                    <input x-model.trim="pref.place_name" class="form-input">
                                </div>

                                <div>
                                    <label class="form-label">{{ __('City') }} *</label>
                                    <input x-model.trim="pref.city" required class="form-input">
                                </div>

                                <div>
                                    <label class="form-label">{{ __('Delivery Area') }} *</label>
                                    <input x-model.trim="pref.delivery_area" required class="form-input">
                                </div>

                                <div class="sm:col-span-2">
                                    <label class="form-label">{{ __('Delivery Address') }} *</label>
                                    <input x-model.trim="pref.delivery_address" required class="form-input">
                                </div>

                                {{-- <div>
                                    <label class="form-label">{{ __('Preferred Delivery Time') }} *</label>
                                    <input type="time" x-model="pref.preferred_delivery_time" required
                                        class="form-input">
                                </div> --}}

                                <div>
                                    <label class="form-label">{{ __('Delivery Note') }}</label>
                                    <input x-model.trim="pref.delivery_note" class="form-input">
                                </div>
                            </div>
                        </div>
                    </template>
                </form>

                <div x-show="deliveryMessage" x-cloak class="px-4 py-3 rounded-xl"
                    :class="deliverySuccess ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'">
                    <p x-text="deliveryMessage"></p>
                </div>
            </div>

            <div class="border-t bg-white px-5 py-4 flex justify-end gap-2">
                <button type="button" @click="closeDeliveryModal()"
                    class="px-4 py-2.5 rounded-xl border text-sm font-bold">
                    {{ __('Edit Profile') }}
                </button>

                <button type="submit" form="delivery-form" :disabled="savingDelivery || !allDeliveryPreferencesComplete"
                    class="px-5 py-2.5 rounded-xl bg-[#173327] text-white text-sm font-bold disabled:opacity-50">
                    <span
                        x-text="savingDelivery ? '{{ __('Saving...') }}' : '{{ __('Save and Go to Dashboard') }}'"></span>
                </button>
            </div>
        </div>
    </div>
</div>



<script>
function customerOnboardingPage() {
    const initialProfile = @json($healthProfileJson ?? []);
    const initialPreferences = @json($deliveryPrefsJson ?? []);

    return {
        savingProfile: false,
        savingDelivery: false,
        profileMessage: '',
        profileSuccess: false,
        profileErrors: [],
        deliveryMessage: '',
        deliverySuccess: false,
        deliveryModalOpen: @json($startAtDelivery ?? false),
        today: new Date().toISOString().split('T')[0],
        locale: @json(app()->getLocale()),
        healthOptionsLoading: true,
        healthOptionsError: '',
        healthOptionsUrl: @json(route('user.health-profile-options.public')),

        profile: {
            age: Number(initialProfile.age ?? '') || '',
            gender: initialProfile.gender || '',
            height_cm: initialProfile.height_cm ?? initialProfile.height ?? '',
            weight_kg: initialProfile.weight_kg ?? initialProfile.weight ?? '',
            fitness_goal: initialProfile.fitness_goal || '',
            activity_level: initialProfile.activity_level || '',
            dietary_preferences: Array.isArray(initialProfile.dietary_preferences)
                ? initialProfile.dietary_preferences
                : [],
            allergies: Array.isArray(initialProfile.allergies)
                ? initialProfile.allergies
                : [],
            chronic_conditions: Array.isArray(initialProfile.chronic_conditions)
                ? initialProfile.chronic_conditions
                : [],
            other_allergy: '',
            other_condition: '',
            foods_to_avoid: initialProfile.foods_to_avoid || '',
            health_notes: initialProfile.health_notes || ''
        },

        preferences: Array.isArray(initialPreferences) ? initialPreferences : [],

        goalLabels: {
            weight_loss: '{{ __("Weight Loss") }}',
            weight_maintenance: '{{ __("Maintain Weight") }}',
            muscle_gain: '{{ __("Muscle Gain") }}',
            healthy_eating: '{{ __("Healthy Eating") }}',
            improve_fitness: '{{ __("Improve Fitness") }}',
            sports_performance: '{{ __("Sports Performance") }}'
        },

        activityLevelLabels: {
            sedentary: '{{ __("Sedentary") }}',
            lightly_active: '{{ __("Lightly Active") }}',
            moderately_active: '{{ __("Moderately Active") }}',
            very_active: '{{ __("Very Active") }}',
            athlete: '{{ __("Athlete") }}'
        },

        dietaryOptions: [],
        allergyOptions: [],

        async init() {
            await this.loadHealthOptions();

            this.preferences = this.preferences.map(pref => ({
                meal_category_id: pref.meal_category_id,
                category_name: pref.category_name || '{{ __("Meal") }}',
                place_type: pref.place_type || '',
                place_name: pref.place_name || '',
                city: pref.city || '',
                delivery_area: pref.delivery_area || '',
                delivery_address: pref.delivery_address || '',
                latitude: pref.latitude ?? null,
                longitude: pref.longitude ?? null,
                preferred_delivery_time:
    pref.preferred_delivery_time ||
    this.getDefaultDeliveryTime(pref.meal_category_id),
                delivery_note: pref.delivery_note || ''
    }));

    if (this.deliveryModalOpen) {
        document.body.classList.add('overflow-hidden');
    }
},

        optionLabel(option) {
            if (!option || typeof option !== 'object') {
                return '';
            }

            if (this.locale === 'ar') {
                return option.label_ar || option.label_en || option.value || '';
            }

            return option.label_en || option.label_ar || option.value || '';
        },

        normalizeHealthOption(option) {
            return {
                id: Number(option?.id || 0),
                value: String(option?.value || '').trim(),
                label: this.optionLabel(option),
                label_en: option?.label_en || '',
                label_ar: option?.label_ar || '',
                description: option?.description || '',
                sort_order: Number(option?.sort_order || 0)
            };
        },

        async loadHealthOptions() {
            this.healthOptionsLoading = true;
            this.healthOptionsError = '';

            try {
                const response = await fetch(this.healthOptionsUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const responseText = await response.text();
                let data = {};

                try {
                    data = responseText ? JSON.parse(responseText) : {};
                } catch (error) {
                    console.error('Health options returned non-JSON:', responseText);
                }

                if (!response.ok || data.success === false) {
                    throw new Error(
                        data.message ||
                        data.error ||
                        '{{ __('Unable to load health profile options.') }}'
                    );
                }

                const payload = data.data || data;

                this.dietaryOptions = (
                    payload.dietary_preferences || []
                ).map(option => this.normalizeHealthOption(option))
                 .filter(option => option.value);

                this.allergyOptions = (
                    payload.allergies || []
                ).map(option => this.normalizeHealthOption(option))
                 .filter(option => option.value);

                this.conditionOptions = (
                    payload.health_conditions || []
                ).map(option => this.normalizeHealthOption(option))
                 .filter(option => option.value);

                /*
                 * Keep saved historical values visible even when an admin
                 * later deactivates or removes an option.
                 */
                this.preserveSelectedOptionValues(
                    'dietary_preferences',
                    this.dietaryOptions
                );
                this.preserveSelectedOptionValues(
                    'allergies',
                    this.allergyOptions
                );
                this.preserveSelectedOptionValues(
                    'chronic_conditions',
                    this.conditionOptions
                );
            } catch (error) {
                console.error(error);
                this.healthOptionsError =
                    error.message ||
                    '{{ __('Unable to load health profile options.') }}';
            } finally {
                this.healthOptionsLoading = false;
            }
        },

        preserveSelectedOptionValues(field, options) {
            const selectedValues = Array.isArray(this.profile[field])
                ? this.profile[field]
                : [];

            selectedValues.forEach(value => {
                const normalizedValue = String(value || '').trim();

                if (
                    normalizedValue &&
                    !options.some(option => option.value === normalizedValue)
                ) {
                    options.push({
                        id: 0,
                        value: normalizedValue,
                        label: normalizedValue.replaceAll('_', ' '),
                        label_en: normalizedValue,
                        label_ar: '',
                        description: '',
                        sort_order: 999999
                    });
                }
            });
        },

        selectGender(gender) {
            this.profile.gender = gender;
            if (window.applyGenderVisualState) {
                window.applyGenderVisualState(gender);
            }
        },

        calculatePlan() {
            if (window.calculateCustomerPlan) {
                window.calculateCustomerPlan();
            }
        },

        get fitnessGoalLabel() {
            return this.goalLabels[this.profile.fitness_goal] || '';
        },

        get activityLevelLabel() {
            return this.activityLevelLabels[this.profile.activity_level] || '';
        },

        get bmi() {
            const heightMeters = Number(this.profile.height_cm) / 100;
            const weight = Number(this.profile.weight_kg);

            if (!heightMeters || !weight || heightMeters <= 0 || weight <= 0) {
                return null;
            }

            return (weight / (heightMeters * heightMeters)).toFixed(1);
        },

        get completedCount() {
            return this.preferences.filter(p => this.isCategoryComplete(p)).length;
        },

        get deliveryProgress() {
            return this.preferences.length ? Math.round(this.completedCount / this.preferences.length * 100) : 0;
        },

        get allDeliveryPreferencesComplete() {
            return this.preferences.length > 0 && this.completedCount === this.preferences.length;
        },

        isSelected(field, value) {
            return Array.isArray(this.profile[field]) && this.profile[field].includes(value);
        },

        toggleArrayValue(field, value) {
            const values = this.profile[field] || [];
            this.profile[field] = values.includes(value) ?
                values.filter(v => v !== value) : [...values, value];
        },

        toggleExclusiveNone(field, value) {
            if (value === 'none') {
                this.profile[field] = this.isSelected(field, 'none') ? [] : ['none'];
                return;
            }
            this.profile[field] = (this.profile[field] || []).filter(v => v !== 'none');
            this.toggleArrayValue(field, value);
        },

        isCategoryComplete(pref) {
            return !!(pref.place_type && pref.city && pref.delivery_area &&
                pref.delivery_address && pref.preferred_delivery_time);
        },

        copyLocationToAll(sourcePref) {
            this.deliveryMessage = '';
            this.deliverySuccess = false;

            if (
                !sourcePref.place_type ||
                !sourcePref.city ||
                !sourcePref.delivery_area ||
                !sourcePref.delivery_address
            ) {
                this.deliveryMessage =
                    '{{ __("Please complete the place type, city, delivery area and delivery address before copying.") }}';
                return;
            }

            this.preferences = this.preferences.map(pref => ({
                ...pref,
                place_type: sourcePref.place_type,
                place_name: sourcePref.place_name || '',
                city: sourcePref.city,
                delivery_area: sourcePref.delivery_area,
                delivery_address: sourcePref.delivery_address,
                latitude: sourcePref.latitude ?? null,
                longitude: sourcePref.longitude ?? null,
                delivery_note: sourcePref.delivery_note || '',
                preferred_delivery_time:
                    pref.preferred_delivery_time ||
                    this.getDefaultDeliveryTime(pref.meal_category_id)
            }));

            this.deliverySuccess = true;
            this.deliveryMessage =
                '{{ __("The delivery location has been copied to all meal categories.") }}';
        },

        validateProfile() {
            const errors = [];

            if (!this.profile.age) {
                errors.push('{{ __("Age is required.") }}');
            } else if (Number(this.profile.age) < 18 || Number(this.profile.age) > 100) {
                errors.push('{{ __("Age must be between 18 and 100.") }}');
            }

            if (!this.profile.gender) {
                errors.push('{{ __("Gender is required.") }}');
            }

            if (!this.profile.height_cm) {
                errors.push('{{ __("Height is required.") }}');
            }

            if (!this.profile.weight_kg) {
                errors.push('{{ __("Weight is required.") }}');
            }

            if (!this.profile.fitness_goal) {
                errors.push('{{ __("Select a fitness goal.") }}');
            }

            if (!this.profile.activity_level) {
                errors.push('{{ __("Activity level is required.") }}');
            }

            if (!this.profile.dietary_preferences.length) {
                errors.push('{{ __("Select at least one dietary preference.") }}');
            }

            if (!this.profile.allergies.length && !this.profile.other_allergy) {
                errors.push('{{ __("Select an allergy or choose None.") }}');
            }

            if (!this.profile.chronic_conditions.length && !this.profile.other_condition) {
                errors.push('{{ __("Select a health condition or choose None.") }}');
            }

            return errors;
        },

        buildProfilePayload() {
            const allergies = [...this.profile.allergies];
            const chronicConditions = [...this.profile.chronic_conditions];

            if (this.profile.other_allergy) {
                allergies.push(this.profile.other_allergy);
            }

            if (this.profile.other_condition) {
                chronicConditions.push(this.profile.other_condition);
            }

            return {
                age: Number(this.profile.age),
                gender: this.profile.gender,
                height_cm: Number(this.profile.height_cm),
                weight_kg: Number(this.profile.weight_kg),
                fitness_goal: this.profile.fitness_goal,
                activity_level: this.profile.activity_level,
                dietary_preferences: this.profile.dietary_preferences,
                allergies: allergies,
                chronic_conditions: chronicConditions,
                foods_to_avoid: this.profile.foods_to_avoid || null,
                health_notes: this.profile.health_notes || null
            };
        },

        async saveProfile() {
            this.profileErrors = this.validateProfile();
            this.profileMessage = '';
            this.profileSuccess = false;

            if (this.profileErrors.length) {
                this.profileMessage = '{{ __('Please correct the information below.') }}';
                return;
            }

            this.savingProfile = true;

            try {
                const r = await fetch('{{ route("user.onboarding.health-profile") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.buildProfilePayload())
                    });

                const responseText = await r.text();
                let d = {};

                try {
                    d = responseText ? JSON.parse(responseText) : {};
                } catch (parseError) {
                    console.error('Health profile returned a non-JSON response:', responseText);
                }

                console.log('Health profile status:', r.status);
                console.log('Health profile response:', d);

                if (!r.ok || d.success === false) {
                    this.profileMessage =
                        d.error ||
                        d.message ||
                        (r.status === 422
                            ? '{{ __('Please check the health details and try again.') }}'
                            : '{{ __('Unable to save profile. Server status:') }} ' + r.status);
                    return;
                }

                this.profileSuccess = true;
                this.profileMessage =
                    d.message || '{{ __('Health profile saved. Continue with your delivery preferences.') }}';

                window.location.href = d.redirect ||
                 '{{ route("user.onboarding.delivery-preferences.page", ["step" => "delivery"]) }}';
            } catch (e) {
                console.error(e);
                this.profileMessage = '{{ __('Unable to save profile. Please try again.') }}';
            } finally {
                this.savingProfile = false;
            }
        },

        closeDeliveryModal() {
            if (this.savingDelivery) return;
            this.deliveryModalOpen = false;
            document.body.classList.remove('overflow-hidden');
        },

        async saveDelivery() {
            this.deliveryMessage = '';
            this.deliverySuccess = false;

            if (!this.allDeliveryPreferencesComplete) {
                this.deliveryMessage = '{{ __('Please complete all delivery destinations.') }}';
                return;
            }

            this.savingDelivery = true;

            try {

                const profilePayload = this.buildProfilePayload();

                const payload = {
    delivery_preferences: this.preferences.map(pref => ({
        meal_category_id: Number(pref.meal_category_id),
        place_type: pref.place_type,
        place_name: pref.place_name || null,
        city: pref.city,
        delivery_area: pref.delivery_area,
        delivery_address: pref.delivery_address,
        latitude: pref.latitude || null,
        longitude: pref.longitude || null,
        preferred_delivery_time:
            pref.preferred_delivery_time,
        delivery_note: pref.delivery_note || null
    }))
};

console.log('Delivery payload:', payload);

                const r = await fetch('{{ route("user.onboarding.delivery-preferences") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                const d = await r.json().catch(() => ({}));

                if (!r.ok || d.success === false) {
                    this.deliveryMessage = d.error || d.message || '{{ __('Unable to save delivery preferences.') }}';
                    return;
                }

                this.deliverySuccess = true;
                this.deliveryMessage = d.message || '{{ __('Saved successfully. Redirecting...') }}';

                setTimeout(() => {
                    window.location.href =
                        d.redirect || '{{ route("user.dashboard") }}';
                }, 1200);
            } catch (e) {
                console.error(e);
                this.deliveryMessage = '{{ __('Unable to save delivery preferences.') }}';
            } finally {
                this.savingDelivery = false;
            }
        }
    };
}
</script>

<script>
(function () {
    const goalLabels = {
        weight_loss: @json(__('Weight Loss')),
        weight_maintenance: @json(__('Maintain Weight')),
        muscle_gain: @json(__('Muscle Gain')),
        healthy_eating: @json(__('Healthy Eating')),
        improve_fitness: @json(__('Improve Fitness')),
        sports_performance: @json(__('Sports Performance'))
    };

    const activityLabels = {
        sedentary: @json(__('Sedentary')),
        lightly_active: @json(__('Lightly Active')),
        moderately_active: @json(__('Moderately Active')),
        very_active: @json(__('Very Active')),
        athlete: @json(__('Athlete'))
    };

    function alpineData() {
        const root = document.getElementById('customer-onboarding-root');

        try {
            if (root && window.Alpine && typeof window.Alpine.$data === 'function') {
                return window.Alpine.$data(root);
            }
        } catch (error) {
            console.warn('Unable to read Alpine component data:', error);
        }

        return null;
    }

    window.applyGenderVisualState = function (gender) {
        const male = document.getElementById('gender-male-btn');
        const female = document.getElementById('gender-female-btn');
        const hidden = document.getElementById('selected-gender');
        const selectedText = document.getElementById('selected-gender-text');

        [male, female].forEach(button => {
            if (!button) return;
            button.classList.remove('is-selected');
            button.setAttribute('aria-pressed', 'false');
        });

        const activeButton = gender === 'male' ? male : female;

        if (activeButton) {
            activeButton.classList.add('is-selected');
            activeButton.setAttribute('aria-pressed', 'true');
        }

        if (hidden) {
            hidden.value = gender;
            hidden.dispatchEvent(new Event('input', { bubbles: true }));
            hidden.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (selectedText) {
            const strong = selectedText.querySelector('strong');
            if (strong) {
                strong.textContent = gender === 'male'
                    ? @json(__('Male'))
                    : @json(__('Female'));
            }
            selectedText.style.display = 'block';
        }
    };

    window.selectCustomerGender = function (gender) {
        window.applyGenderVisualState(gender);

        const data = alpineData();
        if (data && data.profile) {
            data.profile.gender = gender;
        }
    };

    window.calculateCustomerPlan = function () {
        const ageInput = document.getElementById('age');
        const weightInput = document.getElementById('weight');
        const heightInput = document.getElementById('height');
        const goalInput = document.getElementById('fitness_goal');
        const activityInput = document.getElementById('activity_level');
        const genderInput = document.getElementById('selected-gender');
        const feedback = document.getElementById('calculator-feedback');
        const results = document.getElementById('health-results');

        const age = Number(ageInput?.value || 0);
        const weight = Number(weightInput?.value || 0);
        const height = Number(heightInput?.value || 0);
        const goal = goalInput?.value || '';
        const activity = activityInput?.value || '';
        const gender = genderInput?.value || '';

        const missing = [];
        if (!age) missing.push(@json(__('Age')));
        if (!weight) missing.push(@json(__('Weight')));
        if (!height) missing.push(@json(__('Height')));
        if (!gender) missing.push(@json(__('Gender')));
        if (!goal) missing.push(@json(__('Goal')));
        if (!activity) missing.push(@json(__('Activity Level')));

        if (missing.length) {
            if (feedback) {
                feedback.className = 'calculator-feedback error';
                feedback.textContent =
                    @json(__('Please complete these fields:')) + ' ' + missing.join(', ');
            }
            return;
        }

        if (age < 18 || age > 100 || weight < 25 || height < 80) {
            if (feedback) {
                feedback.className = 'calculator-feedback error';
                feedback.textContent =
                    @json(__('Please enter valid age, weight, and height values.'));
            }
            return;
        }

        const heightMeters = height / 100;
        const bmi = (weight / (heightMeters * heightMeters)).toFixed(1);

        const values = {
            'result-age': age,
            'result-bmi': bmi,
            'result-weight': weight,
            'result-height': height,
            'result-activity': activityLabels[activity] || activity,
            'result-goal': goalLabels[goal] || goal
        };

        Object.entries(values).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value;
        });

        if (results) {
            results.classList.add('results-filled');
        }

        if (feedback) {
            feedback.className = 'calculator-feedback success';
            feedback.textContent =
                @json(__('Your plan details were calculated successfully and are ready to save.'));
        }

        const data = alpineData();
        if (data && data.profile) {
            data.profile.age = age;
            data.profile.weight_kg = weight;
            data.profile.height_cm = height;
            data.profile.gender = gender;
            data.profile.fitness_goal = goal;
            data.profile.activity_level = activity;
            data.planCalculated = true;
        }

        results?.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        const data = alpineData();
        const initialGender =
            data?.profile?.gender ||
            document.getElementById('selected-gender')?.value;

        if (initialGender) {
            window.applyGenderVisualState(initialGender);
        }
    });
})();
</script>
@endsection
