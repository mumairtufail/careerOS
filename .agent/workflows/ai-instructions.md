---
description: Read AI instructions for CareerOS Laravel SaaS project
---

# AI Instructions Workflow

This workflow should be triggered automatically when starting any coding task for this project.

## Purpose

The `ai_instructions.md` file contains the project manifest and coding guidelines for CareerOS. It defines:

-   Tech Stack & Libraries (Laravel 11, Breeze, Tailwind, Preline UI, Heroicons, ApexCharts)
-   Architecture & File Structure guidelines
-   Blade Component mandates
-   Coding patterns and examples
-   UI/UX Guidelines

## Steps

// turbo-all

1. Read the AI instructions file:

    ```
    View file: d:\projects\test\ai_instructions.md
    ```

2. Always follow these key principles:

    - Use reusable Blade Components for all UI elements
    - Thin Controllers - delegate complex logic to Services or Jobs
    - All components must include `dark:` classes for dark mode
    - Never write raw HTML for standard UI elements (inputs, buttons, cards)
    - Charts data should be passed from Controller, not queried in View

3. Reference the `resources/views/components/` directory for existing components

## Reference Files

-   Project Manifest: `d:\projects\test\ai_instructions.md`
-   Components: `d:\projects\test\resources\views\components\`
-   Layouts: `d:\projects\test\resources\views\layouts\`
