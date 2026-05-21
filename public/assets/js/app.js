import { initOfferAutocomplete, initOfferContractFilters } from './modules/offer-search.js';

document.addEventListener('DOMContentLoaded', () => {
    initOfferAutocomplete();
    initOfferContractFilters();
});