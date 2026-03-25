const activeControllers = new WeakMap();

function initRoomDatePickers(scope = document) {
    if (typeof window.flatpickr !== 'function') {
        return;
    }

    scope.querySelectorAll('.room-date-picker').forEach((input) => {
        if (input.dataset.fpInitialized === '1') {
            return;
        }

        const cell = input.closest('td');
        if (!cell) {
            return;
        }

        const trigger = cell.querySelector('.room-date-trigger');
        if (!trigger) {
            return;
        }

        const bookedDates = JSON.parse(input.dataset.bookedDates || '[]');
        const bookedSet = new Set(bookedDates);

        const fp = window.flatpickr(input, {
            dateFormat: 'Y-m-d',
            disable: [() => true],
            defaultDate: bookedDates.length ? bookedDates[0] : null,
            allowInput: false,
            clickOpens: false,
            positionElement: trigger,
            appendTo: document.body,
            static: false,
            onDayCreate: (_, __, ___, dayElem) => {
                const year = dayElem.dateObj.getFullYear();
                const month = String(dayElem.dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dayElem.dateObj.getDate()).padStart(2, '0');
                const key = `${year}-${month}-${day}`;

                if (bookedSet.has(key)) {
                    dayElem.classList.add('booked-day');
                }
                dayElem.style.pointerEvents = 'none';
            },
            onReady: (_, __, instance) => {
                instance.calendarContainer.addEventListener('click', (event) => {
                    event.stopPropagation();
                });
            },
        });

        trigger.addEventListener('click', () => fp.open());
        input.dataset.fpInitialized = '1';
    });
}

function getResponseMode(html) {
    const normalized = html.trimStart().toLowerCase();
    if (normalized.startsWith('<!doctype html') || normalized.startsWith('<html')) {
        return 'full-document';
    }
    return 'partial';
}

async function loadAjaxList(container, url, shouldPushState) {
    const activeController = activeControllers.get(container);
    if (activeController) {
        activeController.abort();
    }

    const controller = new AbortController();
    activeControllers.set(container, controller);
    container.classList.add('is-loading');

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'text/html',
            },
            signal: controller.signal,
        });

        if (!response.ok) {
            throw new Error('Cannot load list');
        }

        if (response.redirected) {
            window.location.href = response.url;
            return;
        }

        const html = await response.text();

        if (getResponseMode(html) === 'full-document') {
            const parser = new DOMParser();
            const nextDoc = parser.parseFromString(html, 'text/html');
            const key = container.dataset.ajaxKey;
            const selector = key ? `.js-ajax-list[data-ajax-key="${key}"]` : '.js-ajax-list';
            const nextContainer = nextDoc.querySelector(selector);

            if (!nextContainer) {
                window.location.href = url;
                return;
            }

            container.innerHTML = nextContainer.innerHTML;
        } else {
            container.innerHTML = html;
        }

        initRoomDatePickers(container);

        if (shouldPushState) {
            window.history.pushState({ ajaxListUrl: url }, '', url);
        }
    } catch (error) {
        if (error.name === 'AbortError') {
            return;
        }
        window.location.href = url;
    } finally {
        if (activeControllers.get(container) === controller) {
            container.classList.remove('is-loading');
            activeControllers.delete(container);
        }
    }
}

document.addEventListener('click', (event) => {
    const link = event.target.closest('.js-ajax-list .pagination a');
    if (!link) {
        return;
    }

    const container = link.closest('.js-ajax-list');
    if (!container) {
        return;
    }

    event.preventDefault();
    loadAjaxList(container, link.href, true);
});

document.addEventListener('click', async (event) => {
    const button = event.target.closest('.js-delete-chuc-vu');
    if (!button) {
        return;
    }

    if (!window.confirm('Bạn chắc chắn muốn xóa?')) {
        return;
    }

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    const alertBox = document.getElementById('actionAlert');

    try {
        const response = await fetch(button.dataset.url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ _method: 'DELETE' }),
        });

        const result = await response.json();

        if (!response.ok) {
            if (alertBox) {
                alertBox.innerHTML = `<div class="alert alert-danger">${result.message ?? 'Xóa thất bại.'}</div>`;
            }
            return;
        }

        if (alertBox) {
            alertBox.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
        }
        button.closest('tr')?.remove();
    } catch {
        if (alertBox) {
            alertBox.innerHTML = '<div class="alert alert-danger">Không thể kết nối máy chủ.</div>';
        }
    }
});

window.addEventListener('popstate', () => {
    const container = document.querySelector('.js-ajax-list[data-ajax-key]');
    if (!container) {
        return;
    }

    loadAjaxList(container, window.location.href, false);
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!form.classList.contains('js-ajax-search')) {
        return;
    }

    event.preventDefault();

    const containerKey = form.dataset.ajaxContainer;
    const container = document.querySelector(`.js-ajax-list[data-ajax-key="${containerKey}"]`);
    if (!container) {
        return;
    }

    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    const url = `${form.action}?${params.toString()}`;

    loadAjaxList(container, url, true);
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('.js-search-reset');
    if (!button) {
        return;
    }

    const form = button.closest('form');
    if (!form) {
        return;
    }

    const containerKey = form.dataset.ajaxContainer;
    const container = document.querySelector(`.js-ajax-list[data-ajax-key="${containerKey}"]`);
    if (!container) {
        return;
    }

    form.reset();
    const url = form.action;
    loadAjaxList(container, url, true);
});

document.addEventListener('DOMContentLoaded', () => {
    initRoomDatePickers(document);
});
