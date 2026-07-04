<script>
(function () {
    let currentStep = 1;
    const totalSteps = 4;
    const form = document.getElementById('listing-wizard-form');
    if (!form) return;

    const steps = form.querySelectorAll('.wizard-step');
    const backBtn = document.getElementById('wizard-back');
    const nextBtn = document.getElementById('wizard-next');
    const submitBtn = document.getElementById('wizard-submit');
    const indicators = document.querySelectorAll('[data-step-indicator]');
    const stateSelect = document.getElementById('wizard-state-select');
    const citySelect = document.getElementById('wizard-city-select');
    const spacesContainer = document.getElementById('spaces-container');
    const spaceTemplate = document.getElementById('space-card-template');
    const addSpaceBtn = document.getElementById('add-space-btn');

    @php
        $scriptCitiesByState = $wizardCitiesByState ?? $cities
            ->filter(fn ($city) => filled($city->state))
            ->groupBy('state')
            ->map(fn ($group) => $group->map(fn ($city) => ['id' => $city->id, 'name' => $city->name])->values());
    @endphp
    const citiesByState = @json($scriptCitiesByState);
    const initialCityId = @json(old('city_id'));
    let spaceIndex = 0;

    function populateCities(state, selectedCityId = '') {
        if (!stateSelect || !citySelect) return;
        const cities = citiesByState[state] || [];
        citySelect.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = cities.length ? 'Select city' : (state ? 'No cities available' : 'Select a state first');
        citySelect.appendChild(placeholder);
        cities.forEach(function (city) {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            if (String(city.id) === String(selectedCityId)) option.selected = true;
            citySelect.appendChild(option);
        });
        citySelect.disabled = cities.length === 0;
    }

    stateSelect?.addEventListener('change', () => populateCities(stateSelect.value));
    if (stateSelect?.value) populateCities(stateSelect.value, initialCityId);

    function addSpaceCard() {
        if (!spaceTemplate || !spacesContainer) return;
        const html = spaceTemplate.innerHTML
            .replaceAll('__INDEX__', String(spaceIndex))
            .replaceAll('__NUMBER__', String(spaceIndex + 1));
        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        const card = wrap.firstElementChild;
        spacesContainer.appendChild(card);
        spaceIndex++;
        updateSpaceCards();
    }

    function updateSpaceCards() {
        const cards = spacesContainer.querySelectorAll('.space-card');
        cards.forEach((card, i) => {
            card.querySelector('.space-number').textContent = String(i + 1);
            const removeBtn = card.querySelector('.remove-space-btn');
            if (removeBtn) removeBtn.classList.toggle('hidden', cards.length <= 1);
        });
    }

    spacesContainer?.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-space-btn');
        if (!btn) return;
        btn.closest('.space-card')?.remove();
        updateSpaceCards();
    });

    spacesContainer?.addEventListener('change', function (e) {
        const input = e.target.closest('.space-images-input');
        if (!input) return;
        const countEl = input.closest('div')?.querySelector('.space-images-count');
        if (!countEl) return;
        const count = input.files?.length || 0;
        countEl.textContent = count
            ? count + ' photo' + (count === 1 ? '' : 's') + ' selected'
            : '';
    });

    addSpaceBtn?.addEventListener('click', addSpaceCard);
    addSpaceCard();

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
            if (field.disabled) continue;
            if (!field.value.trim()) {
                field.focus();
                field.classList.add('ring-2', 'ring-red-300');
                return false;
            }
            field.classList.remove('ring-2', 'ring-red-300');
        }

        if (step === 3) {
            const cards = spacesContainer.querySelectorAll('.space-card');
            if (!cards.length) {
                alert('Add at least one bookable space.');
                return false;
            }
            for (const card of cards) {
                for (const field of card.querySelectorAll('[data-space-required]')) {
                    if (!field.value.trim()) {
                        field.focus();
                        field.classList.add('ring-2', 'ring-red-300');
                        return false;
                    }
                    field.classList.remove('ring-2', 'ring-red-300');
                }
            }
        }

        return true;
    }

    function populateReview() {
        const stateText = stateSelect?.options[stateSelect.selectedIndex]?.text || '';
        const cityText = citySelect?.options[citySelect.selectedIndex]?.text || '';
        document.getElementById('review-name').textContent = form.querySelector('[name="name"]').value || '—';
        document.getElementById('review-address').textContent = [
            form.querySelector('[name="address"]').value,
            cityText && !cityText.startsWith('Select') ? cityText : '',
            stateText && stateText !== 'Select state' ? stateText : '',
        ].filter(Boolean).join(', ') || '—';
        document.getElementById('review-photos').textContent = selectedFiles.length
            ? selectedFiles.length + ' photo(s)'
            : 'No photos';

        const reviewSpaces = document.getElementById('review-spaces');
        const cards = spacesContainer.querySelectorAll('.space-card');
        if (!cards.length) {
            reviewSpaces.textContent = 'No spaces added';
            return;
        }
        reviewSpaces.innerHTML = '';
        cards.forEach((card, i) => {
            const name = card.querySelector('[name*="[name]"]')?.value || ('Space ' + (i + 1));
            const typeSelect = card.querySelector('[name*="[category_id]"]');
            const type = typeSelect?.options[typeSelect.selectedIndex]?.text || '';
            const price = card.querySelector('[name*="[price]"]')?.value || '0';
            const periodSelect = card.querySelector('[name*="[price_period]"]');
            const period = periodSelect?.options[periodSelect.selectedIndex]?.text || 'Per day';
            const capacity = card.querySelector('[name*="[capacity]"]')?.value || '1';
            const amenities = [...card.querySelectorAll('input[type="checkbox"]:checked')].length;
            const photos = card.querySelector('.space-images-input')?.files?.length || 0;
            const row = document.createElement('p');
            row.className = 'font-inter text-sm';
            row.textContent = `${name} · ${type} · ₦${Number(price).toLocaleString()} ${period.toLowerCase()} · ${capacity} people · ${amenities} amenities · ${photos} photo(s)`;
            reviewSpaces.appendChild(row);
        });
    }

    backBtn.addEventListener('click', () => { if (currentStep > 1) { currentStep--; updateUI(); } });
    nextBtn.addEventListener('click', () => {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) { currentStep++; updateUI(); }
    });

    // Photos
    const imageInput = document.getElementById('image-upload');
    const imagePreview = document.getElementById('image-preview');
    const imageCount = document.getElementById('image-count');
    const imageError = document.getElementById('image-error');
    const imageDropzone = document.getElementById('image-dropzone');
    const imageBrowseBtn = document.getElementById('image-browse-btn');
    const maxImages = 10;
    const maxSize = 2 * 1024 * 1024;
    let selectedFiles = [];

    function showImageError(message) {
        if (!imageError) return;
        imageError.textContent = message;
        imageError.classList.toggle('hidden', !message);
    }

    function syncImageInput() {
        if (!imageInput) return;
        const transfer = new DataTransfer();
        selectedFiles.forEach(file => transfer.items.add(file));
        imageInput.files = transfer.files;
    }

    function renderImagePreviews() {
        if (!imagePreview) return;
        imagePreview.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'relative group rounded-xl overflow-hidden border border-outline-variant/60 bg-surface-container';
            const img = document.createElement('img');
            img.className = 'w-full h-24 object-cover';
            img.alt = file.name;
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; };
            reader.readAsDataURL(file);
            const badge = document.createElement('span');
            badge.className = 'absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-black/60 text-white text-[10px] font-semibold';
            badge.textContent = index === 0 ? 'Cover' : String(index + 1);
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'absolute top-1.5 right-1.5 w-7 h-7 rounded-full bg-black/70 text-white flex items-center justify-center hover:bg-red-600';
            removeBtn.innerHTML = '<span class="material-symbols-outlined text-[16px]">close</span>';
            removeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selectedFiles.splice(index, 1);
                syncImageInput();
                renderImagePreviews();
            });
            item.appendChild(img);
            item.appendChild(badge);
            item.appendChild(removeBtn);
            imagePreview.appendChild(item);
        });
        if (imageCount) imageCount.textContent = selectedFiles.length + ' / ' + maxImages + ' photos';
    }

    function addImageFiles(fileList) {
        const incoming = Array.from(fileList || []);
        const errors = [];
        incoming.forEach(file => {
            if (!file.type.startsWith('image/')) { errors.push(file.name + ' is not an image.'); return; }
            if (file.size > maxSize) { errors.push(file.name + ' is larger than 2MB.'); return; }
            if (selectedFiles.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified)) return;
            if (selectedFiles.length >= maxImages) { errors.push('Maximum ' + maxImages + ' photos.'); return; }
            selectedFiles.push(file);
        });
        syncImageInput();
        renderImagePreviews();
        showImageError(errors[0] || '');
    }

    imageBrowseBtn?.addEventListener('click', e => { e.preventDefault(); e.stopPropagation(); imageInput?.click(); });
    imageDropzone?.addEventListener('click', e => { if (!e.target.closest('button')) imageInput?.click(); });
    imageInput?.addEventListener('change', function () {
        const files = Array.from(this.files || []);
        this.value = '';
        addImageFiles(files);
    });
    ['dragenter', 'dragover'].forEach(evt => imageDropzone?.addEventListener(evt, e => {
        e.preventDefault(); imageDropzone.classList.add('image-dropzone-active');
    }));
    ['dragleave', 'drop'].forEach(evt => imageDropzone?.addEventListener(evt, e => {
        e.preventDefault(); imageDropzone.classList.remove('image-dropzone-active');
    }));
    imageDropzone?.addEventListener('drop', e => addImageFiles(e.dataTransfer?.files));

    updateUI();

    @if($errors->any() || request('add_listing'))
    if (typeof openListingModal === 'function') openListingModal();
    @endif
})();
</script>
