<script>
(function () {
    let currentStep = 1;
    const totalSteps = 5;
    const form = document.getElementById('listing-wizard-form');
    if (!form) return;

    const steps = form.querySelectorAll('.wizard-step');
    const backBtn = document.getElementById('wizard-back');
    const nextBtn = document.getElementById('wizard-next');
    const submitBtn = document.getElementById('wizard-submit');
    const indicators = document.querySelectorAll('[data-step-indicator]');

    function updateUI() {
        steps.forEach(s => s.classList.toggle('active', parseInt(s.dataset.step) === currentStep));
        backBtn.classList.toggle('invisible', currentStep === 1);
        nextBtn.classList.toggle('hidden', currentStep === totalSteps);
        submitBtn.classList.toggle('hidden', currentStep !== totalSteps);
        indicators.forEach(el => {
            const n = parseInt(el.dataset.stepIndicator);
            el.classList.remove('done', 'active');
            if (n < currentStep) el.classList.add('done');
            if (n === currentStep) el.classList.add('active');
        });
        if (currentStep === totalSteps) populateReview();
    }

    function validateStep(step) {
        const panel = form.querySelector(`.wizard-step[data-step="${step}"]`);
        for (const field of panel.querySelectorAll('[data-wizard-required]')) {
            if (!field.value.trim()) {
                field.focus();
                field.classList.add('ring-2', 'ring-red-300');
                return false;
            }
            field.classList.remove('ring-2', 'ring-red-300');
        }
        return true;
    }

    function populateReview() {
        const citySelect = form.querySelector('[name="city_id"]');
        const cityText = citySelect.options[citySelect.selectedIndex]?.text || '';
        const amenities = [...form.querySelectorAll('[name="amenities[]"]:checked')].map(cb => cb.closest('label').innerText.trim());
        const files = document.getElementById('image-upload').files;
        document.getElementById('review-name').textContent = form.querySelector('[name="name"]').value || '—';
        document.getElementById('review-address').textContent = [form.querySelector('[name="address"]').value, cityText !== 'Select city' ? cityText : ''].filter(Boolean).join(', ') || '—';
        const price = form.querySelector('[name="price"]').value;
        document.getElementById('review-price').textContent = price ? '₦' + Number(price).toLocaleString() + '/day' : '—';
        document.getElementById('review-amenities').textContent = amenities.length ? amenities.join(', ') : 'None selected';
        document.getElementById('review-photos').textContent = files.length ? files.length + ' photo(s)' : 'No photos';
    }

    backBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateUI(); } });
    nextBtn.addEventListener('click', () => {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) { currentStep++; updateUI(); }
    });

    const imageInput = document.getElementById('image-upload');
    imageInput?.addEventListener('change', function () {
        const preview = document.getElementById('image-preview');
        const imageCount = document.getElementById('image-count');
        const imageError = document.getElementById('image-error');
        preview.innerHTML = '';
        let valid = 0;
        const maxSize = 2 * 1024 * 1024;
        for (let i = 0; i < this.files.length; i++) {
            if (this.files[i].size > maxSize) continue;
            valid++;
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-16 object-cover rounded-lg';
                preview.appendChild(img);
            };
            reader.readAsDataURL(this.files[i]);
        }
        imageCount.textContent = valid ? valid + ' file(s) selected' : '';
        imageError?.classList.add('hidden');
    });

    updateUI();

    @if($errors->any() || request('add_listing'))
    if (typeof openListingModal === 'function') openListingModal();
    @endif
})();
</script>
