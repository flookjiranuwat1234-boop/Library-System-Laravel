

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

Alpine.start();
