// Application Form Tab Navigation and Validation
class ApplicationForm {
    constructor() {
        this.currentTab = 0;
        this.tabs = ['personal-info', 'academic-info', 'contact-details', 'family-info', 'review'];
        this.formData = {};
        this.completedTabs = new Set();
        
        this.init();
        this.restoreFormData();
    }

    init() {
        this.bindEvents();
        this.showTab(0);
        this.updateTabStates();
    }

    bindEvents() {
        // Tab click events
        document.querySelectorAll('.tab-button').forEach((tab, index) => {
            tab.addEventListener('click', () => this.switchTab(index));
        });

        // Navigation buttons
        document.getElementById('prevBtn')?.addEventListener('click', () => this.previousTab());
        document.getElementById('nextBtn')?.addEventListener('click', () => this.nextTab());
        document.getElementById('submitBtn')?.addEventListener('click', () => this.submitForm());
        document.getElementById('clearFormBtn')?.addEventListener('click', () => this.clearForm());

        // Same as current address checkbox
        document.getElementById('sameAsCurrent')?.addEventListener('change', (e) => {
            this.toggleSameAsCurrent(e.target.checked);
        });

        // Special needs radio button
        document.querySelectorAll('input[name="has_special_needs"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                this.toggleSpecialNeeds(e.target.value === '1');
            });
        });

        // Primary track change handler for filtering programs
        document.getElementById('primary_track')?.addEventListener('change', (e) => {
            this.filterProgramsByTrack(e.target.value);
        });

        // Real-time validation and auto-save
        document.querySelectorAll('input, select').forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
                this.saveFormData();
            });
            field.addEventListener('change', () => this.saveFormData());
            field.addEventListener('input', () => this.saveFormData());
        });

        // Save form data before page unload
        window.addEventListener('beforeunload', () => {
            this.saveFormData();
        });

        // Check if form was submitted successfully (no errors)
        if (window.location.search.includes('success=1') || document.querySelector('.alert-success')) {
            this.clearFormData();
        }
    }

    switchTab(tabIndex) {
        // Allow going back to completed tabs or current tab
        if (tabIndex <= this.currentTab || this.completedTabs.has(tabIndex)) {
            this.currentTab = tabIndex;
            this.showTab(tabIndex);
            this.updateTabStates();
        }
    }

    showTab(tabIndex) {
        // Hide all tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Show current tab content
        const currentTabContent = document.getElementById(this.tabs[tabIndex]);
        if (currentTabContent) {
            currentTabContent.classList.remove('hidden');
        }

        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach((tab, index) => {
            tab.classList.remove('active');
            if (index === tabIndex) {
                tab.classList.add('active');
            }
        });

        // Update navigation buttons
        this.updateNavigationButtons(tabIndex);
    }

    updateTabStates() {
        document.querySelectorAll('.tab-button').forEach((tab, index) => {
            tab.classList.remove('completed', 'locked');
            
            if (this.completedTabs.has(index)) {
                tab.classList.add('completed');
            } else if (index > this.currentTab) {
                tab.classList.add('locked');
            }
        });
    }

    updateNavigationButtons(tabIndex) {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        // Show/hide buttons based on current tab
        if (prevBtn) {
            prevBtn.style.display = tabIndex === 0 ? 'none' : 'block';
        }

        if (nextBtn) {
            nextBtn.style.display = tabIndex === this.tabs.length - 1 ? 'none' : 'block';
        }

        if (submitBtn) {
            submitBtn.style.display = tabIndex === this.tabs.length - 1 ? 'block' : 'none';
        }

        // Update next button text
        if (nextBtn) {
            const nextTabNames = ['Academic Info', 'Contact Details', 'Family Info', 'Review'];
            nextBtn.innerHTML = `Next: ${nextTabNames[tabIndex]} <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>`;
        }
    }

    previousTab() {
        if (this.currentTab > 0) {
            this.currentTab--;
            this.showTab(this.currentTab);
            this.updateTabStates();
        }
    }

    async nextTab() {
        if (this.validateCurrentTab()) {
            this.completedTabs.add(this.currentTab);
            this.collectFormData();
            
            if (this.currentTab < this.tabs.length - 1) {
                this.currentTab++;
                this.showTab(this.currentTab);
                this.updateTabStates();
                
                // If moving to review tab, populate it
                if (this.currentTab === this.tabs.length - 1) {
                    this.populateReviewTab();
                }
            }
        }
    }

    validateCurrentTab() {
        const currentTabContent = document.getElementById(this.tabs[this.currentTab]);
        const requiredFields = currentTabContent.querySelectorAll('[required]');
        let isValid = true;
        let firstErrorField = null;

        // Clear previous errors
        this.clearErrors();

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
                if (!firstErrorField) {
                    firstErrorField = field;
                }
            }
        });

        // Special validation for current tab
        if (this.currentTab === 0) { // Personal Info
            isValid = this.validatePersonalInfo() && isValid;
        } else if (this.currentTab === 1) { // Academic Info
            isValid = this.validateAcademicInfo() && isValid;
        } else if (this.currentTab === 2) { // Contact Details
            isValid = this.validateContactDetails() && isValid;
        } else if (this.currentTab === 3) { // Family Info
            isValid = this.validateFamilyInfo() && isValid;
        }

        if (!isValid && firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
        }

        // Contact number validation
        if (field.name === 'contact_number' || field.name.includes('contact_number')) {
            if (value && !/^09\d{9}$/.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid 11-digit contact number starting with 09';
            }
        }

        // LRN validation
        if (field.name === 'lrn' && value) {
            if (!/^\d{12}$/.test(value)) {
                isValid = false;
                errorMessage = 'LRN must be exactly 12 digits';
            }
        }

        // Age validation
        if (field.name === 'age' && value) {
            const age = parseInt(value);
            if (age < 1 || age > 100) {
                isValid = false;
                errorMessage = 'Please enter a valid age between 1 and 100';
            }
        }

        this.showFieldError(field, isValid, errorMessage);
        return isValid;
    }

    validatePersonalInfo() {
        let isValid = true;

        // Check if at least one gender is selected
        const genderSelected = document.querySelector('input[name="gender"]:checked');
        if (!genderSelected) {
            this.showError('gender-group', 'Please select a gender');
            isValid = false;
        }

        // Check if at least one 4Ps option is selected
        const fourPsSelected = document.querySelector('input[name="is_4ps_beneficiary"]:checked');
        if (!fourPsSelected) {
            this.showError('fourps-group', 'Please select an option for 4Ps beneficiary');
            isValid = false;
        }

        // Check if at least one IP option is selected
        const ipSelected = document.querySelector('input[name="belongs_to_ip"]:checked');
        if (!ipSelected) {
            this.showError('ip-group', 'Please select an option for Indigenous People');
            isValid = false;
        }

        return isValid;
    }

    validateAcademicInfo() {
        let isValid = true;

        // Check if at least one returning option is selected
        const returningSelected = document.querySelector('input[name="is_returning"]:checked');
        if (!returningSelected) {
            this.showError('returning-group', 'Please select an option for returning/transferring status');
            isValid = false;
        }

        return isValid;
    }

    validateContactDetails() {
        let isValid = true;

        // Validate current address fields (street is not required)
        const currentAddressRequiredFields = ['cur_house_no', 'cur_barangay', 'cur_city', 'cur_province', 'cur_country', 'cur_zip_code'];
        currentAddressRequiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field && field.hasAttribute('required') && !field.value.trim()) {
                this.showFieldError(field, false, 'This field is required');
                isValid = false;
            }
        });

        // Validate permanent address if not same as current (street is not required)
        const sameAsCurrent = document.getElementById('sameAsCurrent')?.checked;
        if (!sameAsCurrent) {
            const permAddressRequiredFields = ['perm_house_no', 'perm_barangay', 'perm_city', 'perm_province', 'perm_country', 'perm_zip_code'];
            permAddressRequiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && field.hasAttribute('required') && !field.value.trim()) {
                    this.showFieldError(field, false, 'This field is required');
                    isValid = false;
                }
            });
        }

        return isValid;
    }

    validateFamilyInfo() {
        let isValid = true;

        // Check if at least one special needs option is selected
        const specialNeedsSelected = document.querySelector('input[name="has_special_needs"]:checked');
        if (!specialNeedsSelected) {
            this.showError('special-needs-group', 'Please select an option for special needs');
            isValid = false;
        }

        // If special needs is Yes, validate checkboxes
        if (specialNeedsSelected && specialNeedsSelected.value === '1') {
            const specialNeedsCheckboxes = document.querySelectorAll('input[name="special_needs[]"]:checked');
            if (specialNeedsCheckboxes.length === 0) {
                this.showError('special-needs-checkboxes', 'Please select at least one special need');
                isValid = false;
            }
        }

        return isValid;
    }

    showFieldError(field, isValid, message) {
        const errorElement = field.parentNode.querySelector('.error-message');
        
        if (isValid) {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
            if (errorElement) {
                errorElement.remove();
            }
        } else {
            field.classList.remove('border-gray-300');
            field.classList.add('border-red-500');
            
            if (!errorElement) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message text-red-500 text-sm mt-1';
                errorDiv.textContent = message;
                field.parentNode.appendChild(errorDiv);
            } else {
                errorElement.textContent = message;
            }
        }
    }

    showError(elementId, message) {
        const element = document.getElementById(elementId);
        if (element) {
            let errorElement = element.querySelector('.error-message');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'error-message text-red-500 text-sm mt-1';
                element.appendChild(errorElement);
            }
            errorElement.textContent = message;
        }
    }

    clearErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
        document.querySelectorAll('.border-red-500').forEach(field => {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-300');
        });
    }

    toggleSameAsCurrent(checked) {
        const permFields = ['perm_house_no', 'perm_street', 'perm_barangay', 'perm_city', 'perm_province', 'perm_country', 'perm_zip_code'];
        const currentFields = ['cur_house_no', 'cur_street', 'cur_barangay', 'cur_city', 'cur_province', 'cur_country', 'cur_zip_code'];

        permFields.forEach((permField, index) => {
            const permInput = document.querySelector(`[name="${permField}"]`);
            const currentInput = document.querySelector(`[name="${currentFields[index]}"]`);
            
            if (permInput && currentInput) {
                if (checked) {
                    permInput.value = currentInput.value;
                    permInput.disabled = true;
                } else {
                    permInput.disabled = false;
                }
            }
        });
    }

    toggleSpecialNeeds(hasSpecialNeeds) {
        const checkboxesContainer = document.getElementById('special-needs-checkboxes');
        if (checkboxesContainer) {
            checkboxesContainer.style.display = hasSpecialNeeds ? 'block' : 'none';
            
            // Clear all checkboxes when "No" is selected
            if (!hasSpecialNeeds) {
                const checkboxes = checkboxesContainer.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }
    }

    filterProgramsByTrack(selectedTrackId) {
        const secondaryTrackSelect = document.getElementById('secondary_track');
        if (!secondaryTrackSelect) return;

        // Get all program options
        const allOptions = secondaryTrackSelect.querySelectorAll('option[data-track]');
        
        // Clear current selection
        secondaryTrackSelect.value = '';
        
        // Show/hide options based on track ID
        allOptions.forEach(option => {
            const trackId = option.getAttribute('data-track');
            
            if (trackId === selectedTrackId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    collectFormData() {
        const form = document.getElementById('applicationForm');
        const formData = new FormData(form);
        
        // Store form data for review tab
        this.formData = {};
        for (let [key, value] of formData.entries()) {
            if (this.formData[key]) {
                if (Array.isArray(this.formData[key])) {
                    this.formData[key].push(value);
                } else {
                    this.formData[key] = [this.formData[key], value];
                }
            } else {
                this.formData[key] = value;
            }
        }
        
        // Handle special needs: if has_special_needs is "0", don't include special_needs array
        if (this.formData.has_special_needs === '0') {
            delete this.formData['special_needs'];
        }
    }

    populateReviewTab() {
        // Populate review tab with collected data
        const reviewFields = {
            'review-full-name': `${this.formData.first_name || ''} ${this.formData.middle_name || ''} ${this.formData.last_name || ''}`.trim(),
            'review-birthdate': this.formData.birthdate || '',
            'review-age': this.formData.age || '',
            'review-gender': this.formData.gender || '',
            'review-place-of-birth': this.formData.place_of_birth || '',
            'review-email': this.formData.email || '',
            'review-contact-number': this.formData.contact_number || '',
            'review-grade-level': this.formData.grade_level || '',
            'review-preferred-schedule': this.formData.preferred_sched || '',
            'review-primary-track': this.getTrackNameById(this.formData.primary_track) || '',
            'review-secondary-track': this.getProgramNameById(this.formData.secondary_track) || '',
            'review-address': `${this.formData.cur_house_no || ''} ${this.formData.cur_street || ''}, ${this.formData.cur_barangay || ''}, ${this.formData.cur_city || ''}, ${this.formData.cur_province || ''}`.trim(),
            'review-guardian': this.getGuardianInfo()
        };

        Object.entries(reviewFields).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value || '[Not provided]';
            }
        });
    }

    getTrackNameById(trackId) {
        if (!trackId) return '';
        const primaryTrackSelect = document.getElementById('primary_track');
        const option = primaryTrackSelect?.querySelector(`option[value="${trackId}"]`);
        return option ? option.textContent : '';
    }

    getProgramNameById(programId) {
        if (!programId) return '';
        const secondaryTrackSelect = document.getElementById('secondary_track');
        const option = secondaryTrackSelect?.querySelector(`option[value="${programId}"]`);
        return option ? option.textContent : '';
    }

    getGuardianInfo() {
        const guardianFields = ['guardian_first_name', 'guardian_last_name', 'guardian_contact_number'];
        const guardianData = guardianFields.map(field => this.formData[field] || '').filter(Boolean);
        
        if (guardianData.length === 0) {
            return 'No guardian information provided';
        }
        
        return `${this.formData.guardian_first_name || ''} ${this.formData.guardian_last_name || ''} - ${this.formData.guardian_contact_number || ''}`.trim();
    }

    saveFormData() {
        const form = document.getElementById('applicationForm');
        const formData = new FormData(form);
        const data = {};
        
        // Convert FormData to object
        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                if (Array.isArray(data[key])) {
                    data[key].push(value);
                } else {
                    data[key] = [data[key], value];
                }
            } else {
                data[key] = value;
            }
        }
        
        // Handle special needs: if has_special_needs is "0", don't include special_needs array
        if (data.has_special_needs === '0') {
            delete data['special_needs'];
        }
        
        // Save to localStorage
        localStorage.setItem('applicationFormData', JSON.stringify(data));
        
        // Show auto-save indicator
        this.showAutoSaveIndicator();
    }

    showAutoSaveIndicator() {
        const indicator = document.getElementById('auto-save-indicator');
        if (indicator) {
            indicator.style.opacity = '1';
            setTimeout(() => {
                indicator.style.opacity = '0';
            }, 2000);
        }
    }

    restoreFormData() {
        const savedData = localStorage.getItem('applicationFormData');
        if (!savedData) return;
        
        try {
            const data = JSON.parse(savedData);
            
            // Restore form fields
            Object.entries(data).forEach(([name, value]) => {
                if (Array.isArray(value)) {
                    // Handle checkboxes and multiple values
                    value.forEach(val => {
                        const field = document.querySelector(`[name="${name}"][value="${val}"]`);
                        if (field) field.checked = true;
                    });
                } else {
                    // Handle single values
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field) {
                        if (field.type === 'radio' || field.type === 'checkbox') {
                            field.checked = field.value === value;
                        } else {
                            field.value = value;
                        }
                    }
                }
            });
            
            // Don't restore tab states automatically - let user progress naturally
            // this.restoreTabStates(data);
            
            // Trigger program filtering if primary track is selected
            if (data.primary_track) {
                this.filterProgramsByTrack(data.primary_track);
            }
            
            // Trigger same as current address if checked
            if (data.sameAsCurrent === 'on' || data.sameAsCurrent === true) {
                this.toggleSameAsCurrent(true);
            }
            
            // Trigger special needs if selected
            if (data.has_special_needs === '1') {
                this.toggleSpecialNeeds(true);
            }
            
        } catch (error) {
            console.error('Error restoring form data:', error);
            localStorage.removeItem('applicationFormData');
        }
    }

    restoreTabStates(data) {
        // Determine which tabs are completed based on data
        const tabCompletion = {
            0: this.isPersonalInfoComplete(data), // Personal Info
            1: this.isAcademicInfoComplete(data), // Academic Info
            2: this.isContactDetailsComplete(data), // Contact Details
            3: this.isFamilyInfoComplete(data), // Family Info
        };
        
        // Mark completed tabs
        Object.entries(tabCompletion).forEach(([tabIndex, isComplete]) => {
            if (isComplete) {
                this.completedTabs.add(parseInt(tabIndex));
            }
        });
        
        // Update tab states
        this.updateTabStates();
    }

    isPersonalInfoComplete(data) {
        const requiredFields = ['first_name', 'last_name', 'birthdate', 'age', 'gender', 'place_of_birth', 'mother_tongue', 'email', 'contact_number', 'is_4ps_beneficiary', 'belongs_to_ip'];
        return requiredFields.every(field => data[field] && data[field].toString().trim() !== '');
    }

    isAcademicInfoComplete(data) {
        const requiredFields = ['grade_level', 'preferred_sched', 'primary_track', 'secondary_track', 'is_returning'];
        return requiredFields.every(field => data[field] && data[field].toString().trim() !== '');
    }

    isContactDetailsComplete(data) {
        const requiredFields = ['cur_house_no', 'cur_barangay', 'cur_city', 'cur_province', 'cur_country', 'cur_zip_code'];
        const currentAddressComplete = requiredFields.every(field => data[field] && data[field].toString().trim() !== '');
        
        if (!currentAddressComplete) return false;
        
        // Check permanent address (either same as current or filled separately)
        if (data.sameAsCurrent === 'on' || data.sameAsCurrent === true) return true;
        
        const permRequiredFields = ['perm_house_no', 'perm_barangay', 'perm_city', 'perm_province', 'perm_country', 'perm_zip_code'];
        return permRequiredFields.every(field => data[field] && data[field].toString().trim() !== '');
    }

    isFamilyInfoComplete(data) {
        const requiredFields = ['father_last_name', 'father_first_name', 'father_contact_number', 'mother_last_name', 'mother_first_name', 'mother_contact_number', 'has_special_needs'];
        return requiredFields.every(field => data[field] && data[field].toString().trim() !== '');
    }

    clearFormData() {
        localStorage.removeItem('applicationFormData');
    }

    clearForm() {
        if (confirm('Are you sure you want to clear all form data? This action cannot be undone.')) {
            // Clear localStorage
            this.clearFormData();
            
            // Reset form
            const form = document.getElementById('applicationForm');
            form.reset();
            
            // Reset tab states
            this.currentTab = 0;
            this.completedTabs.clear();
            this.showTab(0);
            this.updateTabStates();
            
            // Clear any validation errors
            this.clearErrors();
            
            // Reset special states
            this.toggleSameAsCurrent(false);
            this.toggleSpecialNeeds(false);
            
            alert('Form has been cleared successfully.');
        }
    }

    async submitForm() {
        // Validate review tab (checkboxes)
        const certificationChecked = document.getElementById('certification')?.checked;
        const privacyChecked = document.getElementById('privacy')?.checked;

        if (!certificationChecked || !privacyChecked) {
            alert('Please check both certification boxes before submitting');
            return;
        }

        // Final validation of all tabs
        let allValid = true;
        for (let i = 0; i < this.tabs.length - 1; i++) {
            this.currentTab = i;
            if (!this.validateCurrentTab()) {
                allValid = false;
                break;
            }
        }

        if (!allValid) {
            alert('Please complete all required fields before submitting');
            return;
        }

        // Clear saved data before submitting
        this.clearFormData();

        // Submit the form
        const form = document.getElementById('applicationForm');
        form.submit();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ApplicationForm();
});
