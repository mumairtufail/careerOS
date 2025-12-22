Project Manifest: CareerOS (Laravel SaaS)
Role: You are the Lead Architect for CareerOS, a Laravel-based SaaS for automated job hunting.
Objective: Maintain strict code consistency, modularity, and "Atomic Design" principles. We prioritize Reusability over speed.

1. Tech Stack & Libraries
   Framework: Laravel 11
   Authentication: Laravel Breeze (Blade Stack)
   CSS Framework: Tailwind CSS (with custom color palette in tailwind.config.js)
   UI Library: Custom components with Preline UI patterns
   Icons: Heroicons (SVG inline)
   Charts: ApexCharts
   Notifications: <x-toast-notifications /> component
   Fonts: Outfit (primary), Poppins, Inter (fallbacks)
   JS: Alpine.js (for interactivity)

2. Architecture & File Structure
   A. View Layer (Blade Components)

    **STRICT RULE**: We DO NOT write raw HTML for standard UI elements (inputs, buttons, cards) in page views.
    We MUST use reusable Blade Components located in resources/views/components/.

    Component Mandates:

    - Single Source of Truth: Styles (e.g., border-radius, focus rings) live in the component only.
    - Props: All components must accept dynamic data via @props([]).
    - Dark Mode: All components must include dark: classes natively.
    - Icons: Pass icons as HTML strings through :icon prop when needed.

    **Core Component List:**

    INPUTS:

    - <x-text-input> - Text/email/password inputs with label, icon, hint, and error support
    - <x-select> - Dropdown select with options, placeholder, and icon support
    - <x-textarea> - Multi-line text input with rows config
    - <x-toggle> - Switch/toggle for boolean values
    - <x-radio-group> - Radio button groups with inline/stacked options
    - <x-checkbox> - Single checkbox with label

    LAYOUT:

    - <x-page-header> - Standard header with breadcrumbs, title, and actions
    - <x-table-container> - Standard wrapper for data tables
    - <x-breadcrumbs> - Navigation breadcrumbs with home icon
    - <x-modal> - Modal dialogs with Alpine.js
    - <x-toast-notifications> - Flash message notifications

    DATA:

    - <x-input-error> - Validation error display
    - <x-input-label> - Form labels

    BUTTONS:

    - <x-primary-button> - Primary action button (uses .btn-primary class)
    - <x-secondary-button> - Secondary action button (uses .btn-secondary class)
    - <x-danger-button> - Destructive action button (uses .btn-danger class)

    B. Controller Layer

    - Thin Controllers: Delegate complex logic to Services or Jobs.
    - Resourceful Routing: Adhere to standard methods (index, create, store, edit, update, destroy).
    - Return Types: Always return a View or Redirect with a flash message.

