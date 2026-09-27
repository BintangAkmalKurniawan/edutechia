import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Alpine = Alpine;

const AppSwal = Swal.mixin({
    background: '#101b24',
    color: '#e2e8f0',
    confirmButtonColor: '#d89408',
    cancelButtonColor: '#475569',
    customClass: {
        popup: 'edutechia-swal',
    },
});

window.Swal = AppSwal;

Alpine.start();

const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    "'": '&#039;',
    '"': '&quot;',
})[character]);

const messageList = (messages) => `
    <ul style="margin: 0; padding-left: 1.25rem; text-align: left;">
        ${messages.map((message) => `<li style="margin: .4rem 0;">${escapeHtml(message)}</li>`).join('')}
    </ul>
`;

const showValidationErrors = (messages) => AppSwal.fire({
    icon: 'error',
    title: 'Data belum sesuai',
    html: `<p style="margin-bottom: .75rem; text-align: left;">Periksa kembali data berikut:</p>${messageList(messages)}`,
    confirmButtonText: 'Periksa kembali',
});

const fieldName = (field) => {
    if (field.type === 'radio' || field.type === 'checkbox') {
        const legend = field.closest('fieldset')?.querySelector('legend');

        if (legend?.textContent.trim()) {
            return legend.textContent.trim();
        }
    }

    const label = field.labels?.[0]?.textContent.trim();

    return label || field.getAttribute('placeholder') || field.name || 'Kolom ini';
};

const validationMessage = (field) => {
    const name = fieldName(field);

    if (field.validity.valueMissing) {
        return `${name} wajib diisi.`;
    }

    if (field.validity.typeMismatch) {
        return field.type === 'email'
            ? `${name} harus berupa alamat email yang valid.`
            : `${name} harus berupa tautan yang valid.`;
    }

    if (field.validity.tooShort) {
        return `${name} minimal terdiri dari ${field.minLength} karakter.`;
    }

    if (field.validity.tooLong) {
        return `${name} maksimal terdiri dari ${field.maxLength} karakter.`;
    }

    if (field.validity.rangeUnderflow) {
        return `${name} minimal bernilai ${field.min}.`;
    }

    if (field.validity.rangeOverflow) {
        return `${name} maksimal bernilai ${field.max}.`;
    }

    if (field.validity.patternMismatch) {
        return `${name} memiliki format yang tidak sesuai.`;
    }

    return `${name} memiliki nilai yang tidak sesuai.`;
};

const invalidFieldsFor = (form) => Array.from(form.elements)
    .filter((field) => field.willValidate && !field.validity.valid);

const showFlashMessage = () => {
    const payloadElement = document.getElementById('app-alert-data');

    if (!payloadElement) {
        return;
    }

    let payload;

    try {
        payload = JSON.parse(payloadElement.textContent);
    } catch {
        return;
    }

    if (payload.errors?.length) {
        showValidationErrors(payload.errors);
        return;
    }

    if (payload.error) {
        AppSwal.fire({
            icon: 'error',
            title: 'Tindakan gagal',
            text: payload.error,
            confirmButtonText: 'Mengerti',
        });
        return;
    }

    if (payload.success) {
        AppSwal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: payload.success,
            confirmButtonText: 'Tutup',
        });
    }
};

const handleFormSubmit = async (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    if (form.dataset.swalConfirmed === 'true') {
        delete form.dataset.swalConfirmed;
        return;
    }

    const invalidFields = invalidFieldsFor(form);

    if (invalidFields.length) {
        event.preventDefault();

        const messages = [...new Set(invalidFields.map(validationMessage))];
        await showValidationErrors(messages);
        invalidFields[0].focus();
        return;
    }

    const submitter = event.submitter;
    const confirmationSource = submitter?.dataset.confirm ? submitter : form;
    const confirmationMessage = confirmationSource.dataset.confirm;

    if (!confirmationMessage) {
        return;
    }

    if (form.dataset.swalPending === 'true') {
        event.preventDefault();
        return;
    }

    event.preventDefault();
    form.dataset.swalPending = 'true';

    const danger = confirmationSource.dataset.confirmDanger === 'true';
    const result = await AppSwal.fire({
        icon: danger ? 'warning' : 'question',
        title: confirmationSource.dataset.confirmTitle || 'Konfirmasi tindakan',
        text: confirmationMessage,
        showCancelButton: true,
        confirmButtonText: confirmationSource.dataset.confirmButton || 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: danger ? '#dc2626' : '#d89408',
        reverseButtons: true,
        focusCancel: true,
    });

    delete form.dataset.swalPending;

    if (result.isConfirmed) {
        form.dataset.swalConfirmed = 'true';
        form.requestSubmit(submitter || undefined);
    }
};

