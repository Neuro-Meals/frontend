@extends('layouts.user')

@section('title', __('Complete Your Profile') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Customer Onboarding'))

@section('content')

<style>[x-cloak]{display:none!important}</style>

<div x-data="customerOnboardingPage()" x-init="init()" class="max-w-4xl mx-auto space-y-5">

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

    <div class="bg-gradient-to-br from-[#173327] to-[#025C5F] rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
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

    <form @submit.prevent="saveProfile()" class="space-y-5">

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Body Information') }}</h3>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('Date of Birth') }} *</label>
                    <input type="date" x-model="profile.birth_date" :max="today" required class="form-input">
                    <p class="text-xs text-gray-500 mt-1" x-show="calculatedAge !== null">
                        {{ __('Age:') }} <strong x-text="calculatedAge"></strong>
                    </p>
                </div>

                <div>
                    <label class="form-label">{{ __('Gender') }} *</label>
                    <select x-model="profile.gender" required class="form-input">
                        <option value="">{{ __('Select gender') }}</option>
                        <option value="male">{{ __('Male') }}</option>
                        <option value="female">{{ __('Female') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                        <option value="prefer_not_to_say">{{ __('Prefer not to say') }}</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">{{ __('Height (cm)') }} *</label>
                    <input type="number" min="80" max="250" step="0.1"
                           x-model.number="profile.height" required class="form-input" placeholder="170">
                </div>

                <div>
                    <label class="form-label">{{ __('Current Weight (kg)') }} *</label>
                    <input type="number" min="25" max="400" step="0.1"
                           x-model.number="profile.weight" required class="form-input" placeholder="75">
                </div>

                <div>
                    <label class="form-label">{{ __('Target Weight (kg)') }}</label>
                    <input type="number" min="25" max="400" step="0.1"
                           x-model.number="profile.target_weight" class="form-input" placeholder="68">
                </div>

                <div>
                    <label class="form-label">{{ __('Target Date') }}</label>
                    <input type="date" x-model="profile.target_date" :min="today" class="form-input">
                </div>

                <div class="sm:col-span-2" x-show="bmi !== null">
                    <div class="rounded-xl bg-[#F5F7EA] border border-[#DDE4B4] px-4 py-3">
                        <p class="text-xs text-gray-500">{{ __('Estimated BMI') }}</p>
                        <p class="text-lg font-bold text-[#173327]" x-text="bmi"></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Goals and Lifestyle') }}</h3>
            </div>

            <div class="p-5 space-y-5">
                <div>
                    <label class="form-label">{{ __('Activity Level') }} *</label>
                    <select x-model="profile.activity_level" required class="form-input">
                        <option value="">{{ __('Select activity level') }}</option>
                        <option value="sedentary">{{ __('Sedentary') }}</option>
                        <option value="lightly_active">{{ __('Lightly Active') }}</option>
                        <option value="moderately_active">{{ __('Moderately Active') }}</option>
                        <option value="very_active">{{ __('Very Active') }}</option>
                        <option value="athlete">{{ __('Athlete') }}</option>
                    </select>
                </div>

                <div>
                    <label class="form-label">{{ __('Fitness Goals') }} *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-3">
                        <template x-for="item in fitnessGoalOptions" :key="item.value">
                            <button type="button"
                                    @click="toggleArrayValue('fitness_goals', item.value)"
                                    class="choice-chip"
                                    :class="isSelected('fitness_goals', item.value) ? 'choice-chip-selected' : ''"
                                    x-text="item.label"></button>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="form-label">{{ __('Meals Per Day') }}</label>
                    <select x-model.number="profile.meals_per_day" class="form-input">
                        <option value="">{{ __('Select') }}</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Dietary Preferences') }}</h3>
            </div>
            <div class="p-5 grid grid-cols-2 sm:grid-cols-3 gap-2">
                <template x-for="item in dietaryOptions" :key="item.value">
                    <button type="button"
                            @click="toggleArrayValue('dietary_preferences', item.value)"
                            class="choice-chip"
                            :class="isSelected('dietary_preferences', item.value) ? 'choice-chip-selected' : ''"
                            x-text="item.label"></button>
                </template>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Food Allergies') }}</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <template x-for="item in allergyOptions" :key="item.value">
                        <button type="button"
                                @click="toggleExclusiveNone('allergies', item.value)"
                                class="choice-chip"
                                :class="isSelected('allergies', item.value) ? 'choice-chip-selected' : ''"
                                x-text="item.label"></button>
                    </template>
                </div>
                <input type="text" x-model.trim="profile.other_allergy" class="form-input"
                       placeholder="{{ __('Other allergy') }}">
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                <h3 class="text-sm font-bold text-gray-800">{{ __('Health Conditions') }}</h3>
            </div>
            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <template x-for="item in conditionOptions" :key="item.value">
                        <button type="button"
                                @click="toggleExclusiveNone('chronic_conditions', item.value)"
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

        <div x-show="profileMessage" x-cloak class="px-4 py-3 rounded-xl"
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

        <button type="submit" :disabled="savingProfile"
                class="w-full px-5 py-3.5 text-sm font-bold rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white disabled:opacity-50">
            <span x-text="savingProfile ? '{{ __('Saving profile...') }}' : '{{ __('Save Profile and Continue') }}'"></span>
        </button>
    </form>

    <div x-show="deliveryModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6">
        <div class="absolute inset-0 bg-gray-950/65 backdrop-blur-sm"></div>

        <div class="relative z-10 bg-gray-50 w-full max-w-4xl max-h-[92vh] rounded-2xl shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-[#173327] to-[#025C5F] text-white px-5 py-4 flex justify-between">
                <div>
                    <p class="text-[10px] uppercase text-white/60">{{ __('Step 2 of 2') }}</p>
                    <h3 class="text-base font-bold">{{ __('Set Your Delivery Destinations') }}</h3>
                </div>
                <button type="button" @click="closeDeliveryModal()" class="w-9 h-9 rounded-xl bg-white/10">×</button>
            </div>

            <div class="overflow-y-auto p-4 sm:p-5 space-y-4">
                <div class="bg-white rounded-xl border p-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-xs font-bold">{{ __('Progress') }}</span>
                        <span class="text-xs font-bold text-[#6E7A25]"
                              x-text="completedCount + ' / ' + preferences.length"></span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-full bg-[#6E7A25] rounded-full"
                             :style="'width:' + deliveryProgress + '%'"></div>
                    </div>
                </div>

                <form id="delivery-form" @submit.prevent="saveDelivery()" class="space-y-4">
                    <template x-for="(pref,index) in preferences" :key="pref.meal_category_id || index">
                        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                            <div class="p-4 border-b bg-gray-50 flex justify-between">
                                <h4 class="text-sm font-bold" x-text="pref.category_name"></h4>
                                <span class="text-xs"
                                      :class="isCategoryComplete(pref) ? 'text-green-600' : 'text-amber-500'"
                                      x-text="isCategoryComplete(pref) ? '{{ __('Completed') }}' : '{{ __('Required') }}'"></span>
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

                                <div>
                                    <label class="form-label">{{ __('Preferred Delivery Time') }} *</label>
                                    <input type="time" x-model="pref.preferred_delivery_time" required class="form-input">
                                </div>

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

                <button type="submit" form="delivery-form"
                        :disabled="savingDelivery || !allDeliveryPreferencesComplete"
                        class="px-5 py-2.5 rounded-xl bg-[#173327] text-white text-sm font-bold disabled:opacity-50">
                    <span x-text="savingDelivery ? '{{ __('Saving...') }}' : '{{ __('Save and Go to Dashboard') }}'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.form-label{display:block;margin-bottom:.35rem;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280}
.form-input{width:100%;border:1px solid #e5e7eb;border-radius:.75rem;padding:.7rem .8rem;background:#fff;font-size:.875rem;outline:none}
.form-input:focus{border-color:#6E7A25;box-shadow:0 0 0 3px rgba(110,122,37,.14)}
.choice-chip{min-height:2.75rem;border:1px solid #e5e7eb;border-radius:.75rem;padding:.6rem .75rem;background:#fff;font-size:.75rem;font-weight:600;color:#4b5563}
.choice-chip-selected{border-color:#6E7A25!important;background:#F1F5D9!important;color:#173327!important}
</style>
@endpush

@push('scripts')
<script>
function customerOnboardingPage() {
    const initialProfile = @json($healthProfileJson ?? []);
    const initialPreferences = @json($deliveryPrefsJson ?? []);

    return {
        savingProfile:false,
        savingDelivery:false,
        profileMessage:'',
        profileSuccess:false,
        profileErrors:[],
        deliveryMessage:'',
        deliverySuccess:false,
        deliveryModalOpen:false,
        today:new Date().toISOString().split('T')[0],

        profile:{
            birth_date:initialProfile.birth_date || '',
            gender:initialProfile.gender || '',
            height:initialProfile.height ?? '',
            weight:initialProfile.weight ?? '',
            target_weight:initialProfile.target_weight ?? '',
            target_date:initialProfile.target_date || '',
            activity_level:initialProfile.activity_level || '',
            fitness_goals:Array.isArray(initialProfile.fitness_goals) ? initialProfile.fitness_goals : [],
            dietary_preferences:Array.isArray(initialProfile.dietary_preferences) ? initialProfile.dietary_preferences : [],
            allergies:Array.isArray(initialProfile.allergies) ? initialProfile.allergies : [],
            chronic_conditions:Array.isArray(initialProfile.chronic_conditions) ? initialProfile.chronic_conditions : [],
            meals_per_day:initialProfile.meals_per_day ?? '',
            other_allergy:'',
            other_condition:'',
            foods_to_avoid:initialProfile.foods_to_avoid || '',
            health_notes:initialProfile.health_notes || ''
        },

        preferences:Array.isArray(initialPreferences) ? initialPreferences : [],

        fitnessGoalOptions:[
            {value:'lose_weight',label:'{{ __('Lose Weight') }}'},
            {value:'maintain_weight',label:'{{ __('Maintain Weight') }}'},
            {value:'gain_weight',label:'{{ __('Gain Weight') }}'},
            {value:'build_muscle',label:'{{ __('Build Muscle') }}'},
            {value:'healthy_lifestyle',label:'{{ __('Healthy Lifestyle') }}'},
            {value:'sports_performance',label:'{{ __('Sports Performance') }}'}
        ],

        dietaryOptions:[
            {value:'halal',label:'{{ __('Halal') }}'},
            {value:'balanced',label:'{{ __('Balanced') }}'},
            {value:'high_protein',label:'{{ __('High Protein') }}'},
            {value:'low_carb',label:'{{ __('Low Carb') }}'},
            {value:'low_fat',label:'{{ __('Low Fat') }}'},
            {value:'keto',label:'{{ __('Keto') }}'},
            {value:'vegetarian',label:'{{ __('Vegetarian') }}'},
            {value:'vegan',label:'{{ __('Vegan') }}'},
            {value:'pescatarian',label:'{{ __('Pescatarian') }}'}
        ],

        allergyOptions:[
            {value:'none',label:'{{ __('None') }}'},
            {value:'milk',label:'{{ __('Milk') }}'},
            {value:'eggs',label:'{{ __('Eggs') }}'},
            {value:'fish',label:'{{ __('Fish') }}'},
            {value:'shellfish',label:'{{ __('Shellfish') }}'},
            {value:'peanuts',label:'{{ __('Peanuts') }}'},
            {value:'tree_nuts',label:'{{ __('Tree Nuts') }}'},
            {value:'soy',label:'{{ __('Soy') }}'},
            {value:'gluten',label:'{{ __('Gluten') }}'}
        ],

        conditionOptions:[
            {value:'none',label:'{{ __('None') }}'},
            {value:'diabetes',label:'{{ __('Diabetes') }}'},
            {value:'hypertension',label:'{{ __('Hypertension') }}'},
            {value:'high_cholesterol',label:'{{ __('High Cholesterol') }}'},
            {value:'heart_disease',label:'{{ __('Heart Disease') }}'},
            {value:'kidney_disease',label:'{{ __('Kidney Disease') }}'},
            {value:'thyroid',label:'{{ __('Thyroid') }}'},
            {value:'pcos',label:'{{ __('PCOS') }}'}
        ],

        init(){
            this.preferences=this.preferences.map(pref=>({
                meal_category_id:pref.meal_category_id,
                category_name:pref.category_name || '{{ __('Meal') }}',
                place_type:pref.place_type || '',
                place_name:pref.place_name || '',
                city:pref.city || '',
                delivery_area:pref.delivery_area || '',
                delivery_address:pref.delivery_address || '',
                latitude:pref.latitude ?? null,
                longitude:pref.longitude ?? null,
                preferred_delivery_time:pref.preferred_delivery_time || '',
                delivery_note:pref.delivery_note || ''
            }));
        },

        get calculatedAge(){
            if(!this.profile.birth_date) return null;
            const dob=new Date(this.profile.birth_date+'T00:00:00');
            const now=new Date();
            let age=now.getFullYear()-dob.getFullYear();
            const m=now.getMonth()-dob.getMonth();
            if(m<0 || (m===0 && now.getDate()<dob.getDate())) age--;
            return age;
        },

        get bmi(){
            const h=Number(this.profile.height)/100;
            const w=Number(this.profile.weight);
            return h>0 && w>0 ? (w/(h*h)).toFixed(1) : null;
        },

        get completedCount(){
            return this.preferences.filter(p=>this.isCategoryComplete(p)).length;
        },

        get deliveryProgress(){
            return this.preferences.length ? Math.round(this.completedCount/this.preferences.length*100) : 0;
        },

        get allDeliveryPreferencesComplete(){
            return this.preferences.length>0 && this.completedCount===this.preferences.length;
        },

        isSelected(field,value){
            return Array.isArray(this.profile[field]) && this.profile[field].includes(value);
        },

        toggleArrayValue(field,value){
            const values=this.profile[field] || [];
            this.profile[field]=values.includes(value)
                ? values.filter(v=>v!==value)
                : [...values,value];
        },

        toggleExclusiveNone(field,value){
            if(value==='none'){
                this.profile[field]=this.isSelected(field,'none') ? [] : ['none'];
                return;
            }
            this.profile[field]=(this.profile[field] || []).filter(v=>v!=='none');
            this.toggleArrayValue(field,value);
        },

        isCategoryComplete(pref){
            return !!(pref.place_type && pref.city && pref.delivery_area &&
                pref.delivery_address && pref.preferred_delivery_time);
        },

        validateProfile(){
            const e=[];
            if(!this.profile.birth_date)e.push('{{ __('Date of birth is required.') }}');
            if(!this.profile.gender)e.push('{{ __('Gender is required.') }}');
            if(!this.profile.height)e.push('{{ __('Height is required.') }}');
            if(!this.profile.weight)e.push('{{ __('Weight is required.') }}');
            if(!this.profile.activity_level)e.push('{{ __('Activity level is required.') }}');
            if(!this.profile.fitness_goals.length)e.push('{{ __('Select a fitness goal.') }}');
            if(!this.profile.dietary_preferences.length)e.push('{{ __('Select a dietary preference.') }}');
            if(!this.profile.allergies.length && !this.profile.other_allergy)e.push('{{ __('Select an allergy or None.') }}');
            if(!this.profile.chronic_conditions.length && !this.profile.other_condition)e.push('{{ __('Select a condition or None.') }}');
            return e;
        },

        buildProfilePayload(){
            const allergies=[...this.profile.allergies];
            const conditions=[...this.profile.chronic_conditions];
            if(this.profile.other_allergy)allergies.push(this.profile.other_allergy);
            if(this.profile.other_condition)conditions.push(this.profile.other_condition);

            return {
                birth_date:this.profile.birth_date,
                gender:this.profile.gender,
                height:Number(this.profile.height),
                weight:Number(this.profile.weight),
                target_weight:this.profile.target_weight ? Number(this.profile.target_weight) : null,
                target_date:this.profile.target_date || null,
                activity_level:this.profile.activity_level,
                fitness_goals:this.profile.fitness_goals,
                dietary_preferences:this.profile.dietary_preferences,
                allergies:allergies,
                chronic_conditions:conditions,
                meals_per_day:this.profile.meals_per_day ? Number(this.profile.meals_per_day) : null,
                foods_to_avoid:this.profile.foods_to_avoid || null,
                health_notes:this.profile.health_notes || null
            };
        },

        async saveProfile(){
            this.profileErrors=this.validateProfile();
            this.profileMessage='';
            this.profileSuccess=false;

            if(this.profileErrors.length){
                this.profileMessage='{{ __('Please correct the information below.') }}';
                return;
            }

            this.savingProfile=true;

            try{
                const r=await fetch('{{ route('user.onboarding.health-profile') }}',{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'Accept':'application/json'
                    },
                    body:JSON.stringify(this.buildProfilePayload())
                });

                const d=await r.json().catch(()=>({}));

                if(!r.ok || d.success===false){
                    this.profileMessage=d.error || d.message || '{{ __('Unable to save profile.') }}';
                    return;
                }

                this.profileSuccess=true;
                this.profileMessage=d.message || '{{ __('Profile saved successfully.') }}';
                this.deliveryModalOpen=true;
                document.body.classList.add('overflow-hidden');
            }catch(e){
                console.error(e);
                this.profileMessage='{{ __('Unable to save profile. Please try again.') }}';
            }finally{
                this.savingProfile=false;
            }
        },

        closeDeliveryModal(){
            if(this.savingDelivery)return;
            this.deliveryModalOpen=false;
            document.body.classList.remove('overflow-hidden');
        },

        async saveDelivery(){
            this.deliveryMessage='';
            this.deliverySuccess=false;

            if(!this.allDeliveryPreferencesComplete){
                this.deliveryMessage='{{ __('Please complete all delivery destinations.') }}';
                return;
            }

            this.savingDelivery=true;

            try{
                const payload={
                    delivery_preferences:this.preferences.map(p=>({
                        meal_category_id:p.meal_category_id,
                        place_type:p.place_type,
                        place_name:p.place_name || null,
                        city:p.city,
                        delivery_area:p.delivery_area,
                        delivery_address:p.delivery_address,
                        latitude:p.latitude || null,
                        longitude:p.longitude || null,
                        preferred_delivery_time:p.preferred_delivery_time,
                        delivery_note:p.delivery_note || null
                    }))
                };

                const r=await fetch('{{ route('user.onboarding.delivery-preferences') }}',{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'Accept':'application/json'
                    },
                    body:JSON.stringify(payload)
                });

                const d=await r.json().catch(()=>({}));

                if(!r.ok || d.success===false){
                    this.deliveryMessage=d.error || d.message || '{{ __('Unable to save delivery preferences.') }}';
                    return;
                }

                this.deliverySuccess=true;
                this.deliveryMessage=d.message || '{{ __('Saved successfully. Redirecting...') }}';

                setTimeout(()=>{
                    window.location.href='{{ route('user.dashboard') }}';
                },1200);
            }catch(e){
                console.error(e);
                this.deliveryMessage='{{ __('Unable to save delivery preferences.') }}';
            }finally{
                this.savingDelivery=false;
            }
        }
    }
}
</script>
@endpush

@endsection
