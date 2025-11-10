(function (window, document) {
    'use strict';

    const DEFAULTS = {
        tableSelector: '#dataTable',
        entriesSelector: '#entriesSelect',
        searchInputSelector: '#searchInput',
        clearButtonSelector: '[data-bolopa-clear]',
        toastSelector: '#bolopaToast',
        perPageParam: 'per_page',
        allOptionValue: 'all',
        allOptionReplacement: '1000',
        resetPaginationOnFilter: true,
        resetPaginationOnSort: true,
    };

    const TOAST_COLORS = {
        success: '#28a745',
        info: '#4361ee',
        warning: '#ffc107',
        danger: '#e63946',
        error: '#e63946',
    };

    const sanitizeNumeric = (value) => {
        if (typeof value !== 'string') {
            return value;
        }
        return value
            .replace(/[^0-9,.-]/g, '')
            .replace(/\.(?=\d{3}(?:\D|$))/g, '')
            .replace(/,/g, '.');
    };

    const parseDate = (value) => {
        if (!value) {
            return NaN;
        }

        // Support ISO (YYYY-MM-DD) and localized (DD/MM/YYYY) formats
        const isoMatch = value.match(/^(\d{4})[-/](\d{1,2})[-/](\d{1,2})$/);
        if (isoMatch) {
            const [, year, month, day] = isoMatch;
            return new Date(Number(year), Number(month) - 1, Number(day)).getTime();
        }

        const localMatch = value.match(/^(\d{1,2})[\/-](\d{1,2})[\/-](\d{2,4})$/);
        if (localMatch) {
            const [, day, month, yearRaw] = localMatch;
            const year = yearRaw.length === 2 ? `20${yearRaw}` : yearRaw;
            return new Date(Number(year), Number(month) - 1, Number(day)).getTime();
        }

        const timestamp = Date.parse(value);
        return Number.isNaN(timestamp) ? NaN : timestamp;
    };

    const detectType = (value, explicitType) => {
        if (explicitType) {
            return explicitType;
        }
        if (typeof value !== 'string') {
            return 'string';
        }

        const trimmed = value.trim();
        if (!trimmed) {
            return 'string';
        }

        if (/^\d{1,2}[\/-]\d{1,2}[\/-]\d{2,4}$/.test(trimmed) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(trimmed)) {
            return 'date';
        }

        const numericCandidate = sanitizeNumeric(trimmed);
        if (!Number.isNaN(Number(numericCandidate)) && numericCandidate !== '') {
            return 'numeric';
        }

        return 'string';
    };

    const normalizeValue = (rawValue, type) => {
        if (rawValue == null) {
            return '';
        }

        const value = String(rawValue).trim();
        const inferType = detectType(value, type);

        switch (inferType) {
            case 'numeric': {
                const numeric = Number(sanitizeNumeric(value));
                return Number.isNaN(numeric) ? value.toLowerCase() : numeric;
            }
            case 'date': {
                const timestamp = parseDate(value);
                return Number.isNaN(timestamp) ? value.toLowerCase() : timestamp;
            }
            default:
                return value.toLowerCase();
        }
    };

    const getCellValue = (cell) => {
        if (!cell) {
            return '';
        }
        if (cell.dataset && cell.dataset.sortValue !== undefined) {
            return cell.dataset.sortValue;
        }
        return cell.textContent || cell.innerText || '';
    };

    const resetPaginationQuery = () => {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        window.history.replaceState({}, '', url);
    };

    const updateSortIndicators = (headers, activeHeader, direction) => {
        headers.forEach((header) => {
            header.classList.remove('bolopa-tabel-active');
            const up = header.querySelector('img.bolopa-tabel-sort-up');
            const down = header.querySelector('img.bolopa-tabel-sort-down');
            if (up) up.classList.remove('bolopa-tabel-dominant');
            if (down) down.classList.remove('bolopa-tabel-dominant');
        });

        if (!activeHeader) {
            return;
        }

        activeHeader.classList.add('bolopa-tabel-active');
        const currentUp = activeHeader.querySelector('img.bolopa-tabel-sort-up');
        const currentDown = activeHeader.querySelector('img.bolopa-tabel-sort-down');
        if (currentUp && currentDown) {
            if (direction === 'asc') {
                currentUp.classList.add('bolopa-tabel-dominant');
                currentDown.classList.remove('bolopa-tabel-dominant');
            } else {
                currentDown.classList.add('bolopa-tabel-dominant');
                currentUp.classList.remove('bolopa-tabel-dominant');
            }
        }
    };

    const defaultToast = (message, type = 'info', toastElement) => {
        const element = toastElement || document.querySelector('.bolopa-tabel-toast');
        if (!element) {
            console[type === 'danger' || type === 'error' ? 'error' : 'log'](message);
            return;
        }

        element.textContent = message;
        element.style.background = TOAST_COLORS[type] || TOAST_COLORS.info;
        element.classList.add('bolopa-tabel-show');

        window.clearTimeout(element._bolopaTimeout);
        element._bolopaTimeout = window.setTimeout(() => {
            element.classList.remove('bolopa-tabel-show');
        }, 3000);
    };

    const initBolopaTable = (options = {}) => {
        const config = Object.assign({}, DEFAULTS, options);

        const table = typeof config.tableSelector === 'string'
            ? document.querySelector(config.tableSelector)
            : config.tableSelector;

        if (!table) {
            return;
        }

        const tbody = table.tBodies && table.tBodies[0];
        const headers = Array.from(table.querySelectorAll('thead th[data-sort]'));
        const entriesSelect = config.entriesSelector
            ? document.querySelector(config.entriesSelector)
            : null;
        const searchInput = config.searchInputSelector
            ? document.querySelector(config.searchInputSelector)
            : null;
        const clearButton = config.clearButtonSelector
            ? document.querySelector(config.clearButtonSelector)
            : null;
        const toastElement = config.toastSelector
            ? document.querySelector(config.toastSelector)
            : document.querySelector('.bolopa-tabel-toast');

        const state = { column: null, direction: 'asc' };

        const filterRows = () => {
            if (!searchInput || !tbody) {
                return;
            }
            const searchTerm = searchInput.value.toLowerCase();
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.forEach((row) => {
                const haystack = row.dataset.search
                    ? row.dataset.search.toLowerCase()
                    : row.textContent.toLowerCase();
                row.style.display = haystack.includes(searchTerm) ? '' : 'none';
            });

            if (config.resetPaginationOnFilter) {
                resetPaginationQuery();
            }
        };

        const toggleClearButton = () => {
            if (!searchInput || !clearButton) {
                return;
            }
            clearButton.style.display = searchInput.value ? 'block' : 'none';
        };

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                filterRows();
                toggleClearButton();
            });
        }

        if (clearButton && searchInput) {
            clearButton.addEventListener('click', () => {
                searchInput.value = '';
                filterRows();
                toggleClearButton();
                searchInput.focus();
            });
        }

        if (entriesSelect) {
            entriesSelect.addEventListener('change', () => {
                const value = entriesSelect.value;
                const url = new URL(window.location.href);

                if (value === config.allOptionValue) {
                    url.searchParams.set(config.perPageParam, config.allOptionReplacement);
                } else {
                    url.searchParams.set(config.perPageParam, value);
                }

                url.searchParams.delete('page');
                window.location.href = url.toString();
            });
        }

        headers.forEach((header) => {
            header.addEventListener('click', () => {
                if (!tbody) {
                    return;
                }

                const columnKey = header.dataset.sort;
                const columnIndex = Array.from(header.parentNode.children).indexOf(header);
                const sortType = header.dataset.sortType;

                if (state.column === columnKey) {
                    state.direction = state.direction === 'asc' ? 'desc' : 'asc';
                } else {
                    state.column = columnKey;
                    state.direction = 'asc';
                }

                const rows = Array.from(tbody.querySelectorAll('tr'));
                const visibleRows = rows.filter((row) => !row.dataset.noSort);

                visibleRows.sort((rowA, rowB) => {
                    const cellA = rowA.cells[columnIndex];
                    const cellB = rowB.cells[columnIndex];

                    const valueA = normalizeValue(getCellValue(cellA), sortType);
                    const valueB = normalizeValue(getCellValue(cellB), sortType);

                    if (valueA < valueB) {
                        return state.direction === 'asc' ? -1 : 1;
                    }
                    if (valueA > valueB) {
                        return state.direction === 'asc' ? 1 : -1;
                    }
                    return 0;
                });

                visibleRows.forEach((row) => tbody.appendChild(row));

                updateSortIndicators(headers, header, state.direction);

                if (config.resetPaginationOnSort) {
                    resetPaginationQuery();
                }
            });
        });

        toggleClearButton();
        if (searchInput && searchInput.value) {
            filterRows();
        }

        updateSortIndicators(headers, null, 'asc');

        return {
            filter: filterRows,
            showToast: (message, type = 'info') => defaultToast(message, type, toastElement),
        };
    };

    window.initBolopaTable = initBolopaTable;
    window.bolopaToast = (message, type = 'info', toastSelector) => {
        const toastElement = toastSelector
            ? document.querySelector(toastSelector)
            : document.querySelector('.bolopa-tabel-toast');
        defaultToast(message, type, toastElement);
    };

    // Table scroll shadow indicator
    const initScrollShadow = () => {
        const tables = document.querySelectorAll('.bolopa-tabel-table-responsive');
        
        tables.forEach((table) => {
            const updateShadow = () => {
                const scrollLeft = table.scrollLeft;
                const scrollWidth = table.scrollWidth;
                const clientWidth = table.clientWidth;
                const maxScroll = scrollWidth - clientWidth;

                // Add/remove classes based on scroll position
                if (scrollLeft > 5) {
                    table.classList.add('scrolled-left');
                } else {
                    table.classList.remove('scrolled-left');
                }

                if (scrollLeft < maxScroll - 5) {
                    table.classList.add('scrolled-right');
                } else {
                    table.classList.remove('scrolled-right');
                }
            };

            table.addEventListener('scroll', updateShadow);
            window.addEventListener('resize', updateShadow);
            
            // Initial check
            updateShadow();
        });
    };

    // Auto-init scroll shadow on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollShadow);
    } else {
        initScrollShadow();
    }
}(window, document));
