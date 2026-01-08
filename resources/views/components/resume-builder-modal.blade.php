<div
    x-data="{
        show: false,
        currentStep: 0,
        enhancing: null,
        loading: false,
        showPreview: true,
        previewHtml: '',
        months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        years: [],
        steps: [
            { label: 'Personal Details', valid: false },
            { label: 'Experience', valid: false },
            { label: 'Education', valid: false },
            { label: 'Finalize', valid: true }
        ],
        formData: {
            template: 'minimal',
            personal: {
                full_name: '',
                professional_title: '',
                email: '',
                phone: '',
                city: '',
                state_country: '',
                linkedin_url: '',
                portfolio_url: '',
                professional_summary: ''
            },
            skills_text: '',
            experience: [],
            education: [],
            certifications_text: '',
            projects_text: ''
        },
        
        availableTemplates: [
            { id: 'minimal', name: 'Standard Professional' },
            { id: 'modern', name: 'Modern Sidebar' }
        ],

        init() {
            // Generate Year Options
            const currentYear = new Date().getFullYear();
            for (let i = currentYear + 10; i >= currentYear - 50; i--) {
                this.years.push(i);
            }

            this.loadFromLocalStorage();
            
            // Defensiveness
            if (!this.formData.template) this.formData.template = 'minimal';
            if (!Array.isArray(this.formData.experience)) this.formData.experience = [];
            if (!Array.isArray(this.formData.education)) this.formData.education = [];
            
            if (this.formData.experience.length === 0) this.addExperience();
            if (this.formData.education.length === 0) this.addEducation();
            
            this.$watch('formData', (value) => {
                 if(this._debounceTimer) clearTimeout(this._debounceTimer);
                 this._debounceTimer = setTimeout(() => {
                     this.saveToLocalStorage();
                     this.updatePreview();
                 }, 800);
            });
        },
        
        open(resumeData = null) {
            this.show = true;
            if (resumeData) {
                try {
                    const parsed = typeof resumeData === 'string' ? JSON.parse(resumeData) : resumeData;
                    this.formData = { ...this.formData, ...parsed };
                    this.formData.experience = parsed.experience || [];
                    this.formData.education = parsed.education || [];
                    this.formData.template = this.formData.template || 'minimal';
                } catch (e) { 
                    console.error('Failed to load resume data', e); 
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Failed to load resume data' } }));
                }
            }
            document.body.classList.add('overflow-y-hidden');
            this.$nextTick(() => this.updatePreview());
        },
        
        close() {
            this.show = false;
            document.body.classList.remove('overflow-y-hidden');
        },
        
        nextStep() {
            if (this.currentStep < this.steps.length - 1) {
                this.currentStep++;
            }
        },
        
        prevStep() {
            if (this.currentStep > 0) this.currentStep--;
        },
        
        addExperience() {
            this.formData.experience.push({
                company: '', position: '', location: '', start_month: '', start_year: '', end_month: '', end_year: '', currently_working: false, description: ''
            });
        },
        
        removeExperience(index) {
            this.formData.experience.splice(index, 1);
        },
        
        addEducation() {
            this.formData.education.push({
                institution: '', degree: '', field: '', location: '', graduation_month: '', graduation_year: '', gpa: ''
            });
        },
        
        removeEducation(index) {
            this.formData.education.splice(index, 1);
        },

        async updatePreview() {
            if (!this.show) return;
            try {
                const response = await fetch('{{ route('resumes.builder.preview') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ data: JSON.stringify(this.formData) })
                });
                if (response.ok) {
                    this.previewHtml = await response.text();
                    const iframe = document.getElementById('resumePreviewFrame');
                    if (iframe && iframe.contentWindow) {
                        iframe.contentWindow.document.open();
                        iframe.contentWindow.document.write(this.previewHtml);
                        iframe.contentWindow.document.close();
                    }
                }
            } catch (error) { console.error(error); }
        },
        
        saveToLocalStorage() {
            localStorage.setItem('resumeBuilderData', JSON.stringify(this.formData));
        },
        
        loadFromLocalStorage() {
            const saved = localStorage.getItem('resumeBuilderData');
            if (saved) {
                try {
                    const savedData = JSON.parse(saved);
                    this.formData = { ...this.formData, ...savedData };
                } catch (e) { console.error(e); }
            }
        },
        
        async enhanceContent(section, fieldKey) {
           const content = fieldKey ? this.formData[fieldKey] : this.formData.personal[section];
            
            if (!content || content.trim().length < 10) {
                 window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'warning', message: 'Enter at least 10 characters to enhance.' } }));
                return;
            }
            this.enhancing = section;
            try {
                const response = await fetch('{{ route('resumes.builder.enhance') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ content: content, section: section })
                });
                const data = await response.json();
                if (data.success) {
                    if (fieldKey) this.formData[fieldKey] = data.enhanced;
                    else this.formData.personal[section] = data.enhanced;
                }
            } catch (e) { console.error(e); } finally { this.enhancing = null; }
        },
        
        async submitForm(action) {
            this.loading = true;
            const formDataToSubmit = new FormData();
            formDataToSubmit.append('_token', document.querySelector('meta[name=csrf-token]').content);
            formDataToSubmit.append('action', action || 'generate');
            formDataToSubmit.append('data', JSON.stringify(this.formData));
            
            try {
                const response = await fetch('{{ route('resumes.builder.store') }}', { method: 'POST', body: formDataToSubmit });
                if (response.ok) {
                    localStorage.removeItem('resumeBuilderData');
                    window.location.href = '{{ route('resumes.index') }}';
                }
            } catch (e) { console.error(e); } finally { this.loading = false; }
        }
    }"
    @open-resume-builder.window="open($event.detail)"
    @keydown.escape.window="show && close()"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-0 md:p-6"
    style="display: none;"