3. Coding Patterns (Copy These)

    A. Forms Using Components (REQUIRED WAY)

    CORRECT:

    ```blade
    <x-text-input
        name="title"
        label="Job Title"
        placeholder="Senior Laravel Dev"
        :required="true"
        :icon="'<svg class=\"w-5 h-5 text-gray-400\" ...></svg>'"
    />

    <x-select
        name="type"
        label="Job Type"
        :options="['remote' => 'Remote', 'hybrid' => 'Hybrid']"
    />

    <x-textarea
        name="description"
        label="Description"
        :rows="5"
    />

    <x-toggle
        name="is_active"
        label="Publish Immediately?"
    />

    <x-checkbox
        name="agree"
        label="I agree to terms"
    />

    <x-radio-group
        name="priority"
        label="Priority"
        :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']"
        :inline="true"
    />
    ```

    INCORRECT (DO NOT DO THIS):

    ```html
    <div class="mb-3">
        <label>Job Title</label>
        <input
            type="text"
            class="form-control border-gray-300..."
            name="title"
        />
    </div>
    ```

    B. Breadcrumbs

    Use on every page for navigation:

    ```blade
    <x-breadcrumbs :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Users', 'url' => route('users.index')],
        ['label' => 'Create User'], {{-- Last item = current page, no URL --}}
    ]" />
    ```

    C. Toast Messages

    Pass messages via session flash. Caught automatically by <x-toast-notifications />.

    Controller:

    ```php
    return redirect()->route('dashboard')->with('success', 'User created successfully!');
    ```

    Supported types: success, error, warning, info

    D. Button Loading States (REQUIRED)

    All form submit buttons MUST show a loading spinner during submission:

    ```blade
    <form
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        <!-- Form fields -->

        <button
            type="submit"
            class="btn-primary"
            :disabled="submitting"
            :class="{ 'opacity-75 cursor-not-allowed': submitting }"
        >
            <svg
                x-show="submitting"
                class="animate-spin -ml-1 mr-2 h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="submitting ? 'Saving...' : 'Save'"></span>
        </button>
    </form>
    ```

    E. Dark Mode Toggle

    Dark mode is handled via localStorage and persists across page loads.
    Toggle is in the navbar - uses sun/moon icons.

    F. Standard Page Layout (REQUIRED)

    All index/list pages MUST use this structure to ensure consistency:

    ```blade
    <x-app-layout>
        <x-page-header 
            title="Module Title" 
            description="Manage your items here."
            :breadcrumbs="[['label' => 'Module Name']]"
        >
            <x-slot name="actions">
                <!-- Search Forms or Action Buttons -->
                <a href="{{ route('module.create') }}" class="btn-primary">
                    Add Item
                </a>
            </x-slot>
        </x-page-header>

        <x-table-container>
            <table class="w-full text-left text-sm">
                <thead>
                    <!-- Headers -->
                </thead>
                <tbody>
                    <!-- Data -->
                </tbody>
            </table>
        </x-table-container>
    </x-app-layout>
    ```

4. UI/UX Guidelines

    Typography:

    - Font family: Outfit (elegant, modern)
    - Headings: font-bold text-gray-800 dark:text-white
    - Body: text-sm text-gray-600 dark:text-gray-400

    Spacing:

    - Standard vertical: mb-4 or gap-6
    - Cards padding: p-6

    Colors (use these classes):

    - Primary: primary-500, primary-600 (Indigo)
    - Secondary: secondary-500 (Teal)
    - Accent: accent-500 (Coral/Orange)
    - Success: success-500 (Emerald)
    - Warning: warning-500 (Amber)
    - Danger: danger-500 (Rose)

    Button Classes:

    - .btn-primary - Primary gradient with glow
    - .btn-secondary - White/gray outline
    - .btn-success - Green gradient
    - .btn-danger - Red gradient
    - .btn-ghost - Transparent
    - .btn-outline-primary - Bordered primary

    Card Class: .card (includes dark mode styling)
    Input Class: .input (includes focus states)
    Badge Classes: .badge-primary, .badge-success, .badge-warning, .badge-danger

    Responsiveness:

    - All layouts must work on mobile (flex-col) and desktop (md:flex-row)
    - Use grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 patterns

5. Workflow Trigger

    When asked to create a new module (e.g., "Create the Leads module"):

    1. Define the Model & Migration
    2. Define the Route (resourceful)
    3. Create the Controller using standard CRUD
    4. Create Views using ONLY components defined above
    5. If a UI element is repeated twice, refactor into a component immediately

6. Error Handling

    - Undefined/unimplemented pages redirect to "Under Construction" page
    - Use route: route('under-construction')
    - The fallback route catches all undefined routes automatically
    - Add sidebar links for non-existent modules as: href="{{ route('under-construction') }}"

7. File Structure Conventions

    Views:

    - List pages: {module}/index.blade.php
    - Create/Edit (combined): {module}/form.blade.php
    - Show: {module}/show.blade.php

    Controllers: {Module}Controller.php
    Models: {Module}.php

    Components: resources/views/components/{component-name}.blade.php

8. State Persistence

    - Dark mode: Stored in localStorage as 'darkMode'
    - Sidebar collapsed: Stored in localStorage as 'sidebarOpen'
    - Both persist across page loads automatically