const initializeAlerts = () => {
    document.querySelectorAll('form').forEach((form) => {
        form.noValidate = true;
    });

    document.addEventListener('submit', handleFormSubmit, true);
    showFlashMessage();
};

const formatRemainingTime = (totalSeconds) => {
    const hours = Math.floor(totalSeconds / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;
    const parts = hours > 0 ? [hours, minutes, seconds] : [minutes, seconds];

    return parts.map((part) => String(part).padStart(2, '0')).join(':');
};

const initializeQuizTimer = (form) => {
    const deadline = Number(form.dataset.quizDeadline);
    const display = form.querySelector('[data-quiz-timer-display]');
    const panel = form.querySelector('[data-quiz-timer-panel]');
    const status = form.querySelector('[data-quiz-timer-status]');
    const autoSubmitted = form.querySelector('[data-auto-submitted]');
    const submitButton = form.querySelector('[data-quiz-submit]');
    const storageKey = form.dataset.quizStorageKey;

    if (!Number.isFinite(deadline) || !display || !autoSubmitted) {
        return;
    }

    if (storageKey) {
        try {
            const savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');

            Object.entries(savedAnswers).forEach(([name, value]) => {
                const option = Array.from(form.elements).find((field) => (
                    field instanceof HTMLInputElement
                    && field.type === 'radio'
                    && field.name === name
                    && field.value === String(value)
                ));

                if (option) {
                    option.checked = true;
                }
            });
        } catch {
            localStorage.removeItem(storageKey);
        }

        form.addEventListener('change', (event) => {
            const field = event.target;

            if (!(field instanceof HTMLInputElement) || field.type !== 'radio' || !field.checked) {
                return;
            }

            let savedAnswers = {};

            try {
                savedAnswers = JSON.parse(localStorage.getItem(storageKey) || '{}');
            } catch {
                // Timpa data lokal yang tidak lagi valid.
            }

            savedAnswers[field.name] = field.value;
            localStorage.setItem(storageKey, JSON.stringify(savedAnswers));
        });
    }

    let timerId;
    let isSubmitting = false;

    const submitAutomatically = () => {
        if (isSubmitting) {
            return;
        }

        isSubmitting = true;
        window.clearInterval(timerId);
        autoSubmitted.value = '1';
        form.removeAttribute('data-confirm');
        form.querySelectorAll('input[type="radio"][required]').forEach((field) => {
            field.required = false;
        });

        if (status) {
            status.textContent = 'Waktu habis. Jawaban sedang dikirim otomatis…';
            status.classList.remove('text-slate-400');
            status.classList.add('text-red-300');
        }

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Mengirim jawaban…';
        }

        HTMLFormElement.prototype.submit.call(form);
    };

    const updateTimer = () => {
        const millisecondsRemaining = deadline - Date.now();
        const secondsRemaining = Math.max(0, Math.ceil(millisecondsRemaining / 1000));

        display.textContent = formatRemainingTime(secondsRemaining);
        display.setAttribute('datetime', `PT${secondsRemaining}S`);

        if (secondsRemaining <= 60 && millisecondsRemaining > 0 && panel) {
            panel.classList.remove('border-brand-400/30');
            panel.classList.add('border-red-400/50');
            display.classList.remove('text-brand-300');
            display.classList.add('text-red-300');
        }

        if (millisecondsRemaining <= 0) {
            display.textContent = '00:00';
            submitAutomatically();
        }
    };

    updateTimer();

    if (!isSubmitting) {
        timerId = window.setInterval(updateTimer, 250);
    }
};

const initializeQuizTimers = () => {
    document.querySelectorAll('form[data-quiz-timer]').forEach(initializeQuizTimer);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initializeAlerts();
        initializeQuizTimers();
    });
} else {
    initializeAlerts();
    initializeQuizTimers();
}
