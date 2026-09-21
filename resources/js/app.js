

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('catalogSearch', () => ({
    loading: false,
    controller: null,

    async update() {
        this.controller?.abort();
        this.controller = new AbortController();
        this.loading = true;

        const url = new URL(this.$refs.form.action);
        const formData = new FormData(this.$refs.form);

        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                url.searchParams.set(key, value);
            }
        }

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: this.controller.signal,
            });

            if (!response.ok) {
                throw new Error(`Search failed with status ${response.status}`);
            }

            const documentFragment = new DOMParser().parseFromString(await response.text(), 'text/html');
            const newResults = documentFragment.getElementById('catalog-results');
            const newPagination = documentFragment.getElementById('catalog-pagination');
            const newCount = documentFragment.getElementById('catalog-count');

            if (!newResults || !newPagination || !newCount) {
                throw new Error('Search response is incomplete');
            }

            document.getElementById('catalog-results').replaceWith(newResults);
            document.getElementById('catalog-pagination').replaceWith(newPagination);
            document.getElementById('catalog-count').textContent = newCount.textContent;
            document.getElementById('catalog-total').textContent = newCount.textContent;
            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.assign(url);
            }
        } finally {
            this.loading = false;
        }
    },
}));

Alpine.data('journeyCounter', (targetValue = 0) => ({
    displayValue: 0,
    target: Number(targetValue) || 0,

    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.displayValue = this.target;
            return;
        }

        const duration = 800;
        const startTime = performance.now();
        const startValue = 0;

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            this.displayValue = Math.round(startValue + (this.target - startValue) * easeOut);

            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                this.displayValue = this.target;
            }
        };

        requestAnimationFrame(animate);
    },
}));

Alpine.data('journeyReveal', () => ({
    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        this.$el.classList.add('transition-all', 'duration-500', 'ease-out', 'opacity-0', 'translate-y-4');

        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.$el.classList.remove('opacity-0', 'translate-y-4');
                        this.$el.classList.add('opacity-100', 'translate-y-0');
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.1 }
        );

        observer.observe(this.$el);
    },
}));

Alpine.start();
