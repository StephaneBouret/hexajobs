export function initOfferAutocomplete() {
    const inputs = document.querySelectorAll('[data-autocomplete-input]');

    inputs.forEach((input) => {
        const targetSelector = input.dataset.autocompleteTarget;
        const url = input.dataset.autocompleteUrl;
        const results = document.querySelector(targetSelector);

        if (!results || !url) {
            return;
        }

        let timeout = null;

        input.addEventListener('input', () => {
            clearTimeout(timeout);

            const query = input.value.trim();

            if (query.length < 2) {
                hideResults(results);
                return;
            }

            timeout = setTimeout(async () => {
                const suggestions = await fetchSuggestions(url, query);
                renderSuggestions(input, results, suggestions);
            }, 250);
        });

        document.addEventListener('click', (event) => {
            if (!input.contains(event.target) && !results.contains(event.target)) {
                hideResults(results);
            }
        });
    });
}

export function initOfferContractFilters() {
    const filters = document.querySelectorAll('[data-contract-filter]');

    filters.forEach((filter) => {
        filter.addEventListener('change', () => {
            const form = filter.closest('form');

            if (form) {
                form.submit();
            }
        });
    });
}

async function fetchSuggestions(url, query) {
    const response = await fetch(`${url}?q=${encodeURIComponent(query)}`, {
        headers: {
            'Accept': 'application/json',
        },
    });

    if (!response.ok) {
        return [];
    }

    return await response.json();
}

function renderSuggestions(input, results, suggestions) {
    results.innerHTML = '';

    if (!Array.isArray(suggestions) || suggestions.length === 0) {
        hideResults(results);
        return;
    }

    suggestions.forEach((suggestion) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'list-group-item list-group-item-action';
        button.textContent = suggestion;

        button.addEventListener('click', () => {
            input.value = suggestion;
            hideResults(results);
        });

        results.appendChild(button);
    });

    results.classList.remove('d-none');
}

function hideResults(results) {
    results.classList.add('d-none');
    results.innerHTML = '';
}