>
    <!-- Lightened Backdrop -->
    <div x-show="show" 
         x-transition.opacity
         class="absolute inset-0 bg-black/40 backdrop-blur-sm"
         @click="close()"></div>

    <!-- Main Container -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         class="relative w-full max-w-[1240px] h-[85vh] bg-white dark:bg-zinc-950 rounded-xl md:rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-zinc-200 dark:border-zinc-800"
    >
        <!-- Left Panel: Form Input Area (Fixed Width) -->
        <div class="w-full md:w-[460px] lg:w-[500px] flex-shrink-0 flex flex-col border-r border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-950 z-20">
            
            <!-- Header -->
            <div class="h-14 flex-shrink-0 flex items-center justify-between px-6 border-b border-zinc-100 dark:border-zinc-800">
                 <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 flex items-center justify-center shadow-lg shadow-zinc-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xs font-black text-zinc-900 dark:text-zinc-50 tracking-tight uppercase">CareerOS</h2>
                        <div class="flex gap-1 mt-0.5">
                             <template x-for="(step, index) in steps" :key="index">
                                <div class="w-1.5 h-1.5 rounded-full transition-colors duration-300"
                                     :class="currentStep === index ? 'bg-zinc-900 dark:bg-white' : 'bg-zinc-200 dark:bg-zinc-800'"></div>
                            </template>
                        </div>
                    </div>
                </div>
                
                 <button @click="close()" class="group p-2 rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors">
                    <svg class="w-5 h-5 text-zinc-400 group-hover:text-zinc-900 dark:group-hover:text-zinc-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <h3 class="text-xl font-bold text-zinc-900 dark:text-white tracking-tight" x-text="steps[currentStep].label"></h3>
                        <p class="text-xs text-zinc-500 mt-1 dark:text-zinc-400">Fill in details to update your resume.</p>
                    </div>

                    <!-- STEP 0: Personal Details -->
                    <div x-show="currentStep === 0" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Full Name</label>
                                <input type="text" x-model="formData.personal.full_name" placeholder="Ex. John Doe" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>

                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Job Title</label>
                                <input type="text" x-model="formData.personal.professional_title" placeholder="Ex. Senior Software Engineer" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Email</label>
                                <input type="email" x-model="formData.personal.email" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Phone</label>
                                <input type="tel" x-model="formData.personal.phone" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>
                            
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">City</label>
                                <input type="text" x-model="formData.personal.city" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Country</label>
                                <input type="text" x-model="formData.personal.state_country" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>

                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">LinkedIn</label>
                                <input type="url" x-model="formData.personal.linkedin_url" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm transition-all focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400">
                            </div>

                            <div class="col-span-2 space-y-1 relative group">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Professional Summary</label>
                                    <button @click="enhanceContent('professional_summary')" 
                                            :disabled="enhancing === 'professional_summary'"
                                            class="text-[10px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-700 flex items-center gap-1 transition-colors bg-purple-50 dark:bg-purple-900/20 px-2 py-0.5 rounded-full">
                                        <span x-show="!enhancing">✨ Enhance</span>
                                        <span x-show="enhancing" class="animate-pulse">Writing...</span>
                                    </button>
                                </div>
                                <textarea x-model="formData.personal.professional_summary" rows="4" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm leading-relaxed resize-none focus:ring-1 focus:ring-zinc-900 focus:border-zinc-400"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 1: Experience -->
                    <div x-show="currentStep === 1" class="space-y-4">
                         <template x-for="(exp, index) in formData.experience" :key="index">
                            <div class="p-4 bg-zinc-50/50 dark:bg-zinc-900/50 rounded-xl border border-zinc-200 dark:border-zinc-800 relative group hover:bg-white dark:hover:bg-zinc-900 hover:shadow-sm transition-all">
                                <button @click="removeExperience(index)" class="absolute top-3 right-3 text-zinc-300 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                
                                <div class="space-y-3">
                                    <div class="grid gap-3">
                                        <input type="text" x-model="exp.position" placeholder="Job Title" class="font-bold text-zinc-900 dark:text-white w-full bg-transparent border-0 border-b border-transparent focus:border-zinc-900 dark:focus:border-white focus:ring-0 px-0 py-1 transition-all placeholder-zinc-300 text-sm">
                                        <input type="text" x-model="exp.company" placeholder="Company Name" class="text-xs font-semibold text-zinc-600 dark:text-zinc-300 w-full bg-transparent border-0 border-b border-transparent focus:border-zinc-900 dark:focus:border-white focus:ring-0 px-0 py-1 transition-all placeholder-zinc-300">
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <!-- Start Date -->
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-zinc-400 uppercase">Start Date</label>
                                            <div class="flex gap-2">
                                                <select x-model="exp.start_month" class="w-full bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] p-1.5 focus:ring-1 focus:ring-zinc-900">
                                                    <option value="">Month</option>
                                                    <template x-for="month in months" :key="month">
                                                        <option :value="month" x-text="month.substring(0,3)"></option>
                                                    </template>
                                                </select>
                                                <select x-model="exp.start_year" class="w-full bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] p-1.5 focus:ring-1 focus:ring-zinc-900">
                                                    <option value="">Year</option>
                                                    <template x-for="year in years" :key="year">
                                                        <option :value="year" x-text="year"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <!-- End Date -->
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-zinc-400 uppercase">End Date</label>
                                            <div class="flex gap-2">
                                                <template x-if="!exp.currently_working">
                                                    <div class="flex gap-2 w-full">
                                                        <select x-model="exp.end_month" class="w-full bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] p-1.5 focus:ring-1 focus:ring-zinc-900">
                                                            <option value="">Month</option>
                                                            <template x-for="month in months" :key="month">
                                                                <option :value="month" x-text="month.substring(0,3)"></option>
                                                            </template>
                                                        </select>
                                                        <select x-model="exp.end_year" class="w-full bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] p-1.5 focus:ring-1 focus:ring-zinc-900">
                                                            <option value="">Year</option>
                                                            <template x-for="year in years" :key="year">
                                                                <option :value="year" x-text="year"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </template>
                                                <template x-if="exp.currently_working">
                                                    <div class="w-full flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] font-medium text-green-600 dark:text-green-400">
                                                        Present
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" x-model="exp.currently_working" :id="'work'+index" class="w-3.5 h-3.5 rounded border-zinc-300 text-zinc-900 focus:ring-black">
                                        <label :for="'work'+index" class="text-[10px] font-medium text-zinc-500">I currently work here</label>
                                    </div>

                                    <textarea x-model="exp.description" rows="3" placeholder="Describe your achievements..." class="w-full text-xs bg-white dark:bg-zinc-800 rounded-lg p-3 border border-zinc-200 dark:border-zinc-700 focus:ring-1 focus:ring-zinc-900 resize-none"></textarea>
                                </div>
                            </div>
                        </template>
                        
                        <button @click="addExperience()" class="w-full py-3 bg-white dark:bg-zinc-900 text-zinc-600 hover:bg-zinc-50 hover:border-zinc-300 dark:hover:bg-zinc-800 text-xs font-bold uppercase tracking-wide flex items-center justify-center gap-2 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 transition-all">
                             + Add Position
                        </button>

                         <div class="pt-6 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                             <div class="flex justify-between items-center">
                                <label class="text-[10px] font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Key Skills</label>
                            </div>
                            <textarea x-model="formData.skills_text" rows="4" class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm resize-none focus:ring-1 focus:ring-zinc-900" placeholder="PHP, Laravel, Docker..."></textarea>
                        </div>
                    </div>

                    <!-- STEP 2: Education -->
                    <div x-show="currentStep === 2" class="space-y-4">
                        <template x-for="(edu, index) in formData.education" :key="index">
                            <div class="p-4 bg-zinc-50/50 dark:bg-zinc-900/50 rounded-xl border border-zinc-200 dark:border-zinc-800 relative group hover:bg-white dark:hover:bg-zinc-900 hover:shadow-sm transition-all">
                                <button @click="removeEducation(index)" class="absolute top-3 right-3 text-zinc-300 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <div class="space-y-3">
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-zinc-400 uppercase">Institution</label>
                                        <input type="text" x-model="edu.institution" class="w-full p-0 border-0 border-b border-zinc-200 dark:border-zinc-700 bg-transparent text-sm font-semibold focus:ring-0 focus:border-zinc-900 transition-colors" placeholder="University Name">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-zinc-400 uppercase">Degree</label>
                                        <input type="text" x-model="edu.degree" class="w-full p-0 border-0 border-b border-zinc-200 dark:border-zinc-700 bg-transparent text-sm focus:ring-0 focus:border-zinc-900 transition-colors" placeholder="Bachelor of Science">
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-[9px] font-bold text-zinc-400 uppercase">Graduation</label>
                                             <div class="flex gap-2">
                                                <select x-model="edu.graduation_month" class="w-full bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] p-1.5 focus:ring-1 focus:ring-zinc-900">
                                                    <option value="">Month</option>
                                                    <template x-for="month in months" :key="month">
                                                        <option :value="month" x-text="month.substring(0,3)"></option>
                                                    </template>
                                                </select>
                                                <select x-model="edu.graduation_year" class="w-full bg-white dark:bg-zinc-800 rounded-md border border-zinc-200 dark:border-zinc-700 text-[11px] p-1.5 focus:ring-1 focus:ring-zinc-900">
                                                    <option value="">Year</option>
                                                    <template x-for="year in years" :key="year">
                                                        <option :value="year" x-text="year"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <button @click="addEducation()" class="w-full py-3 bg-white dark:bg-zinc-900 text-zinc-600 hover:bg-zinc-50 hover:border-zinc-300 dark:hover:bg-zinc-800 text-xs font-bold uppercase tracking-wide flex items-center justify-center gap-2 rounded-xl border border-dashed border-zinc-300 dark:border-zinc-700 transition-all">
                             + Add Education
                        </button>
                    </div>

                    <!-- STEP 3: Finalize -->
                    <div x-show="currentStep === 3" class="flex flex-col items-center justify-center h-full py-10 space-y-6">
                        <div class="w-16 h-16 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center mb-2 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <div class="text-center space-y-2 max-w-xs mx-auto">
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-white">Profile Completed!</h3>
                            <p class="text-xs text-zinc-500 leading-relaxed">Your professional profile is ready. Download it now or save it to your dashboard.</p>
                        </div>
                        
                        <div class="w-full max-w-xs space-y-3 pt-4">
                            <button @click="submitForm('download_pdf')" :disabled="loading" class="w-full py-3 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-xl font-bold text-sm tracking-wide hover:opacity-90 transform active:scale-[0.98] transition-all shadow-xl shadow-zinc-900/10 flex items-center justify-center gap-2">
                                <span x-show="!loading">Download Resume PDF</span>
                                <span x-show="loading" class="flex items-center gap-2">
                                     <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing...
                                </span>
                            </button>
                            <button @click="submitForm('save_draft')" class="w-full py-3 bg-white dark:bg-zinc-950 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 rounded-xl font-bold text-sm tracking-wide hover:bg-zinc-50 dark:hover:bg-zinc-900 transition-all">
                                Save to Dashboard
                            </button>
                        </div>
                    </div>

                </div>

            </div>
             <!-- Footer Navigation -->
            <div class="h-16 flex-shrink-0 flex justify-between items-center px-6 border-t border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-950 z-20">
                <button @click="prevStep()" 
                        x-show="currentStep > 0"
                        class="px-4 py-2 text-xs font-bold text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition-colors">
                    Back
                </button>
                <div x-show="currentStep === 0"></div> 
                
                <button @click="nextStep()" 
                        x-show="currentStep < steps.length - 1"
                        class="px-6 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-lg text-xs font-bold shadow-lg shadow-zinc-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                    Continue &rarr;
                </button>
            </div>
        </div>
        
        <!-- Right Panel: Immersive Preview -->
        <div class="hidden md:flex flex-1 bg-zinc-100/50 dark:bg-[#121212]/50 relative overflow-hidden items-center justify-center p-8">
             
             <!-- Pattern Background -->
             <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 24px 24px;"></div>

            <!-- Floating Pill -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border border-zinc-200/50 dark:border-zinc-800/50 shadow-sm rounded-full px-1.5 py-1 flex items-center gap-1">
                <template x-for="t in availableTemplates" :key="t.id">
                    <button @click="formData.template = t.id; updatePreview()" 
                            class="px-3 py-1.5 rounded-full text-[10px] font-bold transition-all duration-200"
                            :class="formData.template === t.id 
                                ? 'bg-zinc-900 text-white shadow-md' 
                                : 'text-zinc-500 hover:bg-zinc-200/50 dark:hover:bg-zinc-800'">
                        <span x-text="t.name"></span>
                    </button>
                </template>
            </div>

            <!-- The Resume Scale Container -->
            <div class="transform scale-[0.45] xl:scale-[0.55] 2xl:scale-[0.65] origin-center transition-all duration-500 ease-out shadow-[0_50px_100px_-20px_rgba(0,0,0,0.15)] rounded-sm">
                <!-- Using fixed dimensions for A4 to ensure perfect compilation -->
                <iframe id="resumePreviewFrame" class="w-[210mm] h-[297mm] bg-white border-none block"></iframe>
            </div>

        </div>

         <div class="md:hidden absolute bottom-20 left-1/2 -translate-x-1/2 z-30">
            <button @click="openPreviewModal()" class="px-5 py-2.5 bg-zinc-900 text-white rounded-full shadow-xl text-xs font-bold flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Preview Resume
            </button>
        </div>
    </div>
</div